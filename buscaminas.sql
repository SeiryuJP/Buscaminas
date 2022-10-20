-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2022 a las 18:46:37
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `buscaminas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fields`
--

CREATE TABLE `fields` (
  `ID` int(11) NOT NULL,
  `Id_Player` varchar(20) NOT NULL,
  `Size` varchar(20) NOT NULL,
  `Field_visible` varchar(100) NOT NULL,
  `Field_hidden` varchar(100) NOT NULL,
  `Finished` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fields`
--

INSERT INTO `fields` (`ID`, `Id_Player`, `Size`, `Field_visible`, `Field_hidden`, `Finished`) VALUES
(10, '1234', '10', '*,1,0,1,*,2,*,*,1,0', '*,,,,,,,,,', 1),
(11, '1234', '10', '1,*,1,1,*,1,0,0,0,0', '1,,1,1,*,,,,,', 1),
(12, '1234', '10', '1,*,1,0,0,0,0,0,0,0', '1,*,,,,,,,,', 1),
(13, '1234', '10', '0,0,1,*,1,0,0,0,0,0', '0,0,1,,1,0,0,0,0,0', 1),
(14, '1234', '10', '1,*,1,0,0,0,0,1,*,1', '1,,1,0,0,0,0,1,,1', 1),
(15, '1234', '5', '0,0,1,*,1', '0,0,1,,1', 1),
(16, '1234', '3', '1,*,1', '1,,1', 1),
(17, '1234', '10', '*,*,1,1,*,1,0,0,1,*', '*,,,,,,,,,', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `players`
--

CREATE TABLE `players` (
  `Id` varchar(20) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Wins` varchar(20) NOT NULL,
  `Losses` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `players`
--

INSERT INTO `players` (`Id`, `Name`, `Password`, `Wins`, `Losses`) VALUES
('1234', 'probando', '1234', '1', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Id_Player` (`Id_Player`);

--
-- Indices de la tabla `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `fields`
--
ALTER TABLE `fields`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`Id_Player`) REFERENCES `players` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
