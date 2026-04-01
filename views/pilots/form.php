<section class="form-section">
    <div class="container">
        <h2 class="section-title"><?= $pilot ? 'Modifier un pilote' : 'Créer un pilote' ?></h2>
        <p class="section-subtitle">Formulaire simple de gestion des pilotes.</p>

        <form class="application-form" method="POST" action="<?= $action ?>">
            <?= \Core\CSRF::field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required value="<?= \Core\View::e($pilot['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required value="<?= \Core\View::e($pilot['prenom'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?= \Core\View::e($pilot['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password"><?= $pilot ? 'Nouveau mot de passe' : 'Mot de passe *' ?></label>
                <input type="password" id="password" name="password" <?= $pilot ? '' : 'required' ?> placeholder="<?= $pilot ? 'Laisser vide pour ne pas changer' : 'Minimum 8 caractères' ?>">
            </div>

            <div class="page-actions">
                <button type="submit" class="btn btn-primary btn-large"><?= $pilot ? 'Enregistrer' : 'Créer le compte' ?></button>
                <a href="<?= APP_URL ?>/pilotes" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>
    </div>
</section>
