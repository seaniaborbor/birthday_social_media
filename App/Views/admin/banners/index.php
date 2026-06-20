<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">🎨 Banner Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Manage homepage hero banners</p>
        </div>
        <a href="/admin/banners/create" class="btn-vintage btn-vintage-primary">➕ Add Banner</a>
    </div>
    
    <!-- Banners List -->
    <?php if (!empty($banners)): ?>
        <form method="POST" action="/admin/banners/reorder" id="reorderForm">
            <?= csrf_field() ?>
            <input type="hidden" name="order" id="orderInput">
            
            <div id="sortable-list">
                <?php foreach ($banners as $banner): ?>
                    <div data-id="<?= $banner['id'] ?>" class="sortable-item" style="margin-bottom: 16px;">
                        <div class="polaroid-card" style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap; cursor: move;">
                            <div style="width: 80px; height: 60px; overflow: hidden;">
                                <img src="<?= base_url($banner['image']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="flex: 1;">
                                <h3 style="margin-bottom: 4px;"><?= esc($banner['title']) ?></h3>
                                <?php if ($banner['subtitle']): ?>
                                    <p class="font-mono-courier" style="font-size: 12px;"><?= esc($banner['subtitle']) ?></p>
                                <?php endif; ?>
                                <div style="display: flex; gap: 12px; margin-top: 8px;">
                                    <span class="font-mono-courier" style="font-size: 10px;">Order: <?= $banner['sort_order'] ?></span>
                                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">
                                        <?= $banner['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/banners/edit/<?= $banner['id'] ?>" class="btn-vintage" style="padding: 4px 12px;">Edit</a>
                                <a href="/admin/banners/toggle-active/<?= $banner['id'] ?>" class="btn-vintage" style="padding: 4px 12px;">
                                    <?= $banner['is_active'] ? 'Deactivate' : 'Activate' ?>
                                </a>
                                <a href="/admin/banners/delete/<?= $banner['id'] ?>" class="btn-vintage" style="padding: 4px 12px; color: var(--color-error);" onclick="return confirm('Delete this banner?')">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="margin-top: 20px; text-align: center;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Save Banner Order</button>
            </div>
        </form>
        
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script>
            const list = document.getElementById('sortable-list');
            if (list) {
                new Sortable(list, {
                    animation: 150,
                    handle: '.polaroid-card',
                    onEnd: function() {
                        const items = list.querySelectorAll('.sortable-item');
                        const order = Array.from(items).map(item => item.dataset.id);
                        document.getElementById('orderInput').value = JSON.stringify(order);
                    }
                });
            }
        </script>
        
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">wallpaper</span>
            <h3 style="margin: 16px 0;">No Banners Yet</h3>
            <p class="font-mono-courier">Create your first homepage banner.</p>
            <a href="/admin/banners/create" class="btn-vintage btn-vintage-primary" style="margin-top: 20px;">Create Banner</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>