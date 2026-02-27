<?php
/**
 * Install wizard — Step 5: Installation complete
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
    <title><?= e($il['title']) ?></title>
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/install.css">
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
</head>
<body class="install-body">
    <div class="install-container text-center">
        <?php $currentStep = 5; include __DIR__ . '/step_indicator.php'; ?>

        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
        </div>

        <h1 class="install-title" style="font-size: 2.5rem;">Awesome!</h1>

        <?php if ($currentLang === 'ru'): ?>
            <p class="text-light fs-5 mb-4">
                Установка завершена! Ваш сайт готов к использованию.
            </p>
            <p class="text-muted mb-4">
                Если вам понравился этот проект, вы можете поддержать его развитие. 
                Каждый вклад помогает нам делать проект лучше!
            </p>
        <?php else: ?>
            <p class="text-light fs-5 mb-4">
                Installation complete! Your website is ready to use.
            </p>
            <p class="text-muted mb-4">
                If you enjoy this project, consider supporting its development. 
                Every contribution helps us make it better!
            </p>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
            <a href="https://github.com/untakebtw" target="_blank" class="btn btn-outline-light btn-lg">
                <i class="fab fa-github me-2"></i> GitHub
            </a>
            <a href="https://discord.gg/VtKfRgjC" target="_blank" class="btn btn-outline-primary btn-lg" style="border-color: #5865F2; color: #5865F2;">
                <i class="fab fa-discord me-2"></i> Discord
            </a>
            <a href="https://untakebtw.github.io" target="_blank" class="btn btn-outline-warning btn-lg">
                <i class="fas fa-heart me-2"></i> <?= $currentLang === 'ru' ? 'Поддержать' : 'Donate' ?>
            </a>
        </div>

        <hr class="border-secondary my-4">

        <a href="/<?= e($currentLang) ?>/" class="btn btn-success btn-lg">
            <i class="fas fa-arrow-right me-2"></i> <?= $currentLang === 'ru' ? 'Перейти на сайт' : 'Go to Website' ?>
        </a>
    </div>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
