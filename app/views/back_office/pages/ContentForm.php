<?php

$section = $section ?? null;
$contentItem = $contentItem ?? null;
$images = $images ?? [];
$notice = $notice ?? '';

$isEdit = $contentItem !== null;
$sectionId = (int) ($section->id ?? 0);
$formAction = '/admin/sections/' . $sectionId . '/contents/save';
$cancelUrl = '/admin/sections/' . $sectionId . '/contents';
?>

<div class="bo-shell">
    <h1 class="bo-title"><?= $isEdit ? 'Modifier un contenu' : 'Ajouter un contenu' ?> dans la section <?= htmlspecialchars($section->title ?? '') ?></h1>

    <?php if ($notice !== ''): ?>
        <p class="bo-notice"><?= htmlspecialchars($notice) ?></p>
    <?php endif; ?>

    <section class="bo-card" aria-label="Formulaire contenu">
        <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="bo-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= (int) ($contentItem->id ?? 0) ?>" />

            <label>
                Meta titre
                <input class="bo-field" type="text" name="meta_title" id="meta-title-input" required maxlength="255" value="<?= htmlspecialchars((string) ($contentItem->meta_title ?? '')) ?>" />
            </label>

            <label>
                Slug
                <input class="bo-field" type="text" name="slug" id="slug-input" maxlength="255" value="<?= htmlspecialchars((string) ($contentItem->slug ?? '')) ?>" />
            </label>

            <label>
                Meta description
                <textarea class="bo-field" name="meta_description" rows="3"><?= htmlspecialchars((string) ($contentItem->meta_description ?? '')) ?></textarea>
            </label>

            <label>
                Titre
                <input class="bo-field" type="text" name="title" required maxlength="255" value="<?= htmlspecialchars((string) ($contentItem->title ?? '')) ?>" />
            </label>

            <label>
                Resume
                <textarea class="bo-field" name="summary" rows="4"><?= htmlspecialchars((string) ($contentItem->summary ?? '')) ?></textarea>
            </label>

            <label>
                Contenu text
                <textarea class="bo-field" id="content-editor" name="content_text" rows="12"><?= htmlspecialchars((string) ($contentItem->content_text ?? '')) ?></textarea>
            </label>

            <fieldset class="bo-card" style="margin: 8px 0 0 0;">
                <legend>Images (la premiere image est la couverture)</legend>

                <?php if (!empty($images)): ?>
                    <div class="bo-list" style="margin-bottom: 20px;">
                        <h3 style="font-size: 0.95rem; color: #666; margin-bottom: 10px;">Images existantes</h3>
                        <?php foreach ($images as $img): ?>
                            <article class="bo-row" style="align-items: flex-start; gap: 12px;">
                                <div style="flex: 1;">
                                    <div class="bo-row-head" style="word-break: break-word;"><?= htmlspecialchars((string) ($img->url ?? '')) ?></div>
                                    <div class="bo-row-sub">Alt: <?= htmlspecialchars((string) ($img->alt_text ?? '')) ?></div>
                                    <div class="bo-row-sub">Ordre: <?= (int) ($img->display_order ?? 0) ?></div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div id="image-uploads-container" style="display: grid; gap: 16px; margin-bottom: 16px;">
                    <!-- Sections dynamiques pour les uploads vont ici -->
                </div>

                <button type="button" id="add-image-btn" class="btn-secondary" style="display: inline-block; padding: 8px 16px; background: #f0f0f0; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                    + Ajouter une nouvelle photo
                </button>

                <?php if ($isEdit): ?>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:12px;">
                        <input type="checkbox" name="replace_images" value="1" /> Remplacer les images existantes si nouvelles images uploadées
                    </label>
                <?php endif; ?>
            </fieldset>

            <div class="bo-form-actions">
                <a href="<?= htmlspecialchars($cancelUrl) ?>">Annuler</a>
                <button type="submit"><?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter le contenu' ?></button>
            </div>
        </form>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    (function () {
        var metaTitleInput = document.getElementById('meta-title-input');
        var slugInput = document.getElementById('slug-input');
        var userTouchedSlug = slugInput && slugInput.value.trim() !== '';
        var form = document.querySelector('.bo-form');
        var imageContainer = document.getElementById('image-uploads-container');
        var addImageBtn = document.getElementById('add-image-btn');
        var imageCounter = 0;

        // Gestion du slug auto-generation
        if (slugInput) {
            slugInput.addEventListener('input', function () {
                userTouchedSlug = slugInput.value.trim() !== '';
            });
        }

        if (metaTitleInput && slugInput) {
            metaTitleInput.addEventListener('input', function () {
                if (userTouchedSlug) {
                    return;
                }
                slugInput.value = toSlug(metaTitleInput.value);
            });
        }

        function toSlug(value) {
            return value
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        // Fonction pour créer une section d'upload
        function createImageUploadSection(index) {
            var sectionId = 'image-section-' + index;
            var section = document.createElement('div');
            section.id = sectionId;
            section.style.cssText = 'border: 1px solid #ddd; border-radius: 8px; padding: 12px; background: #fafafa;';
            
            section.innerHTML = '' +
                '<div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: flex-end;">' +
                    '<div>' +
                        '<label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 4px;">Image</label>' +
                        '<input type="file" name="images_file[]" accept="image/*" class="bo-field" />' +
                    '</div>' +
                    '<div>' +
                        '<label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 4px;">Alt text</label>' +
                        '<input type="text" name="images_alt[]" class="bo-field" maxlength="255" placeholder="Description de l\'image" />' +
                    '</div>' +
                    '<div style="min-width: 80px;">' +
                        '<label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 4px;">Ordre</label>' +
                        '<input type="number" name="images_order[]" class="image-order-input" value="' + (index + 1) + '" min="1" />' +
                    '</div>' +
                    '<button type="button" class="btn-remove" data-section-id="' + sectionId + '" style="padding: 8px 12px; background: #ff4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; height: 40px;">Retirer</button>' +
                '</div>';

            return section;
        }

        // Fonction pour valider et corriger les ordres
        function validateImageOrders() {
            var orderInputs = imageContainer.querySelectorAll('.image-order-input');
            var totalImages = orderInputs.length;
            var orders = new Map();

            orderInputs.forEach(function (input, idx) {
                var value = parseInt(input.value, 10) || 0;
                
                // Corriger: l'ordre ne doit pas dépasser le nombre d'images
                if (value > totalImages) {
                    value = totalImages;
                }
                
                // L'ordre doit être au minimum 1
                if (value < 1) {
                    value = 1;
                }

                // Vérifier l'unicité : si cet ordre existe déjà, chercher un ordre libre
                if (orders.has(value)) {
                    for (let i = 1; i <= totalImages; i++) {
                        if (!orders.has(i)) {
                            value = i;
                            break;
                        }
                    }
                }

                orders.set(value, true);
                input.value = value;
                input.style.borderColor = '';
            });
        }

        // Ajouter une section initiale
        if (imageContainer) {
            imageContainer.appendChild(createImageUploadSection(imageCounter));
            imageCounter++;

            // Gestion du bouton "Ajouter"
            addImageBtn.addEventListener('click', function (e) {
                e.preventDefault();
                imageContainer.appendChild(createImageUploadSection(imageCounter));
                imageCounter++;
                attachRemoveHandlers();
                attachOrderValidation();
            });

            // Validation des ordres sur le submit du formulaire
            if (form) {
                form.addEventListener('submit', function (e) {
                    validateImageOrders();
                });
            }

            // Délégation d'événement pour les boutons "Retirer"
            function attachRemoveHandlers() {
                var removeButtons = imageContainer.querySelectorAll('.btn-remove');
                removeButtons.forEach(function (btn) {
                    if (!btn.dataset.attached) {
                        btn.dataset.attached = 'true';
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            var sectionId = btn.getAttribute('data-section-id');
                            var section = document.getElementById(sectionId);
                            if (section) {
                                section.remove();
                                validateImageOrders();
                            }
                        });
                    }
                });
            }

            // Attachement des validations sur les inputs d'ordre
            function attachOrderValidation() {
                var orderInputs = imageContainer.querySelectorAll('.image-order-input');
                orderInputs.forEach(function (input) {
                    if (!input.dataset.validationAttached) {
                        input.dataset.validationAttached = 'true';
                        input.addEventListener('change', validateImageOrders);
                        input.addEventListener('blur', validateImageOrders);
                    }
                });
            }

            attachRemoveHandlers();
            attachOrderValidation();
        }

        // Initialisation de TinyMCE
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#content-editor',
                plugins: 'lists link image',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
                height: 320,
                license_key: 'gpl'
            });
        }

        // Gestion du submit du formulaire
        if (form) {
            form.addEventListener('submit', function (e) {
                if (typeof tinymce !== 'undefined' && tinymce.get('content-editor')) {
                    var htmlContent = tinymce.get('content-editor').getContent();
                    document.getElementById('content-editor').value = htmlContent;
                }
            });
        }
    })();
</script>
