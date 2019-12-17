-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 16 déc. 2019 à 12:23
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `soupe`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `ID_COMMANDE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_RECETTE` int(11) NOT NULL,
  `DATE` date DEFAULT NULL,
  PRIMARY KEY (`ID_COMMANDE`),
  KEY `FK_COMMANDE_GENERE_RECETTE` (`ID_RECETTE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `compose`
--

DROP TABLE IF EXISTS `compose`;
CREATE TABLE IF NOT EXISTS `compose` (
  `ID_INGREDIENT` int(11) NOT NULL,
  `ID_RECETTE` int(11) NOT NULL,
  `ID_UNITE` int(11) NOT NULL,
  `QUANTITE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_INGREDIENT`,`ID_RECETTE`,`ID_UNITE`),
  KEY `FK_COMPOSE_COMPOSE2_RECETTE` (`ID_RECETTE`),
  KEY `FK_COMPOSE_COMPOSE3_UNITE` (`ID_UNITE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `compose`
--

INSERT INTO `compose` (`ID_INGREDIENT`, `ID_RECETTE`, `ID_UNITE`, `QUANTITE`) VALUES
(1, 1, 1, 2),
(1, 4, 1, 2),
(2, 1, 1, 1),
(3, 4, 1, 6),
(5, 1, 2, 500),
(6, 1, 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE IF NOT EXISTS `ingredient` (
  `ID_INGREDIENT` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_INGREDIENT` varchar(50) DEFAULT NULL,
  `PRIX_RUNGIS` float DEFAULT NULL,
  `PRIX_LEADER` float DEFAULT NULL,
  `VALEURCAL` float DEFAULT NULL,
  `LIPIDE` float DEFAULT NULL,
  `GLUCIDE` float DEFAULT NULL,
  `PROTEINE` float DEFAULT NULL,
  PRIMARY KEY (`ID_INGREDIENT`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ingredient`
--

INSERT INTO `ingredient` (`ID_INGREDIENT`, `NOM_INGREDIENT`, `PRIX_RUNGIS`, `PRIX_LEADER`, `VALEURCAL`, `LIPIDE`, `GLUCIDE`, `PROTEINE`) VALUES
(1, 'carotte', 1.75, 1.09, 36.4, 0.26, 6.45, 0.77),
(2, 'courgette', 2.95, 2.49, 17, 0.32, 3.11, 1.21),
(3, 'ail', 2.3, 1.99, 149, 0.5, 33.06, 6.36),
(4, 'echalote', 2.2, 1.49, 72, 0.1, 16.8, 2.5),
(5, 'poireau', 1.9, 2.69, 61, 0.3, 14.15, 1.5),
(6, 'aubergine', 3.99, 1.99, 35, 0.23, 8.73, 0.83),
(7, 'citron jaune', 2.64, 2.49, 29, 0.3, 9.32, 1.1),
(8, 'pomme de terre', 4.15, 3.59, 86, 0.1, 20.01, 1.71),
(9, 'tomate grappe', 2.15, 1.29, 23, 0.2, 5.1, 1.2);

-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

DROP TABLE IF EXISTS `recette`;
CREATE TABLE IF NOT EXISTS `recette` (
  `ID_RECETTE` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_RECETTE` varchar(100) DEFAULT NULL,
  `PRIX` float DEFAULT NULL,
  `VALEURCALR` float DEFAULT NULL,
  `LIPIDER` float DEFAULT NULL,
  `GLUCIDER` float DEFAULT NULL,
  `PROTEINER` float DEFAULT NULL,
  `PRIXR_LEADER` float DEFAULT NULL,
  `PRIXR_RUNGIS` float DEFAULT NULL,
  PRIMARY KEY (`ID_RECETTE`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `recette`
--

INSERT INTO `recette` (`ID_RECETTE`, `NOM_RECETTE`, `PRIX`, `VALEURCALR`, `LIPIDER`, `GLUCIDER`, `PROTEINER`, `PRIXR_LEADER`, `PRIXR_RUNGIS`) VALUES
(1, 'Soupe Hiver', 48, 1098, 209, 30, 187, NULL, NULL),
(4, 'soupe de merde', NULL, 9668, 35.2, 2112.6, 397, 14.12, 17.3);

-- --------------------------------------------------------

--
-- Structure de la table `unite`
--

DROP TABLE IF EXISTS `unite`;
CREATE TABLE IF NOT EXISTS `unite` (
  `ID_UNITE` int(11) NOT NULL AUTO_INCREMENT,
  `UNITE_MASSE` varchar(10) DEFAULT NULL,
  `UNITE_MONNAIE` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID_UNITE`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `unite`
--

INSERT INTO `unite` (`ID_UNITE`, `UNITE_MASSE`, `UNITE_MONNAIE`) VALUES
(1, 'kg', ''),
(2, 'g', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID_USERS` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(100) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_USERS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_COMMANDE_GENERE_RECETTE` FOREIGN KEY (`ID_RECETTE`) REFERENCES `recette` (`ID_RECETTE`);

--
-- Contraintes pour la table `compose`
--
ALTER TABLE `compose`
  ADD CONSTRAINT `FK_COMPOSE_COMPOSE2_RECETTE` FOREIGN KEY (`ID_RECETTE`) REFERENCES `recette` (`ID_RECETTE`),
  ADD CONSTRAINT `FK_COMPOSE_COMPOSE3_UNITE` FOREIGN KEY (`ID_UNITE`) REFERENCES `unite` (`ID_UNITE`),
  ADD CONSTRAINT `FK_COMPOSE_COMPOSE_INGREDIE` FOREIGN KEY (`ID_INGREDIENT`) REFERENCES `ingredient` (`ID_INGREDIENT`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
