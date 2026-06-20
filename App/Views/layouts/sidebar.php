<?php
// Path: app/Views/layouts/sidebar.php
// Admin sidebar for dashboard
?>

<aside style="width: 280px; background: var(--color-surface); border-right: 1px solid var(--color-outline); min-height: calc(100vh - 200px); position: sticky; top: 100px;" class="admin-sidebar">
    <div style="padding: 24px 16px;">
        <div class="ledger-lines" style="padding: 16px;">
            <h3 style="font-size: 14px; letter-spacing: 2px; margin-bottom: 20px; line-height: 32px;">ADMIN PANEL</h3>
            
            <nav style="display: flex; flex-direction: column; gap: 4px;">
                <a href="/admin/dashboard" class="admin-nav-link <?= current_url() == base_url('/admin/dashboard') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined" style="font-size: 18px;">dashboard</span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="/admin/settings" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">settings</span>
                    <span class="nav-label">Settings</span>
                </a>
                <a href="/admin/members" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">groups</span>
                    <span class="nav-label">Members</span>
                </a>
                <a href="/admin/executives" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">crown</span>
                    <span class="nav-label">Executives</span>
                </a>
                <a href="/admin/events" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">event</span>
                    <span class="nav-label">Events</span>
                </a>
                <a href="/admin/news" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">news</span>
                    <span class="nav-label">News</span>
                </a>
                <a href="/admin/gallery" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">photo_library</span>
                    <span class="nav-label">Gallery</span>
                </a>
                <a href="/admin/wishes" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">celebration</span>
                    <span class="nav-label">Birthday Wishes</span>
                </a>
                <a href="/admin/pages" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">description</span>
                    <span class="nav-label">Pages</span>
                </a>
                <a href="/admin/banners" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">wallpaper</span>
                    <span class="nav-label">Banners</span>
                </a>
                <a href="/admin/announcements" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">announcement</span>
                    <span class="nav-label">Announcements</span>
                </a>
                <a href="/admin/messages" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">mail</span>
                    <span class="nav-label">Messages</span>
                </a>
                <a href="/admin/audit" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">history</span>
                    <span class="nav-label">Audit Logs</span>
                </a>
                <a href="/admin/reports" class="admin-nav-link">
                    <span class="material-symbols-outlined" style="font-size: 18px;">bar_chart</span>
                    <span class="nav-label">Reports</span>
                </a>
            </nav>
            
            <hr class="dotted-divider">
            
            <a href="/" class="admin-nav-link" style="margin-top: 16px; line-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">public</span>
                <span class="nav-label">View Website</span>
            </a>
        </div>
    </div>
</aside>

<style>
    .admin-sidebar {
        transition: all 0.3s ease;
    }
    
    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        color: var(--color-text);
        text-decoration: none;
        font-family: 'Courier Prime', monospace;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        line-height: 1.6;
    }
    
    .admin-nav-link:hover {
        background: rgba(29, 78, 216, 0.05);
        border-left-color: var(--color-primary);
    }
    
    .admin-nav-link.active {
        background: rgba(29, 78, 216, 0.1);
        border-left-color: var(--color-primary);
        color: var(--color-primary);
    }
    
    .admin-nav-link .material-symbols-outlined {
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .nav-label {
        flex: 1;
    }
    
    @media (max-width: 1024px) {
        .admin-nav-link .nav-label {
            display: inline;
        }
    }
    
    @media (max-width: 768px) {
        .admin-nav-link .nav-label {
            display: none;
        }
        
        .admin-nav-link {
            gap: 6px;
            padding: 8px 10px;
            border-left: 2px solid transparent;
            justify-content: center;
        }
        
        .admin-nav-link .material-symbols-outlined {
            font-size: 18px;
        }
    }
    
    @media (max-width: 480px) {
        .admin-nav-link {
            padding: 6px 8px;
        }
        
        .admin-nav-link .material-symbols-outlined {
            font-size: 16px;
        }
    }
</style>