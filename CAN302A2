-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2023 at 06:20 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`) VALUES
(1, 'Book'),
(2, 'Computer'),
(3, 'Car'),
(4, 'Clothes'),
(5, 'Office supplies'),
(6, 'Camera');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount_percentage` double NOT NULL,
  `expiration_date` date NOT NULL,
  `isUsed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`coupon_id`, `code`, `discount_percentage`, `expiration_date`, `isUsed`) VALUES
(1, 'SALE20', 20, '2023-05-31', 0),
(2, 'NewYear25', 25, '2023-06-15', 0),
(3, 'Valetine20', 20, '2023-06-30', 0),
(4, 'SALE30', 30, '2023-06-30', 0),
(5, 'Summer10', 10, '2023-07-10', 0),
(6, 'Summer20', 20, '2023-06-30', 0),
(7, '618618', 20, '2023-07-31', 0),
(8, 'SALE20', 25, '2023-07-31', 0),
(9, 'BlackFriday30', 30, '2023-12-10', 0),
(10, 'SALE20', 20, '2023-08-31', 0),
(11, 'Christmas', 35, '2023-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `date`, `status`, `payment_method`, `shipping_address`, `user_id`) VALUES
(1, '2023-05-01', 4, 1, 'Suzhou', 1),
(2, '2023-05-03', 2, 1, 'Shenzhen', 4),
(3, '2023-05-08', 3, 2, 'Wuxi', 2),
(4, '2023-02-13', 1, 1, 'Shanghai', 3),
(5, '2023-03-06', 1, 3, 'Suzhou', 6),
(6, '2023-05-11', 3, 3, 'Xiamen', 2),
(7, '2023-05-03', 2, 1, 'Huzhou', 3);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_id`, `product_id`, `amount`) VALUES
(4, 1, 999),
(2, 9, 789),
(3, 3, 8),
(7, 6, 9),
(5, 5, 1),
(6, 8, 90),
(4, 2, 99),
(1, 2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `price`, `image`, `stock`, `category_id`) VALUES
(1, 'Harry Potter', 'Harry Potter is a series of seven fantasy novels written by British author J. K. Rowling.', 50, '', 100, 1),
(2, 'Macbook', 'The MacBook is a brand of Mac notebook computers designed and marketed by Apple Inc. ', 9999, '', 80, 2),
(3, 'Xiaomi', 'Xiaomi\'s notebook line is currently sold through its RedmiBook', 8888, '', 99, 2),
(4, 'Pen', 'A black pen.', 8, '', 8888, 5),
(5, 'Porsche', 'A German automobile manufacturer specializing in high-performance sports car.', 999999, '', 555, 3),
(6, 'Sony', 'Sony ZV-1F Vlog Camera for Content Creators and Vloggers', 7777, '', 8, 6),
(7, 'Coat', 'A warm coat made in cotton.', 140, '', 88, 4),
(8, 'White board', 'The whiteboard is erasable.', 80, '', 874, 5),
(9, 'Jean', 'A fashion jean designed by XXX.', 100, '', 77, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `isAdmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `payment_method`, `shipping_address`, `isAdmin`) VALUES
(1, 'xu', '11@xjtlu.cn', '123', 1, 'Suzhou', 0),
(2, 'zhai', '22@xjtlu.cn', '456', 2, 'Shanghai', 0),
(3, 'zheng', '33@xjtlu.cn', '333', 1, 'Wuxi', 1),
(4, 'yin', '44@xjtlu.cn', '44$$$', 2, 'Shandong', 1),
(5, 'you', '55@xjtlu.cn', '55555555555&&', 3, 'Shenzhen', 1),
(6, 'yang', '66@xjtlu.cn', '44466644', 3, 'Shanghai', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_coupon`
--

CREATE TABLE `user_coupon` (
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_coupon`
--

INSERT INTO `user_coupon` (`user_id`, `coupon_id`) VALUES
(1, 1),
(2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_coupon`
--
ALTER TABLE `user_coupon`
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `user_coupon`
--
ALTER TABLE `user_coupon`
  ADD CONSTRAINT `user_coupon_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `user_coupon_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`coupon_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
