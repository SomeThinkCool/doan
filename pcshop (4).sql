-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 08, 2025 at 10:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pcshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender_email` varchar(100) NOT NULL,
  `receiver_email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_email`, `receiver_email`, `message`, `sent_at`) VALUES
(1, 'guest', 'admin@example.com', 'Hello', '2025-06-08 06:46:29'),
(2, 'guest', 'admin@example.com', 'Hi', '2025-06-08 06:55:06'),
(3, 'manh123@gmail.com', 'admin@example.com', 'Hi', '2025-06-08 06:56:55'),
(4, 'guest', 'admin@example.com', 'Guest', '2025-06-08 07:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Đang xử lý',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `address`, `phone`, `total`, `status`, `created_at`) VALUES
(1, 3, 'Nam', 'Bac Ninh', '012345', 32259200.00, 'Đang xử lý', '2025-05-05 15:58:51'),
(2, 3, 'Tuyen', '123', '123', 16458800.00, 'Đang xử lý', '2025-05-05 16:32:10'),
(3, 4, 'Tuyen', '123', '123', 6290000.00, 'Đang xử lý', '2025-05-05 16:32:37'),
(4, 3, 'Nam', 'Bac Ninh', '0123456789', 14391000.00, 'Đang xử lý', '2025-05-05 20:22:25'),
(6, 6, 'manh', 'Bac Ninh', '0123456789', 47405000.00, 'Đang xử lý', '2025-06-08 09:49:41'),
(7, 6, 'manh', 'Bac Ninh', '0123456789', 25143700.00, 'Đang xử lý', '2025-06-08 14:10:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 3, 'PC CHƠI GAME RTX 3060 - i5 12400F', 15192000.00, 1, 15192000.00),
(2, 1, 2, 'PC BEST FOR GAMING GTX 1660', 9951700.00, 1, 9951700.00),
(3, 1, 11, 'PC Văn Phòng Intel i3 12100 / 8GB / 256GB SSD', 7115500.00, 1, 7115500.00),
(4, 2, 15, 'PC Streamer Ryzen 5 5600 / RX 6600 / 16GB / 512GB', 16458800.00, 1, 16458800.00),
(5, 3, 17, 'PC Văn Phòng Ryzen 3 3200G / 8GB / 256GB SSD', 6290000.00, 1, 6290000.00),
(6, 4, 12, 'PC Gaming Intel i5 12400F / RTX 3050 / 16GB / 512GB SSD', 14391000.00, 1, 14391000.00),
(7, 6, 4, 'PC WORKSTATION RTX 4070Ti - i7 13700K', 47405000.00, 1, 47405000.00),
(8, 7, 3, 'PC CHƠI GAME RTX 3060 - i5 12400F', 15192000.00, 1, 15192000.00),
(9, 7, 1, 'PC GAMING RTX 3050 - i5 10400F', 9951700.00, 1, 9951700.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(500) NOT NULL,
  `original_price` int(11) NOT NULL,
  `discount_percentage` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `sold_quantity` int(11) DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 99
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_image`, `original_price`, `discount_percentage`, `created_at`, `sold_quantity`, `quantity`) VALUES
(1, 'PC GAMING RTX 3050 - i5 10400F', 'https://product.hstatic.net/1000288298/product/dsc06135_c0ee89a65eb54660be64d812a7cf8082_master.jpg', 11990000, 17, '2025-04-18 20:56:12', 11, 98),
(2, 'PC BEST FOR GAMING GTX 1660', 'https://product.hstatic.net/1000288298/product/dsc02784_281bdbebc0774fbb8d9e4033cd51d7e3_large.jpg', 11990000, 17, '2025-04-18 20:56:12', 13, 99),
(3, 'PC CHƠI GAME RTX 3060 - i5 12400F', 'https://product.hstatic.net/1000288298/product/dsc02830_de93416f743c43a6afabb700213d2937_large.jpg', 18990000, 20, '2025-04-18 20:56:12', 14, 98),
(4, 'PC WORKSTATION RTX 4070Ti - i7 13700K', 'https://product.hstatic.net/1000288298/product/11871_dsc01031_1045573a1930469ca7a9d6ae8de7dc2a_large.jpg', 49900000, 5, '2025-04-18 20:56:12', 10, 5),
(5, 'PC GAMING RTX 4060 - i5 12400F', 'https://product.hstatic.net/1000288298/product/pc-rx-7600-8gb-dual-oc_6f729eb7baa9464883533d8c63f57758_large.jpg', 21990000, 27, '2025-04-18 20:56:12', 11, 99),
(6, 'PC VĂN PHÒNG G3250 - RAM 4GB', 'https://product.hstatic.net/1000288298/product/pc-rx-7600-8gb-dual-oc_4_a2e74b243da7474f843903da7da0e7b6_large.jpg', 3990000, 10, '2025-04-18 20:56:12', 9, 99),
(7, 'PC MINI GTX 1650 - i3 10100F', 'https://product.hstatic.net/1000288298/product/11871_dsc01031_1045573a1930469ca7a9d6ae8de7dc2a_large.jpg', 8990000, 15, '2025-04-18 20:56:12', 10, 99),
(8, 'PC GIẢ LẬP - i7 10700 + RAM 32GB', 'https://product.hstatic.net/1000288298/product/pc-rx-7600-8gb-dual-oc-blast_2_8d34af2901fb4e2e901024cdeff3867d_large.jpg', 17990000, 12, '2025-04-18 20:56:12', 9, 99),
(9, 'PC ULTRA GAMING - i9 14900K + RTX 4080', 'https://product.hstatic.net/1000288298/product/dsc01213_53becc736ad348a894b8435c8b8b3f1e_large.jpg', 68900000, 8, '2025-04-18 20:56:12', 9, 99),
(10, 'PC AMD RYZEN 5 5600G - VEGA', 'https://product.hstatic.net/1000288298/product/pc-rx-7600-8gb-dual-oc-blast_2_aac99d76b1354fc28f9699ee1d4bfeb2_large.jpg', 7290000, 0, '2025-04-18 20:56:12', 9, 99),
(11, 'PC Văn Phòng Intel i3 12100 / 8GB / 256GB SSD', 'https://product.hstatic.net/1000288298/product/dsc01962_82292de4a5db421192d46d563989e690_large.jpg', 7490000, 5, '2025-04-23 09:03:16', 21, 99),
(12, 'PC Gaming Intel i5 12400F / RTX 3050 / 16GB / 512GB SSD', 'https://product.hstatic.net/1000288298/product/dsc03886_2_fe261d7a9e75446492cf913fe83d06ba_large.jpg', 15990000, 10, '2025-04-23 09:03:16', 38, 99),
(13, 'PC Đồ Họa Ryzen 7 5700G / 32GB / 1TB SSD', 'https://product.hstatic.net/1000288298/product/dsc03842_63a702c1af704eebae9adaa8e854db6a_large.jpg', 19990000, 7, '2025-04-23 09:03:16', 18, 99),
(14, 'PC Gaming Intel i7 13700K / RTX 4070 / 32GB RAM', 'https://product.hstatic.net/1000288298/product/dsc07708_8da2fd3f7d774174987f1798c58ef871_large.jpg', 38990000, 12, '2025-04-23 09:03:16', 11, 99),
(15, 'PC Streamer Ryzen 5 5600 / RX 6600 / 16GB / 512GB', 'https://product.hstatic.net/1000288298/product/dsc07708_8da2fd3f7d774174987f1798c58ef871_large.jpg', 17890000, 8, '2025-04-23 09:03:16', 28, 99),
(16, 'PC Mini Intel NUC 11 / i5 / 16GB / 256GB SSD', 'https://product.hstatic.net/1000288298/product/dsc09846_80b0a3dbb25e49d09bc86dc94bd27a9d_large.jpg', 10990000, 5, '2025-04-23 09:03:16', 15, 99),
(17, 'PC Văn Phòng Ryzen 3 3200G / 8GB / 256GB SSD', 'https://product.hstatic.net/1000288298/product/pc-rx-7600-8gb-dual-oc_4_f612d701aaff4122a033178fb99d1ecb_large.jpg', 6290000, 0, '2025-04-23 09:03:16', 41, 99),
(18, 'PC Gaming Intel i9 13900KF / RTX 4090 / 64GB RAM', 'https://product.hstatic.net/1000288298/product/12268_11741_dsc00388_24ff6c248a64478fa407a3e0b9b3de5f_large.jpg', 79990000, 15, '2025-04-23 09:03:16', 5, 99),
(19, 'PC Học Tập Pentium G6405 / 4GB / 128GB SSD', 'https://product.hstatic.net/1000288298/product/12276_11765_dsc01406_3993a547bac648c1b7e9ce7e1e4afdd2_large.jpg', 3990000, 0, '2025-04-23 09:03:16', 60, 99),
(20, 'PC Editing Ryzen 9 7900X / 64GB / 2TB SSD / RTX 4080', 'https://product.hstatic.net/1000288298/product/dsc03006_35733fc05ae641c59c090c4df75dd4d6_large.jpg', 52900000, 10, '2025-04-23 09:03:16', 10, 99);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `status` enum('active','locked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `role`, `status`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$i958WlV4h7lg2NcNY1lbeueFewd1fjR3aV39nnrivL15AvuzeNaC2', 'admin', 'active'),
(2, 'Tuyen', 'tuyen@gmail.com', '$2y$10$BHoD4vMmhQ0pl.RtQGrIXOv/6VmOSKep4H7EO3FhA7kqGlCk0iFsi', 'user', 'active'),
(3, 'Nam', 'nam@gmail.com', '$2y$10$doWAAY0TGqLrzlbqE5GCeOCkB7ABkWY6zHGJMEx623gGgvy5XeOsi', 'user', 'locked'),
(4, 'Doanh', 'doanh@gmal.com', '$2y$10$EtP5ran3cKYnkNezoK0AdOB63h.NddhU7eDDeqBt6moIgt3dngXDe', 'user', 'active'),
(5, 'tuyen', 'tuyen123@gmail.com', '$2y$10$/BXq6F6s21h4ABifOJ8iGunNL.6NR.Z7H9nuaZY76YWnBUtgnxENC', 'user', 'active'),
(6, 'manh', 'manh123@gmail.com', '$2y$10$J15dxu2Qn0lYGyu9eoPLVOEXj7q27BszTAZimCYVd2H2MThqbR7Ny', 'user', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
