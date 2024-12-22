<?php
namespace App\Models;

class User extends BaseModel {
    protected $table = 'users';
    
    public function createUser($userData) {
        return $this->create($userData);
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->query($sql, [$email])->fetch();
    }
    
    public function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->query($sql, [$userId]);
    }
}