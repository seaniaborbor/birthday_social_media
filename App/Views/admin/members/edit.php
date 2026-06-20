<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/members" class="btn-vintage">← Back to Members</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">✏️ Edit Member</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div>• <?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/admin/members/update/<?= $member['id'] ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="<?= old('first_name', $member['first_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="<?= old('last_name', $member['last_name']) ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" value="<?= old('email', $member['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                <small>Only enter if you want to change the password</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Profile Photo</label>
                <div style="margin-bottom: 12px;">
                    <?php if ($member['profile_photo']): ?>
                        <div style="width: 100px; height: 100px; background: var(--color-outline); border-radius: 8px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="<?= base_url($member['profile_photo']) ?>" alt="Current" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                </div>
                <input type="file" name="profile_photo" class="form-control" accept="image/*">
                <small>Leave empty to keep current photo. Max 2MB.</small>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Birth Day *</label>
                    <input type="number" name="birth_day" class="form-control" value="<?= old('birth_day', $member['birth_day']) ?>" min="1" max="31" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Birth Month *</label>
                    <select name="birth_month" class="form-control" required>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= ($member['birth_month'] == $m) ? 'selected' : '' ?>>
                                <?= get_month_name($m) ?>
                                <?= $m == $birthMonthNumber ? '(Our Month)' : '' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Birth Year *</label>
                    <input type="number" name="birth_year" class="form-control" value="<?= old('birth_year', $member['birth_year']) ?>" min="1900" max="<?= date('Y') ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="<?= old('phone', $member['phone']) ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"><?= old('address', $member['address']) ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= old('city', $member['city']) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">County</label>
                    <input type="text" name="county" class="form-control" value="<?= old('county', $member['county']) ?>">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">District</label>
                    <input type="text" name="district" class="form-control" value="<?= old('district', $member['district']) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Occupation</label>
                    <input type="text" name="occupation" class="form-control" value="<?= old('occupation', $member['occupation']) ?>">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select</option>
                        <option value="Male" <?= ($member['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($member['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= ($member['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-control">
                        <option value="">-- Select Role --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= ($currentRoleId == $role['id']) ? 'selected' : '' ?>>
                                <?= ucfirst($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= $member['is_active'] ? 'checked' : '' ?>>
                    <span>Active Account</span>
                </label>
                
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_approved" value="1" <?= $member['is_approved'] ? 'checked' : '' ?>>
                    <span>Approved</span>
                </label>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Update Member</button>
                <a href="/admin/members" class="btn-vintage">Cancel</a>
            </div>
        </form>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <div style="text-align: center;">
            <a href="/admin/members/delete/<?= $member['id'] ?>" class="btn-vintage" style="color: var(--color-error);" onclick="return confirm('Delete this member? This cannot be undone.')">
                🗑️ Delete Member
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>