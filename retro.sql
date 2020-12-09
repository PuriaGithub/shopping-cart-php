-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 12, 2020 at 02:01 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `retro`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum_categories`
--

CREATE TABLE `forum_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_categories`
--

INSERT INTO `forum_categories` (`id`, `name`, `created_at`) VALUES
(1, 'Retro Albums', '2020-05-15'),
(2, 'RARE LPs', '2020-05-15'),
(3, 'Used Records', '2020-05-15');

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `created_at` date NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `body`, `created_at`, `owner_email`, `topic_id`) VALUES
(1038, 'Can I track my order once it’s shipped?', '2020-06-12', 'buyer@gmail.test', 30),
(1039, 'Yes, you can!', '2020-06-12', 'admin@retrorecords.test', 30),
(1040, 'How much does shipping cost?', '2020-06-12', 'john@buyer.test', 31),
(1041, 'We offer free shipping across Australia for all of our boxes.', '2020-06-12', 'admin@retrorecords.com', 31),
(1042, 'Thank you!', '2020-06-12', 'john@buyer.test', 31),
(1043, 'I was billed twice before a box got here, why?', '2020-06-12', 'john@doe.test', 32);

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `title`, `created_at`, `owner_email`, `category_id`) VALUES
(30, 'Tracking My Order', '2020-06-12', 'buyer@gmail.test', 1),
(31, 'Shipping cost', '2020-06-12', 'john@buyer.test', 3),
(32, 'I was billed twice!!!', '2020-06-12', 'john@doe.test', 2);

-- --------------------------------------------------------

--
-- Table structure for table `store_categories`
--

CREATE TABLE `store_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `store_categories`
--

INSERT INTO `store_categories` (`id`, `name`, `description`) VALUES
(1, 'Retro Albums', 'Retro Records...'),
(2, 'RARE LPs', 'Retro Records...'),
(3, 'Used Records', 'Retro Records...');

-- --------------------------------------------------------

--
-- Table structure for table `store_items`
--

CREATE TABLE `store_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `artist_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cover_image` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `store_items`
--

INSERT INTO `store_items` (`id`, `name`, `artist_name`, `description`, `price`, `quantity`, `cover_image`, `created_at`) VALUES
(1, 'Funk and Soul', 'Ian Dooley', 'Funk and Soul', '50.00', 5, 'funk-and-soul.jpg', '2020-05-20 13:29:46'),
(2, 'Rock and Roll', 'Ian Dooley', 'Rock and Roll', '32.00', 15, 'rock-and-roll.jpg', '2020-05-20 13:29:46'),
(3, 'Retro Party', 'John Doe', 'Retro Party', '15.00', 20, 'retro-party.jpg', '2020-06-05 12:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `store_orders`
--

CREATE TABLE `store_orders` (
  `id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_name` varchar(255) NOT NULL,
  `order_address` varchar(255) NOT NULL,
  `order_city` varchar(255) NOT NULL,
  `order_state` varchar(255) NOT NULL,
  `order_zip` varchar(255) NOT NULL,
  `order_tel` varchar(25) DEFAULT NULL,
  `order_email` varchar(255) DEFAULT NULL,
  `item_total` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `store_orders`
--

INSERT INTO `store_orders` (`id`, `order_date`, `order_name`, `order_address`, `order_city`, `order_state`, `order_zip`, `order_tel`, `order_email`, `item_total`) VALUES
(14, '2020-06-12 23:50:03', 'John Doe', 'Building A-Q & Z, 651-731 Harris Street, Ultimo 2007', 'Sydney', 'NSW', '2007', '131601', 'john@doe.test', '582.00'),
(15, '2020-06-12 23:56:38', 'Puria Test', '1 Brushbox St.', 'Sydney', 'NSW', '2127', '0404040404', 'puria@tafe.test', '15.00');

-- --------------------------------------------------------

--
-- Table structure for table `store_shopper_track`
--

CREATE TABLE `store_shopper_track` (
  `id` int(11) NOT NULL,
  `session_id` varchar(32) NOT NULL,
  `sel_item_qty` smallint(6) NOT NULL,
  `sel_item_color` varchar(25) DEFAULT NULL,
  `sel_item_size` varchar(25) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sel_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_categories`
--
ALTER TABLE `store_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `store_items`
--
ALTER TABLE `store_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_orders`
--
ALTER TABLE `store_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_shopper_track`
--
ALTER TABLE `store_shopper_track`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1044;

--
-- AUTO_INCREMENT for table `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `store_categories`
--
ALTER TABLE `store_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_items`
--
ALTER TABLE `store_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_orders`
--
ALTER TABLE `store_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `store_shopper_track`
--
ALTER TABLE `store_shopper_track`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
