<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/executives" class="btn-vintage">← Back to Executives</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">Edit Executive Position</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div>• <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/admin/executives/update/<?= $executive['id'] ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Select Member *</label>
                <select name="member_id" class="form-control" required>
                    <option value="">-- Select Member --</option>
                    <?php foreach ($members as $member): ?>
                        <option value="<?= $member['id'] ?>" <?= ($executive['member_id'] ?? old('member_id')) == $member['id'] ? 'selected' : '' ?>>
                            <?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?> (<?= esc($member['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Position Title *</label>
                <input type="text" name="position" class="form-control" value="<?= old('position', $executive['position']) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Bio / Description</label>
                <textarea name="bio" class="form-control" rows="4"><?= old('bio', $executive['bio']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="<?= old('sort_order', $executive['sort_order']) ?>">
                <small>Determines display order on homepage</small>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Update Position</button>
                <a href="/admin/executives" class="btn-vintage">Cancel</a>
            </div>
        </form>
        
        <hr class="dotted-divider" style="margin: 24px 0;">
        
        <div style="text-align: center;">
            <a href="/admin/executives/delete/<?= $executive['id'] ?>" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Delete this executive position?')">Delete Position</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>