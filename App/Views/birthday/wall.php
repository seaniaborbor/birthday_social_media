<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px; margin-bottom: 8px;">💝 Birthday Wall</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">
            Sending love to our <?= $birthMonth ?> born members
        </p>
        <div class="stamp" style="position: static; display: inline-block; margin-top: 16px;">
            <?= $wishCount ?> WISHES SENT
        </div>
    </div>
    
    <!-- Featured Wish -->
    <?php if ($featuredWish): ?>
        <div class="polaroid-card" style="margin-bottom: 40px; background: linear-gradient(135deg, rgba(29,78,216,0.05) 0%, rgba(234,179,8,0.05) 100%);">
            <div style="text-align: center;">
                <span class="stamp" style="position: static; margin-bottom: 12px; display: inline-block;">✨ FEATURED WISH ✨</span>
                <div style="width: 84px; height: 84px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; background: var(--color-outline); display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($featuredWish['profile_photo'])): ?>
                        <img src="<?= base_url($featuredWish['profile_photo']) ?>" alt="<?= esc($featuredWish['first_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span class="material-symbols-outlined" style="font-size: 40px;">person</span>
                    <?php endif; ?>
                </div>
                <p style="font-size: 20px; font-style: italic; margin: 20px 0;">"<?= esc($featuredWish['message']) ?>"</p>
                <p class="font-mono-courier">
                    — <?= esc($featuredWish['first_name']) ?> wishing <?= esc($featuredWish['recipient_name']) ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Submit Wish Form -->
    <?php if (session()->get('isLoggedIn')): ?>
        <div class="polaroid-card" style="margin-bottom: 40px;">
            <h3 style="margin-bottom: 16px;">✍️ Leave a Birthday Wish</h3>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-error">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <div>• <?= $error ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/birthday/submit-wish">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">Recipient Name</label>
                    <input type="text" name="recipient_name" class="form-control" placeholder="e.g., John Doe" value="<?= old('recipient_name') ?>" required>
                    <small style="font-size: 11px;">Name of the birthday person (can be a member or anyone)</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Your Wish Message</label>
                    <textarea name="message" class="form-control" rows="4" placeholder="Write your birthday wish here..." required><?= old('message') ?></textarea>
                    <small style="font-size: 11px;">Max 500 characters - Your wish will appear after admin approval</small>
                </div>
                
                <button type="submit" class="btn-vintage btn-vintage-primary">
                    <span class="material-symbols-outlined" style="vertical-align: middle;">send</span>
                    Send Wish
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="margin-bottom: 40px; text-align: center;">
            <a href="/auth/login" style="color: var(--color-primary);">Login</a> to leave a birthday wish for our members
        </div>
    <?php endif; ?>
    
    <!-- Wishes Display -->
    <h3 style="margin-bottom: 20px;">💌 Birthday Wishes</h3>
    
    <?php if (!empty($wishes)): ?>
        <?php foreach ($wishes as $wish): ?>
            <?php
            $rotations = ['rotate-1', 'rotate-2', '-rotate-1', '-rotate-2'];
            $rotation = $rotations[array_rand($rotations)];
            ?>
            <div class="polaroid-card <?= $rotation ?>" style="margin-bottom: 20px;">
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="text-align: center; min-width: 100px;">
                        <div style="width: 70px; height: 70px; background: var(--color-outline); border-radius: 50%; margin: 0 auto; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <?php if (!empty($wish['profile_photo'])): ?>
                                <img src="<?= base_url($wish['profile_photo']) ?>" alt="<?= esc($wish['first_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 36px;">person</span>
                            <?php endif; ?>
                        </div>
                        <p class="font-mono-courier" style="font-size: 11px; margin-top: 8px;">
                            From: <?= esc($wish['first_name']) ?>
                        </p>
                    </div>
                    
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 8px;">
                            <h4 style="margin-bottom: 4px; color: var(--color-primary);">
                                🎂 To: <?= esc($wish['recipient_name']) ?>
                                <?php if (in_array($wish['recipient_id'], $todayBirthdayIds)): ?>
                                    <span class="stamp" style="position: static; font-size: 8px; margin-left: 8px;">BIRTHDAY TODAY!</span>
                                <?php endif; ?>
                            </h4>
                            <p class="font-mono-courier" style="font-size: 11px; opacity: 0.6;">
                                <?= date('F j, Y', strtotime($wish['created_at'])) ?>
                            </p>
                        </div>
                        
                        <div class="ruled-lines" style="padding: 16px; margin-top: 12px; text-decoration: none !important;">
                            <p style="font-style: italic; text-decoration: none !important;">"<?= esc($wish['message']) ?>"</p>
                        </div>
                        
                        <div style="margin-top: 12px; text-align: right;">
                            <span class="material-symbols-outlined" style="font-size: 16px; opacity: 0.5;">favorite</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">celebration</span>
            <h3 style="margin: 16px 0;">No Birthday Wishes Yet</h3>
            <p class="font-mono-courier">Be the first to send a birthday wish to our members!</p>
            <?php if (!session()->get('isLoggedIn')): ?>
                <a href="/auth/login" class="btn-vintage" style="margin-top: 20px;">Login to Send a Wish</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>
