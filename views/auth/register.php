<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">

            <?php if ($error ?? null): ?>
                <div style="background:#fef2f2;color:#b91c1c;padding:.75rem 1rem;
                            border-radius:8px;margin-bottom:1rem;font-size:.9rem;">
                    <?= \Core\View::e($error) ?>
                </div>
            <?php endif; ?>

            <div class="auth-tabs">
                <a href="<?= APP_URL ?>/login"    class="tab-btn">Connexion</a>
                <a href="<?= APP_URL ?>/register" class="tab-btn active">Inscription</a>
            </div>

            <form method="POST" action="<?= APP_URL ?>/register" class="auth-form active">
                <?= \Core\CSRF::field() ?>
                <h2>Créer un compte</h2>

                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom"
                           required placeholder="Jean">
                </div>

                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom"
                           required placeholder="Dupont">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           required placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           required placeholder="•••••••• (min. 8 caractères)">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirm" name="password_confirm"
                           required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    Créer mon compte
                </button>
            </form>
        </div>
    </div>
</section>