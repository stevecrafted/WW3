<?php
$loginError = $loginError ?? '';
$oldUserName = $oldUserName ?? '';
?>

<section class="login-section">
    <div class="login-card">
        <h1 class="login-title">Connexion Admin</h1>
        <p class="login-subtitle">Accès au back office.</p>

        <?php if ($loginError !== ''): ?>
            <div class="login-error">
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="login-form">
            <label class="login-label">
                Nom d'utilisateur
                <input type="text" name="user_name" required maxlength="255" 
                       value="admin" 
                       class="login-input" />
            </label>

            <label class="login-label">
                Mot de passe
                <input type="password" name="password" required maxlength="255" 
                       class="login-input" value="admin"/>
            </label>

            <div class="login-actions">
                <a href="/" class="login-btn login-btn-secondary">Retour</a>
                <button type="submit" class="login-btn login-btn-primary">Se connecter</button>
            </div>
        </form>
    </div>
</section>

<style>
    /* Styles adaptés au thème IranWatch */
    .login-section {
        max-width: 620px;
        margin: 36px auto;
        padding: 0 18px 36px;
    }

    .login-card {
        background: var(--beige, #faf9f7);
        border: 2px solid var(--noir, #0d0d0d);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 8px 0 rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .login-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 0 rgba(0, 0, 0, 0.12);
    }

    .login-title {
        margin: 0 0 8px;
        font-family: var(--font-titre, 'Playfair Display', Georgia, serif);
        font-size: 30px;
        font-weight: 900;
        color: var(--noir, #0d0d0d);
        letter-spacing: -0.02em;
    }

    .login-subtitle {
        margin: 0 0 16px;
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-size: 14px;
        color: var(--gris-moyen, #666666);
        font-weight: 400;
    }

    .login-error {
        margin-bottom: 14px;
        background: #ffe9e9;
        border: 2px solid #8a2d2d;
        color: #7a2222;
        border-radius: 10px;
        padding: 10px 12px;
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-weight: 600;
        font-size: 13px;
    }

    .login-form {
        display: grid;
        gap: 12px;
    }

    .login-label {
        display: grid;
        gap: 6px;
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-weight: 600;
        color: var(--noir, #0d0d0d);
        font-size: 14px;
    }

    .login-input {
        border: 2px solid var(--noir, #0d0d0d);
        border-radius: 10px;
        min-height: 42px;
        padding: 8px 10px;
        background: var(--blanc, #ffffff);
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .login-input:focus {
        outline: none;
        border-color: var(--rouge, #c8102e);
        box-shadow: 0 0 0 2px rgba(200, 16, 46, 0.2);
    }

    .login-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .login-btn {
        display: inline-block;
        text-decoration: none;
        border: 2px solid var(--noir, #0d0d0d);
        border-radius: 10px;
        min-height: 42px;
        padding: 8px 12px;
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-weight: 600;
        font-size: 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
    }

    .login-btn-primary {
        background: var(--noir, #0d0d0d);
        color: var(--blanc, #ffffff);
        border-color: var(--noir, #0d0d0d);
    }

    .login-btn-primary:hover {
        background: var(--rouge, #c8102e);
        border-color: var(--rouge, #c8102e);
        transform: translateY(-2px);
    }

    .login-btn-secondary {
        background: var(--blanc, #ffffff);
        color: var(--noir, #0d0d0d);
        border-color: var(--noir, #0d0d0d);
    }

    .login-btn-secondary:hover {
        background: var(--gris-bg, #f4f4f2);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 600px) {
        .login-title {
            font-size: 26px;
        }
        .login-card {
            padding: 20px;
        }
        .login-actions {
            justify-content: stretch;
        }
        .login-btn {
            flex: 1;
            text-align: center;
        }
    }
</style>