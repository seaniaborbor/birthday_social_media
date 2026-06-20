<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px; margin-bottom: 8px;">📊 Birthday Statistics</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">
            Insights about our <?= $birthMonth ?> born community
        </p>
    </div>
    
    <!-- Overview Cards -->
    <div class="card-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 40px;">
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">cake</span>
            <h2 style="margin: 12px 0;"><?= $totalBirthdays ?></h2>
            <p class="font-mono-courier">Total <?= $birthMonth ?> Members</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">event</span>
            <h2 style="margin: 12px 0;"><?= $mostPopularDay ?? 'N/A' ?></h2>
            <p class="font-mono-courier">Most Popular Day</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">timeline</span>
            <h2 style="margin: 12px 0;"><?= $mostPopularDecade ?? 'N/A' ?></h2>
            <p class="font-mono-courier">Most Common Decade</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">location_on</span>
            <h2 style="margin: 12px 0;"><?= count($countyDistribution) ?></h2>
            <p class="font-mono-courier">Counties Represented</p>
        </div>
    </div>
    
    <!-- Day Distribution Chart -->
    <div class="polaroid-card" style="margin-bottom: 40px;">
        <h3 style="margin-bottom: 20px;">Birthday Distribution by Day</h3>
        <div style="overflow-x: auto;">
            <div style="display: flex; gap: 4px; min-width: 800px; align-items: flex-end;">
                <?php for ($day = 1; $day <= 31; $day++): ?>
                    <?php $count = $dayDistribution[$day] ?? 0; ?>
                    <div style="flex: 1; text-align: center;">
                        <div style="height: <?= $count * 20 ?>px; background: var(--color-primary); margin-bottom: 8px; min-height: <?= $count > 0 ? '20px' : '0' ?>;"></div>
                        <div class="font-mono-courier" style="font-size: 11px;"><?= $day ?></div>
                        <div class="font-mono-courier" style="font-size: 10px; opacity: 0.7;"><?= $count ?></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    
    <!-- Decade Distribution -->
    <?php if (!empty($decadeDistribution)): ?>
        <div class="polaroid-card" style="margin-bottom: 40px;">
            <h3 style="margin-bottom: 20px;">Birth Years by Decade</h3>
            <div style="overflow-x: auto;">
                <div style="display: flex; gap: 16px; justify-content: center; align-items: flex-end; min-width: 400px;">
                    <?php 
                    $maxDecadeCount = max($decadeDistribution);
                    foreach ($decadeDistribution as $decade => $count): 
                        $height = ($count / $maxDecadeCount) * 200;
                    ?>
                        <div style="flex: 1; text-align: center;">
                            <div style="height: <?= $height ?>px; background: var(--color-secondary); margin-bottom: 8px; min-height: <?= $count > 0 ? '20px' : '0' ?>;"></div>
                            <div class="font-mono-courier" style="font-size: 12px;"><?= $decade ?></div>
                            <div class="font-mono-courier" style="font-size: 10px; opacity: 0.7;"><?= $count ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- County Distribution -->
    <?php if (!empty($countyDistribution)): ?>
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Geographic Distribution by County</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-outline);">
                            <th style="padding: 12px; text-align: left;">County</th>
                            <th style="padding: 12px; text-align: left;">Members</th>
                            <th style="padding: 12px; text-align: left;">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        arsort($countyDistribution);
                        foreach ($countyDistribution as $county => $count): 
                            $percentage = round(($count / $totalBirthdays) * 100, 1);
                        ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 12px;"><?= esc($county) ?></td>
                                <td style="padding: 12px;"><?= $count ?></td>
                                <td style="padding: 12px;">
                                    <div class="progress-bar-vintage" style="width: 150px; display: inline-block; vertical-align: middle; margin-right: 8px;">
                                        <div class="progress-fill" style="width: <?= $percentage ?>%;"></div>
                                    </div>
                                    <?= $percentage ?>%
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>