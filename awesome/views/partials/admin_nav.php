<?php
/**
 * Admin navigation partial
 * Usage: $adminActivePage = 'settings' | 'servers' | 'database' | 'navigation' | 'template'
 * 
 * Author: untake
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */
$adminActivePage = $adminActivePage ?? 'settings';
$adminNavItems = [
    'settings'   => ['icon' => 'fas fa-sliders-h',  'path' => '/admin'],
    'servers'    => ['icon' => 'fas fa-server',      'path' => '/admin/servers'],
    'database'   => ['icon' => 'fas fa-database',    'path' => '/admin/database'],
    'navigation' => ['icon' => 'fas fa-compass',     'path' => '/admin/navigation'],
    'template'   => ['icon' => 'fas fa-palette',     'path' => '/admin/template'],
];
?>
<div class="d-flex gap-4 mb-5 pb-2 border-bottom border-white-5" style="border-color: rgba(255,255,255,0.05) !important;">
    <?php foreach ($adminNavItems as $key => $item): 
        $isActive = ($adminActivePage === $key);
    ?>
        <a href="/<?= e($currentLang) ?><?= $item['path'] ?>" 
           class="admin-nav-link <?= $isActive ? 'active' : '' ?>" 
           style="text-decoration: none; color: <?= $isActive ? 'var(--text-primary)' : 'var(--text-secondary)' ?>; font-weight: 600; font-size: 0.95rem; position: relative; padding-bottom: 8px; transition: var(--transition-base); display: flex; align-items: center; opacity: <?= $isActive ? '1' : '0.6' ?>;">
            <i class="<?= $item['icon'] ?> me-2"></i><?= e(t('admin.' . $key)) ?>
            <?php if ($isActive): ?>
                <div style="position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: var(--text-primary); border-radius: 4px;"></div>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>