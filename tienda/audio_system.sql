-- Base de datos: `audio_system`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(100) NOT NULL,
  `descripcion_categoria` text NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `categorias` table
INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `descripcion_categoria`) VALUES
(1, 'Electrónica', 'Productos electrónicos diversos'),
(2, 'Hogar', 'Productos para el hogar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cliente` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `dni_UNIQUE` (`dni`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `clientes` table
INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `telefono`, `email`, `direccion`, `dni`, `password`) VALUES
(1, 'Juan Perez', '987654321', 'juan.perez@example.com', 'Calle Falsa 123', '12345678', 'password1'),
(2, 'Ana Gomez', '912345678', 'ana.gomez@example.com', 'Av. Siempre Viva 456', '87654321', 'password2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccionamiento`
--

CREATE TABLE `direccionamiento` (
  `id_url` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `direccionamiento` table
INSERT INTO `direccionamiento` (`id_url`, `url`) VALUES
(1, '/dashboard'),
(2, '/ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_empleado` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  PRIMARY KEY (`id_empleado`),
  KEY `fk_empleados_roles_empleados1_idx` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `empleados` table
INSERT INTO `empleados` (`id_empleado`, `nombre_empleado`, `email`, `password`, `id_rol`) VALUES
(1, 'Carlos Martinez', 'carlos.martinez@example.com', 'password123', 1),
(2, 'Laura Fernandez', 'laura.fernandez@example.com', 'password456', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) DEFAULT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','Yape','Plin','Transferencia BCP') NOT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `id_venta` (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `pagos` table
INSERT INTO `pagos` (`id_pago`, `id_venta`, `fecha_pago`, `monto_pagado`, `metodo_pago`) VALUES
(1, 1, '2024-10-31', 150.00, 'efectivo'),
(2, 2, '2024-11-02', 200.00, 'Yape');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_categoria` (`id_categoria`),
  KEY `id_proveedor` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `productos` table
INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion`, `precio`, `stock`, `id_categoria`, `id_proveedor`) VALUES
(1, 'Producto A', 'Descripción del Producto A', 100.00, 20, 1, 1),
(2, 'Producto B', 'Descripción del Producto B', 150.00, 30, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_reservados`
--

CREATE TABLE `productos_reservados` (
  `id_reserva` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id_reserva`,`id_producto`),
  KEY `id_producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `productos_reservados` table
INSERT INTO `productos_reservados` (`id_reserva`, `id_producto`, `cantidad`) VALUES
(1, 1, 2),
(2, 2, 3);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nomb_empresa` varchar(100) NOT NULL,
  `nomb_contacto` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `postal` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `proveedores` table
INSERT INTO `proveedores` (`id_proveedor`, `nomb_empresa`, `nomb_contacto`, `ciudad`, `telefono`, `email`, `postal`) VALUES
(1, 'Proveedor A', 'Contacto A', 'Ciudad A', '123456789', 'contactoA@empresa.com', '12345'),
(2, 'Proveedor B', 'Contacto B', 'Ciudad B', '987654321', 'contactoB@empresa.com', '54321');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_reserva` date NOT NULL,
  `fecha_evento` date NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `estado` enum('pendiente','confirmada','cancelada','completada') NOT NULL DEFAULT 'pendiente',
  `contacto_cliente` varchar(100) DEFAULT NULL,
  `total_reserva` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `reservas` table
INSERT INTO `reservas` (`id_reserva`, `fecha_reserva`, `fecha_evento`, `id_cliente`, `estado`, `contacto_cliente`, `total_reserva`) VALUES
(1, '2024-10-25', '2024-11-01', 1, 'confirmada', 'Juan Perez', 200.00),
(2, '2024-10-28', '2024-11-05', 2, 'pendiente', 'Ana Gomez', 300.00);

-- Estructura de tabla para la tabla `roles_direccionamiento`
--

CREATE TABLE `roles_direccionamiento` (
  `id_rol` int(11) NOT NULL,
  `id_url` int(11) NOT NULL,
  PRIMARY KEY (`id_rol`, `id_url`),
  KEY `fk_roles_empleados_has_direccionamiento_direccionamiento1_idx` (`id_url`),
  KEY `fk_roles_empleados_has_direccionamiento_roles_empleados1_idx` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `roles_direccionamiento` table
INSERT INTO `roles_direccionamiento` (`id_rol`, `id_url`) VALUES
(1, 1),
(2, 2),
(3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_empleados`
--

CREATE TABLE `roles_empleados` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_rol` text NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `descripcion_rol_UNIQUE` (`descripcion_rol`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `roles_empleados` table
INSERT INTO `roles_empleados` (`id_rol`, `descripcion_rol`) VALUES
(1, 'Vendedor'),
(2, 'Administrador'),
(3, 'Gerente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_reserva` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `fecha_venta` date NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) DEFAULT 0.00,
  `monto_pendiente` decimal(10,2) DEFAULT NULL,
  `fecha_limite_pago` date DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `id_reserva` (`id_reserva`),
  KEY `id_empleado` (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `ventas` table
INSERT INTO `ventas` (`id_venta`, `id_reserva`, `id_empleado`, `fecha_venta`, `total_venta`, `monto_pagado`, `monto_pendiente`, `fecha_limite_pago`) VALUES
(1, 1, 1, '2024-10-30', 200.00, 150.00, 50.00, '2024-11-01'),
(2, 2, 2, '2024-11-01', 300.00, 200.00, 100.00, '2024-11-05');
