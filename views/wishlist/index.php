<section class="container" style="padding-top:5rem;">
    <h2 class="section-title">Ma Wishlist ❤️</h2>

    <?php if (empty($offers)): ?>
        <p style="text-align:center;color:var(--gray);padding:3rem 0;grid-column:1/-1;">
            Ta wishlist est vide.
            <a href="<?= APP_URL ?>/offres">Parcourir les offres</a>
        </p>
    <?php else: ?>
        <div class="offres-grid">
            <?php foreach ($offers as $offre): ?>
                <div class="offre-card">
                    <div class="offre-header">
                        <span class="offre-type stage">Stage</span>
                        <?php if ($offre['lieu']): ?>
                            <span class="offre-duree">📍 <?= \Core\View::e($offre['lieu']) ?></span>
                        <?php endif; ?>
                    </div>

                    <h3 class="offre-title"><?= \Core\View::e($offre['titre']) ?></h3>
                    <p class="offre-company"><?= \Core\View::e($offre['nom_entreprise']) ?></p>

                    <?php if ($offre['remuneration']): ?>
                        <p class="offre-location">
                            💶 <?= number_format($offre['remuneration'], 0, ',', ' ') ?> €/mois
                        </p>
                    <?php endif; ?>

                    <div style="display:flex;gap:.5rem;margin-top:1rem;">
                        <a href="<?= APP_URL ?>/offres/<?= $offre['id_offre'] ?>"
                           class="btn btn-small btn-postuler">Voir</a>
                        <form method="POST"
                              action="<?= APP_URL ?>/offres/<?= $offre['id_offre'] ?>/unwishlist">
                            <?= \Core\CSRF::field() ?>
                            <button type="submit" class="btn btn-small"
                                    style="background:var(--primary-color);color:white;border:none;">
                                Retirer ✕
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
