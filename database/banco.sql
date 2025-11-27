-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: urbanstreet_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `slug` varchar(140) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_categorias_ativo` (`ativo`),
  KEY `idx_categorias_ordem` para l(`ordem`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Tênis','tenis','Cal├ºados urbanos e esportivos',1,1,'2025-11-25 15:01:59','2025-11-25 15:48:01'),(2,'Camisetas','camisetas','Camisetas oversized e regulares',2,1,'2025-11-25 15:01:59','2025-11-25 15:01:59'),(3,'Moletons','moletons','Hoodies e moletom casual',3,1,'2025-11-25 15:01:59','2025-11-25 15:01:59'),(4,'Acessórios','acessorios','Bon├®s, meias e mais',4,1,'2025-11-25 15:01:59','2025-11-25 15:48:01');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itens_pedido`
--

DROP TABLE IF EXISTS `itens_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `itens_pedido` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` int(10) unsigned NOT NULL,
  `produto_id` int(10) unsigned NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_itens_pedido_produto` (`produto_id`),
  KEY `idx_itens_pedido_pedido` (`pedido_id`),
  CONSTRAINT `fk_itens_pedido_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_itens_pedido_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itens_pedido`
--

LOCK TABLES `itens_pedido` WRITE;
/*!40000 ALTER TABLE `itens_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `itens_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pendente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `criado_em` timestamp NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pedidos_status` (`status`),
  KEY `idx_pedidos_usuario` (`usuario_id`),
  CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_product_images_produtos` (`product_id`),
  CONSTRAINT `fk_product_images_produtos` FOREIGN KEY (`product_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (4,5,'images/products/camisetas/1764264056_19.jpeg','2025-11-27 17:20:56'),(15,1,'images/products/tenis/1.jpeg','2025-11-27 18:22:02');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` int(10) unsigned DEFAULT NULL,
  `nome` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `marca` varchar(120) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `imagem` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_produtos_ativo` (`ativo`),
  KEY `idx_produtos_categoria` (`categoria_id`),
  KEY `idx_produtos_destaque` (`destaque`),
  KEY `idx_produtos_marca` (`marca`),
  KEY `idx_produtos_nome` (`nome`),
  CONSTRAINT `fk_produtos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (1,1,'Air Force Urban Black','air-force-urban-black','NIKE','Tênis urbano com design moderno e confortável. Perfeito para o dia a dia.',599.90,50,1,1,NULL,'2025-11-25 15:01:59','2025-11-27 22:22:02'),(2,1,'Dunk Low Street Edition','dunk-low-street-edition','NIKE','Versão street do Dunk Low, ideal para looks despojados.',749.90,50,1,1,NULL,'2025-11-25 15:01:59','2025-11-27 17:08:19'),(3,2,'Camiseta Oversized Preta','camiseta-oversized-preta','URBAN','Camiseta oversized preta, 100% algodão. Estilo urbano autêntico.',149.90,50,1,1,NULL,'2025-11-25 15:01:59','2025-11-27 17:08:20'),(4,3,'Moletom Oversized Cinza','moletom-oversized-cinza','URBAN','Moletom oversized cinza, conforto e estilo para o inverno.',299.90,50,1,1,NULL,'2025-11-25 15:01:59','2025-11-27 17:08:20'),(5,2,'CAMISETA AZUL','','NIKE','ANDASJDPASDAS',150.00,5,0,1,NULL,'2025-11-27 15:55:17','2025-11-27 17:40:05');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `email` varchar(180) NOT NULL,
  `email_verificado_em` timestamp NULL DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` enum('M','F','O') DEFAULT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT 0,
  `sms_marketing` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('admin','cliente','staff') NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_usuarios_ativo` (`ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Admin','admin@urbanstreet.local',NULL,'e0a3b1e3439f4f9ebb3ff4466a2954c9b7c7f1f82b8f0d5959cebb9a399987c8\r\n','11999990000',NULL,'O',1,0,1,'2025-11-25 15:01:59','2025-11-27 12:58:45','cliente'),(2,'Marcos Inacio','marcosinacio@gmail.com',NULL,'e0a3b1e3439f4f9ebb3ff4466a2954c9b7c7f1f82b8f0d5959cebb9a399987c8',NULL,NULL,NULL,1,0,1,'2025-11-27 13:03:03','2025-11-27 13:03:03','cliente'),(4,'Marcos Inacio','admin@gmail.com',NULL,'$2y$10$3f2b3QkU9lGzuqXpJH6Uu.VuJgPpMDf97sbWPSRU8a7FHjOWyH8sm',NULL,NULL,NULL,1,0,1,'2025-11-27 13:08:23','2025-11-27 13:08:23','cliente'),(5,'Marcos Inacio','ServerAdmin@gmail.com',NULL,'$2y$10$iyS0RHnogb2y0WdEoXqkIOOccIln.LOmwHtK5k8YyftKb7rNOfkaq',NULL,NULL,NULL,1,0,1,'2025-11-27 13:11:02','2025-11-27 13:16:27','admin');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'urbanstreet_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-27 15:57:48
