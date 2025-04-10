-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table cinemate.chat_messages: ~0 rows (approximately)

-- Dumping data for table cinemate.customers: ~0 rows (approximately)

-- Dumping data for table cinemate.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2023_03_10_000000_create_users_table', 1),
	(3, '2023_03_10_000100_create_roles_table', 1),
	(4, '2023_03_10_000200_create_role_user_table', 1),
	(5, '2023_03_10_000300_create_rooms_table', 1),
	(6, '2023_03_10_000400_create_chat_messages_table', 1),
	(7, '2023_03_10_000500_create_customers_table', 1);

-- Dumping data for table cinemate.personal_access_tokens: ~0 rows (approximately)

-- Dumping data for table cinemate.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(2, 'ROLE_ADMIN', 'Quyền quản trị hệ thống', '2025-03-05 01:04:33', '2025-03-05 01:04:33'),
	(3, 'ROLE_USER', 'Quyền người dùng thông thường', '2025-03-05 01:04:33', '2025-03-05 01:04:33'),
	(4, 'ROLE_MODERATOR', 'Quyền điều hành', '2025-03-05 01:04:33', '2025-03-05 01:04:33');

-- Dumping data for table cinemate.role_user: ~9 rows (approximately)
INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
	(3, 2),
	(4, 3),
	(5, 3),
	(7, 3),
	(8, 3),
	(9, 3),
	(10, 3),
	(21, 3),
	(22, 3),
	(23, 3);

-- Dumping data for table cinemate.rooms: ~20 rows (approximately)
INSERT INTO `rooms` (`id`, `name`, `current_video_url`, `current_video_title`, `thumbnail`, `owner_username`, `created_at`, `updated_at`) VALUES
	(8, 'Phòng của hlong', 'https://www.youtube.com/watch?v=vHvOYQ0hbBA', 'ROSÉ - stay a little longer (official audio)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-07 02:43:18', '2025-03-07 02:43:18'),
	(9, 'Phòng của hlong', 'https://www.youtube.com/watch?v=kTJczUoc26U', 'The Kid LAROI, Justin Bieber - STAY (Official Video)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-07 02:44:26', '2025-03-07 02:44:26'),
	(12, 'Phòng của hlong', 'https://www.youtube.com/watch?v=qCepOLkcF_A', 'ROSÉ - number one girl (performance video)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-07 03:26:40', '2025-03-07 03:26:40'),
	(13, 'Phòng của hlong', 'https://www.youtube.com/watch?v=SJOKlqJho8U', 'The Kid LAROI - WITHOUT YOU (Official Video)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-07 03:40:09', '2025-03-07 03:40:09'),
	(14, 'Phòng của hlong', 'https://www.youtube.com/watch?v=f1r0XZLNlGQ', 'The Weeknd, JENNIE &amp; Lily Rose Depp - One Of The Girls (Official Audio)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-08 11:24:59', '2025-03-08 11:24:59'),
	(15, 'Phòng của hlong', 'https://www.youtube.com/watch?v=smqhSl0u_sI', 'Kendrick Lamar - Money Trees (Feat. Jay Rock)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-10 10:59:18', '2025-03-10 10:59:18'),
	(16, 'Phòng của hlong', 'https://www.youtube.com/watch?v=THVbtGqEO1o', 'Drake - Fair Trade (Audio) ft. Travis Scott', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-10 11:01:40', '2025-03-10 11:01:40'),
	(17, 'Phòng của hlong', 'https://www.youtube.com/watch?v=tFXnN6Onrbg', 'The Kid LAROI - BABY I&#39;M BACK (Official Audio)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-10 11:14:19', '2025-03-10 11:14:19'),
	(18, 'Phòng của hlongdayy', 'https://www.youtube.com/watch?v=sJt_i0hOugA', 'HIEUTHUHAI - Exit Sign (prod. by Kewtiie) ft. marzuz [Official Lyric Video]', 'https://i.imgur.com/6SqA0B8.png', 'hlongdayy', '2025-03-11 03:28:16', '2025-03-11 03:28:16'),
	(19, 'Phòng của hlongdayy', 'https://www.youtube.com/watch?v=bHXn-SU7YYg', 'The Kid LAROI - BLEED (Official Video)', 'https://i.imgur.com/6SqA0B8.png', 'hlongdayy', '2025-03-11 03:32:00', '2025-03-11 03:32:00'),
	(20, 'Phòng của hlongdayy', 'https://www.youtube.com/watch?v=mX19AV35PhI', 'The Weeknd, Playboi Carti - Timeless (Audio)', 'https://i.imgur.com/6SqA0B8.png', 'hlongdayy', '2025-03-11 03:34:11', '2025-03-11 03:34:11'),
	(21, 'Phòng của hlongdayy', 'https://www.youtube.com/watch?v=B3J6tQTuubc', 'Often', 'https://i.imgur.com/6SqA0B8.png', 'hlongdayy', '2025-03-11 03:36:30', '2025-03-11 03:36:30'),
	(22, 'Phòng của hlong', 'https://www.youtube.com/watch?v=16jA-6hiSUo', 'The Weeknd, Playboi Carti - Timeless (Official Lyric Video)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-15 09:08:19', '2025-03-15 09:08:19'),
	(23, 'Phòng của hlong', 'https://www.youtube.com/watch?v=wmYsGNr-Gv8', 'Too Many Nights/Niagara Falls (SEAMLESS TRANSITION) - Metro Boomin x Travis Scott x Don Toliver', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-16 00:27:12', '2025-03-16 00:27:12'),
	(28, 'Phòng của maianh', 'https://www.youtube.com/watch?v=m3ydJMw5loU', 'Brown Eyed Girls &#39;Sign&#39;', 'https://i.imgur.com/6SqA0B8.png', 'maianh', '2025-03-18 03:15:36', '2025-03-18 03:15:36'),
	(30, 'Phòng của maianh', 'https://www.youtube.com/watch?v=qpi9YXaChHI', 'FLOW - Sign', 'https://i.imgur.com/6SqA0B8.png', 'maianh', '2025-03-18 03:15:37', '2025-03-18 03:15:37'),
	(50, 'Phòng của hlong', 'https://www.youtube.com/watch?v=S6m97N7fa3c', 'JENNIE, Dua Lipa - Handlebars (Official Lyric Video)', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-03-29 03:12:03', '2025-03-29 03:12:03'),
	(52, 'Phòng của hlong', 'https://www.youtube.com/watch?v=U5B4IKKjkJA', '(Lowkey mixtape) Anh muốn nhìn thấy em ft @Dangrangto', 'https://i.imgur.com/6SqA0B8.png', 'hlong', '2025-04-01 03:47:45', '2025-04-01 03:47:45'),
	(56, 'Phòng của maianh', 'https://www.youtube.com/watch?v=sJt_i0hOugA', 'HIEUTHUHAI - Exit Sign (prod. by Kewtiie) ft. marzuz [Official Lyric Video]', 'https://i.imgur.com/6SqA0B8.png', 'maianh', '2025-04-03 03:17:04', '2025-04-03 03:17:04'),
	(71, 'Phòng của hlongdayy', 'https://www.youtube.com/watch?v=V7UgPHjN9qE', 'Drake - Jimmy Cooks ft. 21 Savage', 'https://i.imgur.com/6SqA0B8.png', 'hlongdayy', '2025-04-07 02:45:24', '2025-04-07 02:45:24');

-- Dumping data for table cinemate.users: ~11 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `address`, `provider`, `provider_id`, `avt_url`, `account_non_locked`, `created_at`, `updated_at`) VALUES
	(1, 'testuser123', '$2y$12$KzzQ2vx77IQq3ZNzOS2VhOrxzCuBgg1YDBWwpqJo4cyxXbyBl4rbW', 'testuser@example.com', '0987654321', '123 Đường Test, Quận 1, TP.HCM', NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-03-05 01:02:13', '2025-03-05 01:02:13'),
	(3, 'hlongdayy', '$2y$12$FuDn6P0wYNUpvU8mUnD8QuNhPIoUx1N6/CcP1O6vu9ptMvxdBbEWa', 'testusddera@example.com', '0868686741', '123 Đường Test, Quận 1, TP.HCM', NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-03-05 01:03:31', '2025-03-05 01:03:31'),
	(4, 'hlong', '$2y$12$dNkR5gQrD5b6CHhIrcu7oem6qzERA/4iM2tm5ARBj1B7.qANu18Ni', 'testusddeara@example.com', '0868686742', '123 Đường Test, Quận 1, TP.HCM', NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-03-05 01:04:54', '2025-03-05 01:04:54'),
	(5, 'maianh', '$2y$12$cxoNM.l8qba1uUUomewuG.ZPRQ4cindRLQRFQb28eaPSNCG.p9SFC', 'phianh@gmail.com', '0323232323', NULL, NULL, NULL, 'https://i.imgur.com/BmQFBlW.png', 1, '2025-03-11 00:36:05', '2025-03-11 00:36:05'),
	(7, 'Henry', '$2y$12$u9g5v19SCXD08HuHKvkStuzGUs6Q2PChQqe89.2yciJdkpM.T9J4S', 'yhlongsosad@gmail.com', '0585170543', NULL, NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-03-16 04:13:39', '2025-03-16 04:13:39'),
	(8, 'phianh', '$2y$12$62VJ03QD3iXyoAOUqxvWJOmL3Q1eJctsINk0ojcOvQSDFDSi7RdUG', 'phianhpr3@gmail.com', '0338284509', NULL, NULL, NULL, 'https://i.imgur.com/BmQFBlW.png', 1, '2025-03-29 03:06:51', '2025-03-29 03:06:51'),
	(9, 'admin', '$2y$12$10VYPQ7gwhr41tl0BjXWe.cCynFPQKkUI1nosz.FS4vQb.5YMgZVa', 'admin@gmail.com', '0545170543', NULL, NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-04-03 02:51:30', '2025-04-03 02:51:30'),
	(10, 'testuser1743794894749', '$2y$12$aizUdQwvWFuu5dGiSjKaS.LfTTp8DVm3TdMvtHCOLTjnHTLdxKkwG', 'testuser1743794894749@example.com', '1234567890', NULL, NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-04-04 12:28:16', '2025-04-04 12:28:16'),
	(21, 'henryne', '$2y$12$SH81dFilxbp9IH8Y9106o.M9lY9d9c5Bm3bZnGDjj3FCqZStvQBni', 'hlongsosa1@gmail.com', '0585170542', NULL, NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-04-07 04:26:56', '2025-04-07 04:26:56'),
	(22, 'thuan123', '$2y$12$mrF3SrGQ35.3PXeq047e8uI0CLa0kkK1piOjs64y1vDfzt.eIoiwO', 'thuan12@gmail.com', '0833853385', NULL, NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-04-08 17:41:38', '2025-04-08 17:41:38'),
	(23, 'panhday', '$2y$12$NGyxK410WqaCALSAMxV2D.AXq5wB1AY0AakQ68yOSXs7O2zQPiU9G', 'longanh@gmail.com', '0123443333', NULL, NULL, NULL, 'https://i.imgur.com/Tr9qnkI.jpeg', 1, '2025-04-09 19:46:22', '2025-04-09 19:46:22');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
