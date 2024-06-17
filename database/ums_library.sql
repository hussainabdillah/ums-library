-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2024 at 11:48 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ums_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `published_year` year(4) NOT NULL,
  `available` tinyint(1) DEFAULT 1,
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `isbn`, `published_year`, `available`, `cover`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 1925, 1, 'The Great Gatsby.png'),
(2, 'To Kill a Mockingbird', 'Harper Lee', '9780061120084', 1960, 1, 'To Kill a Mockingbird.png'),
(3, '1984', 'George Orwell', '9780451524935', 1949, 1, '1984.png'),
(4, 'Pride and Prejudice', 'Jane Austen', '9781503290563', 0000, 1, 'Pride and Prejudice.png'),
(17, 'Spy X Family', 'Tatsuya Endo', '9781234567890', 2019, 1, 'Spy X Family.png'),
(18, 'Al Mustafa Republish', 'Kahlil Gibran', '9781234567891', 2014, 1, 'Al Mustafa Republish.png'),
(19, 'Alice in Wonderland', 'Lewiss Carroll', '9781234567892', 0000, 1, 'Alice in Wonderland.png'),
(20, 'Brave New World', 'Aldous Huxley', '9781234567893', 1932, 1, 'Brave New World.png'),
(21, 'Detektif hantu', 'Risa Saraswati', '9781234567894', 2023, 1, 'Detektif hantu.png'),
(22, 'Harry Potter and the Philosopher\'s Stone', 'J.K Rowling', '9781234567895', 1997, 1, 'Harry Potter and the Philosopher\'s Stone.png'),
(23, 'Gadis Minimarket', 'Sayaka Murata', '9789876543210', 2016, 1, 'Gadis Minimarket.png'),
(24, 'How Rich People Think', 'Steve Siebold', '9789876543211', 2010, 1, 'How Rich People Think.png'),
(25, 'How Rich People Think', 'Steve Siebold', '9789876543212', 2010, 1, 'How Rich People Think.png'),
(26, 'I Think I Love You', 'Chamirae', '9789876543213', 2022, 1, 'I Think I Love You.png'),
(27, 'Kisah Tanah Jawa', 'Bonaventura D. Genta, Mada Zidan', '9789876543214', 2018, 1, 'Kisah Tanah Jawa.png'),
(28, 'KKN di Desa Penari', 'Simple Man', '9789876543215', 2018, 1, 'KKN di Desa Penari.png');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `name`, `email`, `address`, `password`) VALUES
(1, 'John Doe', 'john.doe@example.com', '123 Main St', 'password123'),
(2, 'Jane Smith', 'jane.smith@example.com', '456 Elm St', 'password456'),
(3, 'Hussain Abdillah', 'hussainabdillah@test.com', 'Sukoharjo, Jawa Tengah', 'test123'),
(4, 'Agus Ardi', 'agusardi@test.com', 'Solo, Jawa Tengah', 'agus123'),
(5, 'Arwinda Salsabila', 'arwinda@test.com', 'Solo, Jawa Tengah', 'arwinda123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
