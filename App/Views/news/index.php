<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 48px;">📰 Latest News</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Stay updated with association announcements</p>
        </div>
        
        <?php if (!empty($categories)): ?>
            <form method="GET" action="/news" style="display: flex; gap: 8px;">
                <select name="category" class="form-control" style="width: auto;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category'] ?>" <?= $currentCategory == $cat['category'] ? 'selected' : '' ?>>
                            <?= $cat['category'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-vintage">Filter</button>
            </form>
        <?php endif; ?>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
        <!-- Main Content -->
        <div>
            <?php if (!empty($news)): ?>
                <?php foreach ($news as $item): ?>
                    <div class="polaroid-card" style="margin-bottom: 30px; overflow: hidden;">
                        <?php if ($item['featured_image']): ?>
                            <div style="margin-bottom: 16px;">
                                <img src="<?= base_url($item['featured_image']) ?>" alt="<?= esc($item['title']) ?>" style="width: 100%; height: auto; border-bottom: 1px solid var(--color-outline);">
                            </div>
                        <?php endif; ?>
                        
                        <div style="padding: 0 16px 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap;">
                                <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;"><?= esc($item['category']) ?></span>
                                <p class="font-mono-courier" style="font-size: 11px;">📅 <?= date('F j, Y', strtotime($item['published_at'])) ?></p>
                            </div>
                            
                            <h2 style="margin-bottom: 12px;">
                                <a href="/news/<?= $item['slug'] ?>" style="color: var(--color-text); text-decoration: none;">
                                    <?= esc($item['title']) ?>
                                </a>
                            </h2>
                            
                            <p><?= esc(substr($item['excerpt'] ?: strip_tags($item['content']), 0, 200)) ?>...</p>
                            
                            <div style="margin-top: 16px;">
                                <a href="/news/<?= $item['slug'] ?>" class="btn-vintage">Read More →</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div style="margin-top: 24px; text-align: center;">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="polaroid-card" style="text-align: center; padding: 60px;">
                    <span class="material-symbols-outlined" style="font-size: 64px;">news</span>
                    <h3 style="margin: 16px 0;">No news articles found</h3>
                    <p>Check back later for updates!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div>
            <div class="polaroid-card" style="margin-bottom: 30px;">
                <h3 style="margin-bottom: 16px;">Recent News</h3>
                <?php if (!empty($recentNews)): ?>
                    <?php foreach ($recentNews as $item): ?>
                        <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px dotted var(--color-outline);">
                            <a href="/news/<?= $item['slug'] ?>" style="text-decoration: none;">
                                <h4 style="margin-bottom: 4px; font-size: 16px;"><?= esc($item['title']) ?></h4>
                            </a>
                            <p class="font-mono-courier" style="font-size: 10px;"><?= date('M j, Y', strtotime($item['published_at'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No recent news.</p>
                <?php endif; ?>
            </div>
            
            <div class="polaroid-card">
                <h3 style="margin-bottom: 16px;">About Us</h3>
                <p class="font-mono-courier" style="font-size: 13px;">
                    <?= get_association_name() ?> is dedicated to uniting and serving the <?= get_birth_month() ?> born community.
                </p>
                <div class="stamp" style="position: static; margin-top: 16px;"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>