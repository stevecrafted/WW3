<?php

namespace app\controllers;

use app\models\Contenu;
use app\models\Image;
use app\models\Section;

class SectionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(string $slug): void
    {
        $sectionModel = new Section();
        $section = $sectionModel->findOne(['slug' => $slug]);

        if (!$section) {
            $this->notFound();
            return;
        }

        $contenuModel = new Contenu();
        $articles = $contenuModel->getFrontArticlesBySectionId((int) $section->id);

        $imageModel = new Image();
        foreach ($articles as $article) {
            $article->images = $imageModel->getFrontImagesByContentId((int) $article->id);
            $article->image_principale = $article->images[0] ?? null;
        }

        $this->render('Section/SectionIndex', [
            'title'           => $section->meta_title ?? $section->title . ' - IranWatch',
            'metaDescription' => $section->meta_description ?? 'Suivez les actualités sur ' . $section->name,
            'currentPage'     => $section->slug,
            'articles'        => $articles,
            'section'         => $section,
        ]);
    }

       public function show(string $slug, int $id, ?string $articleSlug = null): void
    {
        // 1. Vérifier que la section existe
        $sectionModel = new Section();
        $section = $sectionModel->findOne(['slug' => $slug]);
 
        if (!$section) {
            $this->notFound();
            return;
        }
 
        // 2. Récupérer l'article
        $contenuModel = new Contenu();
        $article = $contenuModel->getFrontArticleById($id);
 
        if (!$article) {
            $this->notFound();
            return;
        }
 
        // 3. Vérifier que l'article appartient bien à cette section
        if ((int) $article->section_id !== (int) $section->id) {
            $this->notFound();
            return;
        }
 
        // 4. Canonicaliser l'URL si le slug article est absent ou incorrect
        if ($articleSlug === null || $articleSlug !== $article->slug) {
            header('Location: /' . $section->slug . '/' . $article->id . '-' . $article->slug, true, 301);
            exit;
        }
 
        // 5. Charger les images
        $imageModel = new Image();
        $article->images = $imageModel->getFrontImagesByContentId((int) $article->id);
        $article->image_principale = $article->images[0] ?? null;
 
        // 6. Attacher la section à l'article (utile dans la vue)
        $article->section = $section;
 
        $this->render('Section/SectionShow', [
            'title'           => $article->meta_title ?? $article->title . ' - IranWatch',
            'metaDescription' => $article->meta_description ?? strip_tags($article->summary ?? ''),
            'currentPage'     => $section->slug,
            'article'         => $article,
            'section'         => $section,
        ]);
    }
}