<?php ob_start(); ?>

<div class="return-container">
   <h2>Restitution du Casier</h2>

   <?php if (isset($assignment)): ?>
   <div class="return-info">
       <p><strong>Casier N°:</strong> <?= htmlspecialchars($assignment['locker_number']) ?></p>
       <p><strong>Utilisateur:</strong> <?= htmlspecialchars($assignment['full_name']) ?></p>
       <p><strong>Date d'attribution:</strong> <?= date('d/m/Y', strtotime($assignment['assignment_date'])) ?></p>

       <form method="POST" action="<?= BASE_PATH ?>/lockers/return">
           <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
           <input type="hidden" name="locker_id" value="<?= $assignment['locker_id'] ?>">

           <div class="form-group">
               <label for="return_date">Date de restitution:</label>
               <input type="date" id="return_date" name="return_date" value="<?= date('Y-m-d') ?>" required>
           </div>

           <div class="form-group">
               <label for="condition">État du casier:</label>
               <select id="condition" name="condition" required>
                   <option value="Bon état">Bon état</option>
                   <option value="État moyen">État moyen</option>
                   <option value="Mauvais état">Mauvais état</option>
               </select>
           </div>

           <div class="form-group">
               <label for="notes">Commentaires:</label>
               <textarea id="notes" name="notes" rows="4" placeholder="État du casier, objets laissés, dégradations éventuelles..."></textarea>
           </div>

           <div class="actions">
               <button type="submit" class="button">Confirmer la restitution</button>
               <a href="<?= BASE_PATH ?>/lockers" class="button button-cancel">Annuler</a>
           </div>
       </form>
   </div>
   <?php else: ?>
   <div class="error-message">
       <p>Aucune attribution trouvée pour ce casier.</p>
       <a href="<?= BASE_PATH ?>/lockers" class="button">Retour à la liste</a>
   </div>
   <?php endif; ?>
</div>

<style>
.return-container {
   max-width: 800px;
   margin: 0 auto;
   padding: 20px;
}

.return-info {
   margin-bottom: 20px;
}

.form-group {
   margin-bottom: 15px;
}

.form-group label {
   display: block;
   margin-bottom: 5px;
   font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
   width: 100%;
   padding: 8px;
   border: 1px solid #ddd;
   border-radius: 4px;
}

.actions {
   margin-top: 20px;
}

.button {
   padding: 8px 16px;
   border-radius: 4px;
   cursor: pointer;
   text-decoration: none;
}

.button-cancel {
   margin-left: 10px;
}

.error-message {
   color: red;
   text-align: center;
   padding: 20px;
}
</style>

<?php 
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>