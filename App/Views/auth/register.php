<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
    <div class="polaroid-card" style="padding: 40px;">
        <div class="stamp" style="position: static; display: inline-block; margin-bottom: 20px;"></div>
        
        <h2 style="font-size: 28px; margin-bottom: 8px;">Join the <?= $birthMonthConfigured ?> Born Association</h2>
        <p class="font-mono-courier" style="font-size: 13px; opacity: 0.7; margin-bottom: 20px;">
            Registration is open only for individuals born in <?= $birthMonthConfigured ?>
        </p>
        
        <div class="alert alert-info" style="margin: 20px 0;">
            <span class="material-symbols-outlined" style="vertical-align: middle;">info</span>
            Birth month validation: You must be born in <strong><?= $birthMonthConfigured ?></strong> to register.
        </div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div>• <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/auth/do-register">
            <?= csrf_field() ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="<?= old('first_name') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="<?= old('last_name') ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Birth Day *</label>
                    <input type="number" name="birth_day" class="form-control" placeholder="1-31" value="<?= old('birth_day') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Birth Month *</label>
                    <select name="birth_month" class="form-control" required>
                        <option value="">Select Month</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= old('birth_month') == $m ? 'selected' : '' ?> <?= $m == $birthMonthNumber ? 'style="font-weight: bold;"' : '' ?>>
                                <?= get_month_name($m) ?>
                                <?= $m == $birthMonthNumber ? '(Our Month)' : '' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Birth Year *</label>
                    <input type="number" name="birth_year" class="form-control" placeholder="YYYY" value="<?= old('birth_year') ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"><?= old('address') ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= old('city') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control" value="<?= old('county') ?>">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">District</label>
                    <input type="text" name="district" class="form-control" value="<?= old('district') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Occupation</label>
                    <input type="text" name="occupation" class="form-control" value="<?= old('occupation') ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select</option>
                    <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= old('gender') == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            
            <button type="submit" class="btn-vintage btn-vintage-primary" style="width: 100%; margin-top: 20px;">
                Register Now
            </button>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <p style="text-align: center; font-size: 14px;">
            Already have an account? 
            <a href="/auth/login" style="color: var(--color-primary);">Sign in</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
 