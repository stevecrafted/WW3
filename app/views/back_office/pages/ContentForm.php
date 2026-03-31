<?php

$section     = $section     ?? null;
$contentItem = $contentItem ?? null;
$images      = $images      ?? [];
$notice      = $notice      ?? '';

$isEdit      = $contentItem !== null;
$sectionId   = (int) ($section->id ?? 0);
$formAction  = '/admin/sections/' . $sectionId . '/contents/save';
$cancelUrl   = '/admin/sections/' . $sectionId . '/contents';
?>


<div class="bo-shell">

    <!-- PAGE HEADER -->
    <div class="bo-page-header">
        <a href="<?= htmlspecialchars($cancelUrl) ?>" class="btn btn--outline btn--sm">&larr; Retour</a>
        <span class="bo-page-header__label">
            <?= htmlspecialchars($section->title ?? '') ?>
        </span>
        <h1 class="bo-title">
            <?= $isEdit ? 'Modifier le contenu' : 'Nouveau contenu' ?>
        </h1>
        <div class="bo-page-header__line"></div>
    </div>

    <?php if ($notice !== ''): ?>
        <p class="bo-notice"><?= htmlspecialchars($notice) ?></p>
    <?php endif; ?>

    <!-- CARD FORMULAIRE -->
    <section class="bo-card" aria-label="Formulaire contenu">
        <div class="bo-card__header">
            <span class="bo-card__title">
                <?= $isEdit ? 'Modification' : 'Création' ?> d'un contenu
            </span>
            <?php if ($isEdit): ?>
                <span style="font-family:var(--font-ui);font-size:11px;color:var(--rouge);font-weight:600;">
                    ID #<?= (int) ($contentItem->id ?? 0) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="bo-card__body">
            <form method="POST"
                  action="<?= htmlspecialchars($formAction) ?>"
                  class="cform"
                  enctype="multipart/form-data"
                  id="content-form">

                <input type="hidden" name="id" value="<?= (int) ($contentItem->id ?? 0) ?>" />

                <!-- ── GROUPE SEO ──────────────────────────────── -->
                <div class="cform__group">
                    <p class="cform__group-title">SEO &amp; métadonnées</p>

                    <div class="cform__row-2">
                        <label class="bo-label">
                            <span class="bo-label-text">
                                Meta titre
                                <span class="bo-label-hint">recommandé : 50–60 car.</span>
                            </span>
                            <input class="bo-field"
                                   type="text" name="meta_title" id="meta-title-input"
                                   required maxlength="255"
                                   value="<?= htmlspecialchars((string) ($contentItem->meta_title ?? '')) ?>"
                                   placeholder="Ex : Frappes sur Téhéran — IranWatch" />
                            <span class="char-counter" data-max="60" data-target="meta-title-input">0 / 60</span>
                        </label>

                        <label class="bo-label">
                            <span class="bo-label-text">
                                Slug
                                <span class="bo-label-hint">auto-généré</span>
                            </span>
                            <input class="bo-field bo-field--slug"
                                   type="text" name="slug" id="slug-input"
                                   maxlength="255"
                                   value="<?= htmlspecialchars((string) ($contentItem->slug ?? '')) ?>"
                                   placeholder="frappes-teheran-mars-2026" />
                        </label>
                    </div>

                    <label class="bo-label">
                        <span class="bo-label-text">
                            Meta description
                            <span class="bo-label-hint">recommandé : 120–160 car.</span>
                        </span>
                        <textarea class="bo-field" name="meta_description" id="meta-desc-input" rows="3"
                                  placeholder="Résumé affiché par les moteurs de recherche…"><?= htmlspecialchars((string) ($contentItem->meta_description ?? '')) ?></textarea>
                        <span class="char-counter" data-max="160" data-target="meta-desc-input">0 / 160</span>
                    </label>
                </div>

                <!-- ── GROUPE ÉDITORIAL ───────────────────────── -->
                <div class="cform__group">
                    <p class="cform__group-title">Contenu éditorial</p>

                    <label class="bo-label">
                        <span class="bo-label-text">Titre <span style="color:var(--rouge)">*</span></span>
                        <input class="bo-field"
                               type="text" name="title" required maxlength="255"
                               value="<?= htmlspecialchars((string) ($contentItem->title ?? '')) ?>"
                               placeholder="Titre affiché en front-office" />
                    </label>

                    <label class="bo-label">
                        <span class="bo-label-text">
                            Résumé / chapeau
                            <span class="bo-label-hint">accroche affichée sous le titre</span>
                        </span>
                        <textarea class="bo-field" name="summary" rows="3"
                                  placeholder="Quelques phrases qui donnent envie de lire l'article…"><?= htmlspecialchars((string) ($contentItem->summary ?? '')) ?></textarea>
                    </label>

                    <div class="bo-label">
                        <span class="bo-label-text">Corps de l'article <span style="color:var(--rouge)">*</span></span>
                        <div class="tinymce-wrap">
                            <textarea id="content-editor" name="content_text" rows="14"><?= htmlspecialchars((string) ($contentItem->content_text ?? '')) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- ── GROUPE IMAGES ──────────────────────────── -->
                <div class="cform__group">
                    <fieldset class="img-fieldset">
                        <legend>Images <span style="font-weight:300;color:var(--gris-clair);text-transform:none;letter-spacing:0;font-size:10px">— la 1ère est la couverture</span></legend>

                        <!-- Images existantes -->
                        <?php if (!empty($images)): ?>
                            <p class="bo-label-text" style="margin-bottom:10px;">
                                Images actuelles
                            </p>
                            <div class="existing-images">
                                <?php foreach ($images as $img): ?>
                                    <figure class="existing-img-card">
                                        <img src="<?= htmlspecialchars((string) ($img->url ?? '')) ?>"
                                             alt="<?= htmlspecialchars((string) ($img->alt_text ?? '')) ?>"
                                             loading="lazy" />
                                        <div class="existing-img-card__meta">
                                            <div class="existing-img-card__row">
                                                <strong>Alt&nbsp;:</strong>
                                                <?= htmlspecialchars((string) ($img->alt_text ?? '—')) ?>
                                            </div>
                                            <div class="existing-img-card__row">
                                                <strong>Ordre&nbsp;:</strong>
                                                <?= (int) ($img->display_order ?? 0) ?>
                                            </div>
                                        </div>
                                    </figure>
                                <?php endforeach; ?>
                            </div>

                            <?php if ($isEdit): ?>
                                <label class="bo-checkbox">
                                    <input type="checkbox" name="replace_images" value="1" />
                                    Remplacer les images existantes par les nouvelles
                                </label>
                            <?php endif; ?>

                            <div style="height:16px;"></div>
                        <?php endif; ?>

                        <!-- Nouvelles images -->
                        <div id="image-uploads-container"></div>

                        <button type="button" id="add-image-btn" class="btn btn--outline btn--sm">
                            + Ajouter une photo
                        </button>
                    </fieldset>
                </div>

                <!-- ── ACTIONS ────────────────────────────────── -->
                <div class="cform__actions">
                    <a href="<?= htmlspecialchars($cancelUrl) ?>" class="btn btn--outline">Annuler</a>
                    <button type="submit" class="btn btn--primary">
                        <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter le contenu' ?>
                    </button>
                </div>

            </form>
        </div>
    </section>
</div>

<!-- TinyMCE -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

<script>
(function () {
    /* ── SLUG AUTO ──────────────────────────────────────────────── */
    var metaTitle  = document.getElementById('meta-title-input');
    var slugInput  = document.getElementById('slug-input');
    var userTouched = slugInput && slugInput.value.trim() !== '';

    if (slugInput) {
        slugInput.addEventListener('input', function () {
            userTouched = slugInput.value.trim() !== '';
        });
    }
    if (metaTitle && slugInput) {
        metaTitle.addEventListener('input', function () {
            if (!userTouched) slugInput.value = toSlug(metaTitle.value);
        });
    }
    function toSlug(v) {
        return v.normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    /* ── COMPTEURS DE CARACTÈRES ────────────────────────────────── */
    document.querySelectorAll('.char-counter').forEach(function (counter) {
        var targetId = counter.dataset.target;
        var max      = parseInt(counter.dataset.max, 10);
        var field    = document.getElementById(targetId);
        if (!field) return;

        function update() {
            var len = field.value.length;
            counter.textContent = len + ' / ' + max;
            counter.classList.toggle('warn',  len > max * 0.85 && len <= max);
            counter.classList.toggle('error', len > max);
        }
        field.addEventListener('input', update);
        update();
    });

    /* ── UPLOAD D'IMAGES ────────────────────────────────────────── */
    var container    = document.getElementById('image-uploads-container');
    var addBtn       = document.getElementById('add-image-btn');
    var form         = document.getElementById('content-form');
    var imageCounter = 0;

    function createUploadBlock(index) {
        var block     = document.createElement('div');
        var previewId = 'preview-' + index;
        block.className = 'img-upload-block';
        block.dataset.index = index;

        block.innerHTML =
            '<div id="' + previewId + '" class="img-upload-preview">Aperçu</div>' +
            '<div class="img-upload-fields">' +
                '<label class="bo-label">' +
                    '<span class="bo-label-text">Fichier image</span>' +
                    '<input type="file" name="images_file[]" accept="image/*"' +
                        ' class="bo-field image-file-input" data-preview="' + previewId + '" />' +
                '</label>' +
                '<div class="img-upload-row-2">' +
                    '<label class="bo-label">' +
                        '<span class="bo-label-text">Alt text <span class="bo-label-hint">— SEO &amp; accessibilité</span></span>' +
                        '<input type="text" name="images_alt[]" class="bo-field"' +
                            ' maxlength="255" placeholder="Description de l\'image pour les moteurs" />' +
                    '</label>' +
                    '<label class="bo-label">' +
                        '<span class="bo-label-text">Ordre</span>' +
                        '<input type="number" name="images_order[]" class="bo-field image-order-input"' +
                            ' value="' + (index + 1) + '" min="1" style="height:38px;padding:0 8px;" />' +
                    '</label>' +
                '</div>' +
                '<button type="button" class="btn-remove-img">✕ Retirer</button>' +
            '</div>';

        /* Preview */
        var fileInput = block.querySelector('.image-file-input');
        fileInput.addEventListener('change', function () {
            var file    = this.files[0];
            var preview = document.getElementById(this.dataset.preview);
            if (file && preview) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="preview" />';
                };
                reader.readAsDataURL(file);
            }
        });

        /* Retirer */
        block.querySelector('.btn-remove-img').addEventListener('click', function () {
            block.remove();
            validateOrders();
        });

        /* Ordre */
        var orderInput = block.querySelector('.image-order-input');
        orderInput.addEventListener('change', validateOrders);
        orderInput.addEventListener('blur',   validateOrders);

        return block;
    }

    function validateOrders() {
        var inputs = container.querySelectorAll('.image-order-input');
        var total  = inputs.length;
        var used   = new Map();
        inputs.forEach(function (inp) {
            var v = Math.max(1, Math.min(total || 1, parseInt(inp.value, 10) || 1));
            if (used.has(v)) {
                for (var i = 1; i <= total; i++) {
                    if (!used.has(i)) { v = i; break; }
                }
            }
            used.set(v, true);
            inp.value = v;
        });
    }

    if (container && addBtn) {
        container.appendChild(createUploadBlock(imageCounter++));
        addBtn.addEventListener('click', function (e) {
            e.preventDefault();
            container.appendChild(createUploadBlock(imageCounter++));
        });
    }

    /* ── TINYMCE ────────────────────────────────────────────────── */
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#content-editor',
            plugins:  'lists link image',
            toolbar:  'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
            height:   360,
            license_key: 'gpl',
            skin:     'oxide',
            content_css: 'default',
        });
    }

    /* ── SUBMIT ─────────────────────────────────────────────────── */
    if (form) {
        form.addEventListener('submit', function () {
            validateOrders();
            if (typeof tinymce !== 'undefined' && tinymce.get('content-editor')) {
                document.getElementById('content-editor').value =
                    tinymce.get('content-editor').getContent();
            }
        });
    }
})();
</script>