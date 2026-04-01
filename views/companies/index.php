<section class="offres-section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 class="section-title" style="margin-bottom:.25rem;">Entreprises</h2>
                <p class="section-subtitle"><?= $pager->total ?> entreprise(s) trouvée(s)</p>
            </div>
            <?php if (\Core\Auth::isStaff()): ?>
                <a href="<?= APP_URL ?>/entreprises/creer" class="btn btn-primary">+ Nouvelle entreprise</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="<?= APP_URL ?>/entreprises"
              style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2rem;">
            <input type="search" name="search"
                   value="<?= \Core\View::e($search) ?>"
                   placeholder="Nom, secteur, adresse..."
                   style="flex:1;min-width:220px;padding:.75rem 1rem;border:2px solid var(--light-gray);border-radius:8px;font-family:var(--font-body);">
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if (!empty($search)): ?>
                <a href="<?= APP_URL ?>/entreprises" class="btn btn-secondary">Effacer</a>
            <?php endif; ?>
        </form>

        <?php if (empty($companies)): ?>
            <div class="empty-state">
                <h3>Aucune entreprise trouvée</h3>
                <p>Essayez avec un autre mot-clé ou ajoutez une nouvelle entreprise.</p>
            </div>
        <?php else: ?>
            <div class="simple-grid simple-grid-3">
                <?php foreach ($companies as $company): ?>
                    <article class="info-card">
                        <div class="offre-header" style="margin-bottom:1rem;align-items:flex-start;">
                            <span class="offre-type alternance">Entreprise</span>
                            <?php if (!empty($company['secteur'])): ?>
                                <span class="offre-duree"><?= \Core\View::e($company['secteur']) ?></span>
                            <?php endif; ?>
                        </div>

                        <h3 class="offre-title" style="font-size:1.35rem;"><?= \Core\View::e($company['nom']) ?></h3>

                        <div class="info-list" style="margin-top:1rem;">
                            <?php if (!empty($company['adresse'])): ?>
                                <p><strong>Adresse :</strong> <?= \Core\View::e($company['adresse']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($company['email'])): ?>
                                <p><strong>Email :</strong> <?= \Core\View::e($company['email']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($company['telephone'])): ?>
                                <p><strong>Téléphone :</strong> <?= \Core\View::e($company['telephone']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="mini-stats">
                            <div class="mini-stat">
                                <span class="mini-stat-value"><?= (int) ($company['nb_candidatures'] ?? 0) ?></span>
                                <span class="mini-stat-label">Candidatures</span>
                            </div>
                            <div class="mini-stat">
                                <span class="mini-stat-value"><?= \Core\View::e($company['note_moyenne'] ?? '—') ?></span>
                                <span class="mini-stat-label">Note</span>
                            </div>
                            <div class="mini-stat">
                                <span class="mini-stat-value"><?= (int) ($company['nb_evaluations'] ?? 0) ?></span>
                                <span class="mini-stat-label">Avis</span>
                            </div>
                        </div>

                        <div class="page-actions" style="margin-top:1.25rem;">
                            <a href="<?= APP_URL ?>/entreprises/<?= $company['id_entreprise'] ?>" class="btn btn-small btn-postuler">Voir</a>
                            <?php if (\Core\Auth::isStaff()): ?>
                                <a href="<?= APP_URL ?>/entreprises/<?= $company['id_entreprise'] ?>/modifier" class="btn btn-small btn-secondary">Modifier</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?= $pager->render(APP_URL . '/entreprises', array_filter(['search' => $search])) ?>
        <?php endif; ?>
    </div>
</section>
