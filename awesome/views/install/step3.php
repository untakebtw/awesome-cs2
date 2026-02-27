<?php
/**
 * Install wizard — Step 3: Steam settings
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
        <?php $currentStep = 3; include __DIR__ . '/step_indicator.php'; ?>

        <h2 class="install-title"><?= e($il['steamSettings']) ?></h2>

        <form id="installForm">
            <div class="mb-3">
                <label class="form-label"><?= e($il['steamApiKey']) ?></label>
                <input type="text" class="form-control" name="steamApiKey" id="steamApiKey" placeholder="<?= e($il['steamApiKeyPlaceholder']) ?>">
                <small class="text-muted"><?= $il['steamApiKeyDesc'] ?></small>
            </div>

            <div class="mb-3">
                <label class="form-label">SteamID64</label>
                <input type="text" class="form-control" name="steamId" id="steamIdInput" placeholder="76561198012345678">
                <small class="text-muted"><?= $currentLang === 'ru' ? 'Ваш SteamID64 (17 цифр). Найти можно на <a href="https://steamid.io" target="_blank">steamid.io</a>' : 'Your SteamID64 (17 digits). Find it at <a href="https://steamid.io" target="_blank">steamid.io</a>' ?></small>
            </div>

            <button type="button" class="btn btn-warning w-100 mb-3" onclick="verifySteam()">
                <i class="fab fa-steam me-2"></i> <?= e($il['verifySteam']) ?>
            </button>

            <div id="verificationResult" class="verification-result">
                <img id="playerAvatar" class="verified-avatar" src="" style="display: none;">
                <p id="playerNickname" class="text-center text-light mb-1" style="display: none;"></p>
                <p id="verificationText" class="text-center mb-0"></p>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/<?= e($currentLang) ?>/install/step2'">
                    <i class="fas fa-arrow-left me-2"></i> <?= e($il['back']) ?>
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="submitForm()" disabled>
                    <?= e($il['next']) ?> <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        let isVerified = false;

        const savedData = JSON.parse(localStorage.getItem('install_data') || '{}');
        if (savedData.steamApiKey) document.getElementById('steamApiKey').value = savedData.steamApiKey;
        if (savedData.steamId) document.getElementById('steamIdInput').value = savedData.steamId;

        async function verifySteam() {
            const steamId64 = document.getElementById('steamIdInput').value.trim();
            const steamApiKey = document.getElementById('steamApiKey').value.trim();

            if (!steamId64 || !steamApiKey) {
                alert('<?= e($il['fieldsRequired']) ?>');
                return;
            }

            // Validate SteamID64 format (17 digits starting with 7656)
            if (!/^7656\d{13}$/.test(steamId64)) {
                const resultDiv = document.getElementById('verificationResult');
                resultDiv.style.display = 'block';
                resultDiv.className = 'verification-result verification-failed';
                document.getElementById('verificationText').textContent = '<?= $currentLang === 'ru' ? 'Неверный формат SteamID64. Должен быть 17 цифр, начинающихся с 7656' : 'Invalid SteamID64 format. Must be 17 digits starting with 7656' ?>';
                return;
            }

            const btn = document.querySelector('.btn-warning');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> <?= e($il['verifying']) ?>';

            try {
                // Use our own PHP proxy to avoid CORS issues with Steam API
                const response = await fetch('/install/verify-steam?apikey=' + encodeURIComponent(steamApiKey) + '&steamid=' + encodeURIComponent(steamId64));
                const data = await response.json();

                const resultDiv = document.getElementById('verificationResult');
                const avatarImg = document.getElementById('playerAvatar');
                const nicknameP = document.getElementById('playerNickname');
                const textP = document.getElementById('verificationText');

                resultDiv.style.display = 'block';

                if (data.success && data.player) {
                    resultDiv.className = 'verification-result verification-success';
                    avatarImg.src = data.player.avatarfull;
                    avatarImg.style.display = 'block';
                    nicknameP.innerHTML = data.player.personaname + '<br><small>(' + steamId64 + ')</small>';
                    nicknameP.style.display = 'block';
                    textP.textContent = '<?= e($il['verified']) ?>';
                    isVerified = true;
                    document.getElementById('nextBtn').disabled = false;

                    savedData.steamId = steamId64;
                    savedData.steamApiKey = steamApiKey;
                    savedData.steamAvatar = data.player.avatarfull;
                    savedData.steamNickname = data.player.personaname;
                    localStorage.setItem('install_data', JSON.stringify(savedData));
                } else {
                    resultDiv.className = 'verification-result verification-failed';
                    avatarImg.style.display = 'none';
                    nicknameP.style.display = 'none';
                    textP.textContent = data.error || '<?= e($il['notVerified']) ?>';
                    isVerified = false;
                    document.getElementById('nextBtn').disabled = true;
                }
            } catch (error) {
                console.error('Error:', error);
                const resultDiv = document.getElementById('verificationResult');
                resultDiv.style.display = 'block';
                resultDiv.className = 'verification-result verification-failed';
                document.getElementById('verificationText').textContent = '<?= $currentLang === 'ru' ? 'Ошибка соединения' : 'Connection error' ?>';
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fab fa-steam me-2"></i> <?= e($il['verifySteam']) ?>';
            }
        }

        function submitForm() {
            if (!isVerified) {
                alert('<?= $currentLang === 'ru' ? 'Сначала подтвердите Steam ID' : 'Please verify your Steam ID first' ?>');
                return;
            }
            window.location.href = '/<?= e($currentLang) ?>/install/step4';
        }
    </script>
</body>
</html>
