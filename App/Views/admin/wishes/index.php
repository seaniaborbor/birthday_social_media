<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">💝 Birthday Wishes Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Approve or delete birthday wishes submitted by members</p>
        </div>
    </div>
    
    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-secondary);"><?= $pendingCount ?></h2>
            <p class="font-mono-courier">Pending Approval</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-success);"><?= $approvedCount ?></h2>
            <p class="font-mono-courier">Approved Wishes</p>
        </div>
    </div>
    
    <!-- Status Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid var(--color-outline);">
        <a href="/admin/wishes?status=pending" class="btn-vintage <?= $status == 'pending' ? 'btn-vintage-primary' : '' ?>" style="border-radius: 0; border-bottom: none;">
            Pending (<?= $pendingCount ?>)
        </a>
        <a href="/admin/wishes?status=approved" class="btn-vintage <?= $status == 'approved' ? 'btn-vintage-primary' : '' ?>" style="border-radius: 0; border-bottom: none;">
            Approved (<?= $approvedCount ?>)
        </a>
    </div>
    
    <!-- Bulk Actions Form -->
    <?php if ($status == 'pending' && !empty($wishes)): ?>
        <form method="POST" action="/admin/wishes/bulk-approve" id="bulkForm">
            <?= csrf_field() ?>
    <?php endif; ?>
    
    <!-- Wishes List -->
    <div class="polaroid-card">
        <?php if (!empty($wishes)): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-outline);">
                        <?php if ($status == 'pending'): ?>
                            <th style="padding: 12px; width: 40px;">
                                <input type="checkbox" id="selectAll">
                            </th>
                        <?php endif; ?>
                        <th style="padding: 12px; text-align: left;">From</th>
                        <th style="padding: 12px; text-align: left;">To</th>
                        <th style="padding: 12px; text-align: left;">Message</th>
                        <th style="padding: 12px; text-align: left;">Date</th>
                        <th style="padding: 12px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishes as $wish): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <?php if ($status == 'pending'): ?>
                                <td style="padding: 12px;">
                                    <input type="checkbox" name="wish_ids[]" value="<?= $wish['id'] ?>" class="wishCheckbox">
                                </td>
                            <?php endif; ?>
                            <td style="padding: 12px;">
                                <strong><?= esc($wish['first_name']) ?> <?= esc($wish['last_name']) ?></strong>
                            </td>
                            <td style="padding: 12px;">
                                <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">🎂 <?= esc($wish['recipient_name']) ?></span>
                            </td>
                            <td style="padding: 12px;">
                                <em>"<?= esc(substr($wish['message'], 0, 80)) ?>..."</em>
                            </td>
                            <td style="padding: 12px; font-size: 12px;">
                                <?= date('M j, Y g:i A', strtotime($wish['created_at'])) ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 8px;">
                                    <?php if ($status == 'pending'): ?>
                                        <a href="/admin/wishes/approve/<?= $wish['id'] ?>" class="btn-vintage btn-vintage-primary" style="padding: 4px 8px; font-size: 11px;">Approve</a>
                                    <?php endif; ?>
                                    <a href="/admin/wishes/delete/<?= $wish['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this wish?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if ($status == 'pending' && !empty($wishes)): ?>
                <div style="margin-top: 16px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-vintage btn-vintage-primary" onclick="return confirm('Approve selected wishes?')">✅ Approve Selected</button>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div style="text-align: center; padding: 60px;">
                <span class="material-symbols-outlined" style="font-size: 64px;">celebration</span>
                <h3 style="margin: 16px 0;">No <?= $status ?> wishes</h3>
                <p class="font-mono-courier">
                    <?= $status == 'pending' ? 'All wishes have been reviewed.' : 'No approved wishes yet.' ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($status == 'pending' && !empty($wishes)): ?>
        </form>
    <?php endif; ?>
</div>

<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.wishCheckbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

<?= $this->endSection() ?>