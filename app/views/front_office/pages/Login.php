<?php
$loginError = $loginError ?? '';
$oldUserName = $oldUserName ?? '';
?>

<section style="max-width: 620px; margin: 36px auto; padding: 0 18px 36px;">
    <div style="background: #f5f2e4; border: 2px solid #2c2c2c; border-radius: 18px; padding: 24px; box-shadow: 0 8px 0 rgba(44,44,44,.12);">
        <h1 style="margin: 0 0 8px; font-size: 30px; color: #1f1f1f;">Connexion Admin</h1>
        <p style="margin: 0 0 16px; color: #535244;">Accès au back office.</p>

        <?php if ($loginError !== ''): ?>
            <div style="margin-bottom: 14px; background: #ffe9e9; border: 2px solid #8a2d2d; color: #7a2222; border-radius: 10px; padding: 10px 12px; font-weight: 600;">
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" style="display: grid; gap: 12px;">
            <label style="display: grid; gap: 6px; font-weight: 600; color: #1f1f1f;">
                Nom d'utilisateur
                <input type="text" name="user_name" required maxlength="255" value="<?= htmlspecialchars((string) $oldUserName) ?>" style="border: 2px solid #2c2c2c; border-radius: 10px; min-height: 42px; padding: 8px 10px; background: #f7f4e8;" />
            </label>

            <label style="display: grid; gap: 6px; font-weight: 600; color: #1f1f1f;">
                Mot de passe
                <input type="password" name="password" required maxlength="255" style="border: 2px solid #2c2c2c; border-radius: 10px; min-height: 42px; padding: 8px 10px; background: #f7f4e8;" />
            </label>

            <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap; margin-top: 4px;">
                <a href="/" style="text-decoration: none; border: 2px solid #2c2c2c; border-radius: 10px; min-height: 42px; padding: 8px 12px; background: #f7f4e8; color: #1f1f1f;">Retour</a>
                <button type="submit" style="cursor: pointer; border: 2px solid #2c2c2c; border-radius: 10px; min-height: 42px; padding: 8px 12px; background: #ece7d1; color: #1f1f1f; font-weight: 700;">Se connecter</button>
            </div>
        </form>
    </div>
</section>
