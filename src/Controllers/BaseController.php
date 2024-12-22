<?php
namespace App\Controllers;

class BaseController {
    protected function render($view, $data = []) {
        // Extrait les données dans des variables
        extract($data);
        
        // Démarre la mise en tampon
        ob_start();
        
        // Inclut la vue
        require_once __DIR__ . "/../Views/$view.php";
        
        // Récupère le contenu et nettoie le tampon
        return ob_get_clean();
    }
    
    protected function redirect($path) {
        header('Location: ' . BASE_PATH . $path);
        exit();
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
}