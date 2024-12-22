<?php
namespace App\Models;

class LockerAssignment extends BaseModel {
    protected $table = 'locker_assignments';
    
    public function createAssignment($data) {
        // Créer l'attribution
        $assignmentId = $this->create($data);
        
        // Mettre à jour le statut du casier
        if ($assignmentId) {
            $lockerModel = new Locker();
            $lockerModel->assign($data['locker_id']);
        }
        
        return $assignmentId;
    }
    
    public function getCurrentAssignment($lockerId) {
        $sql = "SELECT la.*, l.locker_number, u.first_name, u.last_name 
                FROM {$this->table} la
                JOIN lockers l ON la.locker_id = l.id
                JOIN users u ON la.user_id = u.id
                WHERE la.locker_id = :locker_id 
                AND la.status = 'ACTIVE'";
                
        return $this->query($sql, [':locker_id' => $lockerId])->fetch();
    }
    
    public function returnLocker($assignmentId, $returnData) {
        // Mettre à jour l'attribution
        $sql = "UPDATE {$this->table} 
                SET status = 'RETURNED', 
                    return_date = :return_date,
                    notes = CONCAT(notes, '\nRestitution: ', :notes)
                WHERE id = :id";
                
        $this->query($sql, [
            ':return_date' => $returnData['return_date'],
            ':notes' => $returnData['notes'],
            ':id' => $assignmentId
        ]);
        
        // Récupérer le locker_id
        $assignment = $this->findById($assignmentId);
        
        // Mettre à jour le statut du casier
        if ($assignment) {
            $lockerModel = new Locker();
            $lockerModel->assign($assignment['locker_id'], 'DISPONIBLE');
        }
    }
    
    public function getAssignmentHistory($lockerId) {
        $sql = "SELECT la.*, u.first_name, u.last_name 
                FROM {$this->table} la
                JOIN users u ON la.user_id = u.id
                WHERE la.locker_id = :locker_id
                ORDER BY la.created_at DESC";
                
        return $this->query($sql, [':locker_id' => $lockerId])->fetchAll();
    }
}