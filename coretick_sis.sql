-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-05-2024 a las 05:14:13
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `coretick_sis`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `id_color` int(10) NOT NULL,
  `nom_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`id_color`, `nom_color`) VALUES
(1, 'blanco'),
(2, 'negro'),
(3, 'verde'),
(4, 'rosado'),
(5, 'amarillo'),
(6, 'azul'),
(7, 'rojo'),
(8, 'morado'),
(9, 'negro mate'),
(10, 'negro'),
(11, 'gris mate'),
(12, 'gris ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivos`
--

CREATE TABLE `dispositivos` (
  `serial` varchar(30) NOT NULL,
  `imagen` varchar(1000) NOT NULL,
  `id_marca` int(10) NOT NULL,
  `id_color` int(10) NOT NULL,
  `id_tipo_dispositivo` int(11) NOT NULL,
  `documento` bigint(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `nit_empresa` varchar(12) NOT NULL,
  `telefono` int(10) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`nit_empresa`, `telefono`, `nombre`) VALUES
('1234567891', 2147483647, 'sena');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada_salidas`
--

CREATE TABLE `entrada_salidas` (
  `id_entrada_salida` int(11) NOT NULL,
  `entrada_fecha_hora` datetime NOT NULL,
  `salida_fecha_hora` datetime NOT NULL,
  `documento` bigint(11) NOT NULL,
  `tipo_entrada` int(10) NOT NULL,
  `id_placa` varchar(7) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estados` int(11) NOT NULL,
  `nom_estado` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estados`, `nom_estado`) VALUES
(1, 'activo'),
(2, 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `licencia` varchar(255) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `nit_empresa` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencias`
--

INSERT INTO `licencias` (`licencia`, `id_estado`, `fecha`, `fecha_fin`, `nit_empresa`) VALUES
('664ca9e1698a1', 1, '2024-05-21 09:04:17', '2026-05-21 09:04:17', 1234567891);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id_marca` int(10) NOT NULL,
  `nom_marca` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id_marca`, `nom_marca`) VALUES
(1, 'audi'),
(2, 'chevrolet'),
(3, 'mazda'),
(4, 'toyota'),
(5, 'renault'),
(6, 'bmw'),
(7, 'subaru'),
(8, 'hyundai'),
(9, 'honda'),
(10, 'ford'),
(11, 'HP'),
(12, 'LENOVO'),
(13, 'ASUS'),
(14, 'ACER'),
(15, 'MSI'),
(16, 'APPLE'),
(17, 'HUAWEI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nom_rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nom_rol`) VALUES
(1, 'Administrador'),
(2, 'vigilante'),
(3, 'visitantes'),
(4, 'aprendiz'),
(5, 'Instructor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_dispositivo`
--

CREATE TABLE `tipo_dispositivo` (
  `id_tipo_dispositivo` int(11) NOT NULL,
  `nom_dispositivo` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_dispositivo`
--

INSERT INTO `tipo_dispositivo` (`id_tipo_dispositivo`, `nom_dispositivo`) VALUES
(1, 'poratil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id_tipo_documento` int(11) NOT NULL,
  `nom_doc` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id_tipo_documento`, `nom_doc`) VALUES
(1, 'cc'),
(2, 'ti');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_entrada`
--

CREATE TABLE `tipo_entrada` (
  `id_tipo_entrada` int(10) NOT NULL,
  `nom_tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_entrada`
--

INSERT INTO `tipo_entrada` (`id_tipo_entrada`, `nom_tipo`) VALUES
(1, 'vehicular'),
(2, 'peatonal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculo`
--

CREATE TABLE `tipo_vehiculo` (
  `id_tipo_vehiculo` int(4) NOT NULL,
  `nom_vehiculo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_vehiculo`
--

INSERT INTO `tipo_vehiculo` (`id_tipo_vehiculo`, `nom_vehiculo`) VALUES
(1, 'automovil'),
(2, 'moto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trigger`
--

CREATE TABLE `trigger` (
  `id_trigger` int(10) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `documento` bigint(11) NOT NULL,
  `nombres` varchar(20) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `nit_empresa` varchar(12) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `codigo_barras` varchar(500) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_tipo_documento` int(11) NOT NULL,
  `id_estados` int(11) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`documento`, `nombres`, `correo`, `nit_empresa`, `contrasena`, `codigo`, `codigo_barras`, `id_rol`, `id_tipo_documento`, `id_estados`, `foto`) VALUES
(2854089011, 'ale', 'yesicagomezrued42@gmail.com', '1234567891', '$2y$10$VZKiMqtBtzqarK1lCvaziu/NqpUTcjL5SbAg.fS6xZAClOrDlb4e6', '', '664eb1b0d93db', 1, 1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_placa` varchar(11) NOT NULL,
  `id_marca` int(10) NOT NULL,
  `id_color` int(10) NOT NULL,
  `id_tipo_vehiculo` int(4) NOT NULL,
  `documento` bigint(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id_color`);

--
-- Indices de la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD PRIMARY KEY (`serial`),
  ADD KEY `id_tipo_dispositivo` (`id_tipo_dispositivo`),
  ADD KEY `dispositivos_ibfk_1` (`id_marca`),
  ADD KEY `dispositivos_ibfk_2` (`id_color`),
  ADD KEY `documento` (`documento`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`nit_empresa`);

--
-- Indices de la tabla `entrada_salidas`
--
ALTER TABLE `entrada_salidas`
  ADD PRIMARY KEY (`id_entrada_salida`),
  ADD KEY `entrada_salidas_ibfk_1` (`tipo_entrada`),
  ADD KEY `entrada_salidas_ibfk_3` (`id_placa`),
  ADD KEY `entrada_salidas_ibfk_4` (`serial`),
  ADD KEY `documento` (`documento`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estados`);

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipo_dispositivo`
--
ALTER TABLE `tipo_dispositivo`
  ADD PRIMARY KEY (`id_tipo_dispositivo`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id_tipo_documento`);

--
-- Indices de la tabla `tipo_entrada`
--
ALTER TABLE `tipo_entrada`
  ADD PRIMARY KEY (`id_tipo_entrada`);

--
-- Indices de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  ADD PRIMARY KEY (`id_tipo_vehiculo`);

--
-- Indices de la tabla `trigger`
--
ALTER TABLE `trigger`
  ADD PRIMARY KEY (`id_trigger`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `usuario_ibfk_1` (`id_rol`),
  ADD KEY `usuario_ibfk_2` (`id_tipo_documento`),
  ADD KEY `usuario_ibfk_3` (`id_estados`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_placa`),
  ADD KEY `vehiculos_ibfk_2` (`id_color`),
  ADD KEY `vehiculos_ibfk_3` (`id_marca`),
  ADD KEY `vehiculos_ibfk_4` (`id_tipo_vehiculo`),
  ADD KEY `documento` (`documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `id_color` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `entrada_salidas`
--
ALTER TABLE `entrada_salidas`
  MODIFY `id_entrada_salida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id_marca` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id_tipo_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_entrada`
--
ALTER TABLE `tipo_entrada`
  MODIFY `id_tipo_entrada` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  MODIFY `id_tipo_vehiculo` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `trigger`
--
ALTER TABLE `trigger`
  MODIFY `id_trigger` int(10) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dispositivos`
--
ALTER TABLE `dispositivos`
  ADD CONSTRAINT `dispositivos_ibfk_1` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id_marca`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dispositivos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `color` (`id_color`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dispositivos_ibfk_3` FOREIGN KEY (`id_tipo_dispositivo`) REFERENCES `tipo_dispositivo` (`id_tipo_dispositivo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dispositivos_ibfk_4` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);

--
-- Filtros para la tabla `entrada_salidas`
--
ALTER TABLE `entrada_salidas`
  ADD CONSTRAINT `entrada_salidas_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
