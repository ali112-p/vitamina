-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2024 at 01:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeemenudb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `ImageURL`) VALUES
(17, 'Espresso', NULL),
(18, 'Latte', NULL),
(19, 'Cappuccino', NULL),
(20, 'Americano', NULL),
(21, 'Mocha', NULL),
(22, 'Macchiato', NULL),
(23, 'Cold Brew', NULL),
(24, 'Flat White', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coffeemenu`
--

CREATE TABLE `coffeemenu` (
  `CoffeeID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `CategoryID` int(11) NOT NULL,
  `Price` decimal(5,2) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coffeemenu`
--

INSERT INTO `coffeemenu` (`CoffeeID`, `Name`, `Description`, `CategoryID`, `Price`, `ImageURL`) VALUES
(98, 'Espresso Classic', 'Rich and bold espresso shot.', 17, 2.50, 'uploads/coffees/aa.jpeg'),
(99, 'Espresso Macchiato', 'Espresso topped with a dollop of foam.', 17, 2.75, 'uploads/coffees/aa.jpeg'),
(100, 'Latte Vanilla', 'Smooth latte with vanilla flavor.', 18, 3.50, ''),
(101, 'Latte Caramel', 'Caramel-flavored latte with whipped cream.', 18, 3.75, ''),
(102, 'Cappuccino Classic', 'Classic cappuccino with frothy milk.', 19, 3.00, ''),
(103, 'Cappuccino Mocha', 'Cappuccino with a touch of chocolate.', 19, 3.25, ''),
(104, 'Americano Classic', 'Espresso diluted with hot water.', 20, 2.75, ''),
(105, 'Americano With Milk', 'Americano with a splash of milk.', 20, 3.00, ''),
(106, 'Mocha Classic', 'Rich chocolate mocha with whipped cream.', 21, 4.00, ''),
(107, 'Mocha Mint', 'Mocha with a hint of mint flavor.', 21, 4.25, ''),
(108, 'Macchiato Classic', 'Espresso with a small amount of frothy milk.', 22, 2.50, ''),
(109, 'Caramel Macchiato', 'Macchiato with caramel flavor.', 22, 3.00, ''),
(110, 'Cold Brew Classic', 'Smooth and strong cold brew coffee.', 23, 3.50, ''),
(111, 'Cold Brew With Vanilla', 'Cold brew with vanilla syrup.', 23, 3.75, ''),
(112, 'Flat White Classic', 'Rich espresso with silky microfoam.', 24, 3.50, ''),
(113, 'Flat White Caramel', 'Flat white with caramel flavor.', 24, 3.75, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('Admin','User') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `coffeemenu`
--
ALTER TABLE `coffeemenu`
  ADD PRIMARY KEY (`CoffeeID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `coffeemenu`
--
ALTER TABLE `coffeemenu`
  MODIFY `CoffeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coffeemenu`
--
ALTER TABLE `coffeemenu`
  ADD CONSTRAINT `coffeemenu_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
