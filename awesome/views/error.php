<?php
/**
 * Error page view (404, 403, 500, etc.)
 * PHP 7.4+
 *
 * Author: untake
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */

$errorCode    = $errorCode    ?? 404;
$errorTitle   = $errorTitle   ?? 'Page Not Found';
$errorMessage = $errorMessage ?? 'The page you are looking for doesn\'t exist or has been moved.';
$currentLang  = $currentLang  ?? 'en';
$config       = $config       ?? [];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($currentLang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/awesome/awesome_logo.png">
    <title><?= $errorCode ?> — <?= htmlspecialchars($config['name'] ?? 'CS2 WeaponPaints') ?></title>
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <style>
        /* ===== ERROR PAGE ===== */
        html, body { height: 100%; }

        .error-root {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        .error-root::before,
        .error-root::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.12;
            pointer-events: none;
            animation: drift 8s ease-in-out infinite alternate;
        }
        .error-root::before {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #0A84FF, transparent 70%);
            top: -200px; left: -200px;
        }
        .error-root::after {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #5E5CE6, transparent 70%);
            bottom: -150px; right: -150px;
            animation-delay: -4s;
        }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, 30px) scale(1.08); }
        }

        /* Glass card */
        .error-card {
            background: rgba(28, 28, 30, 0.6);
            backdrop-filter: blur(40px) saturate(1.8);
            -webkit-backdrop-filter: blur(40px) saturate(1.8);
            border: 0.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
            padding: 4rem 5rem;
            text-align: center;
            max-width: 580px;
            width: 100%;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
            animation: cardIn 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        /* Big code number */
        .error-code {
            font-size: 9rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -8px;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255,255,255,0.3) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            position: relative;
        }
        .error-code::after {
            content: attr(data-code);
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0A84FF 0%, #5E5CE6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: blur(30px);
            opacity: 0.4;
            z-index: -1;
        }

        /* Glowing dot separator */
        .error-dot {
            width: 6px; height: 6px;
            background: #0A84FF;
            border-radius: 50%;
            margin: 1.5rem auto;
            box-shadow: 0 0 20px #0A84FF, 0 0 40px rgba(10, 132, 255, 0.4);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); box-shadow: 0 0 20px #0A84FF, 0 0 40px rgba(10,132,255,0.4); }
            50%       { transform: scale(1.4); box-shadow: 0 0 30px #0A84FF, 0 0 60px rgba(10,132,255,0.6); }
        }

        .error-title {
            color: #ffffff;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.8px;
            margin-bottom: 0.75rem;
        }

        .error-message {
            color: rgba(255, 255, 255, 0.5);
            font-size: 1rem;
            font-weight: 500;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        /* Buttons */
        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-error-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #0A84FF;
            color: white;
            border: none;
            border-radius: 14px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            box-shadow: 0 8px 24px rgba(10, 132, 255, 0.35);
        }
        .btn-error-home:hover {
            background: #0077ED;
            transform: translateY(-3px);
            box-shadow: 0 14px 32px rgba(10, 132, 255, 0.5);
            color: white;
        }

        .btn-error-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(120, 120, 128, 0.16);
            color: rgba(255, 255, 255, 0.7);
            border: 0.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-error-back:hover {
            background: rgba(120, 120, 128, 0.28);
            color: white;
            transform: translateY(-3px);
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }
        .particle {
            position: absolute;
            width: 2px; height: 2px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            animation: float-up linear infinite;
        }
        @keyframes float-up {
            from { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            to   { transform: translateY(-10vh)  scale(1.5); opacity: 0; }
        }

        @media (max-width: 576px) {
            .error-card   { padding: 3rem 2rem; }
            .error-code   { font-size: 6rem; letter-spacing: -4px; }
            .error-title  { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

<div class="particles" id="particles"></div>

<div class="error-root">
    <div class="error-card">
        <div class="error-code" data-code="<?= $errorCode ?>"><?= $errorCode ?></div>
        <div class="error-dot"></div>
        <h1 class="error-title"><?= htmlspecialchars($errorTitle) ?></h1>
        <p class="error-message"><?= htmlspecialchars($errorMessage) ?></p>
        <div class="error-actions">
            <a href="/<?= htmlspecialchars($currentLang) ?>/" class="btn-error-home">
                <i class="fas fa-home"></i>
                <?= $currentLang === 'ru' ? 'На главную' : 'Go Home' ?>
            </a>
            <a href="javascript:history.back()" class="btn-error-back">
                <i class="fas fa-arrow-left"></i>
                <?= $currentLang === 'ru' ? 'Назад' : 'Go Back' ?>
            </a>
        </div>
    </div>
</div>

<script>
    // Generate floating particles
    const container = document.getElementById('particles');
    for (let i = 0; i < 30; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left  = Math.random() * 100 + 'vw';
        p.style.width = p.style.height = (Math.random() * 3 + 1) + 'px';
        p.style.animationDuration = (Math.random() * 15 + 10) + 's';
        p.style.animationDelay    = (Math.random() * 15) + 's';
        p.style.opacity = Math.random() * 0.5 + 0.1;
        container.appendChild(p);
    }
</script>

</body>
</html>
