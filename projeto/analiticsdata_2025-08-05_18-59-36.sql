# ************************************************************
# Antares - SQL Client
# Version 0.7.35
# 
# https://antares-sql.app/
# https://github.com/antares-sql/antares
# 
# Host: 127.0.0.1 (MySQL Community Server - GPL 8.0.43)
# Database: analiticsdata
# Generation time: 2025-08-05T19:00:51-03:00
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table acls
# ------------------------------------------------------------

DROP TABLE IF EXISTS `acls`;

CREATE TABLE `acls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `acl` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `state` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `acl` (`acl`),
  KEY `idx_acl_state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `acls` WRITE;
/*!40000 ALTER TABLE `acls` DISABLE KEYS */;

INSERT INTO `acls` (`id`, `acl`, `description`, `state`) VALUES
	(1, "ver_dashboard", "Pode ver o dashboard", 1),
	(2, "editar_usuario", "Pode editar usuários", 1),
	(3, "apagar_post", "Pode apagar posts", 1);

/*!40000 ALTER TABLE `acls` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table group_acls
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_acls`;

CREATE TABLE `group_acls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fk_group` int NOT NULL,
  `fk_acls` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_group` (`fk_group`,`fk_acls`),
  KEY `fk_acls` (`fk_acls`),
  KEY `idx_group_acl` (`fk_group`,`fk_acls`),
  CONSTRAINT `group_acls_ibfk_1` FOREIGN KEY (`fk_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `group_acls_ibfk_2` FOREIGN KEY (`fk_acls`) REFERENCES `acls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `group_acls` WRITE;
/*!40000 ALTER TABLE `group_acls` DISABLE KEYS */;

INSERT INTO `group_acls` (`id`, `fk_group`, `fk_acls`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3);

/*!40000 ALTER TABLE `group_acls` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
	(1, "Admin", "Grupo de administradores");

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fk_group` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_user_group` (`fk_group`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`fk_group`) REFERENCES `groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `fk_group`, `created_at`) VALUES
	(1, "João Admin", "admin@example.com", "8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92", 1, "2025-08-03 01:09:04");

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of views
# ------------------------------------------------------------

# Creating temporary tables to overcome VIEW dependency errors


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

# Dump completed on 2025-08-05T19:00:51-03:00
