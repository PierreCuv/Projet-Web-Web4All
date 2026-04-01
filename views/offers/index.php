<section class="offres-section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 class="section-title" style="margin-bottom:.25rem;">Toutes les offres</h2>
                <p class="section-subtitle"><?= $pager->total ?> offre(s) disponible(s)</p>
            </div>
            <?php if (\Core\Auth::isStaff()): ?>
                <a href="<?= APP_URL ?>/offres/creer" class="btn btn-primary">+ Nouvelle offre</a>
            <?php endif; ?>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" action="<?= APP_URL ?>/offres"
              style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2rem;">
            <input type="search" name="search"
                   value="<?= \Core\View::e($filters['search']) ?>"
                   placeholder="Titre, entreprise, description..."
                   style="flex:1;min-width:200px;padding:.75rem 1rem;
                          border:2px solid var(--light-gray);border-radius:8px;
                          font-family:var(--font-body);">
            <input type="text" name="lieu"
                   value="<?= \Core\View::e($filters['lieu']) ?>"
                   placeholder="Ville..."
                   style="width:160px;padding:.75rem 1rem;
                          border:2px solid var(--light-gray);border-radius:8px;
                          font-family:var(--font-body);">
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if (array_filter($filters)): ?>
                <a href="<?= APP_URL ?>/offres" class="btn btn-secondary">Effacer</a>
            <?php endif; ?>
        </form>

        <!-- Grille d'offres — même style que index.html -->
        <?php if (empty($offers)): ?>
            <p style="text-align:center;color:var(--gray);padding:3rem 0;">
                Aucune offre ne correspond à votre recherche.
            </p>
        <?php else: ?>
            <div class="offres-grid">
                <?php foreach ($offers as $offre): ?>
                    <div class="offre-card">
                        <div class="offre-header">
                            <span class="offre-type stage">Stage</span>
                            <?php if ($offre['lieu']): ?>
                                <span class="offre-duree"><?= \Core\View::e($offre['lieu']) ?></span>
                            <?php endif; ?>

                            <?php if (\Core\Auth::isStudent()): ?>
                                <form method="POST"
                                      action="<?= APP_URL ?>/offres/<?= $offre['id_offre'] ?>/wishlist"
                                      style="display:inline;">
                                    <?= \Core\CSRF::field() ?>
                                    <button type="submit" class="btn-wishlist"
                                            style="background:none;border:none;cursor:pointer;font-size:1.2rem;">
                                        🤍
                                    </button>
                                </form>
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
                            <?= \Core\View::e(mb_substr($offre['description'], 0, 100)) ?>...
                        </p>

                        <?php if ($offre['competences']): ?>
                            <div class="offre-tags">
                                <?php foreach (explode(', ', $offre['competences']) as $comp): ?>
                                    <span class="tag"><?= \Core\View::e(trim($comp)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:1rem;">
                            <a href="<?= APP_URL ?>/offres/<?= $offre['id_offre'] ?>"
                               class="btn btn-small btn-postuler">Voir</a>
                            <?php if (\Core\Auth::isStaff()): ?>
                                <a href="<?= APP_URL ?>/offres/<?= $offre['id_offre'] ?>/modifier"
                                   class="btn btn-small btn-secondary">Modifier</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?= $pager->render(APP_URL . '/offres', array_filter($filters)) ?>
        <?php endif; ?>
    </div>
</section>
