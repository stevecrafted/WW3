<?php
/**
 * @var array $articles Liste des articles (objets stdClass)
 * @var object $section Section actuelle (pour les infos)
 */

$hero = $articles[0] ?? null; 
$others = array_slice($articles, 1);
?>

<!-- EN-TÊTE DE SECTION -->
<div class="section-header">
    <h1 class="section-header__title">Actualité Guerre en Iran</h1>
    <div class="section-header__line" aria-hidden="true"></div>
    <span class="section-header__date" id="js-date-long"></span>
</div>

<!-- ── À LA UNE ──────────────────────────────────────────── -->
<section aria-labelledby="une-title">
    <h2 id="une-title" class="visually-hidden">À la une</h2>

    <div class="une-grid">

        <?php if ($hero): ?>
        <!-- ARTICLE HÉRO -->
        <article class="article article-hero" itemscope itemtype="https://schema.org/NewsArticle">
            <?php if (!empty($hero->image_principale)): ?>
                <img src="<?= htmlspecialchars($hero->image_principale->url) ?>" 
                     alt="<?= htmlspecialchars($hero->image_principale->alt_text ?? $hero->title) ?>" 
                     class="article__img" itemprop="image">
            <?php else: ?>
                <div class="img-placeholder article__img" style="width:100%;aspect-ratio:16/9;">IMAGE</div>
            <?php endif; ?>

            <span class="article__tag" itemprop="articleSection">Actualité</span>
            <h2 class="article__title" itemprop="headline">
                <a href="/actualite/<?= htmlspecialchars($hero->slug) ?>"><?= htmlspecialchars($hero->title) ?></a>
            </h2>
            <p class="article__chapo" itemprop="description">
                <?= htmlspecialchars($hero->summary ?? substr(strip_tags($hero->content_text), 0, 200)) ?>
            </p>
            <div class="article__meta">
                <time class="article__meta-time" datetime="<?= $hero->created_at ?>" itemprop="datePublished">
                    <?= date('d/m/Y H:i', strtotime($hero->created_at)) ?>
                </time>
                <span>IranWatch</span>
            </div>
        </article>
        <?php endif; ?>

    </div>
</section>

<!-- ── CONTENU PRINCIPAL + SIDEBAR (optionnel) ─────────────────── -->
<div class="content-with-sidebar">

    <!-- FLUX D'ACTUALITÉS (scroll vertical) -->
    <section aria-labelledby="flux-title">
        <h2 class="subsection-title" id="flux-title">Toutes les actualités</h2>

        <div class="articles-list" role="feed" aria-busy="false">

            <?php foreach ($others as $article): ?>
            <!-- Article inline -->
            <article class="article article--inline" itemscope itemtype="https://schema.org/NewsArticle">
                <?php if (!empty($article->image_principale)): ?>
                    <img src="<?= htmlspecialchars($article->image_principale->url) ?>" 
                         alt="<?= htmlspecialchars($article->image_principale->alt_text ?? $article->title) ?>" 
                         class="article__img" loading="lazy">
                <?php else: ?>
                    <div class="img-placeholder article__img" style="width:120px;height:90px;">IMG</div>
                <?php endif; ?>
                <div class="article__body">
                    <span class="article__tag">Actualité</span>
                    <h3 class="article__title" itemprop="headline">
                        <a href="/actualite/<?= htmlspecialchars($article->slug) ?>"><?= htmlspecialchars($article->title) ?></a>
                    </h3>
                    <p class="article__chapo" itemprop="description">
                        <?= htmlspecialchars($article->summary ?? substr(strip_tags($article->content_text), 0, 150)) ?>
                    </p>
                    <div class="article__meta">
                        <time class="article__meta-time" datetime="<?= $article->created_at ?>">
                            <?= date('d/m/Y H:i', strtotime($article->created_at)) ?>
                        </time>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>

        </div>
    </section>

</div>