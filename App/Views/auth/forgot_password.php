<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 500px; margin: 60px auto; padding: 0 20px;">
    <div class="polaroid-card" style="padding: 40px;">
        <div class="stamp" style="position: static; display: inline-block; margin-bottom: 20px;"></div>
        
        <h2 style="font-size: 28px; margin-bottom: 8px;">Reset Password</h2>
        <p class="font-mono-courier" style="font-size: 13px; opacity: 0.7; margin-bottom: 30px;">
            Enter your email to receive a reset link
        </p>
        
        <hr class="dotted-divider">
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/auth/do-forgot-password">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>
            
            <button type="submit" class="btn-vintage btn-vintage-primary" style="width: 100%;">
                Send Reset Link
            </button>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <p style="text-align: center;">
            <a href="/auth/login" style="color: var(--color-primary);">Back to Login</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
 