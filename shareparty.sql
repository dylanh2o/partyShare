-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 02 déc. 2019 à 20:11
-- Version du serveur :  10.4.6-MariaDB
-- Version de PHP :  7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `shareparty`
--
CREATE DATABASE IF NOT EXISTS `shareparty` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shareparty`;

-- --------------------------------------------------------

--
-- Structure de la table `changemotdepasse`
--

CREATE TABLE `changemotdepasse` (
  `IdToken` int(11) NOT NULL,
  `Token` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  `Utilisateurs_idUtilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONS POUR LA TABLE `changemotdepasse`:
--   `Utilisateurs_idUtilisateur`
--       `utilisateurs` -> `IdUtilisateur`
--

-- --------------------------------------------------------

--
-- Structure de la table `confirmationemail`
--

CREATE TABLE `confirmationemail` (
  `IdToken` int(11) NOT NULL,
  `Token` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  `Utilisateurs_idUtilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONS POUR LA TABLE `confirmationemail`:
--   `Utilisateurs_idUtilisateur`
--       `utilisateurs` -> `IdUtilisateur`
--

--
-- Déchargement des données de la table `confirmationemail`
--

INSERT INTO `confirmationemail` (`IdToken`, `Token`, `Date`, `Utilisateurs_idUtilisateur`) VALUES
(1, '193e6378f36b32570e78359556a8b64a', '2019-12-02 19:25:47', 5);

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `IdEvenements` int(11) NOT NULL,
  `Titre` varchar(45) NOT NULL,
  `Description` text NOT NULL,
  `Adresse` varchar(100) NOT NULL,
  `CodePostal` int(5) NOT NULL,
  `Ville` varchar(45) NOT NULL,
  `Date` date NOT NULL,
  `HeureDebut` time NOT NULL,
  `HeureFin` time NOT NULL,
  `Type` enum('Public','Privé') NOT NULL,
  `DateCreation` date NOT NULL,
  `Utilisateurs_idUtilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS POUR LA TABLE `evenements`:
--   `Utilisateurs_idUtilisateur`
--       `utilisateurs` -> `IdUtilisateur`
--

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`IdEvenements`, `Titre`, `Description`, `Adresse`, `CodePostal`, `Ville`, `Date`, `HeureDebut`, `HeureFin`, `Type`, `DateCreation`, `Utilisateurs_idUtilisateur`) VALUES
(1, 'Soirée dans mon appartement !', 'Viens avec tes potes boire et faire la fête dans une ambiance hip-hop avec Dj Noise\r\nAmène ta conso mais il y aura de l\'eau fraîche à gogo et des glaçon.', '13 route de peney', 1214, 'Vernier', '2019-12-21', '22:00:00', '04:00:00', 'Public', '2019-12-02', 5),
(2, 'Art & création', 'Venez découvrir ou participer à un atelier créatif sur le patchwork, suivis d\'un apéro.\r\nAnimateur: Frederico di coco\r\nPrix: Gratuit', '28 avenue margheriti', 1220, 'Genève', '2020-02-10', '17:00:00', '19:00:00', 'Public', '2019-12-02', 1),
(3, 'Cave de Nyon', 'Viens goûter à nos vins du terroir et passer du bon temps. Si tu n\'aimes pas le vin(TU N\'AIMES PAS LE VIN??!!!) nous avons aussi de la bonne raclette, fondu ou autre plats délicieux.\r\nSurtout oublie pas ta bonne humeur', '16 rue de la Morâche', 1260, 'Nyon', '2019-12-24', '08:00:00', '00:00:00', 'Public', '2019-12-02', 1);

-- --------------------------------------------------------

--
-- Structure de la table `participer`
--

CREATE TABLE `participer` (
  `Utilisateurs_idUtilisateur` int(11) NOT NULL,
  `Evenements_idEvenements` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONS POUR LA TABLE `participer`:
--   `Evenements_idEvenements`
--       `evenements` -> `IdEvenements`
--   `Utilisateurs_idUtilisateur`
--       `utilisateurs` -> `IdUtilisateur`
--

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IdUtilisateur` int(11) NOT NULL,
  `Nom` varchar(25) NOT NULL,
  `Prenom` varchar(25) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Pays` varchar(200) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `DateNaissance` date NOT NULL,
  `Statut` enum('Utilisateur','Administrateur') NOT NULL,
  `DateCreation` date NOT NULL,
  `Active` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS POUR LA TABLE `utilisateurs`:
--

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdUtilisateur`, `Nom`, `Prenom`, `Email`, `Pays`, `MotDePasse`, `DateNaissance`, `Statut`, `DateCreation`, `Active`) VALUES
(1, 'Carluccio', 'Dylan', 'dylan.carlu@gmail.com', 'Suisse', '$2y$10$ocmgiRWunqiep24.NYqbtuX21AWPrgiP.9TfGECUHg6Z11QePlP5S', '2000-12-12', 'Utilisateur', '2019-10-22', '1'),
(2, 'Rodrigues', 'Ivane', 'Ivane@gmail.com', 'Suisse', '$2y$10$ute.0qkUMN3LdAwOrR9.CuKdkXVv1lHeRQ5JWwBFXGQ32RwEWTLLW', '1212-12-12', 'Utilisateur', '2019-10-22', '0'),
(3, 'mutlu', 'hanefi', 'hanefi@gmail.com', 'Russie', '$2y$10$rTtzA5uUUl8TYkcFtDUU6Ow7PmrKIkrTVqmvscsvcW9P32NzXHfGi', '1212-12-12', 'Utilisateur', '2019-10-23', '0'),
(4, 'Sanchez', 'Jean-Claude', 'jc@gmail.com', 'Suisse', '$2y$10$wg4IKLzqdcjSbQtDXecomOUoqizW7gZKK7GmJDchZ.OxnJaKGtQUq', '2000-12-12', 'Utilisateur', '2019-10-23', '0'),
(5, 'Santamaria', 'Kevin', 'kevinSantamaria@gmail.com', 'Bahamas', '$2y$10$mrCTWv/DoR04ZqwKv5powO9dUpN.h95z4Af9Ckx6D.Owz5wbNIo1W', '1996-10-14', 'Utilisateur', '2019-12-02', '1');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `changemotdepasse`
--
ALTER TABLE `changemotdepasse`
  ADD PRIMARY KEY (`IdToken`),
  ADD KEY `Utilisateur_idUtilisateur` (`Utilisateurs_idUtilisateur`);

--
-- Index pour la table `confirmationemail`
--
ALTER TABLE `confirmationemail`
  ADD PRIMARY KEY (`IdToken`),
  ADD KEY `Utilisateurs_idUtilisateur` (`Utilisateurs_idUtilisateur`);

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`IdEvenements`,`Utilisateurs_idUtilisateur`),
  ADD UNIQUE KEY `idEvenements_UNIQUE` (`IdEvenements`),
  ADD KEY `fk_Evenements_Utilisateurs_idx` (`Utilisateurs_idUtilisateur`);

--
-- Index pour la table `participer`
--
ALTER TABLE `participer`
  ADD PRIMARY KEY (`Utilisateurs_idUtilisateur`,`Evenements_idEvenements`),
  ADD KEY `fk_Participer_Utilisateurs1_idx` (`Utilisateurs_idUtilisateur`),
  ADD KEY `fk_Participer_Evenements1_idx` (`Evenements_idEvenements`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IdUtilisateur`),
  ADD UNIQUE KEY `idUtilisateurs_UNIQUE` (`IdUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `changemotdepasse`
--
ALTER TABLE `changemotdepasse`
  MODIFY `IdToken` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `confirmationemail`
--
ALTER TABLE `confirmationemail`
  MODIFY `IdToken` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `IdEvenements` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `IdUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `changemotdepasse`
--
ALTER TABLE `changemotdepasse`
  ADD CONSTRAINT `changemotdepasse_ibfk_1` FOREIGN KEY (`Utilisateurs_idUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `confirmationemail`
--
ALTER TABLE `confirmationemail`
  ADD CONSTRAINT `confirmationemail_ibfk_1` FOREIGN KEY (`Utilisateurs_idUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `fk_Evenements_Utilisateurs` FOREIGN KEY (`Utilisateurs_idUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `participer`
--
ALTER TABLE `participer`
  ADD CONSTRAINT `fk_Participer_Evenements1` FOREIGN KEY (`Evenements_idEvenements`) REFERENCES `evenements` (`IdEvenements`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Participer_Utilisateurs1` FOREIGN KEY (`Utilisateurs_idUtilisateur`) REFERENCES `utilisateurs` (`IdUtilisateur`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
