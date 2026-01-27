-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2025 at 07:38 PM
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
(109, 'SAMSUN', 'ÑÑÑÑÑÑÑ', NULL, 'ÑÑÑÑÑÑÑ', 2, 1, 21000.00, '1212-12-12', '3 años', '2025-12-18 19:37:08', '2025-12-19 01:29:41'),
(111, 'Samsung', 'LAPTOP', '909090', 'WINDOWS 22', 10, NULL, 1200.00, '1212-12-12', '90 MESES', '2025-12-18 22:17:19', '2025-12-18 22:17:19'),
(112, 'Samsung', 'LAPTOP', '909090', 'WINDOWS 22', 10, NULL, 1200.00, '1212-12-12', '90 MESES', '2025-12-18 22:17:47', '2025-12-18 22:17:47'),
(113, 'LOL', 'LAPTOP', '909090', 'WINDOWS 22', 10, 2, 1200.00, '1212-12-12', '90 MESES', '2025-12-18 22:18:32', '2025-12-18 23:03:06'),
(114, 'HP', 'PC GAMER', '909090', 'Windows 11', 18, NULL, 12000.00, '1212-12-12', '3 meses', '2025-12-18 22:38:33', '2025-12-18 22:38:33'),
(115, 'LapTop Gamer', 'PC GAMER', '909090', 'Windows 11', 21, NULL, 12000.00, '1212-12-12', 'kkk', '2025-12-18 22:39:42', '2025-12-18 23:43:14'),
(116, 'HP', 'PC GAMER', '909090', 'Windows 11', 16, NULL, 1200.00, '1212-12-12', '3 años', '2025-12-19 00:24:06', '2025-12-19 00:24:06'),
(117, 'ThinkpadTT', 'PC GAMER', '909090', 'Windows 11', 18, NULL, 12000.00, '1212-12-12', '7 años', '2025-12-19 00:47:56', '2025-12-19 00:47:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `equipos_ubicacion_id_foreign` (`ubicacion_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=118;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`),
  ADD CONSTRAINT `equipos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
