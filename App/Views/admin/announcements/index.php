<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">📣 Announcements</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Create and manage site-wide announcements</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px;"><?= $totalAnnouncements ?></h2>
            <p class="font-mono-courier">Total</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-success);"><?= $activeAnnouncements ?></h2>
            <p class="font-mono-courier">Active</p>
        </div>
    </div>

    <div class="polaroid-card" style="margin-bottom: 30px;">
        <h3 style="margin-bottom: 16px;">New Announcement</h3>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div>• <?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= site_url('admin/announcements/store') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="4" required><?= old('message') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control">
                        <option value="info">Info</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="danger">Danger</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Expires At</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="<?= old('expires_at') ?>">
                </div>
            </div>

            <div style="display: flex; gap: 16px; flex-wrap: wrap; margin: 12px 0 20px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_dismissible" value="1" checked>
                    Dismissible
                </label>
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active
                </label>
            </div>

            <button type="submit" class="btn-vintage btn-vintage-primary">Save Announcement</button>
        </form>
    </div>

    <div class="polaroid-card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-outline);">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Message</th>
                    <th style="padding: 12px; text-align: left;">Type</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Expires</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($announcements)): ?>
                    <?php foreach ($announcements as $announcement): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;">#<?= $announcement['id'] ?></td>
                            <td style="padding: 12px;"><?= esc($announcement['message']) ?></td>
                            <td style="padding: 12px;"><?= esc($announcement['type']) ?></td>
                            <td style="padding: 12px;">
                                <?= $announcement['is_active'] ? 'Active' : 'Inactive' ?>
                            </td>
                            <td style="padding: 12px;">
                                <?= $announcement['expires_at'] ? date('M j, Y g:i A', strtotime($announcement['expires_at'])) : 'Never' ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="<?= site_url('admin/announcements/toggle/' . $announcement['id']) ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">
                                        <?= $announcement['is_active'] ? 'Deactivate' : 'Activate' ?>
                                    </a>
                                    <a href="<?= site_url('admin/announcements/delete/' . $announcement['id']) ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this announcement?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center;">No announcements yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
