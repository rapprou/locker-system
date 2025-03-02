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
            
            $this->assignmentModel->returnLocker($postData['assignment_id'], [
                'return_date' => $postData['return_date'],
                'notes' => $postData['notes']
            ]);
    
            header('Location: ' . BASE_PATH . '/lockers');
            exit();
        }
        
        $lockerId = $this->getGet('id');
        $assignment = $this->assignmentModel->getCurrentAssignment($lockerId);
        
        echo $this->render('lockers/return', [
            'title' => 'Restitution du casier',
            'assignment' => $assignment
        ]);
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
    
        // Utiliser la nouvelle méthode pour récupérer toutes les informations
        $details = $this->assignmentModel->getFullLockerDetails($id);
    
        // Si aucun détail n'est trouvé, rediriger avec message d'erreur
        if (!$details) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Casier non trouvé.'
            ];
            $this->redirect('/lockers');
            return;
        }
    
        // Préparer les données pour la vue
        $data = [
            'title' => "Détails du casier {$details['locker']['locker_number']}",
            'locker' => $details['locker'],
            'currentAssignment' => $details['currentAssignment'],
            'history' => $details['history']
        ];
    
        echo $this->render('lockers/details', $data);
    }



    protected function redirect($path) {
        $redirectUrl = BASE_PATH . $path;
        error_log("Redirection vers: " . $redirectUrl);
        header('Location: ' . $redirectUrl);
        exit();
    }


}




