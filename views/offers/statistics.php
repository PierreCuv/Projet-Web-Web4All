<section class="offres-section">
    <div class="container">
        <h2 class="section-title">Statistiques des offres</h2>
        <p class="section-subtitle">Vue simple pour le pilotage du projet.</p>

        <div class="simple-grid simple-grid-2" style="margin-bottom:2rem;">
            <article class="metric-card">
                <span class="metric-label">Nombre total d'offres</span>
                <strong class="metric-value"><?= (int) ($stats['total_offres'] ?? 0) ?></strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Moyenne de candidatures par offre</span>
                <strong class="metric-value"><?= \Core\View::e($stats['moy_candidatures'] ?? 0) ?></strong>
            </article>
        </div>

        <div class="simple-grid simple-grid-3">
            <article class="info-card">
                <h3 style="margin-bottom:1rem;">Top wishlist</h3>
                <?php if (empty($stats['top_wishlist'])): ?>
                    <p>Aucune donnée.</p>
                <?php else: ?>
                    <div class="info-list">
                        <?php foreach ($stats['top_wishlist'] as $item): ?>
                            <p>
                                <strong><?= \Core\View::e($item['titre']) ?></strong><br>
                                <?= \Core\View::e($item['nom_entreprise']) ?> — <?= (int) $item['nb_wishlist'] ?> ajout(s)
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="info-card">
                <h3 style="margin-bottom:1rem;">Répartition par lieu</h3>
                <?php if (empty($stats['par_lieu'])): ?>
                    <p>Aucune donnée.</p>
                <?php else: ?>
                    <div class="info-list">
                        <?php foreach ($stats['par_lieu'] as $item): ?>
                            <p><strong><?= \Core\View::e($item['lieu']) ?></strong> — <?= (int) $item['nb'] ?> offre(s)</p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="info-card">
                <h3 style="margin-bottom:1rem;">Compétences les plus demandées</h3>
                <?php if (empty($stats['top_competences'])): ?>
                    <p>Aucune donnée.</p>
                <?php else: ?>
                    <div class="info-list">
                        <?php foreach ($stats['top_competences'] as $item): ?>
                            <p><strong><?= \Core\View::e($item['nom']) ?></strong> — <?= (int) $item['nb'] ?> offre(s)</p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
        </div>
    </div>
</section>
