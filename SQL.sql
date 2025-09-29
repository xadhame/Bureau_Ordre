-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql112.byetcluster.com
-- Generation Time: Sep 06, 2025 at 08:17 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39141472_BureauAdmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `administration`
--

CREATE TABLE `administration` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administration`
--

INSERT INTO `administration` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `destinataires`
--

CREATE TABLE `destinataires` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinataires`
--

INSERT INTO `destinataires` (`id`, `nom`) VALUES
(1, 'Bureau_information_documentation'),
(2, 'Bureau_ordre'),
(3, 'Cellule_ouverture_interne'),
(4, 'President_du_Conseil_collectif'),
(5, 'Reception_bureau_orientation'),
(6, 'Redaction_prive'),
(7, 'Unite_gestion_plaintes');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `num_sequence` varchar(50) NOT NULL,
  `date_saisir` date NOT NULL,
  `objet_saisir` text NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `envoyeur` varchar(100) NOT NULL,
  `lu` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_bureau`
--

CREATE TABLE `users_bureau` (
  `id` int(11) NOT NULL,
  `nom_bureau` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_bureau`
--

INSERT INTO `users_bureau` (`id`, `nom_bureau`, `mot_de_passe`) VALUES
(1, 'Bureau_information_documentation', 'Bureau_information_documentation'),
(2, 'Bureau_ordre', 'Bureau_ordre'),
(3, 'Cellule_ouverture_interne', 'Cellule_ouverture_interne'),
(4, 'President_du_Conseil_collectif', 'President_du_Conseil_collectif'),
(5, 'Reception_bureau_orientation', 'Reception_bureau_orientation'),
(6, 'Redaction_prive', 'Redaction_prive'),
(7, 'Unite_gestion_plaintes', 'Unite_gestion_plaintes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administration`
--
ALTER TABLE `administration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinataires`
--
ALTER TABLE `destinataires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destinataire_id` (`destinataire_id`);

--
-- Indexes for table `users_bureau`
--
ALTER TABLE `users_bureau`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom_bureau` (`nom_bureau`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administration`
--
ALTER TABLE `administration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `destinataires`
--
ALTER TABLE `destinataires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users_bureau`
--
ALTER TABLE `users_bureau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`destinataire_id`) REFERENCES `destinataires` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
