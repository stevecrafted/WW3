<?php

$section = $section ?? null;
$content = $content ?? null;
$images = $images ?? [];

$sectionId = (int) ($section->id ?? 0);
$contentId = (int) ($content->id ?? 0);
$backUrl = '/admin/sections/' . $sectionId . '/contents';
$editUrl = '/admin/sections/' . $sectionId . '/contents/' . $contentId . '/edit';
?>

<div class="bo-shell">
    <div style="display: flex; gap: 12px; margin-bottom: 16px; align-items: center;">
        <a href="<?= htmlspecialchars($backUrl) ?>" style="display: inline-block; padding: 8px 12px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #333; font-size: 0.9rem;">← Retour</a>
        <h1 class="bo-title" style="margin: 0; flex: 1;"><?= htmlspecialchars($content->title ?? '') ?></h1>
        <a href="<?= htmlspecialchars($editUrl) ?>" style="display: inline-block; padding: 8px 16px; background: #f7f4e8; border: 2px solid #2c2c2c; border-radius: 10px; text-decoration: none; color: #1f1f1f; font-weight: 600; cursor: pointer;">Modifier</a>
    </div>

    <div class="bo-grid" style="grid-template-columns: 2fr 1fr; gap: 18px;">
        <!-- Contenu principal -->
        <section class="bo-card">
            <!-- Métadonnées -->
            <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid #ddd;">
                <div style="margin-bottom: 12px;">
                    <strong style="color: #666; font-size: 0.85rem;">Meta titre:</strong><br>
                    <?= htmlspecialchars($content->meta_title ?? '') ?>
                </div>
                <div style="margin-bottom: 12px;">
                    <strong style="color: #666; font-size: 0.85rem;">Slug:</strong><br>
                    <code style="background: #f5f5f5; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;"><?= htmlspecialchars($content->slug ?? '') ?></code>
                </div>
                <div>
                    <strong style="color: #666; font-size: 0.85rem;">Meta description:</strong><br>
                    <?= htmlspecialchars($content->meta_description ?? '') ?>
                </div>
            </div>

            <!-- Titre et résumé -->
            <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid #ddd;">
                <h2 style="margin: 0 0 8px 0; font-size: 1.3rem;"><?= htmlspecialchars($content->title ?? '') ?></h2>
                <div style="color: #666; line-height: 1.5;">
                    <?= htmlspecialchars($content->summary ?? '') ?>
                </div>
            </div>

            <!-- Contenu -->
            <div style="margin-bottom: 16px;">
                <h3 style="margin: 0 0 12px 0; font-size: 1rem;">Contenu</h3>
                <div style="background: #fafafa; padding: 12px; border-radius: 8px; border: 1px solid #ddd; line-height: 1.6;">
                    <?= $content->content_text ?? '' ?>
                </div>
            </div>

            <!-- Images -->
            <?php if (!empty($images)): ?>
                <div>
                    <h3 style="margin: 0 0 12px 0; font-size: 1rem;">Images</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                        <?php foreach ($images as $img): ?>
                            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #f9f9f9;">
                                <img src="<?= htmlspecialchars((string) ($img->url ?? '')) ?>" alt="<?= htmlspecialchars((string) ($img->alt_text ?? '')) ?>" style="width: 100%; height: 200px; object-fit: cover; display: block;" />
                                <div style="padding: 10px; border-top: 1px solid #ddd;">
                                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 4px;">
                                        <strong>Alt:</strong> <?= htmlspecialchars((string) ($img->alt_text ?? '')) ?>
                                    </div>
                                    <div style="font-size: 0.85rem; color: #666;">
                                        <strong>Ordre:</strong> <?= (int) ($img->display_order ?? 0) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Sidebar -->
        <aside class="bo-card">
            <h3 style="margin: 0 0 12px 0; font-size: 1rem;">Informations</h3>
            
            <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid #ddd;">
                <div style="color: #666; font-size: 0.85rem; margin-bottom: 2px;">Section</div>
                <div style="font-weight: 600;">
                    <a href="<?= htmlspecialchars('/admin/sections/' . $sectionId . '/contents') ?>" style="color: #2c2c2c; text-decoration: none;">
                        <?= htmlspecialchars($section->title ?? '') ?>
                    </a>
                </div>
            </div>

            <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid #ddd;">
                <div style="color: #666; font-size: 0.85rem; margin-bottom: 2px;">Créé</div>
                <div style="font-size: 0.9rem;">
                    <?= !empty($content->created_at) ? date('d/m/Y H:i', strtotime((string) $content->created_at)) : 'N/A' ?>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <div style="color: #666; font-size: 0.85rem; margin-bottom: 2px;">Modifié</div>
                <div style="font-size: 0.9rem;">
                    <?= !empty($content->updated_at) ? date('d/m/Y H:i', strtotime((string) $content->updated_at)) : 'Jamais' ?>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <div style="color: #666; font-size: 0.85rem; margin-bottom: 2px;">Nombre d'images</div>
                <div style="font-size: 0.9rem; font-weight: 600;">
                    <?= count($images) ?>
                </div>
            </div>
        </aside>
    </div>
</div>
