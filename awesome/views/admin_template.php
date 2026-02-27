<?php
/**
 * Admin template settings page
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
    <title><?= e(t('admin.template')) ?> - <?= e($config['name']) ?></title>
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/admin.css">
    <style>
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .block-item {
            background: var(--btn-secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1rem 1.2rem;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            cursor: grab;
        }
        .block-item:hover { background: var(--btn-secondary-hover); border-color: rgba(102, 126, 234, 0.3); }
        .block-item.dragging { opacity: 0.5; border-color: #667eea; }
        .block-item.drag-over { border-top: 3px solid #667eea; }
        .block-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .block-icon.servers { background: rgba(52, 199, 89, 0.15); color: #34c759; }
        .block-icon.stats { background: rgba(255, 159, 10, 0.15); color: #ff9f0a; }
        .block-icon.info { background: rgba(175, 82, 222, 0.15); color: #af52de; }
        .block-info { flex: 1; }
        .block-name { color: var(--text-color); font-weight: 600; font-size: 0.95rem; }
        .block-desc { color: var(--text-color-muted); font-size: 0.8rem; }
        .block-controls { display: flex; align-items: center; gap: 0.4rem; }
        .block-controls .btn { width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 0.8rem; }
        .form-check-input:checked { background-color: #667eea; border-color: #667eea; }
        .drag-handle { color: var(--text-color-muted); cursor: grab; font-size: 1rem; }

        .editor-block {
            background: var(--btn-secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0.8rem;
            margin-bottom: 0.6rem;
        }
        .editor-block:hover { border-color: rgba(102, 126, 234, 0.3); }
        .editor-block .block-toolbar { display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.5rem; }
        .editor-block .block-toolbar .btn { width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 7px; font-size: 0.75rem; }
        .editor-block .form-control { background: var(--input-bg) !important; border: 1px solid var(--border-color) !important; color: var(--text-color) !important; font-size: 0.85rem; }
        .editor-block .form-control::placeholder { color: var(--text-color-muted) !important; }
        .editor-block textarea.form-control { min-height: 60px; }
        .add-block-bar { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1rem; }
        .add-block-bar .btn { border-radius: 8px; font-size: 0.8rem; padding: 0.3rem 0.7rem; }
        .block-type-badge { font-size: 0.65rem; padding: 0.15rem 0.5rem; border-radius: 5px; }
        .preview-area {
            background: var(--card-bg-color);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.5rem;
            min-height: 150px;
        }
        .preview-area h1, .preview-area h2, .preview-area h3 { color: var(--text-color); }
        .preview-area p { color: var(--text-color-muted); }
        .editor-block .form-select {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 admin-page">
    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">
                <i class="fas fa-palette me-2"></i>
                <?= e(t('admin.homePageTemplate')) ?>
            </h2>
        </div>

        <?php $adminActivePage = 'template'; require ROOT_DIR . '/views/partials/admin_nav.php'; ?>

        <!-- Toast notification -->
        <div class="toast-container">
            <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><i class="fas fa-check me-2"></i><span id="toastMessage"><?= e(t('admin.saved')) ?></span></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <?php
        $template = $config['template'] ?? [];
        $defaultBlocks = [
            ['id' => 'servers', 'name' => t('admin.serverList'), 'desc' => t('admin.serverListDesc'), 'icon' => 'servers', 'fa' => 'fas fa-server', 'visible' => false, 'order' => 0],
            ['id' => 'stats', 'name' => t('admin.playerStatistics'), 'desc' => t('admin.playerStatisticsDesc'), 'icon' => 'stats', 'fa' => 'fas fa-chart-bar', 'visible' => false, 'order' => 1],
            ['id' => 'info', 'name' => t('admin.informationBlock'), 'desc' => t('admin.informationBlockDesc'), 'icon' => 'info', 'fa' => 'fas fa-info-circle', 'visible' => false, 'order' => 2],
        ];
        
        $blocks = [];
        foreach ($defaultBlocks as $def) {
            $saved = null;
            foreach (($template['blocks'] ?? []) as $s) {
                if ($s['id'] === $def['id']) { $saved = $s; break; }
            }
            $blocks[] = [
                'id' => $def['id'],
                'name' => $def['name'],
                'desc' => $def['desc'],
                'icon' => $def['icon'],
                'fa' => $def['fa'],
                'visible' => $saved !== null ? !empty($saved['visible']) : $def['visible'],
                'order' => $saved !== null ? (int)$saved['order'] : $def['order'],
            ];
        }
        usort($blocks, function($a, $b) { return $a['order'] - $b['order']; });
        ?>

        <!-- Row 1: Information Editor + Preview -->
        <div class="row">
            <div class="col-lg-6">
                <div class="admin-card">
                    <h5 class="admin-card-header">
                        <i class="fas fa-edit me-2"></i><?= e(t('admin.infoEditor')) ?>
                    </h5>
                    <p class="text-muted mb-2" style="font-size:0.85rem;">
                        <?= e(t('admin.infoEditorDesc')) ?>
                    </p>
                    <div class="add-block-bar">
                        <button class="btn btn-outline-primary btn-sm" onclick="addInfoBlock('heading')"><i class="fas fa-heading me-1"></i> <?= e(t('admin.addHeading')) ?></button>
                        <button class="btn btn-outline-info btn-sm" onclick="addInfoBlock('text')"><i class="fas fa-paragraph me-1"></i> <?= e(t('admin.addText')) ?></button>
                        <button class="btn btn-outline-success btn-sm" onclick="addInfoBlock('button')"><i class="fas fa-mouse-pointer me-1"></i> <?= e(t('admin.addButton')) ?></button>
                        <button class="btn btn-outline-warning btn-sm" onclick="addInfoBlock('link')"><i class="fas fa-link me-1"></i> <?= e(t('admin.addLink2')) ?></button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="addInfoBlock('divider')"><i class="fas fa-minus me-1"></i> <?= e(t('admin.addDivider')) ?></button>
                    </div>
                    <div id="editorBlocks">
                        <?php
                        $infoBlocks = $config['information']['blocks'] ?? [];
                        foreach ($infoBlocks as $ib):
                            $type = $ib['type'] ?? 'text';
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
                                    <button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,-1)"><i class="fas fa-chevron-up"></i></button>
                                    <button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,1)"><i class="fas fa-chevron-down"></i></button>
                                    <button class="btn btn-outline-danger" onclick="removeInfoBlock(this)"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <?php if ($type === 'heading'): ?>
                                <input type="text" class="form-control block-content" value="<?= e($ib['content'] ?? '') ?>" placeholder="<?= e(t('admin.headingText')) ?>" oninput="updatePreview()">
                                <select class="form-select mt-1 block-level" style="max-width:100px;font-size:0.85rem;" onchange="updatePreview()">
                                    <option value="h1" <?= ($ib['level'] ?? 'h2') === 'h1' ? 'selected' : '' ?>>H1</option>
                                    <option value="h2" <?= ($ib['level'] ?? 'h2') === 'h2' ? 'selected' : '' ?>>H2</option>
                                    <option value="h3" <?= ($ib['level'] ?? 'h2') === 'h3' ? 'selected' : '' ?>>H3</option>
                                </select>
                            <?php elseif ($type === 'text'): ?>
                                <textarea class="form-control block-content" placeholder="<?= e(t('admin.textPlaceholder')) ?>" oninput="updatePreview()"><?= e($ib['content'] ?? '') ?></textarea>
                            <?php elseif ($type === 'button'): ?>
                                <div class="row g-2">
                                    <div class="col-6"><input type="text" class="form-control block-content" value="<?= e($ib['content'] ?? '') ?>" placeholder="<?= e(t('admin.textLabel')) ?>" oninput="updatePreview()"></div>
                                    <div class="col-6"><input type="text" class="form-control block-url" value="<?= e($ib['url'] ?? '') ?>" placeholder="https://..." oninput="updatePreview()"></div>
                                </div>
                                <select class="form-select mt-1 block-style" style="max-width:160px;font-size:0.85rem;" onchange="updatePreview()">
                                    <option value="primary" <?= ($ib['style'] ?? 'primary') === 'primary' ? 'selected' : '' ?>>Primary</option>
                                    <option value="secondary" <?= ($ib['style'] ?? '') === 'secondary' ? 'selected' : '' ?>>Secondary</option>
                                    <option value="success" <?= ($ib['style'] ?? '') === 'success' ? 'selected' : '' ?>>Success</option>
                                    <option value="outline-light" <?= ($ib['style'] ?? '') === 'outline-light' ? 'selected' : '' ?>>Outline</option>
                                </select>
                            <?php elseif ($type === 'link'): ?>
                                <div class="row g-2">
                                    <div class="col-6"><input type="text" class="form-control block-content" value="<?= e($ib['content'] ?? '') ?>" placeholder="<?= e(t('admin.textLabel')) ?>" oninput="updatePreview()"></div>
                                    <div class="col-6"><input type="text" class="form-control block-url" value="<?= e($ib['url'] ?? '') ?>" placeholder="https://..." oninput="updatePreview()"></div>
                                </div>
                            <?php elseif ($type === 'divider'): ?>
                                <hr style="border-color: var(--border-color);">
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="admin-card">
                    <h5 class="admin-card-header">
                        <i class="fas fa-eye me-2"></i><?= e(t('admin.infoPreview')) ?>
                    </h5>
                    <div class="preview-area" id="previewArea">
                        <p class="text-muted text-center"><?= e(t('admin.addBlocksLeft')) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Block Order -->
        <div class="row mt-3">
            <div class="col-lg-6">
                <div class="admin-card">
                    <h5 class="admin-card-header">
                        <i class="fas fa-th-list me-2"></i><?= e(t('admin.blockOrder')) ?>
                    </h5>
                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                        <?= e(t('admin.blockOrderDesc')) ?>
                    </p>
                    <div id="blocksContainer">
                        <?php foreach ($blocks as $i => $block): ?>
                        <div class="block-item" draggable="true" data-id="<?= e($block['id']) ?>">
                            <div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>
                            <div class="block-icon <?= e($block['icon']) ?>"><i class="<?= e($block['fa']) ?>"></i></div>
                            <div class="block-info">
                                <div class="block-name"><?= e($block['name']) ?></div>
                                <div class="block-desc"><?= e($block['desc']) ?></div>
                            </div>
                            <div class="block-controls">
                                <button class="btn btn-outline-secondary" onclick="moveBlock(this, -1)"><i class="fas fa-chevron-up"></i></button>
                                <button class="btn btn-outline-secondary" onclick="moveBlock(this, 1)"><i class="fas fa-chevron-down"></i></button>
                                <div class="form-check form-switch ms-1">
                                    <input class="form-check-input block-visible" type="checkbox" <?= $block['visible'] ? 'checked' : '' ?> style="cursor:pointer; width:2.5rem; height:1.3rem;">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="save-all-container">
            <button class="btn btn-save-all" onclick="saveAll()" id="saveBtn">
                <i class="fas fa-save me-2"></i> <?= e(t('admin.saveAll')) ?>
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

        function escapeHtml(text) { const d = document.createElement('div'); d.textContent = text; return d.innerHTML; }

        // ===== BLOCK ORDER =====
        function moveBlock(btn, direction) {
            const item = btn.closest('.block-item');
            const container = document.getElementById('blocksContainer');
            const items = Array.from(container.children);
            const index = items.indexOf(item);
            if (direction === -1 && index > 0) container.insertBefore(item, items[index - 1]);
            else if (direction === 1 && index < items.length - 1) container.insertBefore(items[index + 1], item);
        }

        // Drag and drop for blocks
        let dragItem = null;
        const bc = document.getElementById('blocksContainer');
        bc.addEventListener('dragstart', function(e) { dragItem = e.target.closest('.block-item'); if (dragItem) dragItem.classList.add('dragging'); });
        bc.addEventListener('dragend', function() { if (dragItem) dragItem.classList.remove('dragging'); document.querySelectorAll('.block-item').forEach(el => el.classList.remove('drag-over')); dragItem = null; });
        bc.addEventListener('dragover', function(e) { e.preventDefault(); const t = e.target.closest('.block-item'); if (t && t !== dragItem) { document.querySelectorAll('.block-item').forEach(el => el.classList.remove('drag-over')); t.classList.add('drag-over'); } });
        bc.addEventListener('drop', function(e) { e.preventDefault(); const t = e.target.closest('.block-item'); if (t && t !== dragItem && dragItem) { const items = Array.from(bc.children); if (items.indexOf(dragItem) < items.indexOf(t)) bc.insertBefore(dragItem, t.nextSibling); else bc.insertBefore(dragItem, t); } document.querySelectorAll('.block-item').forEach(el => el.classList.remove('drag-over')); });

        // ===== INFO EDITOR =====
        const infoTemplates = {
            heading: `<div class="editor-block" data-type="heading"><div class="block-toolbar"><span class="badge bg-primary block-type-badge"><i class="fas fa-heading me-1"></i><?= e(t('admin.addHeading')) ?></span><div class="ms-auto d-flex gap-1"><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,-1)"><i class="fas fa-chevron-up"></i></button><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,1)"><i class="fas fa-chevron-down"></i></button><button class="btn btn-outline-danger" onclick="removeInfoBlock(this)"><i class="fas fa-trash"></i></button></div></div><input type="text" class="form-control block-content" placeholder="<?= e(t('admin.headingText')) ?>" oninput="updatePreview()"><select class="form-select mt-1 block-level" style="max-width:100px;font-size:0.85rem;" onchange="updatePreview()"><option value="h1">H1</option><option value="h2" selected>H2</option><option value="h3">H3</option></select></div>`,
            text: `<div class="editor-block" data-type="text"><div class="block-toolbar"><span class="badge bg-info block-type-badge"><i class="fas fa-paragraph me-1"></i><?= e(t('admin.addText')) ?></span><div class="ms-auto d-flex gap-1"><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,-1)"><i class="fas fa-chevron-up"></i></button><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,1)"><i class="fas fa-chevron-down"></i></button><button class="btn btn-outline-danger" onclick="removeInfoBlock(this)"><i class="fas fa-trash"></i></button></div></div><textarea class="form-control block-content" placeholder="<?= e(t('admin.textPlaceholder')) ?>" oninput="updatePreview()"></textarea></div>`,
            button: `<div class="editor-block" data-type="button"><div class="block-toolbar"><span class="badge bg-success block-type-badge"><i class="fas fa-mouse-pointer me-1"></i><?= e(t('admin.addButton')) ?></span><div class="ms-auto d-flex gap-1"><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,-1)"><i class="fas fa-chevron-up"></i></button><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,1)"><i class="fas fa-chevron-down"></i></button><button class="btn btn-outline-danger" onclick="removeInfoBlock(this)"><i class="fas fa-trash"></i></button></div></div><div class="row g-2"><div class="col-6"><input type="text" class="form-control block-content" placeholder="<?= e(t('admin.textLabel')) ?>" oninput="updatePreview()"></div><div class="col-6"><input type="text" class="form-control block-url" placeholder="https://..." oninput="updatePreview()"></div></div><select class="form-select mt-1 block-style" style="max-width:160px;font-size:0.85rem;" onchange="updatePreview()"><option value="primary">Primary</option><option value="secondary">Secondary</option><option value="success">Success</option><option value="outline-light">Outline</option></select></div>`,
            link: `<div class="editor-block" data-type="link"><div class="block-toolbar"><span class="badge bg-warning block-type-badge"><i class="fas fa-link me-1"></i><?= e(t('admin.addLink2')) ?></span><div class="ms-auto d-flex gap-1"><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,-1)"><i class="fas fa-chevron-up"></i></button><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,1)"><i class="fas fa-chevron-down"></i></button><button class="btn btn-outline-danger" onclick="removeInfoBlock(this)"><i class="fas fa-trash"></i></button></div></div><div class="row g-2"><div class="col-6"><input type="text" class="form-control block-content" placeholder="<?= e(t('admin.textLabel')) ?>" oninput="updatePreview()"></div><div class="col-6"><input type="text" class="form-control block-url" placeholder="https://..." oninput="updatePreview()"></div></div></div>`,
            divider: `<div class="editor-block" data-type="divider"><div class="block-toolbar"><span class="badge bg-secondary block-type-badge"><i class="fas fa-minus me-1"></i><?= e(t('admin.addDivider')) ?></span><div class="ms-auto d-flex gap-1"><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,-1)"><i class="fas fa-chevron-up"></i></button><button class="btn btn-outline-secondary" onclick="moveInfoBlock(this,1)"><i class="fas fa-chevron-down"></i></button><button class="btn btn-outline-danger" onclick="removeInfoBlock(this)"><i class="fas fa-trash"></i></button></div></div><hr style="border-color: var(--border-color);"></div>`
        };

        function addInfoBlock(type) {
            const container = document.getElementById('editorBlocks');
            const div = document.createElement('div');
            div.innerHTML = infoTemplates[type];
            container.appendChild(div.firstElementChild);
            updatePreview();
        }

        function removeInfoBlock(btn) { btn.closest('.editor-block').remove(); updatePreview(); }
        function moveInfoBlock(btn, dir) {
            const block = btn.closest('.editor-block');
            const container = document.getElementById('editorBlocks');
            const items = Array.from(container.children);
            const idx = items.indexOf(block);
            if (dir === -1 && idx > 0) container.insertBefore(block, items[idx - 1]);
            else if (dir === 1 && idx < items.length - 1) container.insertBefore(items[idx + 1], block);
            updatePreview();
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
                    html += '<a href="' + escapeHtml(url) + '" class="btn btn-' + style + '" target="_blank">' + escapeHtml(content) + '</a> ';
                } else if (type === 'link') {
                    const url = (block.querySelector('.block-url') || {}).value || '#';
                    html += '<p><a href="' + escapeHtml(url) + '" style="color:#667eea;" target="_blank">' + escapeHtml(content) + '</a></p>';
                } else if (type === 'divider') {
                    html += '<hr style="border-color: rgba(255,255,255,0.15); margin: 1rem 0;">';
                }
            });
            preview.innerHTML = html || '<p class="text-muted text-center"><?= e(t('admin.addBlocksLeft')) ?></p>';
        }

        // Init preview
        document.querySelectorAll('#editorBlocks .block-content, #editorBlocks .block-url, #editorBlocks .block-level, #editorBlocks .block-style').forEach(function(el) {
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        });
        updatePreview();

        // ===== COLLECT BLOCKS FROM CONTAINER =====
        function collectBlocks(containerId) {
            const blocks = [];
            document.querySelectorAll('#' + containerId + ' .editor-block').forEach(function(block) {
                const type = block.dataset.type;
                const obj = { type: type };
                if (type === 'heading') { obj.content = (block.querySelector('.block-content') || {}).value || ''; obj.level = (block.querySelector('.block-level') || {}).value || 'h2'; }
                else if (type === 'text') { obj.content = (block.querySelector('.block-content') || {}).value || ''; }
                else if (type === 'button') { obj.content = (block.querySelector('.block-content') || {}).value || ''; obj.url = (block.querySelector('.block-url') || {}).value || ''; obj.style = (block.querySelector('.block-style') || {}).value || 'primary'; }
                else if (type === 'link') { obj.content = (block.querySelector('.block-content') || {}).value || ''; obj.url = (block.querySelector('.block-url') || {}).value || ''; }
                blocks.push(obj);
            });
            return blocks;
        }

        // ===== SAVE ALL =====
        async function saveAll() {
            const btn = document.getElementById('saveBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= e(t('admin.saving')) ?>';

            // Collect template blocks
            const templateBlocks = [];
            document.querySelectorAll('#blocksContainer .block-item').forEach(function(item, i) {
                templateBlocks.push({ id: item.dataset.id, order: i, visible: item.querySelector('.block-visible').checked });
            });

            try {
                // Save template order
                const r1 = await fetch('/api/admin/template/save', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ blocks: templateBlocks })
                });
                const res1 = await r1.json();

                // Save information blocks
                const r2 = await fetch('/api/admin/information/save', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ blocks: collectBlocks('editorBlocks') })
                });
                const res2 = await r2.json();

                if (res1.success && res2.success) {
                    showToast('<?= e(t('admin.allSaved')) ?>', true);
                } else {
                    showToast((res1.message || '') + ' ' + (res2.message || ''), false);
                }
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
