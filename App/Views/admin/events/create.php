<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/events" class="btn-vintage">← Back to Events</a>
    </div>
    
    <div class="polaroid-card">
        <h2 style="margin-bottom: 24px;">Create New Event</h2>
        
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
        
        <form method="POST" action="/admin/events/store" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label class="form-label">Event Title *</label>
                <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="6" required><?= old('description') ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Venue *</label>
                <input type="text" name="venue" class="form-control" value="<?= old('venue') ?>" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Event Date *</label>
                    <input type="date" name="event_date" class="form-control" value="<?= old('event_date') ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Event Time</label>
                    <input type="time" name="event_time" class="form-control" value="<?= old('event_time') ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Featured Image</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="upcoming" <?= old('status') == 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                        <option value="ongoing" <?= old('status') == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="completed" <?= old('status') == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= old('status') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; margin-top: 32px;">
                        <input type="checkbox" name="is_featured" value="1" <?= old('is_featured') ? 'checked' : '' ?>>
                        <span>Feature this event on homepage</span>
                    </label>
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Create Event</button>
                <a href="/admin/events" class="btn-vintage">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>