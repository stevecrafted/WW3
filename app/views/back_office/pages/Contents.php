<?php

$section = $section ?? null;
$contents = $contents ?? [];
$filters = $filters ?? ['keyword' => '', 'title' => '', 'slug' => ''];
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$visiblePages = $visiblePages ?? [1];
$total = $total ?? 0;
$notice = $notice ?? '';

$sectionId = (int) ($section->id ?? 0);

$buildUrl = static function (array $params = []) use ($filters, $page, $sectionId): string {
    $base = [
        'keyword' => $filters['keyword'] ?? '',
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

    $url = '/admin/sections/' . $sectionId . '/contents';
    return $url . (!empty($clean) ? ('?' . http_build_query($clean)) : '');
};
?>

<div class="bo-shell">
    <h1 class="bo-title">Liste des contenus de la section: <?= htmlspecialchars($section->title ?? '') ?></h1>

    <?php if ($notice !== ''): ?>
        <p class="bo-notice"><?= htmlspecialchars($notice) ?></p>
    <?php endif; ?>

    <section class="bo-card" aria-label="Liste des contenus">
        <form method="GET" action="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents') ?>" class="bo-toolbar">
            <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Recherche globale" />
            <input type="text" name="title" value="<?= htmlspecialchars($filters['title'] ?? '') ?>" placeholder="Titre" />
            <input type="text" name="slug" value="<?= htmlspecialchars($filters['slug'] ?? '') ?>" placeholder="Slug" />
            <button type="submit">Rechercher</button>
            <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/create') ?>">Ajouter</a>
            <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents') ?>">Reset</a>
        </form>

        <p class="bo-section-list-title">Contenus (<?= (int) $total ?>)</p>

        <div class="bo-list">
            <?php if (empty($contents)): ?>
                <div class="bo-empty">Aucun contenu trouvé.</div>
            <?php endif; ?>

            <?php foreach ($contents as $contentItem): ?>
                <article class="bo-row" style="align-items: center; gap: 16px;">
                    <?php if (!empty($contentItem->first_image)): ?>
                        <img src="<?= htmlspecialchars((string) ($contentItem->first_image->url ?? '')) ?>" alt="<?= htmlspecialchars((string) ($contentItem->first_image->alt_text ?? '')) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; flex-shrink: 0;" />
                    <?php else: ?>
                        <div style="width: 80px; height: 80px; background: #f0f0f0; border-radius: 4px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #999;">Pas d'image</div>
                    <?php endif; ?>
                    <div>
                        <div class="bo-row-head"><?= htmlspecialchars($contentItem->title) ?></div>
                        <div class="bo-row-sub">Meta titre: <?= htmlspecialchars((string) ($contentItem->meta_title ?? '')) ?></div>
                        <div class="bo-row-sub">Slug: <?= htmlspecialchars($contentItem->slug) ?></div>
                    </div>
                    <div class="bo-row-actions">
                        <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/' . (int) $contentItem->id . '/edit') ?>">Modifier</a>
                        <form method="POST" action="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/' . (int) $contentItem->id . '/delete') ?>" onsubmit="return confirm('Confirmer la suppression de ce contenu ?');">
                            <button type="submit" class="bo-btn-danger">Supprimer</button>
                        </form>
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
</div>
