<?php
/**
 * Install wizard — Step 1: Language selection
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
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/install.css">
</head>
<body class="install-body">
    <div class="install-container">
        <?php $currentStep = 1; include __DIR__ . '/step_indicator.php'; ?>

        <h2 class="install-title"><?= e($il['selectLanguage']) ?></h2>
        <p class="install-desc"><?= e($il['languageDesc']) ?></p>

        <form id="installForm">
            <div class="language-grid">
                <button type="button" class="language-btn" onclick="selectLang('en')" id="lang-en">
                    <i class="fas fa-globe fa-2x mb-2"></i>
                    <span>English</span>
                </button>
                <button type="button" class="language-btn" onclick="selectLang('ru')" id="lang-ru">
                    <i class="fas fa-globe fa-2x mb-2"></i>
                    <span>Русский</span>
                </button>
            </div>
            <input type="hidden" name="lang" id="selectedLang" value="">

            <div class="d-flex justify-content-between mt-4">
                <div></div>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <?= e($il['next']) ?> <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        let selectedLanguage = '';

        function selectLang(lang) {
            selectedLanguage = lang;
            document.getElementById('lang-en').classList.remove('selected');
            document.getElementById('lang-ru').classList.remove('selected');
            document.getElementById('lang-' + lang).classList.add('selected');
            document.getElementById('selectedLang').value = lang;
            localStorage.setItem('install_lang', lang);
        }

        const savedLang = localStorage.getItem('install_lang');
        if (savedLang) {
            selectLang(savedLang);
        }

        function submitForm() {
            if (!selectedLanguage) {
                alert('Please select a language');
                return;
            }
            window.location.href = '/' + selectedLanguage + '/install/step2';
        }
    </script>
</body>
</html>
