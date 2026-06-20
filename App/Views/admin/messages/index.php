<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">📬 Contact Messages</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">View and reply to messages from the contact form</p>
        </div>
    </div>
    
    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px;"><?= $totalMessages ?></h2>
            <p class="font-mono-courier">Total Messages</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-secondary);"><?= $unreadCount ?></h2>
            <p class="font-mono-courier">Unread</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-success);"><?= $readCount ?></h2>
            <p class="font-mono-courier">Read/Replied</p>
        </div>
    </div>
    
    <!-- Status Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid var(--color-outline);">
        <a href="/admin/messages?status=all" class="btn-vintage <?= ($status == 'all' || !$status) ? 'btn-vintage-primary' : '' ?>">
            All
        </a>
        <a href="/admin/messages?status=unread" class="btn-vintage <?= $status == 'unread' ? 'btn-vintage-primary' : '' ?>">
            Unread (<?= $unreadCount ?>)
        </a>
        <a href="/admin/messages?status=read" class="btn-vintage <?= $status == 'read' ? 'btn-vintage-primary' : '' ?>">
            Read/Replied
        </a>
    </div>
    
    <!-- Bulk Actions Form -->
    <form method="POST" action="/admin/messages/bulk-delete" id="bulkForm">
        <?= csrf_field() ?>
        
        <!-- Messages Table -->
        <div class="polaroid-card" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--color-outline);">
                        <th style="padding: 12px; width: 40px;">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th style="padding: 12px; text-align: left;">From</th>
                        <th style="padding: 12px; text-align: left;">Subject</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                        <th style="padding: 12px; text-align: left;">Date</th>
                        <th style="padding: 12px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline); <?= !$message['is_read'] ? 'background: rgba(29,78,216,0.05);' : '' ?>">
                                <td style="padding: 12px;">
                                    <input type="checkbox" name="message_ids[]" value="<?= $message['id'] ?>" class="messageCheckbox">
                                </td>
                                <td style="padding: 12px;">
                                    <strong><?= esc($message['name']) ?></strong>
                                    <br>
                                    <small class="font-mono-courier" style="font-size: 10px;"><?= esc($message['email']) ?></small>
                                </td>
                                <td style="padding: 12px;">
                                    <?= esc($message['subject']) ?>
                                    <br>
                                    <small><?= esc(substr($message['message'], 0, 50)) ?>...</small>
                                </td>
                                <td style="padding: 12px;">
                                    <?php if (!$message['is_read']): ?>
                                        <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px; background: var(--color-secondary);">New</span>
                                    <?php elseif ($message['replied_at']): ?>
                                        <span class="stamp" style="position: static; font-size: 10px;">Replied</span>
                                    <?php else: ?>
                                        <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px; font-size: 12px;">
                                    <?= date('M j, Y g:i A', strtotime($message['created_at'])) ?>
                                </td>
                                <td style="padding: 12px;">
                                    <div style="display: flex; gap: 8px;">
                                        <a href="/admin/messages/view/<?= $message['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">View</a>
                                        <a href="/admin/messages/delete/<?= $message['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this message?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 60px; text-align: center;">
                                <span class="material-symbols-outlined" style="font-size: 64px;">mail</span>
                                <h3 style="margin: 16px 0;">No messages found</h3>
                                <p>Messages from the contact form will appear here.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if (!empty($messages)): ?>
                <div style="margin-top: 16px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Delete selected messages?')">
                        🗑️ Delete Selected
                    </button>
                </div>
            <?php endif; ?>
        </div>
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
    const checkboxes = document.querySelectorAll('.messageCheckbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

<?= $this->endSection() ?>