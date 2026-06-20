<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="/admin/gallery" class="btn-vintage" style="margin-bottom: 12px; display: inline-block;">← Back to Albums</a>
            <h1 style="font-size: 32px;"><?= esc($album['title']) ?></h1>
            <p class="font-mono-courier" style="opacity: 0.7;"><?= count($photos) ?> photos in this album</p>
        </div>
    </div>
    
    <!-- Upload Form -->
    <div class="polaroid-card" style="margin-bottom: 30px;">
        <h3 style="margin-bottom: 16px;">📤 Upload New Photo</h3>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?= site_url('admin/gallery/upload-photo/' . $album['id']) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Photo Image</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Caption (optional)</label>
                    <input type="text" name="caption" class="form-control" placeholder="Describe this photo">
                </div>
                
                <div>
                    <button type="submit" class="btn-vintage btn-vintage-primary">Upload Photo</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Photos Grid -->
    <?php if (!empty($photos)): ?>
        <form method="POST" action="<?= site_url('admin/gallery/reorder-photos') ?>" id="reorderForm">
            <?= csrf_field() ?>
            <input type="hidden" name="order" id="orderInput">
            
            <div id="sortable-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($photos as $photo): ?>
                    <div data-id="<?= $photo['id'] ?>" class="sortable-item">
                        <div class="polaroid-card" style="cursor: move;">
                            <div style="height: 200px; overflow: hidden; margin-bottom: 12px;">
                                <img src="<?= base_url($photo['filename']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <?php if ($photo['caption']): ?>
                                <p style="font-size: 12px; margin-bottom: 12px;"><?= esc($photo['caption']) ?></p>
                            <?php endif; ?>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="font-mono-courier" style="font-size: 10px;">Order: <?= $photo['sort_order'] ?></span>
                                <a href="<?= site_url('admin/gallery/delete-photo/' . $photo['id']) ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this photo?')">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="margin-top: 20px; text-align: center;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Save Photo Order</button>
            </div>
        </form>
        
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script>
            const grid = document.getElementById('sortable-grid');
            if (grid) {
                new Sortable(grid, {
                    animation: 150,
                    handle: '.polaroid-card',
                    onEnd: function() {
                        const items = grid.querySelectorAll('.sortable-item');
                        const order = Array.from(items).map(item => item.dataset.id);
                        document.getElementById('orderInput').value = JSON.stringify(order);
                    }
                });
            }
        </script>
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">photo_camera</span>
            <h3 style="margin: 16px 0;">No Photos Yet</h3>
            <p>Upload photos to this album using the form above.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
