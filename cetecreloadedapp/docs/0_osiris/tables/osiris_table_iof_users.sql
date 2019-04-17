CREATE DATABASE  IF NOT EXISTS `osirisACL` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `osiris`;
-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: osirisACL
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
-- Table structure for table `iof_users`
--

DROP TABLE IF EXISTS `iof_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `iof_users` (
`user_id` int(4) NOT NULL,
  `privateKey` int(10) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `user_type` varchar(7) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `datebirth` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `numberEmployee` varchar(7) DEFAULT NULL,
  `rfc` varchar(35) DEFAULT NULL,
  `phone` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `password_salt` varchar(32) DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isDetailed` tinyint(1) DEFAULT NULL,
  `photofilename` longtext,
  `photofile` longtext,
  `id_job` int(4) DEFAULT NULL,
  `canlogin` int(11) DEFAULT NULL,
  `id_department` int(11) DEFAULT NULL,
  `user_principal` int(11) DEFAULT NULL,
  `avatar` longtext
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Indices de la tabla `iof_users`
--
ALTER TABLE `iof_users`
 ADD PRIMARY KEY (`user_id`), ADD KEY `id_company` (`id_company`), ADD KEY `id_job` (`id_job`), ADD KEY `id_department` (`id_department`);

 --
-- AUTO_INCREMENT de la tabla `iof_users`
--
ALTER TABLE `iof_users`
MODIFY `user_id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

--
-- Filtros para la tabla `iof_users`
--
ALTER TABLE `iof_users`
ADD CONSTRAINT `iof_users_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `iof_users_ibfk_2` FOREIGN KEY (`id_job`) REFERENCES `job_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `iof_users_ibfk_3` FOREIGN KEY (`id_department`) REFERENCES `department` (`id_department`) ON DELETE NO ACTION ON UPDATE NO ACTION;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-02 19:31:33
