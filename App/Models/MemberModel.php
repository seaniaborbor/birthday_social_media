<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id';
  protected $allowedFields = [
    'email', 'password', 'first_name', 'last_name', 'birth_day', 'birth_month', 
    'birth_year', 'phone', 'address', 'city', 'county', 'district', 'occupation', 
    'gender', 'profile_photo', 'is_active', 'is_approved', 'last_login',
    'reset_token', 'reset_expires'
];
    protected $useTimestamps = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    /**
     * Verify member credentials
     * 
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function verifyCredentials($email, $password)
    {
        $member = $this->where('email', $email)->first();
        
        if (!$member) {
            return false;
        }
        
        if (!password_verify($password, $member['password'])) {
            return false;
        }
        
        if (!$member['is_active'] || !$member['is_approved']) {
            return false;
        }
        
        return $member;
    }
    
    /**
     * Get members with their roles
     * 
     * @param array $filters
     * @return array
     */
    public function getWithRoles($filters = [])
    {
        $builder = $this->db->table('members');
        $builder->select('members.*, GROUP_CONCAT(roles.name) as roles');
        $builder->join('user_roles', 'user_roles.user_id = members.id', 'left');
        $builder->join('roles', 'roles.id = user_roles.role_id', 'left');
        
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($value !== '' && $value !== null) {
                    $builder->where($key, $value);
                }
            }
        }
        
        $builder->groupBy('members.id');
        $builder->orderBy('members.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Check if member has permission
     * 
     * @param int $memberId
     * @param string $permission
     * @return bool
     */
    public function hasPermission($memberId, $permission)
    {
        $builder = $this->db->table('user_roles');
        $builder->select('role_permissions.permission');
        $builder->join('role_permissions', 'role_permissions.role_id = user_roles.role_id');
        $builder->where('user_roles.user_id', $memberId);
        $builder->where('role_permissions.permission', $permission);
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Get member by email
     * 
     * @param string $email
     * @return array|null
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Get recent members
     * 
     * @param int $limit
     * @return array
     */
    public function getRecentMembers($limit = 10)
    {
        return $this->where('is_approved', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
    
    /**
     * Get member count by county
     * 
     * @return array
     */
    public function getCountByCounty()
    {
        return $this->db->table('members')
                        ->select('county, COUNT(*) as count')
                        ->where('is_approved', 1)
                        ->groupBy('county')
                        ->get()
                        ->getResultArray();
    }
    
    /**
     * Get member count by gender
     * 
     * @return array
     */
    public function getCountByGender()
    {
        return $this->db->table('members')
                        ->select('gender, COUNT(*) as count')
                        ->where('is_approved', 1)
                        ->groupBy('gender')
                        ->get()
                        ->getResultArray();
    }

    /**
 * Get filtered members for directory
 */
public function getFilteredMembers($filters = [])
{
    $builder = $this->where('is_approved', 1)
                    ->where('is_active', 1);
    
    if (!empty($filters['search'])) {
        $builder->groupStart()
                ->like('first_name', $filters['search'])
                ->orLike('last_name', $filters['search'])
                ->orLike('email', $filters['search'])
                ->orLike('occupation', $filters['search'])
                ->groupEnd();
    }
    
    if (!empty($filters['county'])) {
        $builder->where('county', $filters['county']);
    }
    
    if (!empty($filters['district'])) {
        $builder->where('district', $filters['district']);
    }
    
    if (!empty($filters['occupation'])) {
        $builder->where('occupation', $filters['occupation']);
    }
    
    if (!empty($filters['gender'])) {
        $builder->where('gender', $filters['gender']);
    }
    
    return $builder->orderBy('first_name', 'ASC')->findAll();
}

/**
 * Get unique counties for filter
 */
public function getUniqueCounties()
{
    return $this->select('county')
                ->where('county !=', '')
                ->where('county IS NOT NULL')
                ->where('is_approved', 1)
                ->distinct()
                ->orderBy('county', 'ASC')
                ->findAll();
}

/**
 * Get unique districts for filter
 */
public function getUniqueDistricts()
{
    return $this->select('district')
                ->where('district !=', '')
                ->where('district IS NOT NULL')
                ->where('is_approved', 1)
                ->distinct()
                ->orderBy('district', 'ASC')
                ->findAll();
}

/**
 * Get unique occupations for filter
 */
public function getUniqueOccupations()
{
    return $this->select('occupation')
                ->where('occupation !=', '')
                ->where('occupation IS NOT NULL')
                ->where('is_approved', 1)
                ->distinct()
                ->orderBy('occupation', 'ASC')
                ->findAll();
}

/**
 * Get pending members for admin approval
 */
public function getPendingMembers()
{
    return $this->where('is_approved', 0)
                ->where('is_active', 1)
                ->orderBy('created_at', 'ASC')
                ->findAll();
}

/**
 * Get filtered members with pagination
 */
public function getFilteredMembersPaginated($filters = [], $perPage = 12, $page = 1)
{
    $builder = $this->buildFilteredQuery($filters);
    
    $offset = ($page - 1) * $perPage;
    
    return $builder->limit($perPage, $offset)->get()->getResultArray();
}

/**
 * Get filtered members count
 */
public function getFilteredMembersCount($filters = [])
{
    $builder = $this->buildFilteredQuery($filters);
    
    return $builder->countAllResults();
}

/**
 * Build filtered query
 */
private function buildFilteredQuery($filters = [])
{
    $builder = $this->db->table('members');
    $builder->where('is_approved', 1)
            ->where('is_active', 1);
    
    if (!empty($filters['search'])) {
        $builder->groupStart()
                ->like('first_name', $filters['search'])
                ->orLike('last_name', $filters['search'])
                ->orLike('email', $filters['search'])
                ->orLike('occupation', $filters['search'])
                ->groupEnd();
    }
    
    if (!empty($filters['county'])) {
        $builder->where('county', $filters['county']);
    }
    
    if (!empty($filters['district'])) {
        $builder->where('district', $filters['district']);
    }
    
    if (!empty($filters['occupation'])) {
        $builder->where('occupation', $filters['occupation']);
    }
    
    if (!empty($filters['gender'])) {
        $builder->where('gender', $filters['gender']);
    }
    
    // Sort options
    switch ($filters['sort'] ?? 'name') {
        case 'name':
            $builder->orderBy('first_name', 'ASC')->orderBy('last_name', 'ASC');
            break;
        case 'name_desc':
            $builder->orderBy('first_name', 'DESC')->orderBy('last_name', 'DESC');
            break;
        case 'newest':
            $builder->orderBy('created_at', 'DESC');
            break;
        case 'oldest':
            $builder->orderBy('created_at', 'ASC');
            break;
        default:
            $builder->orderBy('first_name', 'ASC');
    }
    
    return $builder;
}

/**
 * Export members to CSV
 */
public function exportToCsv($filters = [])
{
    $builder = $this->buildFilteredQuery($filters);
    $members = $builder->get()->getResultArray();
    
    $filename = 'members_export_' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Add headers
    fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Birth Date', 'County', 'District', 'Occupation', 'Gender', 'Joined Date']);
    
    // Add data
    foreach ($members as $member) {
        fputcsv($output, [
            $member['id'],
            $member['first_name'],
            $member['last_name'],
            $member['email'],
            $member['phone'],
            $member['birth_month'] . '/' . $member['birth_day'] . '/' . $member['birth_year'],
            $member['county'],
            $member['district'],
            $member['occupation'],
            $member['gender'],
            $member['created_at']
        ]);
    }
    
    fclose($output);
    exit();
}
}