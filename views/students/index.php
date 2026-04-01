<section class="offres-section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 class="section-title" style="margin-bottom:.25rem;">Étudiants</h2>
                <p class="section-subtitle"><?= $pager->total ?> étudiant(s)</p>
            </div>
            <?php if (\Core\Auth::isStaff()): ?>
                <a href="<?= APP_URL ?>/etudiants/creer" class="btn btn-primary">+ Nouvel étudiant</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="<?= APP_URL ?>/etudiants" style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2rem;">
            <input type="search" name="search" value="<?= \Core\View::e($search) ?>" placeholder="Nom, prénom, email, promotion..."
                   style="flex:1;min-width:220px;padding:.75rem 1rem;border:2px solid var(--light-gray);border-radius:8px;font-family:var(--font-body);">
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if (!empty($search)): ?>
                <a href="<?= APP_URL ?>/etudiants" class="btn btn-secondary">Effacer</a>
            <?php endif; ?>
        </form>

        <?php if (empty($students)): ?>
            <div class="empty-state">
                <h3>Aucun étudiant trouvé</h3>
                <p>La liste est vide pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="simple-grid simple-grid-3">
                <?php foreach ($students as $student): ?>
                    <article class="info-card">
                        <span class="offre-type stage">Étudiant</span>
                        <h3 class="offre-title" style="margin-top:1rem;font-size:1.3rem;">
                            <?= \Core\View::e($student['prenom'] . ' ' . $student['nom']) ?>
                        </h3>
                        <div class="info-list" style="margin-top:1rem;">
                            <p><strong>Email :</strong> <?= \Core\View::e($student['email']) ?></p>
                            <p><strong>Promotion :</strong> <?= \Core\View::e($student['promotion'] ?: 'Non renseignée') ?></p>
                            <?php if (!empty($student['pilote_nom'])): ?>
                                <p><strong>Pilote :</strong> <?= \Core\View::e($student['pilote_prenom'] . ' ' . $student['pilote_nom']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mini-stats">
                            <div class="mini-stat">
                                <span class="mini-stat-value"><?= (int) ($student['nb_candidatures'] ?? 0) ?></span>
                                <span class="mini-stat-label">Candidatures</span>
                            </div>
                        </div>
                        <div class="page-actions" style="margin-top:1.25rem;">
                            <a href="<?= APP_URL ?>/etudiants/<?= $student['id_utilisateur'] ?>" class="btn btn-small btn-postuler">Voir</a>
                            <a href="<?= APP_URL ?>/etudiants/<?= $student['id_utilisateur'] ?>/modifier" class="btn btn-small btn-secondary">Modifier</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?= $pager->render(APP_URL . '/etudiants', array_filter(['search' => $search])) ?>
        <?php endif; ?>
    </div>
</section>
