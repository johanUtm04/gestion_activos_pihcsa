-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2025 at 06:19 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion_activos_pihcsa`
--

-- --------------------------------------------------------

--
-- Table structure for table `discos_duros`
--

CREATE TABLE `discos_duros` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `equipo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''equipos''',
  `capacidad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''1TB'', ''512GB'', ''2TB''',
  `tipo_hdd_ssd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''SSD'', ''HDD''',
  `interface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''SATA'', ''PCIe''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipos`
--

CREATE TABLE `equipos` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `marca_equipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''HP'', ''Dell''',
  `tipo_equipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''Laptop'', ''Escritorio'', ''Monitor''',
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'no. frabricante',
  `sistema_operativo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''Windows 11'', ''macOS''',
  `usuario_id` bigint UNSIGNED NOT NULL COMMENT 'fk. va a ''users''',
  `ubicacion_id` bigint UNSIGNED DEFAULT NULL COMMENT 'fk. va a ''ubicaciones''',
  `valor_inicial` double(10,2) DEFAULT NULL,
  `fecha_adquisicion` date NOT NULL COMMENT 'fecha de compra',
  `vida_util_estimada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''3 Años'', ''5 Años''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`id`, `marca_equipo`, `tipo_equipo`, `serial`, `sistema_operativo`, `usuario_id`, `ubicacion_id`, `valor_inicial`, `fecha_adquisicion`, `vida_util_estimada`, `created_at`, `updated_at`) VALUES
(129, 'Dell', 'Laptop', 'mw-56582736', 'Windows 12', 18, 1, 1600.00, '2018-12-12', '7 años', '2025-12-21 07:08:13', '2025-12-23 00:07:12');

-- --------------------------------------------------------

--
-- Table structure for table `historiales_log`
--

CREATE TABLE `historiales_log` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk',
  `activo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''equipos''',
  `usuario_accion_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''users''',
  `tipo_registro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''CREATE'', ''UPDATE'', ''DELETE'', ''ASIGNACION''',
  `detalles_json` json DEFAULT NULL COMMENT 'ej. valores antiguos y nuevos de los campos modificados',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_11_19_203722_create_products_table', 1),
(3, '2025_11_19_204126_create_users_table', 1),
(4, '2025_11_19_205912_create_ubicaciones_table', 1),
(5, '2025_11_19_210833_create_equipos_table', 1),
(6, '2025_11_19_211748_create_historiales_log_table', 1),
(7, '2025_11_19_212251_create_historiales_log_table', 1),
(8, '2025_11_19_212612_create_discos_duros_table', 1),
(9, '2025_11_19_213123_create_monitores_table', 1),
(10, '2025_11_19_213427_create_rams_table', 1),
(11, '2025_11_19_213950_create_perifericos_table', 1),
(12, '2025_11_19_214221_create_procesadores_table', 1),
(13, '2025_11_19_220556_rename_correo_to_email_in_users_table', 1),
(14, '2025_11_19_220850_rename_nombre_to_name_in_users_table', 1),
(15, '2025_12_17_161234_incrementar_caracteres__s_o', 2),
(16, '2025_12_17_165604_nullable_serial', 3);

-- --------------------------------------------------------

--
-- Table structure for table `monitores`
--

CREATE TABLE `monitores` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `equipo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''equipos''',
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''samsung''',
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'no. de fabricante',
  `escala_pulgadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''14''5''',
  `interface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''HDMI'', ''VGA''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perifericos`
--

CREATE TABLE `perifericos` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `equipo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''equipos''',
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''mouse''',
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''lenovo''',
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'no. de fabricante',
  `interface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''HDMI'', ''VGA''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procesadores`
--

CREATE TABLE `procesadores` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `equipo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''users''',
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''samsung''',
  `descripcion_tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''caracteristicas''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procesadores`
--

INSERT INTO `procesadores` (`id`, `equipo_id`, `marca`, `descripcion_tipo`, `created_at`, `updated_at`) VALUES
(39, 129, 'SAMSUNG', 'MODELO MEDICO', '2025-12-21 07:08:13', '2025-12-21 07:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rams`
--

CREATE TABLE `rams` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `equipo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''users''',
  `capacidad_gb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''16GB''',
  `clock_mhz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''3200'', ''2666',
  `tipo_chz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''DDR4'', ''DDR3''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rams`
--

INSERT INTO `rams` (`id`, `equipo_id`, `capacidad_gb`, `clock_mhz`, `tipo_chz`, `created_at`, `updated_at`) VALUES
(1, 22, '22GB', '89', '12', '2025-12-01 03:25:51', '2025-12-01 03:25:51'),
(2, 36, '22GB', '89', '12', '2025-12-02 00:47:15', '2025-12-02 00:47:15'),
(3, 38, '16', '...', '...', '2025-12-02 05:58:05', '2025-12-02 05:58:05'),
(4, 39, '8GB', '1600 MHz', 'DDR4', '2025-12-02 21:03:41', '2025-12-02 21:03:41'),
(5, 41, 'NAVIDAD', 'NAVIDAD', 'NAVIDAD', '2025-12-11 02:13:00', '2025-12-14 21:02:51'),
(6, 44, '16', '3600', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(7, 44, '16', '3600', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(8, 44, '32', '3000', 'VIRGENSITA', '2025-12-11 05:51:00', '2025-12-13 01:03:40'),
(9, 44, '8', '3000', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(10, 45, '16', '4000', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(11, 45, '64', '3200', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(12, 45, '16', '3000', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(13, 45, '64', '2400', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(14, 46, '8', '2400', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(15, 46, '64', '2400', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(16, 46, '16', '3200', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(17, 46, '16', '3600', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(18, 47, '4', '4000', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(19, 47, '8', '2133', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(20, 47, '64', '2400', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(21, 47, '32', '2133', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(22, 48, '8', '4000', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(23, 48, '32', '2133', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(24, 48, '32', '2133', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(25, 48, 'Jesus Esta en Ti', '2400', 'DDR4', '2025-12-11 05:51:00', '2025-12-12 23:51:48'),
(26, 49, '32', '3000', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(27, 49, '4', '2400', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(28, 49, '8', '3200', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(29, 49, '64', '2666', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(30, 50, '32', '4000', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(31, 50, '4', '2133', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(32, 50, '32', '3200', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(33, 50, '16', '3600', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(34, 51, '16', '2666', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(35, 51, '16', '3000', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(36, 51, '32', '2666', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(37, 51, '16', '3000', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(38, 52, '64', '2133', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(39, 52, '4', '3200', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(40, 52, '32', '3600', 'DDR5', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(41, 52, '8', '3600', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(42, 53, '4', '3600', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(43, 53, '8', '2400', 'DDR3', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(44, 53, '64', '3200', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(45, 53, '64', '3200', 'DDR4', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(46, 54, '12GB', 'NOSE XD', 'NO SE XD', '2025-12-12 01:31:29', '2025-12-12 01:31:29'),
(47, 56, '12GB', 'NAVIDAD', 'vacio', '2025-12-15 00:39:15', '2025-12-15 00:43:09'),
(48, 57, '12GB', NULL, 'vacio', '2025-12-15 04:30:53', '2025-12-15 04:30:53'),
(49, 58, '12GB', NULL, 'NO SE XD', '2025-12-17 03:23:25', '2025-12-17 03:23:25'),
(50, 59, 'EJ 22GB', NULL, 'DDR4', '2025-12-17 05:24:34', '2025-12-17 05:24:34'),
(51, 60, '22 GB', NULL, 'DDR7', '2025-12-17 21:16:35', '2025-12-17 21:16:35'),
(52, 61, 'equipos.wizard-discos_duros', NULL, 'equipos.wizard-discos_duros', '2025-12-17 21:20:38', '2025-12-17 21:20:38'),
(53, 108, '12GB', '3200', 'DDR5', '2025-12-18 07:57:30', '2025-12-18 07:57:30'),
(54, 113, '12GB', '12', 'DDR4', '2025-12-18 22:23:17', '2025-12-18 22:23:17'),
(55, 125, '69', NULL, NULL, '2025-12-21 03:50:55', '2025-12-21 03:50:55'),
(56, 126, '104', NULL, NULL, '2025-12-21 04:33:46', '2025-12-21 04:33:46'),
(57, 127, 'Scorpion Dorado Chillon', 'Scorpion Dorado Chillon', 'Scorpion Dorado Chillon', '2025-12-21 04:41:54', '2025-12-21 04:41:54'),
(58, 128, '9 Gb', '2666 Mhz', 'DDR4', '2025-12-21 06:57:16', '2025-12-21 06:57:16');

-- --------------------------------------------------------

--
-- Table structure for table `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''MORELIA''',
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. 58102',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `nombre`, `codigo`, `created_at`, `updated_at`) VALUES
(1, 'MORELIA - MICHOACAN', '58200', NULL, '2025-12-23 00:06:51'),
(2, 'ZAMORA - MICHOACAN', '59600', NULL, '2025-12-23 00:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''johan''',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''johan@mail''',
  `rol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SISTEMAS' COMMENT 'admin, sistemas, contabilidad',
  `departamento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sistemas, contabilidad',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estatus` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVO' COMMENT 'ACTIVO, INACTIVO',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `rol`, `departamento`, `password`, `estatus`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ingeniero Marcos Robles', 'ingeniero@gmail.com', 'admin', 'SISTEMAS', '$2y$12$jv0epM9YwRFuJMLZlShel.mfxNHhWjYYOm5zulvAcuXAYqiDCc12K', 'ACTIVO', 'AnztylD00toyD3R6PlCSwaseG79HAk1uhTfRoDKPmSEdhweLEzUvjOhmB2ZZ', '2025-11-26 01:28:33', '2025-12-22 23:48:48'),
(11, 'Luis Mendoza', 'luis.mendoza@ejemplo.com', 'SISTEMAS', 'SISTEMAS', '$2y$12$fwnrII5AUNj1ChwjuXrOI.K/5A7khJvYAPLg9srhJYk7FtxyuDfae', 'ACTIVO', NULL, '2025-12-11 05:25:10', '2025-12-11 05:25:10'),
(12, 'Carla Ruiz', 'carla.ruiz@ejemplo.com', 'CONTABILIDAD', 'Contabilidad', '$2y$12$tCDHwy7OG1jway4Iv.FhdOFw.N86K0wxQU6p.b6qz0uiLve97GyfC', 'ACTIVO', NULL, '2025-12-11 05:25:10', '2025-12-11 05:25:10'),
(13, 'Pedro Solis', 'pedro.solis@ejemplo.com', 'VENTAS', 'VENTAS', '$2y$12$l2Dm7hKTQIzofL5gp4z8Ku5F5J1XDfhV6bJulBoPgcjZ9Oqzp5aDq', 'ACTIVO', NULL, '2025-12-11 05:25:10', '2025-12-11 05:25:10'),
(14, 'Elena Castro', 'elena.castro@ejemplo.com', 'ADMIN', 'RECURSOS HUMANOS', '$2y$12$KRJj0BpUUwPO5uwjbigcB.v1nQU13ugDydMF8Wz/jb7BvjPCkrcFm', 'INACTIVO', NULL, '2025-12-11 05:25:11', '2025-12-22 23:37:35'),
(16, 'Dr. Elian Ratke Jr.', 'yoconner@example.com', 'SISTEMAS', 'Sistemas', '$2y$12$3AfXWhbGgk79.JZt.h/PKOlPC1zcWfNLOnBaa5DwF46WbL12Yvaom', 'Activo', '2k1MM0ZevL', '2025-12-11 05:38:40', '2025-12-11 05:38:40'),
(17, 'Mckenzie Flatley', 'harber.king@example.com', 'SISTEMAS', 'Contabilidad', '$2y$12$3AfXWhbGgk79.JZt.h/PKOlPC1zcWfNLOnBaa5DwF46WbL12Yvaom', 'Suspendido', '7WnL7XPvTR', '2025-12-11 05:38:40', '2025-12-11 05:38:40'),
(18, 'Prof. Ole Haag I', 'chegmann@example.org', 'SISTEMAS', 'Contabilidad', '$2y$12$3AfXWhbGgk79.JZt.h/PKOlPC1zcWfNLOnBaa5DwF46WbL12Yvaom', 'Suspendido', 'gOmKnJuwlJ', '2025-12-11 05:38:40', '2025-12-11 05:38:40'),
(19, 'Kade Russel', 'joe.collier@example.com', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$3AfXWhbGgk79.JZt.h/PKOlPC1zcWfNLOnBaa5DwF46WbL12Yvaom', 'Activo', 'FTDUavLZxB', '2025-12-11 05:38:40', '2025-12-11 05:38:40'),
(20, 'Erick Torphy', 'talon35@example.com', 'SISTEMAS', 'Sistemas', '$2y$12$3AfXWhbGgk79.JZt.h/PKOlPC1zcWfNLOnBaa5DwF46WbL12Yvaom', 'Inactivo', 'XUjPJovZiT', '2025-12-11 05:38:40', '2025-12-11 05:38:40'),
(21, 'Margarete Bernhard', 'charlene.macejkovic@example.org', 'CONTABIILIDAD', 'Sistemas', '$2y$12$nnIWfsH4YBfK0EXe6YJ5ZOP0qBbv2B2QMtKUTozIgX4fb7iG/Tot2', 'Activo', 'x0MIY2kqVe', '2025-12-11 05:40:28', '2025-12-11 05:40:28'),
(22, 'Ms. Ernestina Hill', 'cvonrueden@example.com', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$nnIWfsH4YBfK0EXe6YJ5ZOP0qBbv2B2QMtKUTozIgX4fb7iG/Tot2', 'Suspendido', 'vlLRpxtte4', '2025-12-11 05:40:28', '2025-12-11 05:40:28'),
(23, 'Bernie Conroy', 'ewell.schoen@example.org', 'ADMINISTRADOR', 'Sistemas', '$2y$12$nnIWfsH4YBfK0EXe6YJ5ZOP0qBbv2B2QMtKUTozIgX4fb7iG/Tot2', 'Suspendido', 'S6xqlwmlUg', '2025-12-11 05:40:28', '2025-12-11 05:40:28'),
(24, 'Cleora Kilback II', 'trycia.jacobson@example.com', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$nnIWfsH4YBfK0EXe6YJ5ZOP0qBbv2B2QMtKUTozIgX4fb7iG/Tot2', 'Inactivo', 'u48FB1IL5X', '2025-12-11 05:40:28', '2025-12-11 05:40:28'),
(25, 'Dariana Beatty', 'kaelyn85@example.com', 'ADMINISTRADOR', 'Sistemas', '$2y$12$nnIWfsH4YBfK0EXe6YJ5ZOP0qBbv2B2QMtKUTozIgX4fb7iG/Tot2', 'Suspendido', 'srfOJ3Au22', '2025-12-11 05:40:28', '2025-12-11 05:40:28'),
(26, 'Ms. Rita Beatty', 'wolff.maximillian@example.org', 'CONTABIILIDAD', 'Sistemas', '$2y$12$dikVvuGkDboUaqjT0OLfg.aRwCrqx4N2D78oiDThjQY8xeGu.esk6', 'Suspendido', 'F6wUFP9Mq0', '2025-12-11 05:40:43', '2025-12-11 05:40:43'),
(27, 'Prof. Jaquan Larson', 'deangelo45@example.net', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$dikVvuGkDboUaqjT0OLfg.aRwCrqx4N2D78oiDThjQY8xeGu.esk6', 'Inactivo', 'BAtj5CgSwC', '2025-12-11 05:40:43', '2025-12-11 05:40:43'),
(28, 'Elise Deckow II', 'toy.diego@example.net', 'SISTEMAS', 'Sistemas', '$2y$12$dikVvuGkDboUaqjT0OLfg.aRwCrqx4N2D78oiDThjQY8xeGu.esk6', 'Activo', 'kj4M9qBSEw', '2025-12-11 05:40:43', '2025-12-11 05:40:43'),
(29, 'Laverna Dare', 'claire32@example.net', 'ADMINISTRADOR', 'Sistemas', '$2y$12$dikVvuGkDboUaqjT0OLfg.aRwCrqx4N2D78oiDThjQY8xeGu.esk6', 'Suspendido', 'RpIbMIWKVq', '2025-12-11 05:40:43', '2025-12-11 05:40:43'),
(30, 'Mr. Keven Beahan', 'dgreenholt@example.org', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$dikVvuGkDboUaqjT0OLfg.aRwCrqx4N2D78oiDThjQY8xeGu.esk6', 'Inactivo', 'wxMvjnKxVs', '2025-12-11 05:40:43', '2025-12-11 05:40:43'),
(31, 'Skylar Crona', 'scottie.ruecker@example.net', 'ADMINISTRADOR', 'Sistemas', '$2y$12$WqGGDgUGuSviG4dYt4atbu1fBEFCaFLD5P/N2.1Rlho7C0iQpfAPC', 'Activo', 'e8XwhOaAYB', '2025-12-11 05:44:40', '2025-12-11 05:44:40'),
(32, 'Camilla Hand', 'lnicolas@example.net', 'ADMINISTRADOR', 'Sistemas', '$2y$12$WqGGDgUGuSviG4dYt4atbu1fBEFCaFLD5P/N2.1Rlho7C0iQpfAPC', 'Activo', 'BvonVCeE8X', '2025-12-11 05:44:40', '2025-12-11 05:44:40'),
(33, 'Carter Trantow', 'joshua52@example.com', 'SISTEMAS', 'Contabilidad', '$2y$12$WqGGDgUGuSviG4dYt4atbu1fBEFCaFLD5P/N2.1Rlho7C0iQpfAPC', 'Suspendido', 'nXXjhvV2VJ', '2025-12-11 05:44:40', '2025-12-11 05:44:40'),
(34, 'Raven Ondricka', 'justus.hettinger@example.org', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$WqGGDgUGuSviG4dYt4atbu1fBEFCaFLD5P/N2.1Rlho7C0iQpfAPC', 'Activo', 'aKK3Jv5qgO', '2025-12-11 05:44:40', '2025-12-11 05:44:40'),
(35, 'Keyshawn Bergstrom', 'dgreen@example.com', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$WqGGDgUGuSviG4dYt4atbu1fBEFCaFLD5P/N2.1Rlho7C0iQpfAPC', 'Suspendido', 'PXuYphECsC', '2025-12-11 05:44:40', '2025-12-11 05:44:40'),
(36, 'Jazmin Corkery', 'wklocko@example.net', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$cg6YSrdqYihr3taFbUUo8OYD0p2513.jA.ecd8FWTwMAandjkqv2y', 'Suspendido', 'zdoGVPsZC1', '2025-12-11 05:44:49', '2025-12-11 05:44:49'),
(37, 'Mike Sipes', 'ygrant@example.net', 'ADMINISTRADOR', 'Sistemas', '$2y$12$cg6YSrdqYihr3taFbUUo8OYD0p2513.jA.ecd8FWTwMAandjkqv2y', 'Inactivo', 'mGhkeRpn9r', '2025-12-11 05:44:49', '2025-12-11 05:44:49'),
(38, 'Dr. Cody Weissnat', 'houston.jones@example.com', 'SISTEMAS', 'Sistemas', '$2y$12$cg6YSrdqYihr3taFbUUo8OYD0p2513.jA.ecd8FWTwMAandjkqv2y', 'Suspendido', 'HVYcNmt8nK', '2025-12-11 05:44:49', '2025-12-11 05:44:49'),
(39, 'Kimberly Wilkinson', 'franz.weissnat@example.com', 'SISTEMAS', 'Sistemas', '$2y$12$cg6YSrdqYihr3taFbUUo8OYD0p2513.jA.ecd8FWTwMAandjkqv2y', 'Suspendido', 'FfO93WuOJU', '2025-12-11 05:44:49', '2025-12-11 05:44:49'),
(40, 'Skylar Spencer', 'eichmann.reagan@example.org', 'SISTEMAS', 'Contabilidad', '$2y$12$cg6YSrdqYihr3taFbUUo8OYD0p2513.jA.ecd8FWTwMAandjkqv2y', 'Activo', 'K4ivxYz7aU', '2025-12-11 05:44:49', '2025-12-11 05:44:49'),
(41, 'Aida Corkery', 'lula.franecki@example.com', 'SISTEMAS', 'Contabilidad', '$2y$12$ihKgf5VOinr/StbOU6cNN.lZi/qxfj3fOm4wI6G.lEA3YMNY.xqD2', 'Inactivo', 'u2KGWXrcPB', '2025-12-11 05:45:34', '2025-12-11 05:45:34'),
(42, 'Vernie Berge', 'verona.kerluke@example.com', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$ihKgf5VOinr/StbOU6cNN.lZi/qxfj3fOm4wI6G.lEA3YMNY.xqD2', 'Suspendido', 'oNb3I2j87t', '2025-12-11 05:45:34', '2025-12-11 05:45:34'),
(43, 'Prof. Demetris Cronin', 'crona.marlin@example.com', 'ADMINISTRADOR', 'Sistemas', '$2y$12$ihKgf5VOinr/StbOU6cNN.lZi/qxfj3fOm4wI6G.lEA3YMNY.xqD2', 'Suspendido', 'uETO7bTuQB', '2025-12-11 05:45:34', '2025-12-11 05:45:34'),
(44, 'Mr. Amparo Bauch MD', 'efisher@example.org', 'ADMINISTRADOR', 'Sistemas', '$2y$12$ihKgf5VOinr/StbOU6cNN.lZi/qxfj3fOm4wI6G.lEA3YMNY.xqD2', 'Activo', 'hQTWZz1Zzh', '2025-12-11 05:45:34', '2025-12-11 05:45:34'),
(45, 'Meta Gibson', 'tschuster@example.com', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$ihKgf5VOinr/StbOU6cNN.lZi/qxfj3fOm4wI6G.lEA3YMNY.xqD2', 'Suspendido', '33TwGGskBi', '2025-12-11 05:45:34', '2025-12-11 05:45:34'),
(46, 'Mack Wisozk', 'ereynolds@example.net', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$3c/mOhF2SZ2j4W3rXBqVzux01fUTdQjmBZwyMONKkzl1aT30hRzry', 'Suspendido', 'UgsmMucSic', '2025-12-11 05:47:35', '2025-12-11 05:47:35'),
(47, 'Mrs. Rachelle O\'Kon PhD', 'naomie.mccullough@example.org', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$3c/mOhF2SZ2j4W3rXBqVzux01fUTdQjmBZwyMONKkzl1aT30hRzry', 'Inactivo', 'bIGPYSDimK', '2025-12-11 05:47:35', '2025-12-11 05:47:35'),
(48, 'Herminia Davis', 'bfadel@example.org', 'SISTEMAS', 'Sistemas', '$2y$12$3c/mOhF2SZ2j4W3rXBqVzux01fUTdQjmBZwyMONKkzl1aT30hRzry', 'Activo', 'RTDjMBVTBr', '2025-12-11 05:47:35', '2025-12-11 05:47:35'),
(49, 'Giuseppe Block', 'mueller.gillian@example.net', 'SISTEMAS', 'Contabilidad', '$2y$12$3c/mOhF2SZ2j4W3rXBqVzux01fUTdQjmBZwyMONKkzl1aT30hRzry', 'Activo', 'Pylikqylr6', '2025-12-11 05:47:35', '2025-12-11 05:47:35'),
(50, 'Prof. Dorthy Goldner MD', 'ignatius.eichmann@example.org', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$3c/mOhF2SZ2j4W3rXBqVzux01fUTdQjmBZwyMONKkzl1aT30hRzry', 'Suspendido', 'MDMoawlLC4', '2025-12-11 05:47:35', '2025-12-11 05:47:35'),
(51, 'Pearlie Kutch PhD', 'williamson.annamarie@example.net', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$mxiKzF2xY.FkJVZ3tSeryuWC3GhhJWtgn6dPf1xT4E/T0ZiNCcWfK', 'Activo', 'zGJpxM8JaX', '2025-12-11 05:47:54', '2025-12-11 05:47:54'),
(52, 'Mrs. Janiya Stanton', 'okeefe.aurelie@example.net', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$mxiKzF2xY.FkJVZ3tSeryuWC3GhhJWtgn6dPf1xT4E/T0ZiNCcWfK', 'Activo', '5d4xH9VtQQ', '2025-12-11 05:47:54', '2025-12-11 05:47:54'),
(53, 'Nikolas Borer', 'crist.allison@example.org', 'SISTEMAS', 'Sistemas', '$2y$12$mxiKzF2xY.FkJVZ3tSeryuWC3GhhJWtgn6dPf1xT4E/T0ZiNCcWfK', 'Activo', 'UrZ0QrUQyi', '2025-12-11 05:47:54', '2025-12-11 05:47:54'),
(54, 'Hallie Fahey', 'effertz.aleen@example.org', 'SISTEMAS', 'Contabilidad', '$2y$12$mxiKzF2xY.FkJVZ3tSeryuWC3GhhJWtgn6dPf1xT4E/T0ZiNCcWfK', 'Suspendido', '7Qw6QvNDlX', '2025-12-11 05:47:54', '2025-12-11 05:47:54'),
(55, 'Misael Lynch II', 'hoppe.joan@example.com', 'SISTEMAS', 'Contabilidad', '$2y$12$mxiKzF2xY.FkJVZ3tSeryuWC3GhhJWtgn6dPf1xT4E/T0ZiNCcWfK', 'Inactivo', 'qtOdD9EOsF', '2025-12-11 05:47:54', '2025-12-11 05:47:54'),
(56, 'Mr. Trystan Brakus DDS', 'xherman@example.com', 'ADMINISTRADOR', 'Sistemas', '$2y$12$A3Vazs68w4xRXUTBuTGydegoEIwV9HrLifHX4TVZd5OXvU0.iGesK', 'Suspendido', 'hK3XHfl3ms', '2025-12-11 05:48:33', '2025-12-11 05:48:33'),
(57, 'Fermin Muller DVM', 'xcummerata@example.com', 'CONTABIILIDAD', 'Sistemas', '$2y$12$A3Vazs68w4xRXUTBuTGydegoEIwV9HrLifHX4TVZd5OXvU0.iGesK', 'Suspendido', 'g8F0lNqhZC', '2025-12-11 05:48:33', '2025-12-11 05:48:33'),
(58, 'Russel Eichmann V', 'hazel90@example.org', 'ADMINISTRADOR', 'Sistemas', '$2y$12$A3Vazs68w4xRXUTBuTGydegoEIwV9HrLifHX4TVZd5OXvU0.iGesK', 'Inactivo', '2dGz7hP0vB', '2025-12-11 05:48:33', '2025-12-11 05:48:33'),
(59, 'Mrs. Leonor Dooley V', 'gritchie@example.org', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$A3Vazs68w4xRXUTBuTGydegoEIwV9HrLifHX4TVZd5OXvU0.iGesK', 'Suspendido', 'NHf8BODwAW', '2025-12-11 05:48:33', '2025-12-11 05:48:33'),
(60, 'Trenton Dickens', 'ebony56@example.org', 'SISTEMAS', 'Sistemas', '$2y$12$A3Vazs68w4xRXUTBuTGydegoEIwV9HrLifHX4TVZd5OXvU0.iGesK', 'Suspendido', 'nut4Hr4Xpv', '2025-12-11 05:48:33', '2025-12-11 05:48:33'),
(61, 'Mrs. Liana Weimann', 'keeley.oconner@example.org', 'SISTEMAS', 'Contabilidad', '$2y$12$YttBMy.wsjm9q4CKY.0.vevB4wzSLy27K2UymEKc7SM6/l6vC.hKO', 'Suspendido', 'XkhKDI5BQ8', '2025-12-11 05:48:58', '2025-12-11 05:48:58'),
(62, 'Karine Ortiz', 'karley95@example.org', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$YttBMy.wsjm9q4CKY.0.vevB4wzSLy27K2UymEKc7SM6/l6vC.hKO', 'Activo', 'eFwkMeTTy3', '2025-12-11 05:48:58', '2025-12-11 05:48:58'),
(63, 'Jessie Stroman', 'mcglynn.edna@example.org', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$YttBMy.wsjm9q4CKY.0.vevB4wzSLy27K2UymEKc7SM6/l6vC.hKO', 'Suspendido', 'G3fTgYdOPw', '2025-12-11 05:48:58', '2025-12-11 05:48:58'),
(64, 'Hubert Deckow Sr.', 'tcorwin@example.com', 'CONTABIILIDAD', 'Sistemas', '$2y$12$YttBMy.wsjm9q4CKY.0.vevB4wzSLy27K2UymEKc7SM6/l6vC.hKO', 'Suspendido', 'OmhVa9Yoo5', '2025-12-11 05:48:58', '2025-12-11 05:48:58'),
(65, 'Amparo Beahan', 'fernando22@example.com', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$YttBMy.wsjm9q4CKY.0.vevB4wzSLy27K2UymEKc7SM6/l6vC.hKO', 'Inactivo', 'jzQVZYnxSw', '2025-12-11 05:48:58', '2025-12-11 05:48:58'),
(66, 'Mr. Sherwood Hyatt', 'ugrady@example.net', 'CONTABIILIDAD', 'Contabilidad', '$2y$12$05r7hUSNB57nPYvcM2GD6e3pvs1536Ga7JEW9CpbEqbRg.g0/j4/2', 'Inactivo', 'WEFVwttovZ', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(67, 'Prof. Justyn Daniel', 'virgie95@example.com', 'ADMINISTRADOR', 'Contabilidad', '$2y$12$05r7hUSNB57nPYvcM2GD6e3pvs1536Ga7JEW9CpbEqbRg.g0/j4/2', 'Inactivo', 'Dc2c87lmSX', '2025-12-11 05:51:00', '2025-12-11 05:51:00'),
(68, 'Dr. Alfredo Casper', 'lind.suzanne@example.net', 'SISTEMAS', 'Sistemas', '$2y$12$05r7hUSNB57nPYvcM2GD6e3pvs1536Ga7JEW9CpbEqbRg.g0/j4/2', 'Activo', 'lOiGuYFm9v', '2025-12-11 05:51:00', '2025-12-11 05:51:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discos_duros`
--
ALTER TABLE `discos_duros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discos_duros_equipo_id_foreign` (`equipo_id`);

--
-- Indexes for table `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `equipos_ubicacion_id_foreign` (`ubicacion_id`);

--
-- Indexes for table `historiales_log`
--
ALTER TABLE `historiales_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historiales_log_activo_id_foreign` (`activo_id`),
  ADD KEY `historiales_log_usuario_accion_id_foreign` (`usuario_accion_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitores`
--
ALTER TABLE `monitores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `monitores_equipo_id_foreign` (`equipo_id`);

--
-- Indexes for table `perifericos`
--
ALTER TABLE `perifericos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perifericos_equipo_id_foreign` (`equipo_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `procesadores`
--
ALTER TABLE `procesadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procesadores_equipo_id_foreign` (`equipo_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rams`
--
ALTER TABLE `rams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rams_equipo_id_foreign` (`equipo_id`);

--
-- Indexes for table `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_correo_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discos_duros`
--
ALTER TABLE `discos_duros`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `historiales_log`
--
ALTER TABLE `historiales_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `monitores`
--
ALTER TABLE `monitores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `perifericos`
--
ALTER TABLE `perifericos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procesadores`
--
ALTER TABLE `procesadores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rams`
--
ALTER TABLE `rams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discos_duros`
--
ALTER TABLE `discos_duros`
  ADD CONSTRAINT `discos_duros_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`),
  ADD CONSTRAINT `equipos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `historiales_log`
--
ALTER TABLE `historiales_log`
  ADD CONSTRAINT `historiales_log_activo_id_foreign` FOREIGN KEY (`activo_id`) REFERENCES `equipos` (`id`),
  ADD CONSTRAINT `historiales_log_usuario_accion_id_foreign` FOREIGN KEY (`usuario_accion_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monitores`
--
ALTER TABLE `monitores`
  ADD CONSTRAINT `monitores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `perifericos`
--
ALTER TABLE `perifericos`
  ADD CONSTRAINT `perifericos_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `procesadores`
--
ALTER TABLE `procesadores`
  ADD CONSTRAINT `procesadores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
