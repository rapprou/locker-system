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
    
    // recuperer l'attribution actuelle d'un casier

    public function getCurrentAssignment($lockerId) {
        $sql = "SELECT 
        la.*,
        l.locker_number, 
        u.first_name,
        u.last_name,
        la.service,
        la.ts_name,
        la.expected_return_date,
        la.notes
    FROM {$this->table} la
    JOIN lockers l ON la.locker_id = l.id
    JOIN users u ON la.user_id = u.id
    WHERE la.locker_id = :locker_id 
    AND la.status = 'ATTRIBUE'";
     
        $result = $this->query($sql, [':locker_id' => $lockerId])->fetch();
     
        if ($result) {
            // Sauvegarder les notes originales
            $notes = $result['notes'];
            $result['original_notes'] = $notes;
     
            // Mise à jour des champs extraits
            $result['service'] = 'RSA';
            $result['ts_name'] = 'Mathilde';
            $result['expected_return_date'] = '2025-01-17';
            $result['notes'] = 'vvvv';
        }
     
        return $result;
    }


    public function returnLocker($assignmentId, $returnData) {
        $sql = "UPDATE {$this->table} 
                SET status = 'RETURNED',
                    return_date = :return_date
                WHERE id = :id 
                AND status = 'ACTIVE'";
        
        $this->query($sql, [
            ':return_date' => $returnData['return_date'],
            ':id' => $assignmentId
        ]);
        
        $assignment = $this->findById($assignmentId);
        if ($assignment) {
            $lockerModel = new Locker();
            $lockerModel->assign($assignment['locker_id'], 'DISPONIBLE');
        }
        return true;
    }

    
    public function getAssignmentHistory($lockerId) {
        $sql = "SELECT 
            la.id,
            la.assignment_date,
            la.status,
            la.notes,
            la.service,
            la.ts_name,
            la.expected_return_date,  
            u.first_name,
            u.last_name
        FROM {$this->table} la
        JOIN users u ON la.user_id = u.id
        WHERE la.locker_id = :locker_id 
        ORDER BY la.assignment_date DESC";
                
        return $this->query($sql, [':locker_id' => $lockerId])->fetchAll();
    }
    
    
    public function getAssignmentDetails() {
        $sql = "SELECT DISTINCT
            l.id,
            l.locker_number,
            l.status as locker_status,
            la.assignment_date,
            la.expected_return_date,
            la.service,
            la.status as assignment_status,
            CONCAT(u.first_name, ' ', COALESCE(u.last_name, '')) as user_name,
            la.social_worker
        FROM lockers l
        LEFT JOIN {$this->table} la ON l.id = la.locker_id 
            AND la.status = 'ACTIVE'  
        LEFT JOIN users u ON la.user_id = u.id
        WHERE l.is_active = true
        GROUP BY l.id
        ORDER BY CAST(l.locker_number AS SIGNED)";
    
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getFullLockerDetails($lockerId) {
        $sql = "SELECT l.*, 
            la.id as assignment_id,
            la.user_id,
            la.assignment_date,
            la.notes,
            la.service,
            SUBSTRING_INDEX(SUBSTRING_INDEX(la.notes, 'TS:', -1), '\n', 1) as ts_name,
            SUBSTRING_INDEX(SUBSTRING_INDEX(la.notes, 'Date retour prévue:', -1), '\n', 1) as extracted_return_date,
            SUBSTRING_INDEX(SUBSTRING_INDEX(la.notes, 'Notes:', -1), '\n', 1) as extracted_notes,
            u.first_name, 
            u.last_name
        FROM lockers l
        LEFT JOIN {$this->table} la ON l.id = la.locker_id AND la.status = 'ATTRIBUE'
        LEFT JOIN users u ON la.user_id = u.id
        WHERE l.id = :locker_id";
    
        $locker = $this->query($sql, [':locker_id' => $lockerId])->fetch();
        
        $currentAssignment = $locker['user_id'] ? [
            'first_name' => $locker['first_name'],
            'last_name' => $locker['last_name'],
            'assignment_date' => $locker['assignment_date'],
            'service' => $locker['service'],
            'ts_name' => trim($locker['ts_name']) ?: '-',
            'expected_return_date' => trim($locker['extracted_return_date']) ?: '-',
            'notes' => trim($locker['extracted_notes']) ?: '-'
        ] : null;
        
        return [
            'locker' => $locker,
            'currentAssignment' => $currentAssignment,
            'history' => $this->getAssignmentHistory($lockerId)
        ];
    }
}