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
-- Dumping data for table `iof_users`
--

/*INSERT INTO `iof_users` 
(`user_id`,`privateKey`,`id_company`,`user_type`,`username`,`name`,`surname`,`lastname`,`datebirth`,`email`,`numberEmployee`,`phone`,`hash`,`status`,
`created_on`,`modified_on`,`isDetailed`,`photofilename`,`photofile`,`id_job`,`canlogin`,`id_department`,`user_principal`,`avatar`)
VALUES 
(1,NULL,1,NULL,'uno@zzz.com','Steve','Jobs','Apple','0000-00-00','uno@zzz.com',NULL,12345678,'$2y$10$dW5vQHp6ei5jb20jSU9GceNwzoK4pri9o/VBWiWZeGIt/0D6gyeJK','Y','2014-10-08 22:19:37','2014-11-15 04:38:53',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,NULL,1,NULL,'dos@zzz.com','Bill','Gates','Microsoft','0000-00-00','dos@zzz.com',NULL,0,'$2y$10$ZG9zQHp6ei5jb20jSU9GcefoUdX.fwkr5wNS4NUMknMXbqFB5Gdw2','Y','2014-10-15 03:43:51','2014-11-15 04:38:58',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,NULL,1,NULL,'tres@zzz.com','Linus','Torvalds','Linux','0000-00-00','tres@zzz.com',NULL,0,'$2y$10$dHJlc0B6enouY29tI0lPRev7sHqaWzYpCiAB9LooR8LFwUyVhpSsC','Y','2014-10-23 01:25:40','2014-11-15 04:39:17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,NULL,1,NULL,'cuatro@zzz.com','Ada','Lovelace','Ada','0000-00-00','cuatro@zzz.com',NULL,0,'$2y$10$Y3VhdHJvQHp6ei5jb20jSO/GZ42W19VTPFCedarUXN1sFuHBIzZhW','Y','2014-10-28 22:40:44','2014-11-15 04:39:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,NULL,1,NULL,'cinco@zzz.com','Marissa','Mayer','GoogleYahoo','0000-00-00','cinco@zzz.com',NULL,0,'$2y$10$Y2luY29Aenp6LmNvbSNJTuVxvUSR.VoETzlIx6vpUnyCdT9NPLf7q','Y','2014-10-28 22:41:01','2014-11-15 04:39:26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);*/

INSERT INTO `iof_users` (`user_id`, `privateKey`, `id_company`, `user_type`, `name`, `surname`, `lastname`, `datebirth`, `email`, `numberEmployee`, `rfc`, `phone`, `user_name`, `password`, `password_salt`, `status`, `created_on`, `modified_on`, `isDetailed`, `photofilename`, `photofile`, `id_job`, `canlogin`, `id_department`, `user_principal`, `avatar`) VALUES
(1, NULL, 1, NULL, 'Erick editado', 'Garcia', 'Bravo', '0000-00-00', 'erick@example.com', '13', '', 12345678, 'er1', '317cea28a02172901a8da2711511b0eb', NULL, 'Y', '2014-10-08 17:19:37', '2014-12-05 22:50:17', NULL, NULL, NULL, 1, 1, 1, NULL, NULL),
(4, NULL, 1, NULL, 'lorena', 'martinez', 'cuapio', '0000-00-00', 'l@hotmail.com', '12', '', 0, 'lor1', '62a90ccff3fd73694bf6281bb234b09a', NULL, 'Y', '2014-10-14 22:43:51', '2014-12-05 22:50:03', NULL, NULL, NULL, 2, 1, 1, 0, NULL),
(5, NULL, 1, NULL, 'f', 'ewf', 'fw', '0000-00-00', 'wfew@frefre.com', '11', '', 0, 'ffractal', '7a08841c6ea880754bfccdae0a48cd84', NULL, 'Y', '2014-10-22 20:25:40', '2014-12-05 22:50:39', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(6, NULL, 1, NULL, 'wf', 'fw', 'frefer', '0000-00-00', 'fwfer@ghjhj.com', '10', '', 0, 'wtffractal', 'e6e9dece176d3d462cf520499f3ef3d7', NULL, 'Y', '2014-10-28 16:40:44', '2014-12-05 22:50:09', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(7, NULL, 1, NULL, 'WFERF', 'FREFRE', 'EFER', '0000-00-00', 'FEFREFE@eefr.com', '9', '', 3432423, 'werfg', '1ea752b1694cba665ecefb5a38d2422d', NULL, 'Y', '2014-10-28 16:41:01', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(8, NULL, 1, NULL, 'luis', 'martinez', 'cuapio', '0000-00-00', 'luis@hotmail.com', '8', '', 3432423, 'luisHorus', '1a79a4d60de6718e8e5b326e338ae533', NULL, 'Y', '2014-11-03 15:42:33', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(9, NULL, 1, NULL, 'dasd', 'das', 'fasf', '0000-00-00', 'user@example.com', '7', '', 4324234, 'dsda', '1a79a4d60de6718e8e5b326e338ae533', NULL, 'Y', '2014-11-03 15:45:51', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(10, NULL, 1, NULL, 'ricardo', 'Chávez', 'Fernández', '0000-00-00', 'rick@iofractal.com', '1', '', 423423, 'rick', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 06:00:00', '2014-12-05 22:48:52', NULL, NULL, NULL, 1, 1, 1, NULL, NULL),
(11, NULL, 1, NULL, 'arons', 'lala', 'peres', '1996-07-15', 'maestro@iofractal.com', '4', '', 1234567, 'arons', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 18:35:25', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, 0, NULL),
(12, NULL, 1, NULL, 'luis', 'erick', 'galvan', '1993-05-18', 'erickgarcia693@gmail.com', '3', '', 423355, 'lala', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 18:52:41', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(13, NULL, 1, NULL, 'ricardo', 'cuapios', 'valdellama', '1991-08-01', 'rick.chfz@gmail.com', '2', '', 6756756, 'rickValdeza', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-13 19:01:11', '2014-12-08 18:38:56', NULL, NULL, NULL, 1, 1, 1, 0, NULL),
(14, NULL, 1, NULL, 'gabo', 'lala', 'lala', '0000-00-00', 'gabo@iofractal.com', '5', '', 57565675, 'pepe', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-14 18:44:53', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL),
(15, NULL, 1, NULL, 'alan', 'garcia', 'bellamin', '0000-00-00', 'alan@iofractal.com', '6', '', 33242342, 'jose', 'e961b2ac40aac4cc36a8bf65bca9177e', NULL, 'Y', '2014-11-14 22:43:29', '2014-12-05 22:48:52', NULL, NULL, NULL, 2, 1, 1, NULL, NULL);

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-28 12:38:24
