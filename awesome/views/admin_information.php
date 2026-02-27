<?php
/**
 * Admin information settings page
 * PHP 7.4+
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */
?>
<!DOCTYPE html>
<html lang="<?= e($currentLang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/awesome/awesome_logo.png">
    <title><?= e(t('admin.information')) ?> - <?= e($config['name']) ?></title>
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/admin.css">
    <style>
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .editor-block {
            background: var(--btn-secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.2rem;
            margin-bottom: 0.8rem;
            position: relative;
        }
        .editor-block:hover {
            border-color: rgba(102, 126, 234, 0.3);
        }
        .editor-block .block-toolbar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.8rem;
        }
        .editor-block .block-toolbar .badge {
            font-size: 0.75rem;
        }
        .editor-block .block-toolbar .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.8rem;
        }
        .editor-block .form-control {
            background: var(--input-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-color) !important;
        }
        .editor-block .form-control::placeholder { color: var(--text-color-muted) !important; }
        .editor-block textarea.form-control { min-height: 80px; }
        .editor-block .form-select {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }
        .add-block-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .add-block-bar .btn {
            border-radius: 10px;
            font-size: 0.85rem;
        }
        .block-type-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
        }
        .preview-area {
            background: var(--card-bg-color);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            min-height: 200px;
        }
        .preview-area h1, .preview-area h2, .preview-area h3 { color: var(--text-color); }
        .preview-area p { color: var(--text-color-muted); }
        .preview-area a.btn { margin: 0.25rem; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 admin-page">
    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                <?= e(t('admin.information')) ?>
            </h2>
        </div>

        <?php $adminActivePage = 'information'; require ROOT_DIR . '/views/partials/admin_nav.php'; ?>

        <!-- Toast notification -->
        <div class="toast-container">
            <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><i class="fas fa-check me-2"></i><span id="toastMessage"><?= e(t('admin.saved')) ?></span></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Editor -->
            <div class="col-lg-7">
                <div class="admin-card">
                    <h5 class="admin-card-header">
                        <i class="fas fa-edit me-2"></i><?= e(t('admin.blockEditor')) ?>
                    </h5>

                    <div class="add-block-bar">
                        <button class="btn btn-outline-primary btn-sm" onclick="addBlock('heading')">
                            <i class="fas fa-heading me-1"></i> <?= e(t('admin.addHeading')) ?>
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="addBlock('text')">
                            <i class="fas fa-paragraph me-1"></i> <?= e(t('admin.addText')) ?>
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="addBlock('button')">
                            <i class="fas fa-mouse-pointer me-1"></i> <?= e(t('admin.addButton')) ?>
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="addBlock('link')">
                            <i class="fas fa-link me-1"></i> <?= e(t('admin.addLink2')) ?>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="addBlock('divider')">
                            <i class="fas fa-minus me-1"></i> <?= e(t('admin.addDivider')) ?>
                        </button>
                    </div>

                    <div id="editorBlocks">
                        <?php
                        $infoBlocks = $config['information']['blocks'] ?? [];
                        foreach ($infoBlocks as $i => $block):
                            $type = $block['type'] ?? 'text';
                        ?>
                        <div class="editor-block" data-type="<?= e($type) ?>">
                            <div class="block-toolbar">
                                <?php if ($type === 'heading'): ?>
                                    <span class="badge bg-primary block-type-badge"><i class="fas fa-heading me-1"></i><?= e(t('admin.addHeading')) ?></span>
                                <?php elseif ($type === 'text'): ?>
                                    <span class="badge bg-info block-type-badge"><i class="fas fa-paragraph me-1"></i><?= e(t('admin.addText')) ?></span>
                                <?php elseif ($type === 'button'): ?>
                                    <span class="badge bg-success block-type-badge"><i class="fas fa-mouse-pointer me-1"></i><?= e(t('admin.addButton')) ?></span>
                                <?php elseif ($type === 'link'): ?>
                                    <span class="badge bg-warning block-type-badge"><i class="fas fa-link me-1"></i><?= e(t('admin.addLink2')) ?></span>
                                <?php elseif ($type === 'divider'): ?>
                                    <span class="badge bg-secondary block-type-badge"><i class="fas fa-minus me-1"></i><?= e(t('admin.addDivider')) ?></span>
                                <?php endif; ?>
                                <div class="ms-auto d-flex gap-1">
                                    <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                                    <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                                    <button class="btn btn-outline-danger" onclick="removeEditorBlock(this)"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <?php if ($type === 'heading'): ?>
                                <input type="text" class="form-control block-content" value="<?= e($block['content'] ?? '') ?>" placeholder="<?= e(t('admin.headingText')) ?>">
                                <select class="form-select mt-2 block-level" style="max-width:120px;">
                                    <option value="h1" <?= ($block['level'] ?? 'h2') === 'h1' ? 'selected' : '' ?>>H1</option>
                                    <option value="h2" <?= ($block['level'] ?? 'h2') === 'h2' ? 'selected' : '' ?>>H2</option>
                                    <option value="h3" <?= ($block['level'] ?? 'h2') === 'h3' ? 'selected' : '' ?>>H3</option>
                                </select>
                            <?php elseif ($type === 'text'): ?>
                                <textarea class="form-control block-content" placeholder="<?= e(t('admin.paragraphText')) ?>"><?= e($block['content'] ?? '') ?></textarea>
                            <?php elseif ($type === 'button'): ?>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="text" class="form-control block-content" value="<?= e($block['content'] ?? '') ?>" placeholder="<?= e(t('admin.buttonText')) ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control block-url" value="<?= e($block['url'] ?? '') ?>" placeholder="https://...">
                                    </div>
                                </div>
                                <select class="form-select mt-2 block-style" style="max-width:200px;">
                                    <option value="primary" <?= ($block['style'] ?? 'primary') === 'primary' ? 'selected' : '' ?>>Primary</option>
                                    <option value="secondary" <?= ($block['style'] ?? '') === 'secondary' ? 'selected' : '' ?>>Secondary</option>
                                    <option value="success" <?= ($block['style'] ?? '') === 'success' ? 'selected' : '' ?>>Success</option>
                                    <option value="outline-light" <?= ($block['style'] ?? '') === 'outline-light' ? 'selected' : '' ?>>Outline</option>
                                </select>
                            <?php elseif ($type === 'link'): ?>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="text" class="form-control block-content" value="<?= e($block['content'] ?? '') ?>" placeholder="<?= e(t('admin.linkText')) ?>">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control block-url" value="<?= e($block['url'] ?? '') ?>" placeholder="https://...">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Preview -->
            <div class="col-lg-5">
                <div class="admin-card" style="position:sticky;top:80px;">
                    <h5 class="admin-card-header">
                        <i class="fas fa-eye me-2"></i><?= e(t('admin.preview')) ?>
                    </h5>
                    <div class="preview-area" id="previewArea">
                        <p class="text-muted text-center"><?= e(t('admin.addBlocksPreview')) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save All Button -->
        <div class="save-all-container">
            <button class="btn btn-save-all" onclick="saveInformation()" id="saveBtn">
                <i class="fas fa-save"></i> <?= e(t('admin.save')) ?>
            </button>
        </div>
    </div>

    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message, success) {
            const toast = document.getElementById('saveToast');
            const msgSpan = document.getElementById('toastMessage');
            const icon = toast.querySelector('.toast-body > i');
            if (success) { toast.classList.remove('text-bg-danger'); toast.classList.add('text-bg-success'); icon.className = 'fas fa-check me-2'; }
            else { toast.classList.remove('text-bg-success'); toast.classList.add('text-bg-danger'); icon.className = 'fas fa-exclamation-triangle me-2'; }
            msgSpan.textContent = message;
            new bootstrap.Toast(toast).show();
        }

        const blockTemplates = {
            heading: `<div class="editor-block" data-type="heading">
                <div class="block-toolbar">
                    <span class="badge bg-primary block-type-badge"><i class="fas fa-heading me-1"></i><?= e(t('admin.addHeading')) ?></span>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                        <button class="btn btn-outline-danger" onclick="removeEditorBlock(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <input type="text" class="form-control block-content" placeholder="<?= e(t('admin.headingText')) ?>" oninput="updatePreview()">
                <select class="form-select mt-2 block-level" style="max-width:120px;" onchange="updatePreview()">
                    <option value="h1">H1</option><option value="h2" selected>H2</option><option value="h3">H3</option>
                </select>
            </div>`,
            text: `<div class="editor-block" data-type="text">
                <div class="block-toolbar">
                    <span class="badge bg-info block-type-badge"><i class="fas fa-paragraph me-1"></i><?= e(t('admin.addText')) ?></span>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                        <button class="btn btn-outline-danger" onclick="removeEditorBlock(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <textarea class="form-control block-content" placeholder="<?= e(t('admin.paragraphText')) ?>" oninput="updatePreview()"></textarea>
            </div>`,
            button: `<div class="editor-block" data-type="button">
                <div class="block-toolbar">
                    <span class="badge bg-success block-type-badge"><i class="fas fa-mouse-pointer me-1"></i><?= e(t('admin.addButton')) ?></span>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                        <button class="btn btn-outline-danger" onclick="removeEditorBlock(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-6"><input type="text" class="form-control block-content" placeholder="<?= e(t('admin.buttonText')) ?>" oninput="updatePreview()"></div>
                    <div class="col-6"><input type="text" class="form-control block-url" placeholder="https://..." oninput="updatePreview()"></div>
                </div>
                <select class="form-select mt-2 block-style" style="max-width:200px;" onchange="updatePreview()">
                    <option value="primary">Primary</option><option value="secondary">Secondary</option><option value="success">Success</option><option value="outline-light">Outline</option>
                </select>
            </div>`,
            link: `<div class="editor-block" data-type="link">
                <div class="block-toolbar">
                    <span class="badge bg-warning block-type-badge"><i class="fas fa-link me-1"></i><?= e(t('admin.addLink2')) ?></span>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                        <button class="btn btn-outline-danger" onclick="removeEditorBlock(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-6"><input type="text" class="form-control block-content" placeholder="<?= e(t('admin.linkText')) ?>" oninput="updatePreview()"></div>
                    <div class="col-6"><input type="text" class="form-control block-url" placeholder="https://..." oninput="updatePreview()"></div>
                </div>
            </div>`,
            divider: `<div class="editor-block" data-type="divider">
                <div class="block-toolbar">
                    <span class="badge bg-secondary block-type-badge"><i class="fas fa-minus me-1"></i><?= e(t('admin.addDivider')) ?></span>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                        <button class="btn btn-outline-secondary" onclick="moveEditorBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                        <button class="btn btn-outline-danger" onclick="removeEditorBlock(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
            </div>`
        };

        function addBlock(type) {
            const container = document.getElementById('editorBlocks');
            const div = document.createElement('div');
            div.innerHTML = blockTemplates[type];
            container.appendChild(div.firstElementChild);
            updatePreview();
        }

        function removeEditorBlock(btn) {
            btn.closest('.editor-block').remove();
            updatePreview();
        }

        function moveEditorBlock(btn, dir) {
            const block = btn.closest('.editor-block');
            const container = document.getElementById('editorBlocks');
            const items = Array.from(container.children);
            const idx = items.indexOf(block);
            if (dir === -1 && idx > 0) container.insertBefore(block, items[idx - 1]);
            else if (dir === 1 && idx < items.length - 1) container.insertBefore(items[idx + 1], block);
            updatePreview();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function updatePreview() {
            const blocks = document.querySelectorAll('#editorBlocks .editor-block');
            const preview = document.getElementById('previewArea');
            let html = '';
            blocks.forEach(function(block) {
                const type = block.dataset.type;
                const content = (block.querySelector('.block-content') || {}).value || '';
                if (type === 'heading') {
                    const level = (block.querySelector('.block-level') || {}).value || 'h2';
                    html += '<' + level + '>' + escapeHtml(content) + '</' + level + '>';
                } else if (type === 'text') {
                    html += '<p>' + escapeHtml(content).replace(/\n/g, '<br>') + '</p>';
                } else if (type === 'button') {
                    const url = (block.querySelector('.block-url') || {}).value || '#';
                    const style = (block.querySelector('.block-style') || {}).value || 'primary';
                    html += '<a href="' + escapeHtml(url) + '" class="btn btn-' + style + ' btn-lg" target="_blank">' + escapeHtml(content) + '</a> ';
                } else if (type === 'link') {
                    const url = (block.querySelector('.block-url') || {}).value || '#';
                    html += '<p><a href="' + escapeHtml(url) + '" style="color:#667eea;" target="_blank">' + escapeHtml(content) + '</a></p>';
                } else if (type === 'divider') {
                    html += '<hr style="border-color: rgba(255,255,255,0.15); margin: 1.5rem 0;">';
                }
            });
            preview.innerHTML = html || '<p class="text-muted text-center"><?= e(t('admin.addBlocksPreview')) ?></p>';
        }

        // Add oninput listeners to existing blocks
        document.querySelectorAll('#editorBlocks .block-content, #editorBlocks .block-url, #editorBlocks .block-level, #editorBlocks .block-style').forEach(function(el) {
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        });
        updatePreview();

        function collectBlocks() {
            const blocks = document.querySelectorAll('#editorBlocks .editor-block');
            const result = [];
            blocks.forEach(function(block) {
                const type = block.dataset.type;
                const obj = { type: type };
                if (type === 'heading') {
                    obj.content = (block.querySelector('.block-content') || {}).value || '';
                    obj.level = (block.querySelector('.block-level') || {}).value || 'h2';
                } else if (type === 'text') {
                    obj.content = (block.querySelector('.block-content') || {}).value || '';
                } else if (type === 'button') {
                    obj.content = (block.querySelector('.block-content') || {}).value || '';
                    obj.url = (block.querySelector('.block-url') || {}).value || '';
                    obj.style = (block.querySelector('.block-style') || {}).value || 'primary';
                } else if (type === 'link') {
                    obj.content = (block.querySelector('.block-content') || {}).value || '';
                    obj.url = (block.querySelector('.block-url') || {}).value || '';
                }
                result.push(obj);
            });
            return result;
        }

        async function saveInformation() {
            const btn = document.getElementById('saveBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= e(t('admin.saving')) ?>';

            try {
                const resp = await fetch('/api/admin/information/save', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ blocks: collectBlocks() })
                });
                const result = await resp.json();
                showToast(result.message || (result.success ? '<?= e(t('admin.saved')) ?>' : '<?= e(t('admin.error')) ?>'), result.success);
            } catch (err) {
                showToast('<?= e(t('admin.networkError')) ?>', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        }
    </script>
</body>
</html>
