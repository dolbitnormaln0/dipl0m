-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: my_db_test1
-- ------------------------------------------------------
-- Server version	8.0.41

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
-- Table structure for table `DOLJ`
--

DROP TABLE IF EXISTS `DOLJ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DOLJ` (
  `doljid` int NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`doljid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOLJ`
--

LOCK TABLES `DOLJ` WRITE;
/*!40000 ALTER TABLE `DOLJ` DISABLE KEYS */;
INSERT INTO `DOLJ` VALUES (1,'кассир'),(2,'продавец-консультант'),(3,'администратор');
/*!40000 ALTER TABLE `DOLJ` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_categories`
--

DROP TABLE IF EXISTS `attribute_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_categories` (
  `attribute_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`attribute_id`,`category_id`),
  KEY `fk_cat` (`category_id`),
  CONSTRAINT `fk_attr` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cat` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_categories`
--

LOCK TABLES `attribute_categories` WRITE;
/*!40000 ALTER TABLE `attribute_categories` DISABLE KEYS */;
INSERT INTO `attribute_categories` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(1,2),(2,2),(3,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(1,3),(2,3),(3,3),(16,3),(17,3),(18,3),(19,3),(20,3),(21,3),(1,4),(2,4),(3,4),(4,4),(5,4),(27,4),(28,4),(29,4);
/*!40000 ALTER TABLE `attribute_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_values` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `value_text` text,
  `value_number` decimal(10,2) DEFAULT NULL,
  `value_boolean` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_attribute_values_product_id` (`product_id`),
  KEY `idx_attribute_values_attribute_id` (`attribute_id`),
  KEY `idx_attribute_values_composite` (`attribute_id`,`product_id`),
  KEY `idx_attribute_values_number` (`value_number`),
  KEY `idx_attribute_values_text` (`value_text`(255)),
  KEY `idx_attribute_values_boolean` (`value_boolean`),
  CONSTRAINT `attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`),
  CONSTRAINT `attribute_values_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tovar` (`tovar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_values`
--

LOCK TABLES `attribute_values` WRITE;
/*!40000 ALTER TABLE `attribute_values` DISABLE KEYS */;
INSERT INTO `attribute_values` VALUES (1,1,1,'Bosch',NULL,NULL),(2,2,1,'KGN39VL35R',NULL,NULL),(3,3,1,'Германия',NULL,NULL),(4,4,1,'No Frost',NULL,NULL),(5,5,1,NULL,344.00,NULL),(6,6,1,'Электронное',NULL,NULL),(7,7,1,NULL,2.00,NULL),(8,8,1,NULL,85.00,NULL),(9,9,1,NULL,NULL,1),(10,1,3,'LG',NULL,NULL),(11,2,3,'GA-B459SLGL',NULL,NULL),(12,3,3,'Корея',NULL,NULL),(13,4,3,'No Frost',NULL,NULL),(14,5,3,NULL,419.00,NULL),(15,6,3,'Электронное',NULL,NULL),(16,7,3,NULL,2.00,NULL),(17,8,3,NULL,92.00,NULL),(18,9,3,NULL,NULL,1),(19,1,5,'Samsung',NULL,NULL),(20,2,5,'RB37A502BSA',NULL,NULL),(21,3,5,'Корея',NULL,NULL),(22,4,5,'No Frost',NULL,NULL),(23,5,5,NULL,367.00,NULL),(24,6,5,'Электронное',NULL,NULL),(25,7,5,NULL,2.00,NULL),(26,8,5,NULL,78.00,NULL),(27,9,5,NULL,NULL,1),(28,1,9,'Haier',NULL,NULL),(29,2,9,'C2F636CXRG',NULL,NULL),(30,3,9,'Китай',NULL,NULL),(31,4,9,'No Frost',NULL,NULL),(32,5,9,NULL,316.00,NULL),(33,6,9,'Электронное',NULL,NULL),(34,7,9,NULL,2.00,NULL),(35,8,9,NULL,87.00,NULL),(36,9,9,NULL,NULL,1),(37,1,13,'Beko',NULL,NULL),(38,2,13,'RCNK 335E20 VS',NULL,NULL),(39,3,13,'Турция',NULL,NULL),(40,4,13,'No Frost',NULL,NULL),(41,5,13,NULL,335.00,NULL),(42,6,13,'Электронное',NULL,NULL),(43,7,13,NULL,2.00,NULL),(44,8,13,NULL,82.00,NULL),(45,9,13,NULL,NULL,1),(46,1,17,'Electrolux',NULL,NULL),(47,2,17,'ENB38700OW',NULL,NULL),(48,3,17,'Швеция',NULL,NULL),(49,4,17,'No Frost',NULL,NULL),(50,5,17,NULL,387.00,NULL),(51,6,17,'Электронное',NULL,NULL),(52,7,17,NULL,3.00,NULL),(53,8,17,NULL,94.00,NULL),(54,9,17,NULL,NULL,1),(55,1,21,'Indesit',NULL,NULL),(56,2,21,'ITS 4200 W',NULL,NULL),(57,3,21,'Италия',NULL,NULL),(58,4,21,'Капельная система',NULL,NULL),(59,5,21,NULL,420.00,NULL),(60,6,21,'Электронное',NULL,NULL),(61,7,21,NULL,2.00,NULL),(62,8,21,NULL,89.00,NULL),(63,9,21,NULL,NULL,0),(64,1,25,'Whirlpool',NULL,NULL),(65,2,25,'W9 941K FX',NULL,NULL),(66,3,25,'США',NULL,NULL),(67,4,25,'No Frost',NULL,NULL),(68,5,25,NULL,394.00,NULL),(69,6,25,'Электронное',NULL,NULL),(70,7,25,NULL,2.00,NULL),(71,8,25,NULL,91.00,NULL),(72,9,25,NULL,NULL,1),(73,1,2,'Samsung',NULL,NULL),(74,2,2,'WW80TA046AE',NULL,NULL),(75,3,2,'Корея',NULL,NULL),(76,10,2,'Эко-пузырь',NULL,NULL),(77,11,2,'Фронтальная',NULL,NULL),(78,12,2,'A+++',NULL,NULL),(79,13,2,NULL,8.00,NULL),(80,14,2,NULL,NULL,1),(81,15,2,NULL,NULL,1),(82,1,7,'LG',NULL,NULL),(83,2,7,'F4J6TM0W',NULL,NULL),(84,3,7,'Корея',NULL,NULL),(85,10,7,'Прямой привод',NULL,NULL),(86,11,7,'Фронтальная',NULL,NULL),(87,12,7,'A++',NULL,NULL),(88,13,7,NULL,9.00,NULL),(89,14,7,NULL,NULL,1),(90,15,7,NULL,NULL,1),(91,1,11,'Bosch',NULL,NULL),(92,2,11,'WAT28441',NULL,NULL),(93,3,11,'Германия',NULL,NULL),(94,10,11,'Активная вода',NULL,NULL),(95,11,11,'Фронтальная',NULL,NULL),(96,12,11,'A++',NULL,NULL),(97,13,11,NULL,7.00,NULL),(98,14,11,NULL,NULL,1),(99,15,11,NULL,NULL,1),(100,1,15,'Philips',NULL,NULL),(101,2,15,'PerfectCare 7000',NULL,NULL),(102,3,15,'Нидерланды',NULL,NULL),(103,10,15,'Паровой уход',NULL,NULL),(104,11,15,'Фронтальная',NULL,NULL),(105,12,15,'A+++',NULL,NULL),(106,13,15,NULL,9.00,NULL),(107,14,15,NULL,NULL,1),(108,15,15,NULL,NULL,1),(109,1,19,'Beko',NULL,NULL),(110,2,19,'WRE 6512 BWW',NULL,NULL),(111,3,19,'Турция',NULL,NULL),(112,10,19,'Быстрая стирка',NULL,NULL),(113,11,19,'Фронтальная',NULL,NULL),(114,12,19,'A+',NULL,NULL),(115,13,19,NULL,6.00,NULL),(116,14,19,NULL,NULL,1),(117,15,19,NULL,NULL,1),(118,1,23,'Electrolux',NULL,NULL),(119,2,23,'EW6S404BEW',NULL,NULL),(120,3,23,'Швеция',NULL,NULL),(121,10,23,'Сенсорная стирка',NULL,NULL),(122,11,23,'Фронтальная',NULL,NULL),(123,12,23,'A++',NULL,NULL),(124,13,23,NULL,6.00,NULL),(125,14,23,NULL,NULL,1),(126,15,23,NULL,NULL,1),(127,1,4,'LG',NULL,NULL),(128,2,4,'OLED55C14LB',NULL,NULL),(129,3,4,'Корея',NULL,NULL),(130,16,4,NULL,55.00,NULL),(131,17,4,NULL,3840.00,NULL),(132,18,4,NULL,120.00,NULL),(133,19,4,NULL,NULL,1),(134,20,4,NULL,NULL,1),(135,21,4,NULL,150.00,NULL),(136,1,8,'Samsung',NULL,NULL),(137,2,8,'QE55QN90A',NULL,NULL),(138,3,8,'Корея',NULL,NULL),(139,16,8,NULL,55.00,NULL),(140,17,8,NULL,3840.00,NULL),(141,18,8,NULL,144.00,NULL),(142,19,8,NULL,NULL,1),(143,20,8,NULL,NULL,1),(144,21,8,NULL,180.00,NULL),(145,1,12,'Philips',NULL,NULL),(146,2,12,'55OLED806',NULL,NULL),(147,3,12,'Нидерланды',NULL,NULL),(148,16,12,NULL,55.00,NULL),(149,17,12,NULL,3840.00,NULL),(150,18,12,NULL,120.00,NULL),(151,19,12,NULL,NULL,1),(152,20,12,NULL,NULL,1),(153,21,12,NULL,145.00,NULL),(154,1,16,'Sony',NULL,NULL),(155,2,16,'XR-55A80J',NULL,NULL),(156,3,16,'Япония',NULL,NULL),(157,16,16,NULL,55.00,NULL),(158,17,16,NULL,3840.00,NULL),(159,18,16,NULL,120.00,NULL),(160,19,16,NULL,NULL,1),(161,20,16,NULL,NULL,1),(162,21,16,NULL,160.00,NULL),(163,1,20,'TCL',NULL,NULL),(164,2,20,'55C825',NULL,NULL),(165,3,20,'Китай',NULL,NULL),(166,16,20,NULL,55.00,NULL),(167,17,20,NULL,3840.00,NULL),(168,18,20,NULL,120.00,NULL),(169,19,20,NULL,NULL,1),(170,20,20,NULL,NULL,1),(171,21,20,NULL,130.00,NULL),(172,1,24,'Xiaomi',NULL,NULL),(173,2,24,'Mi TV Q1 55',NULL,NULL),(174,3,24,'Китай',NULL,NULL),(175,16,24,NULL,55.00,NULL),(176,17,24,NULL,3840.00,NULL),(177,18,24,NULL,120.00,NULL),(178,19,24,NULL,NULL,1),(179,20,24,NULL,NULL,1),(180,21,24,NULL,140.00,NULL),(181,1,6,'Philips',NULL,NULL),(182,2,6,'PowerPro Compact',NULL,NULL),(183,3,6,'Нидерланды',NULL,NULL),(184,4,6,'Циклонный',NULL,NULL),(185,5,6,NULL,650.00,NULL),(186,27,6,NULL,78.00,NULL),(187,28,6,NULL,7.00,NULL),(188,29,6,'HEPA',NULL,NULL),(189,1,10,'Dyson',NULL,NULL),(190,2,10,'V11 Absolute',NULL,NULL),(191,3,10,'Великобритания',NULL,NULL),(192,4,10,'Беспроводной',NULL,NULL),(193,5,10,NULL,185.00,NULL),(194,27,10,NULL,72.00,NULL),(195,28,10,NULL,12.00,NULL),(196,29,10,'HEPA',NULL,NULL),(197,1,14,'Bosch',NULL,NULL),(198,2,14,'BGS7PET',NULL,NULL),(199,3,14,'Германия',NULL,NULL),(200,4,14,'Мешковый',NULL,NULL),(201,5,14,NULL,700.00,NULL),(202,27,14,NULL,80.00,NULL),(203,28,14,NULL,8.00,NULL),(204,29,14,'AquaStop',NULL,NULL),(205,1,18,'Samsung',NULL,NULL),(206,2,18,'Jet 90 Complete',NULL,NULL),(207,3,18,'Корея',NULL,NULL),(208,4,18,'Беспроводной',NULL,NULL),(209,5,18,NULL,200.00,NULL),(210,27,18,NULL,75.00,NULL),(211,28,18,NULL,20.00,NULL),(212,29,18,'HEPA',NULL,NULL),(213,1,22,'Thomas',NULL,NULL),(214,2,22,'DryBox Plus',NULL,NULL),(215,3,22,'Германия',NULL,NULL),(216,4,22,'Аквафильтр',NULL,NULL),(217,5,22,NULL,850.00,NULL),(218,27,22,NULL,82.00,NULL),(219,28,22,NULL,10.00,NULL),(220,29,22,'AquaStop',NULL,NULL),(221,1,31,'Karcher',NULL,NULL),(222,2,31,'WD6 Premium',NULL,NULL),(223,3,31,'Германия',NULL,NULL),(224,4,31,'Мойка-пылесос',NULL,NULL),(225,5,31,NULL,1200.00,NULL),(226,27,31,NULL,85.00,NULL),(227,28,31,NULL,8.00,NULL),(228,29,31,'AquaStop',NULL,NULL),(229,1,32,'Rowenta',NULL,NULL),(230,2,32,'RH9287WO',NULL,NULL),(231,3,32,'Франция',NULL,NULL),(232,4,32,'Циклонный',NULL,NULL),(233,5,32,NULL,750.00,NULL),(234,27,32,NULL,79.00,NULL),(235,28,32,NULL,7.00,NULL),(236,29,32,'HEPA',NULL,NULL),(237,1,33,'Miele',NULL,NULL),(238,2,33,'Complete C3',NULL,NULL),(239,3,33,'Германия',NULL,NULL),(240,4,33,'Мешковый',NULL,NULL),(241,5,33,NULL,900.00,NULL),(242,27,33,NULL,77.00,NULL),(243,28,33,NULL,9.00,NULL),(244,29,33,'HEPA',NULL,NULL),(245,1,34,'Black+Decker',NULL,NULL),(246,2,34,'Dustbuster',NULL,NULL),(247,3,34,'США',NULL,NULL),(248,4,34,'Ручной',NULL,NULL),(249,5,34,NULL,18.00,NULL),(250,27,34,NULL,65.00,NULL),(253,1,35,'Zelmer',NULL,NULL),(254,2,35,'919.0 ST',NULL,NULL),(255,3,35,'Польша',NULL,NULL),(256,4,35,'Циклонный',NULL,NULL),(257,5,35,NULL,650.00,NULL),(258,27,35,NULL,76.00,NULL),(259,28,35,NULL,6.00,NULL),(260,29,35,'HEPA',NULL,NULL),(261,1,34,'Black+Decker',NULL,NULL),(262,2,34,'Dustbuster',NULL,NULL),(263,3,34,'США',NULL,NULL),(264,4,34,'Ручной',NULL,NULL),(265,5,34,NULL,18.00,NULL),(266,27,34,NULL,65.00,NULL),(267,28,34,NULL,6.00,NULL),(268,29,34,'HEPA',NULL,NULL);
/*!40000 ALTER TABLE `attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('text','number','boolean') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attributes`
--

LOCK TABLES `attributes` WRITE;
/*!40000 ALTER TABLE `attributes` DISABLE KEYS */;
INSERT INTO `attributes` VALUES (1,'Бренд','text'),(2,'Модель','text'),(3,'Страна производитель','text'),(4,'Тип','text'),(5,'Объем (л)','number'),(6,'Тип управления','text'),(7,'Количество программ','number'),(8,'Вес (кг)','number'),(9,'No Frost','boolean'),(10,'Технология стирки','text'),(11,'Тип загрузки','text'),(12,'Класс энергопотребления','text'),(13,'Макс. загрузка (кг)','number'),(14,'Защита от протечек','boolean'),(15,'Защита от детей','boolean'),(16,'Диагональ (дюймы)','number'),(17,'Разрешение','number'),(18,'Частота обновления (Гц)','number'),(19,'Smart TV','boolean'),(20,'HDR','boolean'),(21,'Мощность (Вт)','number'),(22,'Тип пылесборника','text'),(23,'Технология экрана','text'),(24,'Диагональ экрана','number'),(25,'Тип матрицы','text'),(26,'Частота обновления','number'),(27,'Уровень шума (дБ)','number'),(28,'Длина шнура (м)','number'),(29,'Фильтры','text');
/*!40000 ALTER TABLE `attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brend`
--

DROP TABLE IF EXISTS `brend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brend` (
  `brend_id` int NOT NULL,
  `brend_name` varchar(50) NOT NULL,
  PRIMARY KEY (`brend_id`),
  KEY `idx_brend_name` (`brend_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brend`
--

LOCK TABLES `brend` WRITE;
/*!40000 ALTER TABLE `brend` DISABLE KEYS */;
INSERT INTO `brend` VALUES (9,'Beko'),(1,'Bosch'),(7,'Electrolux'),(8,'Haier'),(5,'Indesit'),(3,'LG'),(4,'Philips'),(2,'Samsung'),(6,'Whirlpool');
/*!40000 ALTER TABLE `brend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `idx_category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (4,'Пылесосы'),(2,'Стиральные машины'),(3,'Телевизоры'),(1,'Холодильники');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `commentcontent` varchar(250) NOT NULL,
  `stars` int NOT NULL,
  `user_id` int unsigned NOT NULL,
  `tovar_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commentcontent` (`commentcontent`),
  KEY `user_id` (`user_id`),
  KEY `tovar_id` (`tovar_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`tovar_id`) REFERENCES `tovar` (`tovar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,'Отличный товар, всем рекомендую!',5,1,1),(4,'Качество на высоте, но дороговато',4,2,1),(5,'Пришел с небольшим дефектом',3,3,1),(6,'Лучшее что я покупал в этом году!',5,4,1),(7,'Не оправдал ожиданий',2,5,1),(8,'Нормальный товар за свои деньги',4,1,2),(9,'Быстро сломался',1,3,2),(10,'Работает как надо',5,5,2),(11,'Достойный продукт',4,2,4),(12,'Не самый лучший в линейке',3,4,4),(13,'Идеальное соотношение цены и качества',5,1,5),(14,'Могло быть и лучше',3,2,5),(15,'Полный восторг!',5,3,5),(16,'Не впечатлило',2,4,5),(17,'Советую к покупке',4,5,5),(18,'Средненько, ничего особенного',3,3,6),(19,'Отличное приобретение',5,1,8),(20,'Не соответствует описанию',2,5,8),(21,'Хороший базовый вариант',4,2,10),(22,'Лучше поискать альтернативы',3,3,10),(23,'Доволен покупкой',4,4,10),(24,'УЖАС!!!!!!!!!!!!!!!!!!!! ВЗОРВАЛСЯ',3,1,35),(25,'ГЛЕБ КРАСНОВ ИЗ УЛЬЯНЫ ОЦЕНИЛ ЭТОТ ЕБЕЙЩИЙ ДАСТЕР НА 5+',5,1,34),(26,'ГЛЕБ ПИЗДАБОЛ!!! ДАТСЕР ПОЛНАЯ ХУЙНЯ',1,2,34),(27,'да норм дастер че:)',5,2,34);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tovar` (`tovar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,5,1),(2,1,12,2),(3,2,3,3),(4,2,15,1),(5,2,22,2),(6,3,8,1),(7,7,1,1),(8,1,35,1),(9,1,31,1),(12,8,35,1),(13,8,31,1),(14,8,35,1),(15,8,31,1);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `total_price` int NOT NULL,
  `created_at` datetime NOT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,12500,'2023-10-01 14:30:00',1),(2,1,32000,'2023-10-05 09:15:00',1),(3,1,8500,'2023-10-10 18:45:00',1),(7,1,1,'2023-10-01 14:30:00',0),(8,1,508,'2025-05-20 15:23:14',0);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postavki`
--

DROP TABLE IF EXISTS `postavki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postavki` (
  `postavka_id` int NOT NULL AUTO_INCREMENT,
  `postavshik_id` int NOT NULL,
  `data_postavki` datetime DEFAULT CURRENT_TIMESTAMP,
  `nomer_postavki` varchar(50) DEFAULT NULL,
  `status_postavki` tinyint DEFAULT '0',
  PRIMARY KEY (`postavka_id`),
  UNIQUE KEY `nomer_postavki` (`nomer_postavki`),
  KEY `postavshik_id` (`postavshik_id`),
  CONSTRAINT `postavki_ibfk_1` FOREIGN KEY (`postavshik_id`) REFERENCES `postavshiki` (`postavshik_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postavki`
--

LOCK TABLES `postavki` WRITE;
/*!40000 ALTER TABLE `postavki` DISABLE KEYS */;
/*!40000 ALTER TABLE `postavki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postavki_tovary`
--

DROP TABLE IF EXISTS `postavki_tovary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postavki_tovary` (
  `zapis_id` int NOT NULL AUTO_INCREMENT,
  `postavka_id` int NOT NULL,
  `tovar_id` int NOT NULL,
  `kolichestvo` int NOT NULL,
  `cena` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`zapis_id`),
  KEY `postavka_id` (`postavka_id`),
  KEY `tovar_id` (`tovar_id`),
  CONSTRAINT `postavki_tovary_ibfk_1` FOREIGN KEY (`postavka_id`) REFERENCES `postavki` (`postavka_id`),
  CONSTRAINT `postavki_tovary_ibfk_2` FOREIGN KEY (`tovar_id`) REFERENCES `tovar` (`tovar_id`),
  CONSTRAINT `postavki_tovary_chk_1` CHECK ((`kolichestvo` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postavki_tovary`
--

LOCK TABLES `postavki_tovary` WRITE;
/*!40000 ALTER TABLE `postavki_tovary` DISABLE KEYS */;
/*!40000 ALTER TABLE `postavki_tovary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postavshiki`
--

DROP TABLE IF EXISTS `postavshiki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postavshiki` (
  `postavshik_id` int NOT NULL AUTO_INCREMENT,
  `nazvanie` varchar(100) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `adres` text,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`postavshik_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postavshiki`
--

LOCK TABLES `postavshiki` WRITE;
/*!40000 ALTER TABLE `postavshiki` DISABLE KEYS */;
/*!40000 ALTER TABLE `postavshiki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postavshiki_tovary`
--

DROP TABLE IF EXISTS `postavshiki_tovary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postavshiki_tovary` (
  `id` int NOT NULL AUTO_INCREMENT,
  `postavshik_id` int NOT NULL,
  `tovar_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `postavshik_id` (`postavshik_id`),
  KEY `tovar_id` (`tovar_id`),
  CONSTRAINT `postavshiki_tovary_ibfk_1` FOREIGN KEY (`postavshik_id`) REFERENCES `postavshiki` (`postavshik_id`),
  CONSTRAINT `postavshiki_tovary_ibfk_2` FOREIGN KEY (`tovar_id`) REFERENCES `tovar` (`tovar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postavshiki_tovary`
--

LOCK TABLES `postavshiki_tovary` WRITE;
/*!40000 ALTER TABLE `postavshiki_tovary` DISABLE KEYS */;
/*!40000 ALTER TABLE `postavshiki_tovary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sotrudniki`
--

DROP TABLE IF EXISTS `sotrudniki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sotrudniki` (
  `sotrid` int NOT NULL,
  `name` varchar(15) NOT NULL,
  `lastname` varchar(15) NOT NULL,
  `doljid` int DEFAULT NULL,
  `log` varchar(8) DEFAULT NULL,
  `pas` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`sotrid`),
  KEY `doljid` (`doljid`),
  CONSTRAINT `sotrudniki_ibfk_1` FOREIGN KEY (`doljid`) REFERENCES `DOLJ` (`doljid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sotrudniki`
--

LOCK TABLES `sotrudniki` WRITE;
/*!40000 ALTER TABLE `sotrudniki` DISABLE KEYS */;
INSERT INTO `sotrudniki` VALUES (1,'Денис','борисов',1,'1','1'),(2,'Юрий','Шестаков',1,'2','2'),(3,'Алина','Ершова',2,'3','3'),(4,'Аркадий','Соболев',2,'4','4'),(5,'София','Горшкова',3,'5','5');
/*!40000 ALTER TABLE `sotrudniki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tovar`
--

DROP TABLE IF EXISTS `tovar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tovar` (
  `tovar_id` int NOT NULL,
  `nazvanie_tovara` varchar(100) NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `kolichestvo_na_sklade` int NOT NULL,
  `brend_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `average_rating` decimal(3,2) DEFAULT NULL,
  PRIMARY KEY (`tovar_id`),
  KEY `idx_tovar_name` (`nazvanie_tovara`),
  KEY `idx_tovar_category` (`category_id`),
  KEY `idx_tovar_brend` (`brend_id`),
  CONSTRAINT `tovar_ibfk_1` FOREIGN KEY (`brend_id`) REFERENCES `brend` (`brend_id`),
  CONSTRAINT `tovar_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tovar`
--

LOCK TABLES `tovar` WRITE;
/*!40000 ALTER TABLE `tovar` DISABLE KEYS */;
INSERT INTO `tovar` VALUES (1,'Bosch KGN39VL35R',699.00,30,1,1,3.80),(2,'Samsung WW80TA046AE',599.00,20,2,2,3.33),(3,'LG GA-B459SLGL',899.00,25,3,1,NULL),(4,'LG OLED55C14LB',1299.00,15,3,3,3.50),(5,'Samsung RB37A502BSA',649.00,28,2,1,3.80),(6,'Philips PowerPro Compact',199.00,10,4,4,3.00),(7,'LG F4J6TM0W',549.00,18,3,2,NULL),(8,'Samsung QE55QN90A',1499.00,12,2,3,3.50),(9,'Haier C2F636CXRG',549.00,22,8,1,NULL),(10,'Dyson V11 Absolute',499.00,8,1,4,3.67),(11,'Bosch WAT28441',679.00,15,1,2,NULL),(12,'Philips 55OLED806',1199.00,20,4,3,NULL),(13,'Beko RCNK 335E20 VS',499.00,18,9,1,NULL),(14,'Bosch BGS7PET',179.00,10,1,4,NULL),(15,'Philips PerfectCare 7000',749.00,12,4,2,NULL),(16,'Sony XR-55A80J',1399.00,15,2,3,NULL),(17,'Electrolux ENB38700OW',799.00,25,7,1,NULL),(18,'Samsung Jet 90 Complete',399.00,8,2,4,NULL),(19,'Beko WRE 6512 BWW',399.00,20,9,2,NULL),(20,'TCL 55C825',899.00,18,8,3,NULL),(21,'Indesit ITS 4200 W',429.00,15,5,1,NULL),(22,'Thomas DryBox Plus',299.00,9,3,4,NULL),(23,'Electrolux EW6S404BEW',459.00,14,7,2,NULL),(24,'Xiaomi Mi TV Q1 55',799.00,16,9,3,NULL),(25,'Whirlpool W9 941K FX',729.00,20,6,1,NULL),(31,'Karcher WD6 Premium',349.00,5,5,4,NULL),(32,'Rowenta RH9287WO',229.00,12,6,4,NULL),(33,'Miele Complete C3',599.00,8,7,4,NULL),(34,'Black+Decker Dustbuster',89.00,3,8,4,3.67),(35,'Zelmer 919.0 ST',159.00,2,9,4,3.00);
/*!40000 ALTER TABLE `tovar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tovar_images`
--

DROP TABLE IF EXISTS `tovar_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tovar_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `tovar_id` int DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `tovar_id` (`tovar_id`),
  CONSTRAINT `tovar_images_ibfk_1` FOREIGN KEY (`tovar_id`) REFERENCES `tovar` (`tovar_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tovar_images`
--

LOCK TABLES `tovar_images` WRITE;
/*!40000 ALTER TABLE `tovar_images` DISABLE KEYS */;
INSERT INTO `tovar_images` VALUES (1,1,'1.jpg'),(3,1,'3.jpg'),(4,2,'4.jpg'),(5,3,'6.png'),(7,35,'35.jpg'),(8,34,'34.jpg'),(9,33,'33.jpg'),(10,32,'32.jpg'),(11,31,'31.jpg'),(12,25,'25.jpg');
/*!40000 ALTER TABLE `tovar_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ssss@mail.ru','sadasd','123'),(2,'wqewqe','qwe','123'),(3,'wqewqewqe','qwe','123'),(4,'wqewqewqesad','qwe','123'),(5,'wqewqewqesadasd','qwezxczxcsad','123'),(6,'asdwqewqewqesadasd','asdsadasqwezxczxcsad','123');
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

-- Dump completed on 2025-08-31  2:28:05
