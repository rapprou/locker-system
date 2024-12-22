<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Configuration de base
const BASE_PATH = '/locker-system/public';

// Router simple
$request = $_SERVER['REQUEST_URI'];

// Nettoyer l'URL et retirer les paramètres GET
$urlParts = parse_url($request);
$path = $urlParts['path'];
$request = str_replace(BASE_PATH, '', $path);

// Router basique
try {
    switch ($request) {
        case '':
        case '/':
            require __DIR__ . '/../src/Views/home.php';
            break;
            
        case '/lockers':
            $controller = new App\Controllers\LockerController();
            $controller->index();
            break;
            
        case '/lockers/assign':
            $controller = new App\Controllers\LockerController();
            $controller->assign();
            break;
            
        case '/lockers/return':
            $controller = new App\Controllers\LockerController();
            $controller->return();
            break;
            
        default:
            // Vérifier si c'est une URL de détails
            if (preg_match('/^\/lockers\/details\/(\d+)$/', $request, $matches)) {
                $controller = new App\Controllers\LockerController();
                $controller->details($matches[1]);
                break;
            }
            
            http_response_code(404);
            echo '404 - Page non trouvée';
            break;
    }
} catch (\Exception $e) {
    // Gestion basique des erreurs
    http_response_code(500);
    echo 'Une erreur est survenue : ' . $e->getMessage();
}