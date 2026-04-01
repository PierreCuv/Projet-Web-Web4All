<section class="offres-section">
    <div class="container" style="max-width:900px;">
        <a href="<?= APP_URL ?>/etudiants" style="color:var(--gray);font-size:.9rem;">← Retour aux étudiants</a>

        <article class="info-card" style="margin-top:1.5rem;">
            <span class="offre-type stage">Étudiant</span>
            <h2 class="offre-title" style="font-size:1.9rem;margin-top:1rem;">
                <?= \Core\View::e($student['prenom'] . ' ' . $student['nom']) ?>
            </h2>

            <div class="divider"></div>

            <div class="simple-grid simple-grid-2">
                <div class="contact-info">
                    <h5>Profil</h5>
                    <p><strong>Email :</strong> <?= \Core\View::e($student['email']) ?></p>
                    <p><strong>Promotion :</strong> <?= \Core\View::e($student['promotion'] ?: 'Non renseignée') ?></p>
                    <p><strong>Rôle :</strong> <?= \Core\View::e($student['role']) ?></p>
                </div>

                <div class="contact-info">
                    <h5>Suivi</h5>
                    <p><strong>ID étudiant :</strong> <?= (int) ($student['id_etudiant'] ?? 0) ?></p>
                    <p><strong>Pilote référent :</strong>
                        <?= !empty($student['ref_pilote_nom'])
                            ? \Core\View::e($student['ref_pilote_prenom'] . ' ' . $student['ref_pilote_nom'])
                            : 'Non renseigné' ?>
                    </p>
                    <p><strong>CV :</strong>
                        <?= !empty($student['cv']) ? 'Renseigné' : 'Non renseigné' ?>
                    </p>
                </div>
            </div>

            <div class="page-actions" style="margin-top:1.5rem;">
                <a href="<?= APP_URL ?>/etudiants/<?= $student['id_utilisateur'] ?>/modifier" class="btn btn-secondary">Modifier</a>
                <form method="POST" action="<?= APP_URL ?>/etudiants/<?= $student['id_utilisateur'] ?>/supprimer" onsubmit="return confirm('Supprimer cet étudiant ?')">
                    <?= \Core\CSRF::field() ?>
                    <button type="submit" class="btn btn-primary">Supprimer</button>
                </form>
            </div>
        </article>
    </div>
</section>
