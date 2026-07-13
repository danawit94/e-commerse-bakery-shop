-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2026 at 01:56 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(7, 3, 5, 'Mille-CrÃªpes', '1400', '2', 'fruit_tart.jpg'),
(8, 3, 7, 'Difo Dabo', '900', '3', 'difo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(4, 6, 'dan', 'dana@gmail.com', '7909098', 'when will i get an award'),
(5, 5, 'lid', 'lid@gmail.com', '0977098', 'we got a big order'),
(6, 3, 'weknesh', 'werk@gmail.com', '0976546', 'thanks for your service'),
(7, 6, 'dan', 'dan@gmail.com', '9999898', 'good');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_products` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `placed_on` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending..'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(2, 6, 'dan', '09856778', 'dan@gmail.com', 'telebirr', 'jemo', '6', '788', 'africa', 'pending..'),
(3, 5, 'lid', '078666', 'lid@gmail.com', 'cbe', 'mexico', '8', '8999', '99', 'pending..'),
(4, 6, 'nanati', '98766', 'nanati94@gmail.com', 'telebirr', '9jj, jemo, adidd, ethiopia', 'sambusa (1), Holland_yogurt (3), Ambasha (1)', '965', '12-Jul-2026', 'pending'),
(5, 3, 'weknesh', '+251 9765', 'werk@gmail.com', 'cbe birr', 'ninja , sansusi, dire dewa, ethiopia', 'sambusa (1), Difo Dabo (2), Holland_yogurt (1)', '2055', '12-Jul-2026', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `images` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `product_detail`, `images`) VALUES
(2, 'berry', '300', 'a pice o cake made from berry ', 'berry.jpg'),
(3, 'Ambasha', '350', 'Himbasha (Tigrinya: áˆ•áˆá‰£áˆ» ) or Ambasha (Amharic: áŠ áˆá‰£áˆ» ), is an Ethiopian and Eritrean celebration bread that is slightly sweet', 'ambasha.jpg'),
(4, 'Dobos Torte:', '1600', ' A traditional Hungarian sponge cake made of thin layers separated by rich chocolate buttercream. The top layer is coated in hard caramel, which adds a distinct crunc', 'choclate_cake.jpg'),
(5, 'Mille-CrÃªpes', '1400', ' A tower of dozens of extremely thin French crÃªpes stacked with delicate, luxurious pastry cream or whipped cream in between each layer.', 'fruit_tart.jpg'),
(6, 'Red Velvet Cake:', '1900', ' Known for its soft, velvety texture and signature deep burgundy color. Originally derived from the reaction between raw cocoa and acidic buttermilk, it is almost always paired with cream cheese frosting', 'torta_cake.jpg'),
(7, 'Difo Dabo', '900', 'is a traditional Ethiopian bread that holds deep cultural significance, particularly during festive occasions.', 'difo.jpg'),
(8, 'Puff Pastry', '450', ' Similar to a croissant but without the yeast leavener. It is used for savory pies, sweet fruit turnovers, and intricate bakes like Mille-Feuille ', 'baklava.jpg'),
(9, 'A croissant', '250', 'A croissant is a buttery, flaky viennoiserie pastry named for its distinct crescent shape.', 'croissant.jpg'),
(10, '  A choclate  croissant', '400', ' is a buttery, flaky viennoiserie pastry named for its distinct crescent shape with choclate.', 'choclate_croissant.jpg'),
(11, 'sambusa', '75', 'Sambusa (also known as samosa) is a delicious, triangular pastry filled with a savory mix of spiced meat, lentils, or vegetables.', 'sanbusa.jpg'),
(12, 'muffin', '130', 'dolce di burro', 'mufin.jpg'),
(13, 'Cannoli', '250', ': A crispy, tube-shaped fried pastry shell of Italian origin, typically filled with a sweetened ricotta cream and topped with chocolate or pistachios.', 'profiterole.jpg'),
(14, 'milk', '120', 'cow milk', 'milk.jpg'),
(15, 'coca_cola', '90', 'soft drink', 'coca.jpg'),
(16, 'Vancho Cake', '2100', '\r\nEver feel like choosing between vanilla and chocolate is a difficult decision? This dessert eliminates that stress with a perfect blend of both.', 'ribbon.jpg'),
(17, 'strawbery cake_ðŸ°', '2000', 'creamed,strawberry decorated  ', 'torta_cake.jpg'),
(18, 'Holland_yogurt', '180', 'purely pastursied', 'holland.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'danawit', 'danawitmesenbet94@gmail.com', '1212', 'admin'),
(3, 'weknesh', 'werk@gmail.com', '22', 'user'),
(4, 'mulu', 'mulu@gmail.com', '33', 'user'),
(5, 'lid', 'lid@gmail.com', '44', 'admin'),
(6, 'dan', 'dan@gmail.com', '111', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(5, 3, 6, 'Red Velvet Cake:', '1900', 'torta_cake.jpg'),
(6, 3, 10, '  A choclate  croissant', '400', 'choclate_croissant.jpg'),
(7, 3, 13, 'Cannoli', '250', 'profiterole.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
