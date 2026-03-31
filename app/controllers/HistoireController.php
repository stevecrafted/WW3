<?php
namespace app\controllers;

use app\models\Section;
use app\models\Contenu;
use app\models\Image;

class HistoireController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sectionModel = new Section();
        $section = $sectionModel->findOne(['slug' => 'histoire']);

        if (!$section) {
            error_log("Section 'histoire' introuvable");
            $this->notFound();
            return;
        }

        $contenuModel = new Contenu();
        $articles = $contenuModel->getFrontArticlesBySectionId((int) $section->id);

        // Récupération des images
        $imageModel = new Image();
        foreach ($articles as $article) {
            $article->images = $imageModel->getFrontImagesByContentId((int) $article->id);
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

        $this->render('Histoire', $data);
    }

    public function show(int $id, ?string $slug = null)
    {
        $contenuModel = new Contenu();
        $article = $contenuModel->getFrontArticleById($id);

        if (!$article) {
            $this->notFound();
            return;
        }

        $sectionModel = new Section();
        $section = $sectionModel->findOne(['id' => $article->section_id]);
        if (!$section || $section->slug !== 'histoire') {
            $this->notFound();
            return;
        }

        if ($slug === null || $slug !== $article->slug) {
            header('Location: /histoire/' . $article->id . '-' . $article->slug, true, 301);
            exit;
        }

        // Charger les images
        $imageModel = new Image();
        $article->images = $imageModel->getFrontImagesByContentId((int) $article->id);
        $article->image_principale = $article->images[0] ?? null;

        // Charger la section
        $article->section = $sectionModel->findOne(['id' => $article->section_id]);

        $data = [
            'title'           => $article->meta_title ?? $article->title . ' — IranWatch',
            'metaDescription' => $article->meta_description ?? strip_tags($article->summary),
            'currentPage'     => 'histoire',
            'article'         => $article,
        ];

        $this->render('Article', $data);
    }
}