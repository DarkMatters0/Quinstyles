-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 09:23 AM
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
-- Database: `quinstyle`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin','staff') NOT NULL DEFAULT 'customer',
  `is_staff` tinyint(1) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `email`, `contact`, `username`, `password`, `role`, `is_staff`, `is_admin`) VALUES
(1, 'cassymelody6@wmsu.edu.ph', '12345678901', 'admin', '$2y$10$BBEtoDOhfNLfRSkNbObnLOHoF7Ae2dQa7rQU/v2QvWEKBON0q.TeS', 'admin', 1, 1),
(2, 'gorb@wmsu.edu.ph', '23456789123', 'gorb', '$2y$10$.wVdEjiOITjx61Aq8RkwqOhcpMYAyn7Q6dMw5xzw9v.42e6Hh4n2G', 'customer', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `account_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2024-12-15 07:36:10', '2024-12-15 07:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `custom_uniform_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_uniform`
--

CREATE TABLE `custom_uniform` (
  `custom_uniform_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `chest_measurement` decimal(5,2) DEFAULT NULL,
  `waist_measurement` decimal(5,2) DEFAULT NULL,
  `hip_measurement` decimal(5,2) DEFAULT NULL,
  `shoulder_width` decimal(5,2) DEFAULT NULL,
  `sleeve_length` decimal(5,2) DEFAULT NULL,
  `pant_length` decimal(5,2) DEFAULT NULL,
  `custom_features` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `production_time_days` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_uniform`
--

INSERT INTO `custom_uniform` (`custom_uniform_id`, `name`, `gender`, `chest_measurement`, `waist_measurement`, `hip_measurement`, `shoulder_width`, `sleeve_length`, `pant_length`, `custom_features`, `price`, `production_time_days`, `created_at`, `updated_at`) VALUES
(10, 'unif', 'male', 35.00, 33.00, 36.00, 15.00, 34.00, 40.00, 'moo', 500.00, NULL, '2024-12-15 18:30:16', '2024-12-15 18:31:49'),
(11, 'unifform', 'male', 35.00, 33.00, 35.00, 16.00, 25.00, 39.00, 'no', 500.00, NULL, '2024-12-16 16:16:15', '2024-12-16 16:16:37'),
(12, 'unifform', 'female', 35.00, 31.00, 35.00, 16.00, 25.00, 39.00, 'no', 0.00, NULL, '2024-12-16 16:38:47', '2024-12-16 16:38:47'),
(13, 'unifform', 'male', 35.00, 31.00, 36.00, 16.00, 25.00, 39.00, 'no', 399.00, NULL, '2024-12-16 16:41:24', '2024-12-16 16:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `status` enum('paid','completed','refund','claimed') NOT NULL,
  `order_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `account_id`, `status`, `order_date`) VALUES
(7, 2, 'paid', '2024-12-19 16:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `custom_uniform_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `custom_uniform_id`, `quantity`, `total`, `created_at`, `updated_at`) VALUES
(7, 7, 59, NULL, 1, 450.00, '2024-12-19 08:20:49', '2024-12-19 08:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `size` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `gender`, `size`, `price`, `created_at`, `updated_at`) VALUES
(25, 'polo', 'School uniform', 'male', 29, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(26, 'polo', 'School uniform', 'male', 30, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(27, 'polo', 'School uniform', 'male', 31, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(28, 'polo', 'School uniform', 'male', 32, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(29, 'polo', 'School uniform', 'male', 33, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(30, 'polo', 'School uniform', 'male', 34, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(31, 'polo', 'School uniform', 'male', 35, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(32, 'polo', 'School uniform', 'male', 36, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(33, 'polo', 'School uniform', 'male', 37, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(34, 'polo', 'School uniform', 'male', 38, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(35, 'polo', 'School uniform', 'male', 39, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(36, 'polo', 'School uniform', 'male', 40, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(37, 'polo', 'School uniform', 'male', 41, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(38, 'polo', 'School uniform', 'male', 42, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(39, 'polo', 'School uniform', 'male', 43, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(40, 'polo', 'School uniform', 'male', 44, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(41, 'polo', 'School uniform', 'male', 45, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(42, 'polo', 'School uniform', 'female', 29, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(43, 'polo', 'School uniform', 'female', 30, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(44, 'polo', 'School uniform', 'female', 31, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(45, 'polo', 'School uniform', 'female', 32, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(46, 'polo', 'School uniform', 'female', 33, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(47, 'polo', 'School uniform', 'female', 34, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(48, 'polo', 'School uniform', 'female', 35, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(49, 'polo', 'School uniform', 'female', 36, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(50, 'polo', 'School uniform', 'female', 37, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(51, 'polo', 'School uniform', 'female', 38, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(52, 'polo', 'School uniform', 'female', 39, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(53, 'polo', 'School uniform', 'female', 40, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(54, 'polo', 'School uniform', 'female', 41, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(55, 'polo', 'School uniform', 'female', 42, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(56, 'polo', 'School uniform', 'female', 43, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(57, 'polo', 'School uniform', 'female', 44, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(58, 'polo', 'School uniform', 'female', 45, 500.00, '2024-12-11 05:34:31', '2024-12-11 05:34:31'),
(59, 'pants', 'School uniform pants', 'male', 29, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(60, 'pants', 'School uniform pants', 'male', 30, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(61, 'pants', 'School uniform pants', 'male', 31, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(62, 'pants', 'School uniform pants', 'male', 32, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(63, 'pants', 'School uniform pants', 'male', 33, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(64, 'pants', 'School uniform pants', 'male', 34, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(65, 'pants', 'School uniform pants', 'male', 35, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(66, 'pants', 'School uniform pants', 'male', 36, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(67, 'pants', 'School uniform pants', 'male', 37, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(68, 'pants', 'School uniform pants', 'male', 38, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(69, 'pants', 'School uniform pants', 'male', 39, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(70, 'pants', 'School uniform pants', 'male', 40, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(71, 'pants', 'School uniform pants', 'male', 41, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(72, 'pants', 'School uniform pants', 'male', 42, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(73, 'pants', 'School uniform pants', 'male', 43, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(74, 'pants', 'School uniform pants', 'male', 44, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(75, 'pants', 'School uniform pants', 'male', 45, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(76, 'pants', 'School uniform pants', 'female', 29, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(77, 'pants', 'School uniform pants', 'female', 30, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(78, 'pants', 'School uniform pants', 'female', 31, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(79, 'pants', 'School uniform pants', 'female', 32, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(80, 'pants', 'School uniform pants', 'female', 33, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(81, 'pants', 'School uniform pants', 'female', 34, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(82, 'pants', 'School uniform pants', 'female', 35, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(83, 'pants', 'School uniform pants', 'female', 36, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(84, 'pants', 'School uniform pants', 'female', 37, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(85, 'pants', 'School uniform pants', 'female', 38, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(86, 'pants', 'School uniform pants', 'female', 39, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(87, 'pants', 'School uniform pants', 'female', 40, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(88, 'pants', 'School uniform pants', 'female', 41, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(89, 'pants', 'School uniform pants', 'female', 42, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(90, 'pants', 'School uniform pants', 'female', 43, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(91, 'pants', 'School uniform pants', 'female', 44, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(92, 'pants', 'School uniform pants', 'female', 45, 450.00, '2024-12-11 22:57:51', '2024-12-11 22:57:51'),
(93, 'PE-shirt', 'PE uniform shirt', 'male', 29, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(94, 'PE-shirt', 'PE uniform shirt', 'male', 30, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(95, 'PE-shirt', 'PE uniform shirt', 'male', 31, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(96, 'PE-shirt', 'PE uniform shirt', 'male', 32, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(97, 'PE-shirt', 'PE uniform shirt', 'male', 33, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(98, 'PE-shirt', 'PE uniform shirt', 'male', 34, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(99, 'PE-shirt', 'PE uniform shirt', 'male', 35, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(110, 'PE-shirt', 'PE uniform shirt', 'female', 29, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(111, 'PE-shirt', 'PE uniform shirt', 'female', 30, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(112, 'PE-shirt', 'PE uniform shirt', 'female', 31, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(113, 'PE-shirt', 'PE uniform shirt', 'female', 32, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(114, 'PE-shirt', 'PE uniform shirt', 'female', 33, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(115, 'PE-shirt', 'PE uniform shirt', 'female', 34, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(116, 'PE-shirt', 'PE uniform shirt', 'female', 35, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(117, 'PE-shirt', 'PE uniform shirt', 'female', 36, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(118, 'PE-shirt', 'PE uniform shirt', 'female', 37, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(119, 'PE-shirt', 'PE uniform shirt', 'female', 38, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(120, 'PE-shirt', 'PE uniform shirt', 'female', 39, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(121, 'PE-shirt', 'PE uniform shirt', 'female', 40, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(122, 'PE-shirt', 'PE uniform shirt', 'female', 41, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(123, 'PE-shirt', 'PE uniform shirt', 'female', 42, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(124, 'PE-shirt', 'PE uniform shirt', 'female', 43, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(125, 'PE-shirt', 'PE uniform shirt', 'female', 44, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(126, 'PE-shirt', 'PE uniform shirt', 'female', 45, 250.00, '2024-12-11 23:15:02', '2024-12-11 23:15:02'),
(127, 'PE-pants', 'PE uniform pants', 'male', 24, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(128, 'PE-pants', 'PE uniform pants', 'male', 25, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(129, 'PE-pants', 'PE uniform pants', 'male', 26, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(130, 'PE-pants', 'PE uniform pants', 'male', 27, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(131, 'PE-pants', 'PE uniform pants', 'male', 28, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(132, 'PE-pants', 'PE uniform pants', 'male', 29, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(133, 'PE-pants', 'PE uniform pants', 'male', 30, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(134, 'PE-pants', 'PE uniform pants', 'male', 31, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(135, 'PE-pants', 'PE uniform pants', 'male', 32, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(136, 'PE-pants', 'PE uniform pants', 'male', 33, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(137, 'PE-pants', 'PE uniform pants', 'male', 34, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(138, 'PE-pants', 'PE uniform pants', 'male', 35, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(139, 'PE-pants', 'PE uniform pants', 'male', 36, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(140, 'PE-pants', 'PE uniform pants', 'male', 37, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(141, 'PE-pants', 'PE uniform pants', 'male', 38, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(142, 'PE-pants', 'PE uniform pants', 'male', 39, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(143, 'PE-pants', 'PE uniform pants', 'male', 40, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(144, 'PE-pants', 'PE uniform pants', 'male', 41, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(145, 'PE-pants', 'PE uniform pants', 'male', 42, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(146, 'PE-pants', 'PE uniform pants', 'male', 43, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(147, 'PE-pants', 'PE uniform pants', 'male', 44, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(148, 'PE-pants', 'PE uniform pants', 'female', 24, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(149, 'PE-pants', 'PE uniform pants', 'female', 25, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(150, 'PE-pants', 'PE uniform pants', 'female', 26, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(151, 'PE-pants', 'PE uniform pants', 'female', 27, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(152, 'PE-pants', 'PE uniform pants', 'female', 28, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(153, 'PE-pants', 'PE uniform pants', 'female', 29, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(154, 'PE-pants', 'PE uniform pants', 'female', 30, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(155, 'PE-pants', 'PE uniform pants', 'female', 31, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(156, 'PE-pants', 'PE uniform pants', 'female', 32, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(157, 'PE-pants', 'PE uniform pants', 'female', 33, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(158, 'PE-pants', 'PE uniform pants', 'female', 34, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(159, 'PE-pants', 'PE uniform pants', 'female', 35, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(160, 'PE-pants', 'PE uniform pants', 'female', 36, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(162, 'PE-pants', 'PE uniform pants', 'female', 38, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(163, 'PE-pants', 'PE uniform pants', 'female', 39, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(164, 'PE-pants', 'PE uniform pants', 'female', 40, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(165, 'PE-pants', 'PE uniform pants', 'female', 41, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(166, 'PE-pants', 'PE uniform pants', 'female', 42, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(167, 'PE-pants', 'PE uniform pants', 'female', 43, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18'),
(168, 'PE-pants', 'PE uniform pants', 'female', 44, 250.00, '2024-12-11 23:15:18', '2024-12-11 23:15:18');


-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`receipt_id`, `order_id`, `receipt_number`, `payment_date`, `payment_method`, `total_amount`) VALUES
(7, 7, 'REC-6763d761263984.29200331', '2024-12-19 16:20:49', 'Credit Card', 450.00);

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

CREATE TABLE `refund` (
  `refund_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `description` varchar(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stock_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(12, 59, 5, '2024-12-19 07:16:30', '2024-12-19 08:03:36'),
(13, 60, 2, '2024-12-19 07:30:33', '2024-12-19 07:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `stock_in_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_in`
--

INSERT INTO `stock_in` (`stock_in_id`, `stock_id`, `quantity`, `reason`, `created_at`) VALUES
(26, 12, 2, 'add', '2024-12-19 07:16:30'),
(27, 12, 1, 'add', '2024-12-19 07:17:07'),
(28, 12, 1, 'add', '2024-12-19 07:28:32'),
(29, 13, 1, 'add', '2024-12-19 07:30:33'),
(30, 13, 1, 'add', '2024-12-19 07:30:46'),
(31, 12, 1, 'wtf', '2024-12-19 08:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `stock_out`
--

CREATE TABLE `stock_out` (
  `stock_out_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_out`
--

INSERT INTO `stock_out` (`stock_out_id`, `stock_id`, `quantity`, `reason`, `created_at`) VALUES
(24, 12, 1, 'damaged', '2024-12-19 07:16:51'),
(25, 13, 1, 'minus', '2024-12-19 07:31:00'),
(26, 12, 1, 'wtf', '2024-12-19 08:04:38'),
(27, 12, 1, 'minus', '2024-12-19 08:19:55'),
(28, 12, 1, 'purchase', '2024-12-19 08:20:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_cart_account_id` (`account_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `fk_cart_items_cart_id` (`cart_id`),
  ADD KEY `fk_cart_items_product_id` (`product_id`),
  ADD KEY `fk_cart_items_custom_uniform_id` (`custom_uniform_id`);

--
-- Indexes for table `custom_uniform`
--
ALTER TABLE `custom_uniform`
  ADD PRIMARY KEY (`custom_uniform_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_account_id` (`account_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_order_items_order_id` (`order_id`),
  ADD KEY `fk_order_items_product_id` (`product_id`),
  ADD KEY `fk_order_items_custom_uniform_id` (`custom_uniform_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `fk_receipts_order_id` (`order_id`);

--
-- Indexes for table `refund`
--
ALTER TABLE `refund`
  ADD PRIMARY KEY (`refund_id`),
  ADD KEY `fk_refund_order_id` (`order_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `fk_stocks_product_id` (`product_id`);

--
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`stock_in_id`),
  ADD KEY `fk_stock_in_stock_id` (`stock_id`);

--
-- Indexes for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`stock_out_id`),
  ADD KEY `fk_stock_out_stock_id` (`stock_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `custom_uniform`
--
ALTER TABLE `custom_uniform`
  MODIFY `custom_uniform_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `refund`
--
ALTER TABLE `refund`
  MODIFY `refund_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `stock_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `stock_out_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart_id` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_custom_uniform_id` FOREIGN KEY (`custom_uniform_id`) REFERENCES `custom_uniform` (`custom_uniform_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cart_items_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_custom_uniform_id` FOREIGN KEY (`custom_uniform_id`) REFERENCES `custom_uniform` (`custom_uniform_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_order_items_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `fk_receipts_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `refund`
--
ALTER TABLE `refund`
  ADD CONSTRAINT `fk_refund_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `fk_stocks_product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD CONSTRAINT `fk_stock_in_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`stock_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD CONSTRAINT `fk_stock_out_stock_id` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`stock_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;