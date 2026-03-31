<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title><?= htmlspecialchars($pageTitle ?? 'Back-office') ?> — IranWatch Admin</title>

    <!-- Fonts (mêmes que le front-office) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <!-- Design system back-office -->
    <link rel="stylesheet" href="/assets/css/back_office.css" />

    <!-- CSS supplémentaire injecté par la vue (optionnel) -->
    <?= $extraCss ?? '' ?>
</head>

<body>

<!-- ── MASTHEAD ────────────────────────────────────────────────── -->
<header class="bo-masthead" role="banner">
    <a href="/admin" class="bo-masthead__logo" aria-label="IranWatch — Accueil admin">
        Iran<span>Watch</span>
    </a>
    <nav aria-label="Navigation admin">
        <span class="bo-masthead__badge">Back-office</span>
    </nav>
</header>

<!-- ── NAVIGATION SECONDAIRE ───────────────────────────────────── -->
<nav class="bo-subnav" aria-label="Rubriques admin">
    <div class="bo-subnav__inner">
        <a href="/admin/sections"
           class="bo-subnav__item <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin/sections') ? 'bo-subnav__item--active' : '' ?>">
            Sections
        </a> 
        <a href="/" class="bo-subnav__item" target="_blank" rel="noopener" aria-label="Voir le site (nouvel onglet)">
            ↗ Voir le site
        </a>
    </div>
</nav>

<!-- ── CONTENU PRINCIPAL ───────────────────────────────────────── -->
<main id="bo-main" role="main">
    <?= $content ?? '' ?>
</main>

<!-- ── FOOTER ──────────────────────────────────────────────────── -->
<footer class="bo-footer" role="contentinfo">
    <span class="bo-footer__text">
        <span>IranWatch</span> — Administration
    </span>
    <span class="bo-footer__text">
        <?= date('d/m/Y H:i') ?>
    </span>
</footer>

<!-- JS supplémentaire injecté par la vue (optionnel) -->
<?= $extraJs ?? '' ?>

</body>
</html>