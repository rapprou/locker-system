USE locker_system;

-- Ajout des rôles de test (si pas déjà présents)
INSERT IGNORE INTO roles (name, permissions) VALUES
('admin', '{"all": true}'),
('user', '{"read": true, "assign": true}'),
('worker', '{"read": true, "assign": true, "return": true}');

-- Ajout des utilisateurs de test
INSERT INTO users (email, password_hash, first_name, last_name, role_id) VALUES
('admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'System', 1),
('ts@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Dupont', 2),
('worker@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marie', 'Martin', 3);
-- Note: Le mot de passe est 'password' pour tous les utilisateurs

-- Ajout des casiers de test
INSERT INTO lockers (locker_number, status) VALUES
('A001', 'DISPONIBLE'),
('A002', 'DISPONIBLE'),
('A003', 'DISPONIBLE'),
('B001', 'DISPONIBLE'),
('B002', 'DISPONIBLE'),
('B003', 'MAINTENANCE');

-- Création de quelques attributions de casiers
INSERT INTO locker_assignments (locker_id, user_id, assignment_date, status, notes) VALUES
(1, 2, DATE_SUB(NOW(), INTERVAL 5 DAY), 'ACTIVE', 'Attribution standard'),
(2, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'RETURNED', 'Retourné en bon état'),
(3, 2, DATE_SUB(NOW(), INTERVAL 15 DAY), 'ACTIVE', 'Attribution longue durée');

-- Mise à jour du statut des casiers attribués
UPDATE lockers SET status = 'ATTRIBUE' WHERE id IN (1, 3);

-- Ajout de quelques logs d'audit
INSERT INTO audit_logs (user_id, action, old_values, new_values, ip_address) VALUES
(1, 'CREATE_LOCKER', NULL, '{"locker_number": "A001"}', '127.0.0.1'),
(2, 'ASSIGN_LOCKER', '{"status": "DISPONIBLE"}', '{"status": "ATTRIBUE"}', '127.0.0.1'),
(3, 'RETURN_LOCKER', '{"status": "ATTRIBUE"}', '{"status": "DISPONIBLE"}', '127.0.0.1');