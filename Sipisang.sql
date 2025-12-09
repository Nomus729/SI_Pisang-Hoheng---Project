-- MySQL dump 10.13  Distrib 8.4.7, for Linux (x86_64)
--
-- Host: localhost    Database: db_sipisang
-- ------------------------------------------------------
-- Server version	8.4.7

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `role` enum('admin','customer') NOT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,5,'dwdw','customer','Login Success','::1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','2025-12-08 18:15:03'),(2,4,'kambing','customer','Login Success','::1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','2025-12-08 18:17:35'),(3,4,'kambing','customer','Login Success','::1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','2025-12-09 08:09:54'),(4,11,'Super Admin','admin','Login Success','::1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','2025-12-09 08:10:57'),(5,5,'dwdw','customer','Login Success','::1','Mozilla/5.0 (X11; Linux x86_64; rv:145.0) Gecko/20100101 Firefox/145.0','2025-12-09 08:57:40');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_pesanan`
--

DROP TABLE IF EXISTS `detail_pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pesanan_id` int NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `qty` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `catatan` text,
  PRIMARY KEY (`id`),
  KEY `pesanan_id` (`pesanan_id`),
  CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_pesanan`
--

LOCK TABLES `detail_pesanan` WRITE;
/*!40000 ALTER TABLE `detail_pesanan` DISABLE KEYS */;
INSERT INTO `detail_pesanan` VALUES (1,1,'Otak Otak',9000.00,6,54000.00,NULL),(2,1,'Sosis Goreng',15000.00,7,105000.00,NULL),(3,2,'Es Teh',5000.00,2,10000.00,NULL),(4,2,'Otak Otak',9000.00,2,18000.00,NULL),(5,3,'Telur Gelung',1000.00,10,10000.00,NULL),(6,3,'Pisang Cokelat',10000.00,6,60000.00,NULL),(7,4,'Seblak',20000.00,14,280000.00,NULL),(8,4,'Tahu Sumedang',10000.00,18,180000.00,NULL),(9,5,'Tempe Goreng',2000.00,7,14000.00,NULL),(10,6,'Telur Gelung',1000.00,10,10000.00,NULL),(11,6,'Tempe Goreng',2000.00,6,12000.00,NULL),(12,6,'Tahu Sumedang',10000.00,6,60000.00,NULL),(13,6,'Kentang Balado',10000.00,6,60000.00,NULL),(14,7,'Tahu Sumedang',10000.00,3,30000.00,NULL),(15,7,'Kentang Balado',10000.00,4,40000.00,NULL),(16,8,'Tahu Sumedang',10000.00,3,30000.00,NULL),(17,9,'Otak Otak',9000.00,3,27000.00,NULL),(18,9,'Seblak',20000.00,1,20000.00,NULL),(19,10,'Tempe Goreng',2000.00,1,2000.00,NULL),(20,11,'Basreng',5000.00,3,15000.00,NULL),(21,11,'Pisang Cokelat',10000.00,1,10000.00,NULL),(22,11,'Sosis Goreng',15000.00,1,15000.00,NULL),(23,11,'Es Teh',5000.00,1,5000.00,NULL),(24,11,'Ayam Penyet',25000.00,1,25000.00,NULL),(25,11,'Tahu Sumedang',10000.00,1,10000.00,NULL),(26,12,'Telur Gelung',1000.00,40,40000.00,NULL),(27,13,'Telur Gelung',1000.00,10,10000.00,NULL),(28,14,'Pisang Cokelat',10000.00,4,40000.00,NULL),(29,15,'Telur Gelung',1000.00,1,1000.00,NULL),(30,16,'Tempe Goreng',2000.00,1,2000.00,NULL),(31,17,'Otak Otak',9000.00,1,9000.00,NULL),(32,18,'Telur Gelung',1000.00,5,5000.00,NULL),(33,19,'Telur Gelung',1000.00,10,10000.00,NULL),(34,19,'Tempe Goreng',2000.00,6,12000.00,NULL),(35,19,'Kentang Balado',10000.00,6,60000.00,NULL),(36,19,'Tahu Sumedang',10000.00,9,90000.00,NULL),(37,19,'Seblak',20000.00,1,20000.00,NULL),(38,20,'Tahu Sumedang',10000.00,1,10000.00,NULL),(39,21,'Seblak',20000.00,5,100000.00,NULL),(40,22,'Otak Otak',9000.00,3,27000.00,NULL),(41,22,'Seblak',20000.00,3,60000.00,NULL),(42,22,'Tahu Sumedang',10000.00,3,30000.00,NULL),(43,23,'Tempe Goreng',2000.00,3,6000.00,NULL),(44,23,'Tahu Sumedang',10000.00,4,40000.00,NULL),(45,24,'Sosis Goreng',15000.00,20,300000.00,NULL),(46,24,'Es Teh',5000.00,3,15000.00,NULL),(47,24,'Telur Gelung',1000.00,4,4000.00,NULL),(48,25,'Pisang Cokelat',10000.00,100,1000000.00,NULL),(49,26,'Tempe Goreng',2000.00,1,2000.00,NULL),(50,27,'Tempe Goreng',2000.00,1,2000.00,NULL),(51,28,'Ayam Penyet',25000.00,1,25000.00,NULL);
/*!40000 ALTER TABLE `detail_pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keranjang`
--

DROP TABLE IF EXISTS `keranjang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keranjang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keranjang`
--

LOCK TABLES `keranjang` WRITE;
/*!40000 ALTER TABLE `keranjang` DISABLE KEYS */;
/*!40000 ALTER TABLE `keranjang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_pesanan` varchar(20) NOT NULL,
  `user_id` int NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_bayar` enum('cash','qris') NOT NULL,
  `metode_kirim` enum('pickup','delivery') NOT NULL,
  `alamat_kirim` text,
  `catatan` text,
  `status` enum('pending','proses','selesai','batal') DEFAULT 'pending',
  `catatan_tolak` text,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
INSERT INTO `pesanan` VALUES (1,'INV-20251203-2101',4,159000.00,'cash','pickup','',NULL,'batal',NULL,'2025-12-03 10:11:08'),(2,'INV-20251203-4580',5,28000.00,'cash','pickup','',NULL,'selesai',NULL,'2025-12-03 10:12:35'),(3,'INV-20251203-5474',5,80000.00,'cash','delivery','Jalan Arif Rahman polisi',NULL,'selesai',NULL,'2025-12-03 11:01:46'),(4,'INV-20251207-4483',4,460000.00,'cash','pickup','',NULL,'selesai',NULL,'2025-12-07 12:36:49'),(5,'INV-20251207-6598',4,14000.00,'cash','pickup','','jangan manis','selesai',NULL,'2025-12-07 13:04:33'),(6,'INV-20251207-8782',4,142000.00,'cash','pickup','','aku mau enak','selesai',NULL,'2025-12-07 13:13:22'),(7,'INV-20251207-7464',4,70000.00,'cash','pickup','','Jangan pedas pedas ya okeey','selesai',NULL,'2025-12-07 13:20:38'),(8,'INV-20251207-3219',4,30000.00,'cash','pickup','','yang banyak ya','selesai',NULL,'2025-12-07 13:38:49'),(9,'INV-20251207-5822',4,47000.00,'cash','pickup','','-','selesai',NULL,'2025-12-07 14:01:05'),(10,'INV-20251207-7720',4,2000.00,'cash','pickup','','-','selesai',NULL,'2025-12-07 14:01:19'),(11,'INV-20251207-4977',5,80000.00,'cash','pickup','','-','selesai',NULL,'2025-12-07 14:12:51'),(12,'INV-20251207-9027',5,40000.00,'cash','pickup','','sambel banyakin','selesai',NULL,'2025-12-07 14:17:54'),(13,'INV-20251207-9908',5,10000.00,'cash','pickup','','-','selesai',NULL,'2025-12-07 14:18:55'),(14,'INV-20251207-5034',4,40000.00,'cash','pickup','','banyakin ya cokelatnya','selesai',NULL,'2025-12-07 18:49:53'),(15,'INV-20251207-6934',4,1000.00,'cash','pickup','','dadoiwjoeiwjodaijodijewodiajoeidjoeiajoidjoeidjoeijaoidjeoifdjaoeijdoiewjodiwjaoidjowaijadoijwaoidjwaoidjawoidjwoaidjowiajdoiaw','batal',NULL,'2025-12-07 18:50:54'),(16,'INV-20251207-6276',4,2000.00,'cash','pickup','','dakjsnkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk','batal',NULL,'2025-12-07 18:59:36'),(17,'INV-20251207-3618',4,9000.00,'cash','pickup','','dkslaksldkmaslkdmaslkdmaslkmdslkamdlksmalksmdlaksmdlkasmdlksmdlaksmsklmalksmdlakm','batal',NULL,'2025-12-07 19:05:08'),(18,'INV-20251207-5159',4,5000.00,'cash','pickup','','dsjdkajsdnkasj sdanjskansjdk adjskajdnskajdnskjnakjdsn adnksjanksdjnsak sdnaksjdnaksjdn ','batal','stok habis','2025-12-07 19:07:25'),(19,'INV-20251207-1198',4,192000.00,'cash','pickup','','banyakin ya porsinya \n','batal','maaf stok habis','2025-12-07 19:48:21'),(20,'INV-20251208-7950',4,10000.00,'cash','pickup','','enak','batal','stok ya habis','2025-12-08 10:11:47'),(21,'INV-20251208-9339',5,100000.00,'cash','pickup','','seblaknya pedas','selesai',NULL,'2025-12-08 16:28:12'),(22,'INV-20251208-8865',5,117000.00,'cash','pickup','','banyakin ya porsinya.','batal','Stok saya habis. Mohon maaf','2025-12-08 16:31:06'),(23,'INV-20251209-3470',4,46000.00,'cash','pickup','','banyakin tempenya','selesai',NULL,'2025-12-09 08:10:41'),(24,'INV-20251209-2884',5,319000.00,'cash','pickup','','enak','selesai',NULL,'2025-12-09 08:58:06'),(25,'INV-20251209-5032',5,1000000.00,'cash','pickup','','banyakin cokelatnya','selesai',NULL,'2025-12-09 09:14:56'),(26,'INV-20251209-6634',5,2000.00,'cash','pickup','','-','batal','maaf stok saya habis','2025-12-09 09:24:06'),(27,'INV-20251209-7082',5,2000.00,'cash','pickup','','okok','batal','stok habis','2025-12-09 09:27:59'),(28,'INV-20251209-4793',5,25000.00,'cash','pickup','','-','pending',NULL,'2025-12-09 09:29:03');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) DEFAULT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` decimal(10,2) NOT NULL,
  `stok` int DEFAULT '0',
  `kategori` enum('Makanan','Minuman') DEFAULT 'Makanan',
  `gambar` varchar(255) DEFAULT NULL,
  `detail_info` json DEFAULT NULL,
  `terjual` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_barang` (`kode_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (11,'BRG4005','Pisang Cokelat','\r\n- pisang\r\n- Cokelat\r\n- keju',10000.00,8895,'Makanan','1764729551_pisang coklat.jpeg',NULL,105,'2025-12-03 02:39:11'),(12,'BRG2778','Basreng','\r\n- Baso\r\n- Bumbu Balado\r\n- jagung',5000.00,7997,'Makanan','1764729658_basreng.jpeg',NULL,3,'2025-12-03 02:40:58'),(13,'BRG3858','Sosis Goreng','\r\n- Sosis\r\n- Bumbu',15000.00,6979,'Makanan','1764729686_Sosis Goreng.webp',NULL,21,'2025-12-03 02:41:26'),(14,'BRG1789','Otak Otak','\r\n- Sosis\r\n- Balado\r\n- Jagung\r\n- Asin',9000.00,7777,'Makanan','1764729723_Otak Otak Goreng.jpg',NULL,0,'2025-12-03 02:42:03'),(15,'BRG5135','Es Teh','\r\n- Teh\r\n- Gula\r\n- Es',5000.00,6662,'Makanan','1764729822_esteh.jpeg',NULL,4,'2025-12-03 02:43:42'),(16,'BRG9545','Seblak','\r\n- seblak\r\n- Ceker\r\n- Telur\r\n- Sawi',20000.00,283,'Makanan','1764734277_seblak-kuah-ceker.webp',NULL,5,'2025-12-03 03:57:57'),(17,'BRG8067','Telur Gulung','- Telur\r\n- Saus Tiram',1000.00,9950,'Makanan','1764734392_Telur Gulung.jpg',NULL,50,'2025-12-03 03:59:52'),(18,'BRG9231','Ayam Penyet','\r\n- ayam \r\n- sambal merah\r\n- sambal matah\r\n- sambal hijau',25000.00,198,'Makanan','1765085538_Ayam Penyet.jpg',NULL,1,'2025-12-07 05:32:18'),(19,'BRG6468','Tempe Goreng','\r\n- tempe\r\n- tepung\r\n- sambel',2000.00,8996,'Makanan','1765085646_tempe goreng.jpg',NULL,3,'2025-12-07 05:34:06'),(20,'BRG2434','Tahu Sumedang','\r\n- tahu goreng\r\n- cabai\r\n- bumbu',10000.00,29994,'Makanan','1765085676_Tahu Sumedang.jpg',NULL,5,'2025-12-07 05:34:36'),(21,'BRG1720','Kentang Balado','\r\n- Kentang\r\n- Cabai merah',10000.00,282828,'Makanan','1765085710_Kentang balado.webp',NULL,0,'2025-12-07 05:35:10');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'rakan','test123@gmail.com','$2y$10$kBRM03KMivorlUJndzFvfOy3jJIT7Fyis1EtxevOT8bQoioGsllCa',NULL,'customer','2025-11-25 23:56:59'),(2,'rakan','test23@gmail.com','$2y$10$w.6t7g9TA1S4pKU9rPX3jeytwgcrYjC3EqmoELIoncelXLXXfEp8K',NULL,'customer','2025-11-26 00:02:34'),(3,'test','test83838@gmail.com','$2y$10$CkW7oDkSbgJ.SWdHmI17o.e4i2o6fnxXm.rhXFgHZOjyzKkEq2zGy',NULL,'customer','2025-11-26 00:21:37'),(4,'kambing','kambing@gmail.com','$2y$10$o9VC86E6WfyIPAGR0ocOWenqzH7R5AwWz.rPnTP56Mmc3DV.34tE6',NULL,'customer','2025-11-26 00:22:27'),(5,'dwdw','dwdw@gmail.com','$2y$10$f2xar4BCSzWQ21gR6sMtf.vTQC1b1uER/aha49mbXC2ZFHPU2Ei1m',NULL,'customer','2025-11-26 01:38:15'),(8,'admin12','admin2@sipisang.com','admin123','Kantor Pusat','admin','2025-12-02 02:44:26'),(10,'Admin Ketiga','admin3@sipisang.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Cabang Surabaya','admin','2025-12-02 02:53:38'),(11,'Super Admin','admin@sipisang.com','$2y$10$6.jbqEPsJM44ZNSVB1iBPuyVYUoEtJ9Z1azFhUxiCfpn47L8SR3Dy','Kantor Pusat','admin','2025-12-02 03:00:59');
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

-- Dump completed on 2025-12-09  9:51:29
