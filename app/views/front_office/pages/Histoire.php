<?php
/**
 * @var array $articles Liste des articles (objets stdClass)
 * @var object $section Section actuelle
 */

// Découper les articles (on peut garder une logique similaire)
$hero = $articles[0] ?? null;
$secondaries = array_slice($articles, 1, 2);
$others = array_slice($articles, 3);
?>

<div class="section-header">
    <h1 class="section-header__title">Histoire</h1>
    <div class="section-header__line" aria-hidden="true"></div>
    <span class="section-header__date" id="js-date-long"></span>
</div>

<section aria-labelledby="histoire-title">
    <h2 id="histoire-title" class="visually-hidden">Histoire du conflit</h2>

    <div class="une-grid">

        <?php if ($hero): ?>
        <article class="article article-hero" itemscope itemtype="https://schema.org/Article">
            <?php if (!empty($hero->image_principale)): ?>
                <img src="<?= htmlspecialchars($hero->image_principale->url) ?>" 
                     alt="<?= htmlspecialchars($hero->image_principale->alt_text ?? $hero->title) ?>" 
                     class="article__img" itemprop="image">
            <?php else: ?>
                <div class="img-placeholder article__img" style="width:100%;aspect-ratio:16/9;">IMAGE</div>
            <?php endif; ?>
            <span class="article__tag" itemprop="articleSection">Événement historique</span>
            <h2 class="article__title" itemprop="headline">
                <a href="/histoire/<?= htmlspecialchars($hero->slug) ?>"><?= htmlspecialchars($hero->title) ?></a>
            </h2>
            <p class="article__chapo" itemprop="description">
                <?= htmlspecialchars($hero->summary ?? substr(strip_tags($hero->content_text), 0, 200)) ?>
            </p>
            <div class="article__meta">
                <time class="article__meta-time" datetime="<?= $hero->created_at ?>" itemprop="datePublished">
                    <?= date('d/m/Y', strtotime($hero->created_at)) ?>
                </time>
                <span>IranWatch</span>
            </div>
        </article>
        <?php endif; ?>

        <?php foreach ($secondaries as $article): ?>
        <article class="article article-secondary" itemscope itemtype="https://schema.org/Article">
            <?php if (!empty($article->image_principale)): ?>
                <img src="<?= htmlspecialchars($article->image_principale->url) ?>" 
                     alt="<?= htmlspecialchars($article->image_principale->alt_text ?? $article->title) ?>" 
                     class="article__img">
            <?php else: ?>
                <div class="img-placeholder article__img" style="width:100%;aspect-ratio:4/3;">IMAGE</div>
            <?php endif; ?>
            <h3 class="article__title" itemprop="headline">
                <a href="/histoire/<?= htmlspecialchars($article->slug) ?>"><?= htmlspecialchars($article->title) ?></a>
            </h3>
            <div class="article__meta">
                <time class="article__meta-time" datetime="<?= $article->created_at ?>">
                    <?= date('d/m/Y', strtotime($article->created_at)) ?>
                </time>
            </div>
        </article>
        <?php endforeach; ?>

    </div>
</section>

<div class="content-with-sidebar">
    <section aria-labelledby="chronologie-title">
        <h2 class="subsection-title" id="chronologie-title">Chronologie des événements</h2>

        <div class="articles-list" role="feed">
            <?php foreach ($others as $article): ?>
            <article class="article article--inline" itemscope itemtype="https://schema.org/Article">
                <?php if (!empty($article->image_principale)): ?>
                    <img src="<?= htmlspecialchars($article->image_principale->url) ?>" 
                         alt="<?= htmlspecialchars($article->image_principale->alt_text ?? $article->title) ?>" 
                         class="article__img" loading="lazy">
                <?php else: ?>
                    <div class="img-placeholder article__img" style="width:120px;height:90px;">IMG</div>
                <?php endif; ?>
                <div class="article__body">
                    <span class="article__tag">Histoire</span>
                    <h3 class="article__title" itemprop="headline">
                        <a href="/histoire/<?= htmlspecialchars($article->slug) ?>"><?= htmlspecialchars($article->title) ?></a>
                    </h3>
                    <p class="article__chapo" itemprop="description">
                        <?= htmlspecialchars($article->summary ?? substr(strip_tags($article->content_text), 0, 150)) ?>
                    </p>
                    <div class="article__meta">
                        <time class="article__meta-time" datetime="<?= $article->created_at ?>">
                            <?= date('d/m/Y', strtotime($article->created_at)) ?>
                        </time>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

</div>