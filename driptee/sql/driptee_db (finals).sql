-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2026 at 07:46 PM
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
-- Database: `driptee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash on Delivery',
  `payment_status` enum('Pending','Confirmed','Failed') DEFAULT 'Pending',
  `order_status` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `fullname`, `address`, `contact`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `created_at`) VALUES
(53, 7, 'Juan Di', '456 Kamote St', '09202220130', 349.00, 'Cash on Delivery', 'Confirmed', 'Cancelled', '2026-03-21 05:17:57'),
(54, 7, 'Juan Di', '456 Kamote St', '09202220130', 50.00, 'Cash on Delivery', 'Confirmed', 'Cancelled', '2026-03-21 05:20:33'),
(55, 7, 'Juan Di', '456 Kamote St', '09202220130', 349.00, 'Cash on Delivery', 'Confirmed', 'Cancelled', '2026-03-21 07:03:35'),
(56, 7, 'Juan', '4567 Kamote St', '09202220130', 299.00, 'Cash on Delivery', 'Confirmed', 'Processing', '2026-03-21 07:05:16'),
(57, 7, 'micaela marie', '4567 Kamote St', '09202220130', 1447.00, 'Cash on Delivery', 'Confirmed', 'Processing', '2026-03-21 07:05:45'),
(58, 7, 'Juan', '123 abcd', '12345678910', 849.00, 'Cash on Delivery', 'Confirmed', 'Cancelled', '2026-03-21 11:18:01'),
(59, 7, 'Juan', '123 abcd', '12345678910', 499.00, 'Cash on Delivery', 'Confirmed', 'Delivered', '2026-03-21 13:47:37'),
(60, 7, 'Juan', 'Holy Angel University', '123123123123', 1917.00, 'Cash on Delivery', 'Pending', 'Pending', '2026-03-21 14:09:26'),
(61, 7, 'Juan', 'Holy Angel University', '123123123123', 498.00, 'Cash on Delivery', 'Confirmed', 'Delivered', '2026-03-21 14:09:59'),
(62, 7, 'Juan', 'Holy Angel University', '123123123123', 649.00, 'Cash on Delivery', 'Confirmed', 'Processing', '2026-03-21 14:14:50'),
(63, 10, 'Joseph Daniel', '1234 watawat street', '09202220130', 499.00, 'Cash on Delivery', 'Confirmed', 'Delivered', '2026-03-26 18:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(85, 53, 37, 1, 50.00),
(86, 53, 31, 1, 299.00),
(87, 54, 37, 1, 50.00),
(88, 55, 37, 1, 50.00),
(89, 55, 31, 1, 299.00),
(90, 56, 30, 1, 299.00),
(91, 57, 37, 1, 50.00),
(92, 57, 31, 1, 299.00),
(93, 57, 28, 1, 599.00),
(94, 57, 23, 1, 499.00),
(96, 58, 19, 1, 499.00),
(97, 59, 18, 1, 499.00),
(98, 60, 24, 1, 599.00),
(99, 60, 49, 1, 220.00),
(100, 60, 19, 1, 499.00),
(101, 60, 29, 1, 599.00),
(102, 61, 16, 1, 199.00),
(103, 61, 14, 1, 299.00),
(104, 62, 17, 1, 499.00),
(105, 62, 50, 1, 150.00),
(106, 63, 13, 1, 499.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `status` enum('available','sold','hidden') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand`, `category`, `price`, `image_url`, `status`, `created_at`) VALUES
(1, 'Fendi White Hoodie M-L', 'Hoodie', 299.00, 'images/hoodies/fendi_white.PNG', 'available', '2026-03-21 06:22:37'),
(2, 'Vintage Gray Hoodie - M', 'Hoodie', 299.00, 'images/hoodies/vintage_gray.PNG', 'available', '2026-03-21 06:22:37'),
(3, 'Teezuz Green Hoodie - M', 'Hoodie', 349.00, 'images/hoodies/teezuz_green.PNG', 'available', '2026-03-21 06:22:37'),
(4, 'Flyerz Black Jacket - L', 'Hoodie', 399.00, 'images/hoodies/flyerz.PNG', 'available', '2026-03-21 06:22:37'),
(5, 'Champion Vintage - M', 'Hoodie', 499.00, 'images/hoodies/champion.PNG', 'available', '2026-03-21 06:22:37'),
(6, 'Supreme Pink Hoodie - S', 'Hoodie', 449.00, 'images/hoodies/supreme_pink.PNG', 'available', '2026-03-21 06:22:37'),
(7, 'Blue and Blue Denim Hoodie - L', 'Denim Jacket', 849.00, 'images/denim_jacket/blue_and_blue.PNG', 'available', '2026-03-21 06:22:37'),
(8, 'Denim Hoodie - M', 'Denim Jacket', 499.00, 'images/denim_jacket/denim_hoodie.PNG', 'available', '2026-03-21 06:22:37'),
(9, 'Green Jacket - L', 'Denim Jacket', 499.00, 'images/denim_jacket/green_jacket.PNG', 'available', '2026-03-21 06:22:37'),
(10, 'Denim Masters - M', 'Denim Jacket', 399.00, 'images/denim_jacket/denim_masters.PNG', 'available', '2026-03-21 06:22:37'),
(11, 'Wash Denim Long - L', 'Denim Jacket', 700.00, 'images/denim_jacket/wash_denim_long.PNG', 'available', '2026-03-21 06:22:37'),
(12, 'Blue Denim - S', 'Denim Jacket', 299.00, 'images/denim_jacket/blue_denim.PNG', 'available', '2026-03-21 06:22:37'),
(13, 'Wash Denim 1-Pocket - M', 'Denim Jacket', 499.00, 'images/denim_jacket/wash_one_pocket.PNG', 'available', '2026-03-21 06:22:37'),
(14, '8 Pockets Black Pants (28-30)', 'Pants', 299.00, 'images/pants/8pocket.PNG', 'sold', '2026-03-21 06:22:37'),
(15, 'Baggy Jeans (30-31)', 'Pants', 399.00, 'images/pants/baggy_jeans.PNG', 'available', '2026-03-21 06:22:37'),
(16, 'Cross Flare Pants (27-29)', 'Pants', 199.00, 'images/pants/cross_flare_pants.PNG', 'sold', '2026-03-21 06:22:37'),
(17, 'Baggy Blue Jeans (30)', 'Pants', 499.00, 'images/pants/baggy_blue_jeans.PNG', 'sold', '2026-03-21 06:22:37'),
(18, 'Wash Blue Pants (32)', 'Pants', 499.00, 'images/pants/wash_blue_pants.PNG', 'sold', '2026-03-21 06:22:37'),
(19, 'Wide Leg Pants (27-28)', 'Pants', 499.00, 'images/pants/wide_leg_pants.PNG', 'sold', '2026-03-21 06:22:37'),
(20, 'Carhartt Brown Pants (29)', 'Pants', 899.00, 'images/pants/carhartt_brown.PNG', 'sold', '2026-03-21 06:22:37'),
(21, 'Vintage Carhartt Pants (30)', 'Pants', 899.00, 'images/pants/vintage_carhartt.PNG', 'sold', '2026-03-21 06:22:37'),
(22, 'Jil Sander (B&W) M-L', 'Shirt', 499.00, 'images/tshirt/jil_sander.PNG', 'sold', '2026-03-21 06:22:37'),
(23, 'Adidas One Pocket - M', 'Shirt', 499.00, 'images/tshirt/adidas.PNG', 'sold', '2026-03-21 06:22:37'),
(24, 'Carhartt Tshirt - M', 'Shirt', 599.00, 'images/tshirt/carhartt_shirt.PNG', 'sold', '2026-03-21 06:22:37'),
(25, 'Lousano Polo Shirt M-L', 'Shirt', 399.00, 'images/tshirt/lousano.PNG', 'sold', '2026-03-21 06:22:37'),
(26, 'New Era Tshirt - L', 'Shirt', 499.00, 'images/tshirt/new_era.PNG', 'sold', '2026-03-21 06:22:37'),
(27, 'Blue Shirt - S', 'Shirt', 299.00, 'images/tshirt/blue_shirt.PNG', 'sold', '2026-03-21 06:22:37'),
(28, 'Hebru Brantley M-L', 'Shirt', 599.00, 'images/tshirt/hebru_brantley.PNG', 'sold', '2026-03-21 06:22:37'),
(29, 'Balenciaga Green Tee M-L', 'Shirt', 599.00, 'images/tshirt/balenciaga.PNG', 'sold', '2026-03-21 06:22:37'),
(30, 'Supreme Cream Shirt - M', 'Shirt', 299.00, 'images/tshirt/supreme_cream.PNG', 'sold', '2026-03-21 06:22:37'),
(31, 'Stussymax95 - L', 'Shirt', 299.00, 'images/tshirt/stussymax95.PNG', 'sold', '2026-03-21 06:22:37'),
(37, 'Gucci Pants - M', 'Pants', 150.00, 'images/pants/1771526004_gucci.jpg', 'sold', '2026-03-21 06:22:37'),
(49, 'Cargo Pants Black for Female - S', 'Pants', 220.00, 'images/pants/1774101934_cargo_pants black.png', 'sold', '2026-03-21 14:05:35'),
(50, 'Aonga Mens Hooded Denim - M', 'Denim Jacket', 150.00, 'images/denim_jacket/1774102298_Aonga Mens Hooded Denim.jpg', 'sold', '2026-03-21 14:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(5, 'admin', 'admin@gmail.com', '$2y$10$ChoBdJd8koZuzgagCXuT6e6y6eAAC8eidGrFhqJre7w1qI.IiC0ye', 'admin', '2026-02-19 15:15:55'),
(7, 'user', 'user@gmail.com', '$2y$10$WNv4ln2kCdJZDj5Jt.bMAOQouMIRPsbQlC.u5PhXP1Ed8EZfkzGjW', 'user', '2026-02-19 16:47:44'),
(10, 'Joseph', 'jo@gmail.com', '$2y$10$dHycELNS9cNcArL2P3Sgaeq7YtB8hdUfzHGZgHQL1rRIaZiMeYcvu', 'user', '2026-03-26 18:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(4, 7, 13, '2026-03-21 14:16:14'),
(5, 7, 15, '2026-03-21 14:16:24'),
(6, 10, 15, '2026-03-26 18:40:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_order` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `wishlist_product_fk` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_order` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
