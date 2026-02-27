<?php
/**
 * Admin settings page
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
    <title><?= e(t('admin.panel')) ?> - <?= e($config['name']) ?></title>
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
                <i class="fas fa-cog me-2"></i>
                <?= e(t('admin.panel')) ?>
            </h2>
        </div>

        <!-- Admin Navigation -->
        <?php $adminActivePage = 'settings'; require ROOT_DIR . '/views/partials/admin_nav.php'; ?>

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
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Site Titles -->
                <div class="admin-card">
                    <h5 class="admin-card-header"><i class="fas fa-heading me-2"></i><?= e(t('admin.titlesAndSeo')) ?></h5>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.navbarName')) ?></label>
                        <input type="text" class="form-control" id="siteName" value="<?= e($config['name']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.pageTitle')) ?></label>
                        <input type="text" class="form-control" id="pageTitle" value="<?= e($config['pageTitle'] ?? '') ?>">
                    </div>

                    <div>
                        <label class="form-label"><?= e(t('admin.metaDescription')) ?></label>
                        <textarea class="form-control" id="metaDescription" rows="2"><?= e($config['metaDescription'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Site Statistics -->
                <div class="admin-card">
                    <h5 class="admin-card-header"><i class="fas fa-chart-bar me-2"></i><?= e(t('admin.statistics')) ?></h5>
                    <?php
                    $totalPlayers = 0;
                    $totalSkins = 0;
                    $totalServers = count($config['servers'] ?? []);
                    try {
                        $totalPlayers = (int)$pdo->query("SELECT COUNT(DISTINCT steamid) FROM wp_player_skins")->fetchColumn();
                        $totalSkins = (int)$pdo->query("SELECT COUNT(*) FROM wp_player_skins")->fetchColumn();
                    } catch (Exception $e) {}
                    ?>
                    <div class="stat-item">
                        <span class="stat-label"><?= e(t('admin.totalPlayersAdmin')) ?></span>
                        <span class="stat-value"><?= number_format($totalPlayers) ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><?= e(t('admin.totalServersAdmin')) ?></span>
                        <span class="stat-value"><?= $totalServers ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><?= e(t('admin.totalSkinsAdmin')) ?></span>
                        <span class="stat-value"><?= number_format($totalSkins) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="save-all-container">
            <button type="button" class="btn btn-save-all" onclick="saveSettings()" id="saveBtn">
                <i class="fas fa-save"></i> <?= e(t('admin.save')) ?>
            </button>
        </div>
    </div>

    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        async function saveSettings() {
            const btn = document.getElementById('saveBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>...';

            const data = {
                name: document.getElementById('siteName').value,
                pageTitle: document.getElementById('pageTitle').value,
                metaDescription: document.getElementById('metaDescription').value
            };

            try {
                const resp = await fetch('/api/admin/save', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });
                const result = await resp.json();
                const toast = document.getElementById('saveToast');
                const msgSpan = toast.querySelector('.toast-body > span');
                const icon = toast.querySelector('.toast-body > i');
                if (result.success) {
                    toast.classList.remove('text-bg-danger');
                    toast.classList.add('text-bg-success');
                    icon.className = 'fas fa-check me-2';
                    msgSpan.textContent = result.message || '<?= e(t('admin.saved')) ?>';
                } else {
                    toast.classList.remove('text-bg-success');
                    toast.classList.add('text-bg-danger');
                    icon.className = 'fas fa-exclamation-triangle me-2';
                    msgSpan.textContent = result.message || 'Error!';
                }
                new bootstrap.Toast(toast).show();
            } catch (err) {
                console.error('Error:', err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        }
    </script>
</body>
</html>
