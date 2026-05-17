-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 04:03 PM
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
-- Database: `generator`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddCheat` (IN `p_student_id` INT, IN `p_quiz_id` INT, IN `p_activity` VARCHAR(255))   BEGIN

    INSERT INTO cheating_logs (student_id, quiz_id, activity)
    VALUES (p_student_id, p_quiz_id, p_activity);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddLog` (IN `p_user_id` INT, IN `p_activity` TEXT, IN `p_type` ENUM('login','quiz','security','admin'))   BEGIN

    INSERT INTO activity_logs (user_id, activity, log_type)
    VALUES (p_user_id, p_activity, p_type);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddQuiz` (IN `p_teacher_id` INT, IN `p_title` VARCHAR(255), IN `p_type` VARCHAR(50), IN `p_difficulty` VARCHAR(20))   BEGIN

    INSERT INTO quizzes (
        teacher_id, title, quiz_type, difficulty
    )
    VALUES (
        p_teacher_id, p_title, p_type, p_difficulty
    );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddUser` (IN `p_id` INT, IN `p_firstname` VARCHAR(50), IN `p_lastname` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_password` VARCHAR(255), IN `p_role` ENUM('admin','teacher','student'))   BEGIN

    INSERT INTO users (
        user_id, firstname, lastname, email, password, role, status
    )
    VALUES (
        p_id, p_firstname, p_lastname, p_email, p_password, p_role, 'active'
    );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTopStudents` ()   BEGIN
    SELECT 
        users.user_id,
        CONCAT(users.firstname, ' ', users.lastname) AS fullname,
        AVG(quiz_attempts.score) AS average_score
    FROM quiz_attempts
    JOIN users 
        ON quiz_attempts.student_id = users.user_id
    GROUP BY users.user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `StartAttempt` (IN `p_student_id` INT, IN `p_quiz_id` INT)   BEGIN

    INSERT INTO quiz_attempts (
        student_id, quiz_id, status
    )
    VALUES (
        p_student_id, p_quiz_id, 'ongoing'
    );

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SubmitAttempt` (IN `p_attempt_id` INT, IN `p_score` INT, IN `p_total` INT)   BEGIN

    UPDATE quiz_attempts
    SET score = p_score,
        total_questions = p_total,
        status = 'submitted'
    WHERE attempt_id = p_attempt_id;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_type` enum('login','quiz','security','admin') DEFAULT 'quiz'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `activity`, `created_at`, `log_type`) VALUES
(1, 3, 'User logged in', '2026-05-16 13:31:23', 'login'),
(2, 3, 'Started quiz: stored', '2026-05-16 13:32:44', 'quiz'),
(3, 3, 'Answered question in quiz: stored', '2026-05-16 13:32:53', 'quiz'),
(4, 3, 'Cheating detected: tab_switch', '2026-05-16 13:32:54', 'security'),
(5, 3, 'Answered question in quiz: stored', '2026-05-16 13:32:57', 'quiz'),
(6, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:04', 'quiz'),
(7, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:08', 'quiz'),
(8, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:12', 'quiz'),
(9, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:18', 'quiz'),
(10, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:23', 'quiz'),
(11, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:27', 'quiz'),
(12, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:32', 'quiz'),
(13, 3, 'Answered question in quiz: stored', '2026-05-16 13:33:37', 'quiz'),
(14, 3, 'Finished quiz: stored', '2026-05-16 13:33:38', 'quiz'),
(15, 3, 'Cheating detected: tab_switch', '2026-05-16 13:33:38', 'security'),
(16, 3, 'Started quiz: stored', '2026-05-16 13:39:03', 'quiz'),
(17, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:12', 'quiz'),
(18, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:16', 'quiz'),
(19, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:18', 'quiz'),
(20, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:22', 'quiz'),
(21, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:25', 'quiz'),
(22, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:29', 'quiz'),
(23, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:37', 'quiz'),
(24, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:41', 'quiz'),
(25, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:46', 'quiz'),
(26, 3, 'Answered question in quiz: stored', '2026-05-16 13:39:48', 'quiz'),
(27, 3, 'Finished quiz: stored', '2026-05-16 13:39:49', 'quiz'),
(28, 4, 'User logged in', '2026-05-16 13:40:29', 'login'),
(29, 4, 'Started quiz: stored', '2026-05-16 13:40:40', 'quiz'),
(30, 4, 'Answered question in quiz: stored', '2026-05-16 13:40:46', 'quiz'),
(31, 4, 'Answered question in quiz: stored', '2026-05-16 13:40:48', 'quiz'),
(32, 4, 'Answered question in quiz: stored', '2026-05-16 13:40:50', 'quiz'),
(33, 4, 'Answered question in quiz: stored', '2026-05-16 13:40:58', 'quiz'),
(34, 4, 'Answered question in quiz: stored', '2026-05-16 13:41:01', 'quiz'),
(35, 4, 'Answered question in quiz: stored', '2026-05-16 13:41:01', 'quiz'),
(36, 4, 'Answered question in quiz: stored', '2026-05-16 13:41:06', 'quiz'),
(37, 4, 'Answered question in quiz: stored', '2026-05-16 13:41:08', 'quiz'),
(38, 4, 'Answered question in quiz: stored', '2026-05-16 13:41:12', 'quiz'),
(39, 4, 'Answered question in quiz: stored', '2026-05-16 13:41:15', 'quiz'),
(40, 4, 'Finished quiz: stored', '2026-05-16 13:41:15', 'quiz'),
(41, 3, 'User logged in', '2026-05-16 13:43:13', 'login'),
(42, 3, 'Started quiz: stored', '2026-05-16 13:43:17', 'quiz'),
(43, 3, 'Cheating detected: tab_switch', '2026-05-16 13:43:33', 'security'),
(44, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:38', 'quiz'),
(45, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:41', 'quiz'),
(46, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:43', 'quiz'),
(47, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:47', 'quiz'),
(48, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:50', 'quiz'),
(49, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:54', 'quiz'),
(50, 3, 'Answered question in quiz: stored', '2026-05-16 13:43:57', 'quiz'),
(51, 3, 'Answered question in quiz: stored', '2026-05-16 13:44:00', 'quiz'),
(52, 3, 'Answered question in quiz: stored', '2026-05-16 13:44:03', 'quiz'),
(53, 3, 'Finished quiz: stored', '2026-05-16 13:44:03', 'quiz'),
(54, 3, 'Started quiz: hards', '2026-05-16 13:44:31', 'quiz'),
(55, 3, 'Cheating detected: paste', '2026-05-16 13:44:52', 'security'),
(56, 3, 'Answered question in quiz: hards', '2026-05-16 13:44:54', 'quiz'),
(57, 3, 'Finished quiz: hards', '2026-05-16 13:44:54', 'quiz'),
(58, 2, 'User logged in', '2026-05-16 14:05:22', 'login'),
(12345, 2, 'act 1', '2026-05-17 11:32:17', 'quiz');

-- --------------------------------------------------------

--
-- Table structure for table `attempt_answers`
--

CREATE TABLE `attempt_answers` (
  `answer_id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attempt_answers`
--

INSERT INTO `attempt_answers` (`answer_id`, `attempt_id`, `question_id`, `selected_answer`, `is_correct`) VALUES
(111, 35, 107, 'C', 1),
(112, 35, 109, 'B', 1),
(113, 35, 111, 'D', 1),
(114, 35, 112, 'D', 1),
(115, 35, 113, 'B', 1),
(116, 35, 114, 'C', 1),
(117, 35, 115, 'C', 1),
(118, 35, 116, 'C', 1),
(119, 35, 117, 'D', 1),
(120, 35, 118, 'A', 0),
(121, 36, 107, 'C', 1),
(122, 36, 109, 'B', 1),
(123, 36, 111, 'D', 1),
(124, 36, 112, 'D', 1),
(125, 36, 113, 'B', 1),
(126, 36, 114, 'C', 1),
(127, 36, 115, 'C', 1),
(128, 36, 116, 'C', 1),
(129, 36, 117, 'D', 1),
(130, 36, 118, 'C', 1),
(131, 37, 121, '$_SERVER', 1),
(132, 38, 124, 'B', 0),
(133, 41, 124, 'A', 0),
(134, 42, 124, 'B', 0),
(135, 43, 124, 'False', 1),
(136, 44, 124, 'False', 1),
(137, 46, 124, 'False', 1),
(138, 50, 107, 'C', 1),
(139, 50, 109, 'B', 1),
(140, 50, 111, 'D', 1),
(141, 50, 112, 'D', 1),
(142, 50, 113, 'B', 1),
(143, 50, 114, 'C', 1),
(144, 50, 115, 'C', 1),
(145, 50, 116, 'C', 1),
(146, 50, 117, 'D', 1),
(147, 50, 118, 'C', 1),
(148, 51, 107, 'A', 0),
(149, 51, 109, 'B', 1),
(150, 51, 111, 'B', 0),
(151, 51, 112, 'A', 0),
(152, 51, 113, 'B', 1),
(153, 51, 114, 'C', 1),
(154, 51, 115, 'A', 0),
(155, 51, 116, 'C', 1),
(156, 51, 117, 'D', 1),
(157, 51, 118, 'B', 0),
(158, 52, 107, 'B', 0),
(159, 52, 109, 'C', 0),
(160, 52, 111, 'B', 0),
(161, 52, 112, 'B', 0),
(162, 52, 113, 'C', 0),
(163, 52, 115, 'A', 0),
(164, 52, 116, 'B', 0),
(165, 52, 117, 'D', 1),
(166, 52, 118, 'C', 1),
(167, 53, 109, 'B', 1),
(168, 53, 111, 'C', 0),
(169, 53, 112, 'C', 0),
(170, 53, 113, 'B', 1),
(171, 53, 114, 'C', 1),
(172, 53, 115, 'B', 0),
(173, 53, 116, 'C', 1),
(174, 53, 117, 'D', 1),
(175, 53, 118, 'C', 1),
(176, 54, 121, '$_SERVER', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cheating_logs`
--

CREATE TABLE `cheating_logs` (
  `cheat_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cheating_logs`
--

INSERT INTO `cheating_logs` (`cheat_id`, `student_id`, `quiz_id`, `activity`, `created_at`) VALUES
(31, 3, 29, 'tab_switch', '2026-05-16 13:43:33'),
(32, 3, 38, 'paste', '2026-05-16 13:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_type` enum('multiple_choice','true_false','identification','enumeration') NOT NULL DEFAULT 'multiple_choice',
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_bank` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `question_type`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `created_at`, `is_bank`) VALUES
(107, 29, 'multiple_choice', '1. What does PHP stand for?', 'PHP: Hypertext Processor', 'Personal Hypertext Processor', 'PHP: Hypertext Preprocessor', 'Personal Home Page', 'C', '2026-05-15 04:02:49', 1),
(109, 29, 'multiple_choice', '2. The default file extension in PHP are ____', '.css', '.php', '.js', '.html', 'B', '2026-05-15 04:03:53', 1),
(111, 29, 'multiple_choice', '3. How does the name of the variable in PHP starts?', 'Sign !', 'Sign #', 'Sign &', 'Sign $', 'D', '2026-05-15 04:05:52', 1),
(112, 29, 'multiple_choice', '4. Which of the following is the syntax of comment in PHP?', '/* */', '#', '//', 'All of the above', 'D', '2026-05-15 04:07:30', 1),
(113, 29, 'multiple_choice', '5. Which of the following is the correct way to concrete() the two strings in PHP?', '+', '.', 'Append', 'All of the above', 'B', '2026-05-15 04:08:54', 1),
(114, 29, 'multiple_choice', '6.  Which of the following function is used to redirect PHP pages?', 'redirect()', 'reflect()', 'header()', 'None of the above', 'C', '2026-05-15 04:10:11', 1),
(115, 29, 'multiple_choice', '7. What is the correct way to create a function in PHP?', 'new_function functionName()', 'create functionName()', 'function functionName()', 'create_function functionName()', 'C', '2026-05-15 04:16:24', 1),
(116, 29, 'multiple_choice', '8. Which operator is used to check if two values are equal and of same data type?', '=', '==', '===', '!=', 'C', '2026-05-15 04:19:09', 1),
(117, 29, 'multiple_choice', '9. \r\nWhat is the correct way to end a PHP statement?', 'End', '.', ':', ';', 'D', '2026-05-15 04:22:34', 1),
(118, 29, 'multiple_choice', '10. Which global variable in PHP holds information about headers, paths, and script locations?', '$_GET', '$_POST', '$_SERVER', '$_ENV', 'C', '2026-05-15 04:25:30', 1),
(121, 38, 'identification', '1. Which global variable in PHP holds information about headers, paths, and script locations?', '', '', '', '', '$_SERVER', '2026-05-16 07:52:08', 1),
(124, 39, 'true_false', '1. PHP stand for PHP: Hypertext Processor.', '', '', '', '', 'False', '2026-05-16 08:06:31', 1),
(125, 40, 'multiple_choice', '1. Which global variable in PHP holds information about headers, paths, and script locations?', '$_GET', '$_POST', '$_SERVER', '$_ENV', 'C', '2026-05-16 08:46:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quiz_type` enum('multiple_choice','identification','true_false','enumeration') NOT NULL DEFAULT 'multiple_choice',
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `teacher_id`, `title`, `description`, `status`, `created_at`, `quiz_type`, `difficulty`) VALUES
(29, 2, 'stored', NULL, 'published', '2026-05-13 06:52:09', '', 'easy'),
(38, 2, 'hards', NULL, 'published', '2026-05-16 07:45:36', 'identification', 'hard'),
(39, 2, 'mediums', NULL, 'published', '2026-05-16 07:45:50', 'true_false', 'medium'),
(40, 2, 'easys', NULL, 'draft', '2026-05-16 07:45:59', 'multiple_choice', 'easy');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `attempt_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) DEFAULT 0,
  `total_questions` int(11) DEFAULT 0,
  `status` enum('ongoing','submitted','timeout') NOT NULL DEFAULT 'ongoing',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`attempt_id`, `student_id`, `quiz_id`, `score`, `total_questions`, `status`, `submitted_at`) VALUES
(35, 3, 29, 9, 10, 'submitted', '2026-05-15 04:26:52'),
(36, 4, 29, 10, 10, 'submitted', '2026-05-15 04:32:50'),
(37, 3, 38, 1, 1, 'submitted', '2026-05-16 08:01:01'),
(38, 3, 39, 0, 1, 'submitted', '2026-05-16 08:06:53'),
(39, 3, 29, 0, 0, 'ongoing', '2026-05-16 08:07:01'),
(40, 3, 39, 0, 1, 'submitted', '2026-05-16 08:07:20'),
(41, 3, 39, 0, 1, 'submitted', '2026-05-16 08:11:50'),
(42, 3, 39, 0, 1, 'submitted', '2026-05-16 11:30:10'),
(43, 3, 39, 1, 1, 'submitted', '2026-05-16 11:35:40'),
(44, 3, 39, 1, 1, 'submitted', '2026-05-16 11:37:59'),
(45, 3, 39, 0, 1, 'submitted', '2026-05-16 11:38:28'),
(46, 3, 39, 1, 1, 'submitted', '2026-05-16 11:41:28'),
(47, 3, 39, 0, 1, 'submitted', '2026-05-16 11:51:06'),
(48, 3, 39, 0, 1, 'submitted', '2026-05-16 11:52:02'),
(49, 3, 38, 0, 1, 'submitted', '2026-05-16 11:52:08'),
(50, 3, 29, 10, 10, 'submitted', '2026-05-16 13:32:44'),
(51, 3, 29, 5, 10, 'submitted', '2026-05-16 13:39:03'),
(52, 4, 29, 2, 10, 'submitted', '2026-05-16 13:40:40'),
(53, 3, 29, 6, 10, 'submitted', '2026-05-16 13:43:17'),
(54, 3, 38, 1, 1, 'submitted', '2026-05-16 13:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_id`) VALUES
(1, 30, 118);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `middlename`, `lastname`, `email`, `password`, `role`, `profile_picture`, `status`, `created_at`) VALUES
(1, 'Hazel', 'Octavio', 'Purificacion', 'hazel21@gmail.com', '$2y$10$HDHE22CfOB86m2uY6vE9tOb7XtGp113phSSn1s0WTNe/Q8yeB665a', 'admin', NULL, 'active', '2026-05-10 23:51:01'),
(2, 'Elmira', 'Manzano', 'Miranda', 'emira@gmail.com', '$2y$10$56boHuQaZ.s4xEVovPSi2uXrHiXB8Bv9TfX079gOcv3zNECSfHK/6', 'teacher', NULL, 'active', '2026-05-11 01:11:28'),
(3, 'Chrizen', 'G.', 'Alcantara', 'alcantara@gmail.com', '$2y$10$F6dWmk6OST8Le8sDyV8V5eamLGFrwnL2RfYvqbFjGy.fml86dx.Oy', 'student', NULL, 'active', '2026-05-11 01:40:20'),
(4, 'als', NULL, '', 'al@gmail.com', '$2y$10$F6dWmk6OST8Le8sDyV8V5eamLGFrwnL2RfYvqbFjGy.fml86dx.Oy', 'student', NULL, 'active', '2026-05-14 00:50:32');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert_cheating` AFTER INSERT ON `users` FOR EACH ROW BEGIN

    INSERT INTO cheating_logs (student_id, quiz_id, activity)
    VALUES (NEW.user_id, 29, 'Account created (system log)');

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `alter_user_insert_activity` AFTER INSERT ON `users` FOR EACH ROW BEGIN

    INSERT INTO activity_logs (user_id, activity, log_type)
    VALUES (NEW.user_id, 'User registered', 'admin');

END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indexes for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `fk_answer_attempt` (`attempt_id`),
  ADD KEY `fk_answer_question` (`question_id`);

--
-- Indexes for table `cheating_logs`
--
ALTER TABLE `cheating_logs`
  ADD PRIMARY KEY (`cheat_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `fk_question_quiz` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `fk_quiz_teacher` (`teacher_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `fk_attempt_student` (`student_id`),
  ADD KEY `fk_attempt_quiz` (`quiz_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12354;

--
-- AUTO_INCREMENT for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `cheating_logs`
--
ALTER TABLE `cheating_logs`
  MODIFY `cheat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12345690;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `attempt_answers`
--
ALTER TABLE `attempt_answers`
  ADD CONSTRAINT `fk_answer_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`attempt_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `cheating_logs`
--
ALTER TABLE `cheating_logs`
  ADD CONSTRAINT `cheating_logs_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cheating_logs_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_question_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quiz_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `fk_attempt_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attempt_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
