<?php

declare(strict_types=1);

namespace Models;

use Core\Model;
use PDO;

class OfferModel extends Model
{
    protected string $table      = 'offre';
    protected string $primaryKey = 'id_offre';
    protected array  $fillable   = [
        'titre', 'description', 'lieu',
        'date_debut', 'date_fin', 'remuneration', 'id_entreprise',
    ];

    public function search(array $filters = [], int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT o.*,
                       e.nom AS nom_entreprise,
                       COUNT(DISTINCT c.id_candidature) AS nb_candidatures,
                       COUNT(DISTINCT w.id_offre)       AS nb_wishlist,
                       GROUP_CONCAT(DISTINCT comp.nom
                           ORDER BY comp.nom SEPARATOR ', ') AS competences
                FROM offre o
                JOIN entreprise e             ON e.id_entreprise    = o.id_entreprise
                LEFT JOIN candidature c       ON c.id_offre         = o.id_offre
                LEFT JOIN wishlist w          ON w.id_offre         = o.id_offre
                LEFT JOIN offre_competence oc ON oc.id_offre        = o.id_offre
                LEFT JOIN competence comp     ON comp.id_competence = oc.id_competence
                WHERE 1=1";

        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (o.titre LIKE :search
                          OR o.description LIKE :search
                          OR e.nom LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['lieu'])) {
            $sql .= " AND o.lieu LIKE :lieu";
            $params[':lieu'] = '%' . $filters['lieu'] . '%';
        }
        if (!empty($filters['id_entreprise'])) {
            $sql .= " AND o.id_entreprise = :eid";
            $params[':eid'] = $filters['id_entreprise'];
        }
        if (!empty($filters['id_competence'])) {
            $sql .= " AND oc.id_competence = :cid";
            $params[':cid'] = $filters['id_competence'];
        }

        $sql .= " GROUP BY o.id_offre ORDER BY o.created_at DESC";
        if ($limit > 0) $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        if ($limit > 0) {
            $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countSearch(array $filters = []): int
    {
        $sql = "SELECT COUNT(DISTINCT o.id_offre)
                FROM offre o
                JOIN entreprise e ON e.id_entreprise = o.id_entreprise
                LEFT JOIN offre_competence oc ON oc.id_offre = o.id_offre
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (o.titre LIKE :search OR e.nom LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['id_competence'])) {
            $sql .= " AND oc.id_competence = :cid";
            $params[':cid'] = $filters['id_competence'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function findWithDetails(int $id): ?array
    {
        $offer = $this->queryOne(
            "SELECT o.*, e.nom AS nom_entreprise,
                    e.email AS email_entreprise,
                    e.telephone AS tel_entreprise,
                    COUNT(DISTINCT c.id_candidature) AS nb_candidatures
             FROM offre o
             JOIN entreprise e       ON e.id_entreprise = o.id_entreprise
             LEFT JOIN candidature c ON c.id_offre      = o.id_offre
             WHERE o.id_offre = :id
             GROUP BY o.id_offre",
            [':id' => $id]
        );

        if ($offer) {
            $offer['competences'] = $this->findCompetences($id);
        }

        return $offer;
    }

    public function findCompetences(int $offerId): array
    {
        return $this->query(
            "SELECT c.* FROM competence c
             JOIN offre_competence oc ON oc.id_competence = c.id_competence
             WHERE oc.id_offre = :id",
            [':id' => $offerId]
        );
    }

    public function syncCompetences(int $offerId, array $competenceIds): void
    {
        $this->db->prepare(
            "DELETE FROM offre_competence WHERE id_offre = :id"
        )->execute([':id' => $offerId]);

        if (empty($competenceIds)) return;

        $stmt = $this->db->prepare(
            "INSERT INTO offre_competence (id_offre, id_competence)
             VALUES (:oid, :cid)"
        );
        foreach ($competenceIds as $cid) {
            $stmt->execute([':oid' => $offerId, ':cid' => (int) $cid]);
        }
    }

    public function getStatistics(): array
    {
        $stats = [];

        $stats['total_offres'] = (int) $this->queryValue(
            "SELECT COUNT(*) FROM offre"
        );

        $stats['moy_candidatures'] = round(
            (float) $this->queryValue(
                "SELECT AVG(cnt) FROM
                 (SELECT COUNT(*) AS cnt FROM candidature
                  GROUP BY id_offre) t"
            ), 1
        );

        $stats['top_wishlist'] = $this->query(
            "SELECT o.id_offre, o.titre,
                    e.nom AS nom_entreprise,
                    COUNT(w.id_offre) AS nb_wishlist
             FROM offre o
             JOIN entreprise e   ON e.id_entreprise = o.id_entreprise
             LEFT JOIN wishlist w ON w.id_offre     = o.id_offre
             GROUP BY o.id_offre
             ORDER BY nb_wishlist DESC LIMIT 5"
        );

        $stats['par_lieu'] = $this->query(
            "SELECT lieu, COUNT(*) AS nb
             FROM offre WHERE lieu IS NOT NULL
             GROUP BY lieu ORDER BY nb DESC LIMIT 8"
        );

        $stats['top_competences'] = $this->query(
            "SELECT c.nom, COUNT(*) AS nb
             FROM competence c
             JOIN offre_competence oc ON oc.id_competence = c.id_competence
             GROUP BY c.id_competence
             ORDER BY nb DESC LIMIT 5"
        );

        return $stats;
    }
}
