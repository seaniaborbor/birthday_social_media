<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div style="max-width: 500px; margin: 60px auto; padding: 0 20px;">
    <div class="polaroid-card" style="padding: 40px;">
        <div class="stamp" style="position: static; display: inline-block; margin-bottom: 20px;"></div>
        
        <h2 style="font-size: 28px; margin-bottom: 8px;">Welcome Back</h2>
        <p class="font-mono-courier" style="font-size: 13px; opacity: 0.7; margin-bottom: 30px;">
            Sign in to your <?= get_birth_month() ?> Born account
        </p>
        
        <hr class="dotted-divider">
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error" style="margin: 20px 0;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="margin: 20px 0;">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>
        
        <form method="POST" action="/auth/do-login" style="margin-top: 20px;">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="/auth/forgot-password" style="color: var(--color-primary); font-size: 13px; text-decoration: none;">
                    Forgot Password?
                </a>
            </div>
            
            <button type="submit" class="btn-vintage btn-vintage-primary" style="width: 100%;">
                Sign In
            </button>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <p style="text-align: center; font-size: 14px;">
            Don't have an account? 
            <a href="/auth/register" style="color: var(--color-primary);">Register here</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
 