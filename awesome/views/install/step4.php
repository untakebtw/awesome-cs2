<?php
/**
 * Install wizard — Step 4: Database settings
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
        <?php $currentStep = 4; include __DIR__ . '/step_indicator.php'; ?>

        <h2 class="install-title"><?= e($il['databaseSettings']) ?></h2>

        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>

        <form id="installForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><?= e($il['dbHost']) ?></label>
                    <input type="text" class="form-control" name="dbHost" id="dbHost" placeholder="<?= e($il['dbHostPlaceholder']) ?>" value="localhost" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><?= e($il['dbPort']) ?></label>
                    <input type="number" class="form-control" name="dbPort" id="dbPort" placeholder="<?= e($il['dbPortPlaceholder']) ?>" value="3306" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><?= e($il['dbUser']) ?></label>
                <input type="text" class="form-control" name="dbUser" id="dbUser" placeholder="<?= e($il['dbUserPlaceholder']) ?>" value="root" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><?= e($il['dbPassword']) ?></label>
                <input type="password" class="form-control" name="dbPassword" id="dbPassword" placeholder="<?= e($il['dbPasswordPlaceholder']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label"><?= e($il['dbName']) ?></label>
                <input type="text" class="form-control" name="dbName" id="dbName" placeholder="<?= e($il['dbNamePlaceholder']) ?>" value="weaponpaints" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/<?= e($currentLang) ?>/install/step3'">
                    <i class="fas fa-arrow-left me-2"></i> <?= e($il['back']) ?>
                </button>
                <button type="button" class="btn btn-success" id="finishBtn" onclick="submitForm()">
                    <i class="fas fa-check me-2"></i> <?= e($il['finish']) ?>
                </button>
            </div>
        </form>

        <div id="loadingSpinner" class="text-center mt-3" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        const savedData = JSON.parse(localStorage.getItem('install_data') || '{}');
        if (savedData.dbHost) document.getElementById('dbHost').value = savedData.dbHost;
        if (savedData.dbPort) document.getElementById('dbPort').value = savedData.dbPort;
        if (savedData.dbUser) document.getElementById('dbUser').value = savedData.dbUser;
        if (savedData.dbName) document.getElementById('dbName').value = savedData.dbName;

        async function submitForm() {
            const dbHost = document.getElementById('dbHost').value;
            const dbPort = document.getElementById('dbPort').value;
            const dbUser = document.getElementById('dbUser').value;
            const dbPassword = document.getElementById('dbPassword').value;
            const dbName = document.getElementById('dbName').value;

            if (!dbHost || !dbPort || !dbUser || !dbName) {
                alert('<?= e($il['fieldsRequired']) ?>');
                return;
            }

            savedData.dbHost = dbHost;
            savedData.dbPort = dbPort;
            savedData.dbUser = dbUser;
            savedData.dbPassword = dbPassword;
            savedData.dbName = dbName;
            savedData.websiteLang = '<?= e($currentLang) ?>';
            localStorage.setItem('install_data', JSON.stringify(savedData));

            document.getElementById('finishBtn').style.display = 'none';
            document.getElementById('loadingSpinner').style.display = 'block';

            try {
                const response = await fetch('/install/save-config', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(savedData)
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.removeItem('install_data');
                    localStorage.removeItem('install_lang');
                    window.location.href = '/<?= e($currentLang) ?>/install/step5';
                } else {
                    alert(data.message || 'Error saving configuration');
                    document.getElementById('finishBtn').style.display = 'block';
                    document.getElementById('loadingSpinner').style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('<?= $currentLang === 'ru' ? 'Ошибка сохранения конфигурации' : 'Error saving configuration' ?>');
                document.getElementById('finishBtn').style.display = 'block';
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }
    </script>
</body>
</html>
