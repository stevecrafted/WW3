<?php

$section = $section ?? null;
$content = $content ?? null;
$images  = $images  ?? [];

$sectionId = (int) ($section->id ?? 0);
$contentId = (int) ($content->id ?? 0);
$backUrl   = '/admin/sections/' . $sectionId . '/contents';
$editUrl   = '/admin/sections/' . $sectionId . '/contents/' . $contentId . '/edit';
?>

<div class="bo-shell">

    <!-- ── PAGE HEADER ──────────────────────────────────────────── -->
    <div class="bo-page-header">
        <a href="<?= htmlspecialchars($backUrl) ?>" class="btn btn--outline btn--sm">
            &larr; Retour
        </a>
        <span class="bo-page-header__label">Contenu</span>
        <h1 class="bo-title"><?= htmlspecialchars($content->title ?? 'Sans titre') ?></h1>
        <div class="bo-page-header__line"></div>
        <a href="<?= htmlspecialchars($editUrl) ?>" class="btn btn--primary btn--sm">Modifier</a>
    </div>

    <!-- ── GRILLE ────────────────────────────────────────────────── -->
    <div class="bo-grid" style="grid-template-columns: 1fr 280px;">

        <!-- ── COLONNE PRINCIPALE ──────────────────────────────── -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- SEO -->
            <section class="bo-card" aria-label="SEO">
                <div class="bo-card__header">
                    <span class="bo-card__title">SEO &amp; métadonnées</span>
                    <span class="bo-card__count">
                        <?= mb_strlen($content->meta_description ?? '') ?> / 160 car.
                    </span>
                </div>
                <div class="bo-card__body">

                    <div class="bo-meta-row">
                        <span class="bo-meta-label">Meta titre</span>
                        <span class="bo-meta-value"><?= htmlspecialchars($content->meta_title ?? '—') ?></span>
                    </div>

                    <div class="bo-meta-row">
                        <span class="bo-meta-label">Slug</span>
                        <code class="bo-slug"><?= htmlspecialchars($content->slug ?? '—') ?></code>
                    </div>

                    <div class="bo-meta-row" style="border-bottom:none;">
                        <span class="bo-meta-label">Meta description</span>
                        <span class="bo-meta-value bo-meta-value--muted">
                            <?= htmlspecialchars($content->meta_description ?? '—') ?>
                        </span>
                    </div>

                </div>
            </section>

            <!-- CONTENU ÉDITORIAL -->
            <section class="bo-card" aria-label="Contenu éditorial">
                <div class="bo-card__header">
                    <span class="bo-card__title">Contenu</span>
                </div>
                <div class="bo-card__body">

                    <!-- Titre + résumé -->
                    <h2 class="bo-content__title">
                        <?= htmlspecialchars($content->title ?? '') ?>
                    </h2>

                    <?php if (!empty($content->summary)): ?>
                        <p class="bo-content__summary">
                            <?= htmlspecialchars($content->summary) ?>
                        </p>
                    <?php endif; ?>

                    <!-- Corps HTML (TinyMCE) -->
                    <div class="bo-content__label">Corps de l'article</div>
                    <div class="bo-content__body">
                        <?= $content->content_text ?? '<em style="color:var(--gris-clair)">Aucun contenu.</em>' ?>
                    </div>

                </div>
            </section>

            <!-- IMAGES -->
            <?php if (!empty($images)): ?>
                <section class="bo-card" aria-label="Images">
                    <div class="bo-card__header">
                        <span class="bo-card__title">Images</span>
                        <span class="bo-card__count"><?= count($images) ?> fichier<?= count($images) > 1 ? 's' : '' ?></span>
                    </div>
                    <div class="bo-card__body">
                        <div class="bo-images-grid">
                            <?php foreach ($images as $img): ?>
                                <figure class="bo-img-card">
                                    <img
                                        src="<?= htmlspecialchars((string) ($img->url ?? '')) ?>"
                                        alt="<?= htmlspecialchars((string) ($img->alt_text ?? '')) ?>"
                                        class="bo-img-card__img"
                                        loading="lazy"
                                    />
                                    <figcaption class="bo-img-card__caption">
                                        <div class="bo-img-card__row">
                                            <span class="bo-meta-label">Alt</span>
                                            <span><?= htmlspecialchars((string) ($img->alt_text ?? '—')) ?></span>
                                        </div>
                                        <div class="bo-img-card__row">
                                            <span class="bo-meta-label">Ordre</span>
                                            <strong><?= (int) ($img->display_order ?? 0) ?></strong>
                                        </div>
                                    </figcaption>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

        </div><!-- /colonne principale -->

        <!-- ── SIDEBAR ──────────────────────────────────────────── -->
        <aside class="bo-card" aria-label="Informations">
            <div class="bo-card__header">
                <span class="bo-card__title">Informations</span>
            </div>
            <div class="bo-card__body" style="padding:0;">

                <div class="bo-info-row">
                    <span class="bo-info-label">Section</span>
                    <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents') ?>"
                       class="bo-info-link">
                        <?= htmlspecialchars($section->title ?? '—') ?>
                    </a>
                </div>

                <div class="bo-info-row">
                    <span class="bo-info-label">ID contenu</span>
                    <span class="bo-info-value">#<?= $contentId ?></span>
                </div>

                <div class="bo-info-row">
                    <span class="bo-info-label">Créé le</span>
                    <span class="bo-info-value">
                        <?= !empty($content->created_at)
                            ? date('d/m/Y', strtotime((string) $content->created_at))
                            : 'N/A' ?>
                    </span>
                </div>

                <div class="bo-info-row">
                    <span class="bo-info-label">À</span>
                    <span class="bo-info-value">
                        <?= !empty($content->created_at)
                            ? date('H:i', strtotime((string) $content->created_at))
                            : '' ?>
                    </span>
                </div>

                <div class="bo-info-row">
                    <span class="bo-info-label">Modifié le</span>
                    <span class="bo-info-value">
                        <?= !empty($content->updated_at)
                            ? date('d/m/Y H:i', strtotime((string) $content->updated_at))
                            : 'Jamais' ?>
                    </span>
                </div>

                <div class="bo-info-row" style="border-bottom:none;">
                    <span class="bo-info-label">Images</span>
                    <span class="bo-info-value" style="font-weight:700;color:var(--rouge);">
                        <?= count($images) ?>
                    </span>
                </div>

            </div>
        </aside>

    </div><!-- /.bo-grid -->
</div><!-- /.bo-shell -->