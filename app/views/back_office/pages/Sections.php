<?php

$filters = $filters ?? ['keyword' => '', 'name' => '', 'title' => '', 'slug' => ''];
$sections = $sections ?? [];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$visiblePages = $visiblePages ?? [1];
$total = $total ?? 0;
$editSection = $editSection ?? null;
$notice = $notice ?? '';

$formTitle = $editSection ? 'Modifier la section' : 'Ajouter une nouvelle section';
$formActionLabel = $editSection ? 'Enregistrer' : 'Ajouter la section';

$buildUrl = static function (array $params = []) use ($filters, $page): string {
    $base = [
        'keyword' => $filters['keyword'] ?? '',
        'name'    => $filters['name']    ?? '',
        'title'   => $filters['title']   ?? '',
        'slug'    => $filters['slug']    ?? '',
        'page'    => $page,
    ];
    $merged = array_merge($base, $params);
    $clean  = [];
    foreach ($merged as $key => $value) {
        if ($value !== '' && $value !== null) {
            $clean[$key] = $value;
        }
    }
    return '/admin/sections' . (!empty($clean) ? ('?' . http_build_query($clean)) : '');
};
?>

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />


<!-- ── SHELL ─────────────────────────────────────────────────────── -->
<div class="bo-shell">

    <!-- PAGE HEADER -->
    <div class="bo-page-header">
        <span class="bo-page-header__label">Administration</span>
        <h1 class="bo-title">Sections</h1>
        <div class="bo-page-header__line"></div>
    </div>

    <!-- NOTICE -->
    <?php if ($notice !== ''): ?>
        <p class="bo-notice"><?= htmlspecialchars($notice) ?></p>
    <?php endif; ?>

    <!-- GRILLE -->
    <div class="bo-grid">

        <!-- ── LISTE ──────────────────────────────────────────────── -->
        <section class="bo-card" aria-label="Liste des sections">

            <div class="bo-card__header">
                <span class="bo-card__title">Sections disponibles</span>
                <span class="bo-card__count"><?= (int) $total ?> au total</span>
            </div>

            <div class="bo-card__body">

                <!-- Filtres -->
                <form method="GET" action="/admin/sections" class="bo-toolbar">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Recherche globale…" />
                    <input type="text" name="name"    value="<?= htmlspecialchars($filters['name']    ?? '') ?>" placeholder="Nom…" />
                    <input type="text" name="title"   value="<?= htmlspecialchars($filters['title']   ?? '') ?>" placeholder="Titre…" />
                    <input type="text" name="slug"    value="<?= htmlspecialchars($filters['slug']    ?? '') ?>" placeholder="Slug…" />
                    <button type="submit" class="btn btn--primary btn--sm">Filtrer</button>
                    <a href="/admin/sections" class="btn btn--outline btn--sm">Réinitialiser</a>
                </form>

                <!-- Rows -->
                <div class="bo-list" role="list">
                    <?php if (empty($sections)): ?>
                        <div class="bo-empty">Aucune section trouvée.</div>
                    <?php endif; ?>

                    <?php foreach ($sections as $section): ?>
                        <article
                            class="bo-row"
                            role="listitem"
                            onclick="window.location.href='<?= htmlspecialchars('/admin/sections/' . (int) $section->id . '/contents') ?>'">

                            <div>
                                <div class="bo-row__name"><?= htmlspecialchars($section->name) ?></div>
                                <div class="bo-row__meta">
                                    <span>
                                        <span class="bo-row__meta-label">Titre&thinsp;: </span>
                                        <?= htmlspecialchars($section->title) ?>
                                    </span>
                                    <span>
                                        <span class="bo-row__meta-label">Slug&thinsp;: </span>
                                        <?= htmlspecialchars($section->slug) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="bo-row__actions" onclick="event.stopPropagation();">
                                <a href="<?= htmlspecialchars($buildUrl(['edit' => (int) $section->id])) ?>"
                                   class="btn btn--outline btn--sm">Modifier</a>

                                <form method="POST" action="/admin/sections/delete"
                                      onsubmit="return confirm('Supprimer cette section ?');">
                                    <input type="hidden" name="id" value="<?= (int) $section->id ?>" />
                                    <button type="submit" class="btn btn--danger btn--sm">Supprimer</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="bo-pagination" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="<?= htmlspecialchars($buildUrl(['page' => $page - 1])) ?>" aria-label="Page précédente">&lsaquo;</a>
                        <?php endif; ?>

                        <?php foreach ($visiblePages as $p): ?>
                            <?php if ((int) $p === (int) $page): ?>
                                <span class="is-current"><?= (int) $p ?></span>
                            <?php else: ?>
                                <a href="<?= htmlspecialchars($buildUrl(['page' => (int) $p]))?>"><?= (int) $p ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="<?= htmlspecialchars($buildUrl(['page' => $page + 1])) ?>" aria-label="Page suivante">&rsaquo;</a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>

            </div><!-- /.bo-card__body -->
        </section>

        <!-- ── FORMULAIRE ─────────────────────────────────────────── -->
        <section class="bo-card" aria-label="Formulaire section">

            <div class="bo-card__header">
                <span class="bo-card__title">
                    <?= $editSection ? 'Modifier la section' : 'Nouvelle section' ?>
                </span>
                <?php if ($editSection): ?>
                    <span style="font-family:var(--font-ui);font-size:11px;color:var(--rouge);font-weight:600;">
                        ID #<?= (int) $editSection->id ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="bo-card__body">
                <form method="POST" action="/admin/sections/save" class="bo-form" id="section-form">

                    <p class="bo-form__section-title">Informations</p>

                    <input type="hidden" name="id" value="<?= (int) ($editSection->id ?? 0) ?>" />

                    <label class="bo-label">
                        Nom
                        <input class="bo-field" type="text" name="name" maxlength="255" required
                               value="<?= htmlspecialchars($editSection->name ?? '') ?>"
                               placeholder="Ex : Actualité" />
                    </label>

                    <label class="bo-label">
                        Titre <span style="font-weight:300;text-transform:none;letter-spacing:0">(affiché en front)</span>
                        <input class="bo-field" type="text" id="title-input" name="title" maxlength="255" required
                               value="<?= htmlspecialchars($editSection->title ?? '') ?>"
                               placeholder="Ex : Actualités du conflit" />
                    </label>

                    <label class="bo-label">
                        Slug <span style="font-weight:300;text-transform:none;letter-spacing:0">(URL)</span>
                        <input class="bo-field bo-field--slug" type="text" id="slug-input" name="slug" maxlength="255"
                               value="<?= htmlspecialchars($editSection->slug ?? '') ?>"
                               placeholder="auto-généré depuis le titre" />
                    </label>

                    <div class="bo-form__actions">
                        <a href="/admin/sections" class="btn btn--outline">Annuler</a>
                        <button type="submit" class="btn btn--primary"><?= htmlspecialchars($formActionLabel) ?></button>
                    </div>

                </form>
            </div>
        </section>

    </div><!-- /.bo-grid -->
</div><!-- /.bo-shell -->

<script>
(function () {
    var titleInput = document.getElementById('title-input');
    var slugInput  = document.getElementById('slug-input');
    if (!titleInput || !slugInput) return;

    var userTouchedSlug = slugInput.value.trim() !== '';

    slugInput.addEventListener('input', function () {
        userTouchedSlug = slugInput.value.trim() !== '';
    });

    titleInput.addEventListener('input', function () {
        if (userTouchedSlug) return;
        slugInput.value = toSlug(titleInput.value);
    });

    function toSlug(value) {
        return value
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
})();
</script>