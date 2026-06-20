<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description'];
    protected $useTimestamps = true;
    
    /**
     * Get role by name
     */
    public function getByName($name)
    {
        return $this->where('name', $name)->first();
    }
    
    /**
     * Get all roles with permission counts
     */
    public function getRolesWithPermissions()
    {
        $builder = $this->db->table('roles');
        $builder->select('roles.*, COUNT(role_permissions.id) as permission_count');
        $builder->join('role_permissions', 'role_permissions.role_id = roles.id', 'left');
        $builder->groupBy('roles.id');
        $builder->orderBy('roles.id', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}