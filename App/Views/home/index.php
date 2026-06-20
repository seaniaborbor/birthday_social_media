<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<!-- Hero Section with Banner -->
<?php if (!empty($banners)): ?>
    <?php foreach ($banners as $index => $banner): ?>
        <div class="hero-section" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); padding: 80px 0; text-align: center; color: white; position: relative; margin-bottom: 40px;">
            <?php if ($banner['image']): ?>
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.3;">
                    <img src="<?= base_url($banner['image']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            <?php endif; ?>
            <div class="container" style="position: relative; z-index: 2;">
                <h1 style="font-size: 48px; margin-bottom: 16px; font-weight: 900;"><?= esc($banner['title']) ?></h1>
                <?php if ($banner['subtitle']): ?>
                    <p style="font-size: 20px; margin-bottom: 24px; opacity: 0.95;"><?= esc($banner['subtitle']) ?></p>
                <?php endif; ?>
                <?php if ($banner['button_text'] && $banner['button_link']): ?>
                    <a href="<?= $banner['button_link'] ?>" class="btn-vintage" style="background: white; color: var(--color-primary); border-color: white;">
                        <?= esc($banner['button_text']) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php break; // Show only first banner ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="hero-section" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); padding: 80px 0; text-align: center; color: white; margin-bottom: 40px;">
        <div class="container">
            <h1 style="font-size: 48px; margin-bottom: 16px; font-weight: 900;"><?= $associationName ?></h1>
            <p style="font-size: 20px; margin-bottom: 24px;"><?= $motto ?></p>
            <a href="/auth/register" class="btn-vintage" style="background: white; color: var(--color-primary); border-color: white;">Join Us Today</a>
        </div>
    </div>
<?php endif; ?>

<div class="container">
    <!-- Today's Birthdays Section -->
    <?php if (!empty($todaysBirthdays)): ?>
        <div class="polaroid-card" style="margin-bottom: 40px; background: var(--color-primary); color: white; text-align: center; padding: 30px;">
            <h2 style="font-size: 32px; margin-bottom: 8px;">🎂 Happy Birthday! 🎂</h2>
            <p class="font-mono-courier" style="font-size: 16px; opacity: 0.9;">Celebrating our members born today</p>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-top: 20px;">
                <?php foreach ($todaysBirthdays as $birthday): ?>
                    <div style="text-align: center;">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <span class="material-symbols-outlined" style="font-size: 48px;">celebration</span>
                        </div>
                        <p style="margin-top: 8px; font-weight: bold;"><?= esc($birthday['first_name']) ?> <?= esc($birthday['last_name']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Progress Tracker -->
    <div class="card-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 40px;">
        <div class="polaroid-card">
            <h3 style="margin-bottom: 16px;">Membership Progress</h3>
            <div class="progress-bar-vintage" style="margin: 16px 0;">
                <div class="progress-fill" style="width: <?= $progressData['percentage'] ?>%;"></div>
            </div>
            <p class="font-mono-courier" style="font-size: 14px;">
                <?= $progressData['total_members'] ?> / <?= $progressData['target'] ?> Members
            </p>
            <p class="font-mono-courier" style="font-size: 12px; margin-top: 8px; opacity: 0.7;">
                <?= $progressData['percentage'] ?>% of target reached
            </p>
        </div>
        
        <div class="polaroid-card">
            <h3 style="margin-bottom: 16px;">Next <?= $birthMonth ?> Celebration</h3>
            <div class="countdown-timer" style="text-align: center; margin: 16px 0;">
                <?= $progressData['days_until_birth_month'] ?> days
            </div>
            <p class="font-mono-courier" style="text-align: center; font-size: 14px;">
                Until <?= $birthMonth ?> Birthdays Begin
            </p>
            <?php if ($progressData['is_birth_month']): ?>
                <div class="stamp" style="position: static; margin-top: 16px; text-align: center;">
                    IT'S OUR MONTH!
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Member Spotlight with Reactions -->
    <?php if ($memberSpotlight): 
        // Get reactions for the spotlight member
        $reactionModel = new \App\Models\ProfileReactionModel();
        $reactions = $reactionModel->getReactionsForProfile($memberSpotlight['id']);
        $userReaction = session()->get('isLoggedIn') ? $reactionModel->getUserReaction(session()->get('memberId'), $memberSpotlight['id']) : null;
    ?>
        <div class="polaroid-card" style="margin-bottom: 40px; display: flex; flex-wrap: wrap; gap: 30px; align-items: center;">
            <div style="flex: 1; min-width: 200px; text-align: center;">
                <a href="/members/view/<?= $memberSpotlight['id'] ?>" style="text-decoration: none;">
                    <div style="width: 150px; height: 150px; background: var(--color-outline); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <?php if ($memberSpotlight['profile_photo']): ?>
                            <img src="<?= base_url($memberSpotlight['profile_photo']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <span class="material-symbols-outlined" style="font-size: 64px;">person</span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <div style="flex: 2;">
                <div class="stamp" style="position: static; display: inline-block; margin-bottom: 12px;">MEMBER SPOTLIGHT</div>
                <h2><?= esc($memberSpotlight['first_name']) ?> <?= esc($memberSpotlight['last_name']) ?></h2>
                <p class="font-mono-courier" style="margin: 8px 0;"><?= esc($memberSpotlight['occupation']) ?> | <?= esc($memberSpotlight['county']) ?></p>
                <p style="margin-top: 12px;">A valued member of our <?= $birthMonth ?> born community.</p>
                
                <!-- Reaction Buttons for Spotlight -->
                <div style="display: flex; gap: 20px; margin-top: 20px; justify-content: flex-start;">
                    <!-- Like Button -->
                    <form method="POST" action="/members/react/<?= $memberSpotlight['id'] ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="like">
                        <button type="submit" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="font-size: 24px; <?= $userReaction === 'like' ? 'color: #3b82f6;' : 'color: var(--color-text-secondary);' ?>">
                                thumb_up
                            </span>
                            <span class="font-mono-courier" style="font-size: 12px;"><?= $reactions['like_count'] ?></span>
                        </button>
                    </form>
                    
                    <!-- Love Button -->
                    <form method="POST" action="/members/react/<?= $memberSpotlight['id'] ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="love">
                        <button type="submit" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                            <span class="material-symbols-outlined" style="font-size: 24px; <?= $userReaction === 'love' ? 'color: #ef4444;' : 'color: var(--color-text-secondary);' ?>">
                                favorite
                            </span>
                            <span class="font-mono-courier" style="font-size: 12px;"><?= $reactions['love_count'] ?></span>
                        </button>
                    </form>
                    
                    <a href="/members/view/<?= $memberSpotlight['id'] ?>" class="btn-vintage" style="padding: 4px 16px; font-size: 12px;">View Profile →</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Upcoming Events & Latest News Grid -->
    <div class="card-grid" style="margin-bottom: 40px;">
        <div>
            <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined">event</span> Upcoming Events
            </h3>
            <?php if (!empty($upcomingEvents)): ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="polaroid-card" style="margin-bottom: 16px; padding: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <h4 style="margin-bottom: 8px;"><?= esc($event['title']) ?></h4>
                                <p class="font-mono-courier" style="font-size: 12px;">
                                    📍 <?= esc($event['venue']) ?>
                                </p>
                                <p class="font-mono-courier" style="font-size: 12px;">
                                    📅 <?= date('F j, Y', strtotime($event['event_date'])) ?>
                                    <?php if ($event['event_time']): ?>
                                        at <?= date('g:i A', strtotime($event['event_time'])) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <?php if ($event['is_featured']): ?>
                                <div class="stamp" style="position: static;">FEATURED</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="font-mono-courier" style="opacity: 0.7;">No upcoming events scheduled.</p>
            <?php endif; ?>
            <a href="/events" class="btn-vintage" style="margin-top: 16px; text-align: center; display: block;">View All Events →</a>
        </div>
        
        <div>
            <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined">news</span> Latest News
            </h3>
            <?php if (!empty($latestNews)): ?>
                <?php foreach ($latestNews as $news): ?>
                    <div class="polaroid-card" style="margin-bottom: 16px; padding: 16px;">
                        <h4 style="margin-bottom: 8px;"><?= esc($news['title']) ?></h4>
                        <p class="font-mono-courier" style="font-size: 11px; opacity: 0.7; margin-bottom: 8px;">
                            <?= date('F j, Y', strtotime($news['published_at'])) ?> | <?= esc($news['category']) ?>
                        </p>
                        <p style="font-size: 14px;"><?= esc(substr($news['excerpt'], 0, 100)) ?>...</p>
                        <a href="/news/<?= $news['slug'] ?>" style="color: var(--color-primary); font-size: 13px; text-decoration: none; margin-top: 8px; display: inline-block;">Read more →</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="font-mono-courier" style="opacity: 0.7;">No news articles published.</p>
            <?php endif; ?>
            <a href="/news" class="btn-vintage" style="margin-top: 16px; text-align: center; display: block;">View All News →</a>
        </div>
    </div>
    
    <!-- Leadership Section -->
    <?php if (!empty($leadership)): ?>
        <div style="margin-bottom: 40px;">
            <h3 style="margin-bottom: 20px; text-align: center;">Our Leadership</h3>
            <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                <?php foreach (array_slice($leadership, 0, 4) as $leader): ?>
                    <div class="polaroid-card" style="text-align: center;">
                        <div style="width: 100px; height: 100px; background: var(--color-outline); border-radius: 50%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <?php if ($leader['profile_photo']): ?>
                                <img src="<?= base_url($leader['profile_photo']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 48px;">crown</span>
                            <?php endif; ?>
                        </div>
                        <h4 style="margin-bottom: 4px;"><?= esc($leader['first_name']) ?> <?= esc($leader['last_name']) ?></h4>
                        <p class="font-mono-courier" style="font-size: 12px; color: var(--color-primary);"><?= esc($leader['position']) ?></p>
                        <p style="font-size: 11px; margin-top: 8px;"><?= esc($leader['occupation']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Upcoming Birthdays Teaser -->
    <?php if (!empty($upcomingBirthdays)): ?>
        <div class="polaroid-card" style="background: var(--color-surface);">
            <h3 style="margin-bottom: 16px;">🎈 Upcoming <?= $birthMonth ?> Birthdays</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                <?php foreach (array_slice($upcomingBirthdays, 0, 6) as $upcoming): ?>
                    <div style="flex: 1; min-width: 120px; text-align: center; padding: 12px; background: rgba(0,0,0,0.03);">
                        <span class="material-symbols-outlined" style="font-size: 32px;">cake</span>
                        <p style="font-weight: bold; margin: 8px 0;"><?= esc($upcoming['member']['first_name']) ?></p>
                        <p class="font-mono-courier" style="font-size: 11px;">in <?= $upcoming['days_until'] ?> days</p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="/birthday/calendar" class="btn-vintage">View Full Birthday Calendar →</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>