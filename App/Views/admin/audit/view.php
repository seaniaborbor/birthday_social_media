<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div style="padding: 24px; max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="/admin/audit" class="btn-vintage">Back to Audit Logs</a>
    </div>

    <div class="polaroid-card">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
            <div>
                <h2><?= esc($log['action']) ?></h2>
                <p class="font-mono-courier" style="margin-top: 8px;">
                    <?= esc($log['table_name'] ?? 'System') ?>
                    <?= !empty($log['record_id']) ? ' #' . esc($log['record_id']) : '' ?>
                </p>
            </div>
            <span class="btn-vintage" style="padding: 4px 12px;">
                <?= $log['created_at'] ? date('F j, Y g:i A', strtotime($log['created_at'])) : 'No date' ?>
            </span>
        </div>

        <hr class="dotted-divider">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div>
                <h4>User ID</h4>
                <p class="font-mono-courier"><?= $log['user_id'] ? esc($log['user_id']) : 'Guest/System' ?></p>
            </div>
            <div>
                <h4>IP Address</h4>
                <p class="font-mono-courier"><?= esc($log['ip_address']) ?></p>
            </div>
            <div>
                <h4>User Agent</h4>
                <p class="font-mono-courier" style="word-break: break-word;"><?= esc($log['user_agent'] ?? '-') ?></p>
            </div>
        </div>

        <hr class="dotted-divider">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <div>
                <h3 style="margin-bottom: 12px;">Old Values</h3>
                <pre class="ledger-lines" style="padding: 16px; white-space: pre-wrap; word-break: break-word;"><?= esc(is_array($oldValues) ? json_encode($oldValues, JSON_PRETTY_PRINT) : ($oldValues ?? 'None')) ?></pre>
            </div>
            <div>
                <h3 style="margin-bottom: 12px;">New Values</h3>
                <pre class="ledger-lines" style="padding: 16px; white-space: pre-wrap; word-break: break-word;"><?= esc(is_array($newValues) ? json_encode($newValues, JSON_PRETTY_PRINT) : ($newValues ?? 'None')) ?></pre>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
