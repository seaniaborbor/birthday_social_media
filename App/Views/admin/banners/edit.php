
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/banners" class="btn-vintage">← Back to Banners</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">✏️ Edit Banner</h2>
        
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
        
        <form method="POST" action="/admin/banners/update/<?= $banner['id'] ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Banner Title *</label>
                <input type="text" name="title" class="form-control" value="<?= old('title', $banner['title']) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="<?= old('subtitle', $banner['subtitle']) ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Current Banner Image</label>
                <?php if ($banner['image']): ?>
                    <div style="margin: 10px 0;">
                        <img src="<?= base_url($banner['image']) ?>" alt="Current banner" style="max-width: 100%; max-height: 200px; border: 1px solid var(--color-outline); padding: 4px;">
                    </div>
                <?php else: ?>
                    <p class="font-mono-courier" style="font-size: 12px;">No image uploaded</p>
                <?php endif; ?>
                <label class="form-label" style="margin-top: 12px;">Replace Banner Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small>Leave empty to keep current image. Recommended size: 1920x600 pixels</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Button Text</label>
                <input type="text" name="button_text" class="form-control" value="<?= old('button_text', $banner['button_text']) ?>" placeholder="e.g., Learn More, Join Now">
            </div>
            
            <div class="form-group">
                <label class="form-label">Button Link</label>
                <input type="text" name="button_link" class="form-control" value="<?= old('button_link', $banner['button_link']) ?>" placeholder="/register or https://example.com">
                <small>Internal or external URL</small>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= $banner['is_active'] ? 'checked' : '' ?>>
                    <span>Active (show on homepage)</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Update Banner</button>
                <a href="/admin/banners" class="btn-vintage">Cancel</a>
            </div>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <div style="text-align: center;">
            <a href="/admin/banners/delete/<?= $banner['id'] ?>" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Delete this banner? This cannot be undone.')">
                🗑️ Delete Banner
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
