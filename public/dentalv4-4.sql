-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 13, 2025 at 12:36 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dentalv4`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_service`
--

CREATE TABLE `appointment_service` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `day_of_week` varchar(20) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text,
  `contact_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `contact_number`) VALUES
(1, 'Perfect Smile - Main Branch', '123 Main Street, Downtown, City Center', '+1 (555) 123-4567'),
(2, 'Perfect Smile - North Branch', '456 North Avenue, North District', '+1 (555) 234-5678'),
(3, 'Perfect Smile - South Branch', '789 South Boulevard, South District', '+1 (555) 345-6789'),
(4, 'Perfect Smile - East Branch', '321 East Road, East District', '+1 (555) 456-7890'),
(5, 'Perfect Smile - West Branch', '654 West Street, West District', '+1 (555) 567-8901');

-- --------------------------------------------------------

--
-- Table structure for table `branch_user`
--

CREATE TABLE `branch_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dental_record`
--

CREATE TABLE `dental_record` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `record_date` date DEFAULT NULL,
  `diagnosis` text,
  `treatment` text,
  `notes` text,
  `xray_image_url` varchar(255) DEFAULT NULL,
  `next_appointment_date` date DEFAULT NULL,
  `dentist_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `procedure_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-07-08-111501', 'App\\Database\\Migrations\\AddPatientFieldsToUsersTable', 'default', 'App', 1751973347, 1),
(2, '2025-07-08-162713', 'App\\Database\\Migrations\\AddStatusToUserTable', 'default', 'App', 1751992066, 2),
(3, '2025-07-11-195213', 'App\\Database\\Migrations\\RemoveSeparateNameFields', 'default', 'App', 1752263588, 3);

-- --------------------------------------------------------

--
-- Table structure for table `patient_guardian`
--

CREATE TABLE `patient_guardian` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `dentist_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `issue_date` date NOT NULL,
  `notes` text,
  `signature_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `instructions` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `procedures`
--

CREATE TABLE `procedures` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `procedure_name` varchar(255) DEFAULT NULL,
  `description` text,
  `procedure_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `procedure_service`
--

CREATE TABLE `procedure_service` (
  `id` int(11) NOT NULL,
  `procedure_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`) VALUES
(1, 'Dental Checkup', 'Comprehensive dental examination and cleaning', '75.00'),
(2, 'Teeth Whitening', 'Professional teeth whitening treatment', '150.00'),
(3, 'Cavity Filling', 'Dental filling for cavities', '120.00'),
(4, 'Root Canal', 'Root canal treatment', '800.00'),
(5, 'Dental Crown', 'Dental crown placement', '600.00'),
(6, 'Tooth Extraction', 'Simple tooth extraction', '200.00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_type`, `name`, `address`, `email`, `dob`, `gender`, `password`, `phone`, `created_at`, `updated_at`, `occupation`, `nationality`, `date_of_birth`, `age`, `status`) VALUES
(1, 'admin', 'Admin User', '123 Admin Street', 'admin@perfectsmile.com', '1990-01-01', 'male', '$2y$12$Tau0ciyH4Ny3A/P0I.qEbO5i63Ja4AtTPBd4OblwwAnoQLCYokErS', '1234567890', '2025-06-28 16:02:14', '2025-06-28 16:02:14', NULL, NULL, NULL, NULL, 'active'),
(2, 'doctor', 'Dr. John Smith', '456 Doctor Avenue', 'doctor@perfectsmile.com', '1985-05-15', 'male', '$2y$12$yW6MCZ6JB37X5t6.E8Rv8u/gBlbviukPlLEqwPJKCM8.IBGqk0Yo2', '1234567891', '2025-06-28 16:02:14', '2025-06-28 16:02:14', NULL, NULL, NULL, NULL, 'active'),
(3, 'patient', 'Patient Jane Doe', '789 Patient Road', 'patient@perfectsmile.com', '1995-10-20', 'Female', '$2y$12$ZxKlA7jaipJ2XAgP8QD.purDu.L8qfMq./pqj3yqGSYOw.bf0Z5MW', '1234567892', '2025-06-28 16:02:14', '2025-07-13 11:58:10', 'hh', 'oo', '2025-07-08', 9, 'inactive'),
(4, 'staff', 'Staff Member', '321 Staff Lane', 'staff@perfectsmile.com', '1992-03-12', 'female', '$2y$12$5ngcd.kPZCtoUX.2U/QvTO4mun3W35rBPGTM6bbdc16pKEnkPbj.2', '1234567893', '2025-06-28 16:02:14', '2025-06-28 16:02:14', NULL, NULL, NULL, NULL, 'active'),
(5, 'patient', 'Brandon Brandon Brandon', 'Brandon@gmail.com', 'Brandon@gmail.com', NULL, 'Male', '$2y$12$3225/eB6Cz2MGgN3eHsp6.26RK/q0nmDVEvBkvGubuBiyWpCNG3Sm', '89078007077', '2025-07-08 13:39:16', '2025-07-10 10:45:58', 'Brandon', 'Brandon@gmail.com', '2025-07-03', 18, 'active'),
(6, 'patient', 'branond r caritos', 'ewan', 'don@gmail.com', NULL, 'Other', NULL, '23423490324', '2025-07-11 19:40:55', '2025-07-11 19:40:55', 'student', 'ph', '2025-07-09', 99, 'active'),
(7, 'patient', 'ccccc', 'brand', 'don@gmail.com', NULL, 'Female', NULL, '8987798', '2025-07-11 22:29:13', '2025-07-13 11:58:21', 'on', 'din', '2025-07-09', 9, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `appointment_service`
--
ALTER TABLE `appointment_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_user`
--
ALTER TABLE `branch_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `dental_record`
--
ALTER TABLE `dental_record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dentist_id` (`dentist_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procedure_id` (`procedure_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_guardian`
--
ALTER TABLE `patient_guardian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dentist_id` (`dentist_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_id` (`prescription_id`);

--
-- Indexes for table `procedures`
--
ALTER TABLE `procedures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `procedure_service`
--
ALTER TABLE `procedure_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procedure_id` (`procedure_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment_service`
--
ALTER TABLE `appointment_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `branch_user`
--
ALTER TABLE `branch_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dental_record`
--
ALTER TABLE `dental_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_guardian`
--
ALTER TABLE `patient_guardian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procedures`
--
ALTER TABLE `procedures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procedure_service`
--
ALTER TABLE `procedure_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `appointment_service`
--
ALTER TABLE `appointment_service`
  ADD CONSTRAINT `appointment_service_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `appointment_service_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `availability_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `branch_user`
--
ALTER TABLE `branch_user`
  ADD CONSTRAINT `branch_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `branch_user_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `dental_record`
--
ALTER TABLE `dental_record`
  ADD CONSTRAINT `dental_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `dental_record_ibfk_2` FOREIGN KEY (`dentist_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`);

--
-- Constraints for table `patient_guardian`
--
ALTER TABLE `patient_guardian`
  ADD CONSTRAINT `patient_guardian_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`dentist_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `prescriptions_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`);

--
-- Constraints for table `procedures`
--
ALTER TABLE `procedures`
  ADD CONSTRAINT `procedures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `procedure_service`
--
ALTER TABLE `procedure_service`
  ADD CONSTRAINT `procedure_service_ibfk_1` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`),
  ADD CONSTRAINT `procedure_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
