-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2026 at 08:52 AM
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
-- Database: `bus_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `bus_id` int(11) NOT NULL,
  `seat_no` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `p_name` varchar(100) DEFAULT NULL,
  `p_email` varchar(100) DEFAULT NULL,
  `p_phone` varchar(20) DEFAULT NULL,
  `booking_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `bus_id`, `seat_no`, `user_email`, `total_price`, `p_name`, `p_email`, `p_phone`, `booking_date`) VALUES
(3, 3, '3D', NULL, NULL, NULL, NULL, NULL, '2026-02-05 00:14:19'),
(14, 1, '2B', 'hengjingchen102@gmail.com', NULL, NULL, NULL, NULL, '2026-02-05 00:14:19'),
(21, 1, '1A,1B', 'hengjingchen102@gmail.com', NULL, NULL, NULL, NULL, '2026-02-05 00:14:19'),
(22, 4, '5B', 'hengjingchen102@gmail.com', NULL, NULL, NULL, NULL, '2026-02-05 00:14:19'),
(23, 5, '3B,2B', 'hengjingchen102@gmail.com', NULL, NULL, NULL, NULL, '2026-02-05 00:14:19'),
(27, 3, '5A,5B', 'hengjingchen102@gmail.com', 40.00, 'cikgu sim', 'hengjingchen102@gmail.com', '0183665756', '2026-02-05 00:14:25'),
(28, 9, '2B,1B', 'hengjingchen102@gmail.com', 100.00, 'cikgu sim', 'hengjingchen102@gmail.com', '0183665756', '2026-02-05 09:30:13'),
(29, 10, '5C,5D,4D,4C,3C,3D,2D,2C,1C,1D', 'hengjingchen102@gmail.com', 250.00, 'Sim Heng Jing', 'hengjingchen102@gmail.com', '0183665756', '2026-02-05 09:32:03'),
(30, 10, '4B,3A', 'hengjingchen102@gmail.com', 50.00, 'Sim Heng Jing', 'hengjingchen102@gmail.com', '0183665756', '2026-02-05 13:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `bus_company` varchar(100) DEFAULT NULL,
  `departure` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `depart_time` varchar(50) DEFAULT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bus_company`, `departure`, `destination`, `depart_time`, `price`, `travel_date`, `status`) VALUES
(9, 'Mayang Sini', 'Johor Bahru ', 'Kuala Lumpur', '13:30', 25.00, '2026-02-06', 0),
(10, 'Go Travel', 'Johor Bahru ', 'Kuala Lumpur', '10:30', 25.00, '2026-02-06', 0),
(11, 'Conjuring', 'Johor Bahru ', 'Kuala Lumpur', '18:30', 25.00, '2026-02-06', 0),
(12, 'Conjuring', 'Johor Bahru ', 'Kuala Lumpur', '10:30', 25.00, '2026-02-07', 1),
(13, 'Conjuring', 'Kuala Lumpur', 'Johor Bahru', '10:30', 25.00, '2026-02-07', 1),
(14, 'Mayang Sini', 'Kuala Lumpur', 'Johor Bahru', '18:30', 25.00, '2026-02-07', 0),
(15, 'Mayang Sini', 'Kuala Lumpur', 'Penang', '16:38', 20.00, '2026-02-07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'hengjingchen102@gmail.com', '$2y$10$yvo/evaI1HVvlPV7Y4QFeeHTHali44Fpr/lQ4GMJV0gNIDsq4H5Z6'),
(2, 'sim.heng.jing@student.mmu.edu.my', '$2y$10$skXk8e5BnHknAYRTq7xNP.Hb85u7yKX9piTyOXJ1Yd0w1IyfSxYBC'),
(3, 'yonglok@gmail.com', '$2y$10$co2lu/.L0sju24cVZzf4..beAXWAsjUfkXBa5wW8Z6Y28lYvnr/Ae'),
(6, 'hengjing@gmail.com', '$2y$10$02hD3Xr0epMe.BR4zXAcW.6NTZL4L5ATJ6lmT7mFKuM3mJ5k/3krG'),
(7, 'hengjingchen1@gmail.com', '$2y$10$52Be.vBQghddI2fy5BZcoesF9GLltrh/xPqLyNH3jT84KMFNHxTgq'),
(8, 'hengjin@gmail.com', '$2y$10$IJrEBeTj43PY2DneXQR1mu84Ir8TCY7R6/7ej1gwjO7bSHTJFDTDm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
