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
$formActionLabel = $editSection ? 'Enregistrer la modification' : 'Ajouter la section';

$buildUrl = static function (array $params = []) use ($filters, $page): string {
    $base = [
        'keyword' => $filters['keyword'] ?? '',
        'name' => $filters['name'] ?? '',
        'title' => $filters['title'] ?? '',
        'slug' => $filters['slug'] ?? '',
        'page' => $page,
    ];

    $merged = array_merge($base, $params);
    $clean = [];
    foreach ($merged as $key => $value) {
        if ($value !== '' && $value !== null) {
            $clean[$key] = $value;
        }
    }

    return '/admin/sections' . (!empty($clean) ? ('?' . http_build_query($clean)) : '');
};
?>

<style>
    :root {
        --bo-bg: #efecd9;
        --bo-border: #2c2c2c;
        --bo-text: #1f1f1f;
        --bo-card: #f5f2e4;
        --bo-muted: #535244;
        --bo-btn: #f7f4e8;
        --bo-btn-hover: #ece7d1;
        --bo-danger: #8a2d2d;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: "Trebuchet MS", "Segoe UI", sans-serif;
        background: radial-gradient(circle at 20% 0%, #f4f0de 0%, var(--bo-bg) 55%, #e3ddc6 100%);
        color: var(--bo-text);
    }

    .bo-shell {
        max-width: 1200px;
        margin: 28px auto;
        padding: 0 18px 24px;
    }

    .bo-title {
        margin: 0 0 12px;
        font-size: 28px;
        letter-spacing: 0.4px;
    }

    .bo-notice {
        background: #eaf7d6;
        border: 2px solid #4f6e24;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 16px;
        font-weight: 600;
    }

    .bo-grid {
        display: grid;
        grid-template-columns: minmax(320px, 1fr) minmax(320px, 0.95fr);
        gap: 18px;
        align-items: start;
    }

    .bo-card {
        background: var(--bo-card);
        border: 2px solid var(--bo-border);
        border-radius: 16px;
        padding: 14px;
        box-shadow: 0 6px 0 rgba(44, 44, 44, 0.12);
    }

    .bo-toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .bo-toolbar input {
        flex: 1 1 180px;
    }

    .bo-field,
    .bo-toolbar input,
    .bo-toolbar button,
    .bo-toolbar a,
    .bo-row button,
    .bo-row a,
    .bo-form button,
    .bo-form a {
        border: 2px solid var(--bo-border);
        border-radius: 10px;
        min-height: 40px;
        padding: 8px 10px;
        background: var(--bo-btn);
        color: var(--bo-text);
        font-size: 14px;
        text-decoration: none;
    }

    .bo-toolbar button,
    .bo-toolbar a,
    .bo-row button,
    .bo-row a,
    .bo-form button,
    .bo-form a {
        cursor: pointer;
        transition: transform .14s ease, background-color .14s ease;
    }

    .bo-toolbar button:hover,
    .bo-toolbar a:hover,
    .bo-row button:hover,
    .bo-row a:hover,
    .bo-form button:hover,
    .bo-form a:hover {
        transform: translateY(-1px);
        background: var(--bo-btn-hover);
    }

    .bo-section-list-title {
        text-align: center;
        font-weight: 700;
        margin: 8px 0 12px;
    }

    .bo-list {
        display: grid;
        gap: 10px;
    }

    .bo-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 8px;
        align-items: center;
        border: 2px solid var(--bo-border);
        border-radius: 12px;
        padding: 10px;
        background: #f9f7ed;
    }

    .bo-row-head {
        font-weight: 700;
        margin-bottom: 4px;
    }

    .bo-row-sub {
        color: var(--bo-muted);
        font-size: 13px;
        margin-bottom: 4px;
    }

    .bo-row-actions {
        display: flex;
        gap: 6px;
    }

    .bo-btn-danger {
        border-color: var(--bo-danger) !important;
        color: var(--bo-danger) !important;
    }

    .bo-pagination {
        margin-top: 14px;
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .bo-pagination a,
    .bo-pagination span {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--bo-border);
        border-radius: 9px;
        text-decoration: none;
        color: var(--bo-text);
        background: var(--bo-btn);
    }

    .bo-pagination .is-current {
        background: #dcd6bd;
        font-weight: 700;
    }

    .bo-form {
        display: grid;
        gap: 12px;
    }

    .bo-form label {
        display: grid;
        gap: 6px;
        font-weight: 600;
    }

    .bo-form-head {
        text-align: center;
        font-weight: 700;
        margin-top: 4px;
    }

    .bo-form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .bo-empty {
        text-align: center;
        color: var(--bo-muted);
        border: 2px dashed var(--bo-border);
        border-radius: 12px;
        padding: 14px;
    }

    @media (max-width: 960px) {
        .bo-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="bo-shell">
    <h1 class="bo-title">Gestion des sections</h1>

    <?php if ($notice !== ''): ?>
        <p class="bo-notice"><?= htmlspecialchars($notice) ?></p>
    <?php endif; ?>

    <div class="bo-grid">
        <section class="bo-card" aria-label="Liste des sections">
            <form method="GET" action="/admin/sections" class="bo-toolbar">
                <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Recherche globale" />
                <input type="text" name="name" value="<?= htmlspecialchars($filters['name'] ?? '') ?>" placeholder="Nom" />
                <input type="text" name="title" value="<?= htmlspecialchars($filters['title'] ?? '') ?>" placeholder="Titre" />
                <input type="text" name="slug" value="<?= htmlspecialchars($filters['slug'] ?? '') ?>" placeholder="Slug" />
                <button type="submit">Rechercher</button>
                <a href="/admin/sections">Ajouter</a>
                <a href="/admin/sections">Reset</a>
            </form>

            <p class="bo-section-list-title">Sections (<?= (int) $total ?>)</p>

            <div class="bo-list">
                <?php if (empty($sections)): ?>
                    <div class="bo-empty">Aucune section trouvée.</div>
                <?php endif; ?>

                <?php foreach ($sections as $section): ?>
                    <article class="bo-row">
                        <div>
                            <div class="bo-row-head">
                                <a href="<?= htmlspecialchars('/admin/sections/' . (int) $section->id . '/contents') ?>"><?= htmlspecialchars($section->name) ?></a>
                            </div>
                            <div class="bo-row-sub">Titre: <?= htmlspecialchars($section->title) ?></div>
                            <div class="bo-row-sub">Slug: <?= htmlspecialchars($section->slug) ?></div>
                        </div>
                        <div class="bo-row-actions">
                            <a href="<?= htmlspecialchars($buildUrl(['edit' => (int) $section->id])) ?>">Modifier</a>
                            <form method="POST" action="/admin/sections/delete" onsubmit="return confirm('Confirmer la suppression de cette section ?');">
                                <input type="hidden" name="id" value="<?= (int) $section->id ?>" />
                                <button type="submit" class="bo-btn-danger">Supprimer</button>
                            </form>
                            <a href="<?= htmlspecialchars('/admin/sections/' . (int) $section->id . '/contents') ?>" aria-label="Voir les contenus de la section">&rarr;</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <nav class="bo-pagination" aria-label="Pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?= htmlspecialchars($buildUrl(['page' => $page - 1])) ?>" aria-label="Page precedente">&lt;</a>
                    <?php endif; ?>

                    <?php foreach ($visiblePages as $p): ?>
                        <?php if ((int) $p === (int) $page): ?>
                            <span class="is-current"><?= (int) $p ?></span>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($buildUrl(['page' => (int) $p])) ?>"><?= (int) $p ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= htmlspecialchars($buildUrl(['page' => $page + 1])) ?>" aria-label="Page suivante">&gt;</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </section>

        <section class="bo-card" aria-label="Formulaire section">
            <form method="POST" action="/admin/sections/save" class="bo-form" id="section-form">
                <p class="bo-form-head"><?= htmlspecialchars($formTitle) ?></p>
                <input type="hidden" name="id" value="<?= (int) ($editSection->id ?? 0) ?>" />

                <label>
                    Nom
                    <input class="bo-field" type="text" name="name" maxlength="255" required value="<?= htmlspecialchars($editSection->name ?? '') ?>" />
                </label>

                <label>
                    Titre
                    <input class="bo-field" type="text" id="title-input" name="title" maxlength="255" required value="<?= htmlspecialchars($editSection->title ?? '') ?>" />
                </label>

                <label>
                    Slug
                    <input class="bo-field" type="text" id="slug-input" name="slug" maxlength="255" value="<?= htmlspecialchars($editSection->slug ?? '') ?>" />
                </label>

                <div class="bo-form-actions">
                    <a href="/admin/sections">Annuler</a>
                    <button type="submit"><?= htmlspecialchars($formActionLabel) ?></button>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
    (function () {
        var titleInput = document.getElementById('title-input');
        var slugInput = document.getElementById('slug-input');
        if (!titleInput || !slugInput) {
            return;
        }

        var userTouchedSlug = slugInput.value.trim() !== '';

        slugInput.addEventListener('input', function () {
            userTouchedSlug = slugInput.value.trim() !== '';
        });

        titleInput.addEventListener('input', function () {
            if (userTouchedSlug) {
                return;
            }
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
