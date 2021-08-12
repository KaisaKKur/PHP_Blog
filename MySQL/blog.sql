-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 12, 2021 at 04:14 PM
-- Server version: 8.0.25
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_articles`
--

CREATE TABLE `blog_articles` (
  `ID` int UNSIGNED NOT NULL,
  `UserID` int UNSIGNED NOT NULL,
  `CategoryID` int UNSIGNED NOT NULL,
  `Title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Content` longtext NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PageView` int UNSIGNED NOT NULL DEFAULT '0',
  `LikeCount` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blog_articles`
--

INSERT INTO `blog_articles` (`ID`, `UserID`, `CategoryID`, `Title`, `Content`, `Date`, `PageView`, `LikeCount`) VALUES
(1, 1, 2, 'Kaisa\' Blog', 'Kaisa\' Blog', '2021-06-30 23:17:37', 0, 0),
(5, 3, 3, '111', '啊飒飒的啊', '2021-07-08 17:14:46', 0, 0),
(6, 3, 2, '22222222222', '222222222222', '2021-07-08 17:19:25', 0, 0),
(9, 3, 3, '完全的青蛙', '读取', '2021-07-08 19:44:21', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `blog_article_tag`
--

CREATE TABLE `blog_article_tag` (
  `ID` int UNSIGNED NOT NULL,
  `BlogArticleID` int UNSIGNED NOT NULL,
  `TagID` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blog_article_tag`
--

INSERT INTO `blog_article_tag` (`ID`, `BlogArticleID`, `TagID`) VALUES
(2, 1, 1),
(3, 1, 7),
(22, 5, 6),
(29, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int UNSIGNED NOT NULL,
  `Name` varchar(32) NOT NULL,
  `Alias` varchar(16) DEFAULT NULL,
  `Description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Alias`, `Description`) VALUES
(1, 'Life', NULL, 'All my Life.'),
(2, 'Study', NULL, 'In studing.'),
(3, 'Other', NULL, 'Uncategoried.');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int UNSIGNED NOT NULL,
  `BlogArticleID` int UNSIGNED NOT NULL,
  `UserEmail` varchar(40) NOT NULL,
  `Content` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sign_up_verification_codes`
--

CREATE TABLE `sign_up_verification_codes` (
  `ID` tinyint UNSIGNED NOT NULL,
  `UserID` int UNSIGNED NOT NULL,
  `Code` char(6) NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sign_up_verification_codes`
--

INSERT INTO `sign_up_verification_codes` (`ID`, `UserID`, `Code`, `Date`) VALUES
(1, 1, '39864d', '2021-08-09 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `ID` int UNSIGNED NOT NULL,
  `Name` varchar(16) NOT NULL,
  `Alias` varchar(16) DEFAULT NULL,
  `Description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`ID`, `Name`, `Alias`, `Description`) VALUES
(1, '游戏', 'Game', 'My like games.'),
(2, '食品', 'Food', 'About foods.'),
(3, '购物', 'Shopping ', 'Go shopping.'),
(4, '程序设计', 'Code', 'About Coding.'),
(5, '旅游', 'Journey', 'About traveling.'),
(6, '建模', NULL, NULL),
(7, '应用程序', 'EXE', 'Make and use exe.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int UNSIGNED NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` char(40) NOT NULL,
  `Nickname` varchar(30) DEFAULT NULL,
  `Email` varchar(40) NOT NULL,
  `PhoneNumber` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `SignUpTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Birthday` date DEFAULT NULL,
  `Sex` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Username`, `Password`, `Nickname`, `Email`, `PhoneNumber`, `SignUpTime`, `Birthday`, `Sex`) VALUES
(1, 'Kaisa.K.Kur', '85c5c627ff0ae97af5485a23c505797314d23669', 'KaisaKur', 'Kaisa.K.Kur@gmail.com', '1234567890', '2021-06-24 12:44:16', '1999-09-13', 1),
(3, '1', '356a192b7913b04c54574d18c28d46e6395428ab', 'Tst', '1@localhost', '1111111', '2021-07-05 09:34:02', '1921-02-08', 0),
(4, '2', 'da4b9237bacccdf19c0760cab7aec4a8359010b0', NULL, '1@localhost', NULL, '2021-07-10 23:11:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_chats`
--

CREATE TABLE `user_chats` (
  `ID` int UNSIGNED NOT NULL,
  `UserID1` int UNSIGNED NOT NULL,
  `UserID2` int UNSIGNED NOT NULL,
  `Chat` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_chats`
--

INSERT INTO `user_chats` (`ID`, `UserID1`, `UserID2`, `Chat`, `Date`) VALUES
(27, 3, 4, '3:4:11', '2021-07-13 14:23:47'),
(28, 4, 3, '4:3:211', '2021-07-13 14:25:51'),
(29, 3, 4, '3:4:11dfdafa', '2021-07-13 14:26:30'),
(30, 4, 3, '4:3:qdwqqwf', '2021-07-13 14:26:44'),
(31, 4, 3, '4:3:qwwqeqwv', '2021-07-13 14:27:02'),
(34, 3, 4, '3:4:1111wqwqwqwqsqdwqd', '2021-07-13 15:57:51'),
(35, 4, 3, '4:3:qqqqq', '2021-07-13 15:57:56'),
(36, 3, 1, '3:1:dddd', '2021-07-13 15:58:12'),
(37, 3, 1, '3:1:asadas', '2021-07-13 16:05:32'),
(38, 3, 1, '3:1:okokokokok', '2021-07-13 16:17:39'),
(39, 3, 4, '3:4:assess ', '2021-07-13 16:24:15'),
(40, 4, 3, '4:3:sdfadfasf', '2021-07-13 16:24:18'),
(41, 3, 1, '3:1:fffff', '2021-07-13 16:24:31'),
(42, 4, 1, '4:1:sdfadfasf', '2021-07-13 16:25:51'),
(43, 3, 4, '3:4:阿萨', '2021-07-15 10:27:27'),
(44, 4, 3, '4:3:打撒', '2021-07-15 10:27:31'),
(45, 3, 1, '3:1:阿萨', '2021-07-15 10:27:51'),
(46, 3, 1, '3:1:是的稳定器', '2021-07-15 10:28:01'),
(47, 4, 3, '4:3:wwww', '2021-07-15 13:53:17'),
(48, 3, 1, '3:1:qqqq ', '2021-07-15 13:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_logs`
--

CREATE TABLE `user_login_logs` (
  `ID` int UNSIGNED NOT NULL,
  `UserID` int UNSIGNED NOT NULL,
  `LoginIP` varchar(12) NOT NULL,
  `AutoLogin` tinyint(1) NOT NULL DEFAULT '0',
  `UserPassword` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `LoginDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_login_logs`
--

INSERT INTO `user_login_logs` (`ID`, `UserID`, `LoginIP`, `AutoLogin`, `UserPassword`, `LoginDate`) VALUES
(10, 3, '127.0.0.1', 1, NULL, '2021-07-22 00:00:00'),
(11, 4, '127.0.0.1', 0, NULL, '2021-07-22 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_BA_U_UserID` (`UserID`),
  ADD KEY `FK_BA_C_CategoryID` (`CategoryID`);

--
-- Indexes for table `blog_article_tag`
--
ALTER TABLE `blog_article_tag`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_BAT_BA_BlogArticleID` (`BlogArticleID`),
  ADD KEY `FK_BAT_T_TagID` (`TagID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `U_Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_C_BA_BlogArticleID` (`BlogArticleID`);

--
-- Indexes for table `sign_up_verification_codes`
--
ALTER TABLE `sign_up_verification_codes`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `U_Code` (`Code`),
  ADD KEY `FK_SUVC_U_UserID` (`UserID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `U_Name` (`Name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `U_Username` (`Username`);

--
-- Indexes for table `user_chats`
--
ALTER TABLE `user_chats`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_UC_U_UserID1` (`UserID1`),
  ADD KEY `FK_UC_U_UserID2` (`UserID2`);

--
-- Indexes for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_ULL_U_UserID` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_articles`
--
ALTER TABLE `blog_articles`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blog_article_tag`
--
ALTER TABLE `blog_article_tag`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sign_up_verification_codes`
--
ALTER TABLE `sign_up_verification_codes`
  MODIFY `ID` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_chats`
--
ALTER TABLE `user_chats`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD CONSTRAINT `FK_BA_C_CategoryID` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_BA_U_UserID` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blog_article_tag`
--
ALTER TABLE `blog_article_tag`
  ADD CONSTRAINT `BlogArticle` FOREIGN KEY (`BlogArticleID`) REFERENCES `blog_articles` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Tag` FOREIGN KEY (`TagID`) REFERENCES `tags` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`BlogArticleID`) REFERENCES `blog_articles` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sign_up_verification_codes`
--
ALTER TABLE `sign_up_verification_codes`
  ADD CONSTRAINT `sign_up_verification_codes_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_chats`
--
ALTER TABLE `user_chats`
  ADD CONSTRAINT `FK_UC_U_UserID1` FOREIGN KEY (`UserID1`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_UC_U_UserID2` FOREIGN KEY (`UserID2`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_login_logs`
--
ALTER TABLE `user_login_logs`
  ADD CONSTRAINT `FK_ULL_U_UserID` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
