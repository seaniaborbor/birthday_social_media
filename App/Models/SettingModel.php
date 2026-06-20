<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value', 'group', 'type'];
    protected $useTimestamps = true;
    
    private $cache = [];
    
    /**
     * Get setting value by key
     * 
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        
        $row = $this->where('key', $key)->first();
        
        if (!$row) {
            return null;
        }
        
        $value = $row['value'];
        
        // Parse based on type
        switch ($row['type']) {
            case 'number':
                $value = (int) $value;
                break;
            case 'checkbox':
                $value = (bool) $value;
                break;
            case 'json':
                $value = json_decode($value, true);
                break;
        }
        
        $this->cache[$key] = $value;
        
        return $value;
    }
    
    /**
     * Set setting value.
     *
     * This method keeps a signature compatible with CodeIgniter\Model::set(),
     * but preserves the original behavior when called with additional
     * `group` and `type` string arguments: `set($key, $value, $group, $type)`.
     *
     * @param string $key
     * @param mixed $value
     * @param bool|null $escape Compatible with parent signature when used normally
     * @return bool|\CodeIgniter\Database\BaseResultInterface
     */
    public function set($key, $value = '', ?bool $escape = null)
    {
        $args = func_get_args();

        // If called with string 3rd arg treat as legacy (group, type) usage.
        if (isset($args[2]) && is_string($args[2])) {
            $group = $args[2];
            $type = isset($args[3]) && is_string($args[3]) ? $args[3] : 'text';
            return $this->upsertSetting($key, $value, $group, $type);
        }

        // Otherwise, behave like parent::set() to remain compatible with Model
        return parent::set($key, $value, $escape);
    }

    /**
     * Insert or update a setting by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @return bool
     */
    public function upsertSetting($key, $value, $group, $type = 'text')
    {
        if ($type === 'checkbox') {
            $value = $value ? '1' : '0';
        } elseif (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        } elseif ($type === 'number') {
            $value = (string) $value;
        } else {
            $value = (string) $value;
        }

        $table = $this->db->protectIdentifiers($this->table);
        $sql = 'INSERT INTO ' . $table . ' (`key`, `value`, `group`, `type`, `created_at`, `updated_at`) '
            . 'VALUES (?, ?, ?, ?, NOW(), NOW()) '
            . 'ON DUPLICATE KEY UPDATE '
            . '`value` = VALUES(`value`), '
            . '`group` = VALUES(`group`), '
            . '`type` = VALUES(`type`), '
            . '`updated_at` = NOW()';

        $result = $this->db->query($sql, [$key, $value, $group, $type]);

        if ($result) {
            $this->cache[$key] = $value;
            return true;
        }

        log_message('error', 'Failed to upsert setting: {key}', ['key' => $key]);
        return false;
    }
    
    /**
     * Get all settings as key-value array
     * 
     * @return array
     */
    public function getAll()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $value = $setting['value'];
            
            switch ($setting['type']) {
                case 'number':
                    $value = (int) $value;
                    break;
                case 'checkbox':
                    $value = (bool) $value;
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
            }
            
            $result[$setting['key']] = $value;
        }
        
        return $result;
    }
    
    /**
     * Get all settings grouped by group
     * 
     * @return array
     */
    public function getAllGrouped()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $group = $setting['group'];
            
            if (!isset($result[$group])) {
                $result[$group] = [];
            }
            
            $value = $setting['value'];
            
            switch ($setting['type']) {
                case 'number':
                    $value = (int) $value;
                    break;
                case 'checkbox':
                    $value = (bool) $value;
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
            }
            
            $result[$group][$setting['key']] = $value;
        }
        
        return $result;
    }
}
