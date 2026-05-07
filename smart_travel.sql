CREATE DATABASE IF NOT EXISTS smart_travel;
USE smart_travel;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `exchange_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Đang mở cửa',
  `usd_rate` decimal(10,2) NOT NULL,
  `distance_km` decimal(5,2) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `reviews_count` int(11) DEFAULT 0,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `scans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `confidence` decimal(5,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `scanned_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_scan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency_from` varchar(10) NOT NULL,
  `currency_to` varchar(10) NOT NULL,
  `result_amount` decimal(15,2) NOT NULL,
  `saved_amount` decimal(15,2) DEFAULT 0,
  `location_id` int(11) DEFAULT NULL,
  `transaction_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `fk_trans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_trans_location` FOREIGN KEY (`location_id`) REFERENCES `exchange_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert some dummy data for exchange locations
INSERT INTO `exchange_locations` (`name`, `address`, `status`, `usd_rate`, `distance_km`, `rating`, `reviews_count`) VALUES
('Hưng Long Exchange', '36 Mạc Thị Bưởi, Quận 1', 'Đang mở cửa', 25410.00, 0.4, 4.9, 1200),
('Vietcombank - CN1', 'Bến Chương Dương, Quận 1', 'Đang mở cửa', 25385.00, 1.2, 4.5, 800),
('Quầy Thu Đổi 59', 'Chợ Bến Thành, Quận 1', 'Đang mở cửa', 25425.00, 0.8, 4.8, 2500);

-- Insert dummy user
INSERT INTO `users` (`full_name`, `email`, `password`) VALUES
('Nguyễn Văn A', 'john@smarttravel.io', 'hashed_password_here');
