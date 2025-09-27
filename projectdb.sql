-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2025 at 12:58 PM
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
-- Database: `projectdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(10) NOT NULL,
  `gender` varchar(3) NOT NULL,
  `city` varchar(255) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `message` varchar(255) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `name`, `lastname`, `email`, `phone`, `gender`, `city`, `appointment_date`, `message`, `doctor_id`) VALUES
(51, 'Erolinda', 'Mazreku', 'erolindamazreku@gmail.com', 123456789, 'F', 'Prizren', '2025-05-23 00:06:00', '', 3),
(52, 'Erolinda', 'Mazreku', 'eroliindamazreku@gmail.com', 494842480, 'F', 'Prishtine', '2025-05-24 18:45:00', 'See you later!', 50),
(53, 'Erolinda', 'Mazreku', 'elsaaamazreku@gmail.com', 494842480, 'M', 'Prishtine', '2025-05-24 12:00:00', 'Hello! ', 51),
(55, 'Test', 'Test', 'test.testi@hotmail.com', 494842480, 'M', 'Gjakove', '2025-05-25 19:58:00', '', 49),
(56, 'Erolinda', 'Mazreku', 'eroliindazreku@gmail.com', 494842480, 'F', 'Prizren', '2025-05-26 20:59:00', '', 50),
(57, 'Eda', 'Test', 'edaamazreku@gmail.ocm', 49123456, 'F', 'Prishtine', '2025-05-28 11:00:00', '', 51),
(58, 'Erjola', 'Mazreku', 'erjolamazreku@gmail.com', 123456789, 'F', 'Peje', '2025-05-29 13:00:00', '', 2),
(59, 'Erolinda', 'Mazreku', 'eroliindazreku@gmail.com', 494842480, 'F', 'Prizren', '2025-05-28 12:02:00', 'Hi\r\nSee you there!', 49),
(60, 'Erolinda', 'Mazreku', 'erolinda2@gmail.com', 494842480, 'F', 'Peje', '2025-05-30 22:38:00', '', 2),
(61, 'Arta', 'Morina', 'artamorina@gmail.com', 2147483647, 'F', 'Gjakove', '2025-05-29 18:45:00', '', 3),
(62, 'Erjola', 'Mazreku', 'erjolamaz@gmail.com', 494842480, 'F', 'Peje', '2025-05-29 19:20:00', '', 3),
(65, 'Erolinda', 'Mazreku', 'erolinda2@gmail.com', 2147483647, 'F', 'Prizren', '2025-05-29 09:00:00', '', 3),
(66, 'Test', 'mazreku', 'erolinda2@gmail.com', 494842480, 'F', 'Prizren', '2025-05-29 09:30:00', '', 3),
(67, 'erolinda', 'Mazreku', 'erolinda2@gmail.com', 494842480, 'F', 'Prizren', '2025-05-29 10:30:00', '', 3),
(68, 'Erolinda', 'Mazreku', 'user@gmail.com', 494842480, 'M', 'Prizren', '2025-05-29 15:00:00', 'nb', 3),
(69, 'Erolinda', 'Mazreku', 'erolinda2@gmail.com', 494842480, 'F', 'Peje', '2025-05-29 12:30:00', '', 3),
(70, 'Erjola', 'Mazreku', 'erjolamazreku@gmail.com', 2147483647, 'F', 'Prizren', '2025-05-30 12:00:00', '', 49),
(71, 'Erolinda', 'Mazreku', 'erolindamazreku@gmail.com', 59484216, 'F', 'Prizren', '2025-05-29 10:00:00', '', 49),
(72, 'Erolinda', 'Mazreku', 'eroliindamazreku@gmail.com', 494842480, 'F', 'Prizren', '2025-05-30 10:30:00', '', 49),
(73, 'Erolinda', 'Mazreku', 'eroliindamazreku@gmail.com', 494842480, 'F', 'Peje', '2025-05-30 09:30:00', '', 3),
(74, 'Erolinda', 'Test', 'erolinda2@gmail.com', 2147483647, 'F', 'Prizren', '2025-06-26 10:00:00', 'See you soon!', 3),
(75, 'Eda', 'Emini', 'edaemini@gmail.com', 494842480, 'F', 'Prishtine', '2025-06-26 09:30:00', '', 3),
(76, 'Andi', 'Ahmeti', 'andiahmeti@gmail.com', 2147483647, 'M', 'Gjakove', '2025-06-27 09:30:00', '', 3),
(77, 'Gerta', 'Gashi', 'gashigerta@gmail.com', 2147483647, 'M', 'Prizren', '2025-06-13 10:00:00', '', 49),
(78, 'erolinda', 'Mazreku', 'user@gmail.com', 494842480, 'F', 'Prizren', '2025-06-18 15:00:00', '', 49),
(79, 'Erolinda', 'Mazreku', 'eroliindamazreku@gmail.com', 494842480, 'F', 'Prizren', '2025-06-25 09:00:00', '', 49),
(80, 'Erolinda', 'Mazreku', 'user@gmail.com', 494842480, 'F', 'Prizren', '2025-06-26 09:30:00', '', 49),
(81, 'Erolinda', 'Mazreku', 'eroliindamazreku@gmail.com', 494842480, 'F', 'Peje', '2025-06-19 09:30:00', '', 51),
(82, 'Erolinda', 'Mazreku', 'user@gmail.com', 494842480, 'F', 'Prizren', '2025-06-25 09:30:00', '', 51),
(83, 'Erolinda', 'Mazreku', 'eroliindamazreku@gmail.com', 2147483647, 'M', 'Prizren', '2025-06-27 14:00:00', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_feedback`
--

CREATE TABLE `doctor_feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_feedback`
--

INSERT INTO `doctor_feedback` (`id`, `user_id`, `doctor_id`, `rating`, `comment`, `created_at`) VALUES
(1, 48, 2, '2', 'Very good doctor! Had great communication skills also.', '2025-06-10 18:27:25'),
(2, 48, 2, '5', 'Very professional and kind.', '2025-06-10 18:30:03'),
(3, 48, 2, '5', 'Highly recommended.', '2025-06-10 18:31:13'),
(4, 48, 2, '5', 'Cared about my concerns.', '2025-06-10 18:31:24'),
(5, 48, 2, '5', 'Fast and friendly.', '2025-06-10 18:34:00'),
(6, 48, 49, '3', 'Great experience.', '2025-06-10 18:34:16'),
(7, 48, 49, '3', 'Listened patiently.', '2025-06-10 18:34:36'),
(8, 48, 3, '3', 'Would visit again.', '2025-06-10 18:36:12'),
(9, 48, 3, '3', 'Great doctor!', '2025-06-10 18:41:10'),
(10, 48, 50, '5', 'Helpful and respectful.', '2025-06-10 18:43:09'),
(11, 48, 50, '5', 'Solved my issue quickly.', '2025-06-10 18:51:19'),
(12, 48, 51, '3', 'Nice doctor!', '2025-06-10 18:51:39'),
(13, 48, 51, '5', 'Had a really great time!', '2025-06-10 18:55:11'),
(14, 48, 2, '3', 'Explained everything well.', '2025-06-10 18:56:52'),
(15, 48, 3, '3', 'Genuine care.', '2025-06-10 18:57:10'),
(16, 48, 3, '5', 'Very knowledgeable.', '2025-06-10 18:57:49'),
(17, 48, 3, '5', 'Very observative doctor!!', '2025-06-10 19:00:14'),
(18, 48, 50, '5', 'Very nice doctor!', '2025-06-10 21:09:29'),
(19, 48, 50, '4', 'A great doctor.', '2025-06-11 16:21:14'),
(20, 48, 51, '4', 'Very knowledgeable.', '2025-06-11 16:26:28'),
(21, 48, 3, '3', 'Not bad!', '2025-06-11 18:02:35'),
(22, 48, 3, '3', 'Good doctor!', '2025-06-30 14:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`id`, `patient_id`, `visit_date`, `visit_time`, `medications`, `notes`, `diagnosis`, `created_at`) VALUES
(1, 3, '2025-06-20', '16:44:00', 'blabla', 'lsajdja', 'lorem ipsum', '2025-06-13 14:41:29'),
(2, 3, '2025-07-05', '16:44:00', 'ergfh', 'hgter', 'lorem ipsum', '2025-06-13 14:42:10'),
(3, 3, '2025-06-17', '18:46:00', 'adg', 'ads', 'lorem ipsum', '2025-06-13 14:46:37'),
(4, 3, '2025-06-16', '16:51:00', 'tuyi', 'rtmjhyrws', 'lorem ipsum', '2025-06-13 14:48:33'),
(5, 16, '2025-06-24', '17:56:00', 'wrfdg', 'vfrewd', 'sacdsvfdbg', '2025-06-13 15:53:12'),
(6, 19, '2025-06-18', '22:10:00', 'sdfdf', 'fesgdfg', 'lorem ipsum', '2025-06-13 16:10:07'),
(8, 21, '2025-06-24', '22:31:00', 'lorem medication', 'lorem ipsum lorem', 'lorem ipsum', '2025-06-13 16:31:44'),
(9, 19, '2025-06-08', '21:02:00', 'fed', 'w2qw', 'ewq', '2025-06-13 19:59:15'),
(10, 3, '2025-06-09', '18:40:00', 'jkh', 'ppppppppppppppp', 'lorem ipsum', '2025-06-14 12:40:34'),
(12, 12, '2025-06-12', '19:00:00', 'lorem', 'Lorem ipsum lorem ipsum', 'lorem ipsum', '2025-06-30 15:07:01'),
(13, 12, '2025-06-12', '21:20:00', 'lorem ipsum', 'lorem ipsum', 'lorem ipsum', '2025-06-30 15:20:39'),
(14, 12, '2025-06-28', '17:23:00', 'lorem', 'lorem lorem', 'lorem ipsum', '2025-06-30 15:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `sent_at`, `user_id`) VALUES
(1, 'erolinda', 'edaamazreku@gmail.com', 'Testim', 'Pershendetje, testimi', '2025-05-23 14:51:15', 41),
(2, 'Erjola', 'erjolamazreku@gmail.com', 'Kerkese', 'Pershendetje,\r\nPo testojme contact formen.\r\nGjithe te mirat', '2025-05-23 14:52:23', 46),
(3, 'User', 'user@gmail.com', 'Test-User', 'Kerkesa per termine', '2025-05-23 14:54:50', 48),
(4, 'User', 'user@gmail.com', 'Kerkese', 'Kerkesa per termine', '2025-05-23 14:56:15', 48),
(6, 'erolinda', 'eroliindazreku@gmail.com', 'Testimi', 'Mesazh testimi\r\nFaleminderit!', '2025-05-23 15:01:36', 41),
(8, 'erolinda', 'eroliindazreku@gmail.com', 'Hello', 'Hi i wanted to inform you ...\r\nThank you!', '2025-05-24 19:14:34', 54),
(11, 'Erolinda', 'erolinda2@gmail.com', 'Kerkese', 'Pershendetje,\r\nTermini me i afert qe mund te bej kur eshte?\r\nFaleminderit!', '2025-06-11 15:27:59', 57),
(12, 'erolinda', 'user@gmail.com', 'Njoftim', 'Sot po ju njoftojme se ...\r\nFaleminderit!', '2025-06-11 16:20:12', 61),
(13, 'Elsa', 'elsaaamazreku@gmail.com', 'Kerkese', 'Pershendetje, ju kerkojme...\r\nFaleminderit!', '2025-06-12 21:13:59', 57),
(14, 'Test', 'test.testi@hotmail.com', 'Testi', 'Pershendetje,\r\nJemi duke testuar...\r\nFaleminderit!', '2025-06-28 13:41:20', 48),
(15, 'Erolinda', 'eroliindamazreku@gmail.com', 'Kerkese', 'Pershendetje,\r\n\r\nA mund te anuloj terminin qe e kam pasur per daten 30.07.2025 per shkaqe ...\r\nFaleminderit!\r\nGjithe te mirat!', '2025-06-30 14:19:16', 48);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `doctor_id`, `name`, `lastname`, `phone`, `gender`, `date_of_birth`, `created_at`) VALUES
(2, 50, 'Laura', 'Kastrati', '0494842480', 'Female', '2025-05-14', '2025-05-25 16:28:10'),
(3, 2, 'Hasan', 'Gashi', '+38349484248', 'Male', '2025-05-25', '2025-05-25 17:01:49'),
(5, 51, 'Eda', 'Mazreku', '044123789', 'Female', '2006-08-06', '2025-05-25 19:42:34'),
(6, 51, 'Kushtrim', 'Morina', '0494842480', 'Male', '2025-05-20', '2025-05-25 20:24:12'),
(7, 49, 'Genta', 'Malaj', '1234567809', 'Female', '2010-11-05', '2025-05-25 20:33:32'),
(12, 3, 'Andi', 'Shala', '0451238282', 'Male', '2025-06-26', '2025-05-25 20:44:41'),
(14, 3, 'Ana', 'Berisha', '20894783232', 'Female', '2020-02-23', '2025-05-25 21:03:13'),
(16, 2, 'Erolinda', 'Mazreku', '+38349123321', 'Female', '2004-01-23', '2025-06-09 18:28:36'),
(19, 2, 'Asllan', 'Emini', '+38329293843', 'Male', '2025-06-27', '2025-06-11 19:07:40'),
(21, 51, 'Asllan', 'Hajdari', '+38329422494', 'Male', '2025-06-17', '2025-06-13 16:31:15');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'doctor'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`) VALUES
(1, 'admin', 'admin@gmail.com', 'e714f5e09b26f37bb36f63f24789a3b5', 1),
(2, 'Filan Fisteku', 'filanfisteku@gmail.com', 'a53cb219484cbca178e1a61e233f46d2', 2),
(3, 'Artan Gashi', 'artangashi@gmail.com', '6275f0ce53e1172fc6d1cb833505c03b', 2),
(41, 'erolinda6', 'erolinda6@gmail.com', 'c47209aa49ef44f919b1956004b8ecf7', 3),
(46, 'test', 'test.testi@hotmail.com', 'c8c4504af0fdf48d3b82c6f33f7a934a', 3),
(48, 'User', 'user@gmail.com', '04fd0ecf0d3215802ef4edff60626c24', 3),
(49, 'Fisnik Berisha', 'fisnikberisha@gmail.com', '0ac74b15851e76fc785dd8dc23e79a98', 2),
(50, 'Arta Kryeziu', 'arta@gmail.com', 'f2bb0ecd1c5edd39b32df1ce2e6dd7d9', 2),
(51, 'Drita Krasniqi', 'dritakrasniqi@gmail.com', '2bab3cfab57a0ec4db717da277810c37', 2),
(54, 'Ana', 'anaismaili@gmail.com', 'ef1bb0f6bef1aa2eef79184efea41f96', 3),
(57, 'Alea_K', 'aleakryeziu@gmail.com', 'c7a7b0d922483edfc0dea32dc7ae4419', 3),
(61, 'YllkaD', 'yllkademiri@gmail.com', '4d98ff55fbac2067fb90d78746b66748', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_feedback`
--
ALTER TABLE `doctor_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_messages_user` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `doctor_feedback`
--
ALTER TABLE `doctor_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctor_feedback`
--
ALTER TABLE `doctor_feedback`
  ADD CONSTRAINT `doctor_feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `doctor_feedback_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
