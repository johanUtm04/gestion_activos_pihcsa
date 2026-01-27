-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2026 at 06:25 PM
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
  `id` bigint UNSIGNED NOT NULL,
  `equipo_id` bigint UNSIGNED NOT NULL,
  `capacidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_hdd_ssd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interface` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipos`
--

CREATE TABLE `equipos` (
  `id` bigint UNSIGNED NOT NULL,
  `marca_equipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ej. Lenovo, Dell',
  `tipo_equipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sistema_operativo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `ubicacion_id` bigint UNSIGNED NOT NULL,
  `valor_inicial` decimal(8,2) NOT NULL,
  `fecha_adquisicion` date NOT NULL,
  `vida_util_estimada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`id`, `marca_equipo`, `tipo_equipo`, `serial`, `sistema_operativo`, `usuario_id`, `ubicacion_id`, `valor_inicial`, `fecha_adquisicion`, `vida_util_estimada`, `created_at`, `updated_at`) VALUES
(1, 'Lenovo', 'PC Escritorio', '1234r vfe3ertytre', 'Windows 7', 1, 1, 14000.00, '2028-12-12', '4', '2026-01-13 06:59:40', '2026-01-13 06:59:49'),
(2, 'Huawei', 'Laptop', '4565432wdcvfd', 'Windows 10', 1, 1, 1212.00, '2027-12-12', '3 meses', '2026-01-13 07:03:51', '2026-01-13 07:53:44'),
(3, 'Supermicro', 'Laptop', '9865redfghiiy', 'Windows Server 2016', 1, 1, 0.01, '2026-01-13', '3', '2026-01-13 10:44:09', '2026-01-13 10:44:09'),
(4, 'ASUS', 'Smartphone', '87654edfgh', 'Windows 11', 1, 1, 0.01, '2026-01-13', '4', '2026-01-13 11:29:39', '2026-01-13 11:29:39'),
(5, 'Dell', 'PC Escritorio', '9876543eruytred', 'Windows 10', 1, 1, 1400.00, '2018-12-12', '3', '2026-01-13 12:39:17', '2026-01-13 12:39:17'),
(6, 'HP', 'PC Escritorio', '09876rfgh', 'Windows 11', 1, 1, 1300.00, '2028-12-12', '3', '2026-01-13 13:13:14', '2026-01-13 13:13:14'),
(7, 'Lenovo', 'All in One', '098765esr6gvhu8ijn', 'Windows 8.1', 1, 1, 13000.00, '2028-12-12', '4', '2026-01-13 13:15:05', '2026-01-13 13:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `his`
--

CREATE TABLE `his` (
  `id` bigint UNSIGNED NOT NULL,
  `activo_id` bigint UNSIGNED NOT NULL,
  `usuario_accion_id` bigint UNSIGNED NOT NULL,
  `tipo_registro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historiales_log`
--

CREATE TABLE `historiales_log` (
  `id` bigint UNSIGNED NOT NULL,
  `activo_id` bigint UNSIGNED NOT NULL,
  `usuario_accion_id` bigint UNSIGNED NOT NULL,
  `tipo_registro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `historiales_log`
--

INSERT INTO `historiales_log` (`id`, `activo_id`, `usuario_accion_id`, `tipo_registro`, `detalles_json`, `created_at`, `updated_at`) VALUES
(74, 1, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 1, \"serial\": \"1234r vfe3ertytre\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T06:59:40.000000Z\", \"updated_at\": \"2026-01-13T06:59:40.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"Lenovo\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"14000\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 7\", \"vida_util_estimada\": \"4\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 06:59:40', '2026-01-13 06:59:40'),
(75, 1, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 1, \"serial\": \"1234r vfe3ertytre\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T06:59:40.000000Z\", \"updated_at\": \"2026-01-13T06:59:40.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"Lenovo\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"14000\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 7\", \"vida_util_estimada\": \"4\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 06:59:40', '2026-01-13 06:59:40'),
(76, 1, 1, 'DELETE', '{\"rol\": \"ADMIN\", \"cambios\": {\"Registro Eliminado\": {\"antes\": \"Equipo:  | S/N: 1234r vfe3ertytre\", \"despues\": \"BORRADO POR USUARIO\"}}, \"mensaje\": \"ELIMINACIÓN DEFINITIVA: El activo ha sido removido.\", \"respaldo_total\": {\"id\": 1, \"serial\": \"1234r vfe3ertytre\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T06:59:40.000000Z\", \"deleted_at\": null, \"updated_at\": \"2026-01-13T06:59:40.000000Z\", \"usuario_id\": 1, \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"Lenovo\", \"ubicacion_id\": 1, \"valor_inicial\": \"14000.00\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 7\", \"vida_util_estimada\": \"4\"}, \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 06:59:49', '2026-01-13 06:59:49'),
(77, 1, 1, 'DELETE', '{\"rol\": \"ADMIN\", \"cambios\": {\"Registro Eliminado\": {\"antes\": \"Equipo:  | S/N: 1234r vfe3ertytre\", \"despues\": \"BORRADO POR USUARIO\"}}, \"mensaje\": \"ELIMINACIÓN DEFINITIVA: El activo ha sido removido.\", \"respaldo_total\": {\"id\": 1, \"serial\": \"1234r vfe3ertytre\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T06:59:40.000000Z\", \"deleted_at\": null, \"updated_at\": \"2026-01-13T06:59:40.000000Z\", \"usuario_id\": 1, \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"Lenovo\", \"ubicacion_id\": 1, \"valor_inicial\": \"14000.00\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 7\", \"vida_util_estimada\": \"4\"}, \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 06:59:49', '2026-01-13 06:59:49'),
(78, 2, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 2, \"serial\": \"4565432wdcvfd\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T07:03:51.000000Z\", \"updated_at\": \"2026-01-13T07:03:51.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"Laptop\", \"marca_equipo\": \"Huawei\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"1212\", \"fecha_adquisicion\": \"2027-12-12\", \"sistema_operativo\": \"Windows 10\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 07:03:51', '2026-01-13 07:03:51'),
(79, 2, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 2, \"serial\": \"4565432wdcvfd\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T07:03:51.000000Z\", \"updated_at\": \"2026-01-13T07:03:51.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"Laptop\", \"marca_equipo\": \"Huawei\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"1212\", \"fecha_adquisicion\": \"2027-12-12\", \"sistema_operativo\": \"Windows 10\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 07:03:51', '2026-01-13 07:03:51'),
(80, 2, 1, 'DELETE', '{\"rol\": \"ADMIN\", \"cambios\": {\"Registro Eliminado\": {\"antes\": \"Equipo:  | S/N: 4565432wdcvfd\", \"despues\": \"BORRADO POR USUARIO\"}}, \"mensaje\": \"ELIMINACIÓN DEFINITIVA: El activo ha sido removido.\", \"respaldo_total\": {\"id\": 2, \"serial\": \"4565432wdcvfd\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T07:03:51.000000Z\", \"deleted_at\": null, \"updated_at\": \"2026-01-13T07:03:51.000000Z\", \"usuario_id\": 1, \"tipo_equipo\": \"Laptop\", \"marca_equipo\": \"Huawei\", \"ubicacion_id\": 1, \"valor_inicial\": \"1212.00\", \"fecha_adquisicion\": \"2027-12-12\", \"sistema_operativo\": \"Windows 10\", \"vida_util_estimada\": \"3\"}, \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 07:03:57', '2026-01-13 07:03:57'),
(81, 2, 1, 'DELETE', '{\"rol\": \"ADMIN\", \"cambios\": {\"Registro Eliminado\": {\"antes\": \"Equipo:  | S/N: 4565432wdcvfd\", \"despues\": \"BORRADO POR USUARIO\"}}, \"mensaje\": \"ELIMINACIÓN DEFINITIVA: El activo ha sido removido.\", \"respaldo_total\": {\"id\": 2, \"serial\": \"4565432wdcvfd\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T07:03:51.000000Z\", \"deleted_at\": null, \"updated_at\": \"2026-01-13T07:03:51.000000Z\", \"usuario_id\": 1, \"tipo_equipo\": \"Laptop\", \"marca_equipo\": \"Huawei\", \"ubicacion_id\": 1, \"valor_inicial\": \"1212.00\", \"fecha_adquisicion\": \"2027-12-12\", \"sistema_operativo\": \"Windows 10\", \"vida_util_estimada\": \"3\"}, \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 07:03:57', '2026-01-13 07:03:57'),
(82, 2, 1, 'UPDATE', '{\"rol\": \"ADMIN\", \"cambios\": {\"vida_util_estimada\": {\"antes\": \"3\", \"despues\": \"3 años\"}}, \"mensaje\": \"Se modificaron campos del equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 07:49:48', '2026-01-13 07:49:48'),
(83, 2, 1, 'UPDATE', '{\"rol\": \"ADMIN\", \"cambios\": {\"vida_util_estimada\": {\"antes\": \"3 años\", \"despues\": \"3 meses\"}}, \"mensaje\": \"Se modificaron campos del equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 07:53:44', '2026-01-13 07:53:44'),
(84, 3, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 3, \"serial\": \"9865redfghiiy\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T10:44:09.000000Z\", \"updated_at\": \"2026-01-13T10:44:09.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"Laptop\", \"marca_equipo\": \"Supermicro\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"0.01\", \"fecha_adquisicion\": \"2026-01-13\", \"sistema_operativo\": \"Windows Server 2016\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 10:44:09', '2026-01-13 10:44:09'),
(85, 3, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 3, \"serial\": \"9865redfghiiy\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T10:44:09.000000Z\", \"updated_at\": \"2026-01-13T10:44:09.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"Laptop\", \"marca_equipo\": \"Supermicro\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"0.01\", \"fecha_adquisicion\": \"2026-01-13\", \"sistema_operativo\": \"Windows Server 2016\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 10:44:09', '2026-01-13 10:44:09'),
(86, 4, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 4, \"serial\": \"87654edfgh\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T11:29:39.000000Z\", \"updated_at\": \"2026-01-13T11:29:39.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"Smartphone\", \"marca_equipo\": \"ASUS\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"0.01\", \"fecha_adquisicion\": \"2026-01-13\", \"sistema_operativo\": \"Windows 11\", \"vida_util_estimada\": \"4\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 11:29:39', '2026-01-13 11:29:39'),
(87, 4, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 4, \"serial\": \"87654edfgh\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T11:29:39.000000Z\", \"updated_at\": \"2026-01-13T11:29:39.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"Smartphone\", \"marca_equipo\": \"ASUS\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"0.01\", \"fecha_adquisicion\": \"2026-01-13\", \"sistema_operativo\": \"Windows 11\", \"vida_util_estimada\": \"4\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 11:29:39', '2026-01-13 11:29:39'),
(88, 5, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 5, \"serial\": \"9876543eruytred\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T12:39:17.000000Z\", \"updated_at\": \"2026-01-13T12:39:17.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"Dell\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"1400\", \"fecha_adquisicion\": \"2018-12-12\", \"sistema_operativo\": \"Windows 10\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 12:39:17', '2026-01-13 12:39:17'),
(89, 5, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 5, \"serial\": \"9876543eruytred\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T12:39:17.000000Z\", \"updated_at\": \"2026-01-13T12:39:17.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"Dell\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"1400\", \"fecha_adquisicion\": \"2018-12-12\", \"sistema_operativo\": \"Windows 10\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 12:39:17', '2026-01-13 12:39:17'),
(90, 6, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 6, \"serial\": \"09876rfgh\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T13:13:14.000000Z\", \"updated_at\": \"2026-01-13T13:13:14.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"HP\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"1300\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 11\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 13:13:14', '2026-01-13 13:13:14'),
(91, 6, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 6, \"serial\": \"09876rfgh\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T13:13:14.000000Z\", \"updated_at\": \"2026-01-13T13:13:14.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"PC Escritorio\", \"marca_equipo\": \"HP\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"1300\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 11\", \"vida_util_estimada\": \"3\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 13:13:14', '2026-01-13 13:13:14'),
(92, 7, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 7, \"serial\": \"098765esr6gvhu8ijn\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T13:15:05.000000Z\", \"updated_at\": \"2026-01-13T13:15:05.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"All in One\", \"marca_equipo\": \"Lenovo\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"13000\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 8.1\", \"vida_util_estimada\": \"4\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 13:15:05', '2026-01-13 13:15:05'),
(93, 7, 1, 'CREATE', '{\"rol\": \"ADMIN\", \"datos\": {\"id\": 7, \"serial\": \"098765esr6gvhu8ijn\", \"usuario\": {\"id\": 1, \"rol\": \"ADMIN\", \"name\": \"Ingeniero Marcos \", \"email\": \"ingeniero@gmail.com\", \"estatus\": \"ACTIVO\", \"created_at\": null, \"updated_at\": null, \"departamento\": \"SISTEMAS\"}, \"created_at\": \"2026-01-13T13:15:05.000000Z\", \"updated_at\": \"2026-01-13T13:15:05.000000Z\", \"usuario_id\": \"1\", \"tipo_equipo\": \"All in One\", \"marca_equipo\": \"Lenovo\", \"ubicacion_id\": \"1\", \"valor_inicial\": \"13000\", \"fecha_adquisicion\": \"2028-12-12\", \"sistema_operativo\": \"Windows 8.1\", \"vida_util_estimada\": \"4\"}, \"mensaje\": \"Creacion de Equipo\", \"usuario_asignado\": \"Ingeniero Marcos \"}', '2026-01-13 13:15:05', '2026-01-13 13:15:05');

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
(15, '2026_01_09_110259_add_soft_deletes_to_equipos_table', 2),
(17, '2026_01_09_110931_change_sistema_operativo_size_in_equipos_table', 3),
(18, '2026_01_12_180745_create_imagens_table', 4),
(19, '2026_01_13_014101_remove_deleted_at_from_equipos_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `monitores`
--

CREATE TABLE `monitores` (
  `id` bigint UNSIGNED NOT NULL,
  `equipo_id` bigint UNSIGNED NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `escala_pulgadas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interface` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perifericos`
--

CREATE TABLE `perifericos` (
  `id` bigint UNSIGNED NOT NULL,
  `equipo_id` bigint UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interface` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
  `equipo_id` bigint UNSIGNED NOT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
  `id` bigint UNSIGNED NOT NULL,
  `equipo_id` bigint UNSIGNED NOT NULL,
  `capacidad_gb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clock_mhz` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_chz` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `nombre`, `codigo`, `created_at`, `updated_at`) VALUES
(1, 'Morelia', '58304', '2026-01-09 17:08:28', '2026-01-09 17:08:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SISTEMAS' COMMENT 'ADMIN, SISTEMAS, CONTABILIDAD.',
  `departamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estatus` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVO',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `rol`, `departamento`, `password`, `estatus`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ingeniero Marcos ', 'ingeniero@gmail.com', 'ADMIN', 'SISTEMAS', '$2y$12$Tjg.fsm/tgVZw3FtVJKEX.W0DUfebDYRFdmhO0O7tZyCXLiKGHFgq', 'ACTIVO', NULL, NULL, NULL);

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
-- Indexes for table `his`
--
ALTER TABLE `his`
  ADD PRIMARY KEY (`id`),
  ADD KEY `his_activo_id_foreign` (`activo_id`),
  ADD KEY `his_usuario_accion_id_foreign` (`usuario_accion_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `his`
--
ALTER TABLE `his`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historiales_log`
--
ALTER TABLE `historiales_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `monitores`
--
ALTER TABLE `monitores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `perifericos`
--
ALTER TABLE `perifericos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procesadores`
--
ALTER TABLE `procesadores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rams`
--
ALTER TABLE `rams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discos_duros`
--
ALTER TABLE `discos_duros`
  ADD CONSTRAINT `discos_duros_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `equipos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `his`
--
ALTER TABLE `his`
  ADD CONSTRAINT `his_activo_id_foreign` FOREIGN KEY (`activo_id`) REFERENCES `equipos` (`id`),
  ADD CONSTRAINT `his_usuario_accion_id_foreign` FOREIGN KEY (`usuario_accion_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `historiales_log`
--
ALTER TABLE `historiales_log`
  ADD CONSTRAINT `historiales_log_activo_id_foreign` FOREIGN KEY (`activo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `historiales_log_usuario_accion_id_foreign` FOREIGN KEY (`usuario_accion_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `monitores`
--
ALTER TABLE `monitores`
  ADD CONSTRAINT `monitores_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `rams`
--
ALTER TABLE `rams`
  ADD CONSTRAINT `rams_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
