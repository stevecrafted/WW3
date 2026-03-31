<section class="welcome-section">
    <div class="welcome-card">
        <h1 class="welcome-title">Bienvenue sur IranWatch</h1>
        <p class="welcome-subtitle">Choisissez votre destination.</p>

        <div class="welcome-buttons">
            <a href="/actualite" class="welcome-btn welcome-btn-front">Aller au Front Office</a>
            <a href="/login" class="welcome-btn welcome-btn-admin">Se connecter (Admin)</a>
        </div>
    </div>
</section>

<style>
    /* Styles adaptés au thème IranWatch */
    .welcome-section {
        max-width: 960px;
        margin: 36px auto;
        padding: 0 18px 36px;
    }

    .welcome-card {
        background: var(--beige, #faf9f7);
        border: 2px solid var(--noir, #0d0d0d);
        /* border-radius: 18px; */
        padding: 24px;
        box-shadow: 0 8px 0 rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .welcome-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 0 rgba(0, 0, 0, 0.12);
    }

    .welcome-title {
        margin: 0 0 8px;
        font-family: var(--font-titre, 'Playfair Display', Georgia, serif);
        font-size: 32px;
        font-weight: 900;
        color: var(--noir, #0d0d0d);
        letter-spacing: -0.02em;
    }

    .welcome-subtitle {
        margin: 0 0 20px;
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-size: 14px;
        color: var(--gris-moyen, #666666);
        font-weight: 400;
    }

    .welcome-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 14px;
    }

    .welcome-btn {
        display: block;
        text-decoration: none;
        background: #efecd9;
        border: 2px solid var(--noir, #0d0d0d);
        /* border-radius: 14px; */
        padding: 16px;
        font-family: var(--font-ui, 'DM Sans', Helvetica, sans-serif);
        font-weight: 600;
        font-size: 15px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .welcome-btn-front {
        background: var(--blanc, #ffffff);
        color: var(--rouge, #c8102e);
        border-color: var(--rouge, #c8102e);
    }

    .welcome-btn-front:hover {
        background: var(--rouge, #c8102e);
        color: var(--blanc, #ffffff);
        transform: translateY(-2px);
    }

    .welcome-btn-admin {
        background: var(--noir, #0d0d0d);
        color: var(--blanc, #ffffff);
        border-color: var(--noir, #0d0d0d);
    }

    .welcome-btn-admin:hover {
        background: var(--rouge, #c8102e);
        border-color: var(--rouge, #c8102e);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 600px) {
        .welcome-title {
            font-size: 26px;
        }
        .welcome-buttons {
            grid-template-columns: 1fr;
        }
        .welcome-card {
            padding: 20px;
        }
    }
</style>