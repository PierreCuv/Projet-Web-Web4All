<section class="hero">
    <div class="container">
        <h1>Trouvez votre stage</h1>
        <p class="hero__sub">
            <?= $total_offers ?> offres dans <?= $total_companies ?> entreprises.
        </p>
        <a href="<?= APP_URL ?>/offres" class="btn btn--primary btn--lg">Voir les offres</a>
    </div>
</section>
<section class="section">
    <div class="container">
        <h2>Dernières offres</h2>
        <div class="grid grid--3">
            <?php foreach ($latest_offers as $offer): ?>
                <article class="card">
                    <span class="card__company"><?= \Core\View::e($offer['nom_entreprise']) ?></span>
                    <h3 class="card__title">
                        <a href="<?= APP_URL ?>/offres/<?= $offer['id_offre'] ?>">
                            <?= \Core\View::e($offer['titre']) ?>
                        </a>
                    </h3>
                    <div class="card__meta">
                        <?php if ($offer['lieu']): ?>
                            <span><?= \Core\View::e($offer['lieu']) ?></span>
                        <?php endif; ?>
                        <?php if ($offer['remuneration']): ?>
                            <span><?= number_format($offer['remuneration'], 0, ',', ' ') ?> €/mois</span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= APP_URL ?>/offres/<?= $offer['id_offre'] ?>"
                       class="btn btn--outline btn--sm mt-sm">Voir l'offre</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
