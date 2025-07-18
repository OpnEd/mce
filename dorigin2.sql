-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: d_origin2
-- ------------------------------------------------------
-- Server version	8.4.2

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
-- Table structure for table `anesthesia_sheet_items`
--

DROP TABLE IF EXISTS `anesthesia_sheet_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anesthesia_sheet_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anesthesia_sheet_id` bigint unsigned NOT NULL,
  `phase` enum('pre_anesthesia','intraoperative','post_anesthesia') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pre_anesthesia',
  `inventory_id` bigint unsigned NOT NULL,
  `dose_per_kg` decimal(8,2) NOT NULL,
  `dose_measure` decimal(8,2) NOT NULL,
  `dose_measure_unit` enum('mg','mL','tab','UI') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mL',
  `administration_route` enum('iv','im','subcutaneous','intradermic','oral','rectal','respiratory','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'iv',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anesthesia_sheet_items_anesthesia_sheet_id_foreign` (`anesthesia_sheet_id`),
  CONSTRAINT `anesthesia_sheet_items_anesthesia_sheet_id_foreign` FOREIGN KEY (`anesthesia_sheet_id`) REFERENCES `anesthesia_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anesthesia_sheet_items`
--

LOCK TABLES `anesthesia_sheet_items` WRITE;
/*!40000 ALTER TABLE `anesthesia_sheet_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `anesthesia_sheet_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anesthesia_sheets`
--

DROP TABLE IF EXISTS `anesthesia_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anesthesia_sheets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `recipe_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Recipe number for the anesthesia sheet',
  `customer_id` bigint unsigned NOT NULL,
  `surgeon_id` bigint unsigned NOT NULL,
  `anamnesis` json DEFAULT NULL,
  `anesthesia_notes` json DEFAULT NULL,
  `anesthesia_start_time` timestamp NULL DEFAULT NULL,
  `anesthesia_end_time` timestamp NULL DEFAULT NULL,
  `status` enum('opened','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'opened' COMMENT 'Status of the anesthesia sheet: opened or closed',
  `consumed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `pet_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anesthesia_sheets_team_id_foreign` (`team_id`),
  KEY `anesthesia_sheets_user_id_foreign` (`user_id`),
  KEY `anesthesia_sheets_customer_id_foreign` (`customer_id`),
  KEY `anesthesia_sheets_surgeon_id_foreign` (`surgeon_id`),
  KEY `anesthesia_sheets_pet_id_foreign` (`pet_id`),
  CONSTRAINT `anesthesia_sheets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anesthesia_sheets_pet_id_foreign` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anesthesia_sheets_surgeon_id_foreign` FOREIGN KEY (`surgeon_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anesthesia_sheets_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anesthesia_sheets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anesthesia_sheets`
--

LOCK TABLES `anesthesia_sheets` WRITE;
/*!40000 ALTER TABLE `anesthesia_sheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `anesthesia_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batches`
--

DROP TABLE IF EXISTS `batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned DEFAULT NULL,
  `sanitary_registry_id` bigint unsigned NOT NULL,
  `manufacturer_id` bigint unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturing_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `batches_code_unique` (`code`),
  KEY `batches_sanitary_registry_id_foreign` (`sanitary_registry_id`),
  CONSTRAINT `batches_sanitary_registry_id_foreign` FOREIGN KEY (`sanitary_registry_id`) REFERENCES `sanitary_registries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
INSERT INTO `batches` VALUES (1,1,1,1,'BATCH-2023-001','2023-10-01','2025-10-01',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(2,1,1,1,'BATCH-2023-002','2023-10-05','2025-10-05',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(3,1,1,1,'BATCH-2023-003','2023-10-10','2025-10-10',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(4,1,1,1,'BATCH-2023-004','2023-11-01','2025-11-01',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(5,1,20,1,'GY5FH5','2025-01-21','2026-03-16',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(6,1,11,1,'TWBZFN','2024-08-18','2026-01-10',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(7,1,12,1,'ZHWNTH','2024-09-23','2026-05-26',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(8,1,13,1,'SCCPTX','2025-05-02','2026-02-02',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(9,1,15,1,'W9OHAV','2025-04-16','2025-10-09',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(10,1,20,1,'KXLLTT','2024-08-21','2025-11-03',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(11,1,17,1,'UIBLSN','2024-09-16','2026-04-18',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(12,1,12,1,'MV4Y27','2025-02-23','2026-07-14',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(13,1,12,1,'Y4T1G7','2025-04-21','2026-05-31',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(14,1,16,1,'3XHPVF','2024-09-25','2025-12-07',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(15,1,15,1,'GTYMRT','2024-08-19','2025-08-21',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(16,1,14,1,'OVZYZD','2025-01-24','2026-06-03',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(17,1,20,1,'AZK55E','2025-06-01','2025-10-24',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(18,1,15,1,'5ZQX6A','2024-12-13','2025-11-27',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(19,1,11,1,'GLCP7N','2024-07-31','2026-01-05',NULL,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(20,1,16,1,'ZQWB6D','2024-11-14','2025-08-20',NULL,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(21,1,18,1,'PR7LLC','2025-04-05','2025-12-25',NULL,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(22,1,14,1,'ZMO9GI','2025-03-26','2025-11-30',NULL,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(23,1,14,1,'IZNQWC','2025-05-31','2025-11-23',NULL,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(24,1,19,1,'P97ROE','2024-11-20','2025-10-29',NULL,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('pqm_cache_spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"f\";s:7:\"team_id\";}s:11:\"permissions\";a:4:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"view-invoice\";s:1:\"c\";s:3:\"web\";s:1:\"f\";i:1;}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:14:\"create-invoice\";s:1:\"c\";s:3:\"web\";s:1:\"f\";i:1;}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"edit-invoice\";s:1:\"c\";s:3:\"web\";s:1:\"f\";i:1;}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"delete-invoice\";s:1:\"c\";s:3:\"web\";s:1:\"f\";i:1;}}s:5:\"roles\";a:0:{}}',1752861152);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `central_product_prices`
--

DROP TABLE IF EXISTS `central_product_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `central_product_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `min` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `central_product_prices_product_id_foreign` (`product_id`),
  CONSTRAINT `central_product_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `central_product_prices`
--

LOCK TABLES `central_product_prices` WRITE;
/*!40000 ALTER TABLE `central_product_prices` DISABLE KEYS */;
INSERT INTO `central_product_prices` VALUES (1,1,17.00,51.80,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,2,3.00,23.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(3,3,17.00,19.90,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(4,4,5.00,40.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(5,5,17.00,35.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(6,6,14.00,24.00,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(7,7,15.00,62.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(8,8,12.00,92.00,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(9,9,6.00,97.50,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(10,10,1.00,41.50,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(11,11,9.00,54.90,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(12,12,3.00,82.50,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(13,13,8.00,71.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(14,14,17.00,48.10,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(15,15,11.00,58.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(16,16,3.00,12.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(17,17,11.00,42.00,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(18,18,14.00,92.60,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(19,19,6.00,45.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(20,20,20.00,95.00,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(21,21,12.00,92.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(22,22,10.00,83.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(23,23,7.00,66.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(24,24,16.00,70.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(25,25,13.00,36.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(26,26,20.00,78.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(27,27,19.00,37.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(28,28,18.00,79.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(29,29,6.00,64.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(30,30,19.00,76.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(31,31,13.00,28.60,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(32,32,3.00,79.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(33,33,5.00,50.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(34,34,12.00,42.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(35,35,9.00,93.80,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(36,36,18.00,37.50,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(37,37,11.00,51.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(38,38,5.00,24.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(39,39,3.00,98.10,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(40,40,10.00,61.50,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(41,41,18.00,84.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(42,42,12.00,55.00,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(43,43,5.00,73.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(44,44,13.00,34.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(45,45,14.00,24.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(46,46,13.00,53.50,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(47,47,15.00,38.70,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(48,48,11.00,93.10,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(49,49,6.00,78.00,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(50,50,12.00,36.80,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `central_product_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist_item_answers`
--

DROP TABLE IF EXISTS `checklist_item_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklist_item_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `checklist_item_id` bigint unsigned NOT NULL,
  `meets` tinyint(1) NOT NULL DEFAULT '1',
  `apply` tinyint(1) NOT NULL DEFAULT '1',
  `evidence` json DEFAULT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checklist_item_answers_team_id_foreign` (`team_id`),
  KEY `checklist_item_answers_user_id_foreign` (`user_id`),
  KEY `checklist_item_answers_checklist_item_id_foreign` (`checklist_item_id`),
  CONSTRAINT `checklist_item_answers_checklist_item_id_foreign` FOREIGN KEY (`checklist_item_id`) REFERENCES `checklist_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checklist_item_answers_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checklist_item_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_item_answers`
--

LOCK TABLES `checklist_item_answers` WRITE;
/*!40000 ALTER TABLE `checklist_item_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `checklist_item_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist_items`
--

DROP TABLE IF EXISTS `checklist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `checklist_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checklist_items_team_id_foreign` (`team_id`),
  KEY `checklist_items_checklist_id_foreign` (`checklist_id`),
  CONSTRAINT `checklist_items_checklist_id_foreign` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checklist_items_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_items`
--

LOCK TABLES `checklist_items` WRITE;
/*!40000 ALTER TABLE `checklist_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `checklist_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklists`
--

DROP TABLE IF EXISTS `checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objective` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checklists_team_id_foreign` (`team_id`),
  KEY `checklists_process_id_foreign` (`process_id`),
  CONSTRAINT `checklists_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checklists_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklists`
--

LOCK TABLES `checklists` WRITE;
/*!40000 ALTER TABLE `checklists` DISABLE KEYS */;
/*!40000 ALTER TABLE `checklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_identification_unique` (`identification`),
  UNIQUE KEY `customers_email_unique` (`email`),
  KEY `customers_team_id_foreign` (`team_id`),
  CONSTRAINT `customers_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,8,'Altenwerth-Jacobson','20880c7d-7b47-32c9-8e91-43dde29bae37','8391 Ritchie Courts\nJohnathonberg, WI 88679','ursula59@heidenreich.com','678-970-1341','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(2,6,'Gleason-Lueilwitz','431924d4-bce5-3d92-8acd-aed23a8a94bc','8780 Hintz Spur Suite 603\nEast Tatum, SC 34626-1934','harmony.lakin@batz.com','(469) 242-4042','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(3,1,'Harris, O\'Kon and Welch','6565bd82-efb6-33c9-8dd3-5081d547d413','5576 Kamryn Forest\nAprilside, GA 53903','susanna44@connelly.com','503.287.3819','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(4,4,'Douglas-Bosco','a7e25147-befa-3c11-a34b-69b0b91e8517','47219 Larkin Causeway Suite 500\nJonesmouth, ND 60935','pgerhold@mayert.org','(267) 561-3416','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(5,4,'Schultz-Johnston','d2a2e260-132d-3bb1-8f8a-ea9470a437ff','595 Kirlin Run\nEast Einarview, KS 00453-1544','nstreich@will.net','234-486-9098','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(6,5,'Thiel PLC','159c52c2-31f5-390a-b56b-c1db029dcc3f','97007 Williamson Crescent Apt. 815\nHuldaburgh, UT 69077-1474','muller.constance@schneider.com','929.496.8913','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(7,6,'Gaylord Group','12e68c4a-fe6d-31ff-96c6-523cc2a68a54','7905 Morissette Ways Suite 057\nBartellborough, AL 37587','rlindgren@mertz.com','(678) 433-3067','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(8,9,'Bartoletti-Mayert','386e9af9-5cbd-333c-a3df-497b32240298','5788 Christiansen Ports\nNew Alex, ND 70107','yleuschke@pouros.com','(956) 805-8519','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(9,5,'Haag Group','a57d7381-1db5-398d-8fce-3cb390ebd36f','101 Janae Circle\nEast Makaylahaven, KY 43225','madelynn.olson@cole.com','+1-854-479-9558','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(10,7,'Feil-Yost','d6bab441-f38b-3e61-b16e-65fa1036c8ba','152 Aliyah Views Suite 330\nLadariusborough, RI 58485-6278','uoreilly@rowe.com','+1 (715) 412-1951','[\"\"]',NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(11,1,'Cliente Gen├®rico','88888888','Sin direcci├│n','generico@example.com','9999999999','[]',NULL,'2025-07-17 03:28:48','2025-07-17 03:28:48');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispatch_items`
--

DROP TABLE IF EXISTS `dispatch_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispatch_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dispatch_id` bigint unsigned NOT NULL,
  `purchase_item_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned DEFAULT NULL,
  `quantity` smallint NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispatch_items`
--

LOCK TABLES `dispatch_items` WRITE;
/*!40000 ALTER TABLE `dispatch_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispatch_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispatches`
--

DROP TABLE IF EXISTS `dispatches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispatches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `dispatched_at` datetime DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispatches`
--

LOCK TABLES `dispatches` WRITE;
/*!40000 ALTER TABLE `dispatches` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispatches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_categories`
--

DROP TABLE IF EXISTS `document_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_categories_code_unique` (`code`),
  KEY `document_categories_team_id_foreign` (`team_id`),
  CONSTRAINT `document_categories_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_categories`
--

LOCK TABLES `document_categories` WRITE;
/*!40000 ALTER TABLE `document_categories` DISABLE KEYS */;
INSERT INTO `document_categories` VALUES (1,NULL,'Manual','MN','Describe los fundamentos de alguna disciplina y se emplea como marco y justificaci├│n para el desarrollo de procesos y procedimientos.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(2,NULL,'Caracterizaci├│n de procesos','CT','Describe los principales razgos de los procesos, sus componentes, desde sus proveedores con sus entradas hasta donde el cliente con las salidas. Se├▒ala los responsables, los indicadores de gesti├│n, los recursos necesarios, puntos cr├¡ticos, riesgos, entre otros.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(3,NULL,'Indicador de gesti├│n','IG','Describe al indicador de gesti├│n, la fuenta de los datos que emplea, las f├│rmulas matem├íticas, su naturaleza y objetivos, su temporalidad, el responsable, entre otros.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(4,NULL,'Procedimiento','PR','Describe el paso a paso de alguna tarea u operaci├│n, contextualiz├índola en primera instancia, se├▒alando sus objetivos, su lugar en el mapa de procesos y la incidencia que tiene en el logro de los objtevos estrat├®gicos.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(5,NULL,'Instrucci├│n','IN','Indicaci├│n sobre c├│mo operar puntualmente alg├║n equipo o ejecutar alguna tarea puntual o m├¡nima.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(6,NULL,'Formulario','FM','Instrumento empleado para la recolecci├│n de informaci├│n (registro) demandada por indicadores de gesti├│n, actividades de evaluaci├│n, operaciones productivas, etc. Permite evidenciar la ejecuci├│n de tareas o procesos, o registrar el estado de las cosas en un momento determinado.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(7,NULL,'Tabla o Matriz','TM','Presenta informaci├│n tabulada.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25'),(8,NULL,'Gr├íficos e ilustraciones','GF','Diagramas, mapas, ilustraciones, etc.',NULL,NULL,'2025-07-16 06:46:25','2025-07-16 06:46:25');
/*!40000 ALTER TABLE `document_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `process_id` bigint unsigned NOT NULL,
  `document_category_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `records` json DEFAULT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_slug_unique` (`slug`),
  KEY `documents_team_id_foreign` (`team_id`),
  KEY `documents_process_id_foreign` (`process_id`),
  KEY `documents_document_category_id_foreign` (`document_category_id`),
  CONSTRAINT `documents_document_category_id_foreign` FOREIGN KEY (`document_category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `environmental_records`
--

DROP TABLE IF EXISTS `environmental_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `environmental_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `temp` decimal(8,2) NOT NULL,
  `hum` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `environmental_records`
--

LOCK TABLES `environmental_records` WRITE;
/*!40000 ALTER TABLE `environmental_records` DISABLE KEYS */;
INSERT INTO `environmental_records` VALUES (1,1,1,22.50,55.20,'2025-06-17 06:46:26','2025-06-17 06:46:26',NULL),(2,1,2,23.10,53.80,'2025-06-18 06:46:26','2025-06-18 06:46:26',NULL),(3,2,3,21.75,60.00,'2025-06-19 06:46:26','2025-06-19 06:46:26',NULL),(4,2,4,22.00,58.10,'2025-06-20 06:46:26','2025-06-20 06:46:26',NULL),(5,3,5,24.30,52.40,'2025-06-21 06:46:26','2025-06-21 06:46:26',NULL),(6,3,6,20.90,61.20,'2025-06-22 06:46:26','2025-06-22 06:46:26',NULL),(7,1,7,23.50,54.00,'2025-06-23 06:46:26','2025-06-23 06:46:26',NULL),(8,2,8,22.80,57.50,'2025-06-24 06:46:26','2025-06-24 06:46:26',NULL),(9,3,9,21.60,59.30,'2025-06-25 06:46:26','2025-06-25 06:46:26',NULL),(10,1,10,23.20,55.10,'2025-06-26 06:46:26','2025-06-26 06:46:26',NULL),(11,2,11,22.40,56.80,'2025-06-27 06:46:26','2025-06-27 06:46:26',NULL),(12,3,12,24.00,53.60,'2025-06-28 06:46:26','2025-06-28 06:46:26',NULL),(13,1,13,21.95,60.50,'2025-06-29 06:46:26','2025-06-29 06:46:26',NULL),(14,2,14,22.65,57.90,'2025-06-30 06:46:26','2025-06-30 06:46:26',NULL),(15,3,15,23.80,54.70,'2025-07-01 06:46:26','2025-07-01 06:46:26',NULL),(16,1,16,22.10,58.30,'2025-07-02 06:46:26','2025-07-02 06:46:26',NULL),(17,2,17,21.85,59.80,'2025-07-03 06:46:26','2025-07-03 06:46:26',NULL),(18,3,18,24.10,52.90,'2025-07-04 06:46:26','2025-07-04 06:46:26',NULL),(19,1,19,23.00,55.60,'2025-07-05 06:46:26','2025-07-05 06:46:26',NULL),(20,2,20,22.30,57.20,'2025-07-06 06:46:26','2025-07-06 06:46:26',NULL),(21,3,21,21.70,60.10,'2025-07-07 06:46:26','2025-07-07 06:46:26',NULL),(22,1,22,23.40,54.20,'2025-07-08 06:46:26','2025-07-08 06:46:26',NULL),(23,2,23,22.90,56.40,'2025-07-09 06:46:26','2025-07-09 06:46:26',NULL);
/*!40000 ALTER TABLE `environmental_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluation_records`
--

DROP TABLE IF EXISTS `evaluation_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluation_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `training_id` bigint unsigned NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluation_records_team_id_foreign` (`team_id`),
  KEY `evaluation_records_user_id_foreign` (`user_id`),
  KEY `evaluation_records_training_id_foreign` (`training_id`),
  CONSTRAINT `evaluation_records_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluation_records_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluation_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluation_records`
--

LOCK TABLES `evaluation_records` WRITE;
/*!40000 ALTER TABLE `evaluation_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluation_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('event','task','milestone') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'event',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `has_time` tinyint(1) NOT NULL DEFAULT '0',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_team_id_foreign` (`team_id`),
  KEY `events_user_id_foreign` (`user_id`),
  KEY `events_schedule_id_foreign` (`schedule_id`),
  KEY `events_role_id_index` (`role_id`),
  CONSTRAINT `events_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Events table with role_id added for role responsbility tracking';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exports`
--

DROP TABLE IF EXISTS `exports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exporter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `total_rows` int unsigned NOT NULL,
  `successful_rows` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exports_user_id_foreign` (`user_id`),
  CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exports`
--

LOCK TABLES `exports` WRITE;
/*!40000 ALTER TABLE `exports` DISABLE KEYS */;
/*!40000 ALTER TABLE `exports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_import_rows`
--

DROP TABLE IF EXISTS `failed_import_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_import_rows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL,
  `import_id` bigint unsigned NOT NULL,
  `validation_error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`),
  CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_import_rows`
--

LOCK TABLES `failed_import_rows` WRITE;
/*!40000 ALTER TABLE `failed_import_rows` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_import_rows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imports`
--

DROP TABLE IF EXISTS `imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int unsigned NOT NULL DEFAULT '0',
  `total_rows` int unsigned NOT NULL,
  `successful_rows` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`),
  CONSTRAINT `imports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imports`
--

LOCK TABLES `imports` WRITE;
/*!40000 ALTER TABLE `imports` DISABLE KEYS */;
/*!40000 ALTER TABLE `imports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `improvement_plans`
--

DROP TABLE IF EXISTS `improvement_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `improvement_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objective` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ends_at` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `improvement_plans_team_id_foreign` (`team_id`),
  CONSTRAINT `improvement_plans_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `improvement_plans`
--

LOCK TABLES `improvement_plans` WRITE;
/*!40000 ALTER TABLE `improvement_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `improvement_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventories_product_id_batch_id_unique` (`product_id`,`batch_id`),
  KEY `inventories_team_id_foreign` (`team_id`),
  KEY `inventories_batch_id_foreign` (`batch_id`),
  CONSTRAINT `inventories_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`),
  CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `inventories_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventories`
--

LOCK TABLES `inventories` WRITE;
/*!40000 ALTER TABLE `inventories` DISABLE KEYS */;
INSERT INTO `inventories` VALUES (1,1,38,8,115,1580.54,'2025-07-12 23:18:26','2025-07-17 03:28:48','doloribus Pomada'),(2,1,45,6,370,2648.71,'2025-07-13 21:47:26','2025-07-17 22:14:54','eos Suspensi├│n Oral'),(3,1,50,19,40,1843.88,'2025-07-04 17:59:26','2025-07-17 22:55:00','incidunt Ung├╝ento'),(4,1,35,7,345,4431.14,'2025-06-27 14:09:26','2025-07-16 06:46:26','sed Tableta'),(5,1,29,11,221,865.94,'2025-06-18 15:55:26','2025-07-16 06:46:26','incidunt Jarabe'),(6,1,45,2,58,1087.15,'2025-07-05 17:53:26','2025-07-16 06:46:26','eos Suspensi├│n Oral'),(7,1,2,6,424,877.91,'2025-07-12 19:13:26','2025-07-16 06:46:26','voluptate Supositorio'),(8,1,17,13,306,2308.10,'2025-07-07 18:34:26','2025-07-16 06:46:26','modi Jarabe'),(9,1,43,19,151,426.74,'2025-06-23 19:39:26','2025-07-16 06:46:26','omnis Jarabe'),(10,1,41,14,185,2152.96,'2025-07-06 17:38:26','2025-07-16 06:46:26','voluptate Supositorio');
/*!40000 ALTER TABLE `inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_item`
--

DROP TABLE IF EXISTS `invoice_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_item` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `sale_item_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_item_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_item_sale_item_id_foreign` (`sale_item_id`),
  KEY `invoice_item_batch_id_foreign` (`batch_id`),
  CONSTRAINT `invoice_item_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_item_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_item_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_item`
--

LOCK TABLES `invoice_item` WRITE;
/*!40000 ALTER TABLE `invoice_item` DISABLE KEYS */;
INSERT INTO `invoice_item` VALUES (1,2,1,8,NULL,1,0.00,0.00,'2025-07-17 03:28:49','2025-07-17 03:28:49'),(2,3,2,6,NULL,9,0.00,0.00,'2025-07-17 22:14:54','2025-07-17 22:14:54'),(3,4,3,19,NULL,4,0.00,0.00,'2025-07-17 22:55:00','2025-07-17 22:55:00');
/*!40000 ALTER TABLE `invoice_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `sale_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `is_our` tinyint(1) NOT NULL DEFAULT '1',
  `issued_date` date NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_code_unique` (`code`),
  KEY `invoices_team_id_foreign` (`team_id`),
  KEY `invoices_sale_id_foreign` (`sale_id`),
  KEY `invoices_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  CONSTRAINT `invoices_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `invoices_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,1,NULL,17,'FE00256jd',2547896.00,0,'2025-07-08','[]',NULL,'2025-07-16 20:52:24','2025-07-16 20:52:24'),(2,1,1,NULL,'INV-20250716222848-1',0.00,1,'2025-07-16',NULL,NULL,'2025-07-17 03:28:48','2025-07-17 03:28:48'),(3,1,2,NULL,'INV-20250717171454-2',0.00,1,'2025-07-17',NULL,NULL,'2025-07-17 22:14:54','2025-07-17 22:14:54'),(4,1,3,NULL,'INV-20250717175500-3',0.00,1,'2025-07-17',NULL,NULL,'2025-07-17 22:55:00','2025-07-17 22:55:00');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `management_indicator_team`
--

DROP TABLE IF EXISTS `management_indicator_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `management_indicator_team` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `management_indicator_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `periodicity` enum('Diario','Mensual','Bimestral','Trimestral','Semestral','Anual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicator_goal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `management_indicator_team_role_id_foreign` (`role_id`),
  CONSTRAINT `management_indicator_team_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `management_indicator_team`
--

LOCK TABLES `management_indicator_team` WRITE;
/*!40000 ALTER TABLE `management_indicator_team` DISABLE KEYS */;
INSERT INTO `management_indicator_team` VALUES (1,1,1,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(2,1,2,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(3,1,3,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(4,1,4,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(5,1,5,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(6,1,6,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(7,1,7,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(8,1,8,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(9,1,9,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(10,1,10,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(11,1,11,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(12,1,12,1,'Mensual',90,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(13,1,13,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(14,1,14,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(15,1,15,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(16,1,16,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19'),(17,1,17,1,'Mensual',NULL,'2025-07-16 21:51:19','2025-07-16 21:51:19');
/*!40000 ALTER TABLE `management_indicator_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `management_indicators`
--

DROP TABLE IF EXISTS `management_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `management_indicators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quality_goal_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objective` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Cardinal','Porcentual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numerator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `denominator` smallint DEFAULT NULL,
  `denominator_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `management_indicators_quality_goal_id_foreign` (`quality_goal_id`),
  CONSTRAINT `management_indicators_quality_goal_id_foreign` FOREIGN KEY (`quality_goal_id`) REFERENCES `quality_goals` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `management_indicators`
--

LOCK TABLES `management_indicators` WRITE;
/*!40000 ALTER TABLE `management_indicators` DISABLE KEYS */;
INSERT INTO `management_indicators` VALUES (1,1,'Disponibilidad','Medir el desempe├▒o de los procesos de selecci├│n y adquisici├│n. Contribuir a mantener el stock de productos necesario para cumplir con las expectativas de la comunidad.','# de faltantes en el mes (Medicamentos que no podemos dispensar debido a que no contamos con existencias, a pesar de estar seleccionados. No se incluyen por lo tanto en este indicador medicamentos no seleccionados, agotados en el mercado, descontinuados o cualquier otra raz├│n ajena a la responsabilidad de la droguer├¡a.)','Cardinal','Registros de faltantes.','# de faltantes en el mes',NULL,NULL,'2025-07-16 21:51:04','2025-07-16 21:51:04'),(2,2,'Calidad de producto','Medir el desempe├▒o del proceso de almacenamiento.','N├║mero de medicamentos con problemas de calidad debido a fallas en los procesos de la droguer├¡a. No se incluyen medicamentos que tienen problemas de calidad desde antes de ingresar a la droguer├¡a.','Cardinal','PQRS, encuestas de satisfacci├│n, inspecci├│n de productos (lo que incluye recepciones t├®cnicas y verificaciones aleatorias de registros sanitarios en las fuentes del INVIMA).','# de medicamentos con problemas de calidad',NULL,NULL,'2025-07-16 21:51:04','2025-07-16 21:51:04'),(3,4,'Cumplimiento Normativo','Medir el grado en que se cumple con los requerimientos normativos.','Proporci├│n de aspectos auditados que cumplen en relaci├│n con el n├║mero total de aspectos programados para auditor├¡a.','Porcentual','Informes de autoinspecciones, Actas de visita de la Secretar├¡a de Salud','# de aspectos evaluados que cumplen * 100',95,'# total de aspectos evaluados','2025-07-16 21:51:04','2025-07-16 21:51:04'),(4,5,'Satisfacci├│n de los usuarios','Medir el grado en que los usuarios est├ín satisfechos con la atenci├│n que se les brinda (amabilidad y rapidez), la calidad y la disponibilidad de productos, la presentaci├│n del establecimiento, etc. Detectar los aspectos del servicio, los productos o el esablecimiento que se deben mejorar.','Proporci├│n de los distintos niveles de calificaciones dadas a cada pregunta. Ejemplo: (# de respuestas Exelente a la pregunta 1 * 100 / # total de respuestas a la pregunta 1)','Porcentual','Encuestas de satisfacci├│n del usuario','(# de respuestas / nivel / pregunta) * 100',240,'# total de respuestas / pregunta','2025-07-16 21:51:04','2025-07-16 21:51:04'),(5,6,'Devoluciones','Medir el desempe├▒o de los procesos de adquisici├│n y almacenamiento. Contribuir al mantenimiento de un inventario ├│ptimo.','Ocasiones en que es necesario devolver productos al proveedor, o descartarlos, debido al acercamiento o cumplimiento de la fecha de vencimiento, o por deterioro atribuible a malas pr├ícticas de manejo o almacenamiento.','Cardinal','Registro de devoluciones y descartes.','# de productos devueltos o descartados',NULL,NULL,'2025-07-16 21:51:04','2025-07-16 21:51:04'),(6,2,'Monitoreo ambiental diario','Verificar que se est├ín monitorizando las variables ambientales.','Cumplimiento con la oblicaci├│n de verificar, como m├¡nimo, tantas veces al d├¡a como se establece en la meta, que la temperatura y la humedad se encuentren dentro de los rangos permitidos.','Cardinal','Registros de temeratura y humedad','# de registros realizados al d├¡a',NULL,NULL,'2025-07-16 21:51:04','2025-07-16 21:51:04'),(7,2,'Monitoreo ambiental mensual','Verificar que se est├ín monitorizando las variables ambientales.','Grado en el que se cumple con la obligaci├│n de verificar en dos ocasiones al d├¡a, ma├▒ana, y tarde-noche, que la temperatura y la humedad se encuentren dentro de los rangos permitidos.','Porcentual','Registros de temeratura y humedad','# de registros realizados (desde el primer d├¡a del mes hasta la fecha actual) * 100',NULL,'# de registros programados (meta de monitoreo ambiental diario * # de d├¡as transcurridos desde el primer d├¡a del mes hasta la fecha actual )','2025-07-16 21:51:04','2025-07-16 21:51:04'),(8,7,'Capacitaciones','Medir el desempe├▒o del proceso de Inducci├│n y capacitaci├│n.','Cumplimiento de cronograma de capacitaciones','Porcentual','Actas de capacitaci├│n, cronograma','# de capacitaciones realizadas * 100',NULL,'# de capacitaciones programadas','2025-07-16 21:51:04','2025-07-16 21:51:04'),(9,8,'Mejora continua','Medir el desempe├▒o de los procesos de evaluaci├│n y mejora continua.','Proporci├│n de planes de acci├│n ejecutados dentro del plazo establecido.','Porcentual','Registro de planes de acci├│n','# de planes de acci├│n al d├¡a o ejecutados dentro del plazo * 100',NULL,'# total de planes de acci├│n vigentes','2025-07-16 21:51:04','2025-07-16 21:51:04'),(10,9,'Promoci├│n del uso racional de medicamentos','Medir el desempe├▒o del proceso de dispensaci├│n.','Frecuencia con que se brinda informaci├│n sobre el uso adecuado de medicamentos','Cardinal','Registro de promoci├│n del uso racional','# de ocasiones en que se brinda al usuario informaci├│n sobre el uso racional de los medicamentos',NULL,NULL,'2025-07-16 21:51:04','2025-07-16 21:51:04'),(11,9,'Errores de dispensaci├│n','Medir el desempe├▒o del proceso de dispensaci├│n.','N├║mero de ocasiones en que se cometen errores en la dispensaci├│n de los medicamentos.','Cardinal','Registro de errores de dispensaci├│n','# de errores de dispensaci├│n',NULL,NULL,'2025-07-16 21:51:04','2025-07-16 21:51:04'),(12,2,'Recepci├│n t├®cnica','Medir el desempe├▒o del proceso de recepci├│n t├®cnica y administrativa.','Proporci├│n de env├¡os por parte de los proveedores a los que se les realiza la recepci├│n t├®cnica.','Porcentual','├ôrdenes de compra, Registro de recepciones t├®cnicas.','# de recepciones t├®cnicas * 100',NULL,'# de ├ôrdenes de compra','2025-07-16 21:51:04','2025-07-16 21:51:04'),(13,3,'Limpieza y sanitizaci├│n - Almacenamiento','Verificar que los productos se est├®n almacenando en ├íras con las condiciones de higiene necesarias para la conservaci├│n de la calidad.','Recuento mensual de actividades de limpieza y sanitizaci├│n en el ├írea de almacenamiento.','Porcentual','Registro de actividades de limpieza y sanitizaci├│n','# de actividades de limpieza y sanitizaci├│n efectuadas * 100',16,'# total de actividades programadas','2025-07-16 21:51:04','2025-07-16 21:51:04'),(14,3,'Limpieza y sanitizaci├│n - Inyectolog├¡a','Verificar que se est├®n desarrollando las actividades de limpieza y sanitizaci├│n con la frecuencia necesaria para brindar un servicio de inyectolog├¡a seguro.','Recuento mensual de actividades de limpieza y sanitizaci├│n en el ├írea de inyectolog├¡a.','Porcentual','Registro de actividades de limpieza y sanitizaci├│n','# de actividades de limpieza y sanitizaci├│n efectuadas * 100',16,'# total de actividades programadas','2025-07-16 21:51:04','2025-07-16 21:51:04'),(15,4,'Autoinspecciones','Medir el desempe├▒o de los procesos de evaluaci├│n y seguimiento.','Cumplimiento del cronograma de autoinspecciones: n├║mero de autoinspecciones realizadas en relaci├│n con las programadas.','Porcentual','Registro de autoinspecciones','# de autoinspecciones efectuadas * 100',NULL,'# total de autoinspecciones programadas','2025-07-16 21:51:04','2025-07-16 21:51:04'),(16,3,'Mantenimiento de equipos','Verificar que se dearrollan las actividades necesarias para mantener equipos en buen estado.','Cumplimiento del cronograma de mantenimiento de equipos: n├║mero de mantenimientos de instalaciones realizados en relaci├│n con los programados.','Porcentual','Certificados de mantenimiento de equipos','# de mantenimientos efectuados * 100',NULL,'# total de mantenimientos programados','2025-07-16 21:51:04','2025-07-16 21:51:04'),(17,3,'Mantenimiento de instalaciones y enseres','Verificar que se dearrollan las actividades necesarias para mantener instalaciones en buen estado.','Cumplimiento del cronograma de mantenimiento de instalaciones y enseres, es decir, n├║mero de mantenimientos de instalaciones y enseres realizados en relaci├│n con los programados.','Porcentual','Certificados de mantenimiento de instalaciones y enseres','# de mantenimientos efectuados * 100',NULL,'# total de mantenimientos programados','2025-07-16 21:51:04','2025-07-16 21:51:04');
/*!40000 ALTER TABLE `management_indicators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manufacturers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `manufacturers_identification_unique` (`identification`),
  UNIQUE KEY `manufacturers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manufacturers`
--

LOCK TABLES `manufacturers` WRITE;
/*!40000 ALTER TABLE `manufacturers` DISABLE KEYS */;
INSERT INTO `manufacturers` VALUES (1,'Ratke Ltd','be4cbcd3-2969-393e-9579-92fb23331764','861 Goldner Greens Suite 207\nPamelaville, ME 79708-9007','bert.carroll@wintheiser.info','+1.406.738.7251',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(2,'Hammes, Daugherty and Satterfield','1019ac49-76be-393d-ad46-09c057884002','86278 Lehner Cape\nJaydenshire, OR 36888','hagenes.carlotta@herzog.net','+17708507633',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(3,'Bartell, Bailey and Thiel','12f19ce2-04de-3a13-bdd6-54fe6071759e','262 Maxwell Gardens\nSouth Jedidiah, ME 54742','cicero48@waelchi.org','+1-754-294-4204',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(4,'Collier-Tillman','fc0dd79c-aeff-3a04-b55d-02b3b5420e56','44316 Kuhic Alley\nNew Everardo, NH 24096-4718','nils.romaguera@heller.com','(213) 325-0479',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(5,'Bogisich PLC','ed89cd6b-6817-3157-a485-05f41e8423b4','92562 Gibson Prairie Suite 343\nUptonhaven, ID 55402','rodolfo79@gislason.org','1-347-369-5709',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(6,'Price, Runolfsson and Ondricka','bf311f18-b6b4-3ca2-9f23-79e93fc14c40','33908 Kuhlman Viaduct Suite 834\nRoselynberg, MO 81414','belle97@rolfson.com','601-726-0879',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(7,'Quigley-Streich','e83f3cc5-571a-3812-9335-0aee0d0cdf73','89737 Robert Crossing\nHoppeville, MA 86292','kcrona@weissnat.org','712.457.5085',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(8,'Botsford, Nolan and Heaney','34c80422-8bdb-326e-a60f-597d8c0d4c12','54325 Green Lock Apt. 203\nWest Davonte, WI 88867','lebsack.zaria@kohler.com','+1 (870) 374-1149',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(9,'Medhurst LLC','e38a9d85-8dbb-30bc-9d9c-dcbc47a04f8e','48876 Walsh Stravenue Suite 599\nHectorport, ME 13271-0653','bartholome94@ruecker.org','+1-385-875-1809',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(10,'Hyatt-Ward','4c06f850-763b-37b1-9c37-1e5b4e53aede','6217 Gerlach Park\nKozeybury, WY 02331-0841','brittany04@baumbach.com','228.419.4354',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL);
/*!40000 ALTER TABLE `manufacturers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_03_22_133650_create_teams_table',1),(5,'2025_03_22_162916_create_team_user_table',1),(6,'2025_03_22_205057_create_manufacturers_table',1),(7,'2025_03_22_211102_create_categories_table',1),(8,'2025_03_22_211119_create_product_categories_table',1),(9,'2025_03_22_211218_create_pharmaceutical_forms_table',1),(10,'2025_03_22_214850_create_sanitary_registries_table',1),(11,'2025_03_22_214910_create_products_table',1),(12,'2025_03_22_220351_create_batches_table',1),(13,'2025_03_22_225057_create_suppliers_table',1),(14,'2025_03_24_151355_add_soft_deltes_to_teams_table',1),(15,'2025_03_24_161850_create_inventories_table',1),(16,'2025_03_24_163809_create_customers_table',1),(17,'2025_03_24_163973_create_patients_table',1),(18,'2025_03_24_164087_create_pets_table',1),(19,'2025_03_24_170509_create_purchases_table',1),(20,'2025_03_24_170536_create_purchase_items_table',1),(21,'2025_03_24_170843_create_sales_table',1),(22,'2025_03_24_170850_create_sale_items_table',1),(23,'2025_03_24_170904_create_invoices_table',1),(24,'2025_03_24_181134_create_product_receptions_table',1),(25,'2025_03_24_181144_create_product_reception_items_table',1),(26,'2025_03_25_012706_add_soft_deletes_to_sanitary_registries_table',1),(27,'2025_03_25_013324_add_soft_deletes_to_pharmaceutical_forms_table',1),(28,'2025_03_25_013914_add_soft_deletes_to_manufacturers_table',1),(29,'2025_03_27_211748_create_recipebooks_table',1),(30,'2025_03_28_010658_create_recipebook_items_table',1),(31,'2025_03_28_011548_create_process_types_table',1),(32,'2025_03_28_011553_create_processes_table',1),(33,'2025_03_28_012448_create_checklists_table',1),(34,'2025_03_28_012520_create_checklist_items_table',1),(35,'2025_03_28_012603_create_checklist_item_answers_table',1),(36,'2025_03_28_014301_create_improvement_plans_table',1),(37,'2025_03_28_014310_create_tasks_table',1),(38,'2025_03_29_020244_create_patient_team_table',1),(39,'2025_03_29_030428_create_team_product_prices_table',1),(40,'2025_03_31_085345_create_document_categories_table',1),(41,'2025_03_31_094345_create_documents_table',1),(42,'2025_03_31_094918_create_records_table',1),(43,'2025_04_01_014012_create_training_categories_table',1),(44,'2025_04_01_014032_create_trainings_table',1),(45,'2025_04_01_014156_create_questions_table',1),(46,'2025_04_01_014211_create_question_options_table',1),(47,'2025_04_01_014241_create_evaluation_records_table',1),(48,'2025_04_01_020346_create_user_answers_table',1),(49,'2025_04_01_191945_create_environmental_records_table',1),(50,'2025_04_01_211950_add_soft_deletes_to_batches_table',1),(51,'2025_04_03_204718_create_permission_tables',1),(52,'2025_04_03_205928_add_team_id_to_permissions_table',1),(53,'2025_04_08_100815_update_unique_index_permissions_table',1),(54,'2025_04_09_181939_add_teams_fields',1),(55,'2025_04_11_010729_create_stocks_table',1),(56,'2025_04_12_093953_create_central_product_prices_table',1),(57,'2025_05_21_190850_create_dispatches_table',1),(58,'2025_05_21_190902_create_dispatch_items_table',1),(59,'2025_06_06_133041_create_peripheral_product_price_table',1),(60,'2025_06_07_003638_create_invoice_item_table',1),(61,'2025_06_07_040316_add_status_to_sales_table',1),(62,'2025_06_20_180547_create_settings_table',1),(63,'2025_06_20_191321_create_tenant_settings_table',1),(64,'2025_06_25_154310_create_schedules_table',1),(65,'2025_06_25_154652_create_events_table',1),(66,'2025_06_25_180627_add_role_id_to_events_table',1),(67,'2025_06_26_214154_create_notifications_table',1),(68,'2025_06_26_214226_create_imports_table',1),(69,'2025_06_26_214227_create_exports_table',1),(70,'2025_06_26_214228_create_failed_import_rows_table',1),(71,'2025_06_27_171215_add_team_id_to_notifications_table',1),(72,'2025_06_30_212244_create_anesthesia_sheets_table',1),(73,'2025_06_30_212326_create_anesthesia_sheet_items_table',1),(74,'2025_06_30_222356_add_softdeletes_to_environmental_records_table',1),(75,'2025_07_01_160319_add_softdeletes_to_anesthesia_sheets_table',1),(76,'2025_07_01_162510_add_pet_id_to_anesthesia_sheets_table',1),(77,'2025_07_01_170542_add_is_surgeon_to_users_table',1),(78,'2025_07_02_192254_add_mce_and_hr_to_products_table',1),(79,'2025_07_02_200028_change_phase_to_enum_in_anesthesia_sheet_items_table',1),(80,'2025_07_02_203818_change_dose_measure_unit_to_enum_in_anesthesia_sheet_items_table',1),(81,'2025_07_02_205855_add_drug_concentration_to_products_table',1),(82,'2025_07_03_131708_add_product_name_to_inventories_table',1),(83,'2025_07_03_154134_add_recomended_dose_to_products_table',1),(84,'2025_07_03_200909_change_dose_per_kg_to_decimal_in_anesthesia_sheet_items_table',1),(85,'2025_07_03_200935_change_dose_measure_to_decimal_in_anesthesia_sheet_items_table',1),(86,'2025_07_03_203506_add_status_to_anesthesia_sheets_table',1),(87,'2025_07_03_211953_add_consumed_to_anesthesia_sheets_table',1),(88,'2025_07_03_214801_add_recipe_number_to_anesthesia_sheets_table',1),(89,'2025_07_07_212125_change_price_to_decimal_in_purchase_items_table',1),(90,'2025_07_07_212139_change_total_to_decimal_in_purchase_items_table',1),(91,'2025_07_08_164735_change_invoice_id_to_nullable_in_product_receptions_table',1),(92,'2025_07_08_170632_change_batch_id_to_nullable_in_product_reception_items_table',1),(93,'2025_07_08_213702_add_code_to_purchases_table',1),(94,'2025_07_12_113043_create_minutes_ivc_sections_table',1),(95,'2025_07_12_125145_create_minutes_ivc_section_entries_table',1),(96,'2025_07_14_204836_create_quality_goals_table',1),(100,'2025_07_16_163345_drop_role_id_from_management_indicators_table',2),(101,'2025_07_14_205102_create_management_indicators_table',3),(102,'2025_07_15_182115_update_status_enum_on_product_receptions_table',3),(103,'2025_07_15_223630_create_management_indicator_team_table',3),(104,'2025_07_18_000123_add_index_to_products_name',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `minutes_ivc_section_entries`
--

DROP TABLE IF EXISTS `minutes_ivc_section_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `minutes_ivc_section_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `minutes_ivc_section_id` bigint unsigned NOT NULL,
  `apply` tinyint(1) NOT NULL,
  `entry_id` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criticality` enum('critical','major','minor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `entry_type` enum('informativo','evidencia') COLLATE utf8mb4_unicode_ci NOT NULL,
  `links` json DEFAULT NULL,
  `compliance` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `minutes_ivc_section_entries_entry_id_unique` (`entry_id`),
  KEY `minutes_ivc_section_entries_minutes_ivc_section_id_foreign` (`minutes_ivc_section_id`),
  CONSTRAINT `minutes_ivc_section_entries_minutes_ivc_section_id_foreign` FOREIGN KEY (`minutes_ivc_section_id`) REFERENCES `minutes_ivc_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minutes_ivc_section_entries`
--

LOCK TABLES `minutes_ivc_section_entries` WRITE;
/*!40000 ALTER TABLE `minutes_ivc_section_entries` DISABLE KEYS */;
INSERT INTO `minutes_ivc_section_entries` VALUES (1,5,1,'9.1','major','Se evidencia desarrollo e implementaci├│n de la Funci├│n Administrativa (Planificar, organizar, dirigir coordinar y controlar los servicios relacionados con los medicamentos y dispositivos m├®dicos ofrecidos a los pacientes y a la comunidad en general, con excepci├│n de la prescripci├│n y administraci├│n de los medicamentos).','Desarrollamos nuestros procesos de tal forma que los indicadores reflejan el cumplimiento con las expectativas de la comunidad.','informativo',NULL,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,5,1,'9.2','major','Se evidencia desarrollo e implementaci├│n de la Funci├│n Promoci├│n (Impulsar estilos de vida saludables y el uso adecuado de medicamentos y dispositivos m├®dicos.','Como puede verse, tenemos material did├íctico al alcance de nuestros usuarios','informativo',NULL,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(3,5,1,'9.3','major','Se evidencia desarrollo e implementaci├│n de la Funci├│n de Prevenci├│n (Prevenci├│n de factores de riesgo derivados del uso inadecuado de medicamentos y dispositivos m├®dicos, as├¡ como problemas relacionados con su uso. Se refiere a la NO venta de medicamentos que tienen la condicion de \"Venta con f├│rmula m├®dica\" sin la presentaci├│n de la misma, prestaci├│n del servicio de inyectolog├¡a estrictamente con la presentaci├│n de la f├│rmula m├®dica, la no venta de medicamentos alterados, fraudulentos ni reportados en alertas sanitarias del INVIMA, el no recomentar ni inducir al usuario al consumo de medicamentos).','','informativo',NULL,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(4,5,1,'9.4','major','Se cuenta con procedimiento para el reporte de eventos adversos.','','evidencia','[{\"key\": \"Procedimiento de farmacovigilancia\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(5,5,1,'9.5','major','De llegarse a presentar, ┬┐se informa a la comunidad competente los reportes hechos por la comunidad, de los eventos adversos relacionados con el uso de medicamentos?.','Se cuenta con procedimiento y enlace directo a e-reporting','evidencia','[{\"key\": \"Procedimiento de farmacovigilancia\", \"value\": \"por.definir\"}, {\"key\": \"e-reporting\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(6,5,1,'9.6','major','Se cuenta con organigrama y manual de funciones del personal que labora en el establecimiento.','','evidencia','[{\"key\": \"organigrama\", \"value\": \"por.definir\"}, {\"key\": \"manual de funciones\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(7,5,1,'9.7','major','Se tiene un sistema documental que impida el uso accidental de documentos obsoletos o no aprobados. Los documentos est├ín dise├▒ados, revisados, modificados, autorizados, fechados y distribuidas por las personas autorizadas y se mantienen actualizados.','','evidencia','[{\"key\": \"manual de calidad\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(8,5,1,'9.8','major','El establecimiento cuenta con una pol├¡tica de calidad documentada. Cuenta con objetivos de calidad que cumplan lo establecido en su politica.','','evidencia','[{\"key\": \"politica de calidad\", \"value\": \"por.definir\"}, {\"key\": \"objetivos de calidad\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(9,5,1,'9.9','major','El establecimiento ha desarrollado y cuenta con una Misi├│n y una Visi├│n.','','evidencia','[{\"key\": \"mision\", \"value\": \"por.definir\"}, {\"key\": \"vision\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(10,5,1,'9.10','major','Los procesos propios del establecimiento farmac├®utico se encuentran debidamente caracterizados.','','evidencia','[{\"key\": \"Selecci├│n\", \"value\": \"por.definir\"}, {\"key\": \"Adquisici├│n\", \"value\": \"por.definir\"}, {\"key\": \"Recepci├│n\", \"value\": \"por.definir\"}, {\"key\": \"Almacenamiento\", \"value\": \"por.definir\"}, {\"key\": \"Dispensaci├│n\", \"value\": \"por.definir\"}, {\"key\": \"Devoluci├│n\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(11,5,1,'9.11','major','Se muestran los procesos estrat├®gicos y criticos (propios del establecimiento farmac├®utico), determinantes de la calidad, su secuencia e interacci├│n (en un mapa de procesos), con base en criterios t├®cnicos previamente definidos.','','evidencia','[{\"key\": \"Mapa de Procesos\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(12,5,1,'9.12','major','Las pol├¡ticas y programas de mejoramiento continuo promueven la capacitaci├│n del recuro humano? Se cuenta con mecanismo de programaci├│n y procedimiento para la inducci├│n y la capacitaci├│n del personal?','','evidencia','[{\"key\": \"Politica de calidad\", \"value\": \"por.definir\"}, {\"key\": \"Cronogramas\", \"value\": \"por.definir\"}, {\"key\": \"Calendario\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(13,5,1,'9.13','major','Se cuenta con registro de capacitaci├│n del personal.','','evidencia','[{\"key\": \"Capacitaci├│n firmada\", \"value\": \"por.definir\"}, {\"key\": \"Respuestas de ex├ímenes\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(14,5,1,'9.14','major','Existe un procedimiento documentado para la medici├│n de la satisfacci├│n del usuario? ┬┐Se cuenta con registros y resultados?','','evidencia','[{\"key\": \"Procedimiento de Evaluaci├│n de la Satisfacci├│n del Usuario\", \"value\": \"por.definir\"}, {\"key\": \"Indicador de evaluaci├│n de la satisfacci├│n\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(15,5,1,'9.15','major','┬┐Existe un procedimiento documentado y registros para el control, recepci├│n, clasificaci├│n, evaluaci├│n y cierre de las quejas presentadas por los usuarios.?','','evidencia','[{\"key\": \"Procedimiento de PQRS\", \"value\": \"por.definir\"}, {\"key\": \"Registro de PQRS\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(16,5,1,'9.16','major','┬┐Se realiza el seguimiento, an├ílisis y medici├│n de los procesos propios del establecimiento farmac├®utico (indicadores de gesti├│n).','','evidencia','[{\"key\": \"Tablero de indicadores de gesti├│n\", \"value\": \"filament.admin.pages.management-indicators\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(17,5,1,'9.17','major','┬┐Cuenta con procedimiento y plan de auditoria / autoinspecci├│n interna identificando la frecuencia de estas.?','','evidencia','[{\"key\": \"Procedimiento de autoinspecciones\", \"value\": \"por.definir\"}, {\"key\": \"Programa de autoinspecciones\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(18,5,1,'9.18','major','Se evidencia procedimiento escrito para el desarrollo de planes de mejora, correcciones, acciones correctives, y los resultados de las mismas.?','','evidencia','[{\"key\": \"Procedimiento para el desarrollo de planes de mejora\", \"value\": \"por.definir\"}, {\"key\": \"Lista de planes de mejora\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(19,5,1,'9.19','major','Se eval├║an y se mantienen bajo control los riesgos de mayor severidad de da├▒o y probabilidad de ocurrencia (Matriz de Riesgos).','','evidencia','[{\"key\": \"Procedimiento de evaluaci├│n y gesti├│n de riesgos\", \"value\": \"por.definir\"}, {\"key\": \"Matriz de riesgos\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(20,5,1,'9.20','major','Se presentan peri├│dicamente los resultados de indicadores de Gesti├│n de Calidad del Servicio/Establecimiento Farmac├®utico?','','evidencia','[{\"key\": \"Tablero de indicadores de gesti├│n\", \"value\": \"por.definir\"}]',1,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `minutes_ivc_section_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `minutes_ivc_sections`
--

DROP TABLE IF EXISTS `minutes_ivc_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `minutes_ivc_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` smallint NOT NULL,
  `status` double NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minutes_ivc_sections`
--

LOCK TABLES `minutes_ivc_sections` WRITE;
/*!40000 ALTER TABLE `minutes_ivc_sections` DISABLE KEYS */;
INSERT INTO `minutes_ivc_sections` VALUES (1,1,'C├®dula del establecimiento','','establecimiento',1,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,1,'Recurso Humano','','recurso-humano',2,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(3,1,'Infraestructura F├¡sica','','infraestructura-fisica',3,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(4,1,'Saneamiento de edificaciones','','saneamiento-edificiones',4,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(5,1,'Sistema de gesti├│n de calidad','','filament.admin.pages.qms-nine-section',9,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(6,1,' Proceso de Selecci├│n','','seleccion',10,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(7,1,' Proceso de Adquisici├│n','','adquisicion',11,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(8,1,' Proceso de Recepci├│n','','recepcion',12,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(9,1,' Proceso de Almacenamiento','','almacenamiento',13,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(10,1,' Proceso de Dispensaci├│n','','dispensacion',14,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(11,1,' Proceso de Devoluciones','','devoluciones',15,1,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(12,1,' Proceso de Manejo de Medicamentos Cadena de Fr├¡o','','cadena-frio',16,1,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `minutes_ivc_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`team_id`,`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_permissions_permission_id_foreign` (`permission_id`),
  KEY `model_has_permissions_team_foreign_key_index` (`team_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`team_id`,`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_roles_role_id_foreign` (`role_id`),
  KEY `model_has_roles_team_foreign_key_index` (`team_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1,1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  KEY `notifications_team_id_notifiable_type_notifiable_id_index` (`team_id`,`notifiable_type`,`notifiable_id`),
  CONSTRAINT `notifications_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_team`
--

DROP TABLE IF EXISTS `patient_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_team` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_team_patient_id_foreign` (`patient_id`),
  KEY `patient_team_team_id_foreign` (`team_id`),
  CONSTRAINT `patient_team_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_team`
--

LOCK TABLES `patient_team` WRITE;
/*!40000 ALTER TABLE `patient_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `species` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patients_team_id_foreign` (`team_id`),
  KEY `patients_customer_id_foreign` (`customer_id`),
  CONSTRAINT `patients_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patients_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peripheral_product_price`
--

DROP TABLE IF EXISTS `peripheral_product_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peripheral_product_price` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `min_stock` int unsigned NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peripheral_product_price_team_id_foreign` (`team_id`),
  KEY `peripheral_product_price_product_id_foreign` (`product_id`),
  CONSTRAINT `peripheral_product_price_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `peripheral_product_price_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peripheral_product_price`
--

LOCK TABLES `peripheral_product_price` WRITE;
/*!40000 ALTER TABLE `peripheral_product_price` DISABLE KEYS */;
INSERT INTO `peripheral_product_price` VALUES (1,1,15,47,67.90,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,1,42,7,98.40,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(3,1,35,28,95.65,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(4,1,2,8,84.35,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(5,1,43,43,66.91,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(6,1,20,16,62.18,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(7,1,12,24,32.14,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(8,1,34,26,77.80,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(9,1,14,48,52.08,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(10,1,6,16,17.13,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `peripheral_product_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_team_id_unique` (`name`,`guard_name`,`team_id`),
  KEY `permissions_team_id_foreign` (`team_id`),
  CONSTRAINT `permissions_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view-invoice','web','2025-07-17 22:52:02','2025-07-17 22:52:02',1),(2,'create-invoice','web','2025-07-17 22:52:10','2025-07-17 22:52:10',1),(3,'edit-invoice','web','2025-07-17 22:52:24','2025-07-17 22:52:24',1),(4,'delete-invoice','web','2025-07-17 22:52:32','2025-07-17 22:52:32',1);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pets`
--

DROP TABLE IF EXISTS `pets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `species` enum('dog','cat','bird','reptile','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dog',
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `weight` smallint DEFAULT NULL,
  `history` json DEFAULT NULL,
  `is_alive` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pets_customer_id_foreign` (`customer_id`),
  CONSTRAINT `pets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pets`
--

LOCK TABLES `pets` WRITE;
/*!40000 ALTER TABLE `pets` DISABLE KEYS */;
INSERT INTO `pets` VALUES (1,2,'Max','dog','male','2020-05-10',20,'{\"vaccinated\": true}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,2,'Bella','cat','female','2019-08-15',5,'{\"allergies\": \"none\"}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(3,3,'Charlie','dog','male','2021-01-20',15,'{\"notes\": \"friendly\"}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(4,4,'Luna','cat','female','2018-11-30',4,'{\"spayed\": true}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(5,5,'Rocky','dog','male','2017-07-07',25,'{\"surgery\": \"knee\"}',0,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(6,6,'Kiwi','bird','female','2022-03-12',1,'{\"color\": \"green\"}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(7,7,'Spike','reptile','male','2016-09-25',2,'{\"type\": \"iguana\"}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(8,8,'Milo','dog','male','2020-12-01',18,'{\"rescued\": true}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(9,9,'Coco','bird','female','2021-06-18',1,'{\"breed\": \"parrot\"}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(10,3,'Simba','cat','male','2019-02-14',6,'{\"favorite_food\": \"fish\"}',1,NULL,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `pets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pharmaceutical_forms`
--

DROP TABLE IF EXISTS `pharmaceutical_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pharmaceutical_forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pharmaceutical_forms_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pharmaceutical_forms`
--

LOCK TABLES `pharmaceutical_forms` WRITE;
/*!40000 ALTER TABLE `pharmaceutical_forms` DISABLE KEYS */;
INSERT INTO `pharmaceutical_forms` VALUES (1,'Tableta','Forma farmac├®utica s├│lida destinada a la administraci├│n oral.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(2,'C├ípsula','Presentaci├│n gelatinosa que contiene el medicamento en su interior.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(3,'Suspensi├│n Oral','Preparaci├│n l├¡quida en la que los f├írmacos se dispersan de manera homog├®nea, utilizada para administraci├│n oral.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(4,'Jarabe','Preparado l├¡quido, habitualmente aromatizado, para facilitar su administraci├│n oral, en especial en pediatr├¡a.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(5,'Inyectable','Soluci├│n o suspensi├│n est├®ril indicada para la administraci├│n parenteral mediante inyecci├│n.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(6,'Pomada','Preparaci├│n semis├│lida para uso t├│pico, empleada en tratamientos dermatol├│gicos.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(7,'Supositorio','Forma farmac├®utica s├│lida dise├▒ada para la administraci├│n rectal o vaginal.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(8,'Ung├╝ento','Preparado semis├│lido similar a la pomada, que se utiliza para aplicaci├│n t├│pica, con una consistencia generalmente m├ís pegajosa.','2025-07-16 06:46:24','2025-07-16 06:46:24',NULL);
/*!40000 ALTER TABLE `pharmaceutical_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_types`
--

DROP TABLE IF EXISTS `process_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `process_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_types`
--

LOCK TABLES `process_types` WRITE;
/*!40000 ALTER TABLE `process_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `processes`
--

DROP TABLE IF EXISTS `processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `processes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned DEFAULT NULL,
  `process_type_id` bigint unsigned NOT NULL,
  `records` json DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suppliers` json DEFAULT NULL,
  `inputs` json DEFAULT NULL,
  `procedures` json DEFAULT NULL,
  `outputs` json DEFAULT NULL,
  `clients` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `processes_team_id_foreign` (`team_id`),
  KEY `processes_process_type_id_foreign` (`process_type_id`),
  CONSTRAINT `processes_process_type_id_foreign` FOREIGN KEY (`process_type_id`) REFERENCES `process_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `processes_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processes`
--

LOCK TABLES `processes` WRITE;
/*!40000 ALTER TABLE `processes` DISABLE KEYS */;
/*!40000 ALTER TABLE `processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,'Medicamentos','Categor├¡a que agrupa todos los medicamentos.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(2,'Medicamentos - Antibi├│ticos','Medicamentos para combatir infecciones bacterianas.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(3,'Medicamentos - Analg├®sicos','Medicamentos para el alivio del dolor.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(4,'Medicamentos - Antiinflamatorios','Medicamentos para reducir la inflamaci├│n.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(5,'Medicamentos - Antipir├®ticos','Medicamentos para controlar la fiebre.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(6,'Medicamentos - Controlados','Medicamentos de control especial.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(7,'Reactivos de Diagn├│stico','Reactivos utilizados en pruebas y an├ílisis de laboratorio.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(8,'Reactivos de Diagn├│stico - Hematolog├¡a','Reactivos para an├ílisis de sangre.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(9,'Reactivos de Diagn├│stico - Bioqu├¡mica','Reactivos para pruebas bioqu├¡micas.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(10,'Reactivos de Diagn├│stico - Inmunolog├¡a','Reactivos para la detecci├│n de marcadores inmunol├│gicos.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(11,'Reactivos de Diagn├│stico - Microbiolog├¡a','Reactivos para el aislamiento y an├ílisis de microorganismos.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(12,'Dispositivos M├®dicos','Equipos e instrumentos utilizados en el ├ímbito m├®dico.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(13,'Dispositivos M├®dicos - Equipos de Diagn├│stico','Dispositivos para diagn├│stico de patolog├¡as.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(14,'Dispositivos M├®dicos - Equipos Terap├®uticos','Dispositivos usados en tratamientos terap├®uticos.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(15,'Dispositivos M├®dicos - Instrumentos Quir├║rgicos','Instrumentos utilizados en procedimientos quir├║rgicos.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(16,'Dispositivos M├®dicos - Dispositivos de Monitoreo','Equipos para el seguimiento de signos vitales.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24'),(17,'Dispositivos M├®dicos - Consumibles M├®dicos','Material descartable y de un solo uso en entornos cl├¡nicos.',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24');
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reception_items`
--

DROP TABLE IF EXISTS `product_reception_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reception_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_reception_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reception_items_product_reception_id_foreign` (`product_reception_id`),
  KEY `product_reception_items_product_id_foreign` (`product_id`),
  KEY `product_reception_items_batch_id_foreign` (`batch_id`),
  CONSTRAINT `product_reception_items_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_reception_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reception_items_product_reception_id_foreign` FOREIGN KEY (`product_reception_id`) REFERENCES `product_receptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reception_items`
--

LOCK TABLES `product_reception_items` WRITE;
/*!40000 ALTER TABLE `product_reception_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_reception_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_receptions`
--

DROP TABLE IF EXISTS `product_receptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_receptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `purchase_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `status` enum('in_progress','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `reception_date` timestamp NULL DEFAULT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_receptions_team_id_foreign` (`team_id`),
  KEY `product_receptions_user_id_foreign` (`user_id`),
  KEY `product_receptions_purchase_id_foreign` (`purchase_id`),
  KEY `product_receptions_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `product_receptions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_receptions_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  CONSTRAINT `product_receptions_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`),
  CONSTRAINT `product_receptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_receptions`
--

LOCK TABLES `product_receptions` WRITE;
/*!40000 ALTER TABLE `product_receptions` DISABLE KEYS */;
INSERT INTO `product_receptions` VALUES (1,1,5,6,1,'in_progress','2025-07-16 19:50:04','Nisi ratione explicabo aliquid nobis doloribus.','[]',NULL,'2025-07-16 19:50:04','2025-07-16 20:57:07'),(2,1,5,5,1,'done','2025-07-11 02:30:29','Dolores omnis qui est qui.',NULL,NULL,'2025-07-11 02:30:29','2025-07-11 02:30:29'),(3,1,5,3,1,'in_progress','2025-07-09 04:36:23','Iusto quibusdam saepe architecto modi.',NULL,NULL,'2025-07-09 04:36:23','2025-07-09 04:36:23'),(5,1,5,3,1,'in_progress','2025-07-08 08:01:49',NULL,NULL,NULL,'2025-07-08 08:01:49','2025-07-08 08:01:49'),(6,1,5,5,1,'in_progress','2025-07-29 17:13:01','Facere dolorem et eum explicabo a harum itaque.',NULL,NULL,'2025-07-29 17:13:01','2025-07-29 17:13:01'),(7,1,5,7,1,'done','2025-07-14 18:38:41',NULL,NULL,NULL,'2025-07-14 18:38:41','2025-07-14 18:38:41'),(8,1,5,5,1,'done','2025-06-09 17:31:50',NULL,NULL,NULL,'2025-06-09 17:31:50','2025-06-09 17:31:50'),(9,1,5,10,1,'done','2025-06-18 04:25:17',NULL,NULL,NULL,'2025-06-18 04:25:17','2025-06-18 04:25:17'),(10,1,5,7,1,'in_progress','2025-06-08 09:10:29',NULL,NULL,NULL,'2025-06-08 09:10:29','2025-06-08 09:10:29'),(11,1,5,8,1,'in_progress','2025-05-20 00:46:36',NULL,NULL,NULL,'2025-05-20 00:46:36','2025-05-20 00:46:36'),(12,1,5,10,1,'in_progress','2025-05-17 09:47:47',NULL,NULL,NULL,'2025-05-17 09:47:47','2025-05-17 09:47:47'),(13,1,5,6,1,'done','2025-05-04 20:05:00',NULL,NULL,NULL,'2025-05-04 20:05:00','2025-05-04 20:05:00'),(14,1,5,4,1,'done','2025-07-31 05:45:02',NULL,NULL,NULL,'2025-07-31 05:45:02','2025-07-31 05:45:02'),(15,1,5,8,1,'done','2025-07-18 16:58:02',NULL,NULL,NULL,'2025-07-18 16:58:02','2025-07-18 16:58:02'),(16,1,5,4,1,'in_progress','2025-07-15 17:46:23','Voluptatem eum veniam quia fugiat minus.',NULL,NULL,'2025-07-15 17:46:23','2025-07-15 17:46:23'),(17,1,5,5,1,'in_progress','2025-06-22 20:08:03','Repellendus incidunt quo non officia.',NULL,NULL,'2025-06-22 20:08:03','2025-06-22 20:08:03'),(18,1,5,4,1,'in_progress','2025-06-07 20:54:28','Consequatur esse quas aut.',NULL,NULL,'2025-06-07 20:54:28','2025-06-07 20:54:28'),(19,1,5,10,1,'in_progress','2025-06-07 01:55:42',NULL,NULL,NULL,'2025-06-07 01:55:42','2025-06-07 01:55:42'),(20,1,5,8,1,'in_progress','2025-05-18 07:08:44','Sunt qui beatae quasi et nemo aut autem sit.',NULL,NULL,'2025-05-18 07:08:44','2025-05-18 07:08:44'),(21,1,5,4,1,'in_progress','2025-05-13 08:25:51','Iure amet omnis voluptatem et sint.',NULL,NULL,'2025-05-13 08:25:51','2025-05-13 08:25:51'),(22,1,5,7,1,'done','2025-05-23 19:15:26','Sunt molestiae sit ut libero est autem quasi.',NULL,NULL,'2025-05-23 19:15:26','2025-05-23 19:15:26');
/*!40000 ALTER TABLE `product_receptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_category_id` bigint unsigned NOT NULL,
  `pharmaceutical_form_id` bigint unsigned DEFAULT NULL,
  `bar_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drug_concentration` double DEFAULT NULL COMMENT 'Concentraci├│n del principio activo por unidad de medida del producto',
  `recommended_dose` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Recommended dose in mg/kg for the product',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_high_risk` tinyint(1) NOT NULL DEFAULT '0',
  `is_mce` tinyint(1) NOT NULL DEFAULT '0',
  `fractionable` tinyint(1) NOT NULL DEFAULT '0',
  `conversion_factor` decimal(8,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` decimal(8,2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_bar_code_unique` (`bar_code`),
  KEY `products_product_category_id_foreign` (`product_category_id`),
  KEY `products_pharmaceutical_form_id_foreign` (`pharmaceutical_form_id`),
  KEY `products_name_index` (`name`),
  CONSTRAINT `products_pharmaceutical_form_id_foreign` FOREIGN KEY (`pharmaceutical_form_id`) REFERENCES `pharmaceutical_forms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,11,4,'MED-2719','pariatur Jarabe',NULL,NULL,0.00,'Tubo con 50g de crema',0,0,1,NULL,'products/default.jpg',3.57,1,NULL,'2023-08-16 20:14:12','2025-07-16 06:46:24'),(2,4,7,'MED-8112','voluptate Supositorio','quaerat',NULL,0.00,'Tubo con 50g de crema',0,0,0,472.32,'products/default.jpg',13.98,1,NULL,'2024-10-22 07:17:00','2025-07-16 06:46:24'),(3,5,5,'MED-9148','rerum Inyectable','excepturi',NULL,0.00,'Envase con 30 tabletas',0,0,1,979.16,'products/default.jpg',16.10,1,NULL,'2024-08-02 03:23:16','2025-07-16 06:46:24'),(4,13,6,'MED-7793','esse Pomada','aut',NULL,0.00,'Envase con 30 tabletas',0,0,1,150.07,'products/default.jpg',3.09,1,NULL,'2025-01-01 17:56:40','2025-07-16 06:46:24'),(5,11,8,'MED-7941','porro Ung├╝ento','minus',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,0,NULL,'products/default.jpg',17.75,1,NULL,'2023-09-25 23:47:48','2025-07-16 06:46:24'),(6,9,2,'MED-2884','dolores C├ípsula',NULL,NULL,0.00,'Frasco de 120ml',0,0,1,NULL,'products/default.jpg',1.48,0,NULL,'2024-04-21 04:20:41','2025-07-16 06:46:24'),(7,1,6,'MED-9781','et Pomada','est',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,1,NULL,'products/default.jpg',6.07,1,NULL,'2023-11-07 11:11:35','2025-07-16 06:46:24'),(8,11,7,'MED-2418','voluptatibus Supositorio','voluptatum',NULL,0.00,'Tubo con 50g de crema',0,0,0,NULL,'products/default.jpg',12.05,1,NULL,'2024-05-26 04:08:56','2025-07-16 06:46:24'),(9,4,4,'MED-4962','et Jarabe',NULL,NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,1,877.46,'products/default.jpg',10.97,1,NULL,'2025-07-01 12:46:31','2025-07-16 06:46:24'),(10,3,7,'MED-1030','incidunt Supositorio',NULL,NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',6.64,1,NULL,'2025-05-06 21:58:53','2025-07-16 06:46:24'),(11,16,7,'MED-4701','tenetur Supositorio','quo',NULL,0.00,'Tubo con 50g de crema',0,0,0,NULL,'products/default.jpg',16.28,0,NULL,'2024-12-02 10:36:02','2025-07-16 06:46:25'),(12,5,5,'MED-3483','animi Inyectable',NULL,NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',2.35,1,NULL,'2023-09-20 20:10:01','2025-07-16 06:46:25'),(13,1,4,'MED-4448','fugit Jarabe','doloribus',NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',4.74,1,NULL,'2024-06-22 15:52:53','2025-07-16 06:46:25'),(14,7,7,'MED-2292','tempore Supositorio','hic',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,0,496.40,'products/default.jpg',0.08,1,NULL,'2023-11-24 17:10:35','2025-07-16 06:46:25'),(15,12,5,'MED-6520','et Inyectable',NULL,NULL,0.00,'Frasco de 120ml',0,0,0,NULL,'products/default.jpg',1.23,1,NULL,'2023-12-15 18:25:07','2025-07-16 06:46:25'),(16,1,4,'MED-2964','sed Jarabe','voluptatem',NULL,0.00,'Tubo con 50g de crema',0,0,0,4.34,'products/default.jpg',14.88,1,NULL,'2023-10-26 02:40:44','2025-07-16 06:46:25'),(17,8,4,'MED-6690','modi Jarabe','cupiditate',NULL,0.00,'Envase con 30 tabletas',0,0,0,779.81,'products/default.jpg',19.05,1,NULL,'2024-06-07 04:16:59','2025-07-16 06:46:25'),(18,8,3,'MED-9559','qui Suspensi├│n Oral',NULL,NULL,0.00,'Frasco de 120ml',0,0,0,NULL,'products/default.jpg',6.94,1,NULL,'2023-09-09 14:15:08','2025-07-16 06:46:25'),(19,11,2,'MED-2261','ipsa C├ípsula','nobis',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,0,NULL,'products/default.jpg',20.47,1,NULL,'2025-04-03 04:42:25','2025-07-16 06:46:25'),(20,6,1,'MED-5938','adipisci Tableta','ipsum',NULL,0.00,'Tubo con 50g de crema',0,0,0,247.43,'products/default.jpg',8.45,1,NULL,'2024-05-26 02:56:16','2025-07-16 06:46:25'),(21,16,1,'MED-3277','placeat Tableta','sint',NULL,0.00,'Tubo con 50g de crema',0,0,0,NULL,'products/default.jpg',17.81,1,NULL,'2024-07-27 00:40:02','2025-07-16 06:46:25'),(22,12,2,'MED-3085','iure C├ípsula','excepturi',NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',16.45,1,NULL,'2025-02-05 16:01:51','2025-07-16 06:46:25'),(23,13,7,'MED-6110','dolores Supositorio','libero',NULL,0.00,'Frasco de 120ml',0,0,1,NULL,'products/default.jpg',18.82,1,NULL,'2024-12-14 08:22:53','2025-07-16 06:46:25'),(24,13,1,'MED-4963','quas Tableta','non',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,0,NULL,'products/default.jpg',9.96,1,NULL,'2023-12-22 02:18:23','2025-07-16 06:46:25'),(25,8,1,'MED-1487','fugiat Tableta','ex',NULL,0.00,'Tubo con 50g de crema',0,0,0,NULL,'products/default.jpg',6.09,1,NULL,'2023-09-06 03:07:43','2025-07-16 06:46:25'),(26,1,5,'MED-3577','soluta Inyectable','voluptate',NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',0.32,1,NULL,'2023-09-29 10:45:01','2025-07-16 06:46:25'),(27,16,6,'MED-6124','nobis Pomada','aliquam',NULL,0.00,'Envase con 30 tabletas',0,0,1,575.01,'products/default.jpg',8.24,1,NULL,'2023-08-24 03:58:14','2025-07-16 06:46:25'),(28,6,7,'MED-5882','provident Supositorio',NULL,NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,1,NULL,'products/default.jpg',2.53,1,NULL,'2024-09-02 01:04:25','2025-07-16 06:46:25'),(29,17,4,'MED-9492','incidunt Jarabe',NULL,NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',13.68,0,NULL,'2025-03-25 04:03:32','2025-07-16 06:46:25'),(30,15,3,'MED-9679','ea Suspensi├│n Oral','ut',NULL,0.00,'Envase con 30 tabletas',0,0,0,966.13,'products/default.jpg',0.75,0,NULL,'2025-04-07 09:25:07','2025-07-16 06:46:25'),(31,17,1,'MED-4555','at Tableta',NULL,NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,0,NULL,'products/default.jpg',8.78,1,NULL,'2025-07-12 09:49:17','2025-07-16 06:46:25'),(32,3,7,'MED-5971','similique Supositorio','neque',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,0,NULL,'products/default.jpg',12.34,1,NULL,'2025-01-11 17:04:04','2025-07-16 06:46:25'),(33,10,4,'MED-6750','perferendis Jarabe',NULL,NULL,0.00,'Frasco de 120ml',0,0,1,NULL,'products/default.jpg',8.02,1,NULL,'2024-11-23 20:06:25','2025-07-16 06:46:25'),(34,14,4,'MED-1882','et Jarabe',NULL,NULL,0.00,'Envase con 30 tabletas',0,0,1,288.06,'products/default.jpg',15.49,1,NULL,'2024-09-20 01:04:11','2025-07-16 06:46:25'),(35,7,1,'MED-6088','sed Tableta','cupiditate',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,0,NULL,'products/default.jpg',12.27,1,NULL,'2025-02-19 02:22:55','2025-07-16 06:46:25'),(36,11,3,'MED-1791','possimus Suspensi├│n Oral',NULL,NULL,0.00,'Frasco de 120ml',0,0,0,684.59,'products/default.jpg',7.93,1,NULL,'2023-09-20 13:22:20','2025-07-16 06:46:25'),(37,6,8,'MED-8681','sit Ung├╝ento','aut',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,0,NULL,'products/default.jpg',1.80,1,NULL,'2025-02-05 08:44:34','2025-07-16 06:46:25'),(38,8,6,'MED-1932','doloribus Pomada','assumenda',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,1,813.42,'products/default.jpg',12.38,1,NULL,'2025-05-30 20:48:01','2025-07-16 06:46:25'),(39,3,7,'MED-9013','minus Supositorio',NULL,NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,1,NULL,'products/default.jpg',1.95,1,NULL,'2023-12-11 02:43:13','2025-07-16 06:46:25'),(40,10,2,'MED-1310','porro C├ípsula',NULL,NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,0,308.53,'products/default.jpg',2.05,1,NULL,'2024-12-11 19:09:22','2025-07-16 06:46:25'),(41,4,7,'MED-6932','voluptate Supositorio','reiciendis',NULL,0.00,'Frasco de 120ml',0,0,0,NULL,'products/default.jpg',6.54,1,NULL,'2025-02-09 16:10:32','2025-07-16 06:46:25'),(42,9,1,'MED-1832','nulla Tableta','eligendi',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,1,NULL,'products/default.jpg',8.06,1,NULL,'2024-03-11 01:23:36','2025-07-16 06:46:25'),(43,7,4,'MED-4135','omnis Jarabe','ducimus',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,0,NULL,'products/default.jpg',11.59,1,NULL,'2025-04-23 12:41:23','2025-07-16 06:46:25'),(44,8,1,'MED-2797','in Tableta','molestiae',NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',4.70,1,NULL,'2024-06-17 11:29:28','2025-07-16 06:46:25'),(45,7,3,'MED-8996','eos Suspensi├│n Oral',NULL,NULL,0.00,'Envase con 30 tabletas',0,0,0,NULL,'products/default.jpg',18.34,1,NULL,'2024-06-27 02:05:28','2025-07-16 06:46:25'),(46,2,2,'MED-5767','maiores C├ípsula','iure',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,0,NULL,'products/default.jpg',12.60,1,NULL,'2024-02-08 08:58:12','2025-07-16 06:46:25'),(47,13,4,'MED-2707','enim Jarabe','aliquam',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,1,952.52,'products/default.jpg',5.77,1,NULL,'2025-04-28 08:46:59','2025-07-16 06:46:25'),(48,9,3,'MED-1593','ipsum Suspensi├│n Oral','impedit',NULL,0.00,'Frasco de 120ml',0,0,1,NULL,'products/default.jpg',0.96,0,NULL,'2024-09-24 02:47:19','2025-07-16 06:46:25'),(49,4,4,'MED-5030','rerum Jarabe','esse',NULL,0.00,'Bl├¡ster con 10 c├ípsulas',0,0,0,895.97,'products/default.jpg',3.82,1,NULL,'2025-05-14 02:55:36','2025-07-16 06:46:25'),(50,16,8,'MED-9988','incidunt Ung├╝ento','voluptatum',NULL,0.00,'Caja con 5 ampollas de 5ml',0,0,1,13.95,'products/default.jpg',0.23,1,NULL,'2024-10-09 20:43:11','2025-07-16 06:46:25');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `enlisted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unique code for the purchase',
  `status` enum('pending','confirmed','in progress','ready','dispatched','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total` bigint NOT NULL DEFAULT '0',
  `observations` text COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_team_id_foreign` (`team_id`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `purchases_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (3,1,10,'OC00019','confirmed',764538,'Voluptatem et laboriosam odit veritatis vel temporibus.',NULL,NULL,'2025-07-09 23:26:11','2025-07-16 19:49:02'),(4,1,10,'OC00020','confirmed',140695,NULL,NULL,NULL,'2025-07-29 00:41:58','2025-07-16 19:49:09'),(5,1,10,'OC00021','confirmed',497493,NULL,NULL,NULL,'2025-06-15 14:39:51','2025-07-16 19:49:11'),(6,1,10,'OC00023','delivered',416191,'Nisi ratione explicabo aliquid nobis doloribus.',NULL,NULL,'2025-06-29 16:04:57','2025-06-29 16:04:57'),(7,1,10,'OC00024','confirmed',943225,NULL,NULL,NULL,'2025-06-24 10:47:45','2025-07-16 19:49:15'),(8,1,10,'OC00025','confirmed',687832,NULL,NULL,NULL,'2025-05-18 14:44:04','2025-05-18 14:44:04'),(9,1,10,'OC00026','confirmed',679106,'Ducimus cumque quaerat dolores nisi tempore et.',NULL,NULL,'2025-05-31 17:50:23','2025-07-16 19:49:19'),(10,1,10,'OC00027','confirmed',386417,'Minus illo rerum pariatur debitis velit exercitationem.',NULL,NULL,'2025-05-18 03:51:50','2025-07-16 19:49:21');
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quality_goals`
--

DROP TABLE IF EXISTS `quality_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quality_goals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quality_goals`
--

LOCK TABLES `quality_goals` WRITE;
/*!40000 ALTER TABLE `quality_goals` DISABLE KEYS */;
INSERT INTO `quality_goals` VALUES (1,'Disponibilidad','Garantizar el abastecimiento oportuno y la disponibilidad de productos. ','[]','2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,'Calidad de los Productos','Garantizar que todos los productos sean aut├®nticos y est├®n certificados por el INVIMA. Mantener registros actualizados de proveedores confiables y verificar la calidad de los productos recibidos.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(3,'Seguridad y Eficacia de los Medicamentos','Cumplir con los est├índares de almacenamiento adecuado para preservar la efectividad de los medicamentos. Capacitar al personal en el manejo seguro de medicamentos y en la correcta dispensaci├│n seg├║n las indicaciones m├®dicas.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(4,'Cumplimiento Normativo y Legal','Garantizar el cumplimiento de todas las regulaciones locales, regionales y nacionales relacionadas con la venta de productos farmac├®uticos. Realizar auditor├¡as internas peri├│dicas para asegurar el cumplimiento de los est├índares de calidad y legalidad.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(5,'Satisfacci├│n de los usuarios','Proporcionar un servicio al cliente excepcional, brindando informaci├│n precisa y consejos sobre los productos disponibles. Mantener un ambiente limpio, ordenado y acogedor para garantizar una experiencia de compra positiva.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(6,'Gesti├│n Eficiente del Inventario','Implementar sistemas de gesti├│n de inventario efectivos para asegurar el abastecimiento oportuno y la disponibilidad de productos. Minimizar el desperdicio y las p├®rdidas mediante un control riguroso del inventario y la rotaci├│n adecuada de productos.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(7,'Formaci├│n y Desarrollo del Personal','Proporcionar formaci├│n continua al personal sobre los productos, las regulaciones y las mejores pr├ícticas de atenci├│n al cliente. Fomentar un ambiente de trabajo colaborativo y motivador que impulse el compromiso y la excelencia en el servicio.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(8,'Mejora Continua','Evaluar la satisfacci├│n de los usuarios para identificar ├íreas de mejora y oportunidades de crecimiento.Implementar medidas correctivas y preventivas para abordar cualquier problema identificado a trav├®s de cualquier medio (encuestas, auditor├¡as internas y externas, etc.) y optimizar los procesos internos.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27'),(9,'Responsabilidad Ambiental y Social','Adoptar pr├ícticas comerciales sostenibles, como el uso de empaques ecoamigables y la gesti├│n adecuada de residuos. Contribuir activamente a la comunidad local mediante iniciativas de responsabilidad social corporativa, como programas de educaci├│n sobre salud y bienestar.','[]','2025-07-16 06:46:27','2025-07-16 06:46:27');
/*!40000 ALTER TABLE `quality_goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_options`
--

DROP TABLE IF EXISTS `question_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint unsigned NOT NULL,
  `option_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_options_question_id_foreign` (`question_id`),
  CONSTRAINT `question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_options`
--

LOCK TABLES `question_options` WRITE;
/*!40000 ALTER TABLE `question_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `question_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `training_id` bigint unsigned NOT NULL,
  `question_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_team_id_foreign` (`team_id`),
  KEY `questions_training_id_foreign` (`training_id`),
  CONSTRAINT `questions_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `questions_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipebook_items`
--

DROP TABLE IF EXISTS `recipebook_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipebook_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `recipebook_id` bigint unsigned NOT NULL,
  `inventory_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipebook_items_recipebook_id_foreign` (`recipebook_id`),
  KEY `recipebook_items_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `recipebook_items_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recipebook_items_recipebook_id_foreign` FOREIGN KEY (`recipebook_id`) REFERENCES `recipebooks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipebook_items`
--

LOCK TABLES `recipebook_items` WRITE;
/*!40000 ALTER TABLE `recipebook_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipebook_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipebooks`
--

DROP TABLE IF EXISTS `recipebooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipebooks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consecutive` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` date NOT NULL,
  `team_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipebooks_consecutive_unique` (`consecutive`),
  KEY `recipebooks_team_id_foreign` (`team_id`),
  KEY `recipebooks_user_id_foreign` (`user_id`),
  KEY `recipebooks_customer_id_foreign` (`customer_id`),
  KEY `recipebooks_patient_id_foreign` (`patient_id`),
  CONSTRAINT `recipebooks_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recipebooks_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recipebooks_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL,
  CONSTRAINT `recipebooks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipebooks`
--

LOCK TABLES `recipebooks` WRITE;
/*!40000 ALTER TABLE `recipebooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipebooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `records`
--

DROP TABLE IF EXISTS `records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `records`
--

LOCK TABLES `records` WRITE;
/*!40000 ALTER TABLE `records` DISABLE KEYS */;
/*!40000 ALTER TABLE `records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_team_id_name_guard_name_unique` (`team_id`,`name`,`guard_name`),
  KEY `roles_team_foreign_key_index` (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,1,'Super-Admin','web','2025-07-16 06:46:25','2025-07-16 06:46:25');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `inventory_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `sale_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_inventory_id_foreign` (`inventory_id`),
  CONSTRAINT `sale_items_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,1,1,0.00,0.00,'2025-07-17 03:28:48','2025-07-17 03:28:48'),(2,2,2,9,0.00,0.00,'2025-07-17 22:14:54','2025-07-17 22:14:54'),(3,3,3,4,0.00,0.00,'2025-07-17 22:55:00','2025-07-17 22:55:00');
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('in-progress','completed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in-progress',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_team_id_foreign` (`team_id`),
  KEY `sales_customer_id_foreign` (`customer_id`),
  KEY `sales_user_id_foreign` (`user_id`),
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,1,11,5,0.00,'completed','S0001',NULL,NULL,'2025-07-17 03:28:48','2025-07-17 03:28:48'),(2,1,11,5,0.00,'completed','S0002',NULL,NULL,'2025-07-17 22:14:54','2025-07-17 22:14:54'),(3,1,11,5,0.00,'completed','S0003',NULL,NULL,'2025-07-17 22:55:00','2025-07-17 22:55:00');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanitary_registries`
--

DROP TABLE IF EXISTS `sanitary_registries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sanitary_registries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cum` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanitary_registries`
--

LOCK TABLES `sanitary_registries` WRITE;
/*!40000 ALTER TABLE `sanitary_registries` DISABLE KEYS */;
INSERT INTO `sanitary_registries` VALUES (1,'REG-001','CUM-101','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(2,'REG-002','CUM-102','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(3,'REG-003','CUM-103','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(4,'REG-004','CUM-104','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(5,'REG-005','CUM-105','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(6,'REG-006','CUM-106','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(7,'REG-007','CUM-107','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(8,'REG-008','CUM-108','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(9,'REG-009','CUM-109','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(10,'REG-010','CUM-110','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(11,'8A3RA','d9a659af-15d4-47a5-ad06-b39ca40d66d4','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(12,'BTFHK','96945a34-dd0d-458e-88df-0513d350578e','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(13,'FEUOV','1f7a789e-184d-4322-bd99-ca40aed24b67','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(14,'FUDN4','37c3468c-f6fe-4361-8c27-030c376ec4cd','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(15,'SVI8K','a6e0d683-1027-4507-9d22-ad8c8c264c83','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(16,'0GVDH','de3d18d4-9b1c-4ffb-8395-e56a18f195e8','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(17,'XJQWZ','6fa693b9-ba9a-4178-8eec-4e3ad441bf75','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(18,'KVQ5S','2753ee71-02c2-4360-91f1-2635329fb673','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(19,'82VYR','4cb14b06-de77-4cd5-8905-562d4931bf89','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL),(20,'RJBYI','1738dc54-5974-41f1-9c9c-567df48e5a83','2025-07-16 06:46:25','2025-07-16 06:46:25',NULL);
/*!40000 ALTER TABLE `sanitary_registries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `objective` text COLLATE utf8mb4_unicode_ci,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `is_rescheduled` tinyint(1) NOT NULL DEFAULT '0',
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `is_in_progress` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_team_id_foreign` (`team_id`),
  KEY `schedules_user_id_foreign` (`user_id`),
  CONSTRAINT `schedules_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('bjiOUxwh2i13eHCEYs9AQg6mn7o5ySgef4mgGUq5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 LikeWise/95.6.5716.53','YTozOntzOjY6Il90b2tlbiI7czo0MDoiajRkWjc4WVhEVFExTDFDa1c1QUlxS3hhTjV0cXdkRzlmVmNLVkZkZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9kLW9yaWdpbjIuMC4wLnRlc3QvYWRtaW4vbG9naW4iO319',1752790780),('VMAMjtitUXgeH9FzuUkhjNyaKCaDLUVdDxMFESvY',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 LikeWise/95.6.5716.53','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiV2tUeXRlT2pybktsWlhTMjQ0Qk5CdWI5anlheUd1VFAySEVHOFdscyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9kLW9yaWdpbjIuMC4wLnRlc3QvcG9zLzEvaW52b2ljZXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O3M6NzoidGVhbV9pZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkbFZCUFVFVkQ5ZXE4Z2IuZ0tZeXExT2x1SVlmMm1DRHRtdGpreWNxWVYyLmdXNXg2LzFLOUsiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1752780656),('wnT9uVCbIKIN4rRFnzvrozt9YG74T1M59puzc8gv',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 LikeWise/95.6.5716.53','YToyOntzOjY6Il90b2tlbiI7czo0MDoiSzBzQk9xWDh1YVZiWjBYQm1jUE5tRE1zVzl0amxHMDdBd2VKNmhnVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1752790763);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attributes` json DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_product_id_foreign` (`product_id`),
  KEY `stocks_batch_id_foreign` (`batch_id`),
  CONSTRAINT `stocks_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`),
  CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (1,1,14,15,53.45,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(2,2,22,5,42.62,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(3,3,15,5,29.24,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(4,4,11,12,47.46,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(5,5,12,4,32.98,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(6,6,2,8,93.27,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(7,7,14,8,90.56,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(8,8,7,14,31.26,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(9,9,17,12,55.15,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(10,10,4,5,99.61,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(11,11,24,10,43.41,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(12,12,17,12,46.88,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(13,13,21,9,44.53,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(14,14,20,8,28.18,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(15,15,8,13,24.36,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(16,16,14,11,40.67,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(17,17,15,19,88.77,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(18,18,17,20,89.38,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(19,19,21,15,99.30,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(20,20,2,18,41.01,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(21,21,19,12,99.62,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(22,22,3,16,34.47,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(23,23,24,12,96.29,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(24,24,12,19,92.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(25,25,12,10,68.10,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(26,26,14,10,46.65,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(27,27,3,4,92.20,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(28,28,9,7,4.58,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(29,29,4,5,59.29,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(30,30,22,19,81.07,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(31,31,15,9,54.62,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(32,32,23,15,11.80,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(33,33,24,15,88.74,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(34,34,9,3,90.27,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(35,35,6,19,72.07,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(36,36,11,15,77.65,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(37,37,4,13,76.56,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(38,38,20,1,77.60,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(39,39,11,11,37.21,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(40,40,12,4,90.89,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(41,41,10,1,34.08,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(42,42,18,8,88.44,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(43,43,24,15,93.85,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(44,44,13,17,37.93,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(45,45,1,17,72.44,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(46,46,7,1,39.85,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(47,47,23,2,67.06,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(48,48,24,5,28.92,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(49,49,10,3,94.98,'2025-07-16 06:46:26','2025-07-16 06:46:26'),(50,50,16,20,47.43,'2025-07-16 06:46:26','2025-07-16 06:46:26');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_identification_unique` (`identification`),
  UNIQUE KEY `suppliers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (10,'ADS PHARMA SAS','900040831','CALLE 102 A 70 79','adsfarma@adsfarma.com','6017516592','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(11,'DISTRIBUCIONES AXA SA','800052534','CRA 34  6  70','distribucionesaxa@distribucionesaxa.com','6017404040','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(12,'DISTRIBUIDORA FARMACEUTICA ROMA S.A.','890901475','CALLE 17 A  68 D 37','distribuidorafarmaceuticaroma@gmail.com','6017469810','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(13,'ETICOS SERRANO GOMEZ LTDA','892300678','AUTOPISTA MEDELLIN KIL 3.4 COSTADO NORTE C. EMPRESARIAL METROPOLITANO BODEGA 51','eticosserrano@gmail.com','6015873010','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(14,'MEMPHIS PRODUCTS SA','800042169','CALLE 17  34 64','memphis@gmail.com','6017447878','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(15,'PHARMEUROPEA DE COLOMBIA','830088135','CRA 88 A  64 D 32','pharmeuropea@gmail.com','6012230562','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(16,'SALAMANCA RAFAEL ANTONIO','17068260','TV 93 51 98 PARQUE EMP PUERTA DEL SOL BG 18','drogasboyaca@gmail.com','6017432597','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04'),(17,'COOPIDROGAS','860026123','Autopista Bogot├í - Medell├¡n, kil├│metro 4.7 costado norte, antes del puente de Siberia','copodrogas@gmail.com','6014375150','[]',NULL,'2025-07-16 06:48:04','2025-07-16 06:48:04');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `checklist_item_id` bigint unsigned NOT NULL,
  `improvement_plan_id` bigint unsigned NOT NULL,
  `causal_analysis` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ends_at` datetime DEFAULT NULL,
  `status` enum('in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_team_id_foreign` (`team_id`),
  KEY `tasks_user_id_foreign` (`user_id`),
  KEY `tasks_checklist_item_id_foreign` (`checklist_item_id`),
  KEY `tasks_improvement_plan_id_foreign` (`improvement_plan_id`),
  CONSTRAINT `tasks_checklist_item_id_foreign` FOREIGN KEY (`checklist_item_id`) REFERENCES `checklist_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_improvement_plan_id_foreign` FOREIGN KEY (`improvement_plan_id`) REFERENCES `improvement_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_product_prices`
--

DROP TABLE IF EXISTS `team_product_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_product_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `min` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_product_prices_team_id_foreign` (`team_id`),
  KEY `team_product_prices_product_id_foreign` (`product_id`),
  CONSTRAINT `team_product_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_product_prices_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_product_prices`
--

LOCK TABLES `team_product_prices` WRITE;
/*!40000 ALTER TABLE `team_product_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_product_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_user_team_id_foreign` (`team_id`),
  KEY `team_user_user_id_foreign` (`user_id`),
  CONSTRAINT `team_user_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_user`
--

LOCK TABLES `team_user` WRITE;
/*!40000 ALTER TABLE `team_user` DISABLE KEYS */;
INSERT INTO `team_user` VALUES (1,1,5,NULL,NULL);
/*!40000 ALTER TABLE `team_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teams_identification_unique` (`identification`),
  UNIQUE KEY `teams_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Gutkowski, Osinski and Veum','dfe81f60-8a8a-37b7-8800-ea56e511eff5','421 Blanda Estate\nGertrudeview, WY 92902','barrows.iliana@wuckert.org','+15129514484',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(2,'Zieme, Hill and Mayer','bd37397f-6b9c-3298-be46-6699d098225a','7955 Ignacio Dale Apt. 617\nEleonorestad, ME 93842-9670','bernardo.goodwin@hills.org','+1 (279) 537-5302',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(3,'Price, Gerhold and Reinger','87a6d378-d83c-3b18-929c-ac0f76160a9a','9126 Reilly Roads Suite 671\nLake Percy, IA 24156-7481','dino22@king.com','1-248-364-6711',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(4,'Roberts Ltd','33d39d60-3e8e-3957-8a05-31ea00a7184a','904 Legros Spurs Suite 625\nKreigertown, OR 21766-0112','brenden46@lockman.com','585.708.5505',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(5,'Streich, Jacobson and Wilkinson','135f0b62-13d1-36d1-8094-b532226afc52','363 Layla Terrace\nGutmannhaven, NM 84569-3630','kovacek.emmie@kirlin.com','907.407.3375',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(6,'Cummerata LLC','68d2375a-36c2-39db-a3bb-9e7c64fdeec3','2355 Watsica Terrace\nFloydland, NM 81250-9405','mekhi73@streich.com','678.959.1828',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(7,'Russel-Waelchi','08d68aa0-e6ee-3158-a860-0e239817e2ee','7310 Deondre Highway Suite 983\nSouth Stanchester, OR 45110-2254','bernadette.hirthe@goldner.com','628-577-6950',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(8,'Ryan-Lockman','431b6e0d-899e-3d7e-a03a-74be313be0fb','966 Crona Rue\nGriffinmouth, MD 81742','jblock@king.com','360.943.3717',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(9,'Keebler Group','2cd2db33-6423-351b-9f6f-0ff33f13c2cd','551 Elliot Fall\nPort Ignacio, MS 64940-6581','marks.maverick@rodriguez.biz','1-478-206-2894',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL),(10,'Orn, Grimes and Nicolas','02cb088d-86c6-3b3a-adee-74c0a6dd92c6','48957 Kuhn Summit Suite 909\nEbertport, FL 67963','reymundo.fadel@flatley.com','+1.360.733.2651',NULL,'2025-07-16 06:46:24','2025-07-16 06:46:24',NULL);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant_settings`
--

DROP TABLE IF EXISTS `tenant_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenant_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `setting_id` bigint unsigned NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_settings_team_id_foreign` (`team_id`),
  KEY `tenant_settings_setting_id_foreign` (`setting_id`),
  CONSTRAINT `tenant_settings_setting_id_foreign` FOREIGN KEY (`setting_id`) REFERENCES `settings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tenant_settings_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant_settings`
--

LOCK TABLES `tenant_settings` WRITE;
/*!40000 ALTER TABLE `tenant_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tenant_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_categories`
--

DROP TABLE IF EXISTS `training_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `data` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_categories_team_id_foreign` (`team_id`),
  CONSTRAINT `training_categories_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_categories`
--

LOCK TABLES `training_categories` WRITE;
/*!40000 ALTER TABLE `training_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trainings`
--

DROP TABLE IF EXISTS `trainings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trainings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `training_category_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainings_team_id_foreign` (`team_id`),
  KEY `trainings_training_category_id_foreign` (`training_category_id`),
  CONSTRAINT `trainings_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trainings_training_category_id_foreign` FOREIGN KEY (`training_category_id`) REFERENCES `training_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trainings`
--

LOCK TABLES `trainings` WRITE;
/*!40000 ALTER TABLE `trainings` DISABLE KEYS */;
/*!40000 ALTER TABLE `trainings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_answers`
--

DROP TABLE IF EXISTS `user_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `question_id` bigint unsigned NOT NULL,
  `question_option_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_answers_user_id_foreign` (`user_id`),
  KEY `user_answers_question_id_foreign` (`question_id`),
  KEY `user_answers_question_option_id_foreign` (`question_option_id`),
  CONSTRAINT `user_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_answers_question_option_id_foreign` FOREIGN KEY (`question_option_id`) REFERENCES `question_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_answers`
--

LOCK TABLES `user_answers` WRITE;
/*!40000 ALTER TABLE `user_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_surgeon` tinyint(1) NOT NULL DEFAULT '0',
  `data` json DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Irma Hartmann DVM','zmorar@example.net','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'njVsGFV4ll','2025-07-16 06:46:24','2025-07-16 06:46:24'),(2,'Ms. Lorena Kirlin','lynch.wendy@example.net','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'MiK0hiNmjG','2025-07-16 06:46:24','2025-07-16 06:46:24'),(3,'Winfield McDermott','ursula67@example.com','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'1CwvB9gljy','2025-07-16 06:46:24','2025-07-16 06:46:24'),(4,'Mr. Vincent Price Sr.','jannie.hudson@example.com','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'gfrkitmer0','2025-07-16 06:46:24','2025-07-16 06:46:24'),(5,'Leola Morissette','zgoodwin@drogueriadigital.net.co','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'N8QiYlS3sHVkUsRsf08puSdPiPnSwwTUCVm3unxzr2FFOLDPE5e066iPzEY1','2025-07-16 06:46:24','2025-07-16 06:46:24'),(6,'Prof. Maribel White','carmella17@example.org','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'Cpr5vaoy0K','2025-07-16 06:46:24','2025-07-16 06:46:24'),(7,'Lisandro D\'Amore','gina70@example.com','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'cYNU4zuWh8','2025-07-16 06:46:24','2025-07-16 06:46:24'),(8,'Elsa Ullrich','kwiza@example.net','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'K5cZx8pkZb','2025-07-16 06:46:24','2025-07-16 06:46:24'),(9,'Dr. Ernestine Dooley PhD','dion39@example.com','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'TxPQdJno3M','2025-07-16 06:46:24','2025-07-16 06:46:24'),(10,'Ellie Stehr','cassin.alysa@example.net','2025-07-16 06:46:24','$2y$12$lVBPUEVD9eq8gb.gKYyq1OluIYf2mCDtmtjkycqYV2.gW5x6/1K9K',0,NULL,'8CZaUUoxmV','2025-07-16 06:46:24','2025-07-16 06:46:24');
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

-- Dump completed on 2025-07-17 19:26:10
