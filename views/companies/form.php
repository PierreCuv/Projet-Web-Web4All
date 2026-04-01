<section class="form-section">
    <div class="container">
        <h2 class="section-title"><?= $company ? 'Modifier une entreprise' : 'Créer une entreprise' ?></h2>
        <p class="section-subtitle">Renseigne les informations principales de l'entreprise.</p>

        <form class="application-form" method="POST" action="<?= $action ?>">
            <?= \Core\CSRF::field() ?>

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required value="<?= \Core\View::e($company['nom'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="secteur">Secteur</label>
                    <input type="text" id="secteur" name="secteur" value="<?= \Core\View::e($company['secteur'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="<?= \Core\View::e($company['telephone'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?= \Core\View::e($company['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" rows="4" placeholder="Adresse complète de l'entreprise"><?= \Core\View::e($company['adresse'] ?? '') ?></textarea>
            </div>

            <div class="page-actions">
                <button type="submit" class="btn btn-primary btn-large"><?= $company ? 'Enregistrer' : 'Créer l\'entreprise' ?></button>
                <a href="<?= APP_URL ?>/entreprises" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>
    </div>
</section>
