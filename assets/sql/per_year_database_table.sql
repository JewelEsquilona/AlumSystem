-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 03:20 AM
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
-- Database: `per year database table`
--

-- --------------------------------------------------------

--
-- Table structure for table `2024-2025`
--

CREATE TABLE `2024-2025` (
  `ID` int(11) NOT NULL,
  `Alumni_ID_Number` int(11) NOT NULL,
  `Student_Number` varchar(20) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `College` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `Section` varchar(50) NOT NULL,
  `Year_Graduated` year(4) NOT NULL,
  `Contact_Number` varchar(15) NOT NULL,
  `Personal_Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `2024-2025`
--
DELIMITER $$
CREATE TRIGGER `before_insert_2024_2025` BEFORE INSERT ON `2024-2025` FOR EACH ROW BEGIN
    IF NEW.ID IS NULL OR NEW.ID = 0 THEN
        SET NEW.ID = (SELECT IFNULL(MAX(ID), 0) + 1 FROM `2024-2025`);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_alumni_id` BEFORE INSERT ON `2024-2025` FOR EACH ROW BEGIN
    DECLARE new_alumni_id INT;
    SELECT COALESCE(MAX(Alumni_ID_Number), 0) + 1 INTO new_alumni_id FROM `2024-2025`;
    SET NEW.Alumni_ID_Number = new_alumni_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `2024-2025_ed`
--

CREATE TABLE `2024-2025_ed` (
  `ID` int(11) NOT NULL,
  `Alumni_ID_Number` int(11) NOT NULL,
  `Employment` varchar(50) DEFAULT NULL,
  `Employment_Status` varchar(50) DEFAULT NULL,
  `Present_Occupation` varchar(100) DEFAULT NULL,
  `Name_of_Employer` varchar(100) DEFAULT NULL,
  `Address_of_Employer` varchar(255) DEFAULT NULL,
  `Number_of_Years_in_Present_Employer` int(11) DEFAULT NULL,
  `Type_of_Employer` varchar(100) DEFAULT NULL,
  `Major_Line_of_Business` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `2024-2025_ed`
--
DELIMITER $$
CREATE TRIGGER `before_insert_2024_2025_ed` BEFORE INSERT ON `2024-2025_ed` FOR EACH ROW BEGIN
    DECLARE duplicate_count INT;
    SELECT COUNT(*) INTO duplicate_count
    FROM `2024-2025_ed`
    WHERE Alumni_ID_Number = NEW.Alumni_ID_Number;

    IF duplicate_count > 0 THEN
        UPDATE `2024-2025_ed`
        SET created_at = NOW()  
        WHERE Alumni_ID_Number = NEW.Alumni_ID_Number
        AND Alumni_ID_Number < NEW.Alumni_ID_Number;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Prix` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments_list`
--

CREATE TABLE `payments_list` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `PaymentSchedule` varchar(255) NOT NULL,
  `BillNumber` varchar(255) NOT NULL,
  `AmountPaid` varchar(255) NOT NULL,
  `BalanceAmount` varchar(255) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_list`
--

CREATE TABLE `students_list` (
  `Id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `EnrollNumber` varchar(255) NOT NULL,
  `DateOfAdmission` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_list`
--

INSERT INTO `students_list` (`Id`, `img`, `Name`, `Email`, `Phone`, `EnrollNumber`, `DateOfAdmission`) VALUES
(2, 'Adobe_XD_CC_icon.svg.png', 'ss', 'ssss', 'ss', 'ss', '2022-11-18'),
(3, 'Angular_full_color_logo.svg.png', 'ss', 'ss', 'ss', 'ss', '2022-11-11'),
(4, 'Python-logo-notext.svg.png', 'ss', 'ss', 's', 's', '2022-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `img`, `username`, `Email`, `Password`) VALUES
(1, '', '', '', '', 'sabir', 'sabir@gmail.com', 'Sabir123'),
(2, '', '', '', '', 'hey', 'hey@gmail.com', '$2y$10$jSoi2HVgLt/7XF5AD2F8BOSW31.PFn.tIYdsonZfv7X6o8AVeP8JW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `2024-2025`
--
ALTER TABLE `2024-2025`
  ADD PRIMARY KEY (`Alumni_ID_Number`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `2024-2025_ed`
--
ALTER TABLE `2024-2025_ed`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_alumni_id` (`Alumni_ID_Number`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments_list`
--
ALTER TABLE `payments_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_list`
--
ALTER TABLE `students_list`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `2024-2025`
--
ALTER TABLE `2024-2025`
  MODIFY `Alumni_ID_Number` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `2024-2025_ed`
--
ALTER TABLE `2024-2025_ed`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments_list`
--
ALTER TABLE `payments_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students_list`
--
ALTER TABLE `students_list`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `2024-2025_ed`
--
ALTER TABLE `2024-2025_ed`
  ADD CONSTRAINT `fk_alumni_id` FOREIGN KEY (`Alumni_ID_Number`) REFERENCES `2024-2025` (`Alumni_ID_Number`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
