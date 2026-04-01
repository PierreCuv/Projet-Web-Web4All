<section class="form-section">
    <div class="container">
        <h2 class="section-title"><?= $student ? 'Modifier un étudiant' : 'Créer un étudiant' ?></h2>
        <p class="section-subtitle">Formulaire simple pour gérer les comptes étudiants.</p>

        <form class="application-form" method="POST" action="<?= $action ?>">
            <?= \Core\CSRF::field() ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input type="text" id="nom" name="nom" required value="<?= \Core\View::e($student['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom *</label>
                    <input type="text" id="prenom" name="prenom" required value="<?= \Core\View::e($student['prenom'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required value="<?= \Core\View::e($student['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="promotion">Promotion</label>
                    <input type="text" id="promotion" name="promotion" value="<?= \Core\View::e($student['promotion'] ?? '') ?>">
                </div>
            </div>

            <?php if (\Core\Auth::isAdmin()): ?>
                <div class="form-group">
                    <label for="id_pilote">Pilote référent</label>
                    <select id="id_pilote" name="id_pilote">
                        <option value="">— Aucun —</option>
                        <?php foreach (($pilots ?? []) as $pilot): ?>
                            <option value="<?= $pilot['id_pilote'] ?>"
                                <?= (string) ($student['id_pilote'] ?? '') === (string) $pilot['id_pilote'] ? 'selected' : '' ?>>
                                <?= \Core\View::e($pilot['prenom'] . ' ' . $pilot['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php elseif (\Core\Auth::isPilot()): ?>
                <input type="hidden" name="id_pilote" value="<?= (int) \Core\Auth::getPiloteId() ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="password"><?= $student ? 'Nouveau mot de passe' : 'Mot de passe *' ?></label>
                <input type="password" id="password" name="password" <?= $student ? '' : 'required' ?> placeholder="<?= $student ? 'Laisser vide pour ne pas changer' : 'Minimum 8 caractères' ?>">
            </div>

            <div class="page-actions">
                <button type="submit" class="btn btn-primary btn-large"><?= $student ? 'Enregistrer' : 'Créer le compte' ?></button>
                <a href="<?= APP_URL ?>/etudiants" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>
    </div>
</section>
