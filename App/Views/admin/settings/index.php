<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 32px;">⚙️ System Settings</h1>
        <div class="stamp" style="position: static;">CONFIGURE</div>
    </div>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <!-- Main Settings Form -->
    <form method="POST" action="/admin/settings/update" id="mainSettingsForm">
        <?= csrf_field() ?>
        
        <!-- General Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">🏢 General Settings</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Association Name</label>
                    <input type="text" name="association_name" class="form-control" value="<?= old('association_name', setting('association_name')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Birth Month Name</label>
                    <input type="text" name="birth_month" class="form-control" value="<?= old('birth_month', setting('birth_month')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Birth Month Number (1-12)</label>
                    <input type="number" name="birth_month_number" class="form-control" value="<?= old('birth_month_number', setting('birth_month_number', 9)) ?>" min="1" max="12">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Motto/Slogan</label>
                    <input type="text" name="motto" class="form-control" value="<?= old('motto', setting('motto')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Membership Target</label>
                    <input type="number" name="membership_target" class="form-control" value="<?= old('membership_target', setting('membership_target', 1000)) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Association Email</label>
                    <input type="email" name="association_email" class="form-control" value="<?= old('association_email', setting('association_email')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Association Phone</label>
                    <input type="text" name="association_phone" class="form-control" value="<?= old('association_phone', setting('association_phone')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Association Address</label>
                    <textarea name="association_address" class="form-control" rows="2"><?= old('association_address', setting('association_address')) ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Branding Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">🎨 Branding & Colors</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Primary Color</label>
                    <input type="color" name="primary_color" class="form-control" style="height: 50px;" value="<?= old('primary_color', setting('primary_color', '#1d4ed8')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Secondary Color</label>
                    <input type="color" name="secondary_color" class="form-control" style="height: 50px;" value="<?= old('secondary_color', setting('secondary_color', '#eab308')) ?>">
                </div>
            </div>
            
            <hr class="dotted-divider">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Current Logo</label>
                    <?php if (setting('logo')): ?>
                        <div style="margin: 10px 0;">
                            <img src="<?= base_url(setting('logo')) ?>" alt="Logo" style="max-width: 150px; border: 1px solid var(--color-outline); padding: 8px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Current Favicon</label>
                    <?php if (setting('favicon')): ?>
                        <div style="margin: 10px 0;">
                            <img src="<?= base_url(setting('favicon')) ?>" alt="Favicon" style="width: 32px; height: 32px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- SMTP Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">📧 SMTP / Email Settings</h3>
            
            <div class="alert alert-info" style="margin-bottom: 20px;">
                Configure SMTP settings to enable email notifications (birthday wishes, password reset, etc.)
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">SMTP Host</label>
                    <input type="text" name="smtp_host" class="form-control" value="<?= old('smtp_host', setting('smtp_host')) ?>" placeholder="smtp.gmail.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">SMTP Port</label>
                    <input type="number" name="smtp_port" class="form-control" value="<?= old('smtp_port', setting('smtp_port', 587)) ?>" placeholder="587">
                </div>
                
                <div class="form-group">
                    <label class="form-label">SMTP Username</label>
                    <input type="text" name="smtp_user" class="form-control" value="<?= old('smtp_user', setting('smtp_user')) ?>" placeholder="your-email@gmail.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">SMTP Password</label>
                    <input type="password" name="smtp_pass" class="form-control" value="<?= old('smtp_pass', setting('smtp_pass')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Encryption</label>
                    <select name="smtp_encryption" class="form-control">
                        <option value="tls" <?= setting('smtp_encryption') == 'tls' ? 'selected' : '' ?>>TLS</option>
                        <option value="ssl" <?= setting('smtp_encryption') == 'ssl' ? 'selected' : '' ?>>SSL</option>
                        <option value="" <?= setting('smtp_encryption') == '' ? 'selected' : '' ?>>None</option>
                    </select>
                </div>
            </div>
            
            <hr class="dotted-divider">
            
            <div>
                <label class="form-label">Test Email Configuration</label>
                <div style="display: flex; gap: 12px; align-items: flex-end;">
                    <input type="email" name="test_email" id="test_email" class="form-control" placeholder="Enter email to test">
                    <button type="button" onclick="sendTestEmail()" class="btn-vintage">Send Test Email</button>
                </div>
            </div>
        </div>
        
        <!-- SEO Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">🔍 SEO Settings</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Site Title</label>
                    <input type="text" name="site_title" class="form-control" value="<?= old('site_title', setting('site_title')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Site Description</label>
                    <textarea name="site_description" class="form-control" rows="2"><?= old('site_description', setting('site_description')) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Site Keywords</label>
                    <input type="text" name="site_keywords" class="form-control" value="<?= old('site_keywords', setting('site_keywords')) ?>" placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>
        </div>
        
        <!-- Social Media Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">🌐 Social Media Links</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control" value="<?= old('facebook_url', setting('facebook_url')) ?>" placeholder="https://facebook.com/your-page">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Twitter/X URL</label>
                    <input type="url" name="twitter_url" class="form-control" value="<?= old('twitter_url', setting('twitter_url')) ?>" placeholder="https://twitter.com/your-handle">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control" value="<?= old('instagram_url', setting('instagram_url')) ?>" placeholder="https://instagram.com/your-handle">
                </div>
                
                <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="form-control" value="<?= old('linkedin_url', setting('linkedin_url')) ?>" placeholder="https://linkedin.com/company/your-page">
                </div>
            </div>
        </div>
        
        <!-- Security Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">🔒 Security Settings</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="checkbox" name="admin_override" value="1" <?= setting('admin_override') ? 'checked' : '' ?>>
                    <span>Allow Admin Override (allow registration of non-birth month members)</span>
                </label>
                
                <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="checkbox" name="require_approval" value="1" <?= setting('require_approval', true) ? 'checked' : '' ?>>
                    <span>Require Admin Approval for New Registrations</span>
                </label>
            </div>
        </div>
        
        <!-- Contact Settings -->
        <div class="polaroid-card" style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px;">🗺️ Contact Page Settings</h3>
            
            <div class="form-group">
                <label class="form-label">Google Maps Embed Code</label>
                <textarea name="contact_map_embed" class="form-control" rows="4" placeholder="<iframe src='...'></iframe>"><?= old('contact_map_embed', setting('contact_map_embed')) ?></textarea>
                <small>Paste your Google Maps embed iframe code here</small>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 16px;">
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="contact_latitude" class="form-control" value="<?= old('contact_latitude', setting('contact_latitude', '6.290743')) ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="contact_longitude" class="form-control" value="<?= old('contact_longitude', setting('contact_longitude', '-10.761421')) ?>">
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div style="text-align: center; margin-top: 30px;">
            <button type="submit" class="btn-vintage btn-vintage-primary" style="padding: 12px 40px; font-size: 16px;">
                💾 Save All Settings
            </button>
        </div>
    </form>
    
    <!-- Separate Forms for Logo and Favicon (Outside Main Form) -->
    <div class="polaroid-card" style="margin-bottom: 30px;">
        <h3 style="margin-bottom: 20px;">📸 Upload Logo</h3>
        <form method="POST" action="/admin/settings/upload-logo" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <input type="file" name="logo" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn-vintage">Upload Logo</button>
            </div>
        </form>
    </div>
    
    <div class="polaroid-card" style="margin-bottom: 30px;">
        <h3 style="margin-bottom: 20px;">⭐ Upload Favicon</h3>
        <form method="POST" action="/admin/settings/upload-favicon" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <input type="file" name="favicon" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn-vintage">Upload Favicon</button>
            </div>
        </form>
    </div>
</div>

<script>
function sendTestEmail() {
    const testEmail = document.getElementById('test_email').value;
    if (!testEmail) {
        alert('Please enter a test email address');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/settings/test-email';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '<?= csrf_token() ?>';
    csrfInput.value = '<?= csrf_hash() ?>';
    form.appendChild(csrfInput);
    
    const emailInput = document.createElement('input');
    emailInput.type = 'hidden';
    emailInput.name = 'test_email';
    emailInput.value = testEmail;
    form.appendChild(emailInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<?= $this->endSection() ?>