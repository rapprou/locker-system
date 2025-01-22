<?php ob_start(); ?>

<div class="details-container">

    <!-- Vue formulaire détail casier -->
    <div class="details-header">
        <h2>Détails Casier N°<?= htmlspecialchars($locker['locker_number']) ?></h2>
        <div class="status-badge status-<?= strtolower($locker['status']) ?>">
            <?= htmlspecialchars($locker['status']) ?>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="details-card">
        <h3>Informations Générales</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Numéro:</label>
                <span><?= htmlspecialchars($locker['locker_number']) ?></span>
            </div>
            <div class="info-item">
                <label>Statut:</label>
                <span><?= htmlspecialchars($locker['status']) ?></span>
            </div>
            <div class="info-item">
                <label>Dernière mise à jour:</label>
                <span><?= date('d/m/Y H:i', strtotime($locker['last_update'])) ?></span>
            </div>
        </div>
    </div>

    <!-- Attribution actuelle si le casier est attribué -->
    <?php if ($locker['status'] === 'ATTRIBUE' && isset($currentAssignment)): ?>
        <div class="details-card">
            <h3>Attribution Actuelle</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Utilisateur:</label>
                        <span><?= htmlspecialchars($currentAssignment['first_name'] ?? '') . ' ' . htmlspecialchars($currentAssignment['last_name'] ?? '') ?></span>
                    </div>   
                    <div class="info-item">
                        <label>Service:</label>
                        <span><?= htmlspecialchars($currentAssignment['service'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <label>TS:</label>
                        <span><?= htmlspecialchars($currentAssignment['ts_name'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Date retour prévue:</label>
                        <span><?= $currentAssignment['expected_return_date'] ? date('d/m/Y', strtotime($currentAssignment['expected_return_date'])) : '-' ?></span>
                    </div>
                    <div class="info-item">
                    
                    
                </div>
        </div>
    <?php endif; ?>

    <!-- bouton retour a la liste et restitution -->
    <div class="details-actions">
        <a href="<?= BASE_PATH ?>/lockers" class="button">Retour à la liste</a>
        <?php if ($locker['status'] === 'ATTRIBUE'): ?>
            <a href="<?= BASE_PATH ?>/lockers/return?id=<?= $locker['id'] ?>" 
               class="button button-warning">Restituer</a>
        <?php endif; ?>
    </div>

    <!-- Historique des attributions -->
<div class="details-card">
    <h3>Historique des Attributions</h3>
        <?php if (!empty($history)): ?>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Statut</th>
                    <th>Notes</th>
                    <th>Service</th>
                    <th>TS</th>
                    <th>Date retour</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $record): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($record['assignment_date'])) ?></td>
                    <td><?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?></td>
                    <td>
                        <span class="status-badge status-<?= strtolower($record['status']) ?>">
                            <?= htmlspecialchars($record['status']) ?>
                        </span>
                    </td>
                    <td><?= nl2br(htmlspecialchars($record['notes'])) ?></td>
                    <td><?= htmlspecialchars($record['service']) ?></td>
                    <td><?= htmlspecialchars($record['ts_name']) ?></td>
                    <td><?= $record['return_date'] ? date('d/m/Y', strtotime($record['return_date'])) : '-' ?></td>
                    <td><?= htmlspecialchars($record['assignment_type'] ?? 'Attribution standard') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-data">Aucun historique d'attribution disponible</p>
    <?php endif; ?>
    </div>

    <!-- Actions -->
    <div class="details-actions">
        <a href="<?= BASE_PATH ?>/lockers" class="button">Retour à la liste</a>
        <?php if ($locker['status'] === 'ATTRIBUE'): ?>
            <a href="<?= BASE_PATH ?>/lockers/return?id=<?= $locker['id'] ?>" 
               class="button button-warning">Restituer</a>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>