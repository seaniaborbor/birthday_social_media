<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="margin-bottom: 20px;">
        <a href="/gallery" class="btn-vintage">← Back to Albums</a>
    </div>
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 42px;"><?= esc($album['title']) ?></h1>
        <?php if ($album['description']): ?>
            <p class="font-mono-courier" style="opacity: 0.7;"><?= esc($album['description']) ?></p>
        <?php endif; ?>
        <div class="stamp" style="position: static; display: inline-block; margin-top: 16px;">
            <?= count($photos) ?> PHOTOS
        </div>
    </div>
    
    <?php if (!empty($photos)): ?>
        <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
            <?php 
            $rotations = ['rotate-1', 'rotate-2', '-rotate-1', '-rotate-2'];
            foreach ($photos as $index => $photo): 
                $rotation = $rotations[$index % count($rotations)];
            ?>
                <div class="polaroid-card <?= $rotation ?>">
                    <div style="cursor: pointer;" onclick="openModal('<?= base_url($photo['filename']) ?>', '<?= esc(addslashes($photo['caption'])) ?>')">
                        <div style="height: 220px; overflow: hidden; margin-bottom: 12px;">
                            <img src="<?= base_url($photo['filename']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <?php if ($photo['caption']): ?>
                            <p style="font-size: 13px; text-align: center;"><?= esc($photo['caption']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">photo_camera</span>
            <h3 style="margin: 16px 0;">No Photos Yet</h3>
            <p>This album doesn't have any photos yet.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10000; align-items: center; justify-content: center; cursor: pointer;">
    <div style="max-width: 90%; max-height: 90%;">
        <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 80vh; margin: 0 auto; display: block;">
        <p id="modalCaption" style="color: white; text-align: center; margin-top: 16px; font-family: 'Courier Prime', monospace;"></p>
    </div>
    <button onclick="closeModal()" style="position: absolute; top: 20px; right: 40px; background: none; border: none; color: white; font-size: 40px; cursor: pointer;">&times;</button>
</div>

<script>
function openModal(imageSrc, caption) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalCaption').textContent = caption || '';
    document.getElementById('lightboxModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('lightboxModal').style.display = 'none';
}

document.getElementById('lightboxModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>