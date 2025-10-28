-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2025 at 05:44 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_moviepedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tahun_rilis` int DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `cast` text,
  `rating_imdb` float DEFAULT NULL,
  `sinopsis` text,
  `poster_url` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `judul`, `tahun_rilis`, `genre`, `director`, `cast`, `rating_imdb`, `sinopsis`, `poster_url`, `trailer_url`) VALUES
(4, 'Oppenheimer', 2023, 'Biography, Drama, History', 'Christopher Nolan', 'Cillian Murphy, Emily Blunt, Matt Damon', 8.5, 'Kisah fisikawan teoretis Amerika J. Robert Oppenheimer, yang memimpin Proyek Manhattan untuk mengembangkan bom atom pertama selama Perang Dunia II. Film ini mengeksplorasi dilema moral dan konsekuensi pribadi serta global dari ciptaannya.', 'uploads/posters/1761391014_Oppenheimer_(film).jpg', 'https://www.youtube.com/watch?v=bK6ldnjE3Y0'),
(5, 'Parasite', 2017, 'Thriller, Drama, Comedy', 'Bong Joon Ho', 'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong', 8.5, 'Keserakahan dan diskriminasi kelas mengancam hubungan simbiosis yang baru terbentuk antara keluarga kaya Park dan keluarga miskin Kim saat mereka menyusup ke dalam kehidupan keluarga Park satu per satu.', 'uploads/posters/1761391295_parasyte.jpg', 'https://www.youtube.com/watch?v=5xH0HfJHsaY'),
(6, 'Inception', 2010, 'Action, Sci-Fi, Thriller', 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page', 8.8, 'Seorang pencuri yang mencuri informasi dengan menyusup ke dalam alam bawah sadar targetnya, diberi tugas terakhir untuk menanamkan ide ke dalam pikiran seorang CEO.', 'uploads/posters/1761402013_Inception_poster.jpg', 'https://www.youtube.com/watch?v=YoHD9XEInc0'),
(7, 'The Shawshank Redemption', 1994, 'Drama', 'Frank Darabont', 'Tim Robbins, Morgan Freeman, Bob Gunton', 9.3, 'Dua pria yang dipenjara menjalin ikatan persahabatan selama bertahun-tahun, menemukan pelipur lara dan penebusan akhir melalui tindakan kesopanan yang umum.', 'uploads/posters/1761401980_ShawshankRedemptionMoviePoster.jpg', 'https://www.youtube.com/watch?v=NmzuHjWmXOc'),
(9, 'The Godfather', 1972, 'Crime, Drama', 'Francis Ford Coppola', 'Marlon Brando, Al Pacino, James Caan', 9.2, 'Patriark yang menua dari sebuah dinasti kejahatan terorganisir di New York pascaperang mentransfer kendali kerajaan bawah tanahnya kepada putranya yang enggan.', 'uploads/posters/1761401918_Godfather_ver1.jpg', 'https://www.youtube.com/watch?v=sY1S34973zA'),
(10, 'Spider-Man: Across the Spider-Verse', 2023, 'Animation, Action, Adventure', 'Joaquim Dos Santos, Kemp Powers, Justin K. Thompson', 'Shameik Moore, Hailee Steinfeld, Brian Tyree Henry', 8.6, 'Miles Morales melintasi Multiverse, di mana ia bertemu dengan tim Spider-People yang bertugas melindunginya. Ketika para pahlawan berselisih, Miles harus mendefinisikan kembali apa artinya menjadi seorang pahlawan.', 'uploads/posters/1761401832_Spider-Man-_Across_the_Spider-Verse_poster.jpg', 'https://www.youtube.com/watch?v=g4Hbz2jLxvQ');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`) VALUES
(2, 'Ridho Nur Maulana', 'ridho@gmail.com', '$2y$10$t7Vu/lz24O0I03tVDCJh7OXqoEJcY5y9eVv6xkE5rA88GMjDUDSyK', 'user'),
(4, 'admin', 'admin@gmail.com', '$2y$10$w98bbYN1mrhrEtDsuRrbsuaZw07gEinds65VmWI3wtZHGGguyeVNS', 'admin'),
(5, 'Garmawan', 'garmawan@gmail.com', '$2y$10$jZ1Ia17JB0x8BSGwHTPSZ.NasHmMhbTqmh//PC2nNRFP1zfxpXfDK', 'user'),
(6, 'Emir', 'emir@gmail.com', '$2y$10$bMxhMPhnlrw76jktw39FcOobbnM2iFX.CYwCBFL2k8Zm4t15S3zzm', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `film_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`id`, `user_id`, `film_id`) VALUES
(18, 2, 4),
(26, 2, 6),
(23, 2, 7),
(22, 2, 10),
(17, 4, 5),
(19, 5, 4),
(28, 6, 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_film_unique` (`user_id`,`film_id`),
  ADD KEY `film_id` (`film_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
