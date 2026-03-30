<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Contenu;
use app\models\Image;
use app\models\Section;
use app\utils\ImageUploader;

class ContentAdminController extends BaseController
{
    private const PER_PAGE = 5;
    private const PAGE_WINDOW = 2;

    private Section $sectionModel;
    private Contenu $contentModel;
    private Image $imageModel;
    private ImageUploader $uploader;

    public function __construct()
    {
        $this->sectionModel = new Section();
        $this->contentModel = new Contenu();
        $this->imageModel = new Image();
        $this->uploader = new ImageUploader('uploads/contents');
    }

    public function index(int $sectionId): void
    {
        $section = $this->sectionModel->findById($sectionId);
        if (!$section) {
            $this->notFound();
            return;
        }

        $filters = [
            'keyword' => trim((string) ($_GET['keyword'] ?? '')),
            'title' => trim((string) ($_GET['title'] ?? '')),
            'slug' => trim((string) ($_GET['slug'] ?? '')),
        ];
        $page = $this->sanitizePositiveInt($_GET['page'] ?? 1, 1);

        $result = $this->contentModel->searchBySectionWithPagination($sectionId, $filters, $page, self::PER_PAGE);
        $totalPages = max(1, (int) ceil($result['total'] / self::PER_PAGE));

        if ($page > $totalPages) {
            $page = $totalPages;
            $result = $this->contentModel->searchBySectionWithPagination($sectionId, $filters, $page, self::PER_PAGE);
        }

        $notice = isset($_GET['notice']) ? trim((string) $_GET['notice']) : '';

        // Charger les images pour chaque contenu
        $contentsWithImages = [];
        foreach ($result['items'] as $contentItem) {
            $images = $this->imageModel->findAll([
                'content_id' => (int) $contentItem->id,
                'deleted_at' => null,
            ], ['order' => 'display_order ASC, id ASC']);
            $contentItem->first_image = !empty($images) ? $images[0] : null;
            $contentsWithImages[] = $contentItem;
        }

        $this->renderBackOffice('Contents', [
            'title' => 'Back Office - Contenus',
            'section' => $section,
            'contents' => $contentsWithImages,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
            'visiblePages' => $this->buildVisiblePages($page, $totalPages),
            'total' => (int) $result['total'],
            'notice' => $notice,
        ]);
    }

    public function createForm(int $sectionId): void
    {
        $section = $this->sectionModel->findById($sectionId);
        if (!$section) {
            $this->notFound();
            return;
        }

        $this->renderBackOffice('ContentForm', [
            'title' => 'Back Office - Nouveau contenu',
            'section' => $section,
            'contentItem' => null,
            'images' => [],
            'notice' => trim((string) ($_GET['notice'] ?? '')),
        ]);
    }

    public function editForm(int $sectionId, int $contentId): void
    {
        $section = $this->sectionModel->findById($sectionId);
        if (!$section) {
            $this->notFound();
            return;
        }

        $contentItem = $this->contentModel->findById($contentId);
        if (!$contentItem || (int) $contentItem->section_id !== $sectionId) {
            $this->notFound();
            return;
        }

        $images = $this->imageModel->findAll([
            'content_id' => $contentId,
            'deleted_at' => null,
        ], ['order' => 'display_order ASC, id ASC']);

        $this->renderBackOffice('ContentForm', [
            'title' => 'Back Office - Modifier contenu',
            'section' => $section,
            'contentItem' => $contentItem,
            'images' => $images,
            'notice' => trim((string) ($_GET['notice'] ?? '')),
        ]);
    }

    public function save(int $sectionId): void
    {
        $section = $this->sectionModel->findById($sectionId);
        if (!$section) {
            $this->notFound();
            return;
        }

        $id = $this->sanitizePositiveInt($_POST['id'] ?? 0, 0);
        $metaTitle = trim((string) ($_POST['meta_title'] ?? ''));
        $slugInput = trim((string) ($_POST['slug'] ?? ''));
        $metaDescription = trim((string) ($_POST['meta_description'] ?? ''));
        $title = trim((string) ($_POST['title'] ?? ''));
        $summary = trim((string) ($_POST['summary'] ?? ''));
        $contentText = (string) ($_POST['content_text'] ?? '');

        if ($metaTitle === '' || $title === '') {
            $this->redirectToForm($sectionId, $id, 'Meta titre et titre sont obligatoires');
            return;
        }

        $slug = $slugInput !== ''
            ? $this->contentModel->generateUniqueSlug($slugInput, $id > 0 ? $id : null)
            : $this->contentModel->generateUniqueSlug($metaTitle, $id > 0 ? $id : null);

        $payload = [
            'meta_title' => $metaTitle,
            'slug' => $slug,
            'meta_description' => $metaDescription,
            'title' => $title,
            'summary' => $summary,
            'content_text' => $contentText,
        ];

        $contentId = 0;
        if ($id > 0) {
            $existing = $this->contentModel->findById($id);
            if (!$existing || (int) $existing->section_id !== $sectionId) {
                $this->notFound();
                return;
            }

            $ok = $this->contentModel->updateContent($id, $payload);
            if (!$ok) {
                $this->redirectToForm($sectionId, $id, 'Modification impossible');
                return;
            }
            $contentId = $id;
        } else {
            $contentId = $this->contentModel->createContent($sectionId, $payload);
            if ($contentId <= 0) {
                $this->redirectToForm($sectionId, null, 'Creation impossible');
                return;
            }
        }

        $uploaded = $this->uploader->uploadMany(
            $_FILES['images_file'] ?? [],
            $_POST['images_alt'] ?? [],
            $_POST['images_order'] ?? []
        );

        if (!empty($uploaded)) {
            $replaceImages = isset($_POST['replace_images']) && $_POST['replace_images'] === '1';
            if ($replaceImages) {
                $this->imageModel->softDeleteByContentId($contentId);
            }
            foreach ($uploaded as $imagePayload) {
                $this->imageModel->createImage($contentId, $imagePayload);
            }
        }

        header('Location: /admin/sections/' . $sectionId . '/contents?notice=' . urlencode('Contenu enregistre'));
        exit;
    }

    public function delete(int $sectionId, int $contentId): void
    {
        $section = $this->sectionModel->findById($sectionId);
        if (!$section) {
            $this->notFound();
            return;
        }

        $contentItem = $this->contentModel->findById($contentId);
        if (!$contentItem || (int) $contentItem->section_id !== $sectionId) {
            $this->notFound();
            return;
        }

        $ok = $this->contentModel->softDelete($contentId);
        if ($ok) {
            $this->imageModel->softDeleteByContentId($contentId);
        }

        header('Location: /admin/sections/' . $sectionId . '/contents?notice=' . urlencode($ok ? 'Contenu supprime' : 'Suppression impossible'));
        exit;
    }

    private function sanitizePositiveInt($value, int $default): int
    {
        $candidate = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0],
        ]);

        return $candidate === false ? $default : (int) $candidate;
    }

    private function buildVisiblePages(int $currentPage, int $totalPages): array
    {
        if ($totalPages <= 1) {
            return [1];
        }

        $start = max(1, $currentPage - self::PAGE_WINDOW);
        $end = min($totalPages, $currentPage + self::PAGE_WINDOW);

        $pages = [];
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        if (!in_array(1, $pages, true)) {
            array_unshift($pages, 1);
        }

        if (!in_array($totalPages, $pages, true)) {
            $pages[] = $totalPages;
        }

        return array_values(array_unique($pages));
    }

    private function redirectToForm(int $sectionId, ?int $contentId, string $notice): void
    {
        if ($contentId !== null && $contentId > 0) {
            $url = '/admin/sections/' . $sectionId . '/contents/' . $contentId . '/edit';
        } else {
            $url = '/admin/sections/' . $sectionId . '/contents/create';
        }

        $url .= '?notice=' . urlencode($notice);

        header('Location: ' . $url);
        exit;
    }

    private function parseLineValues(string $value): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];
        $items = [];

        foreach ($lines as $line) {
            $trimmed = trim((string) $line);
            if ($trimmed === '') {
                continue;
            }
            $items[] = $trimmed;
        }

        return $items;
    }
}
