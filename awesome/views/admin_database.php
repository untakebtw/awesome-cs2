<?php
/**
 * Admin database settings page
 * PHP 7.4+
 * 
 * Author: untake
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
    <title><?= e(t('admin.database')) ?> - <?= e($config['name']) ?></title>
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/admin.css">
    <style>
        .form-text-hint { color: var(--text-color-muted); font-size: 0.85rem; margin-top: 0.25rem; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .db-status {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.9rem; font-weight: 500;
        }
        .db-status.connected { background: rgba(52, 199, 89, 0.15); color: #34c759; border: 1px solid rgba(52, 199, 89, 0.3); }
        .db-status.disconnected { background: rgba(255, 59, 48, 0.15); color: #ff3b30; border: 1px solid rgba(255, 59, 48, 0.3); }
        .db-status.not-configured { background: rgba(255, 204, 0, 0.15); color: #ffcc00; border: 1px solid rgba(255, 204, 0, 0.3); }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 admin-page">
    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">
                <i class="fas fa-database me-2"></i><?= e(t('admin.database')) ?>
            </h2>
        </div>

        <?php $adminActivePage = 'database'; require ROOT_DIR . '/views/partials/admin_nav.php'; ?>

        <div class="toast-container">
            <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body"><i class="fas fa-check me-2"></i><span id="toastMessage"><?= e(t('admin.saved')) ?></span></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="admin-card">
                    <h5><i class="fas fa-database me-2"></i><?= e(t('admin.weaponPaintsDb')) ?></h5>
                    <?php 
                    $mainDb = $config['DB'] ?? [];
                    $mainConnected = false;
                    if (!empty($mainDb['host']) && !empty($mainDb['database'])) {
                        try {
                            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $mainDb['host'], $mainDb['port'] ?? 3306, $mainDb['database']);
                            new PDO($dsn, $mainDb['user'] ?? '', $mainDb['password'] ?? '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3]);
                            $mainConnected = true;
                        } catch (Exception $ex) {}
                    }
                    ?>
                    <div class="mb-3">
                        <?php if ($mainConnected): ?>
                            <span class="db-status connected"><i class="fas fa-check-circle"></i> <?= e(t('admin.dbConnected')) ?></span>
                        <?php else: ?>
                            <span class="db-status disconnected"><i class="fas fa-times-circle"></i> <?= e(t('admin.dbDisconnected')) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.dbHost')) ?></label>
                        <input type="text" class="form-control" id="mainDbHost" value="<?= e($mainDb['host'] ?? 'localhost') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.dbPort')) ?></label>
                        <input type="number" class="form-control" id="mainDbPort" value="<?= e($mainDb['port'] ?? 3306) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.dbName')) ?></label>
                        <input type="text" class="form-control" id="mainDbName" value="<?= e($mainDb['database'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.dbUser')) ?></label>
                        <input type="text" class="form-control" id="mainDbUser" value="<?= e($mainDb['user'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= e(t('admin.dbPassword')) ?></label>
                        <input type="password" class="form-control" id="mainDbPassword" value="<?= e($mainDb['password'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-info" onclick="testDb('main')">
                            <i class="fas fa-plug me-1"></i> <?= e(t('admin.testConnection')) ?>
                        </button>
                        <button class="btn btn-save" onclick="saveDb('main')">
                            <i class="fas fa-save me-1"></i> <?= e(t('admin.save')) ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="admin-card">
                    <h5><i class="fas fa-trophy me-2"></i><?= e(t('admin.levelranksDb')) ?></h5>
                    <?php 
                    $lrDb = $config['levelranks'] ?? [];
                    $lrConnected = false;
                    $lrEnabled = !empty($lrDb['enabled']);
                    if ($lrEnabled && !empty($lrDb['host']) && !empty($lrDb['database'])) {
                        try {
                            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $lrDb['host'], $lrDb['port'] ?? 3306, $lrDb['database']);
                            new PDO($dsn, $lrDb['user'] ?? '', $lrDb['password'] ?? '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3]);
                            $lrConnected = true;
                        } catch (Exception $ex) {}
                    }
                    ?>
                    <div class="mb-3">
                        <?php if (!$lrEnabled): ?>
                            <span class="db-status not-configured"><i class="fas fa-exclamation-triangle"></i> <?= e(t('admin.dbNotConfigured')) ?></span>
                        <?php elseif ($lrConnected): ?>
                            <span class="db-status connected"><i class="fas fa-check-circle"></i> <?= e(t('admin.dbConnected')) ?></span>
                        <?php else: ?>
                            <span class="db-status disconnected"><i class="fas fa-times-circle"></i> <?= e(t('admin.dbDisconnected')) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="lrEnabled" <?= $lrEnabled ? 'checked' : '' ?>>
                        <label class="form-check-label" for="lrEnabled"><?= e(t('admin.enableLevelranks')) ?></label>
                    </div>
                    <div id="lrFields" style="<?= $lrEnabled ? '' : 'opacity:0.5;pointer-events:none;' ?>">
                        <div class="mb-3">
                            <label class="form-label"><?= e(t('admin.dbHost')) ?></label>
                            <input type="text" class="form-control" id="lrDbHost" value="<?= e($lrDb['host'] ?? 'localhost') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= e(t('admin.dbPort')) ?></label>
                            <input type="number" class="form-control" id="lrDbPort" value="<?= e($lrDb['port'] ?? 3306) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= e(t('admin.dbName')) ?></label>
                            <input type="text" class="form-control" id="lrDbName" value="<?= e($lrDb['database'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= e(t('admin.lrTableName')) ?></label>
                            <input type="text" class="form-control" id="lrDbTable" value="<?= e($lrDb['table'] ?? 'lvl_base') ?>" placeholder="lvl_base">
                            <div class="form-text-hint">lvl_base</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= e(t('admin.dbUser')) ?></label>
                            <input type="text" class="form-control" id="lrDbUser" value="<?= e($lrDb['user'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= e(t('admin.dbPassword')) ?></label>
                            <input type="password" class="form-control" id="lrDbPassword" value="<?= e($lrDb['password'] ?? '') ?>">
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-info" onclick="testDb('lr')">
                                <i class="fas fa-plug me-1"></i> <?= e(t('admin.testConnection')) ?>
                            </button>
                            <button class="btn btn-save" onclick="saveDb('lr')">
                                <i class="fas fa-save me-1"></i> <?= e(t('admin.save')) ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save All Button -->
        <div class="save-all-container">
            <button class="btn btn-save-all" onclick="saveAll()" id="saveAllBtn">
                <i class="fas fa-save"></i> <?= e(t('admin.saveAll')) ?>
            </button>
        </div>
    </div>

    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('lrEnabled').addEventListener('change', function() {
            const fields = document.getElementById('lrFields');
            fields.style.opacity = this.checked ? '1' : '0.5';
            fields.style.pointerEvents = this.checked ? 'auto' : 'none';
        });

        function showToast(message, success) {
            const toast = document.getElementById('saveToast');
            const msgSpan = document.getElementById('toastMessage');
            const icon = toast.querySelector('.toast-body > i');
            toast.classList.remove(success ? 'text-bg-danger' : 'text-bg-success');
            toast.classList.add(success ? 'text-bg-success' : 'text-bg-danger');
            icon.className = success ? 'fas fa-check me-2' : 'fas fa-exclamation-triangle me-2';
            msgSpan.textContent = message;
            new bootstrap.Toast(toast).show();
        }

        async function testDb(type) {
            let data;
            if (type === 'main') {
                data = { type: 'main', host: document.getElementById('mainDbHost').value, port: parseInt(document.getElementById('mainDbPort').value) || 3306, database: document.getElementById('mainDbName').value, user: document.getElementById('mainDbUser').value, password: document.getElementById('mainDbPassword').value };
            } else {
                data = { type: 'levelranks', host: document.getElementById('lrDbHost').value, port: parseInt(document.getElementById('lrDbPort').value) || 3306, database: document.getElementById('lrDbName').value, table: document.getElementById('lrDbTable').value, user: document.getElementById('lrDbUser').value, password: document.getElementById('lrDbPassword').value };
            }
            try {
                const resp = await fetch('/api/admin/database/test', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
                const result = await resp.json();
                showToast(result.message || (result.success ? 'OK' : 'Error'), result.success);
            } catch (err) {
                showToast('Network error', false);
            }
        }

        async function saveDb(type) {
            let data;
            if (type === 'main') {
                data = { type: 'main', host: document.getElementById('mainDbHost').value, port: parseInt(document.getElementById('mainDbPort').value) || 3306, database: document.getElementById('mainDbName').value, user: document.getElementById('mainDbUser').value, password: document.getElementById('mainDbPassword').value };
            } else {
                data = { type: 'levelranks', enabled: document.getElementById('lrEnabled').checked, host: document.getElementById('lrDbHost').value, port: parseInt(document.getElementById('lrDbPort').value) || 3306, database: document.getElementById('lrDbName').value, table: document.getElementById('lrDbTable').value || 'lvl_base', user: document.getElementById('lrDbUser').value, password: document.getElementById('lrDbPassword').value };
            }
            try {
                const resp = await fetch('/api/admin/database/save', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
                const result = await resp.json();
                showToast(result.message || (result.success ? '<?= e(t('admin.saved')) ?>' : 'Error'), result.success);
                if (result.success) setTimeout(function() { location.reload(); }, 1000);
            } catch (err) {
                showToast('Network error', false);
            }
        }

        async function saveAll() {
            const btn = document.getElementById('saveAllBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= e(t('admin.saving')) ?>';

            const mainData = { type: 'main', host: document.getElementById('mainDbHost').value, port: parseInt(document.getElementById('mainDbPort').value) || 3306, database: document.getElementById('mainDbName').value, user: document.getElementById('mainDbUser').value, password: document.getElementById('mainDbPassword').value };
            const lrData = { type: 'levelranks', enabled: document.getElementById('lrEnabled').checked, host: document.getElementById('lrDbHost').value, port: parseInt(document.getElementById('lrDbPort').value) || 3306, database: document.getElementById('lrDbName').value, table: document.getElementById('lrDbTable').value || 'lvl_base', user: document.getElementById('lrDbUser').value, password: document.getElementById('lrDbPassword').value };

            try {
                const [r1, r2] = await Promise.all([
                    fetch('/api/admin/database/save', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(mainData) }),
                    fetch('/api/admin/database/save', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(lrData) })
                ]);
                const [res1, res2] = await Promise.all([r1.json(), r2.json()]);
                if (res1.success && res2.success) {
                    showToast('<?= e(t('admin.allSaved')) ?>', true);
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    showToast((res1.message || '') + ' ' + (res2.message || ''), false);
                }
            } catch (err) {
                showToast('Network error', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        }
    </script>
</body>
</html>
