<?php

declare(strict_types=1);

namespace Models;

use Core\Model;

class WishlistModel extends Model
{
    protected string $table      = 'wishlist';
    protected string $primaryKey = 'id_etudiant';
    protected array  $fillable   = ['id_etudiant', 'id_offre', 'date_ajout'];

    public function findByEtudiant(int $etudiantId): array
    {
        return $this->query(
            "SELECT w.date_ajout,
                    o.id_offre, o.titre, o.remuneration,
                    o.lieu, o.date_debut, o.date_fin,
                    e.nom AS nom_entreprise
             FROM wishlist w
             JOIN offre o      ON o.id_offre      = w.id_offre
             JOIN entreprise e ON e.id_entreprise = o.id_entreprise
             WHERE w.id_etudiant = :eid
             ORDER BY w.date_ajout DESC",
            [':eid' => $etudiantId]
        );
    }

    public function isInWishlist(int $etudiantId, int $offreId): bool
    {
        return (int) $this->queryValue(
            "SELECT COUNT(*) FROM wishlist
             WHERE id_etudiant = :eid AND id_offre = :oid",
            [':eid' => $etudiantId, ':oid' => $offreId]
        ) > 0;
    }

    public function add(int $etudiantId, int $offreId): void
    {
        if ($this->isInWishlist($etudiantId, $offreId)) return;
        $this->db->prepare(
            "INSERT INTO wishlist (id_etudiant, id_offre, date_ajout)
             VALUES (:eid, :oid, NOW())"
        )->execute([':eid' => $etudiantId, ':oid' => $offreId]);
    }

    public function remove(int $etudiantId, int $offreId): void
    {
        $this->db->prepare(
            "DELETE FROM wishlist
             WHERE id_etudiant = :eid AND id_offre = :oid"
        )->execute([':eid' => $etudiantId, ':oid' => $offreId]);
    }
}
