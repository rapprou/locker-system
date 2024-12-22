<?php
namespace App\Models;

class Locker extends BaseModel {
    protected $table = 'lockers';
    
    public function getAllAvailable() {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'DISPONIBLE' AND is_active = true";
        return $this->query($sql)->fetchAll();
    }
    
    public function assign($lockerId, $status = 'ATTRIBUE') {
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        return $this->query($sql, [
            ':status' => $status,
            ':id' => $lockerId
        ])->rowCount();
    }
    
    public function getWithAssignments() {
        $sql = "SELECT l.*, la.assignment_date, la.return_date, 
                u.first_name, u.last_name, la.status as assignment_status
                FROM {$this->table} l
                LEFT JOIN locker_assignments la ON l.id = la.locker_id
                LEFT JOIN users u ON la.user_id = u.id
                WHERE la.status = 'ACTIVE' OR la.id IS NULL
                ORDER BY l.locker_number";
        return $this->query($sql)->fetchAll();
    }
    
    public function getByNumber($number) {
        $sql = "SELECT * FROM {$this->table} WHERE locker_number = :number";
        return $this->query($sql, [':number' => $number])->fetch();
    }
}