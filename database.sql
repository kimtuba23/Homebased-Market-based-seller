-- SellNest Database Installation Script Template Build Node Engine Core
CREATE DATABASE IF NOT EXISTS `sellnest_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sellnest_db`;

-- 1. Categories Management Node Matrix Map
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 2. Master User Registry Schema
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(60) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
  `status` ENUM('active', 'suspended') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Inventory Stock Resource Core Map Pipeline (Image Upload Target Storage Support Strings)
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `seller_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `category_id` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT 'default.png',
  `is_approved` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Cart Ledger Session Frame Map
CREATE TABLE IF NOT EXISTS `cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `buyer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  FOREIGN KEY (`buyer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Order Header Transactions Logs Trace Schema Table Frame
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `buyer_id` INT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `commission_earned` DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('bKash', 'Nagad', 'COD') NOT NULL,
  `transaction_id` VARCHAR(100) DEFAULT NULL,
  `payment_status` ENUM('Pending', 'Completed') DEFAULT 'Pending',
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`buyer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Order Line Items Data Frame Snapshot Records Map
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Inject Core Seed Categories Modules System Trace Mapping Keys Parameters
INSERT IGNORE INTO `categories` (`id`, `category_name`) VALUES 
(1, 'Jewelry'), (2, 'Dress'), (3, 'Shoe'), (4, 'Bags'), (5, 'Skin Care'), (6, 'Cake'), (7, 'Snacks'), (8, 'Craft Products');

-- System Admin Root Super-User Profile Module
INSERT IGNORE INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`) 
VALUES (1, 'SellNest Premium System Operations Director', 'admin@sellnest.com', 'admin123', 'admin', 'active');