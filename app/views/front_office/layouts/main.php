<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?= htmlspecialchars($title ?? 'IranWatch — Actualités & Histoire du conflit Iran · USA · Israël') ?></title>
    <meta name="description"
        content="<?= htmlspecialchars($metaDescription ?? 'Suivez en temps réel les actualités sur la guerre en Iran, ainsi que l\'histoire des relations entre l\'Iran, les États-Unis et Israël.') ?>" />
    <meta name="keywords"
        content="<?= htmlspecialchars($keywords ?? 'guerre Iran, actualité Iran, Iran USA, Iran Israël, conflit Moyen-Orient') ?>" />
    <meta name="robots" content="<?= htmlspecialchars($robots ?? 'index, follow') ?>" />
    <link rel="canonical" href="<?= htmlspecialchars($canonical ?? 'https://iranwatch.example.com/') ?>" />

    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle ?? $title ?? 'IranWatch') ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription ?? $metaDescription ?? '') ?>" />
    <meta property="og:image"
        content="<?= htmlspecialchars($ogImage ?? 'https://iranwatch.example.com/og-image.jpg') ?>" />
    <meta property="og:url" content="<?= htmlspecialchars($ogUrl ?? 'https://iranwatch.example.com/') ?>" />

    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <div class="layout">
        <div class="top-bar" role="banner">
            <div class="top-bar__inner">
                <time class="top-bar__date" id="js-date"></time>
                <span class="top-bar__tagline">Conflit Iran · USA · Israël — suivi en continu</span>
            </div>
        </div>

        <!-- ── MASTHEAD ─────────────────────────────────────────────── -->
        <header class="masthead">
            <div class="masthead__inner">
                <a href="/" class="masthead__logo" aria-label="IranWatch - Accueil">
                    <span class="masthead__logo-word">Iran<span>Watch</span></span>
                    <span class="masthead__logo-sub">Géopolitique &amp; Conflits — Moyen-Orient</span>
                </a>
                <div class="masthead__search" role="search" tabindex="0" aria-label="Rechercher">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    Rechercher
                </div>
            </div>
        </header>

        <nav class="nav-primary" role="navigation" aria-label="Navigation principale">
            <div class="nav-primary__inner">
                <?php if (!empty($sections)): ?>
                    <?php foreach ($sections as $section): ?>
                        <a href="/<?= htmlspecialchars($section->slug) ?>"
                            class="nav-primary__item <?= ($currentPage ?? '') === $section->slug ? 'nav-primary__item--active' : '' ?>"
                            aria-current="<?= ($currentPage ?? '') === $section->slug ? 'page' : 'false' ?>">
                            <?= htmlspecialchars($section->name ?? $section->title ?? 'Menu') ?>
                        </a>
                        <div class="nav-primary__divider" aria-hidden="true"></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="">Aucun section disponible</a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="breaking" role="alert" aria-live="polite">
            <div class="breaking__inner">
                <span class="breaking__label" aria-label="Alerte">En direct</span>
                <span class="breaking__text">
                    Dernière heure : Frappes signalées près de Téhéran — bilan en cours d'établissement · Réunion
                    d'urgence du Conseil de sécurité de l'ONU convoquée
                </span>
            </div>
        </div>
    </div>

    <!-- PAGE PRINCIPALE : le contenu est injecté ici -->
    <main class="page-wrapper" id="main-content">
        <?= $content ?? '' ?>
    </main>


    <!-- ── FOOTER ────────────────────────────────────────────────── -->
    <footer class="footer" role="contentinfo">
        <div class="footer__inner">
            <div class="footer__top">
                <div>
                    <div class="footer__logo">Iran<span>Watch</span></div>
                    <p class="footer__desc">
                        Veille géopolitique indépendante sur le conflit iranien.<br>
                        Information continue, vérifiée, sans publicité.
                    </p>
                </div>
                <div>
                    <p class="footer__col-title">Rubriques</p>
                    <a class="footer__link" href="/actualite">Actualité</a>
                    <a class="footer__link" href="/histoire">Histoire</a>
                    <a class="footer__link" href="/analyses">Analyses</a>
                    <a class="footer__link" href="/carte">Carte</a>
                </div>
                <div>
                    <p class="footer__col-title">Légal</p>
                    <a class="footer__link" href="/mentions-legales">Mentions légales</a>
                    <a class="footer__link" href="/politique-confidentialite">Vie privée</a>
                    <a class="footer__link" href="/contact">Contact</a>
                </div>
                <div>
                    <p class="footer__col-title">Suivre</p>
                    <a class="footer__link" href="/rss.xml" rel="alternate" type="application/rss+xml">Flux RSS</a>
                    <a class="footer__link" href="https://twitter.com/iranwatch" rel="noopener">Twitter / X</a>
                    <a class="footer__link" href="https://t.me/iranwatch" rel="noopener">Telegram</a>
                </div>
            </div>
            <div class="footer__bottom">
                <span>© 2026 IranWatch — Tous droits réservés</span>
                <span class="footer__seo-note">
                    Ce site est indépendant et à vocation pédagogique. Les contenus visent à informer sur le conflit en
                    Iran dans le cadre d'un projet académique sur le SEO.
                </span>
            </div>
        </div>
    </footer>

    <!-- Scripts (date dynamique, etc.) -->
    <script>
        // Date dans la top bar (identique)
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date().toLocaleDateString('fr-FR', opts);
        const el = document.getElementById('js-date');
        if (el) el.textContent = today.charAt(0).toUpperCase() + today.slice(1);
        // si vous avez un autre élément pour date-long, faites de même
    </script>
</body>

</html>