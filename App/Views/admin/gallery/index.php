<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">🖼️ Gallery Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Manage photo albums and gallery content</p>
        </div>
        <a href="/admin/gallery/create-album" class="btn-vintage btn-vintage-primary">➕ Create Album</a>
    </div>
    
    <!-- Albums Grid -->
    <?php if (!empty($albums)): ?>
        <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            <?php foreach ($albums as $album): ?>
                <div class="polaroid-card <?= 'rotate-' . rand(1, 2) ?>">
                    <a href="/admin/gallery/album/<?= $album['id'] ?>" style="text-decoration: none; color: inherit;">
                        <div style="height: 200px; background: var(--color-outline); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 16px;">
                            <?php if ($album['cover_image']): ?>
                                <img src="<?= base_url($album['cover_image']) ?>" alt="<?= esc($album['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 64px;">photo_library</span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 style="margin-bottom: 8px;"><?= esc($album['title']) ?></h3>
                        <p class="font-mono-courier" style="font-size: 12px;">
                            <?= $album['photo_count'] ?> photos
                        </p>
                        <?php if ($album['description']): ?>
                            <p style="font-size: 13px; opacity: 0.7; margin-top: 8px;"><?= esc(substr($album['description'], 0, 80)) ?>...</p>
                        <?php endif; ?>
                    </a>
                    
                    <div style="display: flex; gap: 8px; margin-top: 16px;">
                        <a href="/admin/gallery/album/<?= $album['id'] ?>" class="btn-vintage" style="flex: 1; text-align: center; padding: 6px;">Manage</a>
                        <a href="/admin/gallery/edit-album/<?= $album['id'] ?>" class="btn-vintage" style="padding: 6px 12px;">Edit</a>
                        <a href="/admin/gallery/delete-album/<?= $album['id'] ?>" class="btn-vintage" style="padding: 6px 12px; color: var(--color-error);" onclick="return confirm('Delete this album and all photos?')">🗑️</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">photo_library</span>
            <h3 style="margin: 16px 0;">No Albums Yet</h3>
            <p class="font-mono-courier">Create your first photo album to get started.</p>
            <a href="/admin/gallery/create-album" class="btn-vintage btn-vintage-primary" style="margin-top: 20px;">Create Album</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>