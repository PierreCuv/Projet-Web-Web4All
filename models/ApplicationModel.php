<?php

declare(strict_types=1);

namespace Models;

use Core\Model;

class ApplicationModel extends Model
{
    protected string $table      = 'candidature';
    protected string $primaryKey = 'id_candidature';
    protected array  $fillable   = [
        'lettre_motivation', 'cv', 'statut',
        'date_candidature', 'id_etudiant', 'id_offre',
    ];

    public function findByEtudiant(int $etudiantId): array
    {
        return $this->query(
            "SELECT c.*,
                    o.titre       AS titre_offre,
                    o.remuneration,
                    e.nom         AS nom_entreprise,
                    e.email       AS email_entreprise
             FROM candidature c
             JOIN offre o      ON o.id_offre      = c.id_offre
             JOIN entreprise e ON e.id_entreprise = o.id_entreprise
             WHERE c.id_etudiant = :eid
             ORDER BY c.date_candidature DESC",
            [':eid' => $etudiantId]
        );
    }

    public function findByPilote(int $piloteId): array
    {
        return $this->query(
            "SELECT c.*,
                    o.titre    AS titre_offre,
                    e.nom      AS nom_entreprise,
                    u.nom      AS etudiant_nom,
                    u.prenom   AS etudiant_prenom,
                    u.email    AS etudiant_email,
                    et.promotion
             FROM candidature c
             JOIN offre o         ON o.id_offre       = c.id_offre
             JOIN entreprise e    ON e.id_entreprise  = o.id_entreprise
             JOIN etudiant et     ON et.id_etudiant   = c.id_etudiant
             JOIN utilisateur u   ON u.id_utilisateur = et.id_utilisateur
             WHERE et.id_pilote = :pid
             ORDER BY c.date_candidature DESC",
            [':pid' => $piloteId]
        );
    }

    public function hasApplied(int $etudiantId, int $offreId): bool
    {
        return (int) $this->queryValue(
            "SELECT COUNT(*) FROM candidature
             WHERE id_etudiant = :eid AND id_offre = :oid",
            [':eid' => $etudiantId, ':oid' => $offreId]
        ) > 0;
    }

    public function updateStatut(int $id, string $statut): bool
    {
        return $this->update($id, ['statut' => $statut]);
    }
}
