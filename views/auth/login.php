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
                <button class="tab-btn active">Connexion</button>
            </div>

            <!-- Formulaire de connexion PHP -->
            <form method="POST" action="<?= APP_URL ?>/login" class="auth-form active">
                <?= \Core\CSRF::field() ?>
                <h2>Ravi de vous revoir</h2>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           required placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</section>
