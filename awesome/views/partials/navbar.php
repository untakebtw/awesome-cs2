<?php
/**
 * Shared navbar partial (Glassmorphism Pro)
 * 
 * Author: untake
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */
$navbarName = $config['name'] ?? 'Skin Changer';
$isAdmin = false;
if ($user && !empty($config['steamId'])) {
    $isAdmin = ($user['steamid'] === $config['steamId']);
}
$otherLang = ($currentLang === 'ru') ? 'en' : 'ru';
$otherLangLabel = ($currentLang === 'ru') ? 'EN' : 'RU';
$currentLangLabel = ($currentLang === 'ru') ? 'RU' : 'EN';
?>



<nav class="navbar navbar-expand-lg sticky-top" style="background: var(--bg-surface); backdrop-filter: blur(var(--glass-blur)) saturate(1.8); border-bottom: 0.5px solid var(--border-main); transition: var(--transition-base);">
    <div class="container">
        <a class="navbar-brand" href="/<?= e($currentLang) ?>/" style="color: var(--text-primary) !important;">
            <?= e($navbarName) ?>
        </a>
        
        <div class="collapse navbar-collapse d-none d-lg-block" id="mainNavbar">
            <ul class="navbar-nav me-auto ms-lg-4">
                <?php
                $navbarLinks = $config['navigation']['navbar'] ?? [
                    ['name' => t('home.title'), 'path' => '/', 'sort' => 0, 'icon' => ''],
                    ['name' => t('home.skins'), 'path' => '/skins', 'sort' => 1, 'icon' => ''],
                ];
                usort($navbarLinks, function($a, $b) { return ($a['sort'] ?? 0) - ($b['sort'] ?? 0); });
                foreach ($navbarLinks as $navItem):
                    $isActive = ($navItem['path'] === ($path ?? ''));
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="/<?= e($currentLang) ?><?= e($navItem['path']) ?>" style="font-weight: 700; font-size: 0.95rem; padding: 0.5rem 1rem; color: var(--text-secondary); position: relative; transition: var(--transition-smooth);">
                        <?= e($navItem['name']) ?>
                        <?php if ($isActive): ?>
                            <span style="position: absolute; bottom: 0; left: 1rem; right: 1rem; height: 2.5px; background: var(--accent-primary); box-shadow: 0 0 12px var(--accent-glow); border-radius: 10px;"></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="d-flex align-items-center gap-2 ms-auto">
                <!-- Theme/Lang Cluster -->
                <div style="background: rgba(120, 120, 128, 0.1); border-radius: 14px; padding: 4px; display: flex; align-items: center; border: 0.5px solid var(--border-main);">
                    <div class="dropdown">
                        <button class="lang-switcher" type="button" data-bs-toggle="dropdown" style="background: transparent; border: none; color: var(--text-primary); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: var(--transition-bounce);">
                            <i class="fas fa-globe" style="font-size: 1rem; opacity: 0.8;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li>
                                <a class="dropdown-item <?= ($currentLang === 'ru') ? 'active' : '' ?>" href="/ru<?= e($path ?? '/') ?>">
                                    <span class="lang-badge">RU</span>
                                    <span>Русский</span>
                                    <?php if ($currentLang === 'ru'): ?>
                                        <i class="fas fa-check ms-auto" style="font-size: 0.7rem; opacity: 0.8;"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= ($currentLang === 'en') ? 'active' : '' ?>" href="/en<?= e($path ?? '/') ?>">
                                    <span class="lang-badge">EN</span>
                                    <span>English</span>
                                    <?php if ($currentLang === 'en'): ?>
                                        <i class="fas fa-check ms-auto" style="font-size: 0.7rem; opacity: 0.8;"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <button type="button" id="theme-switcher" style="background: transparent; border: none; color: var(--text-primary); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; position: relative;">
                        <i class="fas fa-moon" style="font-size: 1rem; opacity: 0.8;"></i>
                    </button>
                </div>

                <?php if ($user): ?>
                    <div class="dropdown">
                        <button class="btn d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" style="background: var(--bg-surface); border: 0.5px solid var(--border-main); border-radius: 14px; color: var(--text-primary); transition: var(--transition-bounce); padding: 5px 10px 5px 5px;">
                            <img src="<?= e($user['avatarmedium']) ?>" width="32" height="32" style="border-radius: 10px; border: 0.5px solid var(--border-main); object-fit: cover;" alt="User Avatar">
                            <span style="font-weight:700; font-size: 0.9rem; margin-left: 4px; color: var(--text-primary); white-space: nowrap;"><?= e($user['displayName'] ?? $user['personaname'] ?? '') ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="background: var(--bg-surface); backdrop-filter: blur(var(--glass-blur)) saturate(1.8); border: 0.5px solid var(--border-main); border-radius: 20px; margin-top: 12px; min-width: 240px; box-shadow: var(--shadow-premium); overflow: hidden;">
                            <?php if ($isAdmin): ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center mb-1" href="/<?= e($currentLang) ?>/admin" style="border-radius: 14px; padding: 12px; transition: var(--transition-base);">
                                        <div style="width: 38px; height: 38px; background: rgba(255, 255, 255, 0.05); color: var(--text-primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 14px; border: 0.5px solid var(--border-main);">
                                            <i class="fas fa-shield-halved" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-primary);"><?= e(t('adminPanel')) ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Manage website settings</div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider mx-2" style="opacity: 0.05; margin: 8px 0;"></li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/api/logout" style="border-radius: 14px; padding: 12px; transition: var(--transition-base);">
                                    <div style="width: 38px; height: 38px; background: rgba(255, 59, 48, 0.08); color: #ff3b30; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 14px; border: 0.5px solid rgba(255, 59, 48, 0.1);">
                                        <i class="fas fa-arrow-right-from-bracket" style="font-size: 1.1rem;"></i>
                                    </div>
                                    <div style="font-weight: 700; font-size: 0.9rem; color: #ff3b30;"><?= e(t('navLogout')) ?></div>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/api/auth/steam" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2" style="border-radius: 14px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(10, 132, 255, 0.3);">
                        <i class="fab fa-steam" style="font-size: 1.1rem;"></i>
                        <span style="font-size: 0.95rem;"><?= e(t('signIn')) ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
