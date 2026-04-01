<section class="offres-section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 class="section-title" style="margin-bottom:.25rem;">Pilotes</h2>
                <p class="section-subtitle"><?= $pager->total ?> pilote(s)</p>
            </div>
            <a href="<?= APP_URL ?>/pilotes/creer" class="btn btn-primary">+ Nouveau pilote</a>
        </div>

        <?php if (empty($pilots)): ?>
            <div class="empty-state">
                <h3>Aucun pilote trouvé</h3>
                <p>Ajoute un pilote pour commencer le suivi des étudiants.</p>
            </div>
        <?php else: ?>
            <div class="simple-grid simple-grid-3">
                <?php foreach ($pilots as $pilot): ?>
                    <article class="info-card">
                        <span class="offre-type alternance">Pilote</span>
                        <h3 class="offre-title" style="margin-top:1rem;font-size:1.3rem;">
                            <?= \Core\View::e($pilot['prenom'] . ' ' . $pilot['nom']) ?>
                        </h3>
                        <div class="info-list" style="margin-top:1rem;">
                            <p><strong>Email :</strong> <?= \Core\View::e($pilot['email']) ?></p>
                        </div>
                        <div class="mini-stats">
                            <div class="mini-stat">
                                <span class="mini-stat-value"><?= (int) ($pilot['nb_etudiants'] ?? 0) ?></span>
                                <span class="mini-stat-label">Étudiants suivis</span>
                            </div>
                        </div>
                        <div class="page-actions" style="margin-top:1.25rem;">
                            <a href="<?= APP_URL ?>/pilotes/<?= $pilot['id_utilisateur'] ?>" class="btn btn-small btn-postuler">Voir</a>
                            <a href="<?= APP_URL ?>/pilotes/<?= $pilot['id_utilisateur'] ?>/modifier" class="btn btn-small btn-secondary">Modifier</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?= $pager->render(APP_URL . '/pilotes') ?>
        <?php endif; ?>
    </div>
</section>
