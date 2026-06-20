<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div style="display: flex; min-height: calc(100vh - 200px);" class="admin-container">
    <?= $this->include('layouts/sidebar') ?>
    
    <main style="flex: 1; background: var(--color-background);" class="admin-main">
        <?= $this->renderSection('content') ?>
    </main>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>

<style>
    @media (max-width: 1024px) {
        .admin-container {
            flex-direction: column;
        }
        
        aside {
            width: 100% !important;
            border-right: none !important;
            border-bottom: 1px solid var(--color-outline) !important;
            position: static !important;
            min-height: auto !important;
        }
        
        aside .ledger-lines {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            overflow-x: auto;
            padding: 12px;
            line-height: 1.6;
        }
        
        aside nav {
            display: flex;
            gap: 4px;
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .admin-nav-link {
            white-space: nowrap;
            flex-shrink: 0;
        }
        
        .admin-nav-link span {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        aside .ledger-lines {
            gap: 8px;
            padding: 12px 8px;
        }
        
        .admin-nav-link {
            padding: 8px 10px !important;
            font-size: 11px;
        }
        
        .admin-nav-link .material-symbols-outlined {
            font-size: 16px !important;
        }
        
        aside nav {
            gap: 2px;
        }
        
        aside h3 {
            display: none;
        }
        
        aside hr {
            width: 100%;
            flex-basis: 100%;
            margin: 0;
        }
    }
    
    @media (max-width: 480px) {
        aside .ledger-lines {
            padding: 8px;
        }
        
        .admin-nav-link {
            padding: 6px 8px !important;
            font-size: 10px;
        }
        
        .admin-nav-link .material-symbols-outlined {
            font-size: 14px !important;
        }
    }
</style>