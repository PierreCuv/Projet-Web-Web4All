<?php

declare(strict_types=1);

namespace Models;

use Core\Model;
use PDO;

class CompanyModel extends Model
{
    protected string $table      = 'entreprise';
    protected string $primaryKey = 'id_entreprise';
    protected array  $fillable   = ['nom', 'secteur', 'adresse', 'email', 'telephone'];

    public function search(string $term = '', int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT e.*,
                       COUNT(DISTINCT c.id_candidature) AS nb_candidatures,
                       ROUND(AVG(ev.note), 1)           AS note_moyenne,
                       COUNT(DISTINCT ev.id_evaluation) AS nb_evaluations
                FROM entreprise e
                LEFT JOIN offre o        ON o.id_entreprise  = e.id_entreprise
                LEFT JOIN candidature c  ON c.id_offre       = o.id_offre
                LEFT JOIN evaluation ev  ON ev.id_entreprise = e.id_entreprise
                WHERE 1=1";

        $params = [];
        if (!empty($term)) {
            $sql .= " AND (e.nom LIKE :term OR e.secteur LIKE :term
                          OR e.adresse LIKE :term)";
            $params[':term'] = '%' . $term . '%';
        }

        $sql .= " GROUP BY e.id_entreprise ORDER BY e.nom ASC";
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

    public function countSearch(string $term = ''): int
    {
        $sql    = "SELECT COUNT(*) FROM entreprise WHERE 1=1";
        $params = [];
        if (!empty($term)) {
            $sql .= " AND (nom LIKE :term OR secteur LIKE :term
                          OR adresse LIKE :term)";
            $params[':term'] = '%' . $term . '%';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function findWithStats(int $id): ?array
    {
        return $this->queryOne(
            "SELECT e.*,
                    COUNT(DISTINCT c.id_candidature) AS nb_candidatures,
                    ROUND(AVG(ev.note), 1)           AS note_moyenne,
                    COUNT(DISTINCT ev.id_evaluation) AS nb_evaluations
             FROM entreprise e
             LEFT JOIN offre o        ON o.id_entreprise  = e.id_entreprise
             LEFT JOIN candidature c  ON c.id_offre       = o.id_offre
             LEFT JOIN evaluation ev  ON ev.id_entreprise = e.id_entreprise
             WHERE e.id_entreprise = :id
             GROUP BY e.id_entreprise",
            [':id' => $id]
        );
    }

    public function addEvaluation(int $entrepriseId, int $etudiantId,
                                   int $note, string $commentaire = ''): void
    {
        $this->db->prepare(
            "INSERT INTO evaluation
                 (note, commentaire, id_etudiant, id_entreprise)
             VALUES (:note, :comment, :eid, :cid)"
        )->execute([
            ':note'    => $note,
            ':comment' => $commentaire,
            ':eid'     => $etudiantId,
            ':cid'     => $entrepriseId,
        ]);
    }

    public function findEvaluations(int $entrepriseId): array
    {
        return $this->query(
            "SELECT ev.*, u.nom, u.prenom
             FROM evaluation ev
             JOIN etudiant e    ON e.id_etudiant    = ev.id_etudiant
             JOIN utilisateur u ON u.id_utilisateur = e.id_utilisateur
             WHERE ev.id_entreprise = :id
             ORDER BY ev.date_evaluation DESC",
            [':id' => $entrepriseId]
        );
    }
}
