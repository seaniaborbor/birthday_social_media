<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <div style="margin-bottom: 20px;">
        <a href="/members/directory" class="btn-vintage">← Back to Directory</a>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <div class="card-grid" style="grid-template-columns: 1fr 2fr; gap: 30px;">
        
        <!-- Profile Photo & Reactions -->
        <div>
            <div class="polaroid-card <?= 'rotate-' . rand(1, 2) ?>" style="text-align: center;">
                <div style="width: 200px; height: 200px; background: var(--color-outline); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <?php if ($member['profile_photo']): ?>
                        <img src="<?= base_url($member['profile_photo']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span class="material-symbols-outlined" style="font-size: 80px;">person</span>
                    <?php endif; ?>
                </div>
                
                <h2><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></h2>
                
                <?php if ($isBirthMonthMember): ?>
                    <div class="stamp" style="position: static; margin-top: 16px;">
                        <?= get_birth_month() ?> BORN
                    </div>
                <?php endif; ?>
                
                <?php if ($member['occupation']): ?>
                    <p style="margin-top: 16px;">
                        <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 18px;">work</span>
                        <?= esc($member['occupation']) ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <!-- Reactions Section -->
            <?php 
            $reactionModel = new \App\Models\ProfileReactionModel();
            $reactions = $reactionModel->getReactionsForProfile($member['id']);
            $userReaction = session()->get('isLoggedIn') ? $reactionModel->getUserReaction(session()->get('memberId'), $member['id']) : null;
            ?>
            
            <div class="polaroid-card" style="margin-top: 20px; text-align: center;">
                <h3 style="margin-bottom: 16px;">💖 Show Some Love</h3>
                
                <div style="display: flex; gap: 30px; justify-content: center; margin-bottom: 20px;">
                    <!-- Like Button -->
                    <form method="POST" action="/members/react/<?= $member['id'] ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="like">
                        <button type="submit" style="background: none; border: none; cursor: pointer; text-align: center;">
                            <span class="material-symbols-outlined" style="font-size: 48px; <?= $userReaction === 'like' ? 'color: #3b82f6;' : 'color: var(--color-text-secondary);' ?>">
                                thumb_up
                            </span>
                            <div class="font-mono-courier" style="font-size: 14px;"><?= $reactions['like_count'] ?> Likes</div>
                        </button>
                    </form>
                    
                    <!-- Love Button -->
                    <form method="POST" action="/members/react/<?= $member['id'] ?>" style="display: inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="love">
                        <button type="submit" style="background: none; border: none; cursor: pointer; text-align: center;">
                            <span class="material-symbols-outlined" style="font-size: 48px; <?= $userReaction === 'love' ? 'color: #ef4444;' : 'color: var(--color-text-secondary);' ?>">
                                favorite
                            </span>
                            <div class="font-mono-courier" style="font-size: 14px;"><?= $reactions['love_count'] ?> Loves</div>
                        </button>
                    </form>
                </div>
                
                <?php if ($reactions['total_count'] > 0): ?>
                    <div class="stamp" style="position: static; margin-top: 8px;">
                        <?= $reactions['total_count'] ?> people reacted
                    </div>
                <?php endif; ?>
                
                <?php if (!session()->get('isLoggedIn')): ?>
                    <p style="margin-top: 16px; font-size: 12px;">
                        <a href="/auth/login">Login</a> to react to this profile
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Member Details -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Member Information</h3>
            
            <div class="ledger-lines" style="padding: 16px;">
                <p style="margin-bottom: 12px;">
                    <strong>🎂 Birthday:</strong> 
                    <?= get_month_name($member['birth_month']) ?> <?= $member['birth_day'] ?>, <?= $member['birth_year'] ?>
                </p>
                
                <?php if ($member['phone']): ?>
                    <p style="margin-bottom: 12px;">
                        <strong>📞 Phone:</strong> <?= esc($member['phone']) ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($member['email']): ?>
                    <p style="margin-bottom: 12px;">
                        <strong>✉️ Email:</strong> <?= esc($member['email']) ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($member['address'] || $member['city'] || $member['county']): ?>
                    <p style="margin-bottom: 12px;">
                        <strong>📍 Location:</strong><br>
                        <?= esc($member['address']) ?><br>
                        <?= esc($member['city']) ?>, <?= esc($member['county']) ?>
                        <?php if ($member['district']): ?>
                            <br>District: <?= esc($member['district']) ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                
                <p style="margin-bottom: 12px;">
                    <strong>📅 Member Since:</strong> <?= date('F Y', strtotime($member['created_at'])) ?>
                </p>
            </div>
            
            <hr class="dotted-divider">
            
            <div style="text-align: center;">
                <a href="/birthday/wall" class="btn-vintage">Send Birthday Wish →</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>