<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    
    <div style="margin-bottom: 20px;">
        <a href="/events" class="btn-vintage">← Back to Events</a>
    </div>
    
    <div class="polaroid-card" style="padding: 40px;">
        <?php if ($event['featured_image']): ?>
            <div style="margin-bottom: 24px;">
                <img src="<?= base_url($event['featured_image']) ?>" alt="<?= esc($event['title']) ?>" style="width: 100%; height: auto;">
            </div>
        <?php endif; ?>
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
            <span class="btn-vintage"><?= date('F j, Y', strtotime($event['event_date'])) ?></span>
            <?php if ($event['event_time']): ?>
                <span class="btn-vintage">⏰ <?= date('g:i A', strtotime($event['event_time'])) ?></span>
            <?php endif; ?>
        </div>
        
        <h1 style="font-size: 42px; margin-bottom: 16px;"><?= esc($event['title']) ?></h1>
        <p class="font-mono-courier" style="font-size: 16px; margin-bottom: 24px;">📍 <?= esc($event['venue']) ?></p>
        
        <div class="ledger-lines" style="padding: 20px; margin: 20px 0;">
            <?= $event['description'] ?>
        </div>
        
        <!-- RSVP Section -->
        <?php if ($event['status'] == 'upcoming' || $event['status'] == 'ongoing'): ?>
            <hr class="dotted-divider">
            
            <div style="text-align: center; margin-top: 30px;">
                <h3 style="margin-bottom: 16px;">Will you attend?</h3>
                
                <?php if (session()->get('isLoggedIn')): ?>
                    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                        <form method="POST" action="/events/rsvp/<?= $event['id'] ?>" style="display: inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="going">
                            <button type="submit" class="btn-vintage <?= $userRsvp == 'going' ? 'btn-vintage-primary' : '' ?>">
                                ✅ Going (<?= $goingCount ?>)
                            </button>
                        </form>
                        <form method="POST" action="/events/rsvp/<?= $event['id'] ?>" style="display: inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="maybe">
                            <button type="submit" class="btn-vintage <?= $userRsvp == 'maybe' ? 'btn-vintage-primary' : '' ?>">
                                🤔 Maybe (<?= $maybeCount ?>)
                            </button>
                        </form>
                        <form method="POST" action="/events/rsvp/<?= $event['id'] ?>" style="display: inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="btn-vintage <?= $userRsvp == 'declined' ? 'btn-vintage-primary' : '' ?>">
                                ❌ Can't make it
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <p><a href="/auth/login" class="btn-vintage">Login to RSVP</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <hr class="dotted-divider">
        
        <div class="stamp" style="position: static; display: inline-block;">
            <?= get_association_name() ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>