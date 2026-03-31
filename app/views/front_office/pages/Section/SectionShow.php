<?php
/**
 * Vue générique pour l'affichage du détail d'un article d'une section.
 *
 * @var object $article Objet article (stdClass)
 * @var object $section Objet section (stdClass)
 */
?>

<article class="article-full" itemscope itemtype="https://schema.org/NewsArticle">

    <!-- EN-TÊTE ARTICLE -->
    <header class="article-full__header">
        <span class="article__tag" itemprop="articleSection">
            <?= htmlspecialchars($section->name) ?>
        </span>

        <h1 class="article-full__title" itemprop="headline">
            <?= htmlspecialchars($article->title) ?>
        </h1>

        <?php if (!empty($article->summary)): ?>
        <p class="article-full__chapo" itemprop="description">
            <?= htmlspecialchars($article->summary) ?>
        </p>
        <?php endif; ?>

        <div class="article__meta">
            <time class="article__meta-time"
                  datetime="<?= htmlspecialchars($article->created_at) ?>"
                  itemprop="datePublished">
                <?= date('d/m/Y H:i', strtotime($article->created_at)) ?>
            </time>
            <?php if ($article->updated_at !== $article->created_at): ?>
            <time class="article__meta-time article__meta-time--updated"
                  datetime="<?= htmlspecialchars($article->updated_at) ?>"
                  itemprop="dateModified">
                · Mis à jour le <?= date('d/m/Y H:i', strtotime($article->updated_at)) ?>
            </time>
            <?php endif; ?>
            <span>IranWatch</span>
        </div>
    </header>

    <!-- IMAGE PRINCIPALE -->
    <?php if (!empty($article->image_principale)): ?>
    <figure class="article-full__figure">
        <img src="<?= htmlspecialchars($article->image_principale->url) ?>"
             alt="<?= htmlspecialchars($article->image_principale->alt_text ?? $article->title) ?>"
             class="article-full__img"
             itemprop="image">
    </figure>
    <?php endif; ?>

    <!-- CORPS DE L'ARTICLE -->
    <div class="article-full__body" itemprop="articleBody">
        <?= $article->content_text ?? '' ?>
    </div>

    <!-- GALERIE (images supplémentaires) -->
    <?php if (!empty($article->images) && count($article->images) > 1): ?>
    <section class="article-full__gallery" aria-label="Galerie photos">
        <h2 class="subsection-title">Photos</h2>
        <div class="gallery-grid">
            <?php foreach (array_slice($article->images, 1) as $image): ?>
            <figure class="gallery-grid__item">
                <img src="<?= htmlspecialchars($image->url) ?>"
                     alt="<?= htmlspecialchars($image->alt_text ?? $article->title) ?>"
                     loading="lazy">
            </figure>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- RETOUR À LA SECTION -->
    <div class="article-full__back">
        <a href="/<?= htmlspecialchars($section->slug) ?>" class="btn-back">
            ← Retour à <?= htmlspecialchars($section->name) ?>
        </a>
    </div>

</article>