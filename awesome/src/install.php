<?php
/**
 * Install wizard router (PHP 7.4+)
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

require_once ROOT_DIR . '/src/helpers.php';

// Installer language strings
$installerLangs = [
    'en' => [
        'title' => 'Installation Wizard',
        'step' => 'Step',
        'next' => 'Next',
        'back' => 'Back',
        'finish' => 'Finish',
        'selectLanguage' => 'Select Language',
        'languageDesc' => 'Choose your preferred language for the installer',
        'projectSettings' => 'Project Settings',
        'projectName' => 'Project Name',
        'projectNamePlaceholder' => 'My Skin Changer',
        'projectNameDesc' => 'The name of your website',
        'lang' => 'Website Language',
        'langDesc' => 'Default language for your website',
        'host' => 'Host / Domain',
        'hostPlaceholder' => 'localhost or yourdomain.com',
        'hostDesc' => 'Your website hostname (without http://)',
        'protocol' => 'Protocol',
        'protocolDesc' => 'Use HTTPS for production',
        'port' => 'Port',
        'portDesc' => 'Port for the server to run on',
        'steamSettings' => 'Steam Settings',
        'steamApiKey' => 'Steam Web API Key',
        'steamApiKeyPlaceholder' => 'Your Steam Web API Key',
        'steamApiKeyDesc' => 'Get it from <a href="https://steamcommunity.com/dev/apikey" target="_blank">steamcommunity.com/dev/apikey</a>',
        'steamId' => 'Your Steam ID',
        'steamIdPlaceholder' => 'STEAM_1:0:12345678',
        'steamIdDesc' => 'Enter your Steam ID to verify ownership',
        'verifySteam' => 'Verify Steam ID',
        'verifying' => 'Verifying...',
        'verified' => 'Verified! This is your account',
        'notVerified' => "This doesn't appear to be your account",
        'databaseSettings' => 'Database Settings',
        'dbHost' => 'Database Host',
        'dbHostPlaceholder' => 'localhost',
        'dbUser' => 'Database Username',
        'dbUserPlaceholder' => 'root',
        'dbPassword' => 'Database Password',
        'dbPasswordPlaceholder' => 'Your database password',
        'dbName' => 'Database Name',
        'dbNamePlaceholder' => 'weaponpaints',
        'dbPort' => 'Database Port',
        'dbPortPlaceholder' => '3306',
        'sessionSecret' => 'Session Secret',
        'sessionSecretDesc' => 'Random string for session security (min 32 chars)',
        'sessionSecretPlaceholder' => 'Generate or enter a random string',
        'generateSecret' => 'Generate Random',
        'serverSettings' => 'Server Settings (Optional)',
        'showConnect' => 'Show Connect Button',
        'serverIp' => 'CS2 Server IP',
        'serverIpPlaceholder' => '192.168.1.100',
        'serverPort' => 'CS2 Server Port',
        'serverPortPlaceholder' => '27015',
        'serverPassword' => 'Server Password (RCON)',
        'serverPasswordPlaceholder' => 'Your server RCON password',
        'fieldsRequired' => 'Please fill in all required fields',
        'connectionFailed' => 'Failed to connect to database',
        'invalidSteamId' => 'Invalid Steam ID format',
    ],
    'ru' => [
        'title' => 'Мастер установки',
        'step' => 'Шаг',
        'next' => 'Далее',
        'back' => 'Назад',
        'finish' => 'Завершить',
        'selectLanguage' => 'Выберите язык',
        'languageDesc' => 'Выберите предпочитаемый язык для установщика',
        'projectSettings' => 'Настройки проекта',
        'projectName' => 'Название проекта',
        'projectNamePlaceholder' => 'Мой Skin Changer',
        'projectNameDesc' => 'Название вашего сайта',
        'lang' => 'Язык сайта',
        'langDesc' => 'Язык по умолчанию для вашего сайта',
        'host' => 'Хост / Домен',
        'hostPlaceholder' => 'localhost или ваш-домен.рф',
        'hostDesc' => 'Хостнейм вашего сайта (без http://)',
        'protocol' => 'Протокол',
        'protocolDesc' => 'Используйте HTTPS для продакшена',
        'port' => 'Порт',
        'portDesc' => 'Порт для запуска сервера',
        'steamSettings' => 'Настройки Steam',
        'steamApiKey' => 'Steam Web API ключ',
        'steamApiKeyPlaceholder' => 'Ваш Steam Web API ключ',
        'steamApiKeyDesc' => 'Получите на <a href="https://steamcommunity.com/dev/apikey" target="_blank">steamcommunity.com/dev/apikey</a>',
        'steamId' => 'Ваш Steam ID',
        'steamIdPlaceholder' => 'STEAM_1:0:12345678',
        'steamIdDesc' => 'Введите ваш Steam ID для подтверждения владения',
        'verifySteam' => 'Подтвердить Steam ID',
        'verifying' => 'Проверка...',
        'verified' => 'Подтверждено! Это ваш аккаунт',
        'notVerified' => 'Это похоже не ваш аккаунт',
        'databaseSettings' => 'Настройки базы данных',
        'dbHost' => 'Хост базы данных',
        'dbHostPlaceholder' => 'localhost',
        'dbUser' => 'Имя пользователя БД',
        'dbUserPlaceholder' => 'root',
        'dbPassword' => 'Пароль базы данных',
        'dbPasswordPlaceholder' => 'Ваш пароль от базы данных',
        'dbName' => 'Имя базы данных',
        'dbNamePlaceholder' => 'weaponpaints',
        'dbPort' => 'Порт базы данных',
        'dbPortPlaceholder' => '3306',
        'sessionSecret' => 'Секрет сессии',
        'sessionSecretDesc' => 'Случайная строка для безопасности сессий (мин 32 символа)',
        'sessionSecretPlaceholder' => 'Сгенерируйте или введите случайную строку',
        'generateSecret' => 'Сгенерировать',
        'serverSettings' => 'Настройки сервера (Необязательно)',
        'showConnect' => 'Показать кнопку подключения',
        'serverIp' => 'IP CS2 сервера',
        'serverIpPlaceholder' => '192.168.1.100',
        'serverPort' => 'Порт CS2 сервера',
        'serverPortPlaceholder' => '27015',
        'serverPassword' => 'Пароль сервера (RCON)',
        'serverPasswordPlaceholder' => 'Ваш RCON пароль',
        'fieldsRequired' => 'Пожалуйста, заполните все обязательные поля',
        'connectionFailed' => 'Не удалось подключиться к базе данных',
        'invalidSteamId' => 'Неверный формат Steam ID',
    ],
];

$currentLang = $currentLang ?? 'en';
$il = $installerLangs[$currentLang] ?? $installerLangs['en'];

// Determine install sub-path
$installPath = str_replace('/install', '', $path);
$installPath = $installPath ?: '/';

// Handle Steam ID verification via server-side proxy
if ($installPath === '/verify-steam' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $apiKey = $_GET['apikey'] ?? '';
    $steamId = $_GET['steamid'] ?? '';

    if (!$apiKey || !$steamId || !preg_match('/^7656\d{13}$/', $steamId)) {
        jsonResponse(['success' => false, 'error' => 'Invalid parameters']);
    }

    $url = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . urlencode($apiKey) . '&steamids=' . urlencode($steamId);

    $ctx = stream_context_create(['http' => ['timeout' => 10, 'ignore_errors' => true]]);
    $json = @file_get_contents($url, false, $ctx);

    if ($json === false) {
        // Try with cURL as fallback
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $json = curl_exec($ch);
            curl_close($ch);
        }
    }

    if (!$json) {
        jsonResponse(['success' => false, 'error' => $currentLang === 'ru' ? 'Не удалось связаться с Steam API' : 'Failed to contact Steam API']);
    }

    $data = json_decode($json, true);
    $players = $data['response']['players'] ?? [];

    if (!empty($players)) {
        jsonResponse(['success' => true, 'player' => $players[0]]);
    } else {
        jsonResponse(['success' => false, 'error' => $currentLang === 'ru' ? 'Игрок не найден' : 'Player not found']);
    }
}

// Handle save-config POST
if ($installPath === '/save-config' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = getJsonBody();

    if (
        empty($data['navbarName']) && empty($data['projectName']) ||
        empty($data['steamApiKey']) || empty($data['steamId']) ||
        empty($data['dbHost']) || empty($data['dbUser']) || empty($data['dbName']) ||
        empty($data['dbPort'])
    ) {
        jsonResponse(['success' => false, 'message' => 'Missing required fields'], 400);
    }

    $configData = [
        'name' => $data['navbarName'] ?? $data['projectName'],
        'pageTitle' => $data['pageTitle'] ?? $data['projectName'],
        'metaDescription' => $data['metaDescription'] ?? '',
        'lang' => $data['websiteLang'] ?? 'en',
        'DB' => [
            'host'     => $data['dbHost'],
            'user'     => $data['dbUser'],
            'password' => $data['dbPassword'] ?? '',
            'database' => $data['dbName'],
            'port'     => (int)$data['dbPort'],
        ],
        'HOST'           => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'PROTOCOL'       => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http',
        'PORT'           => (int)($_SERVER['SERVER_PORT'] ?? 80),
        'INTERNAL_HOST'  => '0.0.0.0',
        'STEAMAPIKEY'    => $data['steamApiKey'],
        'steamId'        => $data['steamId'],
        'LOG_LEVEL'      => 'INFO',
    ];

    $configPath = ROOT_DIR . '/config.json';
    $result = file_put_contents($configPath, json_encode($configData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    if ($result === false) {
        jsonResponse(['success' => false, 'message' => 'Failed to write config file'], 500);
    }

    jsonResponse(['success' => true]);
}

// Render install step pages
$step = 1;
if ($installPath === '/step2') {
    $step = 2;
} elseif ($installPath === '/step3') {
    $step = 3;
} elseif ($installPath === '/step4') {
    $step = 4;
} elseif ($installPath === '/step5') {
    $step = 5;
}

require ROOT_DIR . '/views/install/step' . $step . '.php';
