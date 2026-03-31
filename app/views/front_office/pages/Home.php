<section class="home-page">
    <div class="home-container">

        <!-- Header (comme login) -->
        <div class="home-header">
            <span class="home-header__badge">Plateforme d'information</span>
            <h1 class="home-header__title">IranWatch</h1>
            <div class="home-header__line"></div>
            <p class="home-header__desc">
                Suivez l’actualité géopolitique en temps réel et accédez à l’interface d’administration.
            </p>
        </div>

        <!-- Carte principale -->
        <div class="home-card">
            <div class="home-actions">

                <a href="/actualite" class="home-btn home-btn--primary">
                    Accéder aux actualités
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>

                <a href="/login" class="home-btn home-btn--secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Administration
                </a>

            </div>
        </div>

    </div>
</section>

<style>
.home-page {
    background: #ffffff;
    min-height: calc(100vh - 280px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.home-container {
    max-width: 700px;
    width: 100%;
}

/* HEADER (copié logique login) */
.home-header {
    text-align: center;
    margin-bottom: 36px;
}

.home-header__badge {
    display: inline-block;
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #c8102e;
    background: rgba(200, 16, 46, 0.08);
    padding: 4px 12px;
    margin-bottom: 18px;
    border: 1px solid rgba(200, 16, 46, 0.2);
}

.home-header__title {
    font-family: 'Playfair Display', serif;
    font-size: 46px;
    font-weight: 900;
    margin: 0 0 10px;
}

.home-header__line {
    width: 60px;
    height: 3px;
    background: #c8102e;
    margin: 0 auto 16px;
}

.home-header__desc {
    font-family: 'Source Serif 4', serif;
    font-size: 16px;
    color: #333;
}

/* CARD */
.home-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    box-shadow: 0 8px 24px rgba(0,0,0,0.04);
    transition: 0.2s;
}

.home-card:hover {
    box-shadow: 0 12px 32px rgba(0,0,0,0.08);
}

/* ACTIONS */
.home-actions {
    padding: 32px;
    display: flex;
    gap: 16px;
    justify-content: center;
}

/* BUTTONS (même logique que login) */
.home-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 22px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-decoration: none;
    transition: 0.2s;
    border: 1px solid transparent;
}

/* PRIMARY */
.home-btn--primary {
    background: #000;
    color: #fff;
}

.home-btn--primary:hover {
    background: #c8102e;
    transform: translateY(-1px);
}

/* SECONDARY */
.home-btn--secondary {
    border: 1px solid #ccc;
    color: #000;
}

.home-btn--secondary:hover {
    border-color: #000;
    background: #f8f8f8;
    transform: translateY(-1px);
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .home-actions {
        flex-direction: column;
    }

    .home-btn {
        width: 100%;
        justify-content: center;
    }

    .home-header__title {
        font-size: 36px;
    }
}
</style>