-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2026 at 11:42 PM
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
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

CREATE TABLE `tblclothes` (
  `clothes_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `category` varchar(20) NOT NULL,
  `size` varchar(10) NOT NULL,
  `condition_rating` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`clothes_id`, `seller_id`, `brand`, `category`, `size`, `condition_rating`, `price`, `description`, `image_path`, `status`, `created_at`) VALUES
(1, 1, 'Nike', 'Men', 'L', 4, 350.00, 'Nike Air Max 90, lightly worn', 'https://th.bing.com/th/id/OIP.MkXn8xW4rZbBoOGPgxCVgAHaHa?w=184&h=184&c=7&r=0&o=7&pid=1.7&rm=3', 'Active', '2026-05-02 15:55:02'),
(2, 1, 'Adidas', 'Men', 'XL', 5, 500.00, 'Adidas Track Jacket, like new', 'https://th.bing.com/th/id/OIP.8fhISzF2Twgfr7NKkS72DAHaHa?w=205&h=205&c=7&r=0&o=7&pid=1.7&rm=3', 'Active', '2026-05-02 15:55:02'),
(3, 2, 'Levi', 'Women', 'M', 3, 200.00, 'Levi 501 Jeans, good condition', 'https://th.bing.com/th/id/OIP.BTt1GyJSgfJohgrJvtpUxwHaJQ?w=184&h=230&c=7&r=0&o=7&pid=1.7&rm=3', 'Active', '2026-05-02 15:55:02'),
(4, 2, 'GUESS', 'Women', 'S', 4, 280.00, 'GUESS Denim Jacket, barely used', 'https://th.bing.com/th/id/OIP.dlb-KbpL5dzbYOrr-PTbkQHaJM?w=184&h=228&c=7&r=0&o=7&pid=1.7&rm=3', 'Active', '2026-05-02 15:55:02'),
(5, 3, 'Codesa', 'Men', 'M', 3, 150.00, 'Codesa Streetwear Hoodie', 'https://th.bing.com/th/id/OIP.J-Lp2SQ_0sL20Agn_brwOAHaG0?w=184&h=169&c=7&r=0&o=7&pid=1.7&rm=3', 'Active', '2026-05-02 15:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `verification_status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `email`, `password_hash`, `first_name`, `last_name`, `role`, `verification_status`, `created_at`) VALUES
(6, 'john@email.com', '$2y$10$oWERzUHJNAlRADnDMS.4Zew1CVhOueEr7GIWLsvIbp5xszt9aIzGa', 'John', 'Doe', 'customer', 'active', '2026-05-02 16:35:33'),
(7, 'jane@email.com', '$2y$10$sIICtDXL/LnoYxZVdedg9OZpPxqZO54N2zIvMN45G00eghnOEw046', 'Jane', 'Smith', 'customer', 'active', '2026-05-02 16:35:33'),
(8, 'admin@pastimes.co.za', '$2y$10$2Upx551YFiJdBMDy.v.iKeWXYOUrruNNNeGqjqrvwo8IIncp7AV.G', 'Admin', 'User', 'admin', 'active', '2026-05-02 16:35:34'),
(9, 'bob@email.com', '$2y$10$anBfWV8KKO4dP6BTXmnIKOulAIcFL2OkIEEc3liX9OeY9WgE5.19m', 'Bob', 'Marley', 'customer', 'active', '2026-05-02 16:35:34'),
(10, 'lebo@email.com', '$2y$10$Bah6hxc7eBN/KELkSGfCc.B2fffjPIjvOml/JiCgy2aqcZzkeg0Y6', 'Lebo', 'Khumalo', 'customer', 'active', '2026-05-02 16:35:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD PRIMARY KEY (`clothes_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblclothes`
--
ALTER TABLE `tblclothes`
  MODIFY `clothes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
