<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">👑 Executive Committee</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Manage association leadership positions</p>
        </div>
        <a href="/admin/executives/create" class="btn-vintage btn-vintage-primary">➕ Add Executive Position</a>
    </div>
    
    <!-- Stats -->
    <div class="polaroid-card" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; text-align: center;">
            <div>
                <h2 style="font-size: 32px;"><?= $totalExecutives ?></h2>
                <p class="font-mono-courier">Total Positions</p>
            </div>
            <div>
                <h2 style="font-size: 32px;"><?= count(array_filter($executives, fn($e) => $e['sort_order'] <= 5)) ?></h2>
                <p class="font-mono-courier">Core Leadership</p>
            </div>
        </div>
    </div>
    
    <!-- Executives Grid -->
    <?php if (!empty($executives)): ?>
        <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
            <?php foreach ($executives as $executive): ?>
                <div class="polaroid-card" data-id="<?= $executive['id'] ?>" style="position: relative;">
                    <div style="display: flex; gap: 16px; align-items: start;">
                        <div style="width: 80px; height: 80px; background: var(--color-outline); border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                            <?php if ($executive['profile_photo']): ?>
                                <img src="<?= base_url($executive['profile_photo']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 48px;">crown</span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div>
                                    <h3 style="margin-bottom: 4px;"><?= esc($executive['position']) ?></h3>
                                    <p class="font-mono-courier" style="font-size: 14px;">
                                        <?= esc($executive['first_name']) ?> <?= esc($executive['last_name']) ?>
                                    </p>
                                </div>
                                <div class="stamp" style="position: static;">
                                    Order #<?= $executive['sort_order'] ?>
                                </div>
                            </div>
                            
                            <?php if ($executive['occupation']): ?>
                                <p style="font-size: 12px; margin-top: 8px;">💼 <?= esc($executive['occupation']) ?></p>
                            <?php endif; ?>
                            
                            <?php if ($executive['bio']): ?>
                                <p style="font-size: 12px; margin-top: 8px; opacity: 0.7;"><?= esc(substr($executive['bio'], 0, 80)) ?>...</p>
                            <?php endif; ?>
                            
                            <div style="display: flex; gap: 8px; margin-top: 12px;">
                                <a href="/admin/executives/edit/<?= $executive['id'] ?>" class="btn-vintage" style="padding: 4px 12px; font-size: 11px;">Edit</a>
                                <a href="/admin/executives/delete/<?= $executive['id'] ?>" class="btn-vintage" style="padding: 4px 12px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this executive position?')">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Reorder Section -->
        <div class="polaroid-card" style="margin-top: 30px;">
            <h3 style="margin-bottom: 16px;">📋 Reorder Executive Positions</h3>
            <p class="font-mono-courier" style="font-size: 12px; margin-bottom: 16px;">Drag and drop to reorder, then click save.</p>
            
            <form method="POST" action="/admin/executives/reorder" id="reorderForm">
                <?= csrf_field() ?>
                <input type="hidden" name="order" id="orderInput">
                
                <ul id="sortable-list" style="list-style: none; padding: 0;">
                    <?php 
                    usort($executives, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
                    foreach ($executives as $executive): 
                    ?>
                        <li data-id="<?= $executive['id'] ?>" style="padding: 12px; margin-bottom: 8px; background: var(--color-surface); border: 1px solid var(--color-outline); cursor: move; display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                <span class="material-symbols-outlined" style="vertical-align: middle;">drag_handle</span>
                                <strong><?= esc($executive['position']) ?></strong> - 
                                <?= esc($executive['first_name']) ?> <?= esc($executive['last_name']) ?>
                            </span>
                            <span class="font-mono-courier" style="font-size: 11px;">Current: <?= $executive['sort_order'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <button type="submit" class="btn-vintage btn-vintage-primary" style="margin-top: 16px;">Save New Order</button>
            </form>
        </div>
        
        <!-- Drag and Drop JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script>
            const list = document.getElementById('sortable-list');
            if (list) {
                new Sortable(list, {
                    animation: 150,
                    onEnd: function() {
                        const items = list.querySelectorAll('li');
                        const order = Array.from(items).map(item => item.dataset.id);
                        document.getElementById('orderInput').value = JSON.stringify(order);
                    }
                });
            }
        </script>
        
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">crown</span>
            <h3 style="margin: 16px 0;">No Executive Positions</h3>
            <p class="font-mono-courier">Start by adding your first executive position.</p>
            <a href="/admin/executives/create" class="btn-vintage btn-vintage-primary" style="margin-top: 20px;">Add Executive Position</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .sortable-drag {
        opacity: 0.5;
    }
</style>

<?= $this->endSection() ?>