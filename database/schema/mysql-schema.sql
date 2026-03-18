/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `discos_duros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discos_duros` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint unsigned NOT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacidad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_hdd_ssd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `motivo_inactivo` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `discos_duros_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `discos_duros_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `marca_id` bigint unsigned DEFAULT NULL,
  `modelo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ultimo_mantenimiento` date DEFAULT NULL,
  `tipo_activo_id` bigint unsigned DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_factura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sistema_operativo` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario_id` bigint unsigned NOT NULL,
  `ubicacion_id` bigint unsigned NOT NULL,
  `departamento_perteneciente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor_inicial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_adquisicion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio_uso` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vida_util_estimada` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivo_inactivacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipos_serial_unique` (`serial`),
  KEY `equipos_ubicacion_id_foreign` (`ubicacion_id`),
  KEY `equipos_usuario_id_foreign` (`usuario_id`),
  KEY `equipos_marca_id_foreign` (`marca_id`),
  KEY `equipos_tipo_activo_id_foreign` (`tipo_activo_id`),
  CONSTRAINT `equipos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  CONSTRAINT `equipos_tipo_activo_id_foreign` FOREIGN KEY (`tipo_activo_id`) REFERENCES `tipo_activos` (`id`),
  CONSTRAINT `equipos_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `equipos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `historiales_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historiales_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `activo_id` bigint unsigned NOT NULL,
  `usuario_accion_id` bigint unsigned NOT NULL,
  `tipo_registro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historiales_log_usuario_accion_id_foreign` (`usuario_accion_id`),
  KEY `historiales_log_activo_id_foreign` (`activo_id`),
  CONSTRAINT `historiales_log_activo_id_foreign` FOREIGN KEY (`activo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historiales_log_usuario_accion_id_foreign` FOREIGN KEY (`usuario_accion_id`) REFERENCES `users` (`id`),
  CONSTRAINT `historiales_log_chk_1` CHECK (json_valid(`detalles_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inpc_indices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inpc_indices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anio` int NOT NULL,
  `mes` int NOT NULL,
  `valor` decimal(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inpc_indices_anio_mes_unique` (`anio`,`mes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marcas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marcas_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `monitores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monitores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint unsigned NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `escala_pulgadas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `motivo_inactivo` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `monitores_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `monitores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `perifericos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perifericos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint unsigned NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interface` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `motivo_inactivo` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `perifericos_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `perifericos_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `procesadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procesadores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint unsigned NOT NULL,
  `marca` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion_tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_ghz` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `motivo_inactivo` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `procesadores_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `procesadores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `equipo_id` bigint unsigned NOT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacidad_gb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_mhz` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_chz` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `motivo_inactivo` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `rams_equipo_id_foreign` (`equipo_id`),
  CONSTRAINT `rams_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tasas_lisr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasas_lisr` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tipo_activos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_activos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frecuencia_meses` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipo_activos_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ubicaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SISTEMAS' COMMENT 'ADMIN, SISTEMAS, CONTABILIDAD.',
  `departamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estatus` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVO',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_correo_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2025_11_19_203722_create_products_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2025_11_19_204126_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_11_19_205912_create_ubicaciones_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_11_19_210833_create_equipos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_11_19_211748_create_historiales_log_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_11_19_212251_create_historiales_log_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_11_19_212612_create_discos_duros_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_11_19_213123_create_monitores_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_11_19_213427_create_rams_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_11_19_213950_create_perifericos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_11_19_214221_create_procesadores_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_11_19_220556_rename_correo_to_email_in_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_11_19_220850_rename_nombre_to_name_in_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_01_09_110259_add_soft_deletes_to_equipos_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_01_09_110931_change_sistema_operativo_size_in_equipos_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_01_12_180745_create_imagens_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_01_13_014101_remove_deleted_at_from_equipos_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_01_22_151922_create_marcas_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_01_22_151934_create_tipo_activos_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_01_22_152807_add_foreign_keys_to_equipos_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_01_28_152248_add_status_to_components_tables',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_02_10_093055_add_soft_deletes_to_equipos_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_02_10_093313_copy_add_soft_deletes_to_equipos_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2026_02_10_094146_add_inactivation_fields_to_equipos',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2026_02_11_120709_add_serial_to_components_tables',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_02_17_151941_add_numero_factura_to_equipos_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_02_17_172647_add_modelo_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_02_25_111048_add_frecuencia_mantenimiento_to_tipo_activos_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2026_02_25_113603_add_mantenimiento_fields_to_equipos_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_12_17_161234_incrementar_caracteres__s_o',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_12_17_165604_nullable_serial',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2026_03_02_140717_add_departamento_perteneciente_to_equipos_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2026_02_11_091501_add_soft_deletes_to_equipos_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_03_04_130156_add_unique_serial_to_equipos_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_03_10_131238_add_clock_ghz_to_procesadores_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_03_12_094336_add_fecha_inicio_uso_to_equipos_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_03_12_100855_create_tasas_lisr_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_03_12_100950_create_inpc_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_03_17_093742_create_marca_equipo_tipo_equipo',25);
