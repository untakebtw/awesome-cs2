<?php
/**
 * CS2 WeaponPaints Website - PHP Version
 * Main entry point / router
 * Requires PHP 7.4+
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

session_start();

define('ROOT_DIR', __DIR__);

// Supported languages
$supportedLangs = ['en', 'ru', 'pt-BR', 'zh-CN'];

// Check if config exists
$configPath = ROOT_DIR . '/config.json';
$configExists = file_exists($configPath);

$config = null;
if ($configExists) {
    $configContent = file_get_contents($configPath);
    $config = json_decode($configContent, true);
}

// Determine the request path
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = rtrim(dirname($scriptName), '/\\');
$path = parse_url($requestUri, PHP_URL_PATH);
if ($basePath !== '') {
    $path = substr($path, strlen($basePath));
}
if ($path === false || $path === '') {
    $path = '/';
}

// Extract language prefix from path: /en/install/step2 -> lang=en, path=/install/step2
$currentLang = null;
$pathParts = explode('/', ltrim($path, '/'), 2);
if (!empty($pathParts[0]) && in_array($pathParts[0], $supportedLangs)) {
    $currentLang = $pathParts[0];
    $path = '/' . ($pathParts[1] ?? '');
    if ($path === '/') {
        $path = '/';
    }
} else {
    // Default language from config or 'en'
    $currentLang = $config['lang'] ?? 'en';
}

// Serve static files from public directory
$publicDir = ROOT_DIR . '/public';
$staticFile = realpath($publicDir . $path);
if ($staticFile && strpos($staticFile, realpath($publicDir)) === 0 && is_file($staticFile)) {
    $ext = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'json'  => 'application/json',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'webp'  => 'image/webp',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'ico'   => 'image/x-icon',
    ];
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    readfile($staticFile);
    exit;
}

// If no config, redirect to install (except for /install routes)
if (!$config || empty($config['name']) || empty($config['DB'])) {
    if (strpos($path, '/install') !== 0) {
        header('Location: /' . $currentLang . '/install');
        exit;
    }
}

// ---- ROUTING ----

// Install wizard
if (strpos($path, '/install') === 0) {
    require ROOT_DIR . '/src/install.php';
    exit;
}

// Steam auth
if ($path === '/api/auth/steam') {
    require ROOT_DIR . '/src/auth.php';
    steamLogin($config);
    exit;
}

if ($path === '/api/auth/steam/return') {
    require ROOT_DIR . '/src/auth.php';
    steamReturn($config);
    exit;
}

// Logout
if ($path === '/api/logout') {
    session_destroy();
    header('Location: /' . $currentLang);
    exit;
}

// Delete account
if ($path === '/api/delete') {
    if (isset($_SESSION['steam_user'])) {
        require ROOT_DIR . '/src/db.php';
        $steamid = $_SESSION['steam_user']['steamid'];
        $pdo = getDb($config);
        $tables = [
            'wp_player_agents',
            'wp_player_gloves',
            'wp_player_knife',
            'wp_player_music',
            'wp_player_skins',
        ];
        foreach ($tables as $table) {
            $stmt = $pdo->prepare("DELETE FROM `{$table}` WHERE steamid = :steamid");
            $stmt->execute(['steamid' => $steamid]);
        }
    }
    session_destroy();
    header('Location: /' . $currentLang);
    exit;
}

// Weapon API (AJAX replacement for Socket.IO)
if (strpos($path, '/api/weapon/') === 0) {
    require ROOT_DIR . '/src/api.php';
    handleWeaponApi($path, $config);
    exit;
}

// Admin save API
if ($path === '/api/admin/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    
    // Update config with admin settings
    if (isset($data['name'])) $config['name'] = $data['name'];
    if (isset($data['pageTitle'])) $config['pageTitle'] = $data['pageTitle'];
    if (isset($data['metaDescription'])) $config['metaDescription'] = $data['metaDescription'];
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true]);
}

// Admin add server API
if ($path === '/api/admin/servers/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    $name = $data['name'] ?? '';
    $ip = $data['ip'] ?? '';
    $port = (int)($data['port'] ?? 27015);
    $rcon = $data['rcon'] ?? '';
    
    if (empty($name) || empty($ip)) {
        jsonResponse(['success' => false, 'message' => 'Name and IP are required']);
    }
    
    if (!isset($config['servers'])) {
        $config['servers'] = [];
    }
    
    $config['servers'][] = [
        'name' => $name,
        'ip' => $ip,
        'port' => $port,
        'rcon' => $rcon,
    ];
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true]);
}

// Admin remove server API
if ($path === '/api/admin/servers/remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    $index = (int)($data['index'] ?? -1);
    
    if (!isset($config['servers'][$index])) {
        jsonResponse(['success' => false, 'message' => 'Server not found']);
    }
    
    array_splice($config['servers'], $index, 1);
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true]);
}

// Server status query API
if ($path === '/api/server/status') {
    require ROOT_DIR . '/src/helpers.php';
    require ROOT_DIR . '/src/sourcequery.php';
    
    $index = (int)($_GET['index'] ?? -1);
    $servers = $config['servers'] ?? [];
    
    if (!isset($servers[$index])) {
        jsonResponse(['online' => false, 'error' => 'Server not found']);
    }
    
    $server = $servers[$index];
    $info = queryServer($server['ip'], $server['port']);
    
    jsonResponse($info);
}

// Admin database test API
if ($path === '/api/admin/database/test' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    $host = $data['host'] ?? 'localhost';
    $port = (int)($data['port'] ?? 3306);
    $database = $data['database'] ?? '';
    $dbUser = $data['user'] ?? '';
    $dbPass = $data['password'] ?? '';
    
    if (empty($database)) {
        jsonResponse(['success' => false, 'message' => 'Database name is required']);
    }
    
    try {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $database);
        $testPdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        
        // If LevelRanks, check table exists
        if (($data['type'] ?? '') === 'levelranks' && !empty($data['table'])) {
            $table = preg_replace('/[^a-zA-Z0-9_]/', '', $data['table']);
            $stmt = $testPdo->query("SHOW TABLES LIKE '" . $table . "'");
            if ($stmt->rowCount() === 0) {
                jsonResponse(['success' => false, 'message' => 'Connected, but table \'' . $table . '\' not found']);
            }
            $count = $testPdo->query("SELECT COUNT(*) FROM `" . $table . "`")->fetchColumn();
            jsonResponse(['success' => true, 'message' => 'Connected! Table has ' . $count . ' rows']);
        }
        
        jsonResponse(['success' => true, 'message' => 'Connection successful!']);
    } catch (PDOException $e) {
        jsonResponse(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    }
}

// Admin database save API
if ($path === '/api/admin/database/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    $type = $data['type'] ?? '';
    
    if ($type === 'main') {
        $config['DB'] = [
            'host' => $data['host'] ?? 'localhost',
            'port' => (int)($data['port'] ?? 3306),
            'database' => $data['database'] ?? '',
            'user' => $data['user'] ?? '',
            'password' => $data['password'] ?? '',
        ];
    } elseif ($type === 'levelranks') {
        $config['levelranks'] = [
            'enabled' => !empty($data['enabled']),
            'host' => $data['host'] ?? 'localhost',
            'port' => (int)($data['port'] ?? 3306),
            'database' => $data['database'] ?? '',
            'table' => $data['table'] ?? 'lvl_base',
            'user' => $data['user'] ?? '',
            'password' => $data['password'] ?? '',
        ];
    } else {
        jsonResponse(['success' => false, 'message' => 'Invalid type']);
    }
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true, 'message' => 'Saved!']);
}

// Admin template save API
if ($path === '/api/admin/template/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    $blocks = [];
    foreach (($data['blocks'] ?? []) as $block) {
        $blocks[] = [
            'id' => $block['id'] ?? '',
            'order' => (int)($block['order'] ?? 0),
            'visible' => !empty($block['visible']),
        ];
    }
    
    $config['template'] = ['blocks' => $blocks];
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true, 'message' => $currentLang === 'ru' ? 'Шаблон сохранён!' : 'Template saved!']);
}

// Admin information save API
if ($path === '/api/admin/information/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    $blocks = [];
    foreach (($data['blocks'] ?? []) as $block) {
        $b = ['type' => $block['type'] ?? 'text'];
        if (isset($block['content'])) $b['content'] = $block['content'];
        if (isset($block['level'])) $b['level'] = $block['level'];
        if (isset($block['url'])) $b['url'] = $block['url'];
        if (isset($block['style'])) $b['style'] = $block['style'];
        $blocks[] = $b;
    }
    
    $config['information'] = ['blocks' => $blocks];
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true, 'message' => $currentLang === 'ru' ? 'Информация сохранена!' : 'Information saved!']);
}

// Admin navigation save API
if ($path === '/api/admin/navigation/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require ROOT_DIR . '/src/helpers.php';
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    
    $data = getJsonBody();
    
    $navbar = [];
    foreach (($data['navbar'] ?? []) as $item) {
        if (!empty($item['name']) && !empty($item['path'])) {
            $navbar[] = [
                'name' => $item['name'],
                'path' => $item['path'],
                'sort' => (int)($item['sort'] ?? 0),
                'icon' => $item['icon'] ?? '',
            ];
        }
    }
    
    $footer = [];
    foreach (($data['footer'] ?? []) as $item) {
        if (!empty($item['name']) && !empty($item['path'])) {
            $footer[] = [
                'name' => $item['name'],
                'path' => $item['path'],
                'sort' => (int)($item['sort'] ?? 0),
                'icon' => $item['icon'] ?? '',
            ];
        }
    }
    
    $config['navigation'] = [
        'navbar' => $navbar,
        'footer' => $footer,
    ];
    
    // Save footer settings (text, custom links, social)
    if (isset($data['footerSettings'])) {
        $fs = $data['footerSettings'];
        $config['footer'] = [
            'text' => $fs['text'] ?? '',
            'links' => $fs['links'] ?? [],
            'social' => $fs['social'] ?? [],
        ];
    }
    
    $result = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config'], 500);
    }
    
    jsonResponse(['success' => true, 'message' => 'Navigation saved!']);
}

// LevelRanks stats API
if ($path === '/api/levelranks/stats') {
    require ROOT_DIR . '/src/helpers.php';
    
    $lr = $config['levelranks'] ?? [];
    if (empty($lr['enabled'])) {
        jsonResponse(['enabled' => false]);
    }
    
    try {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $lr['host'], $lr['port'] ?? 3306, $lr['database']);
        $lrPdo = new PDO($dsn, $lr['user'] ?? '', $lr['password'] ?? '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $lr['table'] ?? 'lvl_base');
        
        // Total players (all time)
        $totalAll = (int)$lrPdo->query("SELECT COUNT(*) FROM `" . $table . "`")->fetchColumn();
        
        // Players this month (lastconnect >= first day of month)
        $monthStart = date('Y-m-01 00:00:00');
        $monthTs = strtotime($monthStart);
        $totalMonth = (int)$lrPdo->query("SELECT COUNT(*) FROM `" . $table . "` WHERE `lastconnect` >= " . $monthTs)->fetchColumn();
        
        // Players this week (lastconnect >= Monday)
        $weekStart = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $weekTs = strtotime($weekStart);
        $totalWeek = (int)$lrPdo->query("SELECT COUNT(*) FROM `" . $table . "` WHERE `lastconnect` >= " . $weekTs)->fetchColumn();
        
        // Players today
        $todayStart = date('Y-m-d 00:00:00');
        $todayTs = strtotime($todayStart);
        $totalToday = (int)$lrPdo->query("SELECT COUNT(*) FROM `" . $table . "` WHERE `lastconnect` >= " . $todayTs)->fetchColumn();
        
        jsonResponse([
            'enabled' => true,
            'total' => $totalAll,
            'month' => $totalMonth,
            'week' => $totalWeek,
            'today' => $totalToday,
        ]);
    } catch (Exception $e) {
        jsonResponse(['enabled' => true, 'error' => $e->getMessage()]);
    }
}

// All servers status API (for home page)
if ($path === '/api/servers') {
    require ROOT_DIR . '/src/helpers.php';
    require ROOT_DIR . '/src/sourcequery.php';
    
    $servers = $config['servers'] ?? [];
    $result = [];
    
    foreach ($servers as $i => $server) {
        $info = queryServer($server['ip'], $server['port']);
        $info['name'] = $server['name'];
        $info['ip'] = $server['ip'];
        $info['port'] = $server['port'];
        $info['index'] = $i;
        $result[] = $info;
    }
    
    jsonResponse($result);
}

// ---- MAIN PAGE ----
require ROOT_DIR . '/src/db.php';
require ROOT_DIR . '/src/helpers.php';

$pdo = getDb($config);
$lang = loadLang($currentLang);
$__lang = $lang; // Global for t() helper

$user = $_SESSION['steam_user'] ?? null;

// Admin page
if ($path === '/admin') {
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        header('Location: /' . $currentLang);
        exit;
    }
    
    require ROOT_DIR . '/views/admin.php';
    exit;
}

// Admin servers page
if ($path === '/admin/servers') {
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        header('Location: /' . $currentLang);
        exit;
    }
    
    require ROOT_DIR . '/views/admin_servers.php';
    exit;
}

// Admin database page
if ($path === '/admin/database') {
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        header('Location: /' . $currentLang);
        exit;
    }
    
    require ROOT_DIR . '/views/admin_database.php';
    exit;
}

// Admin navigation page
if ($path === '/admin/navigation') {
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        header('Location: /' . $currentLang);
        exit;
    }
    
    require ROOT_DIR . '/views/admin_navigation.php';
    exit;
}

// Admin template page
if ($path === '/admin/template') {
    $user = $_SESSION['steam_user'] ?? null;
    $adminSteamId = $config['steamId'] ?? '';
    
    if (!$user || $user['steamid'] !== $adminSteamId) {
        header('Location: /' . $currentLang);
        exit;
    }
    
    require ROOT_DIR . '/views/admin_template.php';
    exit;
}

// Admin information page (redirects to template)
if ($path === '/admin/information') {
    header('Location: /' . $currentLang . '/admin/template');
    exit;
}

// Home page
if ($path === '/' || $path === '') {
    require ROOT_DIR . '/views/home.php';
    exit;
}

// Skins page
if ($path === '/skins') {
    $knife = null;
    $skins = [];
    $gloves = null;
    $agents = null;
    $music = null;

    if ($user) {
        $steamid = $user['steamid'];

        $stmt = $pdo->prepare("SELECT * FROM wp_player_knife WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $knife = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM wp_player_skins WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $skins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM wp_player_gloves WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $gloves = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM wp_player_agents WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $agents = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM wp_player_music WHERE steamid = :sid");
        $stmt->execute(['sid' => $steamid]);
        $music = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    require ROOT_DIR . '/views/index.php';
    exit;
}

// 404
http_response_code(404);
$errorCode    = 404;
$errorTitle   = ($currentLang === 'ru') ? 'Страница не найдена' : 'Page Not Found';
$errorMessage = ($currentLang === 'ru')
    ? 'Страница которую вы ищете не существует или была перемещена.'
    : 'The page you are looking for doesn\'t exist or has been moved.';
require ROOT_DIR . '/views/error.php';
