-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2014 a las 17:37:15
-- Versión del servidor: 5.6.14
-- Versión de PHP: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `iof_acl`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_permission`
--

CREATE TABLE IF NOT EXISTS `iof_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(45) NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  `name_esp` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `iof_permission`
--

INSERT INTO `iof_permission` (`id`, `permission_name`, `resource_id`, `name_esp`) VALUES
(1, 'index', 1, 'Inicio'),
(2, 'add', 1, 'Agregar'),
(3, 'edit', 1, 'Editar'),
(4, 'delete', 1, 'Eliminar'),
(5, 'see', 1, 'Ver'),
(6, 'index', 2, 'Inicio'),
(7, 'add', 2, 'Agregar'),
(8, 'update', 2, 'Editar'),
(9, 'delete', 2, 'Eliminar'),
(10, 'welcome', 3, 'Perfil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_resource`
--

CREATE TABLE IF NOT EXISTS `iof_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(50) NOT NULL,
  `app` varchar(100) DEFAULT NULL,
  `agroupName` varchar(100) DEFAULT NULL,
  `menutemp` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nameMenu` varchar(200) NOT NULL,
  `path` varchar(200) NOT NULL,
  `sub` int(11) NOT NULL,
  `isdisplayed` tinyint(4) NOT NULL,
  `displayedoreder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `iof_resource`
--

INSERT INTO `iof_resource` (`id`, `resource_name`, `app`, `agroupName`, `menutemp`, `name`, `nameMenu`, `path`, `sub`, `isdisplayed`, `displayedoreder`) VALUES
(1, 'Users\\Controller\\Index', 'Usuarios', 'Usuarios', 'Usuarios', 'Usuarios', 'Lista de Usuarios', 'users', 0, 1, 1),
(2, 'Permissions\\Controller\\Index', 'Permisos', 'Permisos', 'Permisos', 'Permisos', 'Lista de Permisos', 'permissions', 0, 1, 1),
(3, 'Users\\Controller\\Index', 'Sistema', 'Sistema', 'Sistema', 'Sistema', 'Mi Perfil', 'users/profile', 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_role`
--

CREATE TABLE IF NOT EXISTS `iof_role` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `iof_role`
--

INSERT INTO `iof_role` (`rid`, `role_name`, `status`) VALUES
(1, 'Administrador', 'Active'),
(2, 'Usuario', 'Active'),
(3, 'Cliente', 'Active'),
(4, 'Maestro', 'Active');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_role_permission`
--

CREATE TABLE IF NOT EXISTS `iof_role_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=259 ;

--
-- Volcado de datos para la tabla `iof_role_permission`
--

INSERT INTO `iof_role_permission` (`id`, `role_id`, `permission_id`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(17, 2, 1, 1),
(18, 2, 2, 0),
(19, 2, 3, 0),
(20, 2, 4, 0),
(21, 2, 5, 0),
(22, 0, 6, 0),
(23, 0, 7, 0),
(24, 0, 8, 0),
(25, 0, 9, 0),
(188, 1, 10, 1),
(189, 2, 10, 1),
(249, 3, 1, 1),
(250, 3, 2, 0),
(251, 3, 0, 0),
(252, 3, 4, 0),
(253, 3, 5, 1),
(254, 4, 1, 1),
(255, 4, 2, 0),
(256, 4, 3, 0),
(257, 4, 4, 0),
(258, 4, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_users`
--

CREATE TABLE IF NOT EXISTS `iof_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `iof_users`
--

INSERT INTO `iof_users` (`user_id`, `name`, `surname`, `lastname`, `email`, `phone`, `password`, `status`, `created_on`, `modified_on`) VALUES
(1, 'Erick editado', 'Garcia', 'Bravo', 'erick@example.com', 12345678, '317cea28a02172901a8da2711511b0eb', 'Y', '2014-10-08 17:19:37', '2014-11-03 15:33:05'),
(4, 'lorena', 'martinez', 'cuapio', 'l@hotmail.com', 0, '62a90ccff3fd73694bf6281bb234b09a', 'Y', '2014-10-14 22:43:51', '2014-10-14 22:43:51'),
(5, 'f', 'ewf', 'fw', 'wfew@frefre.com', 0, '7a08841c6ea880754bfccdae0a48cd84', 'Y', '2014-10-22 20:25:40', '2014-10-22 20:25:40'),
(6, 'wf', 'fw', 'frefer', 'fwfer@ghjhj.com', 0, 'e6e9dece176d3d462cf520499f3ef3d7', 'Y', '2014-10-28 16:40:44', '2014-10-28 16:40:44'),
(7, 'WFERF', 'FREFRE', 'EFER', 'FEFREFE@eefr.com', 0, '1ea752b1694cba665ecefb5a38d2422d', 'Y', '2014-10-28 16:41:01', '2014-10-28 16:41:01'),
(8, 'luis', 'martinez', 'cuapio', 'luis@hotmail.com', 0, '1a79a4d60de6718e8e5b326e338ae533', 'Y', '2014-11-03 15:42:33', '2014-11-05 21:14:01'),
(9, 'dasd', 'das', 'fasf', 'user@example.com', 0, '1a79a4d60de6718e8e5b326e338ae533', 'Y', '2014-11-03 15:45:51', '2014-11-03 15:45:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iof_user_role`
--

CREATE TABLE IF NOT EXISTS `iof_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `iof_user_role`
--

INSERT INTO `iof_user_role` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 2),
(5, 5, 2),
(6, 6, 3),
(7, 7, 3),
(8, 8, 1),
(9, 9, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tmp`
--

CREATE TABLE IF NOT EXISTS `tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `tmp`
--

INSERT INTO `tmp` (`id`, `name`) VALUES
(1, 'test 1'),
(2, 'test 2'),
(3, 'text new');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
