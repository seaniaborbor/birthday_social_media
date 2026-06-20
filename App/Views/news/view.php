<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    
    <div class="polaroid-card" style="padding: 40px;">
        <div style="margin-bottom: 20px;">
            <a href="/news" class="btn-vintage" style="display: inline-block;">← Back to News</a>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap;">
            <span class="btn-vintage" style="padding: 4px 12px;"><?= esc($news['category']) ?></span>
            <p class="font-mono-courier" style="font-size: 12px;">
                📅 <?= date('F j, Y', strtotime($news['published_at'])) ?> | 
                👁️ <?= number_format($news['views']) ?> views
            </p>
        </div>
        
        <h1 style="font-size: 42px; margin-bottom: 20px;"><?= esc($news['title']) ?></h1>
        
        <?php if ($news['featured_image']): ?>
            <div style="margin: 20px 0;">
                <img src="<?= base_url($news['featured_image']) ?>" alt="<?= esc($news['title']) ?>" style="width: 100%; height: auto; border: 1px solid var(--color-outline);">
            </div>
        <?php endif; ?>
        
        <div class="ledger-lines" style="padding: 20px; margin: 20px 0;">
            <?= $news['content'] ?>
        </div>
        
        <hr class="dotted-divider">
        
        <div class="stamp" style="position: static; display: inline-block;">
            <?= get_association_name() ?>
        </div>
    </div>
    
    <!-- Related Articles -->
    <?php if (!empty($relatedNews)): ?>
        <div style="margin-top: 40px;">
            <h3 style="margin-bottom: 20px;">Related Articles</h3>
            <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <?php foreach ($relatedNews as $related): ?>
                    <div class="polaroid-card">
                        <a href="/news/<?= $related['slug'] ?>" style="text-decoration: none; color: inherit;">
                            <?php if ($related['featured_image']): ?>
                                <div style="margin-bottom: 12px;">
                                    <img src="<?= base_url($related['featured_image']) ?>" alt="" style="width: 100%; height: 150px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                            <h4><?= esc($related['title']) ?></h4>
                            <p class="font-mono-courier" style="font-size: 11px; margin-top: 8px;"><?= date('M j, Y', strtotime($related['published_at'])) ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>