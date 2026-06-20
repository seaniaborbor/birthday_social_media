<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px;">📬 Contact Us</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">Get in touch with <?= $associationName ?></p>
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
    
    <div class="card-grid" style="grid-template-columns: 1fr 1fr; gap: 40px;">
        
        <!-- Contact Form -->
        <div class="polaroid-card">
            <h3 style="margin-bottom: 20px;">Send us a message</h3>
            
            <form method="POST" action="/contact/submit">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subject *</label>
                    <input type="text" name="subject" class="form-control" value="<?= old('subject') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message *</label>
                    <textarea name="message" class="form-control" rows="5" required><?= old('message') ?></textarea>
                </div>
                
                <button type="submit" class="btn-vintage btn-vintage-primary">Send Message</button>
            </form>
        </div>
        
        <!-- Contact Info -->
        <div>
            <div class="polaroid-card" style="margin-bottom: 30px;">
                <h3 style="margin-bottom: 20px;">Contact Information</h3>
                
                <?php if ($associationAddress): ?>
                    <div style="margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="vertical-align: middle;">location_on</span>
                        <strong>Address:</strong><br>
                        <?= nl2br(esc($associationAddress)) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($associationEmail): ?>
                    <div style="margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="vertical-align: middle;">mail</span>
                        <strong>Email:</strong><br>
                        <a href="mailto:<?= $associationEmail ?>"><?= $associationEmail ?></a>
                    </div>
                <?php endif; ?>
                
                <?php if ($associationPhone): ?>
                    <div style="margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="vertical-align: middle;">phone</span>
                        <strong>Phone:</strong><br>
                        <a href="tel:<?= $associationPhone ?>"><?= $associationPhone ?></a>
                    </div>
                <?php endif; ?>
                
                <div class="stamp" style="position: static; margin-top: 16px;">WE'D LOVE TO HEAR FROM YOU</div>
            </div>
            
            <!-- Map -->
            <?php if ($mapEmbed): ?>
                <div class="polaroid-card">
                    <h3 style="margin-bottom: 16px;">Find Us</h3>
                    <?= $mapEmbed ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>