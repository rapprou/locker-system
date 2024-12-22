<?php ob_start(); ?>

<div class="return-form-container">
    <h2>Restitution du Casier</h2>

    <?php if (isset($assignment)): ?>
    <div class="assignment-info">
        <p><strong>Casier N°:</strong> <?= htmlspecialchars($assignment['locker_number']) ?></p>
        <p><strong>Utilisateur:</strong> <?= htmlspecialchars($assignment['first_name'] . ' ' . $assignment['last_name']) ?></p>
        <p><strong>Date d'attribution:</strong> <?= date('d/m/Y', strtotime($assignment['assignment_date'])) ?></p>
    </div>

    <form method="POST" action="<?= BASE_PATH ?>/lockers/return" class="return-form">
        <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
        <input type="hidden" name="locker_id" value="<?= $assignment['locker_id'] ?>">

        <div class="form-group">
            <label for="return_date">Date de restitution :</label>
            <input type="date" id="return_date" name="return_date" 
                   value="<?= date('Y-m-d') ?>" required class="form-control">
        </div>

        <div class="form-group">
            <label for="condition">État du casier :</label>
            <select id="condition" name="condition" required class="form-control">
                <option value="BON">Bon état</option>
                <option value="MOYEN">État moyen</option>
                <option value="MAUVAIS">Mauvais état</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">Commentaires :</label>
            <textarea id="notes" name="notes" class="form-control" rows="4"
                      placeholder="État du casier, objets laissés, dégradations éventuelles..."></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="button">Confirmer la restitution</button>
            <a href="<?= BASE_PATH ?>/lockers" class="button button-secondary">Annuler</a>
        </div>
    </form>
    <?php else: ?>
    <div class="error-message">
        <p>Aucune attribution trouvée pour ce casier.</p>
        <a href="<?= BASE_PATH ?>/lockers" class="button">Retour à la liste</a>
    </div>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>