<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/events" class="btn-vintage">← Back to Events</a>
    </div>
    
    <div class="polaroid-card">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
            <div>
                <h2><?= esc($event['title']) ?></h2>
                <p class="font-mono-courier"><?= date('F j, Y', strtotime($event['event_date'])) ?> at <?= esc($event['venue']) ?></p>
            </div>
            <a href="/admin/events/export-rsvps/<?= $event['id'] ?>" class="btn-vintage">📥 Export RSVPs</a>
        </div>
        
        <!-- RSVP Stats -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
            <div style="text-align: center; padding: 16px; background: rgba(5, 150, 105, 0.1);">
                <h3 style="font-size: 28px; color: var(--color-success);"><?= $goingCount ?></h3>
                <p>Going</p>
            </div>
            <div style="text-align: center; padding: 16px; background: rgba(234, 179, 8, 0.1);">
                <h3 style="font-size: 28px;"><?= $maybeCount ?></h3>
                <p>Maybe</p>
            </div>
            <div style="text-align: center; padding: 16px; background: rgba(220, 38, 38, 0.1);">
                <h3 style="font-size: 28px;"><?= $declinedCount ?></h3>
                <p>Declined</p>
            </div>
        </div>
        
        <!-- RSVP List -->
        <?php if (!empty($rsvps)): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-outline);">
                        <th style="padding: 12px; text-align: left;">Member</th>
                        <th style="padding: 12px; text-align: left;">Email</th>
                        <th style="padding: 12px; text-align: left;">Phone</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                        <th style="padding: 12px; text-align: left;">RSVP Date</th>
                     </tr>
                </thead>
                <tbody>
                    <?php foreach ($rsvps as $rsvp): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;"><?= esc($rsvp['first_name']) ?> <?= esc($rsvp['last_name']) ?></td>
                            <td style="padding: 12px;"><?= esc($rsvp['email']) ?></td>
                            <td style="padding: 12px;"><?= esc($rsvp['phone'] ?: '-') ?></td>
                            <td style="padding: 12px;">
                                <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;
                                    <?= $rsvp['status'] == 'going' ? 'background: var(--color-success);' : '' ?>
                                    <?= $rsvp['status'] == 'maybe' ? 'background: var(--color-secondary);' : '' ?>
                                    <?= $rsvp['status'] == 'declined' ? 'background: var(--color-error);' : '' ?>">
                                    <?= ucfirst($rsvp['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px;"><?= date('M j, Y g:i A', strtotime($rsvp['created_at'])) ?></td>
                         </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px;">
                <p>No RSVPs yet for this event.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>