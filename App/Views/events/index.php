<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px;">📅 Upcoming Events</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">Join us at our upcoming gatherings and celebrations</p>
    </div>
    
    <?php if (!empty($events)): ?>
        <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
            <?php foreach ($events as $event): ?>
                <div class="polaroid-card <?= 'rotate-' . rand(1, 2) ?>">
                    <?php if ($event['featured_image']): ?>
                        <div style="height: 200px; overflow: hidden; margin-bottom: 16px;">
                            <img src="<?= base_url($event['featured_image']) ?>" alt="<?= esc($event['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                    
                    <div style="padding: 0 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">
                                <?= date('M j', strtotime($event['event_date'])) ?>
                            </span>
                            <?php if ($event['is_featured']): ?>
                                <span class="stamp" style="position: static; font-size: 8px;">FEATURED</span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 style="margin-bottom: 8px;"><?= esc($event['title']) ?></h3>
                        <p class="font-mono-courier" style="font-size: 12px;">📍 <?= esc($event['venue']) ?></p>
                        <p style="font-size: 13px; margin-top: 12px;"><?= esc(substr(strip_tags($event['description']), 0, 100)) ?>...</p>
                        
                        <div style="margin-top: 16px;">
                            <a href="/events/<?= $event['slug'] ?>" class="btn-vintage">View Details →</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($pager)): ?>
            <div style="margin-top: 40px; text-align: center;">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">event</span>
            <h3 style="margin: 16px 0;">No Upcoming Events</h3>
            <p class="font-mono-courier">Check back later for exciting events from <?= get_association_name() ?>!</p>
        </div>
    <?php endif; ?>
    
    <!-- Past Events Section -->
    <?php if (!empty($pastEvents)): ?>
        <div style="margin-top: 60px;">
            <h2 style="margin-bottom: 20px;">Past Events</h2>
            <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($pastEvents as $event): ?>
                    <div class="polaroid-card" style="opacity: 0.8;">
                        <h4><?= esc($event['title']) ?></h4>
                        <p class="font-mono-courier" style="font-size: 11px;"><?= date('F j, Y', strtotime($event['event_date'])) ?></p>
                        <p style="font-size: 12px;">📍 <?= esc($event['venue']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>