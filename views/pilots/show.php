<section class="offres-section">
    <div class="container" style="max-width:900px;">
        <a href="<?= APP_URL ?>/pilotes" style="color:var(--gray);font-size:.9rem;">← Retour aux pilotes</a>

        <article class="info-card" style="margin-top:1.5rem;">
            <span class="offre-type alternance">Pilote</span>
            <h2 class="offre-title" style="font-size:1.9rem;margin-top:1rem;">
                <?= \Core\View::e($pilot['prenom'] . ' ' . $pilot['nom']) ?>
            </h2>

            <div class="divider"></div>

            <div class="simple-grid simple-grid-2">
                <div class="contact-info">
                    <h5>Informations</h5>
                    <p><strong>Email :</strong> <?= \Core\View::e($pilot['email']) ?></p>
                    <p><strong>Rôle :</strong> <?= \Core\View::e($pilot['role']) ?></p>
                    <p><strong>ID pilote :</strong> <?= (int) ($pilot['pilote_id'] ?? 0) ?></p>
                </div>

                <div class="contact-info">
                    <h5>Utilisation</h5>
                    <p>Cette fiche sert surtout à consulter rapidement le compte pilote et à accéder à sa modification.</p>
                </div>
            </div>

            <div class="page-actions" style="margin-top:1.5rem;">
                <a href="<?= APP_URL ?>/pilotes/<?= $pilot['id_utilisateur'] ?>/modifier" class="btn btn-secondary">Modifier</a>
                <form method="POST" action="<?= APP_URL ?>/pilotes/<?= $pilot['id_utilisateur'] ?>/supprimer" onsubmit="return confirm('Supprimer ce pilote ?')">
                    <?= \Core\CSRF::field() ?>
                    <button type="submit" class="btn btn-primary">Supprimer</button>
                </form>
            </div>
        </article>
    </div>
</section>
