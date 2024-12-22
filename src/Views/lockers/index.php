<?php ob_start(); ?>

<div class="dashboard">
    <!-- Statistiques -->
    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Casiers</h3>
            <p><?= $stats['total'] ?></p>
        </div>
        <div class="stat-box">
            <h3>Disponibles</h3>
            <p><?= $stats['available'] ?></p>
        </div>
        <div class="stat-box">
            <h3>Attribués</h3>
            <p><?= $stats['assigned'] ?></p>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions">
        <a href="<?= BASE_PATH ?>/lockers/assign" class="button">Attribuer un casier</a>
    </div>

    <!-- Liste des casiers -->
    <div class="lockers-list">
        <h2>Liste des Casiers</h2>
        
        <table class="lockers-table">
            <thead>
                <tr>
                    <th>N° Casier</th>
                    <th>Statut</th>
                    <th>Utilisateur</th>
                    <th>Date d'attribution</th>
                    <th>Date de restitution</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lockers)): ?>
                    <?php foreach ($lockers as $locker): ?>
                        <tr class="locker-row <?= strtolower($locker['status']) ?>">
                            <td><?= htmlspecialchars($locker['locker_number']) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($locker['status']) ?>">
                                    <?= htmlspecialchars($locker['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($locker['status'] === 'ATTRIBUE' && isset($locker['first_name'])): ?>
                                    <?= htmlspecialchars($locker['first_name'] . ' ' . $locker['last_name']) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= isset($locker['assignment_date']) 
                                    ? date('d/m/Y', strtotime($locker['assignment_date'])) 
                                    : '-' ?>
                            </td>
                            <!-- retirer code ja -->
                            <td>
                                <?= isset($locker['expected_return_date']) 
                                    ? date('d/m/Y', strtotime($locker['expected_return_date'])) 
                                    : '-' ?>
                            </td>
                             <!-- Retirer code ja -->
                            <td class="actions-cell">
                                <a href="<?= BASE_PATH ?>/lockers/details/<?= $locker['id'] ?>" 
                                   class="button button-small">Détails</a>
                                
                                <?php if ($locker['status'] === 'ATTRIBUE'): ?>
                                    <a href="<?= BASE_PATH ?>/lockers/return?id=<?= $locker['id'] ?>" 
                                       class="button button-small button-secondary">Restituer</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">Aucun casier trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>