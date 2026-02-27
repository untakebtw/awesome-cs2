<?php
/**
 * Install wizard — Step 2: Project settings
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
    <div class="install-container">
        <?php $currentStep = 2; include __DIR__ . '/step_indicator.php'; ?>

        <h2 class="install-title"><?= e($il['projectSettings']) ?></h2>

        <form id="installForm">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-heading me-2"></i><?= $currentLang === 'ru' ? 'Название в заголовке страницы (title)' : 'Page Title (browser tab)' ?></label>
                <input type="text" class="form-control" id="pageTitle" placeholder="<?= $currentLang === 'ru' ? 'CS2 Skin Changer' : 'CS2 Skin Changer' ?>" required>
                <small class="text-muted"><?= $currentLang === 'ru' ? 'Отображается во вкладке браузера' : 'Shown in the browser tab' ?></small>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-search me-2"></i><?= $currentLang === 'ru' ? 'Описание для поисковиков (meta description)' : 'Search Engine Description (meta)' ?></label>
                <textarea class="form-control" id="metaDescription" rows="2" placeholder="<?= $currentLang === 'ru' ? 'Смена скинов для CS2 сервера' : 'CS2 weapon skins changer website' ?>"></textarea>
                <small class="text-muted"><?= $currentLang === 'ru' ? 'Описание сайта в результатах поиска Google/Yandex' : 'Description shown in Google/Yandex search results' ?></small>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-bars me-2"></i><?= $currentLang === 'ru' ? 'Название в навбаре' : 'Navbar Brand Name' ?></label>
                <input type="text" class="form-control" id="navbarName" placeholder="<?= $currentLang === 'ru' ? 'Мой Skin Changer' : 'My Skin Changer' ?>" required>
                <small class="text-muted"><?= $currentLang === 'ru' ? 'Отображается в верхней панели навигации' : 'Shown in the top navigation bar' ?></small>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/<?= e($currentLang) ?>/install'">
                    <i class="fas fa-arrow-left me-2"></i> <?= e($il['back']) ?>
                </button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <?= e($il['next']) ?> <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        const savedData = JSON.parse(localStorage.getItem('install_data') || '{}');
        if (savedData.pageTitle) document.getElementById('pageTitle').value = savedData.pageTitle;
        if (savedData.metaDescription) document.getElementById('metaDescription').value = savedData.metaDescription;
        if (savedData.navbarName) document.getElementById('navbarName').value = savedData.navbarName;

        function submitForm() {
            const pageTitle = document.getElementById('pageTitle').value;
            const metaDescription = document.getElementById('metaDescription').value;
            const navbarName = document.getElementById('navbarName').value;

            if (!pageTitle || !navbarName) {
                alert('<?= e($il['fieldsRequired']) ?>');
                return;
            }

            const data = Object.assign({}, savedData, {
                pageTitle: pageTitle,
                metaDescription: metaDescription,
                navbarName: navbarName,
                projectName: navbarName
            });
            localStorage.setItem('install_data', JSON.stringify(data));
            window.location.href = '/<?= e($currentLang) ?>/install/step3';
        }
    </script>
</body>
</html>
