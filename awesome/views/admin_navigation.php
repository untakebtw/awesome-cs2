<?php 
$adminActivePage = 'navigation';
$footerText = $config['footer']['text'] ?? '';
?>
<?php
/**
 * Admin navigation settings page
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
    <title><?= e(t('admin.navigation')) ?> - <?= e($config['name']) ?></title>
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/admin.css">
</head>
<body class="d-flex flex-column min-vh-100 admin-page">
    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>

    <div class="container mt-4 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">
                <i class="fas fa-compass me-2" style="opacity: 0.5;"></i>
                <?= e(t('admin.navTitle')) ?>
            </h2>
        </div>

        <!-- Admin sub-navigation -->
        <?php require ROOT_DIR . '/views/partials/admin_nav.php'; ?>

        <!-- Toast notification -->
        <div class="toast-container">
            <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i> <?= e(t('admin.saved')) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <!-- Navbar Links Section -->
        <div class="admin-section-title">
            <i class="fas fa-bars"></i> <?= e(t('admin.navbarLinks')) ?>
        </div>
        
        <div class="row g-4 mb-5" id="navbarLinksContainer">
            <?php 
            $navLinks = $config['navigation'] ?? [];
            $navbarLinks = $navLinks['navbar'] ?? [];
            usort($navbarLinks, function($a, $b) { return ($a['sort'] ?? 0) - ($b['sort'] ?? 0); });
            
            foreach ($navbarLinks as $index => $link): ?>
            <div class="col-md-4 nav-item-wrapper" data-type="navbar">
                <div class="admin-card nav-item-card">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-label-sm">NAME</div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-label-sm">SORT ORDER</div>
                            <button class="btn-delete-link" onclick="deleteLink(this)"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <input type="text" class="form-control nav-name" value="<?= e($link['name']) ?>" placeholder="например: Home">
                        <input type="number" class="form-control nav-sort" value="<?= e($link['sort']) ?>" placeholder="0" style="width: 80px;">
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-label-sm">PATH</div>
                        <div class="form-label-sm">PREVIEW</div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <input type="text" class="form-control nav-path" value="<?= e($link['path']) ?>" placeholder="например: / или /skins">
                        <div class="icon-preview-box">
                            <i class="fas fa-link"></i>
                        </div>
                    </div>
                    <div class="form-label-sm mb-2">SVG ICON</div>
                    <textarea class="form-control nav-icon" placeholder="<svg..." rows="2"><?= e($link['icon'] ?? '') ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Add Link Card -->
            <div class="col-md-4">
                <div class="add-link-card h-100 d-flex flex-column align-items-center justify-content-center" onclick="addLink('navbar')">
                    <i class="fas fa-plus mb-3"></i>
                    <span><?= e(t('admin.addLink')) ?></span>
                </div>
            </div>
        </div>


        <!-- Footer Settings Section -->
        <div class="admin-section-title mt-3">
            <i class="fas fa-quote-left"></i> <?= e(t('admin.footerSettings')) ?>
        </div>

        <div class="admin-card mb-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-label-sm mb-2"><?= e(t('admin.footerText')) ?></div>
                    <textarea class="form-control" id="footerText" rows="4"><?= e($footerText) ?></textarea>
                </div>
                <div class="col-md-6">
                    <div class="form-label-sm mb-2"><?= e(t('admin.quickLinks')) ?></div>
                    <div id="footerLinksEditorContainer">
                        <?php 
                        $quickLinks = $navLinks['footer'] ?? [];
                        usort($quickLinks, function($a, $b) { return ($a['sort'] ?? 0) - ($b['sort'] ?? 0); });
                        foreach ($quickLinks as $link): ?>
                        <div class="d-flex gap-2 mb-2">
                            <input type="text" class="form-control form-control-sm footer-link-name" value="<?= e($link['name']) ?>" placeholder="например: Главная">
                            <input type="text" class="form-control form-control-sm footer-link-path" value="<?= e($link['path']) ?>" placeholder="например: /">
                            <button class="btn btn-sm btn-delete" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="btn btn-sm btn-add w-100 mt-2" onclick="addFooterEditorLink()">
                        <i class="fas fa-plus"></i> <?= e(t('admin.addLink')) ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Social Networks -->
        <div class="row">
            <div class="col-md-6">
                <div class="admin-section-title">
                    <i class="fas fa-share-alt"></i> <?= e(t('admin.socialNetworks')) ?>
                </div>
                <div class="admin-card">
                    <?php 
                    $social = $config['footer']['social'] ?? [];
                    $socials = [
                        ['id' => 'discord',  'icon' => 'fab fa-discord',  'label' => 'Discord'],
                        ['id' => 'telegram', 'icon' => 'fab fa-telegram', 'label' => 'Telegram'],
                        ['id' => 'youtube',  'icon' => 'fab fa-youtube',  'label' => 'YouTube'],
                        ['id' => 'github',   'icon' => 'fab fa-github',   'label' => 'GitHub'],
                    ];
                    foreach ($socials as $soc): ?>
                    <div class="mb-3">
                        <div class="form-label-sm mb-2"><?= $soc['label'] ?></div>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent" style="border-color: var(--border-main); color: var(--text-secondary);"><i class="<?= $soc['icon'] ?>"></i></span>
                            <input type="text" class="form-control social-link border-start-0" data-id="<?= $soc['id'] ?>" value="<?= e($social[$soc['id']] ?? '') ?>" placeholder="https://...">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Save All Button -->
        <div class="save-all-container">
            <button class="btn btn-save-all" onclick="saveNavigation()" id="saveBtn">
                <i class="fas fa-save"></i> <?= e(t('admin.save')) ?>
            </button>
        </div>

    </div>

    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
    function addLink(type) {
        const template = document.getElementById('navItemTemplate');
        const clone = template.content.cloneNode(true);
        clone.querySelector('.nav-item-wrapper').dataset.type = type;
        document.getElementById('navbarLinksContainer').insertBefore(clone, document.getElementById('navbarLinksContainer').lastElementChild);
    }

    function deleteLink(btn) {
        btn.closest('.nav-item-wrapper').remove();
    }

    function addFooterEditorLink() {
        const container = document.getElementById('footerLinksEditorContainer');
        const div = document.createElement('div');
        div.className = 'd-flex gap-2 mb-2';
        div.innerHTML = `
            <input type="text" class="form-control form-control-sm footer-link-name" value="" placeholder="например: Главная">
            <input type="text" class="form-control form-control-sm footer-link-path" value="" placeholder="например: /">
            <button class="btn btn-sm btn-delete" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
        `;
        container.appendChild(div);
    }

    async function saveNavigation() {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
        
        const headerLinks = [];
        document.querySelectorAll('.nav-item-wrapper[data-type="navbar"]').forEach(el => {
            headerLinks.push({
                name: el.querySelector('.nav-name').value,
                path: el.querySelector('.nav-path').value,
                sort: parseInt(el.querySelector('.nav-sort').value),
                icon: el.querySelector('.nav-icon').value
            });
        });

        const footerQuickLinks = [];
        document.querySelectorAll('.footer-link-name').forEach((el, i) => {
            const path = document.querySelectorAll('.footer-link-path')[i].value;
            if (el.value) {
                footerQuickLinks.push({ name: el.value, path: path, sort: i });
            }
        });

        const social = {};
        document.querySelectorAll('.social-link').forEach(el => { social[el.dataset.id] = el.value; });

        const data = {
            navbar: headerLinks,
            footer: footerQuickLinks,
            footerSettings: {
                text: document.getElementById('footerText').value,
                social: social
            }
        };

        try {
            const resp = await fetch('/api/admin/navigation/save', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const result = await resp.json();
            if (result.status === 'success' || result.success) {
                new bootstrap.Toast(document.getElementById('saveToast')).show();
            }
        } catch (e) {
            console.error(e);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-2"></i> <?= e(t('admin.save')) ?>';
        }
    }
    </script>

    <template id="navItemTemplate">
        <div class="col-md-4 nav-item-wrapper" data-type="navbar">
            <div class="admin-card nav-item-card">
                <div class="d-flex justify-content-between mb-3">
                    <div class="form-label-sm">NAME</div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-label-sm">SORT ORDER</div>
                        <button class="btn-delete-link" onclick="deleteLink(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <input type="text" class="form-control nav-name" value="" placeholder="например: Skins">
                    <input type="number" class="form-control nav-sort" value="0" placeholder="0" style="width: 80px;">
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="form-label-sm">PATH</div>
                    <div class="form-label-sm">PREVIEW</div>
                </div>
                <div class="d-flex gap-3 mb-3">
                    <input type="text" class="form-control nav-path" value="" placeholder="например: /skins">
                    <div class="icon-preview-box"><i class="fas fa-link"></i></div>
                </div>
                <div class="form-label-sm mb-2">SVG ICON</div>
                <textarea class="form-control nav-icon" placeholder="<svg..." rows="2"></textarea>
            </div>
        </div>
    </template>

    <style>
        .admin-section-title { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-primary); opacity: 0.5; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        .nav-item-card { transition: var(--transition-bounce); }
        .nav-item-card:hover { border-color: rgba(255,255,255,0.1); transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .form-label-sm { font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .icon-preview-box { width: 44px; height: 44px; flex-shrink: 0; background: rgba(0,0,0,0.2); border: 0.5px solid var(--border-main); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); }
        .btn-delete-link { background: rgba(255,59,48,0.1); color: #ff3b30; border: none; width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; cursor: pointer; transition: var(--transition-base); }
        .btn-delete-link:hover { background: #ff3b30; color: white; transform: scale(1.1); }
        .add-link-card { background: rgba(120,120,128,0.05); border: 1.5px dashed var(--border-main); border-radius: 20px; min-height: 180px; cursor: pointer; transition: var(--transition-bounce); color: var(--text-muted); font-weight: 600; font-size: 0.9rem; }
        .add-link-card:hover { background: rgba(120,120,128,0.1); border-color: var(--text-primary); color: var(--text-primary); }
        .btn-add { background: var(--bg-deep); border: 0.5px solid var(--border-main); border-radius: 10px; color: var(--text-primary); font-weight: 600; transition: var(--transition-base); }
        .btn-add:hover { background: var(--text-primary); color: var(--bg-deep); }
        .btn-delete { background: rgba(255,59,48,0.1); color: #ff3b30; border: none; border-radius: 8px; padding: 0.4rem 0.8rem; cursor: pointer; }
        .btn-delete:hover { background: #ff3b30; color: white; }
    </style>
</body>
</html>
