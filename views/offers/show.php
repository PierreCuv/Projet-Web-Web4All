<section class="offres-section">
    <div class="container" style="max-width:900px;">

        <a href="<?= APP_URL ?>/offres" style="color:var(--gray);font-size:.9rem;">
            ← Retour aux offres
        </a>

        <div class="offre-card" style="margin-top:1.5rem;">
            <div class="offre-header">
                <span class="offre-type stage">Stage</span>
                <?php if ($offer['lieu']): ?>
                    <span class="offre-duree">📍 <?= \Core\View::e($offer['lieu']) ?></span>
                <?php endif; ?>
            </div>

            <h2 class="offre-title" style="font-size:1.75rem;">
                <?= \Core\View::e($offer['titre']) ?>
            </h2>
            <p class="offre-company"><?= \Core\View::e($offer['nom_entreprise']) ?></p>

            <div style="display:flex;gap:2rem;flex-wrap:wrap;margin:1rem 0;font-size:.9rem;color:var(--gray);">
                <?php if ($offer['remuneration']): ?>
                    <span>💶 <?= number_format($offer['remuneration'], 0, ',', ' ') ?> €/mois</span>
                <?php endif; ?>
                <?php if ($offer['date_debut']): ?>
                    <span>📅 Début : <?= date('d/m/Y', strtotime($offer['date_debut'])) ?></span>
                <?php endif; ?>
                <?php if ($offer['date_fin']): ?>
                    <span>🏁 Fin : <?= date('d/m/Y', strtotime($offer['date_fin'])) ?></span>
                <?php endif; ?>
                <span>👥 <?= $offer['nb_candidatures'] ?> candidature(s)</span>
            </div>

            <?php if (!empty($offer['competences'])): ?>
                <div class="offre-tags">
                    <?php foreach ($offer['competences'] as $comp): ?>
                        <span class="tag"><?= \Core\View::e($comp['nom']) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="extended-info" style="display:block;margin-top:1.5rem;">
                <div class="divider"></div>
                <h4>Description du poste</h4>
                <p><?= nl2br(\Core\View::e($offer['description'])) ?></p>

                <div class="contact-info" style="margin-top:1.5rem;">
                    <h5>Contact entreprise</h5>
                    <p><strong>Entreprise :</strong> <?= \Core\View::e($offer['nom_entreprise']) ?></p>
                    <?php if ($offer['email_entreprise']): ?>
                        <p><strong>Email :</strong> <?= \Core\View::e($offer['email_entreprise']) ?></p>
                    <?php endif; ?>
                    <?php if ($offer['tel_entreprise']): ?>
                        <p><strong>Téléphone :</strong> <?= \Core\View::e($offer['tel_entreprise']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-top:1.5rem;">
                <?php if (\Core\Auth::isStudent()): ?>
                    <!-- Wishlist -->
                    <form method="POST" action="<?= APP_URL ?>/offres/<?= $offer['id_offre'] ?>/wishlist">
                        <?= \Core\CSRF::field() ?>
                        <button type="submit" class="btn btn-secondary">🤍 Ajouter à ma wishlist</button>
                    </form>

                    <!-- Postuler -->
                    <form method="POST"
                          action="<?= APP_URL ?>/offres/<?= $offer['id_offre'] ?>/postuler"
                          enctype="multipart/form-data"
                          style="display:flex;flex-direction:column;gap:1rem;width:100%;margin-top:1rem;">
                        <?= \Core\CSRF::field() ?>
                        <h4>Postuler à cette offre</h4>
                        <div class="form-group">
                            <label for="cv">CV (PDF, max 5 Mo) *</label>
                            <div class="file-upload">
                                <input type="file" id="cv" name="cv" accept=".pdf" required>
                                <label for="cv" class="file-label">
                                    <span class="file-icon">📄</span>
                                    <span class="file-text">Choisir un fichier PDF</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lettre_motivation">Lettre de motivation (optionnel)</label>
                            <div class="file-upload">
                                <input type="file" id="lettre_motivation"
                                       name="lettre_motivation" accept=".pdf">
                                <label for="lettre_motivation" class="file-label">
                                    <span class="file-icon">📄</span>
                                    <span class="file-text">Choisir un fichier PDF</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large">
                            Envoyer ma candidature
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (\Core\Auth::isStaff()): ?>
                    <a href="<?= APP_URL ?>/offres/<?= $offer['id_offre'] ?>/modifier"
                       class="btn btn-secondary">Modifier</a>
                    <form method="POST"
                          action="<?= APP_URL ?>/offres/<?= $offer['id_offre'] ?>/supprimer"
                          onsubmit="return confirm('Supprimer cette offre ?')">
                        <?= \Core\CSRF::field() ?>
                        <button type="submit" class="btn btn-primary"
                                style="background:var(--primary-color);">Supprimer</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
