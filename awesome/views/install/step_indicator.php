<?php
/**
 * Step indicator partial for install wizard
 * Variables expected: $currentStep (1-5), $currentLang
 * 
 * Author: untake
 * GitHub: https://github.com/untakebtw
 * Discord: https://discord.gg/SdjmNnp56N
 */
$stepUrls = [
    1 => '/' . e($currentLang) . '/install',
    2 => '/' . e($currentLang) . '/install/step2',
    3 => '/' . e($currentLang) . '/install/step3',
    4 => '/' . e($currentLang) . '/install/step4',
    5 => '/' . e($currentLang) . '/install/step5',
];
?>
<div class="install-logo" style="text-align:center;margin-bottom:1rem;">
    <img src="/images/awesome/awesome_logo.png" alt="Logo" style="width:80px;height:80px;object-fit:contain;display:inline-block;">
</div>
<div class="step-indicator">
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <?php
        $class = 'step-dot';
        if ($i < $currentStep) $class .= ' completed';
        elseif ($i === $currentStep) $class .= ' active';
        ?>
        <?php if ($i < $currentStep): ?>
            <a href="<?= $stepUrls[$i] ?>" class="step-link"><div class="<?= $class ?>"><?= $i ?></div></a>
        <?php elseif ($i === $currentStep): ?>
            <div class="<?= $class ?>"><?= $i ?></div>
        <?php else: ?>
            <div class="<?= $class ?>"><?= $i ?></div>
        <?php endif; ?>
    <?php endfor; ?>
</div>
