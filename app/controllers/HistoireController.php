<?php
namespace app\controllers;

use app\models\Section;
use app\models\Contenu;
use app\models\Image;

class HistoireController
{
    public function index()
    {
        $sectionModel = new Section();
        $section = $sectionModel->findOne(['slug' => 'histoire']);

        if (!$section) {
            error_log("Section 'histoire' introuvable");
            \Flight::notFound();
            return;
        }

        $contenuModel = new Contenu();
        $articles = $contenuModel->findAll(
            ['section_id' => $section->id, 'deleted_at' => null],
            ['order' => 'created_at DESC']   // On peut trier par date, ou par ordre personnalisé si besoin
        );

        // Récupération des images
        $imageModel = new Image();
        foreach ($articles as $article) {
            $article->images = $imageModel->findAll(
                ['content_id' => $article->id],
                ['order' => 'display_order ASC']
            );
            $article->image_principale = $article->images[0] ?? null;
        }

        // Préparation des données pour la vue
        $data = [
            'title'           => $section->meta_title ?? $section->title . ' — IranWatch',
            'metaDescription' => $section->meta_description ?? 'Retour sur l\'histoire des tensions entre l\'Iran, les États-Unis et Israël.',
            'currentPage'     => 'histoire',
            'articles'        => $articles,
            'section'         => $section,
        ];

        $content = \Flight::view()->fetch('front_office/pages/histoire', $data);
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
        $imageModel = new Image();
        $article->images = $imageModel->findAll(
            ['content_id' => $article->id],
            ['order' => 'display_order ASC']
        );
        $article->image_principale = $article->images[0] ?? null;

        // Charger la section
        $sectionModel = new Section();
        $article->section = $sectionModel->findOne(['id' => $article->section_id]);

        $data = [
            'title'           => $article->meta_title ?? $article->title . ' — IranWatch',
            'metaDescription' => $article->meta_description ?? strip_tags($article->summary),
            'currentPage'     => 'histoire',
            'article'         => $article,
        ];

        $content = \Flight::view()->fetch('front_office/pages/article', $data);
        \Flight::render('front_office/layouts/main', array_merge($data, ['content' => $content]));
    }
}