<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Section;

class SectionAdminController extends BaseController
{
    private const PER_PAGE = 5;

    private Section $sectionModel;

    public function __construct()
    {
        $this->sectionModel = new Section();
    }

    public function index(): void
    {
        $filters = $this->extractFilters();
        $page = $this->sanitizePositiveInt($_GET['page'] ?? 1, 1);

        $result = $this->sectionModel->searchWithPagination($filters, $page, self::PER_PAGE);

        $totalPages = max(1, (int) ceil($result['total'] / self::PER_PAGE));
        if ($page > $totalPages) {
            $page = $totalPages;
            $result = $this->sectionModel->searchWithPagination($filters, $page, self::PER_PAGE);
        }

        $editSection = null;
        $editId = $this->sanitizePositiveInt($_GET['edit'] ?? 0, 0);
        if ($editId > 0) {
            $editSection = $this->sectionModel->findById($editId);
        }

        $notice = isset($_GET['notice']) ? trim((string) $_GET['notice']) : '';

        $this->renderBackOffice('Sections', [
            'title' => 'Back Office - Gestion des sections',
            'sections' => $result['items'],
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => (int) $result['total'],
            'editSection' => $editSection,
            'notice' => $notice,
        ]);
    }

    public function save(): void
    {
        $id = $this->sanitizePositiveInt($_POST['id'] ?? 0, 0);

        $name = trim((string) ($_POST['name'] ?? ''));
        $title = trim((string) ($_POST['title'] ?? ''));
        $slugInput = trim((string) ($_POST['slug'] ?? ''));

        if ($name === '' || $title === '') {
            $this->redirectWithNotice('Champs obligatoires manquants', $id > 0 ? $id : null);
            return;
        }

        if (mb_strlen($name) > 255 || mb_strlen($title) > 255 || mb_strlen($slugInput) > 255) {
            $this->redirectWithNotice('Un champ depasse 255 caracteres', $id > 0 ? $id : null);
            return;
        }

        $slug = $slugInput !== ''
            ? $this->sectionModel->generateUniqueSlug($slugInput, $id > 0 ? $id : null)
            : $this->sectionModel->generateUniqueSlug($title, $id > 0 ? $id : null);

        if ($id > 0) {
            $ok = $this->sectionModel->updateSection($id, [
                'name' => $name,
                'title' => $title,
                'slug' => $slug,
            ]);

            $this->redirectWithNotice($ok ? 'Section modifiee' : 'Modification impossible', $ok ? null : $id);
            return;
        }

        $newId = $this->sectionModel->createSection([
            'name' => $name,
            'title' => $title,
            'slug' => $slug,
        ]);

        $this->redirectWithNotice($newId > 0 ? 'Section ajoutee' : 'Creation impossible', null);
    }

    public function delete(): void
    {
        $id = $this->sanitizePositiveInt($_POST['id'] ?? 0, 0);
        if ($id <= 0) {
            $this->redirectWithNotice('Section invalide', null);
            return;
        }

        $ok = $this->sectionModel->softDelete($id);
        $this->redirectWithNotice($ok ? 'Section supprimee' : 'Suppression impossible', null);
    }

    private function extractFilters(): array
    {
        return [
            'keyword' => trim((string) ($_GET['keyword'] ?? '')),
            'name' => trim((string) ($_GET['name'] ?? '')),
            'title' => trim((string) ($_GET['title'] ?? '')),
            'slug' => trim((string) ($_GET['slug'] ?? '')),
        ];
    }

    private function sanitizePositiveInt($value, int $default): int
    {
        $candidate = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0],
        ]);

        return $candidate === false ? $default : (int) $candidate;
    }

    private function redirectWithNotice(string $message, ?int $editId): void
    {
        $params = [];
        if ($message !== '') {
            $params['notice'] = $message;
        }
        if ($editId !== null && $editId > 0) {
            $params['edit'] = (string) $editId;
        }

        $url = '/admin/sections';
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        header('Location: ' . $url);
        exit;
    }
}
