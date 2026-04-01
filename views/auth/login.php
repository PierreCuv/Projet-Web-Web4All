<div class="container container--narrow">
    <div class="card card--center mt-xl">
        <h1 class="card__title">Connexion</h1>
        <?php if ($error ?? null): ?>
            <div class="alert alert--error"><?= \Core\View::e($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= APP_URL ?>/login" novalidate>
            <?= \Core\CSRF::field() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn--primary btn--full">Se connecter</button>
        </form>
    </div>
</div>
