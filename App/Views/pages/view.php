<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    
    <div class="polaroid-card" style="padding: 40px;">
        <div class="stamp" style="position: static; display: inline-block; margin-bottom: 20px;">
            <?= get_association_name() ?>
        </div>
        
        <h1 style="font-size: 42px; margin-bottom: 30px;"><?= esc($page['title']) ?></h1>
        
        <div class="ledger-lines" style="padding: 20px;">
            <?= $page['content'] ?>
        </div>
        
        <hr class="dotted-divider" style="margin: 30px 0 20px;">
        
        <div style="text-align: center;">
            <a href="/" class="btn-vintage">← Back to Home</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>