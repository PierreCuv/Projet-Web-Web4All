<section class="offres-section">
    <div class="container">
        <h2 class="section-title">Candidatures des étudiants</h2>
        <p class="section-subtitle">Vue pilote sur les candidatures de ses étudiants.</p>

        <?php if (empty($applications)): ?>
            <div class="empty-state">
                <h3>Aucune candidature à afficher</h3>
                <p>Les candidatures apparaîtront ici une fois envoyées par les étudiants rattachés à ce pilote.</p>
            </div>
        <?php else: ?>
            <div class="simple-grid simple-grid-2">
                <?php foreach ($applications as $application): ?>
                    <article class="info-card">
                        <div style="display:flex;justify-content:space-between;gap:1rem;align-items:flex-start;flex-wrap:wrap;">
                            <div>
                                <h3 class="offre-title" style="font-size:1.25rem;"><?= \Core\View::e($application['titre_offre']) ?></h3>
                                <p class="offre-company"><?= \Core\View::e($application['nom_entreprise']) ?></p>
                            </div>
                            <span class="status-badge status-badge-<?= \Core\View::e($application['statut']) ?>">
                                <?= \Core\View::e(str_replace('_', ' ', $application['statut'])) ?>
                            </span>
                        </div>

                        <div class="contact-info" style="margin-top:1rem;">
                            <h5>Étudiant</h5>
                            <p><strong>Nom :</strong> <?= \Core\View::e($application['etudiant_prenom'] . ' ' . $application['etudiant_nom']) ?></p>
                            <p><strong>Email :</strong> <?= \Core\View::e($application['etudiant_email']) ?></p>
                            <p><strong>Promotion :</strong> <?= \Core\View::e($application['promotion'] ?: 'Non renseignée') ?></p>
                            <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($application['date_candidature'])) ?></p>
                        </div>

                        <div class="page-actions" style="margin-top:1.25rem;">
                            <a href="<?= APP_URL ?>/offres/<?= $application['id_offre'] ?>" class="btn btn-small btn-postuler">Voir l'offre</a>
                            <?php if (!empty($application['cv'])): ?>
                                <a href="<?= APP_URL ?>/public/<?= \Core\View::e($application['cv']) ?>" target="_blank" class="btn btn-small btn-secondary">CV</a>
                            <?php endif; ?>
                            <?php if (!empty($application['lettre_motivation'])): ?>
                                <a href="<?= APP_URL ?>/public/<?= \Core\View::e($application['lettre_motivation']) ?>" target="_blank" class="btn btn-small btn-secondary">LM</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
