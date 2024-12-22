<?php
namespace App\Models;

class AuditLog extends BaseModel {
    protected $table = 'audit_logs';
    
    public function log($userId, $action, $oldValues = null, $newValues = null) {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ];
        
        return $this->create($data);
    }
    
    public function getRecentLogs($limit = 50) {
        $sql = "SELECT al.*, u.first_name, u.last_name 
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT ?";
        return $this->db->query($sql, [$limit])->fetchAll();
    }
    
    public function getLogsByUser($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$userId])->fetchAll();
    }
}