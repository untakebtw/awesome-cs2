<?php
/**
 * Admin servers settings page
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
    <title><?= e(t('admin.servers')) ?> - <?= e($config['name']) ?></title>
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/admin.css">
    <style>
        .server-item {
            background: var(--btn-secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem 1.2rem;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.2s;
        }
        .server-item:hover { background: var(--btn-secondary-hover); }
        .server-info { flex: 1; }
        .server-name { color: var(--text-color); font-weight: 600; font-size: 1rem; }
        .server-address { color: var(--text-color-muted); font-size: 0.85rem; }
        .server-status { display: flex; align-items: center; gap: 0.5rem; margin-right: 1rem; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #dc3545; }
        .status-dot.online { background: #34c759; box-shadow: 0 0 6px rgba(52, 199, 89, 0.5); }
        .toast-container { position: fixed; top: 80px; right: 20px; z-index: 9999; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 admin-page">
    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>

    <div class="container mt-4 mb-5">
        <h2 class="mb-4">
            <i class="fas fa-cog me-2"></i>
            <?= e(t('admin.panel')) ?>
        </h2>

        <?php $adminActivePage = 'servers'; require ROOT_DIR . '/views/partials/admin_nav.php'; ?>

        <!-- Toast notification -->
        <div class="toast-container">
            <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check me-2"></i>
                        <span id="toastMessage"><?= e(t('admin.saved')) ?></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <!-- Add Server -->
        <div class="admin-card">
            <h5><i class="fas fa-plus-circle me-2"></i><?= e(t('admin.addServer')) ?></h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><?= e(t('admin.serverName')) ?></label>
                    <input type="text" class="form-control" id="serverName" placeholder="My CS2 Server">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?= e(t('admin.serverIp')) ?>:<?= e(t('admin.serverPort')) ?></label>
                    <input type="text" class="form-control" id="serverAddress" placeholder="192.168.1.1:27015">
                </div>
                <div class="col-md-4">
                    <label class="form-label">RCON <?= e(t('admin.dbPassword')) ?></label>
                    <input type="password" class="form-control" id="serverRcon" placeholder="rcon_password">
                </div>
            </div>

            <div class="mt-3">
                <button type="button" class="btn btn-primary" onclick="addServer()" id="addBtn">
                    <i class="fas fa-plus me-2"></i><?= e(t('admin.addServer')) ?>
                </button>
            </div>
        </div>

        <!-- Server List -->
        <div class="admin-card">
            <h5><i class="fas fa-list me-2"></i><?= e(t('admin.servers')) ?></h5>

            <div id="serverList">
                <?php
                $servers = $config['servers'] ?? [];
                if (empty($servers)):
                ?>
                    <p class="text-muted text-center py-3" id="noServers">
                        <i class="fas fa-server me-2"></i><?= e(t('admin.noServers')) ?>
                    </p>
                <?php else: ?>
                    <?php foreach ($servers as $i => $server): ?>
                    <div class="server-item" data-index="<?= $i ?>">
                        <div class="server-info">
                            <div class="server-name"><?= e($server['name']) ?></div>
                            <div class="server-address">
                                <i class="fas fa-network-wired me-1"></i>
                                <?= e($server['ip']) ?>:<?= e($server['port']) ?>
                            </div>
                        </div>
                        <div class="server-status">
                            <div class="status-dot" id="status-<?= $i ?>"></div>
                            <span class="text-muted" id="status-text-<?= $i ?>">...</span>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeServer(<?= $i ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        async function addServer() {
            const name = document.getElementById('serverName').value.trim();
            const address = document.getElementById('serverAddress').value.trim();
            const rcon = document.getElementById('serverRcon').value.trim();

            if (!name || !address) return;

            let ip, port;
            if (address.includes(':')) {
                const parts = address.split(':');
                ip = parts[0];
                port = parseInt(parts[1]) || 27015;
            } else {
                ip = address;
                port = 27015;
            }

            const btn = document.getElementById('addBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>...';

            try {
                const response = await fetch('/api/admin/servers/add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, ip, port, rcon })
                });
                const result = await response.json();
                if (result.success) {
                    document.getElementById('toastMessage').textContent = '<?= e(t('admin.saved')) ?>';
                    new bootstrap.Toast(document.getElementById('saveToast')).show();
                    setTimeout(() => location.reload(), 500);
                } else {
                    alert(result.message || 'Error');
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plus me-2"></i><?= e(t('admin.addServer')) ?>';
            }
        }

        async function removeServer(index) {
            if (!confirm('<?= e(t('admin.remove')) ?>?')) return;
            try {
                const response = await fetch('/api/admin/servers/remove', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ index })
                });
                const result = await response.json();
                if (result.success) location.reload();
                else alert(result.message || 'Error');
            } catch (error) {
                console.error('Error:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.server-item').forEach(function(item) {
                checkServerStatus(item.dataset.index);
            });
        });

        async function checkServerStatus(index) {
            try {
                const response = await fetch('/api/server/status?index=' + index);
                const data = await response.json();
                const dot = document.getElementById('status-' + index);
                const text = document.getElementById('status-text-' + index);
                if (data.online) {
                    dot.classList.add('online');
                    text.textContent = data.players + '/' + data.maxplayers + ' <?= e(t('home.players')) ?>';
                    text.className = 'text-success';
                } else {
                    text.textContent = '<?= e(t('home.offline')) ?>';
                    text.className = 'text-danger';
                }
            } catch (e) {
                const text = document.getElementById('status-text-' + index);
                if (text) { text.textContent = 'Error'; text.className = 'text-danger'; }
            }
        }
    </script>
</body>
</html>
