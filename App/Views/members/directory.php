<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1400px; margin: 40px auto; padding: 0 20px;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px;">👥 Member Directory</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">
            Connecting <?= number_format($totalMembers) ?> <?= $birthMonth ?> born members
        </p>
    </div>
    
    <!-- Filter Form -->
    <div class="polaroid-card" style="margin-bottom: 40px;">
        <h3 style="margin-bottom: 16px;">🔍 Filter Members</h3>
        
        <form method="GET" action="/members/directory">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, occupation..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">County</label>
                    <select name="county" class="form-control">
                        <option value="">All Counties</option>
                        <?php foreach ($counties as $county): ?>
                            <option value="<?= htmlspecialchars($county['county']) ?>" <?= ($filters['county'] ?? '') == $county['county'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($county['county']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">District</label>
                    <select name="district" class="form-control">
                        <option value="">All Districts</option>
                        <?php foreach ($districts as $district): ?>
                            <option value="<?= htmlspecialchars($district['district']) ?>" <?= ($filters['district'] ?? '') == $district['district'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($district['district']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Occupation</label>
                    <select name="occupation" class="form-control">
                        <option value="">All Occupations</option>
                        <?php foreach ($occupations as $occupation): ?>
                            <option value="<?= htmlspecialchars($occupation['occupation']) ?>" <?= ($filters['occupation'] ?? '') == $occupation['occupation'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($occupation['occupation']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">All</option>
                        <option value="Male" <?= ($filters['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($filters['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= ($filters['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-control">
                        <option value="name" <?= ($filters['sort'] ?? 'name') == 'name' ? 'selected' : '' ?>>Name (A-Z)</option>
                        <option value="name_desc" <?= ($filters['sort'] ?? '') == 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                        <option value="newest" <?= ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Newest First</option>
                        <option value="oldest" <?= ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
                <button type="submit" class="btn-vintage btn-vintage-primary">Apply Filters</button>
                <a href="/members/directory" class="btn-vintage">Clear Filters</a>
                <?php if (session()->get('isLoggedIn') && session()->get('isAdmin')): ?>
                    <a href="/members/export-csv<?= !empty(array_filter($filters)) ? '?' . http_build_query(array_filter($filters)) : '' ?>" class="btn-vintage">
                        📥 Export to CSV
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Results Count -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
        <p class="font-mono-courier" style="font-size: 14px;">
            Showing <?= count($members) ?> of <?= number_format($totalMembers) ?> members
        </p>
    </div>
    
    <!-- Members Grid -->
    <?php if (!empty($members)): ?>
        <div class="card-grid">
            <?php 
            $rotations = ['rotate-1', 'rotate-2', '-rotate-1', '-rotate-2'];
            foreach ($members as $index => $member): 
                $rotation = $rotations[$index % count($rotations)];
            ?>
                <div class="polaroid-card <?= $rotation ?>" style="text-align: center;">
                    <a href="/members/view/<?= $member['id'] ?>" style="text-decoration: none; color: inherit;">
                        <div style="width: 120px; height: 120px; background: var(--color-outline); border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <?php if ($member['profile_photo']): ?>
                                <img src="<?= base_url($member['profile_photo']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="material-symbols-outlined" style="font-size: 60px;">person</span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 style="margin-bottom: 4px; font-size: 18px;"><?= esc($member['first_name']) ?> <?= esc($member['last_name']) ?></h3>
                        
                        <?php if ($member['occupation']): ?>
                            <p class="font-mono-courier" style="font-size: 12px; margin: 8px 0; opacity: 0.8;">
                                <?= esc($member['occupation']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($member['county']): ?>
                            <p class="font-mono-courier" style="font-size: 11px; opacity: 0.7;">
                                📍 <?= esc($member['county']) ?>
                                <?php if ($member['district']): ?>
                                    • <?= esc($member['district']) ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        
                        <div style="margin-top: 12px;">
                            <span class="btn-vintage" style="padding: 4px 12px; font-size: 11px;">View Profile →</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 40px; flex-wrap: wrap;">
                <?php if ($currentPage > 1): ?>
                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $currentPage - 1])) ?>" class="btn-vintage">← Previous</a>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>" class="btn-vintage <?= $i == $currentPage ? 'btn-vintage-primary' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($filters, ['page' => $currentPage + 1])) ?>" class="btn-vintage">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="polaroid-card" style="text-align: center; padding: 60px;">
            <span class="material-symbols-outlined" style="font-size: 64px;">person_search</span>
            <h3 style="margin: 16px 0;">No members found</h3>
            <p class="font-mono-courier">Try adjusting your filters or check back later.</p>
            <a href="/members/directory" class="btn-vintage" style="margin-top: 20px;">Clear All Filters</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }
    
    @media (max-width: 768px) {
        .card-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>
```