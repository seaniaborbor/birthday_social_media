<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/reports" class="btn-vintage">← Back to Reports</a>
    </div>
    
    <h1 style="font-size: 32px; margin-bottom: 8px;">📊 Member Demographics</h1>
    <p class="font-mono-courier" style="opacity: 0.7; margin-bottom: 32px;">Demographic insights about our members</p>
    
    <!-- Summary -->
    <div class="polaroid-card" style="text-align: center; margin-bottom: 30px; padding: 20px;">
        <h2 style="font-size: 48px;"><?= number_format($totalMembers) ?></h2>
        <p class="font-mono-courier">Total Approved Members</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
        
        <!-- Gender Distribution -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Gender Distribution</h3>
            <canvas id="genderChart" style="max-height: 300px;"></canvas>
            <div style="margin-top: 16px;">
                <?php foreach ($genderData as $gender): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span><?= $gender['gender'] ?: 'Not specified' ?></span>
                        <span class="font-mono-courier"><?= $gender['count'] ?> (<?= round(($gender['count'] / $totalMembers) * 100, 1) ?>%)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Age Distribution -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Age Distribution</h3>
            <canvas id="ageChart" style="max-height: 300px;"></canvas>
            <div style="margin-top: 16px;">
                <?php foreach ($ageGroups as $group => $count): ?>
                    <?php if ($count > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span><?= $group ?></span>
                            <span class="font-mono-courier"><?= $count ?> (<?= round(($count / $totalMembers) * 100, 1) ?>%)</span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- County Distribution -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Top Counties</h3>
            <canvas id="countyChart" style="max-height: 300px;"></canvas>
            <div style="margin-top: 16px;">
                <?php foreach (array_slice($countyData, 0, 5) as $county): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span><?= esc($county['county']) ?></span>
                        <span class="font-mono-courier"><?= $county['count'] ?> members</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Occupation Distribution -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Top Occupations</h3>
            <canvas id="occupationChart" style="max-height: 300px;"></canvas>
            <div style="margin-top: 16px;">
                <?php foreach (array_slice($occupationData, 0, 5) as $occupation): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span><?= esc($occupation['occupation']) ?></span>
                        <span class="font-mono-courier"><?= $occupation['count'] ?> members</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Gender Chart
const genderCtx = document.getElementById('genderChart')?.getContext('2d');
if (genderCtx) {
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($genderData, 'gender') ?: ['No Data']) ?>,
            datasets: [{
                data: <?= json_encode(array_column($genderData, 'count') ?: [1]) ?>,
                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b']
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
}

// Age Chart
const ageCtx = document.getElementById('ageChart')?.getContext('2d');
if (ageCtx) {
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($ageGroups)) ?>,
            datasets: [{
                label: 'Members',
                data: <?= json_encode(array_values($ageGroups)) ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 4
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
}

// County Chart
const countyCtx = document.getElementById('countyChart')?.getContext('2d');
if (countyCtx) {
    new Chart(countyCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column(array_slice($countyData, 0, 5), 'county')) ?>,
            datasets: [{
                label: 'Members',
                data: <?= json_encode(array_column(array_slice($countyData, 0, 5), 'count')) ?>,
                backgroundColor: '#10b981',
                borderRadius: 4
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
}

// Occupation Chart
const occupationCtx = document.getElementById('occupationChart')?.getContext('2d');
if (occupationCtx) {
    new Chart(occupationCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column(array_slice($occupationData, 0, 5), 'occupation')) ?>,
            datasets: [{
                label: 'Members',
                data: <?= json_encode(array_column(array_slice($occupationData, 0, 5), 'count')) ?>,
                backgroundColor: '#f59e0b',
                borderRadius: 4
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
}
</script>

<?= $this->endSection() ?>