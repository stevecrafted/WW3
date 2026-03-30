<?php

namespace app\controllers;

use app\models\Contenu;
use app\models\Image;
use app\models\Section;

class ActualiteController extends BaseController
{
    public function index(): void
    {
        $sectionModel = new Section();
        $section = $sectionModel->findOne(['slug' => 'actualite']);

        if (!$section) {
            $this->notFound();
            return;
        }

        $contenuModel = new Contenu();
        $articles = $contenuModel->findAll(
            ['section_id' => $section->id, 'deleted_at' => null],
            ['order' => 'created_at DESC']
        );

        $imageModel = new Image();
        foreach ($articles as $article) {
            $article->images = $imageModel->findAll(
                ['content_id' => $article->id],
                ['order' => 'display_order ASC']
            );
            $article->image_principale = $article->images[0] ?? null;
        }

        $this->render('Actualite', [
            'title' => $section->meta_title ?? $section->title . ' - IranWatch',
            'metaDescription' => $section->meta_description ?? 'Suivez l actualite du conflit Iran-USA-Israel',
            'currentPage' => 'actualite',
            'articles' => $articles,
            'section' => $section,
        ]);
    }

    public function show(string $slug): void
    {
        $contenuModel = new Contenu();
        $article = $contenuModel->findOne(['slug' => $slug, 'deleted_at' => null]);

        if (!$article) {
            $this->notFound();
            return;
        }

        $imageModel = new Image();
        $article->images = $imageModel->findAll(
            ['content_id' => $article->id],
            ['order' => 'display_order ASC']
        );
        $article->image_principale = $article->images[0] ?? null;

        $sectionModel = new Section();
        $article->section = $sectionModel->findOne(['id' => $article->section_id]);

        $this->render('Article', [
            'title' => $article->meta_title ?? $article->title . ' - IranWatch',
            'metaDescription' => $article->meta_description ?? strip_tags($article->summary ?? ''),
            'currentPage' => 'actualite',
            'article' => $article,
        ]);
    }
}