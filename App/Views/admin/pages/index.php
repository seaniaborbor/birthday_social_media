<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px;">📄 Page Management</h1>
            <p class="font-mono-courier" style="opacity: 0.7;">Manage static pages like About, Privacy, Terms</p>
        </div>
        <a href="/admin/pages/create" class="btn-vintage btn-vintage-primary">➕ Create New Page</a>
    </div>
    
    <!-- Pages Table -->
    <div class="polaroid-card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-outline);">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left;">Slug</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Last Modified</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pages)): ?>
                    <?php foreach ($pages as $page): ?>
                        <tr style="border-bottom: 1px dotted var(--color-outline);">
                            <td style="padding: 12px;">#<?= $page['id'] ?></td>
                            <td style="padding: 12px;">
                                <strong><?= esc($page['title']) ?></strong>
                            </td>
                            <td style="padding: 12px;">
                                <code class="font-mono-courier"><?= $page['slug'] ?></code>
                            </td>
                            <td style="padding: 12px;">
                                <?php if ($page['is_published']): ?>
                                    <span class="stamp" style="position: static; font-size: 10px;">Published</span>
                                <?php else: ?>
                                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 10px;">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; font-size: 12px;">
                                <?= date('M j, Y', strtotime($page['updated_at'])) ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="/page/<?= $page['slug'] ?>" target="_blank" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">View</a>
                                    <a href="/admin/pages/edit/<?= $page['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                                    <a href="/admin/pages/toggle-publish/<?= $page['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px;">
                                        <?= $page['is_published'] ? 'Unpublish' : 'Publish' ?>
                                    </a>
                                    <?php if (!in_array($page['slug'], ['about', 'privacy', 'terms'])): ?>
                                        <a href="/admin/pages/delete/<?= $page['id'] ?>" class="btn-vintage" style="padding: 4px 8px; font-size: 11px; color: var(--color-error);" onclick="return confirm('Delete this page?')">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                         </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center;">No pages found. <a href="/admin/pages/create">Create your first page</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>