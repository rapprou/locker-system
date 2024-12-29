-- MySQL dump 10.13  Distrib 9.0.1, for macos14.7 (arm64)
--
-- Host: localhost    Database: locker_system
-- ------------------------------------------------------
-- Server version	9.0.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_audit_action` (`action`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'CREATE_LOCKER',NULL,'{\"locker_number\": \"A001\"}','127.0.0.1','2024-12-22 18:37:38'),(2,2,'ASSIGN_LOCKER','{\"status\": \"DISPONIBLE\"}','{\"status\": \"ATTRIBUE\"}','127.0.0.1','2024-12-22 18:37:38'),(3,3,'RETURN_LOCKER','{\"status\": \"ATTRIBUE\"}','{\"status\": \"DISPONIBLE\"}','127.0.0.1','2024-12-22 18:37:38');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locker_assignments`
--

DROP TABLE IF EXISTS `locker_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locker_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `locker_id` int NOT NULL,
  `user_id` int NOT NULL,
  `assignment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `return_date` timestamp NULL DEFAULT NULL,
  `signature` blob,
  `status` enum('ACTIVE','RETURNED','CANCELLED') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `locker_id` (`locker_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_assignment_status` (`status`),
  CONSTRAINT `locker_assignments_ibfk_1` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`),
  CONSTRAINT `locker_assignments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locker_assignments`
--

LOCK TABLES `locker_assignments` WRITE;
/*!40000 ALTER TABLE `locker_assignments` DISABLE KEYS */;
INSERT INTO `locker_assignments` VALUES (1,1,2,'2024-12-17 18:37:38','2024-12-21 23:00:00',NULL,'RETURNED','Attribution standard\nRestitution: ','2024-12-22 18:37:38','2024-12-22 21:07:00'),(2,2,3,'2024-12-12 18:37:38',NULL,NULL,'RETURNED','Retourné en bon état','2024-12-22 18:37:38','2024-12-22 18:37:38'),(3,3,2,'2024-12-07 18:37:38','2024-12-21 23:00:00',NULL,'RETURNED','Attribution longue durée\nRestitution: ','2024-12-22 18:37:38','2024-12-22 21:02:27'),(4,4,4,'2024-12-22 19:21:04','2024-12-21 23:00:00',NULL,'RETURNED','Service: URGENCE\nTS: Mamolo\nDate retour prévue: 2024-12-31\nNotes: commentaires\nRestitution: ','2024-12-22 19:21:04','2024-12-22 21:00:27'),(5,2,5,'2024-12-22 19:27:48','2024-12-21 23:00:00',NULL,'RETURNED','Service: URGENCE\nTS: Mamolo\nDate retour prévue: 2024-12-31\nNotes: Comments\nRestitution: ','2024-12-22 19:27:48','2024-12-22 21:06:26'),(6,2,6,'2024-12-22 19:30:20','2024-12-21 23:00:00',NULL,'RETURNED','Service: URGENCE\nTS: Mamolo\nDate retour prévue: 2024-12-31\nNotes: comments\nRestitution: ','2024-12-22 19:30:20','2024-12-22 21:20:14'),(7,4,7,'2024-12-22 20:42:37','2024-12-21 23:00:00',NULL,'RETURNED','Service: SAO\nTS: Mamolo\nDate retour prévue: 2024-12-31\nNotes: comments\nRestitution: ','2024-12-22 20:42:37','2024-12-22 21:06:04'),(8,4,8,'2024-12-22 21:04:33',NULL,NULL,'ACTIVE','Service: URGENCE\nTS: Mamolo\nDate retour prévue: 2025-01-04\nNotes: comments','2024-12-22 21:04:33','2024-12-22 21:04:33'),(9,1,9,'2024-12-22 21:10:23','2024-12-24 23:00:00',NULL,'RETURNED','Service: URGENCE\nTS: Mamolo\nDate retour prévue: 2025-01-22\nNotes: prevoir comentario\nRestitution: ','2024-12-22 21:10:23','2024-12-25 12:45:00'),(10,2,10,'2024-12-22 21:13:30',NULL,NULL,'ACTIVE','Service: SAO\nTS: Mamolo\nDate retour prévue: 2025-01-22\nNotes: 22','2024-12-22 21:13:30','2024-12-22 21:13:30'),(11,3,11,'2024-12-22 21:20:59',NULL,NULL,'ACTIVE','Service: RSA\nTS: Mamolo\nDate retour prévue: 2025-01-22\nNotes: faire ','2024-12-22 21:20:59','2024-12-22 21:20:59'),(12,2,12,'2024-12-25 13:15:53',NULL,NULL,'ACTIVE','Service: SAO\nTS: Mamolo\nDate retour prévue: 2024-12-31\nNotes: andre          ','2024-12-25 13:15:53','2024-12-25 13:15:53');
/*!40000 ALTER TABLE `locker_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lockers`
--

DROP TABLE IF EXISTS `lockers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lockers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `locker_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('DISPONIBLE','ATTRIBUE','MAINTENANCE') COLLATE utf8mb4_unicode_ci DEFAULT 'DISPONIBLE',
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locker_number` (`locker_number`),
  KEY `idx_locker_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lockers`
--

LOCK TABLES `lockers` WRITE;
/*!40000 ALTER TABLE `lockers` DISABLE KEYS */;
INSERT INTO `lockers` VALUES (1,'A001','DISPONIBLE','2024-12-25 12:45:00',1,'2024-12-22 18:37:38','2024-12-25 12:45:00'),(2,'A002','ATTRIBUE','2024-12-25 13:15:53',1,'2024-12-22 18:37:38','2024-12-25 13:15:53'),(3,'A003','ATTRIBUE','2024-12-22 21:20:59',1,'2024-12-22 18:37:38','2024-12-22 21:20:59'),(4,'B001','DISPONIBLE','2024-12-22 21:06:04',1,'2024-12-22 18:37:38','2024-12-22 21:06:04'),(5,'B002','DISPONIBLE','2024-12-22 18:37:38',1,'2024-12-22 18:37:38','2024-12-22 18:37:38'),(6,'B003','MAINTENANCE','2024-12-22 18:37:38',1,'2024-12-22 18:37:38','2024-12-22 18:37:38');
/*!40000 ALTER TABLE `lockers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','{\"all\": true}','2024-12-22 18:17:49'),(2,'user','{\"read\": true, \"assign\": true}','2024-12-22 18:17:49'),(3,'worker','{\"read\": true, \"assign\": true, \"return\": true}','2024-12-22 18:37:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Admin','System',1,NULL,1,'2024-12-22 18:37:38','2024-12-22 18:37:38'),(2,'ts@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Jean','Dupont',2,NULL,1,'2024-12-22 18:37:38','2024-12-22 18:37:38'),(3,'worker@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Marie','Martin',3,NULL,1,'2024-12-22 18:37:38','2024-12-22 18:37:38'),(4,'temp_1734895263@example.com','$2y$12$hvTQLRXlWuQGqKAnvJI0AenKJQoWRu0EQmUxaSE1J.rEkEEXGK7/K','jean','',2,NULL,1,'2024-12-22 19:21:04','2024-12-22 19:21:04'),(5,'temp_1734895667@example.com','$2y$12$9jqy5AHc7VfnSrwm6YJui.IcMUL81eFZ7ClSZHDp.FZrAmKzgyUTu','Jean Dupont\"','',2,NULL,1,'2024-12-22 19:27:48','2024-12-22 19:27:48'),(6,'temp_1734895819@example.com','$2y$12$VaTBrUaMEVEzXD9kMmO9/eaKTVp9f7EPBGJ0sQkEmzhMphE0WfegK','Jean Dupont','',2,NULL,1,'2024-12-22 19:30:20','2024-12-22 19:30:20'),(7,'temp_1734900157@example.com','$2y$12$hogu9MrYObHpZI03H5cuKu6rc0AIsAlECIWfAzw9HFnwpSJkp34OS','jean','',2,NULL,1,'2024-12-22 20:42:37','2024-12-22 20:42:37'),(8,'temp_1734901472@example.com','$2y$12$1rGzKMj9Ax8jspw2SXhFUelMEz8tjrVCADyYtVbu76BM.HY91UZ/K','Thomas','',2,NULL,1,'2024-12-22 21:04:33','2024-12-22 21:04:33'),(9,'temp_1734901822@example.com','$2y$12$c9N07GpWFxbrA34OkjgtN.v0.bwrwoPoK1PI0xkgDW2FFAwYuA5Ti','Martinez','',2,NULL,1,'2024-12-22 21:10:23','2024-12-22 21:10:23'),(10,'temp_1734902010@example.com','$2y$12$9p/2wBZBHmgL2lcZ/L/AbOTaDLi9x0wp2C/7sYXRajNfqfjsXJiKG','Rennes','',2,NULL,1,'2024-12-22 21:13:30','2024-12-22 21:13:30'),(11,'temp_1734902458@example.com','$2y$12$qJbt/AsTacamEgj.XnXKQuXgrUSk5YQJTyobNNYktRE0wujotjaty','DEDIER','',2,NULL,1,'2024-12-22 21:20:59','2024-12-22 21:20:59'),(12,'temp_1735132553@example.com','$2y$12$UpkGpWyphcN1F4l98tDeiOKjMMXu5YhPBE/O9JAxlJeec6RY7I7Gy','Andre Rou','',2,NULL,1,'2024-12-25 13:15:53','2024-12-25 13:15:53');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-28 11:40:41
