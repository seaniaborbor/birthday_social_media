<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/gallery" class="btn-vintage">← Back to Albums</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">✏️ Edit Album</h2>
        
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
        
        <form method="POST" action="/admin/gallery/update-album/<?= $album['id'] ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Album Title *</label>
                <input type="text" name="title" class="form-control" value="<?= old('title', $album['title']) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= old('description', $album['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Current Cover Image</label>
                <?php if ($album['cover_image']): ?>
                    <div style="margin: 10px 0;">
                        <img src="<?= base_url($album['cover_image']) ?>" alt="Current cover" style="max-width: 200px; border: 1px solid var(--color-outline); padding: 4px;">
                    </div>
                <?php else: ?>
                    <p class="font-mono-courier" style="font-size: 12px;">No cover image set</p>
                <?php endif; ?>
                <label class="form-label" style="margin-top: 12px;">Replace Cover Image</label>
                <input type="file" name="cover_image" class="form-control" accept="image/*">
                <small>Leave empty to keep current image</small>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Update Album</button>
                <a href="/admin/gallery" class="btn-vintage">Cancel</a>
            </div>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <div style="text-align: center;">
            <a href="/admin/gallery/delete-album/<?= $album['id'] ?>" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Delete this album and ALL photos in it? This cannot be undone.')">
                🗑️ Delete Album
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>