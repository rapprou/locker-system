<?php
namespace App\Models;

class User extends BaseModel {
    protected $table = 'users';
    
    public function createUser($userData) {
        $sql = "INSERT INTO USERS (email, password_hash, first_name, last_name, is_active, created_at, updated_at) 
                VALUES (:email, :password_hash, :first_name, :last_name, true, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $userData['email'],
            ':password_hash' => $userData['password_hash'],
            ':first_name' => $userData['first_name'],
            ':last_name' => ''
        ]);
        
        return $this->db->lastInsertId();
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