<!-- Hero — identique à leur index.html -->
<section id="accueil" class="hero">
    <div class="hero-layout">
        <div class="hero-content">
            <h2 class="hero-title">Trouvez votre stage ou alternance idéale</h2>
            <p class="hero-subtitle">
                <?= $total_offers ?> offres dans <?= $total_companies ?> entreprises partenaires
            </p>
            <div class="hero-cta">
                <a href="<?= APP_URL ?>/offres" class="btn btn-primary">Voir les offres</a>
                <?php if (!\Core\Auth::isLoggedIn()): ?>
                    <a href="<?= APP_URL ?>/login" class="btn btn-secondary">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-video-wrapper">
            <video autoplay muted loop playsinline class="hero-video">
                <source src="<?= APP_URL ?>/video/video.mp4" type="video/mp4">
            </video>
        </div>
    </div>
</section>

<!-- Offres dynamiques — même style que leurs cartes -->
<section id="offres" class="offres-section">
    <div class="container">
        <h2 class="section-title">Dernières offres disponibles</h2>
        <p class="section-subtitle">Découvrez les opportunités du moment</p>

        <div class="offres-grid">
            <?php foreach ($latest_offers as $offre): ?>
                <div class="offre-card">
                    <div class="offre-header">
                        <span class="offre-type stage">Stage</span>
                        <?php if ($offre['lieu']): ?>
                            <span class="offre-duree"><?= \Core\View::e($offre['lieu']) ?></span>
                        <?php endif; ?>
                    </div>

                    <h3 class="offre-title"><?= \Core\View::e($offre['titre']) ?></h3>
                    <p class="offre-company"><?= \Core\View::e($offre['nom_entreprise']) ?></p>

                    <?php if ($offre['remuneration']): ?>
                        <p class="offre-location">
                            💶 <?= number_format($offre['remuneration'], 0, ',', ' ') ?> €/mois
                        </p>
                    <?php endif; ?>

                    <p class="offre-description">
                        <?= \Core\View::e(mb_substr($offre['description'], 0, 120)) ?>...
                    </p>

                    <?php if ($offre['competences']): ?>
                        <div class="offre-tags">
                            <?php foreach (explode(', ', $offre['competences']) as $comp): ?>
                                <span class="tag"><?= \Core\View::e($comp) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <a href="<?= APP_URL ?>/offres/<?= $offre['id_offre'] ?>"
                       class="btn btn-small btn-postuler">Voir l'offre</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:2rem;">
            <a href="<?= APP_URL ?>/offres" class="btn btn-primary">
                Voir toutes les offres
            </a>
        </div>
    </div>
</section>
