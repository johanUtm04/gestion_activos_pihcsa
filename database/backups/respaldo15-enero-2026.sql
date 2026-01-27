/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: gestion_activos_pihcsa
-- ------------------------------------------------------
-- Server version	10.6.18-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `discos_duros`
--

DROP TABLE IF EXISTS `discos_duros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discos_duros` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint(20) unsigned NOT NULL,
  `capacidad` varchar(255) NOT NULL,
  `tipo_hdd_ssd` varchar(255) NOT NULL,
  `interface` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discos_duros_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `discos_duros_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discos_duros`
--

LOCK TABLES `discos_duros` WRITE;
/*!40000 ALTER TABLE `discos_duros` DISABLE KEYS */;
INSERT INTO `discos_duros` VALUES (2,1,'120GB','HDD','SATA','2026-01-15 23:18:25','2026-01-15 23:18:25');
/*!40000 ALTER TABLE `discos_duros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `marca_equipo` varchar(255) DEFAULT NULL COMMENT 'Ej. Lenovo, Dell',
  `tipo_equipo` varchar(255) NOT NULL,
  `serial` varchar(255) NOT NULL,
  `sistema_operativo` varchar(100) NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `ubicacion_id` bigint(20) unsigned NOT NULL,
  `valor_inicial` decimal(8,2) NOT NULL,
  `fecha_adquisicion` date NOT NULL,
  `vida_util_estimada` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipos_ubicacion_id_foreign` (`ubicacion_id`),
  KEY `equipos_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `equipos_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `equipos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
INSERT INTO `equipos` VALUES (1,'Samsung','Laptop','soy_un_ejemplo','Windows 11',2,1,15000.00,'2023-01-01','9 a�os','2026-01-15 03:33:46','2026-01-16 00:08:09');
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historiales_log`
--

DROP TABLE IF EXISTS `historiales_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historiales_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activo_id` bigint(20) unsigned NOT NULL,
  `usuario_accion_id` bigint(20) unsigned NOT NULL,
  `tipo_registro` varchar(255) NOT NULL,
  `detalles_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles_json`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historiales_log_usuario_accion_id_foreign` (`usuario_accion_id`),
  KEY `historiales_log_activo_id_foreign` (`activo_id`),
  CONSTRAINT `historiales_log_activo_id_foreign` FOREIGN KEY (`activo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historiales_log_usuario_accion_id_foreign` FOREIGN KEY (`usuario_accion_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historiales_log`
--

LOCK TABLES `historiales_log` WRITE;
/*!40000 ALTER TABLE `historiales_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `historiales_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2025_11_19_203722_create_products_table',1),(3,'2025_11_19_204126_create_users_table',1),(4,'2025_11_19_205912_create_ubicaciones_table',1),(5,'2025_11_19_210833_create_equipos_table',1),(6,'2025_11_19_211748_create_historiales_log_table',1),(7,'2025_11_19_212251_create_historiales_log_table',1),(8,'2025_11_19_212612_create_discos_duros_table',1),(9,'2025_11_19_213123_create_monitores_table',1),(10,'2025_11_19_213427_create_rams_table',1),(11,'2025_11_19_213950_create_perifericos_table',1),(12,'2025_11_19_214221_create_procesadores_table',1),(13,'2025_11_19_220556_rename_correo_to_email_in_users_table',1),(14,'2025_11_19_220850_rename_nombre_to_name_in_users_table',1),(15,'2026_01_09_110259_add_soft_deletes_to_equipos_table',2),(17,'2026_01_09_110931_change_sistema_operativo_size_in_equipos_table',3),(18,'2026_01_12_180745_create_imagens_table',4),(19,'2026_01_13_014101_remove_deleted_at_from_equipos_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monitores`
--

DROP TABLE IF EXISTS `monitores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint(20) unsigned NOT NULL,
  `marca` varchar(255) NOT NULL,
  `serial` varchar(255) NOT NULL,
  `escala_pulgadas` varchar(255) NOT NULL,
  `interface` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `monitores_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `monitores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monitores`
--

LOCK TABLES `monitores` WRITE;
/*!40000 ALTER TABLE `monitores` DISABLE KEYS */;
INSERT INTO `monitores` VALUES (5,1,'Dell','soy_un_ejemplo','19','HDMI','2026-01-15 23:18:06','2026-01-16 00:08:57');
/*!40000 ALTER TABLE `monitores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perifericos`
--

DROP TABLE IF EXISTS `perifericos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perifericos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint(20) unsigned NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `serial` varchar(255) NOT NULL,
  `interface` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perifericos_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `perifericos_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perifericos`
--

LOCK TABLES `perifericos` WRITE;
/*!40000 ALTER TABLE `perifericos` DISABLE KEYS */;
INSERT INTO `perifericos` VALUES (8,1,'Teclado','HP','soy_un_ejemplo','USB','2026-01-15 23:17:21','2026-01-16 00:08:38');
/*!40000 ALTER TABLE `perifericos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procesadores`
--

DROP TABLE IF EXISTS `procesadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procesadores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint(20) unsigned NOT NULL,
  `marca` varchar(255) NOT NULL,
  `descripcion_tipo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `procesadores_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `procesadores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procesadores`
--

LOCK TABLES `procesadores` WRITE;
/*!40000 ALTER TABLE `procesadores` DISABLE KEYS */;
INSERT INTO `procesadores` VALUES (5,1,'HP','soy_un_ejemplo','2026-01-15 23:17:56','2026-01-16 00:08:52');
/*!40000 ALTER TABLE `procesadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rams`
--

DROP TABLE IF EXISTS `rams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint(20) unsigned NOT NULL,
  `capacidad_gb` varchar(255) NOT NULL,
  `clock_mhz` varchar(255) DEFAULT NULL,
  `tipo_chz` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rams_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `rams_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rams`
--

LOCK TABLES `rams` WRITE;
/*!40000 ALTER TABLE `rams` DISABLE KEYS */;
INSERT INTO `rams` VALUES (5,1,'2','3000','DDR3L','2026-01-15 23:17:40','2026-01-15 23:17:40');
/*!40000 ALTER TABLE `rams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones`
--

LOCK TABLES `ubicaciones` WRITE;
/*!40000 ALTER TABLE `ubicaciones` DISABLE KEYS */;
INSERT INTO `ubicaciones` VALUES (1,'Morelia','58200','2026-01-09 17:08:28','2026-01-15 20:57:07'),(2,'CDMX','04600','2026-01-15 20:53:53','2026-01-15 20:53:53'),(3,'CHIHUAHUA','31054','2026-01-15 20:54:17','2026-01-15 20:54:17'),(4,'GUADALAJARA','44320','2026-01-15 20:55:26','2026-01-15 20:55:26'),(5,'LEON','37218','2026-01-15 20:56:11','2026-01-15 20:56:11'),(6,'TOLUCA','50200','2026-01-15 20:57:38','2026-01-15 20:57:38');
/*!40000 ALTER TABLE `ubicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'SISTEMAS' COMMENT 'ADMIN, SISTEMAS, CONTABILIDAD.',
  `departamento` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estatus` varchar(50) NOT NULL DEFAULT 'ACTIVO',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_correo_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Ingeniero Marcos','marcos@pihcsa.com','ADMIN','SISTEMAS','$2y$12$Tjg.fsm/tgVZw3FtVJKEX.W0DUfebDYRFdmhO0O7tZyCXLiKGHFgq','ACTIVO',NULL,NULL,'2026-01-16 00:24:01'),(2,'Israel Erick Razo Falcón','israel@pihcsa.com','ADMIN','SISTEMAS','$2y$12$ENAci7R9JDifvXQqN2cRiud96YdrbR1yFyRzW1dw5HyggPz/uGnO6','ACTIVO',NULL,'2026-01-15 03:06:18','2026-01-15 23:32:34'),(3,'Alejandro Escobedo','alejandro@pihcsa.com','INVITADO','ALMACEN','$2y$12$dk.P1xfW1dYHETcVRzo/MuliB6OSffeDqaZevruhQ4ZKUG2XXI68m','ACTIVO',NULL,'2026-01-15 21:15:22','2026-01-15 23:38:57'),(4,'Alfonso Dominguez Pantoja','alfonso@pihcsa.com','INVITADO','SISTEMAS','$2y$12$3niN5Fsf2ih1yBOldIi6JO.foCc0jp6tjZp3IjyacciC/Qk7gVTmW','ACTIVO',NULL,'2026-01-15 21:20:33','2026-01-15 23:36:27'),(5,'Ángeles González','angeles@pihcsa.com','INVITADO','ALMACEN','$2y$12$Cxd59j0smuhzylYqAl5Wnel0BaM3Pa/FQd24WyJQd3kQsLxbwALIq','ACTIVO',NULL,'2026-01-15 21:21:12','2026-01-15 23:38:17'),(6,'Aracely Pérez','araceli@pihcsa.com','INVITADO','SISTEMAS','$2y$12$XQsm5IDclnLP6lWl4rARl.ggaiO7AdNBgX8yIOuUPyhw/v05NR5qG','ACTIVO',NULL,'2026-01-15 21:21:52','2026-01-15 23:39:48'),(7,'Benjamin Infante','benjamin@pihcsa.com','INVITADO','ALMACEN','$2y$12$0wo.TDXl2a/UQG3ZAzFpPOSXTbNmmyAFK2FEYB9WOXM0WlQ/AApK.','ACTIVO',NULL,'2026-01-15 21:22:31','2026-01-15 23:40:23'),(8,'Berenice Martínez Ceja','berenice@pihcsa.com','INVITADO','SISTEMAS','$2y$12$IAN8fwda47QaHafEPFlXCu1ioX6v4gTURC7NAYauLfW5Xr94D8w.S','ACTIVO',NULL,'2026-01-15 21:23:05','2026-01-15 23:41:14'),(9,'Carlos Colin','carlos@pihcsa.com','INVITADO','ALMACEN','$2y$12$rgVuCoAefDhAVlpuZ/uGxu4fKB0C2fpDO.Iqam19LOALNWbG3yT2e','ACTIVO',NULL,'2026-01-15 21:23:43','2026-01-15 23:41:32'),(10,'Christian Vargas','chrstian@pihcsa.com','INVITADO','ALMACEN','$2y$12$pc0ywIGDtqDNMkyNRlbyNuq2UQ5Fo2N9T.vJkCR09LsMsTBXuVqNO','ACTIVO',NULL,'2026-01-15 21:24:34','2026-01-15 23:41:51'),(11,'Christian Arellano','christianarellano@pihsa.com','INVITADO','SISTEMAS','$2y$12$i7XBAkaUYzEjYNiHXVg.FOPY7mKaXvXM.1D06XcZQMTiSizWwjQqy','ACTIVO',NULL,'2026-01-15 21:25:29','2026-01-15 23:42:21'),(12,'Christian Julio Pioquinto','christianjulio@pihcsa.com','INVITADO','SISTEMAS','$2y$12$AvEJQw7/8QtA6UpOcPo27uNpMhhTiTiwVJey7ManMWKfU4Ze5yad6','ACTIVO',NULL,'2026-01-15 21:26:03','2026-01-15 23:42:38'),(13,'Citlali Ramos','citlatiramos@pihcsa.com','INVITADO','SISTEMAS','$2y$12$rQaBFch1dgwRXzQlYOKHU.XAykmtC.WuslLRSC3ueYxVh0S42j4mm','ACTIVO',NULL,'2026-01-15 21:26:51','2026-01-15 23:42:54'),(14,'Claudia Karina Sierra','claudia@pihcsa.com','INVITADO','SISTEMAS','$2y$12$0RYT0kz7k8aGE/gVuc1s3.78byvWH.uYBnrziFAFMhW6iTcXNVziO','ACTIVO',NULL,'2026-01-15 21:27:28','2026-01-15 23:43:32'),(15,'Claudia Barrueta','claudiabarreta@pihcsa.com','INVITADO','SISTEMAS','$2y$12$JIxyEOk1igov4UJduxmwjOdNA.tMZtkhcA3UF0Wh0mOWYKx.Dxp42','ACTIVO',NULL,'2026-01-15 21:28:05','2026-01-15 23:44:00'),(16,'Claudia Suárez','claudiasuarez@pihcsa.com','INVITADO','SISTEMAS','$2y$12$GpctxJF0Cjy5VUfWa/L8LujOlYXoDT7c.PQaBHMh7OhYF9NfCo3Ne','ACTIVO',NULL,'2026-01-15 21:28:44','2026-01-15 23:44:26'),(17,'COPIADORA','copiadora@pihcsa.com','INVITADO','COPIADORA','$2y$12$iFkIkt0bXZ2/V9yNWLh9ielkFj.W/DR3rnZwAxWHzdivOc0udMv/i','ACTIVO',NULL,'2026-01-15 21:29:23','2026-01-15 21:29:23'),(18,'Cristina Pereznegron','cristina@pihcsa.com','INVITADO','SISTEMAS','$2y$12$xnKIixZy2lPxi3OQo21GPe.1hOtOOdRUK7iFipCMq6t7vcknBwJ56','ACTIVO',NULL,'2026-01-15 21:29:56','2026-01-15 23:44:55'),(19,'Dilan Ocampo','dilan@pihcsa.com','INVITADO','ALMACEN','$2y$12$PQYjTBztuMEsdV0URB9eqONg/s1wykpPSao94MWEhn/JmkL8xEETe','ACTIVO',NULL,'2026-01-15 21:30:30','2026-01-15 23:46:16'),(20,'Eder Alejandro Vázquez','edervazquez@pihcsa.com','INVITADO','SISTEMAS','$2y$12$.FeeZG3261pNR1AosdkffuBctG8FrUV1dm2gAi0ml.6x/wkAubx9q','ACTIVO',NULL,'2026-01-15 21:31:12','2026-01-15 23:46:59'),(21,'Efigenia Suárez','efigenia@pihcsa.com','INVITADO','SISTEMAS','$2y$12$.qMbhcB0SxnvCr/.oyqkBehVNgC/m3LPMFaDMMunC.iWV4VXiC/zu','ACTIVO',NULL,'2026-01-15 21:31:43','2026-01-15 23:47:20'),(22,'Emma Hernández','emma@pihcsa.com','INVITADO','SISTEMAS','$2y$12$yG3R7MLgPFh9SxahtE7/gO4cNHnQHvWoGi9.8FrTrWCfY7Kx1m2wW','ACTIVO',NULL,'2026-01-15 21:32:14','2026-01-15 23:49:05'),(23,'Erika Valencia Salas','erika@pihcsa.com','INVITADO','ALMACEN','$2y$12$6vD2MnNMZbhRLFl3/fcaRuggL8fkWIz3i42FZXJGScigICiXpoUKC','ACTIVO',NULL,'2026-01-15 21:33:03','2026-01-15 23:52:15'),(24,'Esthefanya Ayala','esthefanya@pihcsa.com','INVITADO','SISTEMAS','$2y$12$92LdOpb6TvlA71vr7InumOOK6riP2Prq622lIU6rKXpE7T6NfDBZy','ACTIVO',NULL,'2026-01-15 21:34:17','2026-01-15 23:52:54'),(25,'ETIQUETAS','etiquetas@pihcsa.com','INVITADO','ETIQUETAS','$2y$12$Anx6n.1j3Hx67spDgbpsjOrAnNVeOFUJwVzfMA7Ax4i1mgTxScMxq','ACTIVO',NULL,'2026-01-15 21:34:43','2026-01-15 21:34:43'),(26,'Eva Nuño','eva@pihcsa.com','INVITADO','ALMACEN','$2y$12$Aebijy/Le0A65W1hVZmzUeOWkVEdJ3rmoBFSALWaSVIfNyTkF0C3G','ACTIVO',NULL,'2026-01-15 21:35:13','2026-01-15 23:53:07'),(27,'Fabian Nava','fabian@pihcsa.com','INVITADO','ALMACEN','$2y$12$WZ1eBIAE.oquof2xAXWnruZWEoOMo48SZnbFj4xciHTcU50s5cRG.','ACTIVO',NULL,'2026-01-15 21:35:41','2026-01-15 23:53:29'),(28,'Luis Fernando González','fernando@pihcsa.com','INVITADO','ALMACEN','$2y$12$mMZASFfS0QCBEHKyae5Xw.8WQC7CHkVEurY1nFS08LVMmUT.CqfVe','ACTIVO',NULL,'2026-01-15 21:36:09','2026-01-15 23:54:24'),(29,'Francisco','francisco@pihcsa.com','INVITADO','ALMACEN','$2y$12$49x2H9uPf3/bRDb7oD.SDehqXycC7H4eiEOulyIKzon6M/.38.xBK','ACTIVO',NULL,'2026-01-15 21:36:48','2026-01-15 23:54:56'),(30,'Gabriel Puertos Castañeda','gabriel@pihcsa.com','INVITADO','SISTEMAS','$2y$12$aGgKyE1kuNqp94Zn8q0INOshTt5Q/XULlRq5.D6UC7FCqz6GwtxLO','ACTIVO',NULL,'2026-01-15 21:37:13','2026-01-15 23:55:44'),(31,'Gabriela Alipio Cahue','gabriela@pihcsa.com','INVITADO','SISTEMAS','$2y$12$WxljpFqGTp7q2Gq6ZKA/3uock6s0/JThVMV69E9oHLMAVmcWLZc4G','ACTIVO',NULL,'2026-01-15 21:37:58','2026-01-15 23:56:09'),(32,'Gerardo Hiram Hernández','gerardo@pihcsa.com','INVITADO','SISTEMAS','$2y$12$vrm5HofPsYruhLipPDVVGeQCk4myqU0JUtxk2nUUeIupJBVyT0VWC','ACTIVO',NULL,'2026-01-15 21:38:44','2026-01-15 23:56:30'),(33,'Guadalupe Martínez','guadalupemartinez@pihcsa.com','INVITADO','SISTEMAS','$2y$12$S3n.TUrjKApvX4NfyuROfeIC36ALZBNetZXAXjsqQE7B4zaKKN6N.','ACTIVO',NULL,'2026-01-15 21:39:21','2026-01-15 23:57:46'),(34,'Hernán Behar Gallegos','hernangallegos@pihcsa.com','INVITADO','ALMACEN','$2y$12$lGAleIX5DkjsuRrLRayk7OWVlzZu6mVamDQxWGZwpgmgEV7NFJJJe','ACTIVO',NULL,'2026-01-15 21:40:19','2026-01-15 23:58:34'),(35,'Irving Leal','irving@pihcsa.com','INVITADO','SISTEMAS','$2y$12$jChc8OhPGnFRBHe7ZTdjsuEETRp4tIenhGg4vTieAM2wTSFJrbpli','ACTIVO',NULL,'2026-01-15 21:40:55','2026-01-15 23:58:46'),(36,'Isauro Martínez','isauro@pihcsa.com','INVITADO','SISTEMAS','$2y$12$vsqrOus3fWILYse.UkFcTu5H1ewb3t4Q48wRF4IKcZCgMs513aEAO','ACTIVO',NULL,'2026-01-15 21:41:50','2026-01-15 23:59:20'),(37,'Ivan Jahyr Nava','ivan@pihcsa.com','INVITADO','SISTEMAS','$2y$12$cvm3aA5TEzlEL5pe.p3axuqJwiDg5LsL5uI3p2eHEd/WUW5Hy4CUq','ACTIVO',NULL,'2026-01-15 21:42:40','2026-01-15 23:59:40'),(38,'Jese Andy Soto','jese@pihcsa.com','INVITADO','OPERACIONES','$2y$12$cY5.Iu3vlgvBn/yahNULDO/u.g44ddTSa8fVTBCfyarruTJ5U4uS6','ACTIVO',NULL,'2026-01-15 21:43:07','2026-01-16 00:00:10'),(39,'José Esteban Torres','josetorres@pihcsa.com','INVITADO','SISTEMAS','$2y$12$p2k1kuC3VXa9OEt5EOmBGOmsF069n7e1xfnJOa5rJxn8LBiZIHdLi','ACTIVO',NULL,'2026-01-15 21:43:46','2026-01-16 00:00:29'),(40,'Lleymi Chávez','lleimi@pihcsa.com','INVITADO','SISTEMAS','$2y$12$8bEUeBxNYV7H0eVHxnWiMeo9Ir2fLexhfyjVFKig274RzBPRysPym','ACTIVO',NULL,'2026-01-15 21:44:10','2026-01-16 00:00:56'),(41,'Lorena Posadas','lorena@pihcsa.com','INVITADO','SISTEMAS','$2y$12$x0IMA8TveKSM.xYX9zY5N.lSZ86oym4Q7OjJhIDOw3AFwxue5ARZS','ACTIVO',NULL,'2026-01-15 21:44:43','2026-01-16 00:01:16'),(42,'Mauricio Ruíz','mauricio@pihcsa.com','INVITADO','SISTEMAS','$2y$12$b1n7aeSaBTaeCY9pR8qdZOLxdnJg3i7YrdK6c0UXWLCZkiaxXr8du','ACTIVO',NULL,'2026-01-15 21:46:00','2026-01-16 00:01:50'),(43,'Viviana Montserrat Hernández','monse@pihcsa.com','INVITADO','VENTAS','$2y$12$fBiUL9Kc9k7nLsMkq25nuuaX/E74FAJV69Nm4jAE.W1S3CkXA/UFe','ACTIVO',NULL,'2026-01-15 21:46:24','2026-01-16 00:02:43'),(44,'Nataly Romero','nataly@pihcsa.com','INVITADO','VENTAS','$2y$12$Hp9aBYBQrfC6GwjjyFHjAek1f1BQjU8GN4ojO403Tz.71bonDC6iO','ACTIVO',NULL,'2026-01-15 21:53:49','2026-01-16 00:03:17'),(45,'Oscar Serrano','oscar@pihcsa.com','INVITADO','SISTEMAS','$2y$12$b/OxYQmWDXin.3F0dhNxueiAnmYyDIQa5nHgko6AA1erP/31fg6Ry','ACTIVO',NULL,'2026-01-15 21:54:18','2026-01-16 00:03:49'),(46,'Paulina Aguirre Macías','paulina@pihcsa.com','INVITADO','SISTEMAS','$2y$12$62L1CI8EWchXqs46atDQTuQWDzEnz.VV4QAEdz96p9BOUJeTT5.uS','ACTIVO',NULL,'2026-01-15 21:55:40','2026-01-16 00:04:18'),(47,'Roberto Estrada','roberto@pihcsa.com','INVITADO','ALMACEN','$2y$12$7rplNKLWRw.AcIzsxaoU9uvsosHNKqlGE5jQDoJw.6KNpWniR/CYG','ACTIVO',NULL,'2026-01-15 21:56:05','2026-01-16 00:05:11'),(48,'Ruth Cervantes','ruth@pihcsa.com','INVITADO','ALMACEN','$2y$12$2SrMlMBhabrM6haMMaKSguM2SSgnspV9/MyUKC3Ep1D.o0iBkwSyC','ACTIVO',NULL,'2026-01-15 21:56:42','2026-01-16 00:05:42'),(49,'Sara Renteria Alcauter','sara@pihcsa.com','INVITADO','SISTEMAS','$2y$12$H0.W5uAZ9KyDRrQnm/P4DuIvkZPzf3VO1zROvVzTanHWSBPCkVnSK','ACTIVO',NULL,'2026-01-15 21:57:10','2026-01-16 00:06:08'),(50,'Saúl Cruz','saul@pihcsa.com','INVITADO','SISTEMAS','$2y$12$CfLIDm9grtvi01x3dLgbDufrwlBqpg6tK3Fft6/kssWaKPA2GNkCa','ACTIVO',NULL,'2026-01-15 21:57:48','2026-01-16 00:06:38'),(51,'Sergio Durán','sergio@pihcsa.com','INVITADO','SISTEMAS','$2y$12$URKmbX2FXhEJSCWTbjcf6OfY0NIEezrKLsRx5Rl23TGGT.XB4atQm','ACTIVO',NULL,'2026-01-15 21:58:24','2026-01-16 00:07:13'),(52,'SITE','site@pihcsa.com','INVITADO','SITE','$2y$12$DkIcCStbsmBIjy.KSaiVge/2dwhQECkrTU8.6ODwr6mMlKwEXuYgO','ACTIVO',NULL,'2026-01-15 21:59:09','2026-01-15 21:59:09'),(53,'Valentina Valdovinos','valentina@pihcsa.com','INVITADO','SISTEMAS','$2y$12$AT9mU3XX/PXK7u0ugtnYmOHofY7Jgar06j/g2NVz7r9KLJtBxG4pq','ACTIVO',NULL,'2026-01-15 21:59:37','2026-01-16 00:07:34'),(54,'Vigilancia','vigilancia@pihcsa.com','INVITADO','VIGILANCIA','$2y$12$caGQQnbTpTAJB3.3Qt4/SOVQHYAAE0m/sqzZnFGNhdh16hBpgcFaG','ACTIVO',NULL,'2026-01-15 22:00:16','2026-01-15 22:00:16'),(55,'Yolanda Janette Zapien','yolanda@pihcsa.com','INVITADO','SISTEMAS','$2y$12$ozeX0QixFYtMtu4Pnff5ReqtkceY0FXhvl9pXgiaCn9hy/WJw0jwC','ACTIVO',NULL,'2026-01-15 22:00:43','2026-01-16 00:07:59'),(56,'Yesenia Castro','yesenia@pihcsa.com','INVITADO','SISTEMAS','$2y$12$LewUnaK6Z/Z85YOK8XXjXeKXj.vVQG.YOtygdaFY0Uv1vCIBhWpDa','ACTIVO',NULL,'2026-01-15 22:01:13','2026-01-16 00:08:15'),(57,'Zaira Hernández','zaira@pihcsa.com','INVITADO','ALMACEN','$2y$12$Xm4hjBTuLL1KzkTV5Ootae5n2/iFpDpMDlSDXEMULyt5Os6EuIzKS','ACTIVO',NULL,'2026-01-15 22:01:48','2026-01-16 00:23:00'),(58,'Fiorella Figueroa','responsablesanitario@pihcsa.com.mx','INVITADO','CALIDAD','$2y$12$G1d6ZSnFSbDp8yG/eWyzQuOx.Bu5YuZMp.TgXcjq0Jercp2/rlyY6','ACTIVO',NULL,'2026-01-16 00:13:07','2026-01-16 00:13:07'),(59,'Ana Quiroz','ventasgobierno03@pihcsa.com.mx','INVITADO','VENTAS_GOB','$2y$12$wW1UJur0yh3cWS.sdtFFJeMIXRMErX1VeED7K.hQi1FwvKKm31ZfK','ACTIVO',NULL,'2026-01-16 00:14:27','2026-01-16 00:14:27'),(60,'José Felipe Gutiérrez Solano','supervisortoluca@pihcsa.com.mx','INVITADO','VENTAS_PRIV','$2y$12$REnPQoV2/N3T0Y5S1AHy9eRaX49LtWv2eEfifMQZcqDYAAtYMzp0W','ACTIVO',NULL,'2026-01-16 00:54:57','2026-01-16 00:54:57'),(61,'Guadalupe Gómez Gallardo','asistcredito@pihcsa.com.mx','INVITADO','CREDITO','$2y$12$Om0.fCKn6QN9uHqLkbzBy.hi2JfXDV1DSAdFycgeQIRAGGvNavVJK','ACTIVO',NULL,'2026-01-16 00:56:25','2026-01-16 00:56:25');
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

-- Dump completed on 2026-01-15 12:58:12
