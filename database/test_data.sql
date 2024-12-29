USE locker_system;

-- Désactiver les vérifications de clé étrangère temporairement
SET FOREIGN_KEY_CHECKS = 0;

-- Nettoyage des tables dans le bon ordre
TRUNCATE TABLE audit_logs;
TRUNCATE TABLE locker_assignments;
TRUNCATE TABLE lockers;

-- Réactiver les vérifications de clé étrangère
SET FOREIGN_KEY_CHECKS = 1;

-- Ajout des rôles de test (si pas déjà présents)
INSERT IGNORE INTO roles (name, permissions) VALUES
('admin', '{"all": true}'),
('user', '{"read": true, "assign": true}'),
('worker', '{"read": true, "assign": true, "return": true}');

-- Ajout des utilisateurs de test
INSERT IGNORE INTO users (email, password_hash, first_name, last_name, role_id) VALUES
('admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'System', 1),
('ts@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Dupont', 2),
('worker@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marie', 'Martin', 3);

-- Réinitialisation de l'auto-increment
ALTER TABLE lockers AUTO_INCREMENT = 1;

-- Ajout des 50 casiers
INSERT INTO lockers (locker_number, status) VALUES
(1, 'DISPONIBLE'),
(2, 'DISPONIBLE'),
(3, 'DISPONIBLE'),
(4, 'DISPONIBLE'),
(5, 'DISPONIBLE'),
(6, 'DISPONIBLE'),
(7, 'DISPONIBLE'),
(8, 'DISPONIBLE'),
(9, 'DISPONIBLE'),
(10, 'DISPONIBLE'),
(11, 'DISPONIBLE'),
(12, 'DISPONIBLE'),
(13, 'DISPONIBLE'),
(14, 'DISPONIBLE'),
(15, 'DISPONIBLE'),
(16, 'DISPONIBLE'),
(17, 'DISPONIBLE'),
(18, 'DISPONIBLE'),
(19, 'DISPONIBLE'),
(20, 'DISPONIBLE'),
(21, 'DISPONIBLE'),
(22, 'DISPONIBLE'),
(23, 'DISPONIBLE'),
(24, 'DISPONIBLE'),
(25, 'DISPONIBLE'),
(26, 'DISPONIBLE'),
(27, 'DISPONIBLE'),
(28, 'DISPONIBLE'),
(29, 'DISPONIBLE'),
(30, 'DISPONIBLE'),
(31, 'DISPONIBLE'),
(32, 'DISPONIBLE'),
(33, 'DISPONIBLE'),
(34, 'DISPONIBLE'),
(35, 'DISPONIBLE'),
(36, 'DISPONIBLE'),
(37, 'DISPONIBLE'),
(38, 'DISPONIBLE'),
(39, 'DISPONIBLE'),
(40, 'DISPONIBLE'),
(41, 'DISPONIBLE'),
(42, 'DISPONIBLE'),
(43, 'DISPONIBLE'),
(44, 'DISPONIBLE'),
(45, 'DISPONIBLE'),
(46, 'DISPONIBLE'),
(47, 'DISPONIBLE'),
(48, 'DISPONIBLE'),
(49, 'DISPONIBLE'),
(50, 'DISPONIBLE');


-- Création de quelques attributions de casiers
INSERT INTO locker_assignments (locker_id, user_id, assignment_date, status, notes, service) VALUES
(1, 2, DATE_SUB(NOW(), INTERVAL 5 DAY), 'ATTRIBUE', 'Attribution standard', 'SAO'),
(2, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'RESTITUE', 'Retourné en bon état', 'RSA'),
(3, 2, DATE_SUB(NOW(), INTERVAL 15 DAY), 'ATTRIBUE', 'Attribution longue durée', 'URGENCE');

-- Mise à jour du statut des casiers attribués
UPDATE lockers SET status = 'ATTRIBUE' WHERE id IN (1, 3);

-- Ajout de quelques logs d'audit
INSERT INTO audit_logs (user_id, action, old_values, new_values, ip_address) VALUES
(1, 'CREATE_LOCKER', NULL, '{"locker_number": "1"}', '127.0.0.1'),
(2, 'ASSIGN_LOCKER', '{"status": "DISPONIBLE"}', '{"status": "ATTRIBUE"}', '127.0.0.1'),
(3, 'RETURN_LOCKER', '{"status": "ATTRIBUE"}', '{"status": "DISPONIBLE"}', '127.0.0.1');

-- Réactiver les vérifications de clé étrangère pour s'assurer qu'elles sont bien activées
SET FOREIGN_KEY_CHECKS = 1;