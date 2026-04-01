<section class="offres-section">
    <div class="container" style="max-width:950px;">
        <a href="<?= APP_URL ?>/entreprises" style="color:var(--gray);font-size:.9rem;">← Retour aux entreprises</a>

        <article class="info-card" style="margin-top:1.5rem;">
            <div class="offre-header" style="margin-bottom:1rem;align-items:flex-start;">
                <span class="offre-type alternance">Entreprise</span>
                <?php if (!empty($company['secteur'])): ?>
                    <span class="offre-duree"><?= \Core\View::e($company['secteur']) ?></span>
                <?php endif; ?>
            </div>

            <h2 class="offre-title" style="font-size:1.9rem;"><?= \Core\View::e($company['nom']) ?></h2>

            <div class="mini-stats" style="margin-top:1.5rem;">
                <div class="mini-stat">
                    <span class="mini-stat-value"><?= (int) ($company['nb_candidatures'] ?? 0) ?></span>
                    <span class="mini-stat-label">Candidatures</span>
                </div>
                <div class="mini-stat">
                    <span class="mini-stat-value"><?= \Core\View::e($company['note_moyenne'] ?? '—') ?></span>
                    <span class="mini-stat-label">Note moyenne</span>
                </div>
                <div class="mini-stat">
                    <span class="mini-stat-value"><?= (int) ($company['nb_evaluations'] ?? 0) ?></span>
                    <span class="mini-stat-label">Évaluations</span>
                </div>
            </div>

            <div class="divider"></div>

            <div class="simple-grid simple-grid-2">
                <div class="contact-info">
                    <h5>Informations</h5>
                    <p><strong>Nom :</strong> <?= \Core\View::e($company['nom']) ?></p>
                    <p><strong>Secteur :</strong> <?= \Core\View::e($company['secteur'] ?: 'Non renseigné') ?></p>
                    <p><strong>Adresse :</strong> <?= \Core\View::e($company['adresse'] ?: 'Non renseignée') ?></p>
                    <p><strong>Email :</strong> <?= \Core\View::e($company['email'] ?: 'Non renseigné') ?></p>
                    <p><strong>Téléphone :</strong> <?= \Core\View::e($company['telephone'] ?: 'Non renseigné') ?></p>
                </div>

                <div class="contact-info">
                    <h5>Résumé</h5>
                    <p>Cette fiche permet de centraliser les coordonnées de l'entreprise, ses statistiques de candidatures et les avis laissés par les étudiants.</p>
                    <p>Tu peux utiliser cette page pour consulter rapidement l'entreprise avant de postuler à une de ses offres.</p>
                </div>
            </div>

            <?php if (\Core\Auth::isStaff()): ?>
                <div class="page-actions" style="margin-top:1.5rem;">
                    <a href="<?= APP_URL ?>/entreprises/<?= $company['id_entreprise'] ?>/modifier" class="btn btn-secondary">Modifier</a>
                    <form method="POST" action="<?= APP_URL ?>/entreprises/<?= $company['id_entreprise'] ?>/supprimer" onsubmit="return confirm('Supprimer cette entreprise ?')">
                        <?= \Core\CSRF::field() ?>
                        <button type="submit" class="btn btn-primary">Supprimer</button>
                    </form>
                </div>
            <?php endif; ?>
        </article>

        <section style="margin-top:2rem;">
            <h3 style="margin-bottom:1rem;">Avis sur l'entreprise</h3>

            <?php if (empty($evaluations)): ?>
                <div class="info-card">
                    <p>Aucun avis pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="simple-grid simple-grid-2">
                    <?php foreach ($evaluations as $evaluation): ?>
                        <article class="info-card">
                            <div style="display:flex;justify-content:space-between;gap:1rem;align-items:center;flex-wrap:wrap;">
                                <strong><?= \Core\View::e(trim(($evaluation['prenom'] ?? '') . ' ' . ($evaluation['nom'] ?? ''))) ?></strong>
                                <span class="status-badge status-badge-neutral">Note : <?= (int) $evaluation['note'] ?>/5</span>
                            </div>
                            <p style="margin-top:1rem;"><?= nl2br(\Core\View::e($evaluation['commentaire'] ?: 'Aucun commentaire.')) ?></p>
                            <?php if (!empty($evaluation['date_evaluation'])): ?>
                                <p style="margin-top:1rem;color:var(--gray);font-size:.9rem;">
                                    <?= date('d/m/Y H:i', strtotime($evaluation['date_evaluation'])) ?>
                                </p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <?php if (\Core\Auth::isStudent()): ?>
            <section class="form-section" style="padding-top:2rem;">
                <h3 style="margin-bottom:1rem;">Laisser un avis</h3>
                <form method="POST" action="<?= APP_URL ?>/entreprises/<?= $company['id_entreprise'] ?>/evaluer" class="application-form" style="max-width:none;">
                    <?= \Core\CSRF::field() ?>

                    <div class="form-group">
                        <label for="note">Note *</label>
                        <select id="note" name="note" required>
                            <option value="">— Choisir —</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?>/5</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea id="commentaire" name="commentaire" rows="5" placeholder="Ton retour sur l'entreprise..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer l'évaluation</button>
                </form>
            </section>
        <?php endif; ?>
    </div>
</section>
