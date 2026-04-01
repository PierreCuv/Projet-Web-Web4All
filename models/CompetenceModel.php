<?php

declare(strict_types=1);

namespace Models;

use Core\Model;

class CompetenceModel extends Model
{
    protected string $table      = 'competence';
    protected string $primaryKey = 'id_competence';
    protected array  $fillable   = ['nom'];

    public function findAllSorted(): array
    {
        return $this->query(
            "SELECT * FROM competence ORDER BY nom ASC"
        );
    }

    public function firstOrCreate(string $nom): int
    {
        $existing = $this->findBy(['nom' => trim($nom)]);
        if ($existing) return (int) $existing['id_competence'];
        return $this->create(['nom' => trim($nom)]);
    }
}
