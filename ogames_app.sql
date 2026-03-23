-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Mar 12, 2026 at 02:59 PM
-- Server version: 11.8.6-MariaDB-ubu2404
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `athletes`
--

CREATE TABLE `athletes` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(150) NOT NULL,
  `birth_country_id` int(11) NOT NULL,
  `death_date` date DEFAULT NULL,
  `death_place` varchar(150) DEFAULT NULL,
  `death_country_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `athletes`
--

INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES
(1, 'Alojz', 'Szokol', '1871-07-04', 'Hronec', 2, '1932-10-27', 'Bernecebaráti', 3),
(2, 'Zoltán', 'Halmaj', '1881-06-18', 'Vysoká pri Morave', 2, '1956-05-20', 'Budapešť', 3),
(3, 'Alexander', 'Prokopp', '1887-03-31', 'Košice', 2, '1950-11-04', 'Budapešť', 3),
(4, 'Július', 'Torma', '1922-03-07', 'Budapešť', 3, '1991-10-23', 'Praha', 7),
(5, 'Ján', 'Zachara', '1928-08-27', 'Kubrá pri Trenčíne', 2, '2025-01-02', 'Nová Dubnica', 2),
(6, 'Anton', 'Švajlen', '1937-12-03', 'Solčany', 2, NULL, NULL, NULL),
(7, 'Anton', 'Urban', '1934-01-16', 'Kysucké Nové Mesto', 2, '2021-03-05', 'Bratislava', 2),
(8, 'Vladimír', 'Dzurilla', '1942-02-22', 'Bratislava', 2, '1995-07-27', 'Düsseldorf', 11),
(9, 'Jozef', 'Golonka', '1938-01-06', 'Bratislava', 2, NULL, NULL, NULL),
(10, 'Ondrej', 'Nepela', '1951-01-22', 'Bratislava', 2, '1989-02-02', 'Mannheim', 11),
(11, 'Eva', 'Šuranová', '1946-12-16', 'Ózd', 3, '2016-12-31', 'Bratislava', 2),
(12, 'Anton', 'Tkáč', '1951-03-30', 'Lozorno', 2, '2022-12-22', 'Bratislava', 2),
(13, 'František', 'Kunzo', '1954-09-17', 'Spišský Hrušov', 2, NULL, NULL, NULL),
(14, 'Stanislav', 'Seman', '1952-08-06', 'Košice', 2, NULL, NULL, NULL),
(15, 'Imrich', 'Bugár', '1955-04-14', 'Ohrady', 2, NULL, NULL, NULL),
(16, 'Igor', 'Liba', '1960-11-04', 'Prešov', 2, NULL, NULL, NULL),
(17, 'Vincent', 'Lukáč', '1954-02-14', 'Košice', 2, NULL, NULL, NULL),
(18, 'Dušan', 'Pašek', '1960-09-07', 'Bratislava', 2, '1998-03-14', 'Bratislava', 2),
(19, 'Dárius', 'Rusnák', '1959-12-02', 'Ružomberok', 2, NULL, NULL, NULL),
(20, 'Miloslav', 'Mečíř', '1964-05-19', 'Bojnice', 2, NULL, NULL, NULL),
(21, 'Jozef', 'Pribilinec', '1960-07-06', 'Kopernica', 2, NULL, NULL, NULL),
(22, 'Miloš', 'Mečíř', '1964-05-19', 'Bojnice', 2, NULL, NULL, NULL),
(23, 'Michal', 'Martikán', '1979-05-18', 'Liptovský Mikuláš', 2, NULL, NULL, NULL),
(24, 'Slavomír', 'Kňazovický', '1967-05-03', 'Piešťany', 2, NULL, NULL, NULL),
(25, 'Jozef', 'Gönci', '1974-03-18', 'Košice', 2, NULL, NULL, NULL),
(26, 'Elena', 'Kaliská', '1972-01-19', 'Zvolen', 2, NULL, NULL, NULL),
(27, 'Peter', 'Hochschorner', '1979-09-07', 'Bratislava', 2, NULL, NULL, NULL),
(28, 'Pavol', 'Hochschorner', '1979-09-07', 'Bratislava', 2, NULL, NULL, NULL),
(29, 'Martina', 'Moravcová', '1976-01-16', 'Piešťany', 2, NULL, NULL, NULL),
(30, 'Juraj', 'Minčík', '1977-03-27', 'Spišská Nová Ves', 2, NULL, NULL, NULL),
(31, 'Jozef', 'Krnáč', '1977-12-30', 'Bratislava', 2, NULL, NULL, NULL),
(32, 'Juraj', 'Bača', '1977-03-17', 'Komárno', 2, NULL, NULL, NULL),
(33, 'Michal', 'Riszdorfer', '1977-07-01', 'Bratislava', 2, NULL, NULL, NULL),
(34, 'Richard', 'Riszdorfer', '1981-03-17', 'Komárno', 2, NULL, NULL, NULL),
(35, 'Erik', 'Vlček', '1981-12-29', 'Komárno', 2, NULL, NULL, NULL),
(36, 'Radoslav', 'Židek', '1981-10-15', 'Žilina', 2, NULL, NULL, NULL),
(37, 'Zuzana', 'Štefečeková', '1984-01-15', 'Nitra', 2, NULL, NULL, NULL),
(38, 'Juraj', 'Tarr', '1979-02-18', 'Komárno', 2, NULL, NULL, NULL),
(39, 'David', 'Musuľbes', '1972-07-02', 'Vladi-kaukaz', 20, NULL, NULL, NULL),
(40, 'Anastasiya', 'Kuzmina', '1984-08-28', 'Ťumeň', 14, NULL, NULL, NULL),
(41, 'Pavol', 'Hurajt', '1978-02-04', 'Poprad', 2, NULL, NULL, NULL),
(42, 'Danka', 'Barteková', '1984-10-19', 'Trenčín', 2, NULL, NULL, NULL),
(43, 'Ladislav', 'Škantár', '1983-02-11', 'Kežmarok', 2, NULL, NULL, NULL),
(44, 'Peter', 'Škantár', '1982-07-20', 'Kežmarok', 2, NULL, NULL, NULL),
(45, 'Matej', 'Tóth', '1983-02-10', 'Nitra', 2, NULL, NULL, NULL),
(46, 'Matej', 'Beňuš', '1987-11-02', 'Bratislava', 2, NULL, NULL, NULL),
(47, 'Tibor', 'Linka', '1995-02-13', 'Šamorín', 2, NULL, NULL, NULL),
(48, 'Denis', 'Myšák', '1995-11-30', 'Bojnice', 2, NULL, NULL, NULL),
(49, 'Zuzana', 'Rehák-Štefečeková', '1984-01-15', 'Nitra', 2, NULL, NULL, NULL),
(50, 'Jakub', 'Grigar', '1997-04-27', 'Liptovský Mikuláš', 2, NULL, NULL, NULL),
(51, 'Rory', 'Sabbatini', '1976-04-02', 'Durban', 23, NULL, NULL, NULL),
(52, 'Samuel', 'Baláž', '1998-08-25', 'Bratislava', 2, NULL, NULL, NULL),
(53, 'Adam', 'Botek', '1997-03-05', 'Komárno', 2, NULL, NULL, NULL),
(54, 'Petra', 'Vlhová', '1995-06-13', 'Liptovský Mikuláš', 2, NULL, NULL, NULL),
(55, 'Peter', 'Cehlárik', '1995-08-02', 'Žilina', 2, NULL, NULL, NULL),
(56, 'Michal', 'Čajkovský', '1992-05-06', 'Bratislava', 2, NULL, NULL, NULL),
(57, 'Peter', 'Čerešňák', '1993-01-26', 'Trenčín', 2, NULL, NULL, NULL),
(58, 'Marek', 'Ďaloga', '1989-04-04', 'Zvolen', 2, NULL, NULL, NULL),
(59, 'Marko', 'Daňo', '1994-11-30', 'Eisenstadt', 10, NULL, NULL, NULL),
(60, 'Martin', 'Gernát', '1993-04-11', 'Košice', 2, NULL, NULL, NULL),
(61, 'Adrián', 'Holešinský', '1996-02-11', 'Čadca', 2, NULL, NULL, NULL),
(62, 'Marek', 'Hrivík', '1991-08-28', 'Čadca', 2, NULL, NULL, NULL),
(63, 'Libor', 'Hudáček', '1990-09-07', 'Levoča', 2, NULL, NULL, NULL),
(64, 'Tomáš', 'Jurčo', '1992-12-28', 'Košice', 2, NULL, NULL, NULL),
(65, 'Miloš', 'Kelemen', '1999-07-06', 'Lučenec', 2, NULL, NULL, NULL),
(66, 'Samuel', 'Kňažko', '2002-08-07', 'Trenčín', 2, NULL, NULL, NULL),
(67, 'Branislav', 'Konrád', '1987-10-10', 'Nitra', 2, NULL, NULL, NULL),
(68, 'Michal', 'Krištof', '1993-10-11', 'Nitra', 2, NULL, NULL, NULL),
(69, 'Martin', 'Marinčin', '1992-02-18', 'Košice', 2, NULL, NULL, NULL),
(70, 'Šimon', 'Nemec', '2004-02-15', 'Liptovský Mikuláš', 2, NULL, NULL, NULL),
(71, 'Kristián', 'Pospíšil', '1996-04-22', 'Zvolen', 2, NULL, NULL, NULL),
(72, 'Pavol', 'Regenda', '1999-12-07', 'Michalovce', 2, NULL, NULL, NULL),
(73, 'Miloš', 'Roman', '1999-10-13', 'Kysucké Nové Mesto', 2, NULL, NULL, NULL),
(74, 'Mislav', 'Rosandič', '1995-01-26', 'Záhreb', 24, NULL, NULL, NULL),
(75, 'Patrik', 'Rybár', '1993-11-09', 'Skalica', 2, NULL, NULL, NULL),
(76, 'Juraj', 'Slafkovský', '2004-03-30', 'Košice', 2, NULL, NULL, NULL),
(77, 'Samuel', 'Takáč', '1991-12-03', 'Prievidza', 2, NULL, NULL, NULL),
(78, 'Matej', 'Tomek', '1997-05-24', 'Bratislava', 2, NULL, NULL, NULL),
(79, 'Peter', 'Zuzin', '1990-09-04', 'Zvolen', 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `athlete_medals`
--

CREATE TABLE `athlete_medals` (
  `id` int(11) NOT NULL,
  `athlete_id` int(11) NOT NULL,
  `olympic_games_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL,
  `medal_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `athlete_medals`
--

INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `medal_type_id`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 3, 2, 2),
(3, 3, 4, 3, 2),
(4, 4, 5, 4, 2),
(5, 5, 6, 5, 2),
(6, 6, 7, 6, 3),
(7, 7, 7, 6, 3),
(8, 8, 10, 7, 1),
(9, 9, 10, 7, 1),
(10, 10, 10, 8, 4),
(11, 10, 11, 8, 5),
(12, 10, 12, 8, 2),
(13, 11, 13, 9, 1),
(14, 12, 14, 10, 2),
(15, 13, 15, 6, 2),
(16, 14, 15, 6, 2),
(17, 15, 15, 11, 3),
(18, 16, 16, 7, 3),
(19, 17, 16, 7, 3),
(20, 18, 16, 7, 3),
(21, 19, 16, 7, 3),
(22, 20, 17, 12, 2),
(23, 21, 17, 13, 2),
(24, 22, 17, 14, 1),
(25, 23, 18, 15, 2),
(26, 24, 18, 16, 3),
(27, 25, 18, 17, 1),
(28, 26, 18, 18, 6),
(29, 27, 19, 19, 2),
(30, 28, 19, 19, 2),
(31, 23, 19, 15, 3),
(32, 29, 19, 20, 3),
(33, 29, 19, 21, 3),
(34, 30, 19, 15, 1),
(35, 26, 19, 18, 7),
(36, 27, 20, 19, 2),
(37, 28, 20, 19, 2),
(38, 26, 20, 18, 2),
(39, 31, 20, 22, 3),
(40, 23, 20, 15, 3),
(41, 32, 20, 23, 1),
(42, 25, 20, 24, 1),
(43, 33, 20, 23, 1),
(44, 34, 20, 23, 1),
(45, 35, 20, 23, 1),
(46, 36, 21, 25, 3),
(47, 27, 22, 19, 2),
(48, 28, 22, 19, 2),
(49, 26, 22, 18, 2),
(50, 23, 22, 15, 2),
(51, 34, 22, 23, 3),
(52, 33, 22, 23, 3),
(53, 37, 22, 26, 3),
(54, 38, 22, 23, 3),
(55, 35, 22, 23, 3),
(56, 39, 22, 27, 1),
(57, 40, 23, 28, 2),
(58, 41, 23, 29, 3),
(59, 40, 23, 30, 3),
(60, 41, 23, 31, 1),
(61, 37, 24, 26, 3),
(62, 42, 24, 32, 1),
(63, 27, 24, 19, 1),
(64, 28, 24, 19, 1),
(65, 23, 24, 15, 1),
(66, 40, 25, 28, 2),
(67, 43, 26, 19, 2),
(68, 44, 26, 19, 2),
(69, 45, 26, 33, 2),
(70, 46, 26, 15, 3),
(71, 47, 26, 34, 3),
(72, 48, 26, 34, 3),
(73, 38, 26, 34, 3),
(74, 35, 26, 34, 3),
(75, 40, 27, 31, 2),
(76, 40, 27, 30, 3),
(77, 40, 27, 35, 3),
(78, 49, 28, 26, 2),
(79, 50, 28, 18, 3),
(80, 51, 28, 36, 3),
(81, 52, 28, 23, 1),
(82, 53, 28, 23, 1),
(83, 48, 28, 23, 1),
(84, 35, 28, 23, 1),
(85, 54, 29, 37, 2),
(86, 55, 29, 7, 1),
(87, 56, 29, 7, 1),
(88, 57, 29, 7, 1),
(89, 58, 29, 7, 1),
(90, 59, 29, 7, 1),
(91, 60, 29, 7, 1),
(92, 61, 29, 7, 1),
(93, 62, 29, 7, 1),
(94, 63, 29, 7, 1),
(95, 64, 29, 7, 1),
(96, 65, 29, 7, 1),
(97, 66, 29, 7, 1),
(98, 67, 29, 7, 1),
(99, 68, 29, 7, 1),
(100, 69, 29, 7, 1),
(101, 70, 29, 7, 1),
(102, 71, 29, 7, 1),
(103, 72, 29, 7, 1),
(104, 73, 29, 7, 1),
(105, 74, 29, 7, 1),
(106, 75, 29, 7, 1),
(107, 76, 29, 7, 1),
(108, 77, 29, 7, 1),
(109, 78, 29, 7, 1),
(110, 79, 29, 7, 1),
(111, 46, 30, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`) VALUES
(1, 'Grécko', NULL),
(2, 'Slovensko', NULL),
(3, 'Maďarsko', NULL),
(4, 'Spojené Štáty Americké', 'USA'),
(5, 'Švédsko', 'SWE'),
(6, 'Spojené Kráľovstvo', 'GBR'),
(7, 'Česko', NULL),
(8, 'Fínsko', 'FIN'),
(9, 'Japonsko', 'JPN'),
(10, 'Rakúsko', 'AUT'),
(11, 'Nemecko', NULL),
(12, 'Francúzsko', 'FRA'),
(13, 'Kanada', 'CAN'),
(14, 'Sovietsky zväz', 'SUN'),
(15, 'Juhoslávia', 'YUG'),
(16, 'Južná Kórea', 'KOR'),
(17, 'Austrália', 'AUS'),
(18, 'Taliansko', 'ITA'),
(19, 'Čína', 'CHN'),
(20, 'Rusko', NULL),
(21, 'Brazília', 'BRA'),
(22, 'Kórea', 'PRK'),
(23, 'Južná Afrika', NULL),
(24, 'Chorvátsko', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE `disciplines` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disciplines`
--

INSERT INTO `disciplines` (`id`, `name`) VALUES
(1, 'atletika - beh na 100 m'),
(13, 'atletika - chôdza na 20 km'),
(33, 'atletika - chôdza na 50 km'),
(11, 'atletika - hod diskom'),
(9, 'atletika - skok do diaľky'),
(31, 'biatlon - hromadný štart'),
(29, 'biatlon - šprint'),
(28, 'biatlon - šprint na 7.5 km'),
(30, 'biatlon - stíhacie preteky na 10 km'),
(35, 'biatlon - vytrvalostné preteky na 15 km'),
(5, 'box do 57 kg'),
(4, 'box do 67 kg'),
(10, 'dráhová cyklistika - šprint'),
(6, 'futbal'),
(36, 'golf'),
(22, 'judo - do 66 kg'),
(34, 'kanoistika - K4 na 1000m'),
(8, 'krasokorčuľovanie'),
(7, 'ľadový hokej'),
(20, 'plávanie - 100 m motýlik'),
(21, 'plávanie - 200 m v.sp.'),
(2, 'plávanie - 50 yd v.sp.'),
(16, 'rýchlostná kanoistika - C1 500m'),
(23, 'rýchlostná kanoistika - K4'),
(25, 'snowboarding - snowboardcross'),
(17, 'športová streľba - ľubovoľná malokalibrovka 60'),
(32, 'športová streľba - skeet'),
(26, 'športová streľba - trap'),
(3, 'športová streľba - vojenská puška'),
(24, 'športová streľba - vzduchová puška 10'),
(12, 'tenis - dvojhra'),
(14, 'tenis - štvorhra'),
(15, 'vodný slalom - C1'),
(19, 'vodný slalom - C2'),
(18, 'vodný slalom - K1'),
(27, 'zápasenie - voľný štýl do 120 kg'),
(37, 'zjazdové lyžovanie - slalom');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_type` enum('LOCAL','OAUTH') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `login_type`, `created_at`) VALUES
(1, 1, 'LOCAL', '2026-03-10 16:21:18'),
(2, 2, 'OAUTH', '2026-03-11 10:20:19'),
(3, 4, 'OAUTH', '2026-03-11 10:28:15'),
(4, 2, 'OAUTH', '2026-03-11 10:31:13'),
(5, 2, 'OAUTH', '2026-03-11 12:16:41'),
(6, 2, 'OAUTH', '2026-03-11 12:56:18'),
(7, 4, 'OAUTH', '2026-03-11 13:01:36'),
(8, 2, 'OAUTH', '2026-03-11 13:02:20'),
(9, 2, 'OAUTH', '2026-03-11 13:04:09'),
(10, 2, 'OAUTH', '2026-03-11 13:04:25'),
(11, 2, 'OAUTH', '2026-03-11 13:11:45'),
(12, 2, 'OAUTH', '2026-03-11 13:26:16'),
(13, 4, 'OAUTH', '2026-03-11 13:38:23'),
(14, 4, 'OAUTH', '2026-03-11 13:38:23'),
(15, 4, 'OAUTH', '2026-03-11 13:38:24'),
(16, 4, 'OAUTH', '2026-03-11 14:10:20'),
(17, 4, 'OAUTH', '2026-03-11 14:12:50'),
(18, 4, 'OAUTH', '2026-03-11 14:20:22'),
(19, 4, 'OAUTH', '2026-03-11 14:40:28'),
(20, 4, 'OAUTH', '2026-03-11 14:44:41'),
(21, 4, 'OAUTH', '2026-03-11 15:18:24'),
(22, 4, 'OAUTH', '2026-03-11 15:23:33'),
(23, 4, 'OAUTH', '2026-03-11 15:29:55'),
(24, 4, 'OAUTH', '2026-03-12 12:23:19'),
(25, 4, 'OAUTH', '2026-03-12 12:52:31'),
(26, 4, 'OAUTH', '2026-03-12 13:16:32'),
(27, 4, 'OAUTH', '2026-03-12 13:27:20'),
(28, 4, 'OAUTH', '2026-03-12 13:30:27');

-- --------------------------------------------------------

--
-- Table structure for table `medal_types`
--

CREATE TABLE `medal_types` (
  `id` int(11) NOT NULL,
  `placing` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medal_types`
--

INSERT INTO `medal_types` (`id`, `placing`) VALUES
(2, 1),
(3, 2),
(1, 3),
(7, 4),
(5, 8),
(6, 19),
(4, 22);

-- --------------------------------------------------------

--
-- Table structure for table `olympic_games`
--

CREATE TABLE `olympic_games` (
  `id` int(11) NOT NULL,
  `year` varchar(4) NOT NULL,
  `order_number` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `type` enum('LOH','ZOH') NOT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `olympic_games`
--

INSERT INTO `olympic_games` (`id`, `year`, `order_number`, `city`, `type`, `country_id`) VALUES
(1, '1896', 1, 'Atény', 'LOH', 1),
(3, '1904', 3, 'St. Louis', 'LOH', 4),
(4, '1912', 5, 'Štokholm', 'LOH', 5),
(5, '1948', 14, 'Londýn', 'LOH', 6),
(6, '1952', 15, 'Helsinki', 'LOH', 8),
(7, '1964', 18, 'Tokio', 'LOH', 9),
(10, '1964', 9, 'Innsbruck', 'ZOH', 10),
(11, '1968', 10, 'Grenoble', 'ZOH', 12),
(12, '1972', 11, 'Sapporo', 'ZOH', 9),
(13, '1972', 20, 'Mníchov', 'LOH', 11),
(14, '1976', 21, 'Montreal', 'LOH', 13),
(15, '1980', 22, 'Moskva', 'LOH', 14),
(16, '1984', 14, 'Sarajevo', 'ZOH', 15),
(17, '1988', 24, 'Soul', 'LOH', 16),
(18, '1996', 26, 'Atlanta', 'LOH', 4),
(19, '2000', 27, 'Sydney', 'LOH', 17),
(20, '2004', 28, 'Atény', 'LOH', 1),
(21, '2006', 20, 'Turín', 'ZOH', 18),
(22, '2008', 29, 'Peking/Hongkong', 'LOH', 19),
(23, '2010', 21, 'Vancouver', 'ZOH', 13),
(24, '2012', 30, 'Londýn', 'LOH', 6),
(25, '2014', 22, 'Soči', 'ZOH', 20),
(26, '2016', 31, 'Rio de Janeiro', 'LOH', 21),
(27, '2018', 23, 'Pjongčang', 'ZOH', 22),
(28, '2020', 32, 'Tokio', 'LOH', 9),
(29, '2022', 24, 'Peking', 'ZOH', 19),
(30, '2024', 33, 'Paríž', 'LOH', 12);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` int(11) NOT NULL,
  `first_name` varchar(64) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tfa_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `created_at`, `tfa_secret`) VALUES
(1, 'Yeliza', 'Sioma', 'liz@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UW9LUnJhOEFWam9HYUI5Nw$2rNrmJhs/hu/vBpicEV7/EHRWDZJa/nx2Q0LJpuZ/X4', '2026-03-10 16:21:10', ''),
(2, 'Yelizaveta', 'Siomchanka', 'xsiomchanka@stuba.sk', NULL, '2026-03-11 10:20:19', NULL),
(3, 'halo', 'pepe', 'fgjhgfdjh@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$S0VuRGZMWVFxNGI4MGthWg$RH/ebw7/WgCl8+jUJuQ60aWDsu6WEFPxlbw/SEvFg20', '2026-03-11 10:22:30', 'GYY64B4V4R4NLRS5ORA7FYK3VHB26EAT'),
(4, 'Yelizaveta', 'sIOMA', 'yelizavetasioma@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SVNMWnBUazR3Q0F2cVlkUw$smT5qr+9Rojlv68/fNIFNfXASG0u9NrlBK0CFWX/Cy0', '2026-03-11 10:28:15', NULL),
(5, 'fhg', 'hggf', 'dh@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eWkvYkk0UUs4TzkybkNiRw$ZEJKPE9p6OnAKaBkpDzUZfFT3xjAGA6I09jAB0iIdHs', '2026-03-11 10:35:03', 'KV62JK4W2W5I2PWLBQ3FLALGPTXWSM3X'),
(6, 'blabla', 'bubu', 'blaBLAbubu@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SW1lNGZKYUV3YVpBNTUzaw$WNSNwAlzH+26gMw76lpqwsaHzGMY+oQDcSf5VoLBrpw', '2026-03-11 15:19:23', 'B7EBRFAFTNY5RT54EZZ2UFOCZQQIFXKD'),
(7, 'baba', 'aba', 'jhsdhj32@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$aE9DSDV5VHFaYlg2NUtYcw$ONAsEmDKFi2agskx0teK8MWMpqhv1k/C5vqAyfzl+eA', '2026-03-11 15:23:08', 'JTEDB2L5VCW7ZP3626NYGHIGDPXYQ23I'),
(8, 'fdsgd', 'dhdfh', 'dfsgf@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$OFYuNE93d0poUW1jcFFjSw$JcgnRh/vQ6oVs0zb/C0Nub3mx35it88EF4fe75u3lDo', '2026-03-12 12:52:10', 'L3QSOKWCTNANAXCC6T4WGKUOSZETFL3I'),
(9, 'fghgfh', 'fghgf', 'fdghf@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WmNHZUJBWC54MVB2c3d1eg$uGbEmhagOsnKkYhEEsU+jEN/eXauDm+HVnIcQImwEvQ', '2026-03-12 12:52:59', 'NHFCEQHASWIJ23DNHAJOTNSVMDA46XKE'),
(10, 'dfghd', 'dhd', 'dgfhd@gmaali.cvom', '$argon2id$v=19$m=65536,t=4,p=1$b3Y3VE9ic2tXMkhyNEhFOQ$qVzjzDbpJsDAWaJTSYDJHsJGAKi3ail1EqhXIJEB2kU', '2026-03-12 12:59:21', '67LA6PG5PMSB3UPFSJ2TJP72SI2GEK5Z'),
(11, 'dhfd', 'dhfgd', 'dhdhfg@maadg.ccvom', '$argon2id$v=19$m=65536,t=4,p=1$Yk04V2xGdDBjT2lOSzRxLg$bVeQ9lDcVOfu5pvz5eljKokcUIylYUU377PdQ82sEZI', '2026-03-12 13:00:22', 'LSSUVDY7Q7PLOMS4H35VF4ZJ5PUO426Z'),
(12, 'fsddfh', 'dhdfh', 'dfgh@fdh.cvkg', '$argon2id$v=19$m=65536,t=4,p=1$SnFmbThNZUJRUGJBN0xHMA$UCgLLb7KDiGWFbEtVV4nPmnadLfOtdB4bdWTfz7HbXQ', '2026-03-12 13:04:47', 'QQIFUAW3Q2JFOD5EK2FFHBPHEYPCGMHB'),
(13, 'df', 'dfh', 'dhfdhf@dfgh.bnm', '$argon2id$v=19$m=65536,t=4,p=1$VENXRUt6bjZOajkxeFFZbw$6g4NWadOhmxadyxbiduyw6c2xaiz8fbTunsCQiEsppI', '2026-03-12 13:06:07', 'PQMN7CFJ2BD6NY6YW66UZFJPJXIKO3XY');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `athletes`
--
ALTER TABLE `athletes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `death_country_id` (`death_country_id`),
  ADD KEY `birth_country_id` (`birth_country_id`) USING BTREE;

--
-- Indexes for table `athlete_medals`
--
ALTER TABLE `athlete_medals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `athlete_id` (`athlete_id`,`olympic_games_id`,`discipline_id`),
  ADD KEY `olympic_games_id` (`olympic_games_id`),
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `medal_type_id` (`medal_type_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medal_types`
--
ALTER TABLE `medal_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placing` (`placing`);

--
-- Indexes for table `olympic_games`
--
ALTER TABLE `olympic_games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year_type` (`year`,`type`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `athletes`
--
ALTER TABLE `athletes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `athlete_medals`
--
ALTER TABLE `athlete_medals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `medal_types`
--
ALTER TABLE `medal_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `olympic_games`
--
ALTER TABLE `olympic_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `athletes`
--
ALTER TABLE `athletes`
  ADD CONSTRAINT `athletes_ibfk_1` FOREIGN KEY (`birth_country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `athletes_ibfk_2` FOREIGN KEY (`death_country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `athlete_medals`
--
ALTER TABLE `athlete_medals`
  ADD CONSTRAINT `athlete_medals_ibfk_1` FOREIGN KEY (`athlete_id`) REFERENCES `athletes` (`id`),
  ADD CONSTRAINT `athlete_medals_ibfk_2` FOREIGN KEY (`olympic_games_id`) REFERENCES `olympic_games` (`id`),
  ADD CONSTRAINT `athlete_medals_ibfk_3` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`),
  ADD CONSTRAINT `athlete_medals_ibfk_4` FOREIGN KEY (`medal_type_id`) REFERENCES `medal_types` (`id`);

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `fk_login_history_user` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `olympic_games`
--
ALTER TABLE `olympic_games`
  ADD CONSTRAINT `olympic_games_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
