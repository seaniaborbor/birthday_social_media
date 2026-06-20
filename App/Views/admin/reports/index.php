<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 32px;">📊 Reports Dashboard</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">View association statistics and generate reports</p>
    </div>
    
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div class="polaroid-card" style="text-align: center; padding: 24px;">
            <div style="width: 60px; height: 60px; background: rgba(59,130,246,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <span class="material-symbols-outlined" style="font-size: 32px; color: #3b82f6;">groups</span>
            </div>
            <h2 style="font-size: 36px; margin-bottom: 4px;"><?= number_format($totalMembers) ?></h2>
            <p class="font-mono-courier" style="font-size: 12px;">Total Members</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center; padding: 24px;">
            <div style="width: 60px; height: 60px; background: rgba(16,185,129,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <span class="material-symbols-outlined" style="font-size: 32px; color: #10b981;">cake</span>
            </div>
            <h2 style="font-size: 36px; margin-bottom: 4px;"><?= number_format($birthMonthMembers) ?></h2>
            <p class="font-mono-courier" style="font-size: 12px;"><?= get_birth_month() ?> Born Members</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center; padding: 24px;">
            <div style="width: 60px; height: 60px; background: rgba(245,158,11,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <span class="material-symbols-outlined" style="font-size: 32px; color: #f59e0b;">event</span>
            </div>
            <h2 style="font-size: 36px; margin-bottom: 4px;"><?= number_format($totalEvents) ?></h2>
            <p class="font-mono-courier" style="font-size: 12px;">Total Events</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center; padding: 24px;">
            <div style="width: 60px; height: 60px; background: rgba(139,92,246,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <span class="material-symbols-outlined" style="font-size: 32px; color: #8b5cf6;">how_to_reg</span>
            </div>
            <h2 style="font-size: 36px; margin-bottom: 4px;"><?= number_format($totalRsvps) ?></h2>
            <p class="font-mono-courier" style="font-size: 12px;">Total RSVPs</p>
        </div>
    </div>
    
    <!-- Report Options -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px; margin-bottom: 40px;">
        
        <!-- Member Reports -->
        <div class="polaroid-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <span class="material-symbols-outlined" style="font-size: 28px; color: #3b82f6;">group</span>
                <h3 style="margin: 0;">Member Reports</h3>
            </div>
            <p style="font-size: 13px; margin-bottom: 20px;">Export member data and view demographic insights</p>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="/admin/reports/demographics" class="btn-vintage" style="text-align: center; display: block;">📊 View Demographics</a>
                <a href="/admin/reports/export-members?type=all" class="btn-vintage" style="text-align: center; display: block;">📥 Export All Members (CSV)</a>
                <a href="/admin/reports/export-members?type=birth_month" class="btn-vintage" style="text-align: center; display: block;">📥 Export <?= get_birth_month() ?> Members (CSV)</a>
            </div>
        </div>
        
        <!-- Event Reports -->
        <div class="polaroid-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <span class="material-symbols-outlined" style="font-size: 28px; color: #f59e0b;">event</span>
                <h3 style="margin: 0;">Event Reports</h3>
            </div>
            <p style="font-size: 13px; margin-bottom: 20px;">Track event participation and attendance metrics</p>
            <a href="/admin/reports/events" class="btn-vintage" style="text-align: center; display: block;">📅 View Event Participation</a>
        </div>
        
        <!-- Audit Logs -->
        <div class="polaroid-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <span class="material-symbols-outlined" style="font-size: 28px; color: #8b5cf6;">history</span>
                <h3 style="margin: 0;">Audit Logs</h3>
            </div>
            <p style="font-size: 13px; margin-bottom: 20px;">Track all admin actions and system changes</p>
            <a href="/admin/audit" class="btn-vintage" style="text-align: center; display: block;">🔍 View Audit Logs</a>
        </div>
    </div>
    
    <!-- Recent Members Table -->
    <div class="polaroid-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>📋 Recent Registrations</h3>
            <a href="/admin/members" class="btn-vintage" style="padding: 4px 12px;">View All →</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-outline);">
                        <th style="padding: 12px; text-align: left;">Name</th>
                        <th style="padding: 12px; text-align: left;">Email</th>
                        <th style="padding: 12px; text-align: left;">Birth Month</th>
                        <th style="padding: 12px; text-align: left;">County</th>
                        <th style="padding: 12px; text-align: left;">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentMembers as $member): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;">
                                <strong><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></strong>
                            </td>
                            <td style="padding: 12px;"><?= esc($member['email']) ?></td>
                            <td style="padding: 12px;"><?= get_month_name($member['birth_month']) ?></td>
                            <td style="padding: 12px;"><?= esc($member['county'] ?: '-') ?></td>
                            <td style="padding: 12px;"><?= date('M j, Y', strtotime($member['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>