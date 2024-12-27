<?php ob_start(); ?>

<!-- Vue formulaire attribution casier -->

<div class="assign-form-container">
    <h2>Attribution d'un Casier</h2>

    <form method="POST" action="<?= BASE_PATH ?>/lockers/assign" class="assign-form">
        <div class="form-group">
            <label for="locker_id">Numéro du Casier :</label>
            <select name="locker_id" id="locker_id" required class="form-control">
                <option value="">Sélectionnez un casier</option>
                <?php foreach ($availableLockers as $locker): ?>
                    <option value="<?= $locker['id'] ?>">
                        Casier <?= htmlspecialchars($locker['locker_number']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="user_name">Nom complet :</label>
            <input type="text" id="user_name" name="user_name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="social_worker">Travailleur Social :</label>
            <input type="text" id="social_worker" name="social_worker" required class="form-control">
        </div>

        <div class="form-group">
            <label for="service">Service :</label>
            <select id="service" name="service" required class="form-control">
                <option value="">Sélectionnez un service</option>
                <option value="URGENCE">URGENCE</option>
                <option value="SAO">SAO</option>
                <option value="RSA">RSA</option>
                <option value="ADJ">ADJ</option>
            </select>
        </div>

        <div class="form-group">
            <label for="expected_return_date">Date de restitution prévue :</label>
            <input type="date" id="expected_return_date" name="expected_return_date" 
                   required class="form-control"
                   min="<?= date('Y-m-d') ?>">
        </div>

        <div class="form-group">
            <label for="notes">Notes (optionnel) :</label>
            <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="button">Attribution casier</button>
            <a href="<?= BASE_PATH ?>/lockers" class="button button-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>