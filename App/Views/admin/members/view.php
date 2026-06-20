<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/members" class="btn-vintage">← Back to Members</a>
    </div>
    
    <div class="card-grid" style="grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Profile Card -->
        <div class="polaroid-card" style="text-align: center;">
            <div style="width: 150px; height: 150px; background: var(--color-outline); border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <?php if ($member['profile_photo']): ?>
                    <img src="<?= base_url($member['profile_photo']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <span class="material-symbols-outlined" style="font-size: 64px;">person</span>
                <?php endif; ?>
            </div>
            
            <h2><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></h2>
            
            <?php if ($isBirthMonthMember): ?>
                <div class="stamp" style="position: static; margin-top: 12px;">
                    <?= get_birth_month() ?> BORN
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 16px;">
                <?php if (!$member['is_approved']): ?>
                    <a href="/admin/members/approve/<?= $member['id'] ?>" class="btn-vintage btn-vintage-primary">Approve Member</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Details Card -->
        <div class="polaroid-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <h3 style="margin-bottom: 20px;">Member Information</h3>
                <a href="/admin/members/edit/<?= $member['id'] ?>" class="btn-vintage" style="padding: 4px 12px;">Edit</a>
            </div>
            
            <div class="ledger-lines" style="padding: 16px;">
                <p><strong>📧 Email:</strong> <?= esc($member['email']) ?></p>
                <p><strong>📞 Phone:</strong> <?= esc($member['phone']) ?: '-' ?></p>
                <p><strong>🎂 Birthday:</strong> <?= get_month_name($member['birth_month']) ?> <?= $member['birth_day'] ?>, <?= $member['birth_year'] ?></p>
                <p><strong>📍 Location:</strong> <?= esc($member['city']) ?>, <?= esc($member['county']) ?></p>
                <p><strong>🗺️ District:</strong> <?= esc($member['district']) ?: '-' ?></p>
                <p><strong>💼 Occupation:</strong> <?= esc($member['occupation']) ?: '-' ?></p>
                <p><strong>👤 Gender:</strong> <?= esc($member['gender']) ?: '-' ?></p>
                <p><strong>📅 Member Since:</strong> <?= date('F j, Y', strtotime($member['created_at'])) ?></p>
                <p><strong>🔑 Roles:</strong> 
                    <?php foreach ($roles as $role): ?>
                        <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;"><?= ucfirst($role['name']) ?></span>
                    <?php endforeach; ?>
                </p>
            </div>
            
            <hr class="dotted-divider">
            
            <div style="display: flex; gap: 12px; justify-content: center;">
                <a href="mailto:<?= $member['email'] ?>" class="btn-vintage">📧 Send Email</a>
                <?php if ($member['id'] != session()->get('memberId')): ?>
                    <a href="/admin/members/delete/<?= $member['id'] ?>" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Are you sure?')">🗑️ Delete Member</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>