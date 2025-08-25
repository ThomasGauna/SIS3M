-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3308
-- Tiempo de generación: 16-04-2025 a las 20:45:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `todgninf_product`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_stock`
--

CREATE TABLE `movimientos_stock` (
  `id` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `tipoMovimiento` varchar(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechaMovimiento` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_stock`
--

INSERT INTO `movimientos_stock` (`id`, `idProducto`, `tipoMovimiento`, `cantidad`, `fechaMovimiento`) VALUES
(1, 1, 'Ingreso', 1, '2025-04-14 15:53:04'),
(2, 1, 'Ingreso', 1, '2025-04-14 15:53:04'),
(3, 1, 'Ingreso', 1, '2025-04-14 15:53:04'),
(4, 1, 'Egreso', 1, '2025-04-14 15:53:54'),
(5, 1, 'Egreso', 1, '2025-04-14 15:53:54'),
(6, 1, 'Egreso', 1, '2025-04-14 15:53:54'),
(7, 1, 'Ingreso', 1, '2025-04-14 15:53:56'),
(8, 1, 'Egreso', 1, '2025-04-14 16:21:49'),
(9, 1, 'Egreso', 1, '2025-04-14 16:21:49'),
(10, 2, 'Ingreso', 1, '2025-04-14 16:22:08'),
(11, 2, 'Ingreso', 1, '2025-04-14 16:22:08'),
(12, 2, 'Ingreso', 1, '2025-04-14 16:22:08'),
(13, 2, 'Egreso', 1, '2025-04-14 16:22:09'),
(14, 2, 'Egreso', 1, '2025-04-14 16:22:09'),
(15, 2, 'Egreso', 1, '2025-04-14 16:22:09'),
(16, 1, 'Ingreso', 1, '2025-04-14 16:22:11'),
(17, 1, 'Ingreso', 1, '2025-04-14 16:22:12'),
(18, 1, 'Ingreso', 1, '2025-04-14 16:22:12'),
(19, 1, 'Egreso', 1, '2025-04-14 16:22:13'),
(20, 7, 'Ingreso', 1, '2025-04-15 13:23:27'),
(21, 7, 'Ingreso', 1, '2025-04-15 13:23:27'),
(22, 7, 'Ingreso', 1, '2025-04-15 13:23:27'),
(23, 7, 'Egreso', 1, '2025-04-15 13:23:40'),
(24, 7, 'Egreso', 1, '2025-04-15 13:23:41'),
(25, 7, 'Egreso', 1, '2025-04-15 13:23:41'),
(26, 1, 'Egreso', 3, '2025-04-16 11:54:02'),
(27, 1, 'Ingreso', 3, '2025-04-16 11:54:46'),
(28, 3, 'Egreso', 4, '2025-04-16 11:55:46'),
(29, 3, 'Ingreso', 4, '2025-04-16 11:56:15'),
(30, 1, 'Ingreso', 1, '2025-04-16 12:22:48'),
(31, 1, 'Ingreso', 1, '2025-04-16 12:25:59'),
(32, 1, 'Egreso', 1, '2025-04-16 12:26:04'),
(33, 1, 'Egreso', 1, '2025-04-16 12:26:06'),
(34, 1, 'Ingreso', 1, '2025-04-16 12:46:30'),
(35, 1, 'Egreso', 1, '2025-04-16 12:46:32'),
(36, 1, 'Ingreso', 1, '2025-04-16 12:50:49'),
(37, 1, 'Ingreso', 1, '2025-04-16 12:53:10'),
(38, 1, 'Egreso', 1, '2025-04-16 13:00:56'),
(39, 1, 'Egreso', 1, '2025-04-16 13:00:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombreProducto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipoProducto` enum('Importados','Nacionales') DEFAULT NULL,
  `categoria` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `ubicacion` varchar(50) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `codigo`, `nombreProducto`, `tipoProducto`, `categoria`, `stock`, `ubicacion`, `imagen`) VALUES
(1, '0E9371AS', 'Filtro de aire: 5,6 / 8 kva', 'Importados', 'Stock Service', 8, 'Sin datos', 'images/productos/FILTER-AIR-HSB-MY-20.png'),
(2, '0J8478S', 'Filtro de aire: 13 / 17 kva', 'Importados', 'Stock Service', 65, 'Sin datos', 'images/productos/FILTER-AIR-ELEMENT-PRE-BOX.png'),
(3, 'G059402', 'Filtro de aire: 27 kva', 'Importados', 'Stock Service', 4, 'Sin datos', 'images/productos/AIR-CLEANER-1.png'),
(4, '0F1922', 'Filtro de aire: 35 kva', 'Importados', 'Stock Service', 1, 'Sin datos', 'images/productos/AIR-CLEANER-3IN-X-6.75OD.png'),
(5, 'A0000080542', 'Filtro de aire: 50 kva', 'Importados', 'Stock Service', 1, 'Sin datos', 'images/productos/AIR-CLEANER-95-X-152OD-1.png'),
(6, '070185ES', 'Filtro de aceite: 8 / 13 / 17 kva', 'Importados', 'Stock Service', 58, 'Sin datos', 'images/productos/OIL-FILTER-75-LOGO-ORNG-CAN.png'),
(7, '0A45310244', 'Filtro de aceite: 27 kva', 'Importados', 'Stock Service', 4, 'Sin datos', 'images/productos/FILTER-1.5L2.4L-G2-OIL-1.png'),
(8, '0D5419', 'Filtro de aceite: 35 kva', 'Importados', 'Stock Service', 1, 'Sin datos', 'images/productos/OIL-FILTER.png'),
(9, '10000013438', 'Filtro de aceite: 50 kva', 'Importados', 'Stock Service', 2, 'Sin datos', 'images/productos/sin-foto.png'),
(10, 'BKR6EN11', 'Bujia: 5.6 kva', 'Importados', 'Stock Service', 3, 'Sin datos', 'images/productos/SPARK-PLUG-RC9YC-GAP-0.030-56-kva.png'),
(11, 'BPR6HS', 'Bujia: 8 kva', 'Importados', 'Stock Service', 16, 'Sin datos', 'images/productos/SPARK-PLUG-2.png'),
(12, 'BKR5E11', 'Bujia: 13 / 17 kva', 'Importados', 'Stock Service', 97, 'Sin datos', 'images/productos/SPARK-PLUG-GAP-0.040-–-BUJIAS-DE-13-Y-17-KVA.png'),
(13, 'BCPR6ES', 'Bujia: 27 kva', 'Importados', 'Stock Service', 8, 'Sin datos', 'images/productos/Spark-plug.png'),
(14, '0D34540186', 'Bujia: 35 / 50 kva', 'Importados', 'Stock Service', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(15, '0J5141', 'Aceite : 5,6 / 8 / 13 / 17 / 27 / 35 / 50 kva', 'Importados', 'Stock Service', 23, 'Sin datos', 'images/productos/OIL-SAE-5W-30-x-946ml-1.png'),
(16, 'JFA0134', 'Filtro de aire: 13 / 17 kva', 'Nacionales', 'Stock Service', 158, 'Sin datos', 'images/productos/FILTER-AIR-ELEMENT-PRE-BOX.png'),
(17, 'W610/3', 'Filtro de aceite: 8 / 13 / 17 kva', 'Nacionales', 'Stock Service', 123, 'Sin datos', 'images/productos/OIL-FILTER-75-LOGO-ORNG-CAN.png'),
(18, '00001', 'Unión de 10 (UCC 10)', '', 'Stock General', 3, 'Sin datos', 'images/productos/sin-foto.png'),
(19, '00002', 'Unión de 16 (UCC 16)', '', 'Stock General', 5, 'Sin datos', 'images/productos/sin-foto.png'),
(20, '00003', 'Unión de 25 (UCC 25)', '', 'Stock General', 2, 'Sin datos', 'images/productos/sin-foto.png'),
(21, '00004', 'Unión de 35 (UCC 35)', '', 'Stock General', 2, 'Sin datos', 'images/productos/sin-foto.png'),
(22, '00005', 'Unión de 50 (UCC 50)', '', 'Stock General', 5, 'Sin datos', 'images/productos/sin-foto.png'),
(23, '00006', 'Terminal de 10 (SCC 10/3)', '', 'Stock General', 2, 'Sin datos', 'images/productos/sin-foto.png'),
(24, '00007', 'Terminal de 16 (SCC 16/3)', '', 'Stock General', 4, 'Sin datos', 'images/productos/sin-foto.png'),
(25, '00008', 'Terminal de 25 (SCC 25/3)', '', 'Stock General', 2, 'Sin datos', 'images/productos/sin-foto.png'),
(26, '00009', 'Terminal de 35 (SCC 35/3)', '', 'Stock General', 5, 'Sin datos', 'images/productos/sin-foto.png'),
(27, '00010', 'Unión roja (A10)', '', 'Stock General', 10, 'Sin datos', 'images/productos/sin-foto.png'),
(28, '00011', 'Pala hembra 6,3 (A18)', '', 'Stock General', 8, 'Sin datos', 'images/productos/sin-foto.png'),
(29, '00012', 'Pin plano 11.2m (A22)', '', 'Stock General', 5, 'Sin datos', 'images/productos/sin-foto.png'),
(30, '00013', 'Ojaldres 8mm (A6)', '', 'Stock General', 5, 'Sin datos', 'images/productos/sin-foto.png'),
(31, '00014', 'Filtro de partículas', '', 'Stock General', 37, 'Sin datos', 'images/productos/sin-foto.png'),
(32, '00015', 'Llaves de paso', '', 'Stock General', 35, 'Sin datos', 'images/productos/sin-foto.png'),
(33, '00016', 'Manometros', '', 'Stock General', 6, 'Sin datos', 'images/productos/sin-foto.png'),
(34, '00017', '8 / 13 / 17 kva', '', 'Stock General', 25, 'Sin datos', 'images/productos/sin-foto.png'),
(35, '00018', '27 kva', '', 'Stock General', 5, 'Sin datos', 'images/productos/sin-foto.png'),
(36, '00019', '50 / 65 / 80 kva', '', 'Stock General', 1, 'Sin datos', 'images/productos/sin-foto.png'),
(37, '00020', 'Coolers', '', 'Stock General', 7, 'Sin datos', 'images/productos/sin-foto.png'),
(38, '00021', 'Térmicas', '', 'Stock General', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(39, '00022', 'Luces', '', 'Stock General', 31, 'Sin datos', 'images/productos/sin-foto.png'),
(40, '0e0502', 'TEMPERATURE SENDER DELPHI', '', 'Stock Repuesto', 2, 'C03', 'images/productos/TEMPERATURE-SENDER-DELPHI.png'),
(41, '0E3812', 'SEAL D 35 X 48.2 VITON', '', 'Stock Repuesto', 1, 'C13', 'images/productos/SEAL-D-35-X-48.2-VITON-2.png'),
(42, '0E9352', 'GASKET,VALVE COVER,GT530', '', 'Stock Repuesto', 4, 'C17', 'images/productos/GASKETVALVE-COVERGT530.png'),
(43, '00E9368', 'SPARK PLUG (Bujia) 8KVA', '', 'Stock Repuesto', 2, 'C10', 'images/productos/SPARK-PLUG-2.png'),
(44, '0E6585 ', 'COVER IAC ACTUATOR', '', 'Stock Repuesto', 2, 'C17', 'images/productos/COVER-IAC-ACTUATOR.png'),
(45, '0E6154 ', 'COIL-HSB ATS UTILITY', '', 'Stock Repuesto', 2, 'E03', 'images/productos/COIL-HSB-ATS-UTILITY.png'),
(46, '0e9406', 'OIL COOLER, GT530', '', 'Stock Repuesto', 1, 'E01', 'images/productos/OIL-COOLER-GT530.png'),
(47, '0E9370', 'GASKET,OIL FILTER ADAPTR,GT530', '', 'Stock Repuesto', 1, 'C16', 'images/productos/GASKETOIL-FILTER-ADAPTRGT530.png'),
(48, '0E9351', 'GASKET CYLINDER HEAD GT530', '', 'Stock Repuesto', 2, 'C17', 'images/productos/GASKET-CYLINDER-HEAD-GT530-1.png'),
(49, '0E9471', 'GASKET, MANIFOLD TO CARB/MIXER', '', 'Stock Repuesto', 1, 'C18', 'images/productos/GASKET-MANIFOLD-TO-CARBMIXER.png'),
(50, '0E9472', 'GASKET, AIRBOX TO CARB/MIXER', '', 'Stock Repuesto', 1, 'C18', 'images/productos/GASKET-AIRBOX-TO-CARBMIXER-1.png'),
(51, '0E4395', 'ACTUATOR BOSCH 32 GOVERNOR', '', 'Stock Repuesto', 3, 'E03', 'images/productos/ACTUATOR-BOSCH-32-GOVERNOR.png'),
(52, '10000003275', 'ASSY CONTROLLER 2020 AC HSB (compu verde)', '', 'Stock Repuesto', 1, 'Archivo', 'images/productos/ASSY-CONTROLLER-2020-AC-HSB.png'),
(53, '10000003293', 'ASSY CONTROLLER2016 AC HSB', '', 'Stock Repuesto', 0, 'E01', 'images/productos/ASSY-CONTROLLER2016-AC-HSB-1.png'),
(54, '10000006433', 'ASSY DPE FILTER (Filtro de tension negro)', '', 'Stock Repuesto', 0, 'E03', 'images/productos/ASSY-DPE-FILTER.png'),
(55, '10000011471', 'Assy head CyL GT-999', '', 'Stock Repuesto', 1, 'C20', 'images/productos/Assy-head-CyL-GT-999.png'),
(56, '10000011472', 'Assy head CyL', '', 'Stock Repuesto', 1, 'C20', 'images/productos/Assy-head-CyL-1.png'),
(57, '10000021917', 'FUEL FILTER 2,5KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(58, '10000021950', 'FUEL FILTER 2,5KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(59, '10000023993', 'CHOKE BRACKET 3,3KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(60, '10000024004', 'CARBURETOR GASKET 3,3KVA', '', 'Stock Repuesto', 1, 'C25', 'images/productos/sin-foto.png'),
(61, '10000024020', 'CARBURETOR GASKET  3,3KVA', '', 'Stock Repuesto', 1, 'C25', 'images/productos/sin-foto.png'),
(62, '10000024648', 'CARBURETOR 2,5KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(63, '10000024656', 'CARBURETOR GASKET 2,5KVA', '', 'Stock Repuesto', 1, 'C25', 'images/productos/sin-foto.png'),
(64, '10000024664', 'AIR CLEANER ASSEMBLY 2,5KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(65, '10000034295', 'AIR FILTER ELEMENT 3,3KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(66, '10000038207', 'AIR CLEANER ASSEMBLY  3,3KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(67, '10000038292', 'CARBURETOR 3,3KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(68, '10000038353', 'FUEL STRAINER (IN TANK) 3,3KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(69, '10000045401', 'ASSY DPE FILTER (Filtro de tension plateado)', '', 'Stock Repuesto', 2, 'E03', 'images/productos/sin-foto.png'),
(70, '070185B', 'OIL FILTER 75 LOGO ORNG-CAN', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/OIL-FILTER-75-LOGO-ORNG-CAN.png'),
(71, '070185ES', 'OIL FLTR 90LOGO ORNG PRE-BOX / filtro de aceite (8 - 13 -17 kva)', '', 'Stock Repuesto', 10, 'Archivo', 'images/productos/OIL-FLTR-90LOGO-ORNG-PRE-BOX.png'),
(72, '070936C', 'VIBRATION ISOLATOR 70-75 DURO', '', 'Stock Repuesto', 3, 'C15', 'images/productos/VIBRATION-ISOLATOR-70-75-DURO.png'),
(73, '073111s', 'ELEMENT,AIR FILTER BOX&BARCODE', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/ELEMENTAIR-FILTER-BOXBARCODE.png'),
(74, '073590A', 'FUSE 5A X BUSS', '', 'Stock Repuesto', 2, 'C29', 'images/productos/FUSE-5A-X-BUSS.png'),
(75, '077220A', 'COIL-HSB ATS STANDBYS STANDBY', '', 'Stock Repuesto', 3, 'E0', 'images/productos/COIL-HSB-ATS-STANDBYS-STANDBY.png'),
(76, '078699B', 'SLEEVE DOWEL PIN 12 DIA.', '', 'Stock Repuesto', 6, 'C20', 'images/productos/SLEEVE-DOWEL-PIN-12-DIA.png'),
(77, '0A45310244', 'FILTER 1.5L/2.4L G2 OIL / filtro de aceite (27kva)', '', 'Stock Repuesto', 4, 'Archivo', 'images/productos/FILTER-1.5L2.4L-G2-OIL-1.png'),
(78, '0A6751', 'SWITCH HI-TEMP 245D X 3/8NPT', '', 'Stock Repuesto', 2, 'C03', 'images/productos/SWITCH-HI-TEMP-245D-X-38NPT.png'),
(79, '0A8584', 'SW OIL PRESS 10PSI 1/8-27 NC', '', 'Stock Repuesto', 1, 'C03', 'images/productos/SW-OIL-PRESS-10PSI-18-27-NC.png'),
(80, '0C2150E', 'SOL COIL-W TYPE 29.7', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/SOL-COIL-W-TYPE-29.7.png'),
(81, '0C2174', 'Relay 12v 25A SPST', '', 'Stock Repuesto', 2, 'C04', 'images/productos/Relay-12v-25A-SPST.png'),
(82, '0c2977', 'GASKET CCASE GV990', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/GASKET-CCASE-GV990.png'),
(83, '0C30250SRV', 'KIT-OIL PRESSURE 10PSI 1/4-18 NC', '', 'Stock Repuesto', 1, 'C03', 'images/productos/sin-foto.png'),
(84, '0C3725BSRV', 'FLYWHEEL ASSY GT-990 36 DEG', '', 'Stock Repuesto', 1, 'E01', 'images/productos/sin-foto.png'),
(85, '0C4138', 'GASKET EXHAUST PORT', '', 'Stock Repuesto', 2, 'C16', 'images/productos/sin-foto.png'),
(86, '0C4647', 'GASKET SOLENOID', '', 'Stock Repuesto', 4, 'C01', 'images/productos/GASKET-SOLENOID.png'),
(87, '0C5371', 'WASHER VALVE SPRING', '', 'Stock Repuesto', 0, 'C14', 'images/productos/WASHER-VALVE-SPRING.png'),
(88, '0C5943', 'SEAL, OIL PASSAGE GT', '', 'Stock Repuesto', 0, 'C20', 'images/productos/SEAL-OIL-PASSAGE-GT.png'),
(89, '0C8127', 'FILTER AIR ELEMENT PRE-BOX / filtro de aire viejo (13kva)', '', 'Stock Repuesto', 1, 'Archivo', 'images/productos/FILTER-AIR-ELEMENT-PRE-BOX-2.png'),
(90, '0D2244M', 'ASSY MAGPICKUP(3/8-24 MALE)', '', 'Stock Repuesto', 4, 'C24', 'images/productos/ASSY-MAGPICKUP38-24-MALE.png'),
(91, '0D3488S', 'BELT SERPENTINE 37.0', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/BELT-SERPENTINE-37.0-1.png'),
(92, '0D4516', 'SWITCH LS1 LS2 5A 250V', '', 'Stock Repuesto', 2, 'C06', 'images/productos/SWITCH-LS1-LS2-5A-250V.png'),
(93, '0D5419', 'OIL FILTER', '', 'Stock Repuesto', -1, 'Archivo', 'images/productos/OIL-FILTER.png'),
(94, '0D6313', 'FILTER, FUEL, GT990', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/FILTER-FUEL-GT990.png'),
(95, '0D7177V', 'DIODE BRIDGE 1P 35A 1000V', '', 'Stock Repuesto', 2, 'C04', 'images/productos/DIODE-BRIDGE-1P-35A-1000V.png'),
(96, '0D7178T', 'FUSE ATO TYPE 7.5AMP (BROWN)', '', 'Stock Repuesto', 4, 'C05', 'images/productos/FUSE-ATO-TYPE-7.5AMP-BROWN.png'),
(97, '0d9853d', 'PUSHROD 147 LENGTH GT-760/990', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/PUSHROD-147-LENGTH-GT-760990.png'),
(98, '0E6154A', 'COIL-HSB ATS STANDBY', '', 'Stock Repuesto', 5, 'E03', 'images/productos/COIL-HSB-ATS-STANDBY.png'),
(99, '0E7585A', 'SPARK PLUG GAP: 0.040 - BUJIAS DE 13 Y 17 KVA', '', 'Stock Repuesto', 34, 'C11', 'images/productos/SPARK-PLUG-GAP-0.040-–-BUJIAS-DE-13-Y-17-KVA.png'),
(100, '0E9371AS', 'FILTER AIR HSB MY 20 filtro de aire (8kva)', '', 'Stock Repuesto', 2, 'Archivo', 'images/productos/FILTER-AIR-HSB-MY-20.png'),
(101, '0E9837C', 'RADIATOR 2.4L G2', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/RADIATOR-2.4L-G2.png'),
(102, '0E9868A', 'ALTERNATOR DC W/OUT PULLEY', '', 'Stock Repuesto', 1, 'E03', 'images/productos/ALTERNATOR-DC-WOUT-PULLEY.png'),
(103, '0F1922', 'AIR CLEANER 3IN X 6.75OD', '', 'Stock Repuesto', 1, 'E01', 'images/productos/AIR-CLEANER-3IN-X-6.75OD.png'),
(104, '0f3725k', 'FUSE ATO 25A CLEAR', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/FUSE-ATO-25A-CLEAR.png'),
(105, '0F5048D', 'VISE-ACTION LATCH SLOTTED CIR', '', 'Stock Repuesto', 2, 'C07', 'images/productos/VISE-ACTION-LATCH-SLOTTED-CIR.png'),
(106, '0F5752F', 'RES WW 15R 5% 25W QK CONN', '', 'Stock Repuesto', 2, 'C06', 'images/productos/RES-WW-15R-5-25W-QK-CONN.png'),
(107, '0F8869D', 'KEY VISE-ACTION LATCH SLOT CIR', '', 'Stock Repuesto', 1, 'C22', 'images/productos/KEY-VISE-ACTION-LATCH-SLOT-CIR.png'),
(108, '0G0860', 'SPACER STARTER', '', 'Stock Repuesto', 2, 'C18', 'images/productos/SPACER-STARTER.png'),
(109, '0G10080102', 'GASKET, ROCKER', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/GASKET-ROCKER.png'),
(110, '0G10080112', 'OIL SEAL', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/OIL-SEAL.png'),
(111, '0G10080188', 'BELT TIMING', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/BELT-TIMING.png'),
(112, '0G10080232', 'BELT, TIMING B', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/sin-foto.png'),
(113, '0G1397CSRV', 'FUEL REG RK 2.4 1800 22/27 kva', '', 'Stock Repuesto', 2, 'E02', 'images/productos/FUEL-REG-RK-2.4-1800-2227-kva.png'),
(114, '0G1472A', 'CAM SENSOR PIN ASSY', '', 'Stock Repuesto', 2, 'C06', 'images/productos/CAM-SENSOR-PIN-ASSY.png'),
(115, '0G3224TA', 'ASSY IGN COIL NO ADV 760/990 (bobina 8 / 13 / 17kva)', '', 'Stock Repuesto', 1, 'Sin datos', 'images/productos/ASSY-IGN-COIL-NO-ADV-760990-1.png'),
(116, '0G3224TB', 'ASSY IGN COIL NO ADV 760/990 (bobina 8 / 13 / 17kva)', '', 'Stock Repuesto', 1, 'Sin datos', 'images/productos/ASSY-IGN-COIL-NO-ADV-760990-3.png'),
(117, '0G3251TA', 'ASSY IGN COIL W/DIO GTH530CYL1', '', 'Stock Repuesto', 1, 'C09', 'images/productos/ASSY-IGN-COIL-WDIO-GTH530CYL1.png'),
(118, '0G3251TB', 'ASSY IGN COIL W/DIO GTH530CYL2', '', 'Stock Repuesto', 1, 'C09', 'images/productos/sin-foto.png'),
(119, '0G41800SRV', 'HARN MAG PICKUP TEST KIT', '', 'Stock Repuesto', 2, 'C23', 'images/productos/HARN-MAG-PICKUP-TEST-KIT.png'),
(120, '0G53550SRV', 'HSB MASTER KEY SET', '', 'Stock Repuesto', 3, 'C22', 'images/productos/HSB-MASTER-KEY-SET.png'),
(121, '0G6820', 'SW OIL PRESS 10PSI 1/4-18 NC', '', 'Stock Repuesto', 0, 'C03', 'images/productos/SW-OIL-PRESS-10PSI-14-18-NC-1.png'),
(122, '0G7461RWK', 'STARTER MOTOR 12V RWK (burro de arranque 27kva)', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/STARTER-MOTOR-12V-RWK.png'),
(123, '0G84420150', 'COIL ASSY, IGNITION', '', 'Stock Repuesto', 1, 'C09', 'images/productos/COIL-ASSY-IGNITION.png'),
(124, '0G8853', 'COIL 2.4L G2 IGNITION', '', 'Stock Repuesto', 3, 'C08', 'images/productos/COIL-2.4L-G2-IGNITION.png'),
(125, '0H1827', 'PROBE COOLANT LEVEL 3/8-18NPTF', '', 'Stock Repuesto', 2, 'C05', 'images/productos/PROBE-COOLANT-LEVEL-38-18NPTF.png'),
(126, '0H7668DSRV', 'ASSY CTRL 2010 CPL PROGRAMMED', '', 'Stock Repuesto', 1, 'E02', 'images/productos/ASSY-CTRL-2010-CPL-PROGRAMMED.png'),
(127, '0H7957', 'Spark plug 27KVA', '', 'Stock Repuesto', 4, 'C12', 'images/productos/Spark-plug.png'),
(128, '0H8074', 'SPARK PLUG 2,5KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/sin-foto.png'),
(129, '0H8572', 'PULLEY DC ALTERNATOR', '', 'Stock Repuesto', 1, 'C08', 'images/productos/PULLEY-DC-ALTERNATOR.png'),
(130, '0J00620106', 'SPARK PLUG (F7TC) 3,3KVA', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/SPARK-PLUG-F7TC-3-3KVA.png'),
(131, '0J35220127', 'CARBURETOR GASKET 2,5 Y 3,3KVA', '', 'Stock Repuesto', 2, 'C25', 'images/productos/CARBURETOR-GASKET-2-5-Y-3-3KVA.png'),
(132, '0J39340113', 'GASKET, HEAD COVER (juntas de valvula powerpack)', '', 'Stock Repuesto', 3, 'C19', 'images/productos/GASKET-HEAD-COVER-2.png'),
(133, '0J5139', 'OIL SAE 10W-30 x 946ml', '', 'Stock Repuesto', -1, 'Archivo', 'images/productos/OIL-SAE-10W-30-x-946ml.png'),
(134, '0J5141', 'OIL SAE 5W-30 x 946ml', '', 'Stock Repuesto', 50, 'Archivo', 'images/productos/OIL SAE 5W-30 x 946ml.png'),
(135, '0J7137', 'SOLENOID SHUTOFF (selenoide 8 - 13 - 17 kva)', '', 'Stock Repuesto', 4, 'C06', 'images/productos/SOLENOID-SHUTOFF-1.png'),
(136, '0J8315A', 'ASSY SOLEN PLUNGER & SPRING', '', 'Stock Repuesto', 4, 'C01', 'images/productos/ASSY-SOLEN-PLUNGER-SPRING.png'),
(137, '0J8371C', 'ASSY CONTROLER AC HSB (compu blanca)', '', 'Stock Repuesto', 3, 'Archivo', 'images/productos/ASSY-CTRLR-AC-HSB.png'),
(138, '0J8415', '2 POSITION BRUSH HOLDER ASY', '', 'Stock Repuesto', 6, 'C02', 'images/productos/2-POSITION-BRUSH-HOLDER-ASY.png'),
(139, '0J8478S', 'FILTER AIR ELEMENT PRE-BOX (13 y 17kva)', '', 'Stock Repuesto', 42, 'Archivo', 'images/productos/FILTER-AIR-ELEMENT-PRE-BOX.png'),
(140, '0j9679', 'PUSH ROD GUIDE PLATE', '', 'Stock Repuesto', 2, 'C01', 'images/productos/PUSH-ROD-GUIDE-PLATE.png'),
(141, '0K0258P', 'RESISTOR ASSY 13 kva', '', 'Stock Repuesto', 0, 'C05', 'images/productos/sin-foto.png'),
(142, '0K1534', 'SEAL CSHFT FLYWL SIDE RED', '', 'Stock Repuesto', 6, 'C13', 'images/productos/SEAL-CSHFT-FLYWL-SIDE-RED.png'),
(143, '0K2035', 'SEAL CSHFT PTO 760/990 RED', '', 'Stock Repuesto', 5, 'C14', 'images/productos/SEAL-CSHFT-PTO-760990-RED.png'),
(144, '0K2267C', 'ASSY CONTROLLER PROTECTOR CPL', '', 'Stock Repuesto', 0, 'Sin datos', 'images/productos/ASSY-CONTROLLER-PROTECTOR-CPL.png'),
(145, '0K3754', 'ASSY MIXER 13KVA HSB 2013', '', 'Stock Repuesto', 0, 'E02', 'images/productos/ASSY-MIXER-13KVA-HSB-2013-2.png'),
(146, '0K3964', 'ASSY PRESSURE STABILIZER / galleta de gas 13kva', '', 'Stock Repuesto', 0, 'E01', 'images/productos/ASSY-PRESSURE-STABILIZER-1.png'),
(147, '0K43590117', 'STARTER/CONTACTOR ASSY.', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/sin-foto.png'),
(148, '0K4759B', 'ASSY CONTROLLER POWER PACT', '', 'Stock Repuesto', 1, 'E01', 'images/productos/ASSY-CONTROLLER-POWER-PACT-1.png'),
(149, '0K63030SRV', 'MAGNETO KIT', '', 'Stock Repuesto', -1, 'Sin datos', 'images/productos/MAGNETO-KIT.png'),
(150, '0K7869', 'AVR 7KW UL (Regulador de Voltaje (AVR))', '', 'Stock Repuesto', 6, 'C26', 'images/productos/AVR-7KW-UL-Regulador-de-Voltaje.png'),
(151, '0L2917B', 'LOW OIL PRES SWITCH 5 PSI NO', '', 'Stock Repuesto', 0, 'C03', 'images/productos/sin-foto.png'),
(152, '0L2917C', 'LOW OIL PRES SWITCH 5 PSI NO', '', 'Stock Repuesto', 1, 'C03', 'images/productos/sin-foto.png'),
(153, '0L3059', 'SPARK PLUG RC9YC GAP 0.030 5,6 kva', '', 'Stock Repuesto', 8, 'C10', 'images/productos/SPARK-PLUG-RC9YC-GAP-0.030-56-kva.png'),
(154, '0L5966', 'LED LENS HOLDER W/GASKET', '', 'Stock Repuesto', 1, 'C14', 'images/productos/sin-foto.png'),
(155, 'A0000080542', 'AIR CLEANER 95 X 152OD / filtro de aire 50kva', '', 'Stock Repuesto', 1, 'Archivo', 'images/productos/AIR-CLEANER-95-X-152OD-1.png'),
(156, 'A0000501971', 'STARTER MOTOR GEAR REDUCED 1KW (burro de aranque 13kva)', '', 'Stock Repuesto', 0, 'E02', 'images/productos/STARTER-MOTOR-GEAR-REDUCED-1KW.png'),
(157, 'A0002791673', 'GASKET,VALVE COVER GV 13 kva (Juntas tapa de válvula)', '', 'Stock Repuesto', 17, 'C16', 'images/productos/GASKETVALVE-COVER-GV-13-kva.png'),
(158, 'G026793', 'DIO BRIDGE 25A 600V', '', 'Stock Repuesto', 4, 'C04', 'images/productos/DIO-BRIDGE-25A-600V.png'),
(159, 'G048512', 'CB 5A 1P AUTO ETA 46-500-P', '', 'Stock Repuesto', 2, 'C04', 'images/productos/CB-5A-1P-AUTO-ETA-46-500-P-2.png'),
(160, 'G056739', 'RELAY SOLENOID 12VDC PNL MNT', '', 'Stock Repuesto', 2, 'E03', 'images/productos/RELAY-SOLENOID-12VDC-PNL-MNT.png'),
(161, 'G059402', 'AIR CLEANER filtro de aire (27kva)', '', 'Stock Repuesto', 4, 'Archivo', 'images/productos/AIR-CLEANER-1.png'),
(162, 'G075591', 'HOLDER BRUSH - ASSEMBLY', '', 'Stock Repuesto', 2, 'C02', 'images/productos/HOLDER-BRUSH-–-ASSEMBLY.png'),
(163, 'G077220', 'COIL-HSB ATS UTILITYS UTILITY', '', 'Stock Repuesto', 2, 'E03', 'images/productos/COIL-HSB-ATS-UTILITYS-UTILITY.png'),
(164, 'G080318', 'SCREW HHFC M6-1.0 X 25 FTH G8', '', 'Stock Repuesto', 4, 'C20', 'images/productos/SCREW-HHFC-M6-1.0-X-25-FTH-G8.png'),
(165, 'G083235', 'TAPPET 7.5 DIA.', '', 'Stock Repuesto', 4, 'C21', 'images/productos/TAPPET-7.5-DIA.png'),
(166, 'g083897', 'TAPPET SOLID', '', 'Stock Repuesto', 0, 'C21', 'images/productos/TAPPET-SOLID.png'),
(167, 'RV8H-S-AD220', 'Rele Negro', '', 'Stock Repuesto', 10, 'Archivo', 'images/productos/sin-foto.png'),
(168, '00023', 'CONTACTORA ELIBET 63', '', 'Stock Repuesto', 12, 'Archivo', 'images/productos/sin-foto.png'),
(169, '00024', 'CONTACTORA ELIBET 100', '', 'Stock Repuesto', 1, 'E04', 'images/productos/sin-foto.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Cliente','Técnico','','') NOT NULL,
  `cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `role`, `cliente_id`) VALUES
(2, 'admin', '$2y$10$BhD3CU6vHHnCaruB45ot4OsFsIxFh8LreyGQwSEgelzhitrLBApB2', 'Técnico', 0),
(3, 'tecnico', '$2y$10$DC692jJKMWiiLbjDQZtbMeFoiewikFyHSVtZp6TtYS79ZtpRztaki', 'Técnico', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto` (`idProducto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD UNIQUE KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD KEY `clientes_id` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
