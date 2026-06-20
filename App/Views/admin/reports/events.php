<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/reports" class="btn-vintage">← Back to Reports</a>
    </div>
    
    <h1 style="font-size: 32px; margin-bottom: 8px;">📅 Event Participation Report</h1>
    <p class="font-mono-courier" style="opacity: 0.7; margin-bottom: 32px;">Track event attendance and RSVP metrics</p>
    
    <!-- Summary -->
    <div class="polaroid-card" style="text-align: center; margin-bottom: 30px; padding: 20px;">
        <h2 style="font-size: 48px;"><?= number_format($totalRsvps) ?></h2>
        <p class="font-mono-courier">Total RSVPs Across All Events</p>
    </div>
    
    <!-- Events Table -->
    <div class="polaroid-card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-outline);">
                    <th style="padding: 12px; text-align: left;">Event</th>
                    <th style="padding: 12px; text-align: left;">Date</th>
                    <th style="padding: 12px; text-align: center;">Going</th>
                    <th style="padding: 12px; text-align: center;">Total RSVPs</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;">
                                <strong><?= esc($event['title']) ?></strong>
                            </td>
                            <td style="padding: 12px;"><?= date('M j, Y', strtotime($event['event_date'])) ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <span style="background: #10b981; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    <?= $event['going_count'] ?? 0 ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;"><?= $event['rsvp_count'] ?? 0 ?></td>
                            <td style="padding: 12px;">
                                <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;
                                    <?= $event['status'] == 'upcoming' ? 'background: #3b82f6;' : '' ?>
                                    <?= $event['status'] == 'completed' ? 'opacity: 0.6;' : '' ?>
                                    <?= $event['status'] == 'cancelled' ? 'background: #dc2626;' : '' ?>">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <a href="/admin/events/rsvps/<?= $event['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">View RSVPs</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center;">No events found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>