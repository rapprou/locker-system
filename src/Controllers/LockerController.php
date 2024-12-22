<?php
namespace App\Controllers;

use App\Models\Locker;
use App\Models\User;
use App\Models\LockerAssignment;

class LockerController extends BaseController {
    private $lockerModel;
    private $userModel;
    private $assignmentModel;

    public function __construct() {
        $this->lockerModel = new Locker();
        $this->userModel = new User();
        $this->assignmentModel = new LockerAssignment();
    }

    public function index() {
        // Récupérer tous les casiers avec leurs attributions
        $lockers = $this->lockerModel->getWithAssignments();
        
        // Calculer les statistiques
        $stats = [
            'total' => count($lockers),
            'available' => count(array_filter($lockers, function($l) { 
                return $l['status'] === 'DISPONIBLE'; 
            })),
            'assigned' => count(array_filter($lockers, function($l) { 
                return $l['status'] === 'ATTRIBUE'; 
            }))
        ];

        $data = [
            'title' => 'Gestion des Casiers',
            'lockers' => $lockers,
            'stats' => $stats
        ];
        
        echo $this->render('lockers/index', $data);
    }

    public function assign() {
        if ($this->isPost()) {
            $postData = $this->getPost();
            
            try {
                // Créer ou récupérer l'utilisateur
                $userData = [
                    'first_name' => $postData['user_name'],
                    'email' => 'temp_' . time() . '@example.com', // Email temporaire
                    'password_hash' => password_hash('temp' . time(), PASSWORD_DEFAULT),
                    'last_name' => '',
                    'role_id' => 2 // ID du rôle utilisateur standard
                ];
                
                $userId = $this->userModel->createUser($userData);

                // Créer l'attribution
                $assignmentData = [
                    'locker_id' => $postData['locker_id'],
                    'user_id' => $userId,
                    'notes' => sprintf(
                        "Service: %s\nTS: %s\nDate retour prévue: %s\nNotes: %s",
                        $postData['service'],
                        $postData['social_worker'],
                        $postData['expected_return_date'],
                        $postData['notes'] ?? ''
                    )
                ];
                
                $this->assignmentModel->createAssignment($assignmentData);
                
                // Redirection avec message de succès
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Le casier a été attribué avec succès.'
                ];
                
                $this->redirect('/lockers');
                return;
            } catch (\Exception $e) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Une erreur est survenue lors de l\'attribution du casier.'
                ];
            }
        }
        
        // Pour le formulaire d'attribution
        $availableLockers = $this->lockerModel->getAllAvailable();
        
        $data = [
            'title' => 'Attribution d\'un casier',
            'availableLockers' => $availableLockers
        ];
        
        echo $this->render('lockers/assign', $data);
    }

    public function return() {
        if ($this->isPost()) {
            $postData = $this->getPost();
            try {
                // Mettre à jour l'attribution
                $this->assignmentModel->returnLocker(
                    $postData['assignment_id'],
                    [
                        'return_date' => $postData['return_date'],
                        'condition' => $postData['condition'],
                        'notes' => $postData['notes']
                    ]
                );

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Le casier a été restitué avec succès.'
                ];

                $this->redirect('/lockers');
                return;
            } catch (\Exception $e) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Une erreur est survenue lors de la restitution du casier.'
                ];
            }
        }

        $lockerId = $this->getGet('id');
        $assignment = $this->assignmentModel->getCurrentAssignment($lockerId);

        if (!$assignment) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Aucune attribution trouvée pour ce casier.'
            ];
            $this->redirect('/lockers');
            return;
        }

        $data = [
            'title' => 'Restitution du casier',
            'assignment' => $assignment
        ];

        echo $this->render('lockers/return', $data);
    }

    //methode getDetails 

    public function details($id = null) {
        // Si l'ID n'est pas fourni, rediriger vers la liste
        if (!$id) {
            $id = $this->getGet('id');
            if (!$id) {
                $this->redirect('/lockers');
                return;
            }
        }
    
        // Récupérer les informations du casier
        $locker = $this->lockerModel->findById($id);
        if (!$locker) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Casier non trouvé.'
            ];
            $this->redirect('/lockers');
            return;
        }
    
        // Récupérer l'attribution actuelle si le casier est attribué
        $currentAssignment = null;
        if ($locker['status'] === 'ATTRIBUE') {
            $currentAssignment = $this->assignmentModel->getCurrentAssignment($id);
        }
    
        // Récupérer l'historique des attributions
        $history = $this->assignmentModel->getAssignmentHistory($id);
    
        $data = [
            'title' => "Détails du casier {$locker['locker_number']}",
            'locker' => $locker,
            'currentAssignment' => $currentAssignment,
            'history' => $history
        ];
    
        echo $this->render('lockers/details', $data);
    }


}




