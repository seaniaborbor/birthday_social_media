<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px;">🖼️ Photo Gallery</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">Memories from our <?= get_birth_month() ?> born community</p>
    </div>
    
    <?php if (!empty($albums)): ?>
        <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
            <?php 
            $rotations = ['rotate-1', 'rotate-2', '-rotate-1', '-rotate-2'];
            foreach ($albums as $index => $album): 
                $rotation = $rotations[$index % count($rotations)];
            ?>
                <a href="/gallery/album/<?= $album['slug'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="polaroid-card <?= $rotation ?>">
                        <div style="height: 220px; background: var(--color-outline); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 16px;">
                            <?php if ($album['cover_image']): ?>
                                <img src="<?= base_url($album['cover_image']) ?>" alt="<?= esc($album['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 64px;">photo_library</span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 style="margin-bottom: 8px;"><?= esc($album['title']) ?></h3>
                        <p class="font-mono-courier" style="font-size: 12px;">
                            📸 <?= $album['photo_count'] ?> photos
                        </p>
                        <?php if ($album['description']): ?>
                            <p style="font-size: 13px; opacity: 0.7; margin-top: 8px;"><?= esc(substr($album['description'], 0, 80)) ?>...</p>
                        <?php endif; ?>
                        
                        <div class="stamp" style="position: static; margin-top: 16px;">VIEW ALBUM</div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">photo_library</span>
            <h3 style="margin: 16px 0;">No Albums Yet</h3>
            <p>Check back later for photos from our events and activities!</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>