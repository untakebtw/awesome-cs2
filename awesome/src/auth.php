<?php
/**
 * Steam OpenID Authentication (PHP 7.4+)
 * Lightweight implementation without external libraries
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

function getSteamLoginUrl(array $config): string
{
    // Auto-detect host, protocol and port from the current request
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $protocol = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';

    $baseUrl = "{$protocol}://{$host}";
    $returnUrl = "{$baseUrl}/api/auth/steam/return";
    $realm = "{$baseUrl}/";

    $params = [
        'openid.ns'         => 'http://specs.openid.net/auth/2.0',
        'openid.mode'       => 'checkid_setup',
        'openid.return_to'  => $returnUrl,
        'openid.realm'      => $realm,
        'openid.identity'   => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
    ];

    return 'https://steamcommunity.com/openid/login?' . http_build_query($params);
}

function steamLogin(array $config): void
{
    $url = getSteamLoginUrl($config);
    header('Location: ' . $url);
    exit;
}

function steamReturn(array $config): void
{
    // Verify the OpenID response
    if (!isset($_GET['openid_claimed_id'])) {
        header('Location: /');
        exit;
    }

    // Validate with Steam
    $params = [
        'openid.assoc_handle' => $_GET['openid_assoc_handle'] ?? '',
        'openid.signed'       => $_GET['openid_signed'] ?? '',
        'openid.sig'          => $_GET['openid_sig'] ?? '',
        'openid.ns'           => 'http://specs.openid.net/auth/2.0',
        'openid.mode'         => 'check_authentication',
    ];

    $signed = explode(',', $_GET['openid_signed'] ?? '');
    foreach ($signed as $item) {
        $key = 'openid_' . str_replace('.', '_', $item);
        if (isset($_GET[$key])) {
            $params['openid.' . $item] = $_GET[$key];
        }
    }

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($params),
        ],
    ]);

    $response = @file_get_contents('https://steamcommunity.com/openid/login', false, $ctx);

    if ($response === false || strpos($response, 'is_valid:true') === false) {
        header('Location: /');
        exit;
    }

    // Extract Steam ID from claimed_id
    $claimedId = $_GET['openid_claimed_id'] ?? '';
    preg_match('/\/id\/(\d+)$/', $claimedId, $matches);
    if (empty($matches)) {
        preg_match('/\/(\d+)$/', $claimedId, $matches);
    }

    if (empty($matches[1])) {
        header('Location: /');
        exit;
    }

    $steamId64 = $matches[1];

    // Fetch player info from Steam API
    $apiKey = $config['STEAMAPIKEY'] ?? '';
    $apiUrl = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$apiKey}&steamids={$steamId64}";
    $playerData = @file_get_contents($apiUrl);

    if ($playerData === false) {
        header('Location: /');
        exit;
    }

    $playerJson = json_decode($playerData, true);
    $players = $playerJson['response']['players'] ?? [];

    if (empty($players)) {
        header('Location: /');
        exit;
    }

    $player = $players[0];

    // Store user in session
    $_SESSION['steam_user'] = [
        'steamid'     => $steamId64,
        'displayName' => $player['personaname'] ?? 'Unknown',
        'avatar'      => $player['avatar'] ?? '',
        'avatarmedium'=> $player['avatarmedium'] ?? '',
        'avatarfull'  => $player['avatarfull'] ?? '',
        'profileurl'  => $player['profileurl'] ?? '',
        'id'          => $steamId64,
        'photos'      => [
            ['value' => $player['avatar'] ?? ''],
            ['value' => $player['avatarmedium'] ?? ''],
            ['value' => $player['avatarfull'] ?? ''],
        ],
    ];

    header('Location: /');
    exit;
}
