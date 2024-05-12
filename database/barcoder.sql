-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: barcoder
-- ------------------------------------------------------
-- Server version	8.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barcodes`
--

DROP TABLE IF EXISTS `barcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barcodes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL DEFAULT '',
  `count` int NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `device_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barcodes`
--

LOCK TABLES `barcodes` WRITE;
/*!40000 ALTER TABLE `barcodes` DISABLE KEYS */;
INSERT INTO `barcodes` VALUES (1,'1234567890',1,'2024-04-28 13:14:11',0),(2,'1234567890',1,'2024-04-28 13:14:21',0),(3,'1234567890',1,'2024-04-28 13:14:22',0),(4,'1234567890',1,'2024-04-28 13:14:22',0),(5,'1234567890',1,'2024-04-28 13:14:42',0),(6,'1234567890',1,'2024-04-28 13:14:43',0),(7,'1234567890',1,'2024-04-28 13:14:49',0),(8,'1234567890',1,'2024-04-28 13:14:49',0),(9,'1234567890',1,'2024-04-28 13:14:50',0),(10,'1234567890',1,'2024-04-28 13:14:50',0),(11,'1234567890',1,'2024-04-28 13:14:50',0),(12,'1234567890',1,'2024-04-28 13:14:51',0),(13,'1234567890',1,'2024-04-28 13:15:21',0),(14,'1234567890',1,'2024-04-28 13:15:33',0),(15,'1234567890',1,'2024-04-28 13:15:34',0),(16,'1234567890',1,'2024-04-28 13:15:34',0),(17,'1234567890',1,'2024-04-28 13:36:11',0),(18,'1234567890',1,'2024-04-28 13:36:12',0),(19,'1234567890',1,'2024-04-28 13:36:13',0),(20,'1234567890',1,'2024-04-28 13:36:16',0),(21,'code1',1,'2024-04-29 12:53:00',1),(22,'code2',11,'2024-04-29 12:53:17',1),(23,'hello',1,'2024-05-11 11:39:02',1),(24,'asdasd',1,'2024-05-11 11:49:38',1),(25,'hello',1,'2024-05-11 11:58:59',1),(26,'hello',1,'2024-05-11 12:00:23',1),(27,'hello',1,'2024-05-11 12:01:22',1),(28,'1111',1,'2024-05-11 12:01:45',1),(29,'GGGGGGGGG',1,'2024-05-11 12:03:16',1),(30,'hello',1,'2024-05-11 12:57:12',1),(31,'hi',1,'2024-05-11 13:04:33',1),(32,'hi',1,'2024-05-11 13:04:34',1),(33,'zzzzzz',1,'2024-05-11 13:04:35',1),(34,'asd',1,'2024-05-11 13:04:44',1),(35,'dddddd',1,'2024-05-11 13:05:24',1),(36,'ddd',1,'2024-05-11 13:06:01',1),(37,'dddddd',1,'2024-05-11 13:06:36',1),(38,'!!!!!!!',1,'2024-05-11 13:08:52',1),(39,'33333333',1,'2024-05-11 13:09:09',1),(40,'dddddddddddd',1,'2024-05-11 13:09:21',1),(41,'d',1,'2024-05-11 13:10:48',1),(42,'eeeeeeeee',1,'2024-05-11 13:12:07',1),(43,'ddddd',1,'2024-05-11 13:12:32',1),(44,'GGG',1,'2024-05-11 13:13:28',1),(45,'!',1,'2024-05-11 13:13:37',1),(46,'G',1,'2024-05-11 13:15:19',1),(47,'Gg',1,'2024-05-11 13:15:51',1),(48,'sdsdasd',1,'2024-05-11 13:16:42',1),(49,'dddddddd',1,'2024-05-11 13:17:00',1),(50,'dddddddddddd',1,'2024-05-11 13:18:10',1),(51,'GGGGGGGGg',1,'2024-05-11 13:20:06',1),(52,'zzzzzzzzzz',1,'2024-05-11 13:56:27',1),(53,'yyyyyyyy',1,'2024-05-11 13:56:40',1),(54,'zxczxc',1,'2024-05-11 13:57:29',1),(55,'asdasdasd',1,'2024-05-11 13:57:35',1),(56,'asdasd',55,'2024-05-11 13:57:40',1),(57,'n,vcnm,sfd',1,'2024-05-11 13:59:22',1),(58,'fuckne',1,'2024-05-11 14:00:44',1),(59,'barcode',1,'2024-05-11 14:02:33',1),(60,'barcideide',1,'2024-05-11 14:02:42',1),(61,'1',1,'2024-05-11 14:02:51',1),(62,'1',1,'2024-05-11 14:02:52',1),(63,'1',1,'2024-05-11 14:02:52',1),(64,'1',1,'2024-05-11 14:02:52',1),(65,'1',1,'2024-05-11 14:02:52',1),(66,'1',1,'2024-05-11 14:02:55',1),(67,'1',1,'2024-05-11 14:02:59',1),(68,'2',1,'2024-05-11 14:02:59',1),(69,'3',1,'2024-05-11 14:03:00',1),(70,'4',1,'2024-05-11 14:03:00',1),(71,'5',1,'2024-05-11 14:03:00',1),(72,'6',1,'2024-05-11 14:03:01',1),(73,'7',1,'2024-05-11 14:03:01',1),(74,'8',1,'2024-05-11 14:03:01',1),(75,'9',1,'2024-05-11 14:03:02',1),(76,'1',1,'2024-05-11 14:04:05',1),(77,'2',1,'2024-05-11 14:04:05',1),(78,'3',1,'2024-05-11 14:04:05',1),(79,'4',1,'2024-05-11 14:04:06',1),(80,'5',1,'2024-05-11 14:04:06',1),(81,'6',1,'2024-05-11 14:04:06',1),(82,'7',1,'2024-05-11 14:04:07',1),(83,'8',1,'2024-05-11 14:04:07',1),(84,'9',1,'2024-05-11 14:04:08',1),(85,'1',1,'2024-05-11 14:04:51',1),(86,'2',1,'2024-05-11 14:04:51',1),(87,'3',1,'2024-05-11 14:04:51',1),(88,'4',1,'2024-05-11 14:04:52',1),(89,'5',1,'2024-05-11 14:04:52',1),(90,'67',1,'2024-05-11 14:04:52',1),(91,'8',1,'2024-05-11 14:04:52',1),(92,'9',1,'2024-05-11 14:04:53',1),(93,'',1,'2024-05-11 14:04:53',1),(94,'23',1,'2024-05-11 14:04:54',1),(95,'32',1,'2024-05-11 14:04:54',1),(96,'23',1,'2024-05-11 14:04:54',1),(97,'23',1,'2024-05-11 14:04:55',1),(98,'32',1,'2024-05-11 14:04:55',1),(99,'2332',1,'2024-05-11 14:04:55',1),(100,'23',1,'2024-05-11 14:04:55',1),(101,'32',1,'2024-05-11 14:04:56',1),(102,'ddd',1,'2024-05-11 14:08:42',1),(103,'zzz',1,'2024-05-11 14:08:48',1),(104,'bbbc',1,'2024-05-11 14:09:13',1),(105,'qq',1,'2024-05-11 14:10:01',1),(106,'qqv',1,'2024-05-11 14:11:04',1),(107,'vvz',1,'2024-05-11 14:11:56',1),(108,'zzv',1,'2024-05-11 14:12:07',1),(109,'ggg',1,'2024-05-11 14:12:51',1),(110,'ggbb',1,'2024-05-11 14:13:02',1),(111,'nnbb',1,'2024-05-11 14:14:03',1),(112,'bbvv',1,'2024-05-11 14:14:16',1),(113,'test',1,'2024-05-12 10:57:40',1),(114,'hello',1,'2024-05-12 10:58:19',1),(115,'hello',1,'2024-05-12 13:48:52',1),(116,'barcide',1,'2024-05-12 13:49:28',1);
/*!40000 ALTER TABLE `barcodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `host` varchar(255) NOT NULL DEFAULT '%',
  `order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'mindeo','%',0);
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'клиент 1','token');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-12 17:17:02
