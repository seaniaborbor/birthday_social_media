<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">📰 News Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Create and manage association news and announcements</p>
        </div>
        <a href="/admin/news/create" class="btn-vintage btn-vintage-primary">✏️ Write Article</a>
    </div>
    
    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px;"><?= $totalNews ?></h2>
            <p class="font-mono-courier">Total Articles</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-success);"><?= $publishedCount ?></h2>
            <p class="font-mono-courier">Published</p>
        </div>
        <div class="polaroid-card" style="text-align: center;">
            <h2 style="font-size: 28px; color: var(--color-secondary);"><?= $draftCount ?></h2>
            <p class="font-mono-courier">Drafts</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="polaroid-card" style="margin-bottom: 24px;">
        <form method="GET" action="/admin/news">
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
                <div>
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        <option value="all">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category'] ?>" <?= ($category ?? '') == $cat['category'] ? 'selected' : '' ?>>
                                <?= $cat['category'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="all">All Status</option>
                        <option value="published" <?= ($status ?? '') == 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= ($status ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-vintage">Filter</button>
                    <a href="/admin/news" class="btn-vintage">Clear</a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- News Table -->
    <div class="polaroid-card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-outline);">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left;">Category</th>
                    <th style="padding: 12px; text-align: left;">Views</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Date</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                 </tr>
            </thead>
            <tbody>
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $item): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;">#<?= $item['id'] ?></td>
                            <td style="padding: 12px;">
                                <strong><?= esc($item['title']) ?></strong>
                                <?php if ($item['featured_image']): ?>
                                    <br><span class="material-symbols-outlined" style="font-size: 12px;">image</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;"><?= esc($item['category']) ?></span>
                            </td>
                            <td style="padding: 12px;"><?= number_format($item['views']) ?></td>
                            <td style="padding: 12px;">
                                <?php if ($item['is_published']): ?>
                                    <span class="stamp" style="position: static; font-size: 10px;">Published</span>
                                <?php else: ?>
                                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; font-size: 12px;">
                                <?= date('M j, Y', strtotime($item['created_at'])) ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="/news/<?= $item['slug'] ?>" target="_blank" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">View</a>
                                    <a href="/admin/news/edit/<?= $item['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                                    <a href="/admin/news/toggle-publish/<?= $item['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">
                                        <?= $item['is_published'] ? 'Unpublish' : 'Publish' ?>
                                    </a>
                                    <a href="/admin/news/delete/<?= $item['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this article?')">Delete</a>
                                </div>
                            </td>
                         </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center;">No news articles found. <a href="/admin/news/create">Create your first article</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <div style="margin-top: 24px; text-align: center;">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>