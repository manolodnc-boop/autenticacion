-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2026 a las 06:25:11
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
-- Base de datos: `sistema_utpl`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` varchar(20) NOT NULL DEFAULT 'estudiante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `cedula`, `nombre`, `correo`, `password`, `fecha_registro`, `rol`) VALUES
(1, '1231231233', 'sad', 'sad@sad.com', '$2y$10$ojFFb3rhcPiXIipVH6JnneflNbshYgTg8bNPGAzvhs3KiIl6nNFoC', '2026-05-17 02:11:20', 'estudiante'),
(2, '1723650956', 'Chris Daza', 'cdaza@utpl.edu.ec', '$2y$10$smCzWGg1Em.URSMmnYmtFu0KHTa5YxDjYa92iAZJF.KRFQEiamvBm', '2026-05-17 02:35:10', 'profesor'),
(3, '1715000392', 'Paola', 'pao@pao.com', '$2y$10$sXDxsbUoxbZ8EX9YlJYxseqUhOPsKHgihqA/NxFHB6PDgwIKvMv5W', '2026-05-17 03:02:57', 'estudiante'),
(4, '1758750655', 'Atamaika', 'ata@ata.com', '$2y$10$.pTZcsy4b7QZuMUBbxoi1uHhz5O8oEqv9MNtCtSTD8SqOMi6tSF6m', '2026-05-17 03:07:41', 'estudiante'),
(9, '0123456789', 'men', 'man@man.com', '$2y$10$CvkpYXKGs0g/lSOB6eQG8etctzc7fSpV4tKXq5dA35tFXf1IDfnj.', '2026-05-17 03:21:45', 'estudiante'),
(10, '1111111111', 'axy', 'axy@axy.com', '$2y$10$2sB45lqIWEWY8v8RY01HauL5z/a.zeQ8UShl5pYgZV1JklkK2/3ge', '2026-05-17 03:59:43', 'estudiante');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
