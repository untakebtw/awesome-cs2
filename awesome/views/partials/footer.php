<?php
/**
 * Footer partial — 3-column layout with Glassmorphism
 * 
 * Author: untake
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */
$footerConfig = $config['footer'] ?? [];
$footerText = $footerConfig['text'] ?? '';
$footerLinks = $footerConfig['links'] ?? [];
$social = $footerConfig['social'] ?? [];

$footerNavLinks = $config['navigation']['footer'] ?? [];
usort($footerNavLinks, function($a, $b) { return ($a['sort'] ?? 0) - ($b['sort'] ?? 0); });

$socialIcons = [
    'discord'  => 'fab fa-discord',
    'telegram' => 'fab fa-telegram-plane',
    'youtube'  => 'fab fa-youtube',
    'github'   => 'fab fa-github',
];
?>

<style>
    footer.glass-footer {
        background: var(--bg-surface);
        backdrop-filter: blur(var(--glass-blur)) saturate(1.8);
        border-top: 0.5px solid var(--border-main);
        padding: 1rem 0;
        position: relative;
        overflow: hidden;
    }

    footer.glass-footer::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent-primary), transparent);
        opacity: 0.2;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 3rem;
        align-items: start;
    }

    @media (max-width: 992px) {
        .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; text-align: center; }
        .footer-col-left, .footer-col-center, .footer-col-right { text-align: center !important; }
        .footer-social-list { justify-content: center !important; }
    }

    .footer-col-left { text-align: left; }
    .footer-col-center { text-align: center; }
    .footer-col-right { text-align: right; }

    .footer-heading {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 1.2rem;
    }

    .footer-nav-link {
        display: block;
        color: var(--text-secondary) !important;
        font-size: 0.95rem;
        text-decoration: none;
        padding: 6px 0;
        transition: var(--transition-bounce);
        font-weight: 500;
    }
    .footer-nav-link:hover {
        color: var(--text-primary) !important;
        transform: translateX(5px);
    }

    .footer-user-text {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        font-weight: 400;
    }

    .footer-social-list {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        gap: 12px;
    }

    .footer-social-link-icon {
        font-size: 1.25rem;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(120, 120, 128, 0.1);
        border: 0.5px solid var(--border-main);
        color: var(--text-primary) !important;
        text-decoration: none;
        transition: var(--transition-bounce);
    }
    .footer-social-link-icon:hover {
        background: var(--accent-primary);
        color: white !important;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px var(--accent-glow);
    }

    .footer-bottom {
        border-top: 0.5px solid var(--border-main);
        margin-top: 2rem;
        padding-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .footer-bottom small {
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.75rem;
    }
</style>

<footer class="glass-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col-left">
                <div class="footer-heading"><?= e(t('footer.usefulLinks')) ?></div>
                <?php if (!empty($footerNavLinks)): ?>
                    <?php foreach ($footerNavLinks as $fLink): ?>
                        <a href="/<?= e($currentLang) ?><?= e($fLink['path']) ?>" class="footer-nav-link">
                            <?= e($fLink['name']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="footer-col-center">
                <div class="footer-heading"><?= e(t('footer.information')) ?></div>
                <div class="footer-user-text">
                    <?= $footerText ? e($footerText) : e(t('footer.yourInfoHere')) ?>
                </div>
            </div>

            <div class="footer-col-right">
                <div class="footer-heading"><?= e(t('footer.socialNetworks')) ?></div>
                <div class="footer-social-list">
                    <?php foreach ($socialIcons as $key => $icon):
                        if (!empty($social[$key])): ?>
                            <a href="<?= e($social[$key]) ?>" target="_blank" class="footer-social-link-icon" title="<?= ucfirst($key) ?>">
                                <i class="<?= $icon ?>"></i>
                            </a>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <small>&copy; <?= date('Y') ?> <span style="color: #fff;"><?= e($config['name'] ?? 'CS2 WeaponPaints') ?></span></small>
            <small>Concept by <a href="https://github.com/untakebtw" target="_blank" style="color: var(--accent-color); text-decoration:none;">untake</a></small>
        </div>
    </div>
</footer>

<script>
    (function() {
        const themeSwitcher = document.getElementById('theme-switcher');
        const docBody = document.body;
        const savedTheme = localStorage.getItem('theme') || 'dark';
        if (savedTheme === 'light') {
            docBody.classList.add('light-theme');
        }
        if (themeSwitcher) {
            themeSwitcher.addEventListener('click', function() {
                docBody.classList.toggle('light-theme');
                const isLight = docBody.classList.contains('light-theme');
                localStorage.setItem('theme', isLight ? 'light' : 'dark');
            });
        }
    })();
</script>
