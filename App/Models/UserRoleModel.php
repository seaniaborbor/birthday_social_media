<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'role_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
    
    /**
     * Assign role to user
     * 
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public function assignRole($userId, $roleId)
    {
        // Remove existing roles
        $this->where('user_id', $userId)->delete();
        
        // Assign new role
        return $this->insert([
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }
    
    /**
     * Get user roles
     * 
     * @param int $userId
     * @return array
     */
    public function getUserRoles($userId)
    {
        return $this->select('roles.*')
                    ->join('roles', 'roles.id = user_roles.role_id')
                    ->where('user_id', $userId)
                    ->findAll();
    }
    
    /**
     * Check if user has role
     * 
     * @param int $userId
     * @param string $roleName
     * @return bool
     */
    public function hasRole($userId, $roleName)
    {
        $count = $this->select('user_roles.id')
                      ->join('roles', 'roles.id = user_roles.role_id')
                      ->where('user_roles.user_id', $userId)
                      ->where('roles.name', $roleName)
                      ->countAllResults();
        
        return $count > 0;
    }
}
