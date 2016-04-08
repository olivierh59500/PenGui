-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: PenGui
-- ------------------------------------------------------
-- Server version	5.5.47-0+deb8u1

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
-- Table structure for table `nmap`
--

DROP TABLE IF EXISTS `nmap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `user_input_command` varchar(45) DEFAULT NULL,
  `nmap_log_returned` longtext,
  `nmap_log_simplified` longtext,
  `ip_address` varchar(12) DEFAULT NULL,
  `task_status` varchar(9) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nmap`
--

LOCK TABLES `nmap` WRITE;
/*!40000 ALTER TABLE `nmap` DISABLE KEYS */;
INSERT INTO `nmap` VALUES (1,'mojtaba_amiri@hotmail.co.uk','nmap -sV 192.168.0.9','Starting Nmap 6.47 ( http://nmap.org ) at 2016-04-06 23:43 BST Nmap scan report for 192.168.0.9 Host is up (0.000010s latency). Not shown: 997 closed ports PORT    STATE SERVICE VERSION 22/tcp  open  ssh     OpenSSH 6.7p1 Debian 5+deb8u1 (protocol 2.0) 80/tcp  open  http    Apache httpd 2.4.10 ((Debian)) 111/tcp open  rpcbind 2-4 (RPC #100000) Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel Service detection performed. Please report any incorrect results at http://nmap.org/submit/ . Nmap done: 1 IP address (1 host up) scanned in 8.65 seconds',NULL,'::1','completed','2016-04-06 23:00:33'),(2,'mojtaba_amiri@hotmail.co.uk','nmap -sV 192.168.0.9',NULL,NULL,'::1','pending','2016-04-04 23:14:49'),(3,'mojtaba_amiri@hotmail.co.uk','nmap -sS 192.168.0.9',NULL,NULL,'::1','pending','2016-04-04 23:18:21'),(4,'mojtaba_amiri@hotmail.co.uk','nmap -sT 192.168.0.9',NULL,NULL,'::1','pending','2016-04-04 23:21:08'),(5,'mojtaba_amiri@hotmail.co.uk','nmap -sV 192.168.0.9',NULL,NULL,'0','pending','2016-04-04 23:23:07'),(6,'mojtaba_amiri@hotmail.co.uk','nmap -sS 192.168.0.9',NULL,NULL,NULL,'pending','2016-04-04 23:24:35'),(7,'matthew.slegg@poo.com','nmap -sv youtube.com',NULL,NULL,'192.168.0.30','pending','2016-04-05 10:38:46'),(8,'matthew.slegg@poo.com','nmap -sv youtube.com',NULL,NULL,'192.168.0.30','pending','2016-04-05 10:39:02'),(9,'mojtaba_amiri@hotmail.co.uk','bob',NULL,NULL,'::1','pending','2016-04-06 21:34:04'),(10,'mojtaba_amiri@hotmail.co.uk','hello',NULL,NULL,'::1','pending','2016-04-06 21:39:11'),(11,'mojtaba_amiri@hotmail.co.uk','testingsdfdf',NULL,NULL,'::1','pending','2016-04-06 23:30:17');
/*!40000 ALTER TABLE `nmap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_details`
--

DROP TABLE IF EXISTS `personal_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_details` (
  `First_Name` varchar(20) DEFAULT NULL,
  `Last_Name` varchar(20) DEFAULT NULL,
  `Email` varchar(60) NOT NULL,
  `Password` char(128) DEFAULT NULL,
  PRIMARY KEY (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_details`
--

LOCK TABLES `personal_details` WRITE;
/*!40000 ALTER TABLE `personal_details` DISABLE KEYS */;
INSERT INTO `personal_details` VALUES ('Matt','Slegg','matt@slegg.com','$2y$14$xLty/3wWAxQIiBgy8Qm8i.jdYDwDeKPNU4OFl7REQksnI.C.87/YS'),('Matthew','Slegg','matthew.slegg@poo.com','$2y$14$PD6eV6nmOqPEM00hOlWpKuNvv7BWq1kM3KtFzCFhAIdjJWjOLrh/S'),('mojtaba','amiri','mojtaba_amiri@gmail.com','$2y$14$zpIm.0E.ATCuckj3qrUWQ.QpYKb7WMgkFZMlQ3HV5WvIN9QWUDxV.'),('mojtaba','amiri','mojtaba_amiri@hotmail.co.uk','$2y$14$A5KG/fv6bsuFO6gV9lZxxu3TdUShMuWOy9TaQ6sMyGtpX/iruzXv6'),('mojtaba','amiri','mojtaba_amiri@hotmail.com','$2y$14$Aw6.nLo4lwbiDdyK/zsp..9Pw4wDNnOX5Ab4u1DKgeHCwHzKoKmAm'),('Oliver','Loveless','oli@loveless.com','$2y$14$ipocu5F9los4Qy3UYmg59eHcE9nE0/TY0fCf5idocOvkc1uoih6Ha');
/*!40000 ALTER TABLE `personal_details` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-08  1:12:31
