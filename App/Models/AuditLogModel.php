<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'action', 'table_name', 'record_id', 'old_values', 'new_values', 'ip_address', 'user_agent'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    /**
     * Log an action
     */
    public function log($userId, $action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null)
    {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Get logs with user details
     */
    public function getLogsWithUsers($limit = 100, $offset = 0, $filters = [])
    {
        $builder = $this->db->table('audit_logs');
        $builder->select('audit_logs.*, members.first_name, members.last_name, members.email');
        $builder->join('members', 'members.id = audit_logs.user_id', 'left');
        
        if (!empty($filters['action'])) {
            $builder->where('audit_logs.action', $filters['action']);
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('audit_logs.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['table_name'])) {
            $builder->where('audit_logs.table_name', $filters['table_name']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('audit_logs.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('audit_logs.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        $builder->orderBy('audit_logs.created_at', 'DESC');
        $builder->limit($limit, $offset);
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get total log count
     */
    public function getLogCount($filters = [])
    {
        $builder = $this->db->table('audit_logs');
        
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }
        
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['table_name'])) {
            $builder->where('table_name', $filters['table_name']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Get unique actions for filter
     */
    public function getUniqueActions()
    {
        return $this->select('action')->distinct()->findAll();
    }
    
    /**
     * Get unique tables for filter
     */
    public function getUniqueTables()
    {
        return $this->select('table_name')->where('table_name !=', null)->distinct()->findAll();
    }
    
    /**
     * Clean old logs
     */
    public function cleanOldLogs($days = 90)
    {
        $date = date('Y-m-d H:i:s', strtotime("-$days days"));
        return $this->where('created_at <', $date)->delete();
    }
    
    /**
     * Get action counts for statistics
     */
    public function getActionCounts()
    {
        $builder = $this->db->table('audit_logs');
        $builder->select('action, COUNT(*) as count');
        $builder->groupBy('action');
        $builder->orderBy('count', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}