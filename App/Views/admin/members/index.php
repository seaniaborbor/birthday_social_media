<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">👥 Member Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Manage all registered members</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="/admin/members/create" class="btn-vintage btn-vintage-primary">➕ Add New Member</a>
            <a href="/admin/members/export" class="btn-vintage">📥 Export CSV</a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="polaroid-card" style="text-align: center; padding: 16px;">
            <h2 style="font-size: 28px;"><?= $totalMembers ?></h2>
            <p class="font-mono-courier">Total Members</p>
        </div>
        <div class="polaroid-card" style="text-align: center; padding: 16px;">
            <h2 style="font-size: 28px; color: var(--color-success);"><?= $approvedCount ?></h2>
            <p class="font-mono-courier">Approved</p>
        </div>
        <div class="polaroid-card" style="text-align: center; padding: 16px;">
            <h2 style="font-size: 28px; color: var(--color-secondary);"><?= $pendingCount ?></h2>
            <p class="font-mono-courier">Pending Approval</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="polaroid-card" style="margin-bottom: 24px;">
        <form method="GET" action="/admin/members">
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <div style="flex: 1;">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
                <div>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="approved" <?= ($status ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="pending" <?= ($status ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="inactive" <?= ($status ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-vintage">Filter</button>
                    <a href="/admin/members" class="btn-vintage">Clear</a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Bulk Actions Form -->
    <form method="POST" action="/admin/members/bulk-approve" id="bulkForm">
        <?= csrf_field() ?>
        
        <!-- Members Table -->
        <div class="polaroid-card" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-outline);">
                        <th style="padding: 12px; width: 40px;">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Name</th>
                        <th style="padding: 12px; text-align: left;">Email</th>
                        <th style="padding: 12px; text-align: left;">Birth Month</th>
                        <th style="padding: 12px; text-align: left;">County</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                        <th style="padding: 12px; text-align: left;">Joined</th>
                        <th style="padding: 12px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $member): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 12px;">
                                    <input type="checkbox" name="member_ids[]" value="<?= $member['id'] ?>" class="memberCheckbox">
                                </td>
                                <td style="padding: 12px;">#<?= $member['id'] ?></td>
                                <td style="padding: 12px;">
                                    <strong><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></strong>
                                </td>
                                <td style="padding: 12px;"><?= esc($member['email']) ?></td>
                                <td style="padding: 12px;">
                                    <?= get_month_name($member['birth_month']) ?> <?= $member['birth_day'] ?>
                                </td>
                                <td style="padding: 12px;"><?= esc($member['county']) ?: '-' ?></td>
                                <td style="padding: 12px;">
                                    <?php if (!$member['is_approved']): ?>
                                        <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px; background: var(--color-secondary);">Pending</span>
                                    <?php elseif ($member['is_active']): ?>
                                        <span class="stamp" style="position: static; font-size: 10px;">Active</span>
                                    <?php else: ?>
                                        <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px; font-size: 12px;"><?= date('M j, Y', strtotime($member['created_at'])) ?></td>
                                <td style="padding: 12px;">
                                    <div style="display: flex; gap: 8px;">
                                        <a href="/admin/members/view/<?= $member['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">View</a>
                                        <a href="/admin/members/edit/<?= $member['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                                        <?php if (!$member['is_approved']): ?>
                                            <a href="/admin/members/approve/<?= $member['id'] ?>" class="btn-vintage btn-vintage-primary" style="padding: 4px 8px; font-size: 11px;">Approve</a>
                                        <?php endif; ?>
                                        <?php if ($member['id'] != session()->get('memberId')): ?>
                                            <a href="/admin/members/delete/<?= $member['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Are you sure?')">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="padding: 40px; text-align: center;">No members found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Bulk Actions -->
        <?php if (!empty($members)): ?>
            <div style="margin-top: 16px; display: flex; gap: 12px;">
                <button type="submit" class="btn-vintage btn-vintage-primary" onclick="return confirm('Approve selected members?')">✅ Approve Selected</button>
            </div>
        <?php endif; ?>
    </form>
    
    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <div style="margin-top: 24px; text-align: center;">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.memberCheckbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

<?= $this->endSection() ?>