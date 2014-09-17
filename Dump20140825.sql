-- CREATE DATABASE  IF NOT EXISTS `ami` !40100 DEFAULT CHARACTER SET utf8 ;
-- USE `ami`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: ami
-- ------------------------------------------------------
-- Server version	5.6.19-log

-- /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
-- /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
-- /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
-- /*!40101 SET NAMES utf8 */;
-- /*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
-- /*!40103 SET TIME_ZONE='+00:00' */;
-- /*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
-- /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
-- /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `albums`
--

LOCK TABLES `albums` WRITE;
-- /*!40000 ALTER TABLE `albums` DISABLE KEYS */;
INSERT INTO `albums` VALUES (0,'Несортовано'),(1,'Основний альбом'),(2,'Порожній альбом');
/*!40000 ALTER TABLE `albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discuss`
--

DROP TABLE IF EXISTS `discuss`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discuss` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(10) unsigned NOT NULL,
  `user` varchar(45) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discuss`
--

LOCK TABLES `discuss` WRITE;
-- /*!40000 ALTER TABLE `discuss` DISABLE KEYS */;
INSERT INTO `discuss` VALUES (1,1,'13i319','Це не точні дані\r\nUPD: тепер ніби точні!','2014-08-13 15:05:28');
-- /*!40000 ALTER TABLE `discuss` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
-- /*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (1,'1_schedule_2-2014.pdf');
-- /*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(45) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `attach` varchar(45) NOT NULL DEFAULT 'none',
  `attach_id` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
-- /*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'13i319','Навчання розпочнеться 1 вересня!\r\nАле 1 вересня пар не буде!','2014-08-13 14:23:00','none','0');
-- /*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album` varchar(45) NOT NULL DEFAULT '0',
  `title` varchar(500) NOT NULL DEFAULT '',
  `file_id` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
-- /*!40000 ALTER TABLE `photos` DISABLE KEYS */;
INSERT INTO `photos` VALUES (1,'1','','Z69bafbOgW'),(2,'1','','ao42akDYq1'),(4,'0','','n1rAz4Or2G'),(5,'0','','HsCSGjF5Sf'),(6,'0','','0jQZZZGJt6'),(7,'0','','0dM8TEM1XK'),(8,'0','','NggJFOqkL8'),(9,'0','','wKTcaIquqs');
-- /*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answers` text NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
-- /*!40000 ALTER TABLE `polls` DISABLE KEYS */;
INSERT INTO `polls` VALUES (1,'Оцініть цей сайт','[\"5\",\"4\",\"3\",\"2\",\"1\"]','[[\"13i319\",\"13i313\"],[],[],[],[]]');
-- /*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` varchar(3) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `title` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'pr',
  `aud1` varchar(45) NOT NULL,
  `aud2` varchar(45) NOT NULL DEFAULT '0',
  `teacher1` varchar(45) NOT NULL,
  `teacher2` varchar(45) NOT NULL DEFAULT '0',
  `week` int(10) unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule`
--

LOCK TABLES `schedule` WRITE;
-- /*!40000 ALTER TABLE `schedule` DISABLE KEYS */;
INSERT INTO `schedule` VALUES (1,'Mon',1,'Укр. мова','pr','366','0','Вак','0',0),(2,'Mon',1,'Філософія','pr','366','0','Вак','0',1),(3,'Mon',2,'Програмування','pr','118а','0','Квасниця Галина Андріївна','Трушевський Валерій Миколайович',2),(4,'Mon',3,'Програмування','lek','439','0','Музичук Анатолій Омелянович','0',2),(5,'Tue',1,'Філософія','lek','311','0','Джунь Валерій Володимирович','0',2),(6,'Tue',2,'Осн. псих. та педагог.','lek','146','0','Краманов Олексій Владиславович','0',0),(7,'Tue',2,'Осн. псих. та педагог.','pr','367','0','Крива Марія Володимирівна','0',1),(8,'Tue',3,'Матем. аналіз','lek','111','0','Тарасюк Святослав Іванович','0',2),(9,'Wed',2,'Фізвиховання','pr','СК','0','Лисак Надія Василівна','?',2),(10,'Wed',3,'Диференц. рівняння','lek','439','0','Бугрій Олег Миколайович','0',0),(11,'Wed',3,'Іноземна мова','pr','~','0','різні','0',1),(12,'Wed',4,'Мат. лог. та теор. алг.','pr','266','0','Прядко Ольга Ярославівна','Щербина Юрій Миколайович',2),(13,'Wed',5,'Архітект. обч. систем','pr','119а','0','Галамага Л. Б.','Рикалюк Роман Євстахович',2),(14,'Thu',1,'Матем. аналіз','pr','174','0','вак?Тарасюк','0',2),(15,'Thu',2,'Мат. лог. та теор. алг.','lek','111','0','Щербина Юрій Миколайович','0',2),(16,'Thu',3,'Іноземна мова','pr','~','0','різні','0',2),(17,'Fri',2,'Фізвиховання','pr','СК','0','Лисак Надія Василівна','?',2),(18,'Fri',3,'Архітект. обч. систем','lek','111','0','Рикалюк Роман Євстахович','0',2),(19,'Fri',4,'Навч. практика','pr','270','0','Квасниця Галина Андріївна','0',2),(20,'Fri',5,'Диференц. рівняння','pr','266','0','Бугрій Олег Миколайович','0',2);
-- /*!40000 ALTER TABLE `schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
-- /*!40101 SET @saved_cs_client     = @@character_set_client */;
-- /*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `login` varchar(45) NOT NULL,
  `password` varchar(60) NOT NULL DEFAULT '$2y$10$hxgwDXCjASqCne2AxmQNv.LHTH8mxt7Z4wTLxzADIT7ATz1rkp7D2',
  `firstname` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL,
  `midname` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `skype` varchar(100) NOT NULL DEFAULT '',
  `birthday` date NOT NULL,
  `type` varchar(45) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`login`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- /*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
-- /*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('13i301','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Сергій','Бойко','Андрійович','0958673653','boyshyk@gmail.com','henzerberg','1996-06-04','user'),('13i302','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Ярослав','Боровий','Ярославович','0964498343','sharp_x@bk.ru','sharp_x2','1996-08-08','user'),('13i303','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Ірина','Вахнянин','Аркадіївна','0671428261','iravahnianyn@gmail.com','iryba0596','1996-05-11','user'),('13i304','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Андрій','Вельгош','Сергійович','0938603401','andriyvelgosh@gmail.com','','1996-09-21','user'),('13i305','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Роман-Йосип','Вовк','Юрійович','0958311369','wolf8196@gmail.com','romanvovk8196','0000-00-00','user'),('13i306','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Назарій','Галущак','Володимирович','0936202158','galnaz67@gmail.com','naz gal','1992-08-25','user'),('13i307','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Григорій','Гарбарук','Сергійович','0934391962','grishagarbaruk1995@gmail.com','elfarus','1996-09-07','user'),('13i308','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Михайло','Гузюк','Андрійович','0979827616','g.and.m@ukr.net','guzukma','0000-00-00','user'),('13i309','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Ярослав','Демчук','Андрійович','0992052490','yaroslav.demchuk@live.com','','0000-00-00','user'),('13i310','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Богдан','Денькович','Вікторович','0950589649','bogdanvlviv@gmail.com','bogdanvlviv','1996-06-27','user'),('13i311','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Роман','Дмитрів','Богданович','0633709302','romatill2@gmail.com','','0000-00-00','user'),('13i312','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Юлія','Ейнес','Русланівна','0632091420','eynesj@gmail.com','eynesj','1996-04-11','user'),('13i313','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Олександр','Зайцев','Олександрович','0934411543','olk-zytsev@gmail.com','','0000-00-00','user'),('13i314','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Лілія','Климко','Богданівна','0638378929','lilli4ka_24@mail.ru','lili-klymko','0000-00-00','user'),('13i315','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Микола','Малик','Петрович','0978516904','qwerty1234554321111221@gmail.com','','0000-00-00','user'),('13i316','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Назарій-Григорій','Мацура','Тарасович','0983322612','MNazanar@gmail.com','','0000-00-00','user'),('13i317','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Володимир','Мельник','Степанович','0678830642','volodya842@gmail.com','','0000-00-00','user'),('13i318','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Іван','Михайлюк','Степанович','0982518911','johnnyd@ukr.net','iv.mykh','1996-01-15','user'),('13i319','$2y$10$5MvT1wrtpv8yu3y3fTuTDOiiuJONWDwhxm3BK3N84UT2zXsmD2ZPG','Олег','Пилипчак','Ярославович','0671123425','liole.plo@gmail.com','liole.oleg','1996-10-02','admin'),('13i320','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Владислав','Підкович','Андрійович','0932376426','suffy17@gmail.com','suffy17','0000-00-00','user'),('13i321','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Роман','Прецель','Олегович','0634336284','pretsel.roman@yandex.ru','','0000-00-00','user'),('13i322','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Влодимир','Ривак','Ігорович','0937108462','fakedev@i.ua','','0000-00-00','user'),('13i323','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Ярина','Усач','Орестівна','0631996935','usachyaryna@ukr.net','usachyaryna','0000-00-00','user'),('13i324','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Ярослав','Хамар','Орестович','0635881552','khamaryar@gmail.com','','1995-09-22','user'),('13i325','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Марія','Хмільовська','Іванівна','0631814364','maria.khmilovska@mail.ru','','1996-03-31','user'),('13i326','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Олег','Хомин','Васильович','0637228732','karambarashka3101@mail.ru','homa-fable','1996-04-22','user'),('user','$2y$10$KVx5Z445aTZx9/aqahtCXuEIFPcodd1kP3bFSKlYP1vYRTnZF9YOC','Користувач','','','','','','0000-00-00','user');
-- /*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
-- /*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

-- /*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
-- /*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
-- /*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
-- /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
-- /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
-- /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- /*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-25 20:20:23
