<?php ob_start(); ?>

<div class="home-container">
    <h2>Bienvenue sur le Système de Gestion de Casiers</h2>
    
    <div class="welcome-content">
        <p>Ce système vous permet de :</p>
        <ul>
            <li>Consulter la liste des casiers disponibles</li>
            <li>Attribuer un casier à un utilisateur</li>
            <li>Gérer les restitutions de casiers</li>
            <li>Suivre l'historique des utilisations</li>
        </ul>
    </div>

    <div class="quick-actions">
        <h3>Actions rapides</h3>
        <div class="action-buttons">
            <a href="<?= BASE_PATH ?>/lockers" class="button">Voir les casiers</a>
            <a href="<?= BASE_PATH ?>/lockers/assign" class="button">Attribuer un casier</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
?>