-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2023 at 04:27 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `komitex_stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(255) NOT NULL,
  `summary` varchar(500) NOT NULL,
  `action_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `company_id` varchar(20) NOT NULL,
  `inventory_unique_id` varchar(20) DEFAULT NULL,
  `inventory_name` varchar(50) DEFAULT NULL,
  `initiator` varchar(30) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch`
--

CREATE TABLE `dispatch` (
  `id` int(255) NOT NULL,
  `order_id` int(255) NOT NULL,
  `order_details` varchar(500) NOT NULL,
  `inventory_name` varchar(30) NOT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `location` varchar(20) NOT NULL,
  `product` varchar(30) NOT NULL,
  `multiple_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`multiple_products`)),
  `dispatch_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `edited_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `dispatched_by` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'dispatched'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_unique_id` varchar(20) NOT NULL,
  `inventory_name` varchar(30) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `inventory_created_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `inventory_updated_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_signatory`
--

CREATE TABLE `inventory_signatory` (
  `id` int(255) NOT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `inventory_name` varchar(50) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(255) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `location` varchar(30) NOT NULL,
  `charge` int(10) NOT NULL,
  `created_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `edited_timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `company_id`, `location`, `charge`, `created_timestamp`, `edited_timestamp`) VALUES
(1, 'mJl1Q2dJyvX9iwz', 'Lidköping', 3500, '2023-04-09 13:23:04', '2023-04-09 13:23:04'),
(2, 'rJc5nUvDpL6gKxh', 'Želešice', 3500, '2023-04-09 13:23:04', '2023-04-09 13:23:04'),
(3, 'rJc5nUvDpL6gKxh', 'Kisovec', 3500, '2023-04-09 13:23:04', '2023-04-09 13:23:04'),
(4, 'mJl1Q2dJyvX9iwz', 'Amper', 3000, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(5, 'mJl1Q2dJyvX9iwz', 'Tân Hòa', 3500, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(6, 'rJc5nUvDpL6gKxh', 'Maştağa', 4000, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(7, 'mJl1Q2dJyvX9iwz', 'Brest', 5000, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(8, 'rJc5nUvDpL6gKxh', 'Ziroudani', 3000, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(9, 'mJl1Q2dJyvX9iwz', 'Isidro Fabela', 4500, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(10, 'mJl1Q2dJyvX9iwz', 'Alcácer do Sal', 4500, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(11, 'rJc5nUvDpL6gKxh', 'Heshengbao', 3000, '2023-04-09 13:23:05', '2023-04-09 13:23:05'),
(12, 'mJl1Q2dJyvX9iwz', 'Capitán Solari', 4000, '2023-04-09 13:23:06', '2023-04-09 13:23:06'),
(13, 'mJl1Q2dJyvX9iwz', 'San Bernardo', 3000, '2023-04-09 13:23:06', '2023-04-09 13:23:06'),
(14, 'rJc5nUvDpL6gKxh', 'Aulnay-sous-Bois', 5000, '2023-04-09 13:23:06', '2023-04-09 13:23:06'),
(15, 'mJl1Q2dJyvX9iwz', 'Mujur Satu', 3000, '2023-04-09 13:23:06', '2023-04-09 13:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `location_change_history`
--

CREATE TABLE `location_change_history` (
  `id` int(255) NOT NULL,
  `location_id` int(255) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `location_old_name` varchar(30) NOT NULL,
  `charge_old` float NOT NULL,
  `location_new_name` varchar(30) NOT NULL,
  `charge_new` float NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phone_number` bigint(13) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `profile_image` varchar(50) NOT NULL DEFAULT 'assets/images/default_profile.png',
  `account_type` varchar(20) NOT NULL,
  `signup_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `login_timestamp` datetime DEFAULT current_timestamp(),
  `company_id` varchar(20) DEFAULT NULL,
  `preferred_page` varchar(20) NOT NULL DEFAULT 'Home',
  `password` varchar(100) NOT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(255) NOT NULL,
  `order_details` varchar(500) NOT NULL,
  `product` varchar(30) NOT NULL,
  `multiple_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`multiple_products`)),
  `company_id` varchar(20) NOT NULL,
  `stock_id` int(255) NOT NULL,
  `dispatch_id` int(255) DEFAULT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `inventory_name` varchar(50) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` float NOT NULL,
  `location` varchar(30) NOT NULL,
  `report` varchar(100) DEFAULT NULL,
  `sent_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `delivery_timestamp` datetime DEFAULT NULL,
  `reschedule_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_name_change_history`
--

CREATE TABLE `product_name_change_history` (
  `id` int(255) NOT NULL,
  `stock_id` int(255) NOT NULL,
  `product_new_name` varchar(50) NOT NULL,
  `product_old_name` varchar(50) NOT NULL,
  `user_id` int(255) NOT NULL,
  `changed_by` varchar(50) NOT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(255) NOT NULL,
  `date` date NOT NULL,
  `order_details` varchar(500) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `inventory_name` varchar(30) NOT NULL,
  `stock_id` int(255) NOT NULL,
  `location` varchar(30) NOT NULL,
  `product` varchar(30) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `charge` int(10) NOT NULL,
  `remittance` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(255) NOT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `company_id` varchar(30) NOT NULL,
  `product` varchar(30) NOT NULL,
  `quantity` int(10) NOT NULL,
  `product_image` varchar(50) NOT NULL DEFAULT 'assets/images/default_product.png',
  `stock_updated_timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `waybill`
--

CREATE TABLE `waybill` (
  `id` int(255) NOT NULL,
  `inventory_unique_id` varchar(20) NOT NULL,
  `stock_id` int(255) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `product` varchar(30) NOT NULL,
  `quantity` int(10) NOT NULL,
  `details` varchar(500) NOT NULL,
  `sent_timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'sent',
  `received_timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatch`
--
ALTER TABLE `dispatch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_unique_id`);

--
-- Indexes for table `inventory_signatory`
--
ALTER TABLE `inventory_signatory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_change_history`
--
ALTER TABLE `location_change_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `product_name_change_history`
--
ALTER TABLE `product_name_change_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `waybill`
--
ALTER TABLE `waybill`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispatch`
--
ALTER TABLE `dispatch`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_signatory`
--
ALTER TABLE `inventory_signatory`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `location_change_history`
--
ALTER TABLE `location_change_history`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_name_change_history`
--
ALTER TABLE `product_name_change_history`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waybill`
--
ALTER TABLE `waybill`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
