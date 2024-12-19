-- USERS TABLOSU
CREATE TABLE `users` (
  `id` INTEGER PRIMARY KEY,
  `avatar` VARCHAR(255),
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) UNIQUE NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `birth_of_date` DATE,
  `phone_number` VARCHAR(15) UNIQUE,
  `user_type` ENUM('customer', 'seller') NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL
);

-- ADDRESSES TABLOSU
CREATE TABLE `addresses` (
  `id` INTEGER PRIMARY KEY,
  `user_id` INTEGER NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `address_line_1` VARCHAR(255) NOT NULL,
  `address_line_2` VARCHAR(255),
  `country` VARCHAR(100) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NOT NULL,
  `landmark` VARCHAR(255),
  `phone_number` VARCHAR(15),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

-- CATEGORIES TABLOSU
CREATE TABLE `categories` (
  `id` INTEGER PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL
);

-- SUB_CATEGORIES TABLOSU
CREATE TABLE `sub_categories` (
  `id` INTEGER PRIMARY KEY,
  `parent_id` INTEGER,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`)
);

-- PRODUCTS TABLOSU
CREATE TABLE `products` (
  `id` INTEGER PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `summary` VARCHAR(255),
  `cover` VARCHAR(255),
  `category_id` INTEGER NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
);

-- PRODUCT_ATTRIBUTES TABLOSU
CREATE TABLE `product_attributes` (
  `id` INTEGER PRIMARY KEY,
  `type` ENUM('color', 'size') NOT NULL,
  `value` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL
);

-- PRODUCTS_SKUS TABLOSU
CREATE TABLE `products_skus` (
  `id` INTEGER PRIMARY KEY,
  `product_id` INTEGER NOT NULL,
  `size_attribute_id` INTEGER,
  `color_attribute_id` INTEGER,
  `sku` VARCHAR(50) UNIQUE NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `quantity` INTEGER NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`size_attribute_id`) REFERENCES `product_attributes`(`id`),
  FOREIGN KEY (`color_attribute_id`) REFERENCES `product_attributes`(`id`)
);

-- WISHLIST TABLOSU
CREATE TABLE `wishlist` (
  `id` INTEGER PRIMARY KEY,
  `user_id` INTEGER NOT NULL,
  `product_id` INTEGER NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);

-- CART TABLOSU
CREATE TABLE `cart` (
  `id` INTEGER PRIMARY KEY,
  `user_id` INTEGER NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

-- CART_ITEM TABLOSU
CREATE TABLE `cart_item` (
  `id` INTEGER PRIMARY KEY,
  `cart_id` INTEGER NOT NULL,
  `product_id` INTEGER NOT NULL,
  `products_sku_id` INTEGER,
  `quantity` INTEGER NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`cart_id`) REFERENCES `cart`(`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`products_sku_id`) REFERENCES `products_skus`(`id`)
);

-- ORDER_DETAILS TABLOSU
CREATE TABLE `order_details` (
  `id` INTEGER PRIMARY KEY,
  `user_id` INTEGER NOT NULL,
  `payment_id` INTEGER NOT NULL,
  `total` DECIMAL(10, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

-- ORDER_ITEM TABLOSU
CREATE TABLE `order_item` (
  `id` INTEGER PRIMARY KEY,
  `order_id` INTEGER NOT NULL,
  `product_id` INTEGER NOT NULL,
  `products_sku_id` INTEGER,
  `quantity` INTEGER NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `order_details`(`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`products_sku_id`) REFERENCES `products_skus`(`id`)
);

-- PAYMENT_DETAILS TABLOSU
CREATE TABLE `payment_details` (
  `id` INTEGER PRIMARY KEY,
  `order_id` INTEGER NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `provider` VARCHAR(255) NOT NULL,
  `status` ENUM('pending', 'completed', 'failed') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `order_details`(`id`)
);
