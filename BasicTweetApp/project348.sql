-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 11 Haz 2023, 20:43:58
-- Sunucu sürümü: 8.0.33
-- PHP Sürümü: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `project348`
--

DELIMITER $$
--
-- Yordamlar
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetHomepageTweets` (IN `userId` INT)   BEGIN
    SELECT t.tweet_id, t.content, t.created_at, u.username
    FROM tweets AS t
    INNER JOIN follows AS f ON f.followed_id = t.user_id
    INNER JOIN users AS u ON u.user_id = t.user_id
    WHERE f.follower_id = userId
    ORDER BY t.created_at DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetProfileTweets` (IN `userId` INT)   BEGIN
    SELECT tweet_id, content, created_at
    FROM tweets
    WHERE user_id = userId
    ORDER BY created_at DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `follows`
--

CREATE TABLE `follows` (
  `follower_id` int DEFAULT NULL,
  `followed_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `follows`
--

INSERT INTO `follows` (`follower_id`, `followed_id`) VALUES
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 3),
(1, 10),
(3, 1),
(1, 1),
(3, 7);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tweets`
--

CREATE TABLE `tweets` (
  `tweet_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `content` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `tweets`
--

INSERT INTO `tweets` (`tweet_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 'Merhaba', '1753-01-01 00:00:00'),
(2, 2, 'Se;lam', '1754-01-01 00:00:00'),
(7, 1, 'efgr', '2023-05-20 00:40:17'),
(8, 1, 'efgr', '2023-05-20 00:40:19'),
(9, 1, 'dsaadsfdssfd', '2023-05-20 00:49:43'),
(10, 1, 'sdaads', '2023-05-20 11:28:29'),
(11, 3, 'asd', '2023-05-20 11:59:13'),
(12, 3, '', '2023-05-20 11:59:22'),
(13, 1, 'merhabaklarrrr\r\n', '2023-05-20 12:15:41'),
(14, 6, 'benzaman', '2023-05-20 12:29:39'),
(15, 3, 'ne', '2023-05-20 13:20:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `username` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`name`, `username`, `password`, `user_id`) VALUES
('mert', 'yigit', '123', 1),
('kemal', 'kılıc', '456', 2),
('can', 'avar', '789', 3),
('mahmut', 'zaman', '3423', 4),
('mahmut', 'zaman', '3423', 5),
('mahmut', 'zaman', '55656', 6),
('muharrem ', 'ince', '3232', 7),
('meral', 'aksener', '1234', 8),
('mahmut', 'dasdas', '123', 9),
('mahmut', '3223', '3223', 10),
('nedim', 'nedim', '123', 11);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `follows`
--
ALTER TABLE `follows`
  ADD KEY `fk_follows_follower` (`follower_id`),
  ADD KEY `fk_follows_followed` (`followed_id`);

--
-- Tablo için indeksler `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`tweet_id`),
  ADD KEY `fk_tweets_user` (`user_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `tweets`
--
ALTER TABLE `tweets`
  MODIFY `tweet_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `fk_follows_followed` FOREIGN KEY (`followed_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_follows_follower` FOREIGN KEY (`follower_id`) REFERENCES `users` (`user_id`);

--
-- Tablo kısıtlamaları `tweets`
--
ALTER TABLE `tweets`
  ADD CONSTRAINT `fk_tweets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
