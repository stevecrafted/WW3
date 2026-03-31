<?php
$loginError = $loginError ?? '';
$oldUserName = $oldUserName ?? '';
?>

<div class="login-page">
    <div class="login-container">
        <!-- En-tête de la page -->
        <div class="login-header">
            <span class="login-header__badge">Accès restreint</span>
            <h1 class="login-header__title">Connexion</h1>
            <div class="login-header__line"></div>
            <p class="login-header__desc">Veuillez vous authentifier pour accéder à l'administration</p>
        </div>

        <!-- Message d'erreur -->
        <?php if ($loginError !== ''): ?>
            <div class="login-alert login-alert--error">
                <svg class="login-alert__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?= htmlspecialchars($loginError) ?></span>
            </div>
        <?php endif; ?>

        <!-- Carte de connexion -->
        <div class="login-card">
            <form method="POST" action="/login" class="login-form">
                <div class="login-form__group">
                    <label class="login-form__label" for="username">Nom d'utilisateur</label>
                    <div class="login-form__field-wrapper">
                        <svg class="login-form__field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" 
                               id="username"
                               name="user_name" 
                               class="login-form__field" 
                               required 
                               maxlength="255" 
                               value="admin" 
                               placeholder="admin" />
                    </div>
                </div>

                <div class="login-form__group">
                    <label class="login-form__label" for="password">Mot de passe</label>
                    <div class="login-form__field-wrapper">
                        <svg class="login-form__field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="login-form__field" 
                               required 
                               value="admin" 
                               maxlength="255" 
                               placeholder="••••••••" />
                        <button type="button" 
                                class="login-form__toggle" 
                                aria-label="Afficher le mot de passe">
                            <svg class="login-form__eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="login-form__actions">
                    <a href="/" class="login-form__btn login-form__btn--secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9.5L12 3l9 6.5" />
                            <path d="M9 22V12h6v10" />
                        </svg>
                        Retour au site
                    </a>
                    <button type="submit" class="login-form__btn login-form__btn--primary">
                        Se connecter
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    (function() {
        const toggleBtn = document.querySelector('.login-form__toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = this.querySelector('.login-form__eye');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = `
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    `;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = `
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    `;
                }
            });
        }
    })();
</script>

<style>
    /* =====================================================
       STYLES SPÉCIFIQUES À LA PAGE DE CONNEXION
       Cohérents avec la charte graphique d'IranWatch
       ===================================================== */

    /* Reset des marges pour la page */
    .login-page {
        background: #ffffff;
        min-height: calc(100vh - 280px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .login-container {
        max-width: 500px;
        width: 100%;
        margin: 0 auto;
    }

    /* En-tête */
    .login-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-header__badge {
        display: inline-block;
        font-family: 'DM Sans', Helvetica, sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #c8102e;
        background: rgba(200, 16, 46, 0.08);
        padding: 4px 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(200, 16, 46, 0.2);
    }

    .login-header__title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 42px;
        font-weight: 900;
        letter-spacing: -0.02em;
        color: #000000;
        margin: 0 0 12px 0;
    }

    .login-header__line {
        width: 60px;
        height: 3px;
        background: #c8102e;
        margin: 0 auto 16px auto;
    }

    .login-header__desc {
        font-family: 'Source Serif 4', Georgia, serif;
        font-size: 16px;
        color: #333333;
        margin: 0;
    }

    /* Alerte */
    .login-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        margin-bottom: 28px;
        font-family: 'DM Sans', Helvetica, sans-serif;
        font-size: 14px;
        border-left: 4px solid;
    }

    .login-alert--error {
        background: #fff5f5;
        border-left-color: #c8102e;
        color: #c8102e;
    }

    .login-alert__icon {
        flex-shrink: 0;
        stroke: #c8102e;
    }

    /* Carte */
    .login-card {
        background: #ffffff;
        border: 1px solid #e8e8e8;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        transition: box-shadow 0.2s ease;
    }

    .login-card:hover {
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    }

    /* Formulaire */
    .login-form {
        padding: 32px;
    }

    .login-form__group {
        margin-bottom: 24px;
    }

    .login-form__label {
        display: block;
        font-family: 'DM Sans', Helvetica, sans-serif;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #000000;
        margin-bottom: 8px;
    }

    .login-form__field-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .login-form__field-icon {
        position: absolute;
        left: 12px;
        color: #999999;
        pointer-events: none;
        stroke-width: 1.5;
    }

    .login-form__field {
        width: 100%;
        padding: 12px 12px 12px 40px;
        font-family: 'DM Sans', Helvetica, sans-serif;
        font-size: 15px;
        border: 1px solid #cccccc;
        background: #ffffff;
        transition: all 0.2s ease;
        color: #000000;
    }

    .login-form__field:focus {
        outline: none;
        border-color: #c8102e;
        box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
    }

    .login-form__field::placeholder {
        color: #bbbbbb;
        font-weight: 400;
    }

    .login-form__toggle {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #999999;
        transition: color 0.2s;
    }

    .login-form__toggle:hover {
        color: #c8102e;
    }

    .login-form__eye {
        display: block;
        stroke-width: 1.8;
    }

    /* Boutons d'action */
    .login-form__actions {
        display: flex;
        gap: 16px;
        justify-content: space-between;
        margin-top: 32px;
        padding-top: 8px;
    }

    .login-form__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 24px;
        font-family: 'DM Sans', Helvetica, sans-serif;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .login-form__btn--primary {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
    }

    .login-form__btn--primary:hover {
        background: #c8102e;
        border-color: #c8102e;
        transform: translateY(-1px);
    }

    .login-form__btn--secondary {
        background: transparent;
        color: #000000;
        border-color: #cccccc;
    }

    .login-form__btn--secondary:hover {
        border-color: #000000;
        background: #f8f8f8;
        transform: translateY(-1px);
    }

    /* Footer de la page */
    .login-footer {
        text-align: center;
        margin-top: 32px;
        font-family: 'DM Sans', Helvetica, sans-serif;
        font-size: 13px;
        color: #666666;
    }

    .login-footer a {
        color: #000000;
        text-decoration: none;
        border-bottom: 1px solid #cccccc;
        transition: border-color 0.2s;
    }

    .login-footer a:hover {
        border-bottom-color: #c8102e;
        color: #c8102e;
    }

    /* Responsive */
    @media (max-width: 560px) {
        .login-page {
            padding: 30px 16px;
        }

        .login-form {
            padding: 24px;
        }

        .login-header__title {
            font-size: 34px;
        }

        .login-form__actions {
            flex-direction: column-reverse;
            gap: 12px;
        }

        .login-form__btn {
            width: 100%;
        }
    }
</style>