CREATE DATABASE  IF NOT EXISTS `ami` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ami`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: ami
-- ------------------------------------------------------
-- Server version	5.6.19-log

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
-- Table structure for table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_topics`
--

LOCK TABLES `forum_topics` WRITE;
/*!40000 ALTER TABLE `forum_topics` DISABLE KEYS */;
INSERT INTO `forum_topics` VALUES (1,'New Title 1','About this topic'),(2,'Another one','Some more words about this');
/*!40000 ALTER TABLE `forum_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_messages`
--

DROP TABLE IF EXISTS `forum_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL,
  `author` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_messages`
--

LOCK TABLES `forum_messages` WRITE;
/*!40000 ALTER TABLE `forum_messages` DISABLE KEYS */;
INSERT INTO `forum_messages` VALUES (1,1,'13i319','2014-09-28 22:30:00','<b>Hello</b> <i>world!</i>'),(2,1,'13i313','2014-09-28 22:33:00','<img src=\"http://assets3.parliament.uk/iv/main-large//ImageVault/Images/id_7382/scope_0/ImageVaultHandler.aspx.jpg\" alt=\"\"/><br>+ image'),(3,1,'13i319','2014-09-30 20:19:09','Текст вашого повідомлення...'),(4,1,'13i319','2014-09-30 20:23:29','Текст вашого повідомлення...<img src=\"images/upload/RiVr99KsZy.jpg\" style=\"width: 100%;\">'),(5,1,'13i319','2014-09-30 21:09:57','Ще тестове повідомлення<div>вар</div><div>ро</div><div>апо</div><div>рп</div><div>впао</div><div><br></div><div>р</div><div>оа</div>'),(6,1,'13i319','2014-09-30 21:10:42','і ще&nbsp;<div>для</div><div>&nbsp;перевірки&nbsp;</div><div>підвантаження&nbsp;</div><div>нових&nbsp;</div><div>повідомлень&nbsp;</div><div>при</div><div>&nbsp;прокручуванн&nbsp;</div><div>сторінки&nbsp;</div><div>до&nbsp;</div><div>кінця</div>'),(7,1,'13i319','2014-09-30 21:11:12','Текст вашого повідомлення...'),(8,1,'13i319','2014-09-30 21:11:13','Текст вашого повідомлення...'),(9,1,'13i319','2014-09-30 21:11:15','<br><br><br>Текст вашого повідомлення...<br><br><br><br>'),(10,1,'13i319','2014-09-30 21:13:09','<font color=\"#ff0000\">Треба&nbsp;</font><div><font color=\"#ff0000\">прокрутити&nbsp;</font></div><div><font color=\"#ff0000\">цю</font></div><div><font color=\"#ff0000\">&nbsp;сторінку</font></div><div><font color=\"#ff0000\">&nbsp;до</font></div><div><font color=\"#ff0000\">&nbsp;кінця щоб побачити більше повідомлень</font></div><div>При використанні завантажувати повідомлення порціями по 10 а не по 2.</div><div>Для цього змінити&nbsp;<i><b>window.loadLimit</b></i></div>'),(11,2,'13i319','2014-09-30 23:10:52','І ще одне тут'),(12,2,'13i319','2014-09-30 23:19:37','Методичка з архітектури:&nbsp;<a class=\"download_btn\" href=\"https://drive.google.com/uc?export=download&amp;id=0B2f03GVdvXf2WXRZREwxV3E0ZFk\">&nbsp;</a>(тест додавання спільних ресурсів з GoogleDrive)<div>Якщо додати посилання на публікацію документа у форматі GoogleDocs то буде відображатися не посилання на завантаження, а документ в iframe</div>');
/*!40000 ALTER TABLE `forum_messages` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-30 23:38:40
