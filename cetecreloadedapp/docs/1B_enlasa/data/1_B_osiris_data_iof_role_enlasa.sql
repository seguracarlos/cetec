CREATE DATABASE  IF NOT EXISTS `osiris` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `osiris`;
-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: osiris
-- ------------------------------------------------------
-- Server version	5.5.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `iof_role`
--

/*INSERT INTO `iof_role` 
(`rid`,`role_name`,`type_user`,`status`)
VALUES 
(1, 'SuperUsuario', 0, 'Active'),
(2, 'Administrador', 0, 'Active'),
(3, 'Usuario', 0, 'Active'),
(4, 'Empleado', 0, 'Active'),
(5, 'Cliente', 1, 'Active'),
(6, 'Proveedor', 1, 'Active'),
(7, 'Vendedor', 0, 'Active'),
(8, 'Compras', 0, 'Active'),
(9, 'Maestro', 1,'Active');*/

INSERT INTO `iof_role` (`rid`, `role_name`, `type_user`, `status`, `Active`) VALUES
(1, 'SuperUsuario', 0, 'Active', 1),
(2, 'Administrador', 0, 'Active', 1),
(3, 'Usuario', 0, 'Active', 1),
(4, 'Empleado', 0, 'Active', 1),
(5, 'Cliente', 1, 'Active', 1),
(6, 'Proveedor', 1, 'Active', 1),
(7, 'Vendedor', 0, 'Active', 1),
(8, 'Compras', 0, 'Active', 1),
(9, 'jslkjad', NULL, 'Active', NULL);


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 12:38:23
