<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- SEO META TAGS dynamiques -->
    <title><?= htmlspecialchars($title ?? 'IranWatch — Actualités & Histoire du conflit Iran · USA · Israël') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Suivez en temps réel les actualités sur la guerre en Iran, ainsi que l\'histoire des relations entre l\'Iran, les États-Unis et Israël.') ?>" />
    <meta name="keywords" content="<?= htmlspecialchars($keywords ?? 'guerre Iran, actualité Iran, Iran USA, Iran Israël, conflit Moyen-Orient') ?>" />
    <meta name="robots" content="<?= htmlspecialchars($robots ?? 'index, follow') ?>" />
    <link rel="canonical" href="<?= htmlspecialchars($canonical ?? 'https://iranwatch.example.com/') ?>" />

    <!-- Open Graph (optionnel) -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle ?? $title ?? 'IranWatch') ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription ?? $metaDescription ?? '') ?>" />
    <meta property="og:image" content="<?= htmlspecialchars($ogImage ?? 'https://iranwatch.example.com/og-image.jpg') ?>" />
    <meta property="og:url" content="<?= htmlspecialchars($ogUrl ?? 'https://iranwatch.example.com/') ?>" />

    <link rel="stylesheet" href="./assets/css/style.css">
    <!-- Fonts etc. (gardez vos balises CSS) -->
    <!-- ... (tout le reste de l'en-tête inchangé) ... -->
</head>
<body>

    <div class="layout">
        <!-- TOP BAR (inchangé, mais on peut rendre la date dynamique en PHP) -->
        <div class="top-bar" role="banner">
            <div class="top-bar__inner">
                <time class="top-bar__date" id="js-date"></time>
                <span class="top-bar__tagline">Conflit Iran · USA · Israël — suivi en continu</span>
            </div>
        </div>

        <!-- MASTHEAD (inchangé) -->
        <header class="masthead">WW3</header>

        <!-- NAVIGATION (inchangé, mais on peut activer le lien courant) -->
        <nav class="nav-primary" role="navigation" aria-label="Navigation principale">
            <div class="nav-primary__inner">
                <a href="/actualite" class="nav-primary__item <?= ($currentPage ?? '') === 'actualite' ? 'nav-primary__item--active' : '' ?>" aria-current="<?= ($currentPage ?? '') === 'actualite' ? 'page' : 'false' ?>">
                    Actualité
                </a>
                <div class="nav-primary__divider" aria-hidden="true"></div>
                <a href="/histoire" class="nav-primary__item <?= ($currentPage ?? '') === 'histoire' ? 'nav-primary__item--active' : '' ?>">Histoire</a>
                <div class="nav-primary__divider" aria-hidden="true"></div>
            </div>
        </nav>

        <!-- BREAKING NEWS (inchangé, pourrait devenir dynamique) -->
        <div class="breaking" role="alert" aria-live="polite">...</div>
    </div>

    <!-- PAGE PRINCIPALE : le contenu est injecté ici -->
    <main class="page-wrapper" id="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- FOOTER (inchangé, mais on peut le rendre dynamique) -->
    <footer class="footer" role="contentinfo">...</footer>

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