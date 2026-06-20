<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/banners" class="btn-vintage">← Back to Banners</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">➕ Create New Banner</h2>
        
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
        
        <form method="POST" action="/admin/banners/store" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Banner Title *</label>
                <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required placeholder="e.g., Welcome to Our Association">
            </div>
            
            <div class="form-group">
                <label class="form-label">Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="<?= old('subtitle') ?>" placeholder="e.g., Join the September Born Community">
            </div>
            
            <div class="form-group">
                <label class="form-label">Banner Image *</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <small>Recommended size: 1920x600 pixels. JPG, PNG, or WEBP format.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Button Text</label>
                <input type="text" name="button_text" class="form-control" value="<?= old('button_text') ?>" placeholder="e.g., Learn More, Join Now, Register">
            </div>
            
            <div class="form-group">
                <label class="form-label">Button Link</label>
                <input type="text" name="button_link" class="form-control" value="<?= old('button_link') ?>" placeholder="/register or https://example.com">
                <small>Internal or external URL</small>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= old('is_active') ? 'checked' : '' ?>>
                    <span>Active (show on homepage)</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Create Banner</button>
                <a href="/admin/banners" class="btn-vintage">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>