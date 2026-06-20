<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class Audit extends BaseController
{
    protected $auditLogModel;

    public function __construct()
    {
        if (!session()->get('isLoggedIn') || !session()->get('isAdmin')) {
            redirect()->to('/auth/login')->with('error', 'Access denied. Admin login required.')->send();
            exit();
        }

        $this->auditLogModel = new AuditLogModel();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 25;
        $action = $this->request->getGet('action');
        $tableName = $this->request->getGet('table_name');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->auditLogModel;

        if ($action) {
            $builder->like('action', $action);
        }

        if ($tableName) {
            $builder->where('table_name', $tableName);
        }

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $totalLogs = $builder->countAllResults(false);
        $logs = $builder->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'default', $page);

        $data = [
            'pageTitle' => 'Audit Logs',
            'logs' => $logs,
            'pager' => $this->auditLogModel->pager,
            'totalLogs' => $totalLogs,
            'actionCounts' => $this->auditLogModel->getActionCounts(),
            'tables' => $this->auditLogModel->select('table_name')
                ->where('table_name IS NOT NULL')
                ->distinct()
                ->orderBy('table_name', 'ASC')
                ->findAll(),
            'filters' => [
                'action' => $action,
                'table_name' => $tableName,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ];

        return view('admin/audit/index', $data);
    }

    public function view($id)
    {
        $log = $this->auditLogModel->find($id);

        if (!$log) {
            return redirect()->to('/admin/audit')->with('error', 'Audit log not found.');
        }

        return view('admin/audit/view', [
            'pageTitle' => 'Audit Log Details',
            'log' => $log,
            'oldValues' => $this->decodeValues($log['old_values']),
            'newValues' => $this->decodeValues($log['new_values']),
        ]);
    }

    public function export()
    {
        $logs = $this->auditLogModel->orderBy('created_at', 'DESC')->findAll();
        $filename = 'audit_logs_' . date('Ymd_His') . '.csv';

        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'User ID', 'Action', 'Table', 'Record ID', 'IP Address', 'User Agent', 'Created At']);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['user_id'],
                $log['action'],
                $log['table_name'],
                $log['record_id'],
                $log['ip_address'],
                $log['user_agent'],
                $log['created_at'],
            ]);
        }

        fclose($output);
        return $this->response;
    }

    public function clean()
    {
        $days = (int) ($this->request->getPost('days') ?? 90);
        $days = max(1, $days);
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        $deleted = $this->auditLogModel->where('created_at <', $cutoffDate)->delete();

        return redirect()->to('/admin/audit')->with('success', 'Old audit logs cleaned successfully.');
    }

    private function decodeValues($values)
    {
        if (!$values) {
            return null;
        }

        $decoded = json_decode($values, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $values;
    }
}
