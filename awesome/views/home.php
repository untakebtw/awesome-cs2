<?php
/**
 * Home page view
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
    <title><?= e($config['pageTitle'] ?? $config['name']) ?></title>
    <meta name="description" content="<?= e($config['metaDescription'] ?? '') ?>">
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <style>
        /* ===== PREMIUM iOS BENTO SYSTEM ===== */
        .home-section { padding: 4rem 0; position: relative; }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }
        
        .section-header h3 {
            color: var(--text-primary);
            font-weight: 800;
            font-size: 2rem;
            margin: 0;
            letter-spacing: -1px;
            font-family: var(--font-accent);
        }
        
        .section-header .header-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--text-muted), transparent);
            opacity: 0.2;
            position: relative;
        }
        
        .section-header .header-line::before {
            content: '';
            position: absolute;
            left: 0; top: -2px; width: 6px; height: 6px;
            background: var(--text-muted);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--text-muted);
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
        }

        /* ===== SERVER CARDS: iOS CONTROL CENTER STYLE ===== */
        .server-card {
            grid-column: span 6;
            background: var(--bg-surface);
            border: 0.5px solid var(--border-main);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition-spring);
            display: flex;
            flex-direction: column;
            position: relative;
            backdrop-filter: blur(var(--glass-blur)) saturate(1.8);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(1.8);
            box-shadow: var(--shadow-premium);
        }
        @media (max-width: 992px) { .server-card { grid-column: span 12; } }

        .server-card:hover {
            transform: scale(1.02);
            background: var(--bg-surface-hover);
            border-color: var(--border-hover);
        }

        .server-card-map {
            position: relative;
            height: 160px;
            background-size: cover;
            background-position: center;
            transition: var(--transition-smooth);
        }
        
        .map-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, var(--bg-surface) 100%);
        }

        .server-status-pill {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 4px 12px;
            border-radius: 20px;
            color: var(--text-secondary);
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 0.5px solid var(--border-main);
        }

        .server-card-body {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: -10px;
            position: relative;
            z-index: 2;
        }

        .server-icon-wrap {
            width: 56px; height: 56px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-primary); font-size: 1.4rem;
            box-shadow: none;
            transition: var(--transition-spring);
            border: 0.5px solid var(--border-main);
        }
        .server-card:hover .server-icon-wrap { transform: rotate(-5deg) scale(1.05); }

        .server-card-info { flex: 1; min-width: 0; }
        .server-name-big {
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 2px;
            letter-spacing: -0.5px;
        }
        .server-ip-sub {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            opacity: 0.8;
        }

        .server-stats-row {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 0.5px solid var(--border-main);
        }
        .stat-mini { display: flex; flex-direction: column; }
        .stat-mini-val { color: var(--text-primary); font-weight: 700; font-size: 1rem; }
        .stat-mini-label { color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; font-weight: 600; }

        /* ===== STATS: BENTO WIDGETS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        @media (max-width: 992px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-widget {
            background: var(--bg-surface);
            border: 0.5px solid var(--border-main);
            border-radius: var(--radius-lg);
            padding: 2rem;
            transition: var(--transition-spring);
            backdrop-filter: blur(var(--glass-blur)) saturate(1.8);
            box-shadow: var(--shadow-premium);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 180px;
        }
        .stat-widget:hover { transform: translateY(-5px); background: var(--bg-surface-hover); }

        .stat-widget-icon {
            font-size: 1.5rem;
            color: var(--accent-primary);
            margin-bottom: auto;
            width: 44px; height: 44px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            border: 0.5px solid var(--border-main);
        }
        .stat-widget-val {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--text-primary);
            line-height: 1;
            font-family: var(--font-accent);
            letter-spacing: -2px;
            margin: 1rem 0 0.5rem 0;
        }
        .stat-widget-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== CONNECT SHEET (iOS Style) ===== */
        .connect-popup {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(20px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1rem;
        }
        .connect-popup.show { display: flex; animation: sheetIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes sheetIn { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .connect-sheet {
            background: var(--bg-surface);
            border: 0.5px solid var(--border-main);
            border-radius: 32px;
            padding: 2.5rem;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            backdrop-filter: blur(50px) saturate(1.8);
        }
        .connect-sheet h4 {
            color: var(--text-primary);
            font-weight: 800; font-size: 1.8rem;
            margin-bottom: 0.5rem; letter-spacing: -1px;
        }
        .server-tag {
            color: var(--text-secondary);
            font-weight: 700; font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .ip-box {
            background: rgba(120, 120, 128, 0.1);
            padding: 1.2rem; border-radius: 20px;
            color: var(--text-primary);
            font-family: var(--font-accent);
            font-size: 1.1rem; font-weight: 700;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
            border: 0.5px solid var(--border-main);
        }
    </style>
</head>
<body class="bg-color d-flex flex-column min-vh-100" style="padding-bottom: 0;">
    <div class="flex-grow-1">
    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>

    <?php
    $templateBlocks = $config['template']['blocks'] ?? [
        ['id' => 'servers', 'order' => 0, 'visible' => false],
        ['id' => 'stats', 'order' => 1, 'visible' => false],
        ['id' => 'info', 'order' => 2, 'visible' => false],
    ];
    usort($templateBlocks, function($a, $b) { return ($a['order'] ?? 0) - ($b['order'] ?? 0); });
    
    $servers = $config['servers'] ?? [];
    $lrConfig = $config['levelranks'] ?? [];
    $infoBlocks = $config['information']['blocks'] ?? [];
    
    foreach ($templateBlocks as $tBlock):
        if (empty($tBlock['visible'])) continue;
        $blockId = $tBlock['id'] ?? '';
    ?>

    <?php if ($blockId === 'servers' && !empty($servers)): ?>
    <div class="home-section servers-section">
        <div class="container">
            <div class="section-header">
                <h3><i class="fas fa-server me-3"></i><?= e(t('home.ourServers')) ?></h3>
                <div class="header-line"></div>
            </div>
            <div class="bento-grid" id="serversGrid">
                <?php foreach ($servers as $i => $srv): ?>
                <div class="server-card" id="server-card-<?= $i ?>" data-ip="<?= e($srv['ip']) ?>" data-port="<?= e($srv['port']) ?>">
                    <div class="server-card-map" id="server-map-<?= $i ?>">
                        <div class="map-overlay"></div>
                        <div class="server-status-pill"><span id="server-status-pill-<?= $i ?>">Online</span></div>
                    </div>
                    <div class="server-card-body">
                        <div class="server-icon-wrap">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="server-card-info">
                            <div class="server-name-big"><?= e($srv['name']) ?></div>
                            <div class="server-ip-sub"><?= e($srv['ip']) ?>:<?= e($srv['port']) ?></div>
                        </div>
                        <button class="server-connect-btn" onclick="showConnect(<?= $i ?>, '<?= e($srv['ip']) ?>', <?= e($srv['port']) ?>, '<?= e($srv['name']) ?>')" style="width: 44px; height: 44px; border-radius: 12px; border: none; background: var(--accent-primary); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 5px 15px var(--accent-glow); transition: var(--transition-spring);">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="px-4 pb-4">
                        <div class="server-stats-row">
                            <div class="stat-mini">
                                <span class="stat-mini-val" id="server-players-<?= $i ?>">0/0</span>
                                <span class="stat-mini-label"><?= e(t('home.players')) ?></span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-val" id="server-map-name-<?= $i ?>">Unknown</span>
                                <span class="stat-mini-label">Map</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($blockId === 'stats' && !empty($lrConfig['enabled'])): ?>
    <div class="home-section stats-section">
        <div class="container">
            <div class="section-header">
                <h3><i class="fas fa-trophy me-3"></i><?= e(t('home.playerStats')) ?></h3>
                <div class="header-line"></div>
            </div>
            <div class="stats-grid" id="statsGrid">
                <div class="stat-widget">
                    <div class="stat-widget-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-widget-val" id="statTotal">-</div>
                    <div class="stat-widget-label"><?= e(t('home.totalPlayers')) ?></div>
                </div>
                <div class="stat-widget">
                    <div class="stat-widget-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-widget-val" id="statMonth">-</div>
                    <div class="stat-widget-label"><?= e(t('home.thisMonth')) ?></div>
                </div>
                <div class="stat-widget">
                    <div class="stat-widget-icon"><i class="fas fa-calendar-week"></i></div>
                    <div class="stat-widget-val" id="statWeek">-</div>
                    <div class="stat-widget-label"><?= e(t('home.thisWeek')) ?></div>
                </div>
                <div class="stat-widget">
                    <div class="stat-widget-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-widget-val" id="statToday">-</div>
                    <div class="stat-widget-label"><?= e(t('home.today')) ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($blockId === 'info' && !empty($infoBlocks)): ?>
    <div class="home-section info-section">
        <div class="container">
            <div style="background: var(--bg-surface); border: 0.5px solid var(--border-main); border-radius: 32px; padding: 4rem; backdrop-filter: blur(var(--glass-blur)) saturate(1.8); box-shadow: var(--shadow-premium);">
                <?php foreach ($infoBlocks as $ib): ?>
                    <?php $ibType = $ib['type'] ?? 'text'; ?>
                    <?php if ($ibType === 'heading'): ?>
                        <<?= e($ib['level'] ?? 'h2') ?> style="color: var(--text-primary); font-weight:800; letter-spacing:-1.5px; font-family:var(--font-accent);"><?= e($ib['content'] ?? '') ?></<?= e($ib['level'] ?? 'h2') ?>>
                    <?php elseif ($ibType === 'text'): ?>
                        <p style="color: var(--text-secondary); font-size:1.15rem; line-height:1.8;"><?= nl2br(e($ib['content'] ?? '')) ?></p>
                    <?php elseif ($ibType === 'button'): ?>
                        <a href="<?= e($ib['url'] ?? '#') ?>" class="btn btn-primary d-inline-flex align-items-center justify-content-center" style="padding:16px 40px; font-weight:700; border-radius:18px;" target="_blank"><?= e($ib['content'] ?? '') ?></a>
                    <?php elseif ($ibType === 'link'): ?>
                        <p><a href="<?= e($ib['url'] ?? '#') ?>" style="color:var(--accent-primary); font-weight:700; text-decoration:none;" target="_blank"><?= e($ib['content'] ?? '') ?></a></p>
                    <?php elseif ($ibType === 'divider'): ?>
                        <hr style="border-color: var(--border-main); margin: 3rem 0; opacity:0.8;">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php endforeach; ?>

    <!-- Connect Popup (Sheet Style) -->
    <div class="connect-popup" id="connectPopup" onclick="if(event.target===this)hideConnect()">
        <div class="connect-sheet">
            <h4 id="popupServerName"></h4>
            <div class="server-tag">CS2 Community Server</div>
            <div class="ip-box" id="popupServerIp"></div>
            <div class="d-grid gap-3">
                <button class="btn btn-primary w-100 py-3" style="border-radius:18px; font-weight:700; font-size:1.1rem;" onclick="connectToServer()">
                    <i class="fas fa-play me-2"></i> <?= e(t('home.connectBtn')) ?>
                </button>
                <button class="btn btn-outline-light w-100 py-3" style="border-radius:18px; font-weight:700; border-color:var(--border-main); color:var(--text-primary);" onclick="copyIp(event)">
                    <i class="fas fa-copy me-2"></i> <?= e(t('home.copyIp')) ?>
                </button>
            </div>
            <button class="btn btn-link mt-4" style="text-decoration:none; font-weight:700; color:var(--text-muted);" onclick="hideConnect()">
                <?= e(t('home.close')) ?>
            </button>
        </div>
    </div>
    
    <!-- Copy Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 10000;">
        <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="copyToastMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    </div>
    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        function getMapUrl(mapName) {
            return '/images/maps/' + mapName + '.webp';
        }

        let currentConnectIp = '';
        let currentConnectPort = '';

        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/servers')
                .then(r => r.json())
                .then(servers => {
                    servers.forEach(function(srv) {
                        const i = srv.index;
                        const card = document.getElementById('server-card-' + i);
                        const mapDiv = document.getElementById('server-map-' + i);
                        const playersDiv = document.getElementById('server-players-' + i);
                        const barDiv = document.getElementById('server-bar-' + i);

                        if (!card) return;

                        if (srv.online) {
                            const mapName = (srv.map || '').toLowerCase();
                            mapDiv.style.backgroundImage = 'url(' + getMapUrl(mapName) + ')';

                            const statusPill = document.getElementById('server-status-pill-' + i);
                            if (statusPill) {
                                statusPill.textContent = 'Online';
                                statusPill.style.color = '#34c759';
                                statusPill.style.borderColor = '#34c759';
                                statusPill.parentElement.style.background = 'rgba(52, 199, 89, 0.15)';
                            }

                            const players = srv.players || 0;
                            const maxPlayers = srv.maxplayers || 1;
                            playersDiv.textContent = players + '/' + maxPlayers;
                            
                            const mapLabel = document.getElementById('server-map-name-' + i);
                            if (mapLabel) mapLabel.textContent = srv.map || 'Unknown';
                        } else {
                            const statusPill = document.getElementById('server-status-pill-' + i);
                            if (statusPill) {
                                statusPill.textContent = 'Offline';
                                statusPill.style.color = '#ff3b30';
                                statusPill.style.borderColor = '#ff3b30';
                                statusPill.parentElement.style.background = 'rgba(255, 59, 48, 0.15)';
                            }
                            playersDiv.textContent = '0/0';
                            const mapLabel = document.getElementById('server-map-name-' + i);
                            if (mapLabel) mapLabel.textContent = '-';
                        }
                    });
                })
                .catch(function(err) {
                    console.error('Failed to load servers:', err);
                });
        });

        function showConnect(index, ip, port, name) {
            currentConnectIp = ip;
            currentConnectPort = port;
            document.getElementById('popupServerName').textContent = name;
            document.getElementById('popupServerIp').textContent = ip + ':' + port;
            document.getElementById('connectPopup').classList.add('show');
        }

        function hideConnect() {
            document.getElementById('connectPopup').classList.remove('show');
        }

        function copyIp(event) {
            // Format: connect IP:port
            const text = 'connect ' + currentConnectIp + ':' + currentConnectPort;
            
            // Try clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopyFeedback(event);
                }).catch(function(err) {
                    console.error('Clipboard API failed, trying fallback:', err);
                    fallbackCopy(text, event);
                });
            } else {
                fallbackCopy(text, event);
            }
        }
        
        function fallbackCopy(text, event) {
            // Fallback for older browsers
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand("copy");
                showCopyFeedback(event);
            } catch (err) {
                console.error("Fallback copy failed:", err);
                alert("<?= e(t('home.copyFailed')) ?>: " + text);
            }
            document.body.removeChild(textArea);
        }
        
        function showCopyFeedback(event) {
            const btn = event.target.closest('button');
            if (!btn) return;
            
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> <?= e(t('home.copied')) ?>';
            btn.classList.remove('btn-outline-light');
            btn.classList.add('btn-success');
            
            // Show toast notification
            showToast('<?= e(t('home.ipCopied')) ?>: connect ' + currentConnectIp + ':' + currentConnectPort);
            
            setTimeout(function() { 
                btn.innerHTML = orig;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-light');
            }, 2000);
        }
        
        function showToast(message) {
            const toast = document.getElementById('copyToast');
            if (toast) {
                document.getElementById('copyToastMessage').textContent = message;
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        }

        function connectToServer() {
            window.location.href = 'steam://connect/' + currentConnectIp + ':' + currentConnectPort;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') hideConnect();
        });

        <?php if (!empty($config['levelranks']['enabled'])): ?>
        fetch('/api/levelranks/stats')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.enabled && !data.error) {
                    document.getElementById('statTotal').textContent = formatNumber(data.total || 0);
                    document.getElementById('statMonth').textContent = formatNumber(data.month || 0);
                    document.getElementById('statWeek').textContent = formatNumber(data.week || 0);
                    document.getElementById('statToday').textContent = formatNumber(data.today || 0);
                }
            })
            .catch(function(err) {
                console.error('Failed to load LevelRanks stats:', err);
            });

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        <?php endif; ?>
    </script>
</body>
</html>
