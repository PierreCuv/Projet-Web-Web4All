<?php

declare(strict_types=1);

namespace Models;

use Core\Model;
use PDO;

class UserModel extends Model
{
    protected string $table      = 'utilisateur';
    protected string $primaryKey = 'id_utilisateur';
    protected array  $fillable   = ['nom', 'prenom', 'email', 'mot_de_passe', 'id_role'];

    public function findByEmail(string $email): ?array
    {
        return $this->queryOne(
            "SELECT u.*, r.libelle AS role,
                    e.id_etudiant, e.promotion, e.cv, e.id_pilote,
                    p.id_pilote AS pilote_id
             FROM utilisateur u
             JOIN role r          ON r.id_role        = u.id_role
             LEFT JOIN etudiant e ON e.id_utilisateur = u.id_utilisateur
             LEFT JOIN pilote p   ON p.id_utilisateur = u.id_utilisateur
             WHERE u.email = :email LIMIT 1",
            [':email' => $email]
        );
    }

    public function findFullById(int $id): ?array
    {
        return $this->queryOne(
            "SELECT u.*, r.libelle AS role,
                    e.id_etudiant, e.promotion, e.cv, e.id_pilote,
                    p.id_pilote AS pilote_id
             FROM utilisateur u
             JOIN role r          ON r.id_role        = u.id_role
             LEFT JOIN etudiant e ON e.id_utilisateur = u.id_utilisateur
             LEFT JOIN pilote p   ON p.id_utilisateur = u.id_utilisateur
             WHERE u.id_utilisateur = :id",
            [':id' => $id]
        );
    }

    public function createWithPassword(array $data): int
    {
        $roleId = $this->getRoleId($data['role']);

        $userId = $this->create([
            'nom'          => $data['nom'],
            'prenom'       => $data['prenom'],
            'email'        => $data['email'],
            'mot_de_passe' => password_hash($data['password'], PASSWORD_BCRYPT),
            'id_role'      => $roleId,
        ]);

        if ($data['role'] === 'etudiant') {
            $this->db->prepare(
                "INSERT INTO etudiant (id_utilisateur, promotion, id_pilote)
                 VALUES (:uid, :promo, :pilot)"
            )->execute([
                ':uid'   => $userId,
                ':promo' => $data['promotion'] ?? null,
                ':pilot' => $data['id_pilote']  ?? null,
            ]);
        } elseif ($data['role'] === 'pilote') {
            $this->db->prepare(
                "INSERT INTO pilote (id_utilisateur) VALUES (:uid)"
            )->execute([':uid' => $userId]);
        }

        return $userId;
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->db->prepare(
            "UPDATE utilisateur SET mot_de_passe = :hash WHERE id_utilisateur = :id"
        )->execute([
            ':hash' => password_hash($newPassword, PASSWORD_BCRYPT),
            ':id'   => $userId,
        ]);
    }

    public function updateUser(int $userId, array $data): bool
    {
        return $this->update($userId, array_intersect_key(
            $data, array_flip(['nom', 'prenom', 'email'])
        ));
    }

    public function updateEtudiant(int $userId, array $data): void
    {
        $this->db->prepare(
            "UPDATE etudiant SET promotion = :promo, id_pilote = :pilot
             WHERE id_utilisateur = :uid"
        )->execute([
            ':promo' => $data['promotion'] ?? null,
            ':pilot' => $data['id_pilote'] ?? null,
            ':uid'   => $userId,
        ]);
    }

    public function findAllStudents(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email,
                       e.id_etudiant, e.promotion, e.id_pilote,
                       pu.nom AS pilote_nom, pu.prenom AS pilote_prenom,
                       COUNT(c.id_candidature) AS nb_candidatures
                FROM utilisateur u
                JOIN etudiant e          ON e.id_utilisateur  = u.id_utilisateur
                LEFT JOIN pilote p       ON p.id_pilote       = e.id_pilote
                LEFT JOIN utilisateur pu ON pu.id_utilisateur = p.id_utilisateur
                LEFT JOIN candidature c  ON c.id_etudiant     = e.id_etudiant
                GROUP BY u.id_utilisateur, e.id_etudiant
                ORDER BY u.nom ASC";
        if ($limit > 0) $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if ($limit > 0) {
            $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findStudentsByPilot(int $piloteId, int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email,
                       e.id_etudiant, e.promotion,
                       COUNT(c.id_candidature) AS nb_candidatures
                FROM utilisateur u
                JOIN etudiant e        ON e.id_utilisateur = u.id_utilisateur
                LEFT JOIN candidature c ON c.id_etudiant   = e.id_etudiant
                WHERE e.id_pilote = :pid
                GROUP BY u.id_utilisateur, e.id_etudiant
                ORDER BY u.nom ASC";
        if ($limit > 0) $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', $piloteId, PDO::PARAM_INT);
        if ($limit > 0) {
            $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchStudents(string $term, int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email,
                       e.id_etudiant, e.promotion,
                       COUNT(c.id_candidature) AS nb_candidatures
                FROM utilisateur u
                JOIN etudiant e        ON e.id_utilisateur = u.id_utilisateur
                LEFT JOIN candidature c ON c.id_etudiant   = e.id_etudiant
                WHERE (u.nom LIKE :t OR u.prenom LIKE :t
                       OR u.email LIKE :t OR e.promotion LIKE :t)
                GROUP BY u.id_utilisateur, e.id_etudiant
                ORDER BY u.nom ASC";
        if ($limit > 0) $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':t', '%' . $term . '%');
        if ($limit > 0) {
            $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllPilots(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email,
                       p.id_pilote,
                       COUNT(e.id_etudiant) AS nb_etudiants
                FROM utilisateur u
                JOIN pilote p        ON p.id_utilisateur = u.id_utilisateur
                LEFT JOIN etudiant e ON e.id_pilote      = p.id_pilote
                GROUP BY u.id_utilisateur, p.id_pilote
                ORDER BY u.nom ASC";
        if ($limit > 0) $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if ($limit > 0) {
            $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countStudents(string $term = '', ?int $piloteId = null): int
    {
        if ($piloteId !== null) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM etudiant WHERE id_pilote = :pid"
            );
            $stmt->execute([':pid' => $piloteId]);
        } elseif ($term !== '') {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM utilisateur u
                 JOIN etudiant e ON e.id_utilisateur = u.id_utilisateur
                 WHERE u.nom LIKE :t OR u.prenom LIKE :t OR u.email LIKE :t"
            );
            $stmt->execute([':t' => '%' . $term . '%']);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) FROM etudiant");
        }
        return (int) $stmt->fetchColumn();
    }

    public function countPilots(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM pilote")->fetchColumn();
    }

    public function deleteUser(int $userId): bool
    {
        return $this->delete($userId);
    }

    public function getRoleId(string $libelle): int
    {
        $result = $this->queryValue(
            "SELECT id_role FROM role WHERE libelle = :lib",
            [':lib' => $libelle]
        );
        if (!$result) throw new \RuntimeException("Rôle inconnu : {$libelle}");
        return (int) $result;
    }

    public function findAllRoles(): array
    {
        return $this->query("SELECT * FROM role ORDER BY id_role");
    }

    public function getPiloteId(int $userId): ?int
    {
        $r = $this->queryValue(
            "SELECT id_pilote FROM pilote WHERE id_utilisateur = :uid",
            [':uid' => $userId]
        );
        return $r ? (int) $r : null;
    }

    public function getEtudiantId(int $userId): ?int
    {
        $r = $this->queryValue(
            "SELECT id_etudiant FROM etudiant WHERE id_utilisateur = :uid",
            [':uid' => $userId]
        );
        return $r ? (int) $r : null;
    }
}
