<?php
/**
 * Helper functions
 * 
 * Author: untakebtw
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

/**
 * Safely escape HTML output
 */
function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Output JSON and exit
 */
function jsonResponse(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get POST JSON body
 */
function getJsonBody(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Load translation file for given language
 * Returns associative array of translations
 */
function loadLang(string $langCode): array
{
    static $cache = [];
    if (isset($cache[$langCode])) {
        return $cache[$langCode];
    }
    $file = ROOT_DIR . '/src/lang/' . $langCode . '.json';
    if (!file_exists($file)) {
        $file = ROOT_DIR . '/src/lang/en.json';
    }
    $data = json_decode(file_get_contents($file), true);
    $cache[$langCode] = is_array($data) ? $data : [];
    return $cache[$langCode];
}

/**
 * Get translation by dot-notation key
 * Example: t('admin.settings') returns the translation for admin->settings
 */
function t(string $key, array $lang = null)
{
    global $__lang;
    $translations = $lang ?? $__lang ?? [];
    
    $keys = explode('.', $key);
    $value = $translations;
    foreach ($keys as $k) {
        if (!is_array($value) || !isset($value[$k])) {
            return $key; // Return key itself as fallback
        }
        $value = $value[$k];
    }
    return $value;
}
