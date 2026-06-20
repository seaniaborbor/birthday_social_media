<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="font-size: 32px;">📋 Audit Logs</h1>
        <a href="/admin/audit/export" class="btn-vintage">📥 Export CSV</a>
    </div>
    
    <div class="polaroid-card">
        <?php if (!empty($logs)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-outline);">
                            <th style="padding: 12px; text-align: left;">ID</th>
                            <th style="padding: 12px; text-align: left;">User</th>
                            <th style="padding: 12px; text-align: left;">Action</th>
                            <th style="padding: 12px; text-align: left;">Table</th>
                            <th style="padding: 12px; text-align: left;">IP Address</th>
                            <th style="padding: 12px; text-align: left;">Date/Time</th>
                        </td>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 12px;">#<?= $log['id'] ?></td>
                                <td style="padding: 12px;">
                                    <?= esc($log['first_name'] ?? 'System') ?> <?= esc($log['last_name'] ?? '') ?>
                                </td>
                                <td style="padding: 12px;">
                                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;"><?= ucfirst($log['action']) ?></span>
                                </td>
                                <td style="padding: 12px;"><?= $log['table_name'] ?? '-' ?></td>
                                <td style="padding: 12px;"><?= $log['ip_address'] ?></td>
                                <td style="padding: 12px;"><?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px;">
                <span class="material-symbols-outlined" style="font-size: 64px;">history</span>
                <h3>No audit logs found</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>