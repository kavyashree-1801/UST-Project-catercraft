-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 10:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catercraft`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Kavya Mohan', 'kavyashreedmmohan@gmail.com', 'i want more details about catter plan', '2025-12-09 07:25:15'),
(3, 'Mahesh', 'Mahesh@gmail.com', 'i want info about Catering platter', '2025-12-21 14:33:39');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `feedback`, `created_at`) VALUES
(1, 'Mahesh', 'Mahesh@gmail.com', 4, 'The catercraft app is good!!:)', '2025-12-21 14:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `order_status` enum('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `username`, `order_date`, `order_status`, `payment_id`, `payment_status`, `created_at`) VALUES
(3, 'Rekha Rao', '2025-12-07 10:41:29', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(4, 'Rekha Rao', '2025-12-07 10:42:48', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(5, 'Rekha Rao', '2025-12-07 10:45:09', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(8, 'Rekha Rao', '2025-12-07 13:23:47', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(9, 'Rekha Rao', '2025-12-07 13:26:27', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(10, 'Rekha Rao', '2025-12-07 13:29:07', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(11, 'Rekha Rao', '2025-12-07 13:31:00', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(12, 'Sujay Bhat', '2025-12-07 13:34:55', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(13, 'Sujay Bhat', '2025-12-07 13:36:13', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(19, 'Sujay Bhat', '2025-12-07 13:54:55', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(20, 'Sujay Bhat', '2025-12-07 14:12:58', 'Completed', NULL, 'Paid', '2025-12-09 13:23:09'),
(23, 'Uma Das', '2025-12-07 19:02:11', 'Processing', NULL, 'Paid', '2025-12-09 13:23:09'),
(24, 'Rekha Rao', '2025-12-07 19:05:32', 'Processing', NULL, 'Paid', '2025-12-09 13:23:09'),
(28, 'Kavya Mohan', '2025-12-09 19:42:06', 'Processing', 'pay_RpXd24GCfmaSNA', 'Paid', '2025-12-09 14:12:06'),
(29, 'Kavya Mohan', '2025-12-09 19:43:39', 'Processing', NULL, 'Paid', '2025-12-09 14:13:39'),
(30, 'Mahesh', '2025-12-21 21:18:57', 'Pending', 'pay_RuJgldbJbGdvgE', 'Paid', '2025-12-21 15:48:57'),
(31, 'Mahesh', '2025-12-21 21:27:43', 'Pending', NULL, 'Paid', '2025-12-21 15:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_name`, `quantity`, `price`) VALUES
(1, 3, 'Catering Platter', 1, 1200.00),
(2, 4, 'Homemade Sweets', 1, 150.00),
(3, 5, 'Village Snacks', 1, 100.00),
(7, 8, 'Rural Thali', 1, 250.00),
(8, 8, 'Pakora (Mixed Veg)', 1, 30.00),
(9, 8, 'Kheer', 1, 40.00),
(10, 9, 'Rural Thali', 1, 250.00),
(11, 9, 'Kheer', 1, 40.00),
(12, 10, 'Rural Thali', 1, 250.00),
(13, 10, 'Bhel Puri', 1, 35.00),
(14, 11, 'Rural Thali', 1, 250.00),
(15, 11, 'Gulab Jamun (2 pcs)', 1, 35.00),
(16, 12, 'Idli with Sambar', 1, 40.00),
(17, 12, 'Gulab Jamun (2 pcs)', 1, 35.00),
(18, 13, 'Rural Thali', 1, 250.00),
(24, 19, 'Homemade Sweets', 2, 150.00),
(25, 20, 'Village Snacks', 1, 100.00),
(30, 23, 'Vegetable Thali', 1, 100.00),
(31, 23, 'Kesaribath', 1, 50.00),
(32, 24, 'Village Snacks', 1, 100.00),
(33, 24, 'Vegetable Thali', 1, 100.00),
(40, 28, 'Homemade Sweets', 1, 150.00),
(41, 28, 'Chapati Pack (5 pcs)', 1, 30.00),
(42, 28, 'Mixed Vegetable Curry', 1, 80.00),
(43, 29, 'Organic Roti', 1, 50.00),
(44, 29, 'Mixed Vegetable Curry', 1, 80.00),
(45, 30, 'Rava Idli', 1, 45.00),
(46, 30, 'Samosa (2 pcs)', 1, 25.00),
(47, 31, 'Vegetable Paratha', 1, 40.00),
(48, 31, 'Gulab Jamun (2 pcs)', 1, 35.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` text NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) NOT NULL DEFAULT 'Uncategorized'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `uploaded_by`, `created_at`, `category`) VALUES
(6, 'Rural Thali', 'Authentic Indian rural meal', 250.00, 'https://i.ytimg.com/vi/UdO8YMfFF7I/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCaMtawIl02uaLlftB8hilaMpoHhg', 1, '2025-12-02 14:45:50', 'Lunch'),
(7, 'Homemade Sweets', 'Delicious festive sweets', 150.00, 'https://images.livemint.com/img/2023/10/23/original/mithai_festive_season_2023_1698049853905.jpg', 1, '2025-12-02 14:45:50', 'Dessert'),
(8, 'Village Snacks', 'Crispy traditional snacks', 100.00, 'https://thumbs.dreamstime.com/b/indian-street-food-vendor-selling-indian-local-snacks-sweets-street-food-india-very-popular-village-rural-areas-250603767.jpg', 1, '2025-12-02 14:45:50', 'Snack'),
(9, 'Catering Platter', 'Bulk order platter for events', 1200.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTofGKtbCg-CNagqf1Jlfa5oT8IRbD6Pmo4FA&s', 1, '2025-12-02 14:45:50', 'Lunch/Dinner'),
(10, 'Organic Roti', 'Freshly made organic wheat rotis', 50.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTy2A9n2smDJzatbX9n3kfN9-YAhVXiv0zNlQ&s', 1, '2025-12-02 14:45:50', 'Breakfast'),
(31, 'Idli with Sambar', 'Soft steamed idlis served with sambar and coconut chutney.', 40.00, 'https://sapanarestaurant.com/wp-content/uploads/2019/11/idli-sambar.jpg', 6, '2025-12-07 07:32:18', 'Breakfast'),
(32, 'Rava Idli', 'Semolina idlis with mild spices and chutney.', 45.00, 'https://media.istockphoto.com/id/481746758/photo/rava-idli-with-sagu.jpg?s=612x612&w=0&k=20&c=LdJO-OQHWnIGyjpzoRG6lKwdq4zj_eTWsqz6CBzA6Ag=', 6, '2025-12-07 07:32:18', 'Breakfast'),
(33, 'Poori with Potato Curry', 'Fluffy pooris served with lightly spiced potato curry.', 50.00, 'https://i.pinimg.com/564x/b5/c5/1f/b5c51f327936b316d77b05bf5df617d1.jpg', 6, '2025-12-07 07:32:18', 'Breakfast'),
(34, 'Upma', 'Traditional savory semolina porridge with vegetables.', 35.00, 'https://thewhiskaddict.com/wp-content/uploads/2024/07/IMG_0332-scaled.jpg', 2, '2025-12-07 07:32:18', 'Breakfast'),
(35, 'Pongal', 'Rice and lentil dish tempered with pepper and ghee.', 40.00, 'https://justhomemade.files.wordpress.com/2011/01/pongal.jpg', 1, '2025-12-07 07:32:18', 'Breakfast'),
(36, 'Vegetable Paratha', 'Stuffed flatbread with mixed vegetables, served with curd.', 40.00, 'https://www.vegrecipesofindia.com/wp-content/uploads/2018/10/veg-paratha-recipe-1.jpg', 2, '2025-12-07 07:32:18', 'Breakfast'),
(37, 'Vegetable Thali', 'Rice, dal, 2 seasonal vegetables, chapati, and pickle.', 100.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSSEEr5k29KxU4WzzX-s0FuVIWzRNpJhqmwOg&s', 2, '2025-12-07 07:32:18', 'Lunch/Dinner'),
(38, 'Dal Tadka', 'Yellow lentils tempered with garlic and spices.', 60.00, 'https://static.toiimg.com/photo/57746323.cms', 2, '2025-12-07 07:32:18', 'Lunch/Dinner'),
(39, 'Mixed Vegetable Curry', 'Seasonal vegetables cooked in mild spices.', 80.00, 'https://greenbowl2soul.com/wp-content/uploads/2021/06/Indian-vegetable-curry.jpg', 1, '2025-12-07 07:32:18', 'Lunch/Dinner'),
(41, 'Chapati Pack (5 pcs)', 'Freshly made chapatis, served hot.', 30.00, 'https://5.imimg.com/data5/SELLER/Default/2023/9/348193740/WB/HF/SZ/196409685/whatsapp-image-2023-09-28-at-2-48-23-pm-500x500.jpeg', 1, '2025-12-07 07:32:18', 'Lunch/Dinner'),
(42, 'Samosa (2 pcs)', 'Deep-fried pastry stuffed with spiced potato and peas.', 25.00, 'https://www.foodline.sg/PageImage/Caterer/Nalan-Restaurant-(City-Hall)/84623_large.webp', 1, '2025-12-07 07:32:18', 'Snack'),
(43, 'Pakora (Mixed Veg)', 'Crispy batter-fried seasonal vegetables.', 30.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSSagOA7LOQOilEdrY998Es2EVitb1E9kUQjQ&s', 1, '2025-12-07 07:32:18', 'Snack'),
(44, 'Vada (2 pcs)', 'South Indian savory fried lentil dumplings.', 25.00, 'https://img.freepik.com/premium-photo/indian-fried-snack-medu-vada-with-sambar-coconut-chutney-plate-rustic-wooden-background-savoury-fried-snack-kerala-tamil-nadu-south-india-selective-focus_726363-438.jpg', 1, '2025-12-07 07:32:18', 'Snack'),
(45, 'Pani Puri', 'Hollow crispy puris with spicy water and potato stuffing.', 40.00, 'https://media.istockphoto.com/id/2162493341/photo/exploring-the-tangy-spicy-and-refreshing-delight-of-pani-puri-indias-favourite-street-food.jpg?s=612x612&w=0&k=20&c=b6IOcUU4KjppLy1rlLHxaupkXEtjARkU_7n6d8K7fu4=', 6, '2025-12-07 07:32:18', 'Snack'),
(46, 'Bhel Puri', 'Tangy puffed rice snack with chutneys and vegetables.', 35.00, 'https://www.livofy.com/health/wp-content/uploads/2023/06/Untitled-design-4-2.png', 2, '2025-12-07 07:32:18', 'Snack'),
(47, 'Tea', 'Traditional masala chai, served hot.', 10.00, 'https://www.munatycooking.com/wp-content/uploads/2024/04/Three-glasses-filled-with-karak-chai.jpg', 1, '2025-12-07 07:32:18', 'Beverage'),
(48, 'Fresh Buttermilk', 'Cool and refreshing lightly spiced buttermilk.', 15.00, 'https://x9s2d6a3.delivery.rocketcdn.me/wp-content/uploads/2019/01/masala-chaas-4_1200x1200.jpg', 1, '2025-12-07 07:32:18', 'Beverage'),
(49, 'Kheer', 'Sweet rice pudding with cardamom and nuts.', 40.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRkvTdnGg11hKKoVzkPhxUgGz58dAYEgWEKoA&s', 2, '2025-12-07 07:32:18', 'Dessert'),
(50, 'Gulab Jamun (2 pcs)', 'Soft deep-fried milk dumplings soaked in sugar syrup.', 35.00, 'https://inredberry.com/wp-content/uploads/2023/10/Gulab-Jamun-2-Pcs.png', 1, '2025-12-07 07:32:18', 'Dessert'),
(55, 'Kesaribath', 'Kesari bath is a quick sweet dish made with semolina, ghee, sugar and kesari aka saffron.', 50.00, 'https://upload.wikimedia.org/wikipedia/commons/9/92/KEsari_baat.jpg', 6, '2025-12-07 05:28:29', 'Dessert');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `reset_token`, `token_expiry`) VALUES
(1, 'Rekha Rao', 'rekha@gmail.com', '$2y$10$xRQrMmmJCi2XzhtKe.iypepHn84rJvNBe.JnUeRQ9AUywm5eJ5LA6', '9448709890', 'RR Nagar,Bengaluru', 'user', '2025-12-02 13:22:17', NULL, NULL),
(2, 'Harini Nadig', 'harini@yahoo.com', '$2y$10$RPDIJBZySb6NQKLKkim9ROjjeWpjHSvtRb.4eoydu3YA9PrthducK', '8204356732', 'Rajajinagar Bengaluru', 'user', '2025-12-07 02:48:23', NULL, NULL),
(6, 'Admin', 'admin@catercraft.com', '$2y$10$mT.kCieBKxJFxoqLhqyZBuOe.52czoxvpxECL0E5fSslDfGWLj3tm', 'NULL', 'NULL', 'admin', '2025-12-07 03:19:25', NULL, NULL),
(8, 'Uma Das', 'uma@gmail.com', '$2y$10$VQn000QBiS0a3xVxB./DDO4IHyJhYyzLG5/yYGHF9mH7nlW4ipkaO', '9448709842', 'Bank Colony Bengaluru', 'user', '2025-12-07 11:58:42', NULL, NULL),
(9, 'Kavya Mohan', 'kavyashreedmmohan@gmail.com', '$2y$10$LO9Edr.R.NrSm.21bFRihutXRhNIurWV1z6rxGzaShDSgOZ28WU7y', '9448907864', 'JP nagar Bengaluru', 'user', '2025-12-09 07:22:56', NULL, NULL),
(10, 'Mahesh', 'Mahesh@gmail.com', '$2y$10$OPhT03lRve0fg4J0DrdMWONFQE/yOcJ8.jaYhSf8hKj4VFUwGISye', '9448709843', 'Jaynagar,Bengaluru', 'user', '2025-12-21 14:12:06', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
