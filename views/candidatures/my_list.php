<section class="offres-section">
    <div class="container">
        <h2 class="section-title">Mes candidatures</h2>
        <p class="section-subtitle">Suivi simple des candidatures envoyées.</p>

        <?php if (empty($applications)): ?>
            <div class="empty-state">
                <h3>Aucune candidature envoyée</h3>
                <p>Tu peux postuler directement depuis la fiche d'une offre.</p>
            </div>
        <?php else: ?>
            <div class="simple-grid simple-grid-2">
                <?php foreach ($applications as $application): ?>
                    <article class="info-card">
                        <div style="display:flex;justify-content:space-between;gap:1rem;align-items:flex-start;flex-wrap:wrap;">
                            <div>
                                <h3 class="offre-title" style="font-size:1.3rem;"><?= \Core\View::e($application['titre_offre']) ?></h3>
                                <p class="offre-company"><?= \Core\View::e($application['nom_entreprise']) ?></p>
                            </div>
                            <span class="status-badge status-badge-<?= \Core\View::e($application['statut']) ?>">
                                <?= \Core\View::e(str_replace('_', ' ', $application['statut'])) ?>
                            </span>
                        </div>

                        <div class="info-list" style="margin-top:1rem;">
                            <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($application['date_candidature'])) ?></p>
                            <?php if (!empty($application['remuneration'])): ?>
                                <p><strong>Rémunération :</strong> <?= number_format((float) $application['remuneration'], 0, ',', ' ') ?> €/mois</p>
                            <?php endif; ?>
                            <?php if (!empty($application['email_entreprise'])): ?>
                                <p><strong>Contact entreprise :</strong> <?= \Core\View::e($application['email_entreprise']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="page-actions" style="margin-top:1.25rem;">
                            <a href="<?= APP_URL ?>/offres/<?= $application['id_offre'] ?>" class="btn btn-small btn-postuler">Voir l'offre</a>
                            <?php if (!empty($application['cv'])): ?>
                                <a href="<?= APP_URL ?>/public/<?= \Core\View::e($application['cv']) ?>" target="_blank" class="btn btn-small btn-secondary">Voir CV</a>
                            <?php endif; ?>
                            <?php if (!empty($application['lettre_motivation'])): ?>
                                <a href="<?= APP_URL ?>/public/<?= \Core\View::e($application['lettre_motivation']) ?>" target="_blank" class="btn btn-small btn-secondary">Voir LM</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
