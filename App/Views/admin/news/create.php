<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/news" class="btn-vintage">← Back to News</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">Write New Article</h2>
        
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
        
        <form method="POST" action="/admin/news/store" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Article Title *</label>
                <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="<?= old('category') ?>" placeholder="e.g., Announcement, Event, Membership">
            </div>
            
            <div class="form-group">
                <label class="form-label">Excerpt / Summary</label>
                <textarea name="excerpt" class="form-control" rows="3" placeholder="Brief summary of the article..."><?= old('excerpt') ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Content *</label>
                <textarea name="content" class="form-control" rows="15" required><?= old('content') ?></textarea>
                <small>HTML formatting is supported</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Featured Image</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*">
            </div>
            
            <div style="display: flex; gap: 20px; align-items: center; margin: 20px 0;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_published" value="1" <?= old('is_published') ? 'checked' : '' ?>>
                    <span>Publish immediately</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Save Article</button>
                <a href="/admin/news" class="btn-vintage">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Simple WYSIWYG hint -->
<script>
// Add basic formatting help
const textarea = document.querySelector('textarea[name="content"]');
if (textarea) {
    const helpDiv = document.createElement('div');
    helpDiv.style.marginTop = '8px';
    helpDiv.style.fontSize = '11px';
    helpDiv.style.opacity = '0.7';
    helpDiv.innerHTML = 'Tip: Use &lt;p&gt; for paragraphs, &lt;strong&gt; for bold, &lt;em&gt; for italic, &lt;ul&gt;&lt;li&gt; for lists.';
    textarea.parentNode.appendChild(helpDiv);
}
</script>

<?= $this->endSection() ?>