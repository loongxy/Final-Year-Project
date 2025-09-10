-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 08:41 AM
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
-- Database: `beauty`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','completed','completed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `name`, `email`, `phone`, `service`, `date`, `time`, `created_at`, `status`) VALUES
(4, 'Xingyi', 'loongxingyi@gmail.com', '0127728939', 'Hydrating Injections', '2025-04-12', '13:30:00', '2025-04-10 03:28:00', 'completed'),
(5, 'xingyi', 'loongxingyi@gmail.com', '01136578567', 'Collagen Care Treatment', '2025-04-22', '15:30:00', '2025-04-10 05:12:30', 'pending'),
(10, 'Xingyi', 'loongxingyi@gamil.com', '0127728939', 'Eyebrow Microblading', '2025-04-12', '13:00:00', '2025-04-12 06:41:58', ''),
(11, 'Xingyi', 'loongxingyi@gamil.com', '0127728939', 'Hand Care', '2025-04-17', '14:30:00', '2025-04-12 06:56:18', 'pending'),
(15, 'Xingyi', 'loongxingyi@gmail.com', '0127728939', 'Lymphatic Detox Massage', '2025-04-25', '16:00:00', '2025-04-12 08:20:47', 'pending'),
(20, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Deep Cleansing', '2025-05-01', '10:30:00', '2025-04-14 02:24:02', 'pending'),
(21, 'Lim Weng Hin', 'limwenghin13@gmail.com', '0178888948', 'Deep Cleansing', '2025-04-17', '13:13:00', '2025-04-15 02:13:53', 'pending'),
(22, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Deep Cleansing', '2025-04-17', '12:30:00', '2025-04-15 03:24:19', 'pending'),
(23, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Collagen Care Treatment', '2025-04-18', '09:59:00', '2025-04-18 10:52:38', 'pending'),
(24, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Sea Salt Scrub', '2025-04-18', '16:07:00', '2025-04-18 11:04:35', 'pending'),
(25, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Collagen Care Treatment', '2025-04-18', '15:00:00', '2025-04-18 11:07:39', 'pending'),
(26, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Deep Cleansing', '2025-04-18', '13:00:00', '2025-04-18 11:08:03', 'pending'),
(27, 'xingyi', 'loongxingyi@gmail.com', '0127728939', 'Deep Cleansing', '2025-04-18', '13:01:00', '2025-04-18 11:08:47', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `feedback`, `created_at`) VALUES
(5, 'Xingyi', 'loongxingyi@gmail.com', 'The staff was very professional and the massage techniques were very relaxing', '2025-04-12 12:50:52'),
(6, 'Rebecca', 'rebeccalim0505@gmail.com', 'The whole environment is clean and tidy and there is a light scent in the air, the experience was great!', '2025-04-12 12:54:50'),
(7, 'QiXian', 'qingxian0301@gmail.com', 'Suggest adding free Wi-Fi to make it easier for clients to access the internet while waiting', '2025-04-12 13:08:17'),
(8, 'Weiyi', 'zweiyi219@gmail.com', 'The receptionist was very welcoming and attentive, making you feel right at home', '2025-04-12 13:12:33'),
(9, 'Jingning', 'laujingning@gmail.com', 'The technician listened very patiently to my needs and gave professional advice', '2025-04-12 13:27:15'),
(10, 'Eunice', 'eunicelim0311@gmail.com', 'The condition of my skin has improved a lot and the results of the treatment are remarkable', '2025-04-12 13:31:36'),
(11, 'Brian', 'loongxingyi@gmail.com', 'A small snack or drink could be prepared to make the waiting time more enjoyable', '2025-04-12 15:36:09'),
(12, 'Lim Weng Hin', 'limwenghin13@gmail.com', 'Lack of product\r\nDo look into the products', '2025-04-15 10:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `quantity`, `price`) VALUES
(103, 'Blush', 13, 68.00),
(104, 'Concealer', 6, 59.00),
(105, 'Setting Spray', 5, 75.00),
(106, 'Eyeshadow Palette', 3, 86.00),
(107, 'Tattoo Needle', 10, 69.90),
(108, 'Lipstick', 3, 129.90),
(109, 'Hand Cream', 15, 19.90),
(110, 'Massage Oils', 9, 56.90),
(111, 'Soothing Mask', 27, 25.00),
(112, 'Facial Cleansers', 12, 56.90),
(113, 'Hydrating Toners', 20, 49.90);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_consumption`
--

CREATE TABLE `inventory_consumption` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_consumed` int(11) NOT NULL,
  `remaining_stock` int(11) NOT NULL,
  `consumption_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `phone`, `department`) VALUES
(1, 'Zhi Yan', 'zhiyan1212@gmail.com', '0123456789', 'Makeup Services'),
(2, 'Xin Yi', 'loongxinyi2112@gmail.com', '0187634651', 'Facial Treatments'),
(3, 'Wan Qi', 'wanqi990224@gmail.com', '0107042572', 'Semi-Permanent Makeup');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `token_expiry` datetime NOT NULL,
  `reset_token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `token_expiry`, `reset_token`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-04-07 12:44:14', '', '2025-04-13 10:13:50'),
(2, 'xyy', 'loongxingyi@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user', '2025-04-19 13:40:53', '14941c1b6cf98e3ffdcac3abb40b7455', '2025-04-13 10:13:50'),
(8, 'Rebecca', 'rebeccalim0505@gmail.com', 'd0a687942024b444dc5302f1f42fe633', 'user', '2025-04-12 22:39:13', '', '2025-04-13 10:13:50'),
(9, 'Qi Xian', 'qingxian0301@gmail.com', '45686f842fc34473ab5bd255dcf730a1', 'user', '2025-04-12 22:39:56', '', '2025-04-13 10:13:50'),
(10, 'Jing Ning', 'laujingning@gmail.com', '562408fac6852adde9b8312f14002e06', 'user', '2025-04-12 22:41:15', '', '2025-04-13 10:13:50'),
(11, 'Eunice Lim', 'eunicelim0311@gmail.com', '1c20abf0c06eeefb737322a76974eebe', 'user', '2025-04-12 22:41:54', '', '2025-04-13 10:13:50'),
(12, 'Wei Yi', 'zweiyi219@gmail.com', '5fbd356fafa366ef75d6a61d7785701f', 'user', '2025-04-12 22:42:50', '', '2025-04-13 10:13:50'),
(13, 'Rebecca', 'limwwenghin13@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'user', '0000-00-00 00:00:00', '', '2025-04-18 10:57:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`service`,`time`,`date`) USING BTREE;

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_consumption`
--
ALTER TABLE `inventory_consumption`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
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
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `inventory_consumption`
--
ALTER TABLE `inventory_consumption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_consumption`
--
ALTER TABLE `inventory_consumption`
  ADD CONSTRAINT `inventory_consumption_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
