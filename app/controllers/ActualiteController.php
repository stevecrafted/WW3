<?php
namespace app\controllers;

use app\models\Section;
use app\models\Contenu;

class ActualiteController
{ 

    public function index()
    {
        $sectionModel = new Section();
        $section = $sectionModel->findOne(['slug' => 'actualite']);

        if (!$section) {
            error_log("Pas de section");
            \Flight::notFound();
            return;
        }

        $contenuModel = new Contenu();
        $articles = $contenuModel->findAll(
            ['section_id' => $section->id, 'deleted_at' => null],
            ['order' => 'created_at DESC']
        );

        // error_log("Nombre d'articles : " . count($articles));
        if (count($articles) == 0) {
            error_log("Yst article");
        } 

        // Pour chaque article, charger ses images (si nécessaire)
        $imageModel = new \app\models\Image();
        foreach ($articles as $article) {
            $article->images = $imageModel->findAll(['content_id' => $article->id], ['order' => 'created_at ASC']);
        }

        $data = [
            'title'           => $section->meta_title ?? $section->title . ' — IranWatch',
            'metaDescription' => $section->meta_description ?? 'Suivez l’actualité du conflit Iran-USA-Israël',
            'currentPage'     => 'actualite',
            'articles'        => $articles,
        ];

        $content = \Flight::view()->fetch('front_office/pages/actualite', $data);
        \Flight::render('front_office/layouts/main', array_merge($data, ['content' => $content]));
    }

    public function show($slug)
    {
        $contenuModel = new Contenu();
        $article = $contenuModel->findOne(['slug' => $slug, 'deleted_at' => null]);

        if (!$article) {
            \Flight::notFound();
            return;
        }

        // Charger les images
        $imageModel = new \app\models\Image();
        $article->images = $imageModel->findAll(['id_contenu' => $article->id], ['order' => 'ordre ASC']);

        // Charger la section
        $sectionModel = new Section();
        $article->section = $sectionModel->findOne(['id' => $article->section_id]);

        $data = [
            'title'           => $article->meta_title ?? $article->title . ' — IranWatch',
            'metaDescription' => $article->meta_description ?? strip_tags($article->resume),
            'currentPage'     => 'actualite',
            'article'         => $article,
        ];

        $content = \Flight::view()->fetch('front_office/pages/article', $data);
        \Flight::render('front_office/layouts/main', array_merge($data, ['content' => $content]));
    }
}