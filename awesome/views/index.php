<?php
/**
 * Main index view
 * PHP 7.4+
 * 
 * Author: untakebtw
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
    <title><?= e($config['pageTitle'] ?? $config['name']) ?></title>
    <meta name="description" content="<?= e($config['metaDescription'] ?? '') ?>">
    <link rel="stylesheet" href="/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles/default.css">
    <link rel="stylesheet" href="/css/styles/skins.css">
</head>
<body class="bg-color d-flex flex-column min-vh-100">

    <?php require ROOT_DIR . '/views/partials/navbar.php'; ?>
    </div>

    <div class="main-content flex-grow-1">
        <?php if (!$user): ?>
            <div class="flex-grow-1 d-flex align-items-center justify-content-center" style="min-height: 50vh;">
                <h5 class="text-center text-white"><?= e($lang['needLogin']) ?> <a class="text-warning" href="/api/auth/steam"><?= e($lang['viaSteam']) ?></a></h5>
            </div>
        <?php else: ?>


    <div class="container text-light mt-3">
        
        <!-- Mobile: Bottom Navigation (ChatGPT Style) -->
        <nav class="mobile-bottom-nav d-lg-none">
            <button onclick="showKnives()" id="sideBtnKnives" class="mobile-nav-item">
                <img src="/icons/weapon_knife_karambit.svg" width="24">
                <span><?= e($lang['sideMenu']['knives']) ?></span>
            </button>
            <button onclick="showGloves()" id="sideBtnGloves" class="mobile-nav-item">
                <i class="fa-solid fa-hand"></i>
                <span><?= e($lang['sideMenu']['gloves']) ?></span>
            </button>
            <button onclick="showRifles()" id="sideBtnRifles" class="mobile-nav-item">
                <img src="/icons/weapon_ak47.svg" width="24">
                <span><?= e($lang['sideMenu']['rifles']) ?></span>
            </button>
            <button onclick="showPistols()" id="sideBtnPistols" class="mobile-nav-item">
                <img src="/icons/weapon_deagle.svg" width="24">
                <span><?= e($lang['sideMenu']['pistols']) ?></span>
            </button>
            <button onclick="showSmgs()" id="sideBtnSmgs" class="mobile-nav-item">
                <img src="/icons/weapon_mac10.svg" width="24">
                <span><?= e($lang['sideMenu']['SMGs']) ?></span>
            </button>
            <button onclick="showShotguns()" id="sideBtnShotguns" class="mobile-nav-item">
                <img src="/icons/weapon_negev.svg" width="24">
                <span><?= e($lang['sideMenu']['heavy']) ?></span>
            </button>
        </nav>

            <button onclick="showCTAgents()" id="sideBtnCTAgents" class="btn btn-primary d-flex my-1 mx-1 align-items-center category-tab d-lg-none">
                <i class="fa-solid fa-person-rifle"></i>
                <span><small><?= e($lang['sideMenu']['ctAgents']) ?></small></span>
            </button>
            <button onclick="showTAgents()" id="sideBtnTAgents" class="btn btn-primary d-flex my-1 mx-1 align-items-center category-tab d-lg-none">
                <i class="fa-solid fa-person-rifle"></i>
                <span><small><?= e($lang['sideMenu']['tAgents']) ?></small></span>
            </button>
            <button onclick="showMusic()" id="sideBtnMusic" class="btn btn-primary d-flex my-1 mx-1 align-items-center category-tab d-lg-none">
                <i class="fa-solid fa-music"></i>
                <span><small><?= e($lang['sideMenu']['musicKits'] ?? 'Music kits') ?></small></span>
            </button>
        </div>
        <!-- Desktop: Sidebar -->
    <div class="content-wrapper py-4">
        <div class="row">
            <div class="col-auto p-0 pe-2 d-none d-lg-block">
                <div class="weapons-menu-container" id="weaponsMenu">
                    <button id="btnBackSidebar" onclick="goBackCategory()" class="btn-back-sidebar mb-3" style="display: none;">
                        <i class="fas fa-chevron-left"></i>
                        <span>Back</span>
                    </button>
                    <div class="weapons-menu-title">Weapons</div>
                    <button onclick="showKnives()" id="sideBtnKnives" class="sideBtn">
                        <img src="/icons/weapon_knife_karambit.svg" class="sideBtnImg">
                        <span><?= e($lang['sideMenu']['knives']) ?></span>
                    </button>
                    <button onclick="showGloves()" id="sideBtnGloves" class="sideBtn">
                        <i class="fa-solid fa-mitten sideBtnI"></i>
                        <span><?= e($lang['sideMenu']['gloves']) ?></span>
                    </button>
                    <button onclick="showRifles()" id="sideBtnRifles" class="sideBtn">
                        <img src="/icons/weapon_ak47.svg" class="sideBtnImg">
                        <span><?= e($lang['sideMenu']['rifles']) ?></span>
                    </button>
                    <button onclick="showPistols()" id="sideBtnPistols" class="sideBtn">
                        <img src="/icons/weapon_deagle.svg" class="sideBtnImg">
                        <span><?= e($lang['sideMenu']['pistols']) ?></span>
                    </button>
                    <button onclick="showSmgs()" id="sideBtnSmgs" class="sideBtn">
                        <img src="/icons/weapon_mac10.svg" class="sideBtnImg">
                        <span><?= e($lang['sideMenu']['SMGs']) ?></span>
                    </button>
                    <button onclick="showShotguns()" id="sideBtnShotguns" class="sideBtn">
                        <img src="/icons/weapon_negev.svg" class="sideBtnImg">
                        <span><?= e($lang['sideMenu']['heavy']) ?></span>
                    </button>
                    <div class="menu-divider"></div>
                    <div class="weapons-menu-title">Agents</div>
                    <button onclick="showCTAgents()" id="sideBtnCTAgents" class="sideBtn">
                        <i class="fa-solid fa-user-shield sideBtnI"></i>
                        <span><?= e($lang['sideMenu']['ctAgents']) ?></span>
                    </button>
                    <button onclick="showTAgents()" id="sideBtnTAgents" class="sideBtn">
                        <i class="fa-solid fa-user-secret sideBtnI"></i>
                        <span><?= e($lang['sideMenu']['tAgents']) ?></span>
                    </button>
                    <div class="menu-divider"></div>
                    <button onclick="showMusic()" id="sideBtnMusic" class="sideBtn">
                        <i class="fa-solid fa-music sideBtnI"></i>
                        <span><?= e($lang['sideMenu']['musicKits'] ?? 'Music kits') ?></span>
                    </button>
                </div>
            </div>
            <div class="col mb-5">
                <div class="container-fluid px-4">
                    <div id="mobileBackContainer" class="d-lg-none w-100 mb-3" style="display: none;">
                        <button onclick="goBackCategory()" class="btn-back-sidebar w-100">
                             <i class="fas fa-chevron-left"></i> Back
                        </button>
                    </div>
                    <div class="skins-grid-full" id="skinsContainer">
                    <h3 class="text-center mt-5"><?= e($lang['selectCategory']) ?></h3>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
    <?php endif; ?>
    </div>
    <?php require ROOT_DIR . '/views/partials/footer.php'; ?>

    <!-- Pattern/Float Modal 2026 -->
    <div class="modal fade" id="patternFloat" tabindex="-1" aria-labelledby="patternFloatLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: var(--bg-surface); backdrop-filter: blur(var(--glass-blur)) saturate(1.8); border: 0.5px solid var(--border-main); border-radius: var(--radius-lg); box-shadow: var(--shadow-premium);">
                    <div class="modal-header border-0 pb-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title m-0" id="patternFloatLabel" style="color: var(--text-primary); font-weight:700;"><?= e($lang['modal']['title']) ?></h5>
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column text-light px-4">
                        <div style="background: rgba(120, 120, 128, 0.08); border-radius: var(--radius-md); padding: 2rem; margin-bottom: 2rem;" class="text-center">
                            <img id="modalImg" class="mx-auto contrast-reset" src="" style="width: 60%; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="text-center mb-4">
                            <h4 style="font-weight: 700; margin: 0; color: var(--text-primary);"><span id="modalWeapon"></span></h4>
                            <p style="color: var(--accent-primary); font-weight: 600; font-size: 1.1rem; margin: 0;"><span id="modalPaint"></span></p>
                        </div>

                        <div class="form-outline mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label m-0" style="font-weight:600; color: var(--text-secondary);"><?= e($lang['modal']['float']) ?></label>
                                <span id="floatText" class="badge" style="background: var(--accent-primary); font-weight:600; border-radius: 8px;"><?= e($lang['modal']['patternButtons'][0]['longName']) ?></span>
                            </div>
                            <input type="range" id="floatSlider" class="slider mb-3" min="0" max="1" step="0.000001" value="0.000001">
                            <input type="number" id="float" class="form-control" style="background: rgba(120, 120, 128, 0.12); color: var(--text-primary); border-radius: var(--radius-sm); padding: 12px;" min="0" max="1" step="0.000001" value="0.000001">
                        </div>

                    <div class="btn-group w-100 mb-4" style="gap: 8px;">
                        <button onclick="setFloat(0.0001)" class="btn btn-outline-light flex-fill" style="border-radius: 10px; font-weight: 600; font-size: 0.8rem; border-color: rgba(255,255,255,0.1);"><?= e($lang['modal']['patternButtons'][0]['shortName']) ?></button>
                        <button onclick="setFloat(0.07)" class="btn btn-outline-light flex-fill" style="border-radius: 10px; font-weight: 600; font-size: 0.8rem; border-color: rgba(255,255,255,0.1);"><?= e($lang['modal']['patternButtons'][1]['shortName']) ?></button>
                        <button onclick="setFloat(0.15)" class="btn btn-outline-light flex-fill" style="border-radius: 10px; font-weight: 600; font-size: 0.8rem; border-color: rgba(255,255,255,0.1);"><?= e($lang['modal']['patternButtons'][2]['shortName']) ?></button>
                        <button onclick="setFloat(0.38)" class="btn btn-outline-light flex-fill" style="border-radius: 10px; font-weight: 600; font-size: 0.8rem; border-color: rgba(255,255,255,0.1);"><?= e($lang['modal']['patternButtons'][3]['shortName']) ?></button>
                        <button onclick="setFloat(0.45)" class="btn btn-outline-light flex-fill" style="border-radius: 10px; font-weight: 600; font-size: 0.8rem; border-color: rgba(255,255,255,0.1);"><?= e($lang['modal']['patternButtons'][4]['shortName']) ?></button>
                    </div>

                        <div class="form-outline mb-2">
                            <label class="form-label mb-2" style="font-weight:600; color: var(--text-secondary);" for="pattern"><?= e($lang['modal']['pattern']) ?></label>
                            <input type="number" id="pattern" class="form-control" style="background: rgba(120, 120, 128, 0.12); color: var(--text-primary); border-radius: var(--radius-sm); padding: 12px;" placeholder="0" value="0">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn btn-primary w-100 py-3" style="font-size: 1.1rem;" onclick="changeParams()" id="modalButton"><?= e($lang['change']) ?></button>
                    </div>
            </div>
        </div>
    </div>

<script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="/js/index.js"></script>

<script>
    window.lang = "<?= e($config['lang'] ?? 'en') ?>";
    window.langObject = <?= json_encode($lang, JSON_UNESCAPED_UNICODE) ?>;
    window.selectedSkins = <?= json_encode($skins ?? [], JSON_UNESCAPED_UNICODE) ?>;
    window.selectedAgents = <?= json_encode($agents ?? ['agent_ct' => null, 'agent_t' => null, 'steamid' => null], JSON_UNESCAPED_UNICODE) ?>;
    window.selectedMusicKit = <?= json_encode($music ?? ['music_id' => null, 'steamid' => null], JSON_UNESCAPED_UNICODE) ?>;
    <?php if ($user): ?>
    window.user = <?= json_encode($user) ?>;
    <?php else: ?>
    window.user = null;
    <?php endif; ?>
</script>

<script src="/js/templates.js"></script>
<script src="/js/sideBtns.js"></script>

<script>
function updateFloatText(value) {
    let text;
    if (value >= 0 && value < 0.07) {
        text = langObject.modal.patternButtons[0].longName;
    } else if (value >= 0.07 && value < 0.15) {
        text = langObject.modal.patternButtons[1].longName;
    } else if (value >= 0.15 && value < 0.38) {
        text = langObject.modal.patternButtons[2].longName;
    } else if (value >= 0.38 && value < 0.45) {
        text = langObject.modal.patternButtons[3].longName;
    } else if (value >= 0.45 && value <= 1) {
        text = langObject.modal.patternButtons[4].longName;
    }
    document.getElementById('floatText').textContent = text;
}
</script>

<script>
    document.getElementById('floatSlider').oninput = function() {
        document.getElementById('float').value = this.value;
        updateFloatText(parseFloat(this.value));
    }

    document.getElementById('float').oninput = function() {
        document.getElementById('floatSlider').value = this.value;
        updateFloatText(parseFloat(this.value));
    }
</script>

<script>
    function setFloat(value) {
        value = Math.round(value * 10000) / 10000;
        document.getElementById('floatSlider').value = value;
        document.getElementById('float').value = value;
        updateFloatText(value);
    }
</script>

</body>
</html>
