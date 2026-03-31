<?php

$section      = $section      ?? null;
$contents     = $contents     ?? [];
$filters      = $filters      ?? ['keyword' => '', 'title' => '', 'slug' => ''];
$page         = $page         ?? 1;
$totalPages   = $totalPages   ?? 1;
$visiblePages = $visiblePages ?? [1];
$total        = $total        ?? 0;
$notice       = $notice       ?? '';

$sectionId = (int) ($section->id ?? 0);

$buildUrl = static function (array $params = []) use ($filters, $page, $sectionId): string {
    $base = [
        'keyword' => $filters['keyword'] ?? '',
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
    return '/admin/sections/' . $sectionId . '/contents'
        . (!empty($clean) ? ('?' . http_build_query($clean)) : '');
};
?>

<div class="bo-shell">

    <!-- ── PAGE HEADER ─────────────────────────────────────────── -->
    <div class="bo-page-header">
        <a href="/admin/sections" class="btn btn--outline btn--sm">&larr; Sections</a>
        <span class="bo-page-header__label">
            <?= htmlspecialchars($section->name ?? '') ?>
        </span>
        <h1 class="bo-title">Contenus</h1>
        <div class="bo-page-header__line"></div>
        <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/create') ?>"
           class="btn btn--primary btn--sm">
            + Nouveau contenu
        </a>
    </div>

    <?php if ($notice !== ''): ?>
        <p class="bo-notice"><?= htmlspecialchars($notice) ?></p>
    <?php endif; ?>

    <!-- ── CARD LISTE ──────────────────────────────────────────── -->
    <section class="bo-card" aria-label="Liste des contenus">

        <div class="bo-card__header">
            <span class="bo-card__title">
                <?= htmlspecialchars($section->title ?? '') ?>
            </span>
            <span class="bo-card__count"><?= (int) $total ?> contenu<?= $total > 1 ? 's' : '' ?></span>
        </div>

        <div class="bo-card__body">

            <!-- Filtres -->
            <form method="GET"
                  action="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents') ?>"
                  class="bo-toolbar">
                <input type="text" name="keyword"
                       value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>"
                       placeholder="Recherche globale…" />
                <input type="text" name="title"
                       value="<?= htmlspecialchars($filters['title'] ?? '') ?>"
                       placeholder="Titre…" />
                <input type="text" name="slug"
                       value="<?= htmlspecialchars($filters['slug'] ?? '') ?>"
                       placeholder="Slug…" />
                <button type="submit" class="btn btn--primary btn--sm">Filtrer</button>
                <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents') ?>"
                   class="btn btn--outline btn--sm">Réinitialiser</a>
            </form>

            <!-- Liste -->
            <div class="bo-list" role="list">

                <?php if (empty($contents)): ?>
                    <div class="bo-empty">Aucun contenu trouvé.</div>
                <?php endif; ?>

                <?php foreach ($contents as $item): ?>
                    <article
                        class="bo-row bo-row--content"
                        role="listitem"
                        onclick="window.location.href='<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/' . (int) $item->id) ?>'">

                        <!-- Vignette -->
                        <?php if (!empty($item->first_image)): ?>
                            <img
                                class="bo-row__thumb"
                                src="<?= htmlspecialchars((string) ($item->first_image->url ?? '')) ?>"
                                alt="<?= htmlspecialchars((string) ($item->first_image->alt_text ?? '')) ?>"
                                loading="lazy"
                            />
                        <?php else: ?>
                            <div class="bo-row__thumb bo-row__thumb--empty" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="1"/>
                                    <path d="M3 16l5-5 4 4 3-3 6 6"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <!-- Infos -->
                        <div class="bo-row__body">
                            <div class="bo-row__name">
                                <?= htmlspecialchars($item->title) ?>
                            </div>
                            <div class="bo-row__meta">
                                <span>
                                    <span class="bo-row__meta-label">Meta&nbsp;: </span>
                                    <?= htmlspecialchars((string) ($item->meta_title ?? '—')) ?>
                                </span>
                                <span>
                                    <span class="bo-row__meta-label">Slug&nbsp;: </span>
                                    <span class="bo-row__slug"><?= htmlspecialchars($item->slug) ?></span>
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bo-row__actions" onclick="event.stopPropagation();">
                            <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/' . (int) $item->id) ?>"
                               class="btn btn--outline btn--sm">Voir</a>
                            <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/' . (int) $item->id . '/edit') ?>"
                               class="btn btn--outline btn--sm">Modifier</a>
                            <form method="POST"
                                  action="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents/' . (int) $item->id . '/delete') ?>"
                                  onsubmit="return confirm('Supprimer ce contenu ?');">
                                <button type="submit" class="btn btn--danger btn--sm">Supprimer</button>
                            </form>
                        </div>

                    </article>
                <?php endforeach; ?>

            </div><!-- /.bo-list -->

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav class="bo-pagination" aria-label="Pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?= htmlspecialchars($buildUrl(['page' => $page - 1])) ?>"
                           aria-label="Page précédente">&lsaquo;</a>
                    <?php endif; ?>

                    <?php foreach ($visiblePages as $p): ?>
                        <?php if ((int) $p === (int) $page): ?>
                            <span class="is-current"><?= (int) $p ?></span>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($buildUrl(['page' => (int) $p])) ?>">
                                <?= (int) $p ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= htmlspecialchars($buildUrl(['page' => $page + 1])) ?>"
                           aria-label="Page suivante">&rsaquo;</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

        </div><!-- /.bo-card__body -->
    </section>

</div><!-- /.bo-shell -->