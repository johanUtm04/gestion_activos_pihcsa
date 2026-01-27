-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 05, 2026 at 04:12 PM
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
  `capacidad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''1TB'', ''512GB'', ''2TB''',
  `tipo_hdd_ssd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''SSD'', ''HDD''',
  `interface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''SATA'', ''PCIe''',
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
  `usuario_id` bigint UNSIGNED DEFAULT NULL COMMENT 'fk. va a ''users''',
  `ubicacion_id` bigint UNSIGNED DEFAULT NULL COMMENT 'fk. va a ''ubicaciones''',
  `valor_inicial` double(10,2) DEFAULT NULL,
  `fecha_adquisicion` date NOT NULL COMMENT 'fecha de compra',
  `vida_util_estimada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ej. ''3 Años'', ''5 Años''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historiales_log`
--

CREATE TABLE `historiales_log` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk',
  `activo_id` bigint UNSIGNED DEFAULT NULL COMMENT 'fk. viene de ''equipos''',
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
(16, '2025_12_17_165604_nullable_serial', 3),
(17, '2025_12_23_123725_add_deleted_at_to_equipos_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `monitores`
--

CREATE TABLE `monitores` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'pk.',
  `equipo_id` bigint UNSIGNED NOT NULL COMMENT 'fk. viene de ''equipos''',
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''samsung''',
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'no. de fabricante',
  `escala_pulgadas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''14''5''',
  `interface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''HDMI'', ''VGA''',
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
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''mouse''',
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''lenovo''',
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'no. de fabricante',
  `interface` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''HDMI'', ''VGA''',
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `frecuenciaMicro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `capacidad_gb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''16GB''',
  `clock_mhz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''3200'', ''2666',
  `tipo_chz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ej. ''DDR4'', ''DDR3''',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(82, 'Ingeniero Marcos', 'ingeniero@gmail.com', 'ADMIN', 'Area de Sistemas', '$2y$12$qr6OGVc/M9XFMI/JVL.JFuTYIgdVqivy4RTZoq6iT0.AKeP71dMXe', 'ACTIVO', NULL, NULL, '2026-01-05 16:07:24');

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
  ADD KEY `equipos_ubicacion_id_foreign` (`ubicacion_id`),
  ADD KEY `equipos_usuario_id_foreign` (`usuario_id`);

--
-- Indexes for table `historiales_log`
--
ALTER TABLE `historiales_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historiales_log_usuario_accion_id_foreign` (`usuario_accion_id`),
  ADD KEY `historiales_log_activo_id_foreign` (`activo_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `historiales_log`
--
ALTER TABLE `historiales_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk', AUTO_INCREMENT=598;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `monitores`
--
ALTER TABLE `monitores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `perifericos`
--
ALTER TABLE `perifericos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procesadores`
--
ALTER TABLE `procesadores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rams`
--
ALTER TABLE `rams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk.', AUTO_INCREMENT=83;

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
  ADD CONSTRAINT `equipos_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `equipos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `historiales_log`
--
ALTER TABLE `historiales_log`
  ADD CONSTRAINT `historiales_log_activo_id_foreign` FOREIGN KEY (`activo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
