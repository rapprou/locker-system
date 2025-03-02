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
                CONCAT(u.first_name, ' ', u.last_name) as user_name,
                la.status as assignment_status
                FROM {$this->table} l
                LEFT JOIN (
                    SELECT la1.*
                    FROM locker_assignments la1
                    WHERE la1.assignment_date = (
                        SELECT MAX(assignment_date)
                        FROM locker_assignments la2
                        WHERE la2.locker_id = la1.locker_id
                    )
                ) la ON l.id = la.locker_id
                LEFT JOIN users u ON la.user_id = u.id
                ORDER BY CAST(SUBSTRING(l.locker_number, 1) AS SIGNED)";
        
        return $this->query($sql)->fetchAll();
    }
    
    public function getByNumber($number) {
        $sql = "SELECT * FROM {$this->table} WHERE locker_number = :number";
        return $this->query($sql, [':number' => $number])->fetch();
    }
}