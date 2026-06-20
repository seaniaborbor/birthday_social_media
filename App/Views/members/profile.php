<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>My Profile</h1>
        <a href="/" class="btn-vintage">← Back to Home</a>
    </div>
    
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
    
    <div class="card-grid" style="grid-template-columns: 1fr 2fr; gap: 30px;">
        <!-- Profile Photo Section -->
        <div class="polaroid-card" style="text-align: center;">
            <h3 style="margin-bottom: 20px;">Profile Picture</h3>
            
            <div id="profile-photo-display" style="width: 200px; height: 200px; background: var(--color-outline); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                <?php if ($member['profile_photo']): ?>
                    <img src="<?= base_url($member['profile_photo']) ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <span class="material-symbols-outlined" style="font-size: 80px;">person</span>
                <?php endif; ?>
                <div id="photo-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.3s; cursor: pointer;">
                    <span class="material-symbols-outlined" style="font-size: 40px; color: white; opacity: 0; transition: opacity 0.3s;">camera_alt</span>
                </div>
            </div>
            
            <h3><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></h3>
            <p class="font-mono-courier" style="font-size: 12px; margin-top: 8px;">
                Member since <?= date('F Y', strtotime($member['created_at'])) ?>
            </p>
            
            <?php if ($isBirthMonthMember): ?>
                <div class="stamp" style="position: static; margin-top: 16px;">
                    <?= $birthMonth ?> BORN
                </div>
            <?php endif; ?>
            
            <?php foreach ($roles as $role): ?>
                <div style="margin-top: 8px;">
                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">
                        <?= ucfirst($role['name']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
            
            <style>
                #profile-photo-display:hover #photo-overlay {
                    background: rgba(0,0,0,0.5);
                }
                
                #profile-photo-display:hover #photo-overlay span {
                    opacity: 1;
                }
                
                #photo-upload-input {
                    display: none;
                }
                
                .photo-upload-zone {
                    border: 2px dashed var(--color-outline);
                    border-radius: 8px;
                    padding: 20px;
                    text-align: center;
                    cursor: pointer;
                    transition: all 0.3s;
                    background: var(--color-surface);
                }
                
                .photo-upload-zone:hover {
                    border-color: var(--color-text);
                    background: rgba(0,0,0,0.05);
                }
                
                .photo-upload-zone.dragover {
                    border-color: var(--color-primary, #333);
                    background: rgba(0,0,0,0.1);
                }
            </style>
        </div>
        
        <!-- Edit Profile Form -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Edit Profile Information</h3>
            
            <form method="POST" action="/members/update-profile" enctype="multipart/form-data" id="profile-form">
                <?= csrf_field() ?>
                
                <!-- Photo Upload Section -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Profile Picture</label>
                    <div class="photo-upload-zone" id="upload-zone" onclick="document.getElementById('profile_photo').click()">
                        <span class="material-symbols-outlined" style="font-size: 32px; display: block; margin-bottom: 8px;">photo_camera</span>
                        <p style="margin: 0; font-size: 14px;"><strong>Click or drag to upload</strong></p>
                        <small style="font-size: 11px; opacity: 0.7; display: block; margin-top: 4px;">JPG, PNG up to 2MB</small>
                    </div>
                    <input type="file" id="profile_photo" name="profile_photo" class="form-control" accept="image/*" style="display: none;">
                    <small style="font-size: 11px; margin-top: 8px; display: block;">Leave empty to keep current photo</small>
                </div>
                
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
                
                <?php if ($canEditBirthMonth): ?>
                    <div class="alert alert-info" style="margin: 16px 0;">
                        <span class="material-symbols-outlined" style="vertical-align: middle;">admin_panel_settings</span>
                        Admin override enabled - You can edit birth date information.
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Birth Day</label>
                            <input type="number" name="birth_day" class="form-control" value="<?= $member['birth_day'] ?>" min="1" max="31">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Birth Month</label>
                            <select name="birth_month" class="form-control">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?= $m ?>" <?= $member['birth_month'] == $m ? 'selected' : '' ?>>
                                        <?= get_month_name($m) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Birth Year</label>
                            <input type="number" name="birth_year" class="form-control" value="<?= $member['birth_year'] ?>">
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label class="form-label">Phone</label>
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
                
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select</option>
                        <option value="Male" <?= $member['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $member['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $member['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-vintage btn-vintage-primary">Update Profile</button>
            </form>
        </div>
    </div>
    
    <!-- Change Password Section -->
    <div class="polaroid-card" style="margin-top: 30px;">
        <h3 style="margin-bottom: 20px;">Change Password</h3>
        
        <form method="POST" action="/members/change-password">
            <?= csrf_field() ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div></div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                    <small style="font-size: 11px;">Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>
            
            <button type="submit" class="btn-vintage">Change Password</button>
        </form>
    </div>
</div>

<script>
    // Profile photo upload functionality
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('profile_photo');
    const photoDisplay = document.getElementById('profile-photo-display');
    
    if (uploadZone && fileInput) {
        // Click to upload
        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files);
        });
        
        // Drag and drop
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        
        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
        });
        
        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            handleFileSelect(e.dataTransfer.files);
        });
        
        function handleFileSelect(files) {
            if (files && files.length > 0) {
                const file = files[0];
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }
                
                // Set file to input
                fileInput.files = files;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoDisplay.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">';
                };
                reader.readAsDataURL(file);
                
                // Update upload zone text
                uploadZone.innerHTML = '<span class="material-symbols-outlined" style="font-size: 32px; display: block; margin-bottom: 8px;">check_circle</span><p style="margin: 0; font-size: 14px;"><strong>' + file.name + '</strong></p><small style="font-size: 11px; opacity: 0.7;">Ready to upload</small>';
            }
        }
    }
</script>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>