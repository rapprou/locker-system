-- Suppression de la base si elle existe
DROP DATABASE IF EXISTS locker_system;

-- Création de la base de données
CREATE DATABASE locker_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE locker_system;

-- Table des rôles
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20) UNIQUE NOT NULL,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    role_id INT,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Table des casiers
CREATE TABLE lockers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    locker_number VARCHAR(10) UNIQUE NOT NULL,
    status ENUM('DISPONIBLE', 'ATTRIBUE', 'MAINTENANCE') DEFAULT 'DISPONIBLE',
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des attributions de casiers
CREATE TABLE locker_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    locker_id INT NOT NULL,
    user_id INT NOT NULL,
    assignment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    return_date TIMESTAMP NULL,
    signature BLOB,
    status ENUM('ACTIVE', 'RETURNED', 'CANCELLED') DEFAULT 'ACTIVE',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (locker_id) REFERENCES lockers(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des logs d'audit
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(50) NOT NULL,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insertion des rôles par défaut
INSERT INTO roles (name, permissions) VALUES
('admin', '{"all": true}'),
('user', '{"read": true, "assign": true}');

-- Index pour améliorer les performances
CREATE INDEX idx_locker_status ON lockers(status);
CREATE INDEX idx_assignment_status ON locker_assignments(status);
CREATE INDEX idx_audit_action ON audit_logs(action);