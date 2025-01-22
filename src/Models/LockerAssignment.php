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
        CONCAT(u.first_name, ' ', COALESCE(u.last_name, '')) as full_name,
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
        AND la.status = 'ATTRIBUE'
        ORDER BY la.assignment_date DESC
        LIMIT 1";
    
        $result = $this->query($sql, [':locker_id' => $lockerId])->fetch();
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
        // 1. Récupérer les informations du casier
        $sql = "SELECT l.*, 
            la.id as assignment_id,
            la.user_id,
            la.assignment_date,
            la.notes,
            la.service,
            la.ts_name,
            la.expected_return_date,
            u.first_name, 
            u.last_name
        FROM lockers l
        LEFT JOIN (
            SELECT * FROM {$this->table} 
            WHERE status = 'ATTRIBUE'
            ORDER BY assignment_date DESC
            LIMIT 1
        ) la ON l.id = la.locker_id
        LEFT JOIN users u ON la.user_id = u.id
        WHERE l.id = :locker_id";
    
        $locker = $this->query($sql, [':locker_id' => $lockerId])->fetch();
        
        // 2. Préparer l'attribution actuelle
        $currentAssignment = $locker['user_id'] ? [
            'first_name' => $locker['first_name'],
            'last_name' => $locker['last_name'],
            'assignment_date' => $locker['assignment_date'],
            'service' => $this->extractServiceFromNotes($locker['notes']),
            'ts_name' => $this->extractTsNameFromNotes($locker['notes']),
            'expected_return_date' => $locker['expected_return_date'] ?: '-',
            'notes' => $locker['notes'] ?: '-'
        ] : null;
        
        // 3. Récupérer l'historique (seulement les attributions terminées)
        $history = $this->query(
            "SELECT 
                la.assignment_date,
                la.status,
                la.notes,
                la.service,
                la.ts_name,
                la.return_date,
                u.first_name,
                u.last_name
            FROM {$this->table} la
            JOIN users u ON la.user_id = u.id
            WHERE la.locker_id = :locker_id 
            AND la.status NOT IN ('ATTRIBUE')
            ORDER BY la.assignment_date DESC",
            [':locker_id' => $lockerId]
        )->fetchAll();
        
        return [
            'locker' => $locker,
            'currentAssignment' => $currentAssignment,
            'history' => $history
        ];
    }
    
    // Modifié pour enlever la valeur par défaut Mathilde
    private function extractTsNameFromNotes($notes) {
        if (empty($notes)) {
            return '-';
        }
        
        if (strpos($notes, 'TS:') !== false) {
            $parts = explode('TS:', $notes);
            if (isset($parts[1])) {
                return trim(explode("\n", $parts[1])[0]);
            }
        }
        
        return '-';  // Retourne - si pas trouvé au lieu de Mathilde
    }
    
    // Nouvelle méthode pour extraire le service
    private function extractServiceFromNotes($notes) {
        if (empty($notes)) {
            return '-';
        }
        
        if (strpos($notes, 'Service:') !== false) {
            $parts = explode('Service:', $notes);
            if (isset($parts[1])) {
                return trim(explode("\n", $parts[1])[0]);
            }
        }
        
        return '-';
    }

}