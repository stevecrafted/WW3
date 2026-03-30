<?php
/**
 * @var object $article
 */
?>

<div class="section-header">
    <h1 class="section-header__title"><?= htmlspecialchars($article->title ?? 'Article') ?></h1>
    <div class="section-header__line" aria-hidden="true"></div>
    <span class="section-header__date" id="js-date-long"></span>
</div>

<article class="article article-hero" itemscope itemtype="https://schema.org/NewsArticle">
    <?php if (!empty($article->image_principale)): ?>
        <img
            src="<?= htmlspecialchars($article->image_principale->url) ?>"
            alt="<?= htmlspecialchars($article->image_principale->alt_text ?? $article->title) ?>"
            class="article__img"
            itemprop="image">
    <?php endif; ?>

    <span class="article__tag" itemprop="articleSection">
        <?= htmlspecialchars($article->section->title ?? 'Article') ?>
    </span>

    <h2 class="article__title" itemprop="headline"><?= htmlspecialchars($article->title ?? '') ?></h2>

    <?php if (!empty($article->summary)): ?>
        <p class="article__chapo" itemprop="description"><?= htmlspecialchars($article->summary) ?></p>
    <?php endif; ?>

    <div class="article__meta">
        <time class="article__meta-time" datetime="<?= htmlspecialchars((string) ($article->created_at ?? '')) ?>" itemprop="datePublished">
            <?= !empty($article->created_at) ? date('d/m/Y H:i', strtotime((string) $article->created_at)) : '' ?>
        </time>
        <span>IranWatch</span>
    </div>

    <div class="article__body" style="margin-top: 1rem;">
        <?= nl2br(htmlspecialchars($article->content_text ?? '')) ?>
    </div>
</article>
