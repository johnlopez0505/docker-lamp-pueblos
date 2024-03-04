-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-01-2023 a las 20:36:38
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api-restaurantes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `log` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id`, `log`) VALUES
(5, 'recibido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `id_usuario`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 3, 'Socuellamos', 'restaurante de Sonia', NULL),
(2, 3, 'Chinchilla', 'restaurante de 10.000 hb', NULL),
(3, 2, 'Martos', 'restaurante de 8.000 hb', NULL),
(4, 2, 'Mancha Real', 'restaurante de 10.000 hb', NULL),
(6, 3, 'Lezuza', 'restaurante ibero-romano de Albacete', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL COMMENT 'clave principal',
  `email` varchar(150) NOT NULL,
  `password` varchar(240) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `imagen` varchar(200) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL,
  `token` varchar(240) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='tabla de usuarios';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`, `imagen`, `disponible`, `token`) VALUES
(1, 'davidrodenasherraiz@dominio.com', '07d046d5fac12b3f82daf5035b9aae86db5adc8275ebfbf05ec83005a4a8ba3e', 'david rodenas herraiz', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjIxMzczNzIsImRhdGEiOnsiaWQiOiIxIiwiZW1haWwiOiJkYXZpZHJvZEBnbWFpbC5jb20ifX0.FLlqJO30GgMiYWFNSXFjIWunenCjb7EnZJ30PSJdAN8'),
(2, 'soniamenadelgadol@dominio.com', 'b90d33f2b12789d32691050a2083be28eb99985601a1f1a72efc9232e49306fd', 'sonia mena delgado', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzM3NzU0MDEsImRhdGEiOnsiaWQiOiIyIiwiZW1haWwiOiJzb25pYW1lbmFkZWxAZ21haWwuY29tIn19.33d9tDvm1jRJ-fzdz1-leoRQ5EMnnrxuY7BNDqatl5g'),
(3, 'srodher115@g.educaand.es', '324ca5355e9d7d5f60fb23b379f5bad7d4a12013a8b89b46ec2392c3021d3a27', 'santiago', NULL, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzM3NzU2ODYsImRhdGEiOnsiaWQiOiIzIiwiZW1haWwiOiJzcm9kaGVyMTE1QGcuZWR1Y2FhbmQuZXMifX0.9Wb7_IMl_pLDzcPY8IH1SU4XUrOY5sdtC1Vhxr7_44c');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'clave principal', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
