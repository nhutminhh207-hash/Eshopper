-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for fashion_shop
CREATE DATABASE IF NOT EXISTS `fashion_shop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fashion_shop`;

-- Dumping structure for table fashion_shop.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.cache: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.cache_locks: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_user_id_variant_id_unique` (`user_id`,`variant_id`),
  KEY `cart_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.cart_items: ~4 rows (approximately)
INSERT INTO `cart_items` (`id`, `user_id`, `variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
	(1, 2, 9, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(2, 2, 10, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(3, 3, 9, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(4, 3, 10, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02');

-- Dumping structure for table fashion_shop.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.categories: ~3 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Áo thun', 'ao-thun', 'Danh mục demo', '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(2, 'Áo sơ mi', 'ao-so-mi', 'Danh mục demo', '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(3, 'Quần', 'quan', 'Danh mục demo', '2026-10-10 09:59:02', '2026-10-10 09:59:02');

-- Dumping structure for table fashion_shop.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.jobs: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.job_batches: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_10_10_000001_add_role_to_users', 1),
	(5, '2026_10_10_000002_create_fashion_tables', 1);

-- Dumping structure for table fashion_shop.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `checkout_token` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `status` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_amount` decimal(12,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_checkout_token_unique` (`checkout_token`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.orders: ~2 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `checkout_token`, `recipient_name`, `phone`, `address`, `payment_method`, `status`, `total_amount`, `created_at`, `updated_at`) VALUES
	(1, 2, '10000000-0000-4000-8000-000000000001', 'User A demo', '0900000000', 'Địa chỉ demo, không phải thông tin thật', 'cod', 'pending', 306000, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(2, 3, '10000000-0000-4000-8000-000000000002', 'User B demo', '0900000000', 'Địa chỉ demo, không phải thông tin thật', 'cod', 'pending', 308000, '2026-10-10 09:59:02', '2026-10-10 09:59:02');

-- Dumping structure for table fashion_shop.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(12,0) NOT NULL,
  `quantity` int unsigned NOT NULL,
  `line_total` decimal(12,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.order_items: ~2 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `product_name`, `sku`, `size`, `color`, `unit_price`, `quantity`, `line_total`, `created_at`, `updated_at`) VALUES
	(1, 1, 5, 'Sản phẩm demo 3', 'DEMO-03-M', 'M', 'Đen', 153000, 2, 306000, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(2, 2, 7, 'Sản phẩm demo 4', 'DEMO-04-M', 'M', 'Đen', 154000, 2, 308000, '2026-10-10 09:59:02', '2026-10-10 09:59:02');

-- Dumping structure for table fashion_shop.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.products: ~15 rows (approximately)
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Sản phẩm demo 1', 'san-pham-demo-1', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(2, 2, 'Sản phẩm demo 2', 'san-pham-demo-2', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(3, 3, 'Sản phẩm demo 3', 'san-pham-demo-3', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(4, 1, 'Sản phẩm demo 4', 'san-pham-demo-4', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(5, 2, 'Sản phẩm demo 5', 'san-pham-demo-5', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(6, 3, 'Sản phẩm demo 6', 'san-pham-demo-6', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(7, 1, 'Sản phẩm demo 7', 'san-pham-demo-7', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(8, 2, 'Sản phẩm demo 8', 'san-pham-demo-8', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(9, 3, 'Sản phẩm demo 9', 'san-pham-demo-9', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(10, 1, 'Sản phẩm demo 10', 'san-pham-demo-10', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(11, 2, 'Sản phẩm demo 11', 'san-pham-demo-11', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(12, 3, 'Sản phẩm demo 12', 'san-pham-demo-12', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(13, 1, 'Sản phẩm demo 13', 'san-pham-demo-13', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(14, 2, 'Sản phẩm demo 14', 'san-pham-demo-14', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(15, 3, 'Sản phẩm demo 15', 'san-pham-demo-15', 'Dữ liệu mẫu để phát triển song song', 'products/demo.jpg', 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02');

-- Dumping structure for table fashion_shop.product_variants
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,0) NOT NULL,
  `stock` int unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_product_id_size_color_unique` (`product_id`,`size`,`color`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.product_variants: ~30 rows (approximately)
INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `size`, `color`, `price`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'DEMO-01-M', 'M', 'Đen', 151000, 0, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(2, 1, 'DEMO-01-L', 'L', 'Trắng', 161000, 0, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(3, 2, 'DEMO-02-M', 'M', 'Đen', 152000, 1, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(4, 2, 'DEMO-02-L', 'L', 'Trắng', 162000, 1, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(5, 3, 'DEMO-03-M', 'M', 'Đen', 153000, 18, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(6, 3, 'DEMO-03-L', 'L', 'Trắng', 163000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(7, 4, 'DEMO-04-M', 'M', 'Đen', 154000, 18, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(8, 4, 'DEMO-04-L', 'L', 'Trắng', 164000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(9, 5, 'DEMO-05-M', 'M', 'Đen', 155000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(10, 5, 'DEMO-05-L', 'L', 'Trắng', 165000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(11, 6, 'DEMO-06-M', 'M', 'Đen', 156000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(12, 6, 'DEMO-06-L', 'L', 'Trắng', 166000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(13, 7, 'DEMO-07-M', 'M', 'Đen', 157000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(14, 7, 'DEMO-07-L', 'L', 'Trắng', 167000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(15, 8, 'DEMO-08-M', 'M', 'Đen', 158000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(16, 8, 'DEMO-08-L', 'L', 'Trắng', 168000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(17, 9, 'DEMO-09-M', 'M', 'Đen', 159000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(18, 9, 'DEMO-09-L', 'L', 'Trắng', 169000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(19, 10, 'DEMO-10-M', 'M', 'Đen', 160000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(20, 10, 'DEMO-10-L', 'L', 'Trắng', 170000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(21, 11, 'DEMO-11-M', 'M', 'Đen', 161000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(22, 11, 'DEMO-11-L', 'L', 'Trắng', 171000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(23, 12, 'DEMO-12-M', 'M', 'Đen', 162000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(24, 12, 'DEMO-12-L', 'L', 'Trắng', 172000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(25, 13, 'DEMO-13-M', 'M', 'Đen', 163000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(26, 13, 'DEMO-13-L', 'L', 'Trắng', 173000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(27, 14, 'DEMO-14-M', 'M', 'Đen', 164000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(28, 14, 'DEMO-14-L', 'L', 'Trắng', 174000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(29, 15, 'DEMO-15-M', 'M', 'Đen', 165000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02'),
	(30, 15, 'DEMO-15-L', 'L', 'Trắng', 175000, 20, 1, '2026-10-10 09:59:02', '2026-10-10 09:59:02');

-- Dumping structure for table fashion_shop.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.sessions: ~0 rows (approximately)

-- Dumping structure for table fashion_shop.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table fashion_shop.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
	(1, 'Admin demo', 'admin@example.test', NULL, '$2y$12$avZLCcALvQRu6PiinDVu7uGgD3t1nAgwc/1hrbPQcSkvcv/.NcGrO', NULL, '2026-10-10 09:59:02', '2026-10-10 09:59:02', 'admin'),
	(2, 'User A demo', 'usera@example.test', NULL, '$2y$12$nhIxB8/cDIROOrGCKWWeC.CPC3.JbwykUYaokl/69Ej5ghvjMbf3G', NULL, '2026-10-10 09:59:02', '2026-10-10 09:59:02', 'user'),
	(3, 'User B demo', 'userb@example.test', NULL, '$2y$12$rIYjGe3H/yov9MPce1b4mOEfQ04ylD47PwX.SUXngFhBTuVIoh2Ly', NULL, '2026-10-10 09:59:02', '2026-10-10 09:59:02', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
