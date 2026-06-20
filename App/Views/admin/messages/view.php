<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/messages" class="btn-vintage">← Back to Messages</a>
    </div>
    
    <div class="polaroid-card">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
            <div>
                <h2><?= esc($message['subject']) ?></h2>
                <p class="font-mono-courier" style="margin-top: 8px;">
                    From: <strong><?= esc($message['name']) ?></strong> (<?= esc($message['email']) ?>)
                </p>
            </div>
            <div>
                <span class="btn-vintage" style="padding: 4px 12px;">
                    <?= date('F j, Y g:i A', strtotime($message['created_at'])) ?>
                </span>
            </div>
        </div>
        
        <hr class="dotted-divider">
        
        <div class="ledger-lines" style="padding: 20px; margin: 20px 0;">
            <p style="white-space: pre-wrap;"><?= nl2br(esc($message['message'])) ?></p>
        </div>
        
        <hr class="dotted-divider">
        
        <!-- Reply Form -->
        <h3 style="margin: 20px 0 16px;">✉️ Reply to <?= esc($message['name']) ?></h3>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/admin/messages/reply/<?= $message['id'] ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Your Reply</label>
                <textarea name="reply_message" class="form-control" rows="8" required placeholder="Type your response here..."></textarea>
            </div>
            
            <div class="alert alert-info" style="margin: 16px 0;">
                <span class="material-symbols-outlined" style="vertical-align: middle;">info</span>
                This reply will be sent to <?= esc($message['email']) ?>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Send Reply</button>
                <a href="/admin/messages" class="btn-vintage">Cancel</a>
            </div>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <div style="text-align: center;">
            <a href="/admin/messages/delete/<?= $message['id'] ?>" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Delete this message?')">
                🗑️ Delete Message
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>