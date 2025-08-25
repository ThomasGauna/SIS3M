-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3308
-- Tiempo de generación: 25-08-2025 a las 21:31:23
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
-- Base de datos: `trimod_bdd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Repuestos muy chicos', 'Repuestos de tamaño chico'),
(2, 'Repuestos grandes', 'Repuestos de gran tamaño\\r\\n'),
(4, 'Electrodomésticos', 'Productos para el hogar como heladeras, cocinas, microondas, etc.'),
(5, 'Herramientas eléctricas', 'Taladros, amoladoras, sierras y otras herramientas de trabajo'),
(6, 'Iluminación', 'Lámparas, focos LED, reflectores y accesorios'),
(7, 'Componentes electrónicos', 'Chips, placas, memorias, procesadores, etc.'),
(8, 'Audio y video', 'Parlantes, televisores, proyectores y equipos de sonido'),
(9, 'Repuestos', 'Piezas de recambio para distintos tipos de productos'),
(10, 'Informática', 'Computadoras, periféricos y accesorios'),
(11, 'Telefonía', 'Teléfonos móviles, accesorios y repuestos'),
(12, 'Climatización', 'Aires acondicionados, ventiladores y calefactores'),
(13, 'Limpieza industrial', 'Equipos y suministros para limpieza en industrias y comercios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `tipo` enum('persona','empresa') NOT NULL DEFAULT 'persona',
  `nombre` varchar(150) NOT NULL,
  `apellido` varchar(150) DEFAULT NULL,
  `documento_tipo` enum('DNI','CUIT','CUIL','PAS','OTRO') DEFAULT NULL,
  `documento_nro` varchar(32) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(60) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `notas` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `tipo`, `nombre`, `apellido`, `documento_tipo`, `documento_nro`, `email`, `telefono`, `estado`, `notas`, `created_at`, `updated_at`) VALUES
(1, 'persona', 'Agustin', 'Giay', 'DNI', '558548547', 'agustin@gmail.com', '1122334432', 'activo', 'Primer prueba', '2025-08-12 11:54:14', '2025-08-12 11:54:14'),
(3, 'persona', 'Roca', 'Sanchez', 'CUIT', '3222565854', 'carlos@gmail.com', '01122334455', 'activo', 'Segunda prueba', '2025-08-13 10:35:24', '2025-08-13 10:37:20'),
(6, 'empresa', 'Acme SA', NULL, 'CUIT', '30-11223344-9', 'contacto@acme.com', '11-5555-0000', 'activo', 'Cliente demo', '2025-08-15 10:23:43', '2025-08-15 10:23:43'),
(7, 'persona', 'Lucía', 'Pérez', 'DNI', '40111222', 'lucia@example.com', '11-4444-2222', 'activo', 'Cliente demo persona', '2025-08-15 10:23:43', '2025-08-15 10:23:43'),
(8, 'persona', 'Juan', 'Martínez', 'DNI', '30111222', 'juan.martinez@example.com', '1160000001', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(9, 'persona', 'María', 'López', 'DNI', '30222333', 'maria.lopez@example.com', '1160000002', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(10, 'empresa', 'TecnoSur', NULL, 'CUIT', '30-71234567-8', 'contacto@tecnosur.com', '1160000003', 'activo', 'Cliente demo empresa', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(11, 'persona', 'Pedro', 'García', 'DNI', '30333444', 'pedro.garcia@example.com', '1160000004', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(12, 'persona', 'Lucía', 'Fernández', 'DNI', '30444555', 'lucia.fernandez@example.com', '1160000005', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(13, 'empresa', 'ServiTech', NULL, 'CUIT', '30-76543210-9', 'ventas@servitech.com', '1160000006', 'activo', 'Cliente demo empresa', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(14, 'persona', 'Carlos', 'Pérez', 'DNI', '30555666', 'carlos.perez@example.com', '1160000007', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(15, 'persona', 'Sofía', 'Morales', 'DNI', '30666777', 'sofia.morales@example.com', '1160000008', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(16, 'empresa', 'ElectroHouse', NULL, 'CUIT', '30-99887766-5', 'info@electrohouse.com', '1160000009', 'activo', 'Cliente demo empresa', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(17, 'persona', 'Diego', 'Ramírez', 'DNI', '30777888', 'diego.ramirez@example.com', '1160000010', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(18, 'persona', 'Florencia', 'Castro', 'DNI', '30888999', 'florencia.castro@example.com', '1160000011', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(19, 'empresa', 'MaxPower', NULL, 'CUIT', '30-55667788-4', 'ventas@maxpower.com', '1160000012', 'activo', 'Cliente demo empresa', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(20, 'persona', 'Javier', 'Mendoza', 'DNI', '30999000', 'javier.mendoza@example.com', '1160000013', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(21, 'persona', 'Camila', 'Silva', 'DNI', '31000111', 'camila.silva@example.com', '1160000014', 'activo', 'Cliente demo', '2025-08-15 10:26:03', '2025-08-15 10:26:03'),
(22, 'empresa', 'MegaTools', NULL, 'CUIT', '30-44556677-3', 'contacto@megatools.com', '1160000015', 'activo', 'Cliente demo empresa', '2025-08-15 10:26:03', '2025-08-15 10:26:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_contactos`
--

CREATE TABLE `cliente_contactos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `cargo` varchar(120) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(60) DEFAULT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente_contactos`
--

INSERT INTO `cliente_contactos` (`id`, `cliente_id`, `nombre`, `cargo`, `email`, `telefono`, `es_principal`, `created_at`, `updated_at`) VALUES
(2, 3, 'Ezequiel Cerruti', 'Gerente', 'cerruti@gmail.com', '114454567', 0, '2025-08-13 12:07:08', '2025-08-13 12:07:08'),
(3, 3, 'Alexis Cuello', 'Supervisor', 'alexis@gmail.com', '1122334343', 1, '2025-08-13 12:07:56', '2025-08-13 12:07:56'),
(6, 1, 'María Gomez', 'Compras', 'maria.gomez@cliente1.com', '11-2300-1111', 0, '2025-08-15 10:23:43', '2025-08-15 10:23:43'),
(7, 3, 'Pablo Suarez', 'Técnico', 'pablo.suarez@cliente3.com', '11-2300-2222', 0, '2025-08-15 10:23:43', '2025-08-15 10:23:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_direcciones`
--

CREATE TABLE `cliente_direcciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `etiqueta` varchar(50) DEFAULT NULL,
  `direccion` varchar(200) NOT NULL,
  `localidad` varchar(120) DEFAULT NULL,
  `provincia` varchar(120) DEFAULT NULL,
  `pais` varchar(120) DEFAULT 'Argentina',
  `cp` varchar(20) DEFAULT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente_direcciones`
--

INSERT INTO `cliente_direcciones` (`id`, `cliente_id`, `etiqueta`, `direccion`, `localidad`, `provincia`, `pais`, `cp`, `es_principal`, `created_at`, `updated_at`) VALUES
(1, 1, 'Envio', 'Av La Plata 1908', 'CABA', 'bUENO', 'Argentina', '1403', 0, '2025-08-12 11:54:14', '2025-08-12 17:08:59'),
(3, 1, 'Fiscal', 'Av. Corrientes 1234', 'CABA', 'Buenos Aires', 'Argentina', 'C1043', 1, '2025-08-12 16:47:53', '2025-08-12 16:47:53'),
(4, 1, 'Envío', 'Av. La Plata 1908', 'CABA', 'Buenos Aires', 'Argentina', 'C1240', 0, '2025-08-12 16:47:53', '2025-08-12 16:47:53'),
(5, 1, 'Depósito', 'Ruta 8 Km 52', 'Pilar', 'Buenos Aires', 'Argentina', 'B1629', 0, '2025-08-12 16:47:53', '2025-08-12 16:47:53'),
(11, 3, 'Envio', 'Rivadavia 4598', 'CABA', 'Buenos Aires', 'Argentina', '1507', 0, '2025-08-13 12:05:32', '2025-08-13 12:29:56'),
(12, 3, 'Envio 2', 'Santa fe 2801', 'CABA', 'Buenos Aires', 'Argentina', '1708', 1, '2025-08-13 12:10:32', '2025-08-13 12:30:57'),
(15, 1, 'Sucursal', 'Brandsen 250', 'Avellaneda', 'Buenos Aires', 'Argentina', '1870', 0, '2025-08-15 10:23:43', '2025-08-15 10:23:43'),
(16, 3, 'Fiscal', 'Mitre 500', 'Lanús', 'Buenos Aires', 'Argentina', '1824', 0, '2025-08-15 10:23:43', '2025-08-15 10:23:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_tags`
--

CREATE TABLE `cliente_tags` (
  `cliente_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intervenciones`
--

CREATE TABLE `intervenciones` (
  `id` int(11) NOT NULL,
  `trabajo_id` int(11) NOT NULL,
  `tecnico_id` int(11) NOT NULL,
  `estado_intervencion` enum('borrador','final') NOT NULL DEFAULT 'borrador',
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `duracion_min` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `firma_tecnico` varchar(255) DEFAULT NULL,
  `firma_cliente` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intervenciones_materiales`
--

CREATE TABLE `intervenciones_materiales` (
  `id` int(11) NOT NULL,
  `intervencion_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unit` decimal(12,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `estado` enum('abierto','cerrado') NOT NULL DEFAULT 'abierto',
  `ubicacion_id` int(11) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_items`
--

CREATE TABLE `inventario_items` (
  `id` int(11) NOT NULL,
  `inventario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `conteo` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`) VALUES
(7, '3M'),
(10, 'AMD'),
(2, 'Bosch'),
(1, 'Generacc'),
(9, 'Intel'),
(11, 'Lenovo'),
(6, 'LG'),
(3, 'Makita'),
(4, 'Philips'),
(5, 'Samsung'),
(8, 'Whirlpool');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_productos`
--

CREATE TABLE `movimientos_productos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `tipo` enum('entrada','salida','transferencia') NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `costo_unitario` decimal(12,2) DEFAULT NULL,
  `ubic_origen_id` int(11) DEFAULT NULL,
  `ubic_destino_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `origen` varchar(40) DEFAULT NULL,
  `ref_tipo` varchar(40) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `notas` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_productos`
--

INSERT INTO `movimientos_productos` (`id`, `producto_id`, `tipo`, `cantidad`, `costo_unitario`, `ubic_origen_id`, `ubic_destino_id`, `usuario_id`, `origen`, `ref_tipo`, `ref_id`, `notas`, `created_at`) VALUES
(1, 13, 'entrada', 10.00, 740000.00, 1, 3, NULL, 'compras', 'OC', 1001, 'Alta inicial Taladro', '2025-08-12 09:10:00'),
(2, 16, 'entrada', 200.00, 3200.00, 10, 5, NULL, 'compras', 'OC', 1002, 'Reposición Foco LED', '2025-08-13 14:25:00'),
(3, 19, 'entrada', 15.00, 210000.00, NULL, 3, NULL, 'compras', 'OC', 1003, 'Ingreso SSD NVMe', '2025-08-14 11:00:00'),
(4, 16, 'salida', 8.00, 3500.00, 5, NULL, NULL, 'trabajo', 'trabajo', 1, 'Consumo en servicio', '2025-08-15 10:00:00'),
(5, 13, 'salida', 2.00, 750000.00, 3, NULL, NULL, 'trabajo', 'trabajo', 2, 'Uso en intervención', '2025-08-16 16:40:00'),
(6, 20, 'salida', 3.00, 95000.00, 6, NULL, NULL, 'venta', 'factura', 501, 'Venta mostrador', '2025-08-17 12:05:00'),
(7, 14, 'transferencia', 5.00, 1450000.00, 3, 6, NULL, 'logistica', 'traslado', 301, 'Reposición Sucursal Sur', '2025-08-18 09:30:00'),
(8, 15, 'transferencia', 12.00, 25000.00, 1, 4, NULL, 'logistica', 'traslado', 302, 'Mover correas a Depósito Secundario', '2025-08-18 10:15:00'),
(9, 17, 'entrada', 20.00, 17000.00, 3, 4, NULL, 'compras', 'OC', 1004, 'Bidones 5L', '2025-08-19 08:00:00'),
(10, 19, 'salida', 4.00, 220000.00, 3, NULL, NULL, 'trabajo', 'trabajo', 3, 'Instalación SSDs', '2025-08-19 15:45:00'),
(11, 59, 'entrada', 15.00, NULL, NULL, 3, NULL, 'compra', 'FACT', 12143414, 'Segunda prueba', '2025-08-21 13:31:50'),
(12, 59, 'entrada', 15.00, NULL, NULL, 3, NULL, 'compra', 'FACT', 12143414, 'Segunda prueba', '2025-08-21 13:31:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `unidad_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `stock_actual` decimal(12,2) DEFAULT 0.00,
  `stock_minimo` int(11) DEFAULT 0,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ubicacion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `sku`, `nombre`, `descripcion`, `unidad_id`, `categoria_id`, `marca_id`, `proveedor_id`, `costo_unitario`, `stock_actual`, `stock_minimo`, `estado`, `fecha_alta`, `fecha_actualizacion`, `ubicacion_id`) VALUES
(13, 'ELEC-001', 'Taladro Percutor 600W', 'Taladro percutor con velocidad variable y reversa', 1, 8, 2, 8, 750000.00, 5.00, 0, 'activo', '2025-08-12 00:00:00', '2025-08-12 11:11:56', 3),
(14, 'HERR-001', 'Amoladora Angular 850W', 'Amoladora 115mm, 850W', 1, 5, 3, 5, 1450000.00, 12.00, 2, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 3),
(15, 'REP-010', 'Correa Dentada 1200mm', 'Correa de transmisión', 1, 9, 1, 7, 25000.00, 40.00, 5, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 1),
(16, 'ILUM-020', 'Foco LED 12W E27', 'Luz fría, 1200lm', 1, 6, 4, 5, 3500.00, 200.00, 30, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 5),
(17, 'LIMP-100', 'Desengrasante Industrial 5L', 'Bidón 5 litros', 4, 13, 7, 9, 18000.00, 60.00, 10, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 4),
(18, 'ELEC-050', 'Placa Control AC', 'Módulo control aire acondicionado', 1, 7, 6, 6, 125000.00, 15.00, 2, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 7),
(19, 'INF-200', 'Disco SSD 1TB', 'NVMe 1TB', 1, 10, 5, 6, 220000.00, 25.00, 5, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 3),
(20, 'TEL-300', 'Pantalla Repuesto Smartphone', 'OLED repuesto serie S', 1, 11, 5, 8, 95000.00, 30.00, 5, 'activo', '2025-08-12 11:24:01', '2025-08-12 11:24:01', 6),
(51, 'ACE-KM40', 'ACEITE KM40', 'Aceite para motor', 4, 12, 2, 5, 89000.00, 50.00, 5, 'activo', '2025-05-06 00:00:00', '2025-08-11 00:00:00', 2),
(52, 'BAT-12V', 'BATERÍA 12V', 'Batería para auto 12V', 1, 9, 5, 6, 65000.00, 20.00, 3, 'activo', '2025-05-10 00:00:00', '2025-08-11 00:00:00', 1),
(53, 'FIL-AIRE', 'FILTRO DE AIRE', 'Filtro estándar', 1, 1, 3, 7, 9500.00, 120.00, 10, 'activo', '2025-05-12 00:00:00', '2025-08-11 00:00:00', 3),
(54, 'PAST-FRE', 'PASTILLAS DE FRENO', 'Juego delanteras', 1, 9, 2, 8, 22000.00, 35.00, 6, 'activo', '2025-05-15 00:00:00', '2025-08-11 00:00:00', 4),
(55, 'ANTI-5L', 'ANTICONGELANTE 5L', 'Refrigerante anticongelante', 5, 13, 7, 5, 18000.00, 60.00, 8, 'activo', '2025-05-20 00:00:00', '2025-08-12 11:49:24', 5),
(56, 'BUJ-NGK', 'BUJÍA NGK', 'Bujía estándar', 1, 9, 4, 6, 3200.00, 200.00, 20, 'activo', '2025-05-22 00:00:00', '2025-08-11 00:00:00', 6),
(57, 'COR-DEN', 'CORREA DENTADA', 'Correa de distribución', 1, 9, 3, 7, 25000.00, 45.00, 5, 'activo', '2025-05-25 00:00:00', '2025-08-11 00:00:00', 7),
(58, 'DIS-FRE', 'DISCO DE FRENO', 'Disco ventilado', 1, 9, 2, 5, 41000.00, 30.00, 4, 'activo', '2025-05-27 00:00:00', '2025-08-11 00:00:00', 8),
(59, 'AMO-DEL', 'AMORTIGUADOR DELANTERO', 'Hidráulico delantero', 1, 9, 1, 4, 52000.00, 48.00, 3, 'activo', '2025-05-30 00:00:00', '2025-08-21 13:31:54', 9),
(60, 'LAMP-H4', 'LÁMPARA H4 60/55W', 'Halógena H4', 1, 6, 4, 6, 2500.00, 150.00, 25, 'activo', '2025-06-02 00:00:00', '2025-08-11 00:00:00', 10),
(61, 'PIN-2P', 'PINCEL 2\"', 'Pincel para pintura al agua', 1, 4, 7, 7, 1200.00, 80.00, 10, 'activo', '2025-06-05 00:00:00', '2025-08-11 00:00:00', 3),
(62, 'CAB-2X15', 'CABLE 2x1,5mm (100m)', 'Rollo 100 m', 5, 7, 5, 8, 14500.00, 12.00, 3, 'activo', '2025-06-07 00:00:00', '2025-08-11 00:00:00', 1),
(63, 'SSD-1TB', 'DISCO SSD 1TB', 'NVMe 1TB', 1, 10, 10, 6, 220000.00, 25.00, 5, 'activo', '2025-06-10 00:00:00', '2025-08-11 00:00:00', 3),
(64, 'TEL-PANT', 'PANTALLA SMARTPHONE OLED', 'Repuesto serie S', 1, 11, 6, 8, 95000.00, 30.00, 5, 'activo', '2025-06-14 00:00:00', '2025-08-11 00:00:00', 6),
(65, 'GUAN-NIT', 'GUANTES NITRILO', 'Resistentes a químicos', 1, 13, 3, 5, 750.00, 100.00, 15, 'activo', '2025-06-18 00:00:00', '2025-08-11 00:00:00', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cuit` varchar(15) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `pais` varchar(50) NOT NULL DEFAULT 'Argentina',
  `contacto_nombre` varchar(100) DEFAULT NULL,
  `contacto_telefono` varchar(20) DEFAULT NULL,
  `contacto_email` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_alta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `cuit`, `telefono`, `email`, `direccion`, `localidad`, `provincia`, `pais`, `contacto_nombre`, `contacto_telefono`, `contacto_email`, `observaciones`, `estado`, `fecha_alta`) VALUES
(1, 'Marolio', '20-44522777-8', '1123343432', 'marolio@info.com', 'Calle ejemplo 223', 'Lanus', 'Buenos aires', 'Argentina', 'Gaston Hernandez', '1119081908', 'gaston@gmail.com', 'Prueba de proveedores', 'activo', '2024-09-08'),
(4, 'Renault', '20-99887766-8', '12232829', 'renault@ejemplo.com', 'Calle ejemplo 223', 'Rosario', 'Santa fe', 'Argentina', 'Ezequiel Cerruti', '01122334455', 'cerruti@gmail.com', 'Sin observaciones', 'activo', '2025-06-09'),
(5, 'ElectroParts SRL', '30-12345678-9', '11-4567-8901', 'ventas@electroparts.com', 'Av. Siempre Viva 123', 'CABA', 'Buenos Aires', 'Argentina', 'Juan Pérez', '11-4567-8902', 'jperez@electroparts.com', 'Proveedor de insumos eléctricos.', 'activo', '2025-08-01'),
(6, 'TecnoProveedores SA', '30-87654321-0', '11-7890-1234', 'contacto@tecnoproveedores.com', 'Calle Falsa 456', 'Morón', 'Buenos Aires', 'Argentina', 'María López', '11-7890-1235', 'mlopez@tecnoproveedores.com', 'Especialista en componentes electrónicos.', 'activo', '2025-08-02'),
(7, 'Distribuidora Industrial', '30-11112222-3', '11-2222-3333', 'info@distindustrial.com', 'Ruta 8 Km 45', 'Pilar', 'Buenos Aires', 'Argentina', 'Carlos Gómez', '11-2222-3334', 'cgomez@distindustrial.com', 'Provee insumos industriales.', 'activo', '2025-08-03'),
(8, 'Servicios Técnicos del Sur', '30-33334444-5', '11-4444-5555', 'atencion@servtecsur.com', 'Mitre 789', 'Lomas de Zamora', 'Buenos Aires', 'Argentina', 'Ana Martínez', '11-4444-5556', 'amartinez@servtecsur.com', 'Proveedor de soporte técnico especializado.', 'activo', '2025-08-04'),
(9, 'Suministros Globales', '30-55556666-7', '11-6666-7777', 'ventas@suministrosglobales.com', 'Belgrano 1010', 'Rosario', 'Santa Fe', 'Argentina', 'Pedro Fernández', '11-6666-7778', 'pfernandez@suministrosglobales.com', 'Proveedor internacional, actualmente inactivo.', 'inactivo', '2025-08-05');

--
-- Disparadores `proveedores`
--
DELIMITER $$
CREATE TRIGGER `trg_proveedores_bi` BEFORE INSERT ON `proveedores` FOR EACH ROW BEGIN
  IF NEW.fecha_alta IS NULL THEN
    SET NEW.fecha_alta = CURDATE();
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_reservas`
--

CREATE TABLE `stock_reservas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `origen` varchar(40) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `nombre`) VALUES
(5, 'Deudor'),
(6, 'Mayorista'),
(4, 'VIP');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(60) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajos`
--

CREATE TABLE `trabajos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `titulo` varchar(160) NOT NULL,
  `descripcion_ini` text DEFAULT NULL,
  `prioridad` enum('baja','media','alta','critica') DEFAULT 'media',
  `estado` enum('nuevo','asignado','en_progreso','pendiente','finalizado','cancelado') DEFAULT 'nuevo',
  `ubicacion_id` int(11) DEFAULT NULL,
  `fecha_alta` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajos`
--

INSERT INTO `trabajos` (`id`, `cliente_id`, `titulo`, `descripcion_ini`, `prioridad`, `estado`, `ubicacion_id`, `fecha_alta`, `fecha_cierre`, `created_by`, `updated_by`) VALUES
(1, 6, 'Instalación de CCTV', 'Relevamiento y montaje de 6 cámaras PoE', 'alta', 'en_progreso', 6, '2025-08-18 10:15:00', NULL, NULL, NULL),
(2, 1, 'Reparación de notebook', 'No enciende, posible problema de fuente', 'media', 'nuevo', 3, '2025-08-19 09:00:00', NULL, NULL, NULL),
(3, 7, 'Cambio de display celular', 'Pantalla rota, repuesto disponible', 'alta', 'asignado', 5, '2025-08-19 11:45:00', NULL, NULL, NULL),
(4, 3, 'Mantenimiento de UPS', 'Chequeo baterías y pruebas de autonomía', 'critica', 'en_progreso', 4, '2025-08-20 08:30:00', NULL, NULL, NULL),
(5, 8, 'Instalación de SSD', 'Clonado y montaje en equipo del cliente', 'media', 'finalizado', 3, '2025-08-18 15:20:00', '2025-08-19 17:10:00', NULL, NULL),
(6, 6, 'Calibración de red WiFi', 'Optimización de canales y potencias AP', 'baja', 'pendiente', 5, '2025-08-17 14:00:00', NULL, NULL, NULL),
(7, 1, 'Backup y limpieza', 'Respaldo de datos y limpieza interna', 'baja', 'finalizado', 3, '2025-08-16 10:00:00', '2025-08-16 12:30:00', NULL, NULL),
(8, 3, 'Revisión de aire acondicionado', 'Equipo no enfría; revisar gas y filtros', 'media', 'cancelado', 6, '2025-08-15 09:20:00', NULL, NULL, NULL),
(9, 7, 'Armado PC de alto rendimiento', 'Ensambles + stress test 24h', 'alta', 'asignado', 3, '2025-08-20 16:45:00', NULL, NULL, NULL),
(10, 8, 'Cambio de lámparas LED', 'Reemplazo por tiras 6500K en depósito', 'media', 'nuevo', 5, '2025-08-21 09:05:00', NULL, NULL, NULL),
(11, 13, 'Monitor roto', 'Leds quemadas', 'baja', 'nuevo', 3, '2025-08-25 16:06:50', NULL, NULL, NULL),
(12, 13, 'Monitor roto', 'Leds quemadas', 'critica', 'nuevo', 3, '2025-08-25 16:06:50', NULL, NULL, NULL),
(13, 19, 'Prueba 1', 'Prueba de trabajo', 'media', 'nuevo', 1, '2025-08-25 16:07:53', NULL, NULL, NULL),
(14, 19, 'Prueba 1', 'Prueba de trabajo', 'media', 'nuevo', 1, '2025-08-25 16:07:53', NULL, NULL, NULL),
(15, 3, 'Prueba trabajo', 'Prueba trabajo 1', 'alta', 'nuevo', NULL, '2025-08-25 16:27:29', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `pais` varchar(50) DEFAULT 'Argentina',
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `fecha_alta` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `nombre`, `descripcion`, `direccion`, `localidad`, `provincia`, `pais`, `estado`, `fecha_alta`) VALUES
(1, 'Almacen A3', 'Pasillo 2, A3', 'Calle ejemplo 223', 'Lanus', 'Buenos aires', 'Argentina', 'activa', '2022-08-08'),
(2, 'Almacen A4', 'Pasillo 56, A12', 'Calle ejemplo 223', 'Lanus', 'Buenos aires', 'Argentina', '', '2022-08-08'),
(3, 'Depósito Central', 'Almacén principal de la empresa', 'Av. Siempre Viva 742', 'Buenos Aires', 'Buenos Aires', 'Argentina', 'activa', '2025-08-12'),
(4, 'Depósito Secundario', 'Depósito auxiliar para stock de respaldo', 'Calle Falsa 123', 'Rosario', 'Santa Fe', 'Argentina', 'activa', '2025-08-12'),
(5, 'Sucursal Norte', 'Sucursal de ventas y depósito regional', 'Ruta Nacional 9 Km 350', 'Córdoba', 'Córdoba', 'Argentina', 'activa', '2025-08-12'),
(6, 'Sucursal Sur', 'Sucursal con área de servicio técnico', 'Av. Costanera 500', 'Bahía Blanca', 'Buenos Aires', 'Argentina', 'activa', '2025-08-12'),
(7, 'Taller Técnico', 'Espacio de reparación y mantenimiento', 'Calle Industria 456', 'Mendoza', 'Mendoza', 'Argentina', 'activa', '2025-08-12'),
(8, 'Zona de Descarte', 'Área para productos fuera de uso o reciclaje', NULL, 'Buenos Aires', 'Buenos Aires', 'Argentina', 'activa', '2025-08-12'),
(9, 'Depósito Proveedores', 'Zona de recepción directa desde proveedores', NULL, 'La Plata', 'Buenos Aires', 'Argentina', 'activa', '2025-08-12'),
(10, 'Depósito Temporal', 'Espacio temporal para reorganización de stock', NULL, 'San Miguel de Tucumán', 'Tucumán', 'Argentina', 'activa', '2025-08-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abreviatura` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidades_medida`
--

INSERT INTO `unidades_medida` (`id`, `nombre`, `abreviatura`) VALUES
(1, 'Unidad', 'un'),
(2, 'Kilogramo', 'kg'),
(3, 'Gramo', 'g'),
(4, 'Litro', 'L'),
(5, 'Metro', 'm');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cliente_doc` (`documento_tipo`,`documento_nro`);

--
-- Indices de la tabla `cliente_contactos`
--
ALTER TABLE `cliente_contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_cont_cliente` (`cliente_id`,`es_principal`);

--
-- Indices de la tabla `cliente_direcciones`
--
ALTER TABLE `cliente_direcciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_dir_cliente` (`cliente_id`,`es_principal`);

--
-- Indices de la tabla `cliente_tags`
--
ALTER TABLE `cliente_tags`
  ADD PRIMARY KEY (`cliente_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indices de la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_int_trab` (`trabajo_id`),
  ADD KEY `ix_int_tec` (`tecnico_id`),
  ADD KEY `ix_int_est` (`estado_intervencion`);

--
-- Indices de la tabla `intervenciones_materiales`
--
ALTER TABLE `intervenciones_materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_im_int` (`intervencion_id`),
  ADD KEY `ix_im_prod` (`producto_id`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inv_ubi` (`ubicacion_id`);

--
-- Indices de la tabla `inventario_items`
--
ALTER TABLE `inventario_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inv_item_inv` (`inventario_id`),
  ADD KEY `fk_inv_item_prod` (`producto_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `movimientos_productos`
--
ALTER TABLE `movimientos_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mp_uori` (`ubic_origen_id`),
  ADD KEY `fk_mp_udst` (`ubic_destino_id`),
  ADD KEY `ix_mp_prod_fecha` (`producto_id`,`created_at`),
  ADD KEY `ix_mp_origen` (`origen`,`ref_tipo`,`ref_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_productos_sku` (`sku`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `marca_id` (`marca_id`),
  ADD KEY `proveedor_id` (`proveedor_id`),
  ADD KEY `idx_productos_unidad` (`unidad_id`),
  ADD KEY `idx_productos_ubicacion` (`ubicacion_id`),
  ADD KEY `idx_productos_nombre` (`nombre`),
  ADD KEY `idx_productos_sku` (`sku`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_proveedores_cuit` (`cuit`);

--
-- Indices de la tabla `stock_reservas`
--
ALTER TABLE `stock_reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_res_prod` (`producto_id`),
  ADD KEY `ix_res_lookup` (`origen`,`ref_id`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trabajos`
--
ALTER TABLE `trabajos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_trab_cliente` (`cliente_id`),
  ADD KEY `ix_trab_estado` (`estado`),
  ADD KEY `ix_trab_prioridad` (`prioridad`),
  ADD KEY `fk_trab_ubic` (`ubicacion_id`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_unidad_nombre` (`nombre`),
  ADD UNIQUE KEY `uq_unidad_abrev` (`abreviatura`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `cliente_contactos`
--
ALTER TABLE `cliente_contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cliente_direcciones`
--
ALTER TABLE `cliente_direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intervenciones_materiales`
--
ALTER TABLE `intervenciones_materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_items`
--
ALTER TABLE `inventario_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `movimientos_productos`
--
ALTER TABLE `movimientos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `stock_reservas`
--
ALTER TABLE `stock_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajos`
--
ALTER TABLE `trabajos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente_contactos`
--
ALTER TABLE `cliente_contactos`
  ADD CONSTRAINT `fk_cont_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cliente_direcciones`
--
ALTER TABLE `cliente_direcciones`
  ADD CONSTRAINT `fk_dir_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cliente_tags`
--
ALTER TABLE `cliente_tags`
  ADD CONSTRAINT `cliente_tags_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cliente_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `intervenciones`
--
ALTER TABLE `intervenciones`
  ADD CONSTRAINT `fk_int_tec` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnicos` (`id`),
  ADD CONSTRAINT `fk_int_trab` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `intervenciones_materiales`
--
ALTER TABLE `intervenciones_materiales`
  ADD CONSTRAINT `fk_im_int` FOREIGN KEY (`intervencion_id`) REFERENCES `intervenciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_im_prod` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `fk_inv_ubi` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`);

--
-- Filtros para la tabla `inventario_items`
--
ALTER TABLE `inventario_items`
  ADD CONSTRAINT `fk_inv_item_inv` FOREIGN KEY (`inventario_id`) REFERENCES `inventarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inv_item_prod` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `movimientos_productos`
--
ALTER TABLE `movimientos_productos`
  ADD CONSTRAINT `fk_mp_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_mp_udst` FOREIGN KEY (`ubic_destino_id`) REFERENCES `ubicaciones` (`id`),
  ADD CONSTRAINT `fk_mp_uori` FOREIGN KEY (`ubic_origen_id`) REFERENCES `ubicaciones` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_ubicacion` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`),
  ADD CONSTRAINT `fk_productos_unidad` FOREIGN KEY (`unidad_id`) REFERENCES `unidades_medida` (`id`),
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `stock_reservas`
--
ALTER TABLE `stock_reservas`
  ADD CONSTRAINT `fk_res_prod` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `trabajos`
--
ALTER TABLE `trabajos`
  ADD CONSTRAINT `fk_trab_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `fk_trab_ubic` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
