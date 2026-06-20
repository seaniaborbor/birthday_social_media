<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 32px;">Dashboard</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Welcome back, <?= session()->get('firstName') ?>!</p>
        </div>
        <div class="stamp" style="position: static;">
            <?= $associationName ?>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        
        <!-- Total Members -->
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--color-primary);">groups</span>
            <h2 style="font-size: 36px; margin: 8px 0;"><?= number_format($totalMembers) ?></h2>
            <p class="font-mono-courier">Total Members</p>
            <?php if ($pendingMembers > 0): ?>
                <div class="alert alert-warning" style="margin-top: 12px; padding: 8px; font-size: 12px;">
                    <?= $pendingMembers ?> pending approval
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Birth Month Members -->
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--color-secondary);">cake</span>
            <h2 style="font-size: 36px; margin: 8px 0;"><?= number_format($birthMonthMembers) ?></h2>
            <p class="font-mono-courier"><?= $birthMonth ?> Born Members</p>
        </div>
        
        <!-- Events -->
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--color-primary);">event</span>
            <h2 style="font-size: 36px; margin: 8px 0;"><?= $upcomingEvents ?></h2>
            <p class="font-mono-courier">Upcoming Events</p>
            <small style="font-size: 11px;"><?= $totalEvents ?> total events</small>
        </div>
        
        <!-- News -->
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--color-secondary);">news</span>
            <h2 style="font-size: 36px; margin: 8px 0;"><?= $publishedNews ?></h2>
            <p class="font-mono-courier">Published Articles</p>
        </div>
        
        <!-- Pending Wishes -->
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--color-primary);">celebration</span>
            <h2 style="font-size: 36px; margin: 8px 0;"><?= $pendingWishes ?></h2>
            <p class="font-mono-courier">Pending Wishes</p>
            <?php if ($pendingWishes > 0): ?>
                <a href="/admin/wishes" class="btn-vintage" style="margin-top: 12px; display: inline-block; padding: 4px 12px; font-size: 11px;">Review →</a>
            <?php endif; ?>
        </div>
        
        <!-- Messages -->
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--color-secondary);">mail</span>
            <h2 style="font-size: 36px; margin: 8px 0;"><?= $unreadMessages ?></h2>
            <p class="font-mono-courier">Unread Messages</p>
            <?php if ($unreadMessages > 0): ?>
                <a href="/admin/messages" class="btn-vintage" style="margin-top: 12px; display: inline-block; padding: 4px 12px; font-size: 11px;">View →</a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 40px;">
        
        <!-- Member Growth Chart -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Member Growth (Last 6 Months)</h3>
            <canvas id="growthChart" style="max-height: 300px;"></canvas>
        </div>
        
        <!-- County Distribution Chart -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Top 5 Counties</h3>
            <canvas id="countyChart" style="max-height: 300px;"></canvas>
        </div>
        
        <!-- Gender Distribution Chart -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Gender Distribution</h3>
            <canvas id="genderChart" style="max-height: 300px;"></canvas>
        </div>
        
        <!-- Quick Actions -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a href="/admin/members/create" class="btn-vintage" style="text-align: center;">➕ Add New Member</a>
                <a href="/admin/events/create" class="btn-vintage" style="text-align: center;">📅 Create Event</a>
                <a href="/admin/news/create" class="btn-vintage" style="text-align: center;">📝 Write News Article</a>
                <a href="/admin/gallery/create" class="btn-vintage" style="text-align: center;">🖼️ Upload to Gallery</a>
                <a href="/admin/settings" class="btn-vintage" style="text-align: center;">⚙️ System Settings</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
        
        <!-- Recent Members -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Recent Members</h3>
            <?php if (!empty($recentMembers)): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <?php foreach ($recentMembers as $member): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 8px 0;">
                                    <strong><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></strong>
                                    <br>
                                    <small class="font-mono-courier" style="font-size: 10px;"><?= date('M j, Y', strtotime($member['created_at'])) ?></small>
                                </td>
                                <td style="text-align: right;">
                                    <?php if (!$member['is_approved']): ?>
                                        <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">Pending</span>
                                    <?php else: ?>
                                        <span class="stamp" style="position: static;">Approved</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <a href="/admin/members" style="display: block; text-align: center; margin-top: 16px;">View All →</a>
            <?php else: ?>
                <p class="font-mono-courier" style="opacity: 0.7;">No members yet.</p>
            <?php endif; ?>
        </div>
        
        <!-- Recent Events -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Recent Events</h3>
            <?php if (!empty($recentEvents)): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <?php foreach ($recentEvents as $event): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 8px 0;">
                                    <strong><?= esc($event['title']) ?></strong>
                                    <br>
                                    <small class="font-mono-courier" style="font-size: 10px;"><?= date('M j, Y', strtotime($event['event_date'])) ?></small>
                                </td>
                                <td style="text-align: right;">
                                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;"><?= $event['status'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <a href="/admin/events" style="display: block; text-align: center; margin-top: 16px;">View All →</a>
            <?php else: ?>
                <p class="font-mono-courier" style="opacity: 0.7;">No events yet.</p>
            <?php endif; ?>
        </div>
        
        <!-- Recent Messages -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Recent Messages</h3>
            <?php if (!empty($recentMessages)): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <?php foreach ($recentMessages as $message): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 8px 0;">
                                    <strong><?= esc($message['name']) ?></strong>
                                    <br>
                                    <small class="font-mono-courier" style="font-size: 10px;"><?= esc($message['subject']) ?></small>
                                </td>
                                <td style="text-align: right;">
                                    <?php if (!$message['is_read']): ?>
                                        <span class="btn-vintage btn-vintage-primary" style="padding: 2px 8px; font-size: 10px;">New</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <a href="/admin/messages" style="display: block; text-align: center; margin-top: 16px;">View All →</a>
            <?php else: ?>
                <p class="font-mono-courier" style="opacity: 0.7;">No messages yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Member Growth Chart
const growthCtx = document.getElementById('growthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($memberGrowth['months']) ?>,
        datasets: [{
            label: 'New Members',
            data: <?= json_encode($memberGrowth['counts']) ?>,
            borderColor: getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim(),
            backgroundColor: 'rgba(29, 78, 216, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// County Distribution Chart
const countyCtx = document.getElementById('countyChart').getContext('2d');
new Chart(countyCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($countyData['labels']) ?>,
        datasets: [{
            label: 'Members',
            data: <?= json_encode($countyData['counts']) ?>,
            backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--color-secondary').trim(),
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Gender Distribution Chart
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($genderData['labels']) ?>,
        datasets: [{
            data: <?= json_encode($genderData['counts']) ?>,
            backgroundColor: [
                getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim(),
                getComputedStyle(document.documentElement).getPropertyValue('--color-secondary').trim(),
                '#10b981'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>

<?= $this->endSection() ?>