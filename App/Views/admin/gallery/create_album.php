<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/gallery" class="btn-vintage">← Back to Albums</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">📁 Create New Album</h2>
        
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
        
        <form method="POST" action="/admin/gallery/store-album" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Album Title *</label>
                <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe this photo album..."><?= old('description') ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Cover Image</label>
                <input type="file" name="cover_image" class="form-control" accept="image/*">
                <small>Recommended size: 800x600 pixels</small>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Create Album</button>
                <a href="/admin/gallery" class="btn-vintage">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>