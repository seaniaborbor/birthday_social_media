<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">📅 Event Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Create and manage association events</p>
        </div>
        <a href="/admin/events/create" class="btn-vintage btn-vintage-primary">➕ Create Event</a>
    </div>
    
    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px;"><?= $totalEvents ?></h2>
            <p class="font-mono-courier">Total Events</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-success);"><?= $upcomingCount ?></h2>
            <p class="font-mono-courier">Upcoming</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px;"><?= $completedCount ?></h2>
            <p class="font-mono-courier">Completed</p>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="polaroid-card" style="margin-bottom: 24px;">
        <form method="GET" action="/admin/events">
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label class="form-label">Filter by Status</label>
                    <select name="status" class="form-control">
                        <option value="all">All Events</option>
                        <option value="upcoming" <?= ($status ?? '') == 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                        <option value="ongoing" <?= ($status ?? '') == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="completed" <?= ($status ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($status ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-vintage">Filter</button>
                    <a href="/admin/events" class="btn-vintage">Clear</a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Events Table -->
    <div class="polaroid-card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-outline);">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Event</th>
                    <th style="padding: 12px; text-align: left;">Date</th>
                    <th style="padding: 12px; text-align: left;">Venue</th>
                    <th style="padding: 12px; text-align: left;">RSVPs</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                 </tr>
            </thead>
            <tbody>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;">#<?= $event['id'] ?></td>
                            <td style="padding: 12px;">
                                <strong><?= esc($event['title']) ?></strong>
                                <?php if ($event['is_featured']): ?>
                                    <br><span class="stamp" style="position: static; font-size: 8px;">FEATURED</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <?= date('M j, Y', strtotime($event['event_date'])) ?>
                                <?php if ($event['event_time']): ?>
                                    <br><small><?= date('g:i A', strtotime($event['event_time'])) ?></small>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;"><?= esc($event['venue']) ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="/admin/events/rsvps/<?= $event['id'] ?>" class="btn-vintage" style="padding: 2px 8px;">
                                    <?= $event['rsvp_count'] ?? 0 ?> RSVPs
                                </a>
                            </td>
                            <td style="padding: 12px;">
                                <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px; 
                                    <?= $event['status'] == 'upcoming' ? 'background: var(--color-success);' : '' ?>
                                    <?= $event['status'] == 'completed' ? 'opacity: 0.6;' : '' ?>
                                    <?= $event['status'] == 'cancelled' ? 'background: var(--color-error);' : '' ?>">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 8px;">
                                    <a href="/events/<?= $event['slug'] ?>" target="_blank" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">View</a>
                                    <a href="/admin/events/edit/<?= $event['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                                    <a href="/admin/events/delete/<?= $event['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this event?')">Delete</a>
                                </div>
                            </td>
                         </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center;">No events found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <div style="margin-top: 24px; text-align: center;">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>