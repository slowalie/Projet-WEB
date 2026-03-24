-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 24 mars 2026 à 10:16
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ideastage_BDD`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Ajouter_favoris`
--

CREATE TABLE `Ajouter_favoris` (
  `id_offres` int NOT NULL,
  `id_user` int NOT NULL,
  `Etat` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Candidats`
--

CREATE TABLE `Candidats` (
  `id_user` int NOT NULL,
  `titre_profil` varchar(100) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `add_doc` varchar(255) DEFAULT NULL,
  `disponibilite` varchar(50) DEFAULT NULL,
  `id_user_pilote` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Competences`
--

CREATE TABLE `Competences` (
  `id_competences` int NOT NULL,
  `nom_competences` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Demander`
--

CREATE TABLE `Demander` (
  `id_offres` int NOT NULL,
  `id_competences` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Entreprises`
--

CREATE TABLE `Entreprises` (
  `id_entreprise` int NOT NULL,
  `nom_entreprise` varchar(100) NOT NULL,
  `logo_entreprise` varchar(255) DEFAULT NULL,
  `description_entreprise` text,
  `note_entreprise` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Entreprises`
--

INSERT INTO `Entreprises` (`id_entreprise`, `nom_entreprise`, `logo_entreprise`, `description_entreprise`, `note_entreprise`) VALUES
(1, 'TechVision', 'TV', 'Leader en solutions Full-Stack', 5),
(2, 'DesignStudio', 'DS', 'Agence créative UX/UI', 5),
(3, 'DataCorp', 'DC', 'Expertise Big Data et Analytics', 5),
(4, 'GrowthHive', 'GH', 'Accélérateur de marketing digital', 4),
(5, 'SecureNet', 'SN', 'Spécialiste en cybersécurité', 5),
(6, 'FinGroup', 'FG', 'Cabinet de conseil financier', 4),
(7, 'zeubh', '', '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `Langues`
--

CREATE TABLE `Langues` (
  `id_langues` int NOT NULL,
  `nom_langues` varchar(50) NOT NULL,
  `niveau_langues` enum('A1','A2','B1','B2','C1','C2','Natif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Localisation`
--

CREATE TABLE `Localisation` (
  `id_localisation` int NOT NULL,
  `adresse` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ville` varchar(50) NOT NULL,
  `departement` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Localisation`
--

INSERT INTO `Localisation` (`id_localisation`, `adresse`, `ville`, `departement`) VALUES
(1, '19 Rue de Rivoli', 'Paris', '75'),
(2, '20 Place Bellecour', 'Lyon', '69'),
(3, '35 Quai de la Douane', 'Bordeaux', '33'),
(4, '12 Place du Commerce', 'Nantes', '44'),
(5, '10 Place du Capitole', 'Toulouse', '31'),
(6, '1 Av. Albert Einstein', 'Villeurbanne', '69100');

-- --------------------------------------------------------

--
-- Structure de la table `Offres`
--

CREATE TABLE `Offres` (
  `id_offres` int NOT NULL,
  `nom_offres` varchar(100) NOT NULL,
  `type_offres` enum('Stage','Alternance','CDI','CDD') NOT NULL,
  `duree_offres` varchar(50) DEFAULT NULL,
  `salaire_offres` decimal(10,2) DEFAULT NULL,
  `description_offres` text NOT NULL,
  `missions` text NOT NULL,
  `date_debut` date DEFAULT NULL,
  `note` int DEFAULT NULL,
  `secteur_offres` varchar(100) DEFAULT NULL,
  `Profil_recherche` varchar(100) DEFAULT NULL,
  `id_entreprise` int NOT NULL,
  `id_localisation` int NOT NULL,
  `tag` enum('une','new','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Offres`
--

INSERT INTO `Offres` (`id_offres`, `nom_offres`, `type_offres`, `duree_offres`, `salaire_offres`, `description_offres`, `missions`, `date_debut`, `note`, `secteur_offres`, `Profil_recherche`, `id_entreprise`, `id_localisation`, `tag`) VALUES
(1, 'Développeur Full-Stack React/Node', 'Alternance', '24 mois', 1200.00, 'Poste chez TechVision', 'Développement React/Node', '2024-09-01', 5, 'Informatique', 'React, Node.js, TypeScript', 1, 1, 'une'),
(2, 'Stage UX/UI Designer', 'Stage', '6 mois', 900.00, 'Poste chez DesignStudio', 'Design Figma', '2024-06-01', 5, 'Design', 'Figma, Sketch', 2, 2, 'new'),
(3, 'Alternance Data Analyst', 'Alternance', '12 mois', 1100.00, 'Poste chez DataCorp', 'Analyse SQL/Python', '2024-09-01', 5, 'Data', 'Python, SQL, Power BI', 3, 3, 'une'),
(4, 'Stage Marketing Digital', 'Stage', '4 mois', 700.00, 'Poste chez GrowthHive', 'SEO et Ads', '2024-05-01', 4, 'Marketing', 'SEO, Google Ads', 4, 4, 'new'),
(5, 'Alternance Cybersécurité', 'Alternance', '24 mois', 1300.00, 'Poste chez SecureNet', 'Pentest réseau', '2024-10-01', 5, 'Sécurité', 'Réseau, SIEM', 5, 5, ''),
(6, 'Stage Finance d\'Entreprise', 'Stage', '6 mois', 1000.00, 'Poste chez FinGroup', 'Audit financier', '2024-06-01', 4, 'Finance', 'Excel, Audit', 6, 1, ''),
(13, 'Développeur Mobile iOS', 'Alternance', '12 mois', 1250.00, 'Poste chez AppMaster', 'Développement Swift/SwiftUI', '2024-09-01', 5, 'Informatique', 'Swift, iOS, Git', 1, 2, 'une'),
(14, 'Chargé de Recrutement Tech', 'Stage', '6 mois', 850.00, 'Poste chez TalentHunter', 'Sourcing et entretiens', '2024-06-15', 4, 'RH', 'LinkedIn, Recrutement, ATS', 3, 1, 'new'),
(15, 'Chef de Projet Digital', 'Alternance', '24 mois', 1400.00, 'Poste chez DigitalFlow', 'Pilotage de projets Web', '2024-10-01', 5, 'Marketing', 'Agile, Scrum, Trello', 3, 3, 'une'),
(16, 'Analyste Cybersécurité Junior', 'Stage', '6 mois', 1100.00, 'Poste chez ShieldCorp', 'Surveillance des logs et audit', '2024-07-01', 5, 'Sécurité', 'Wireshark, Kali, Linux', 5, 5, 'new'),
(17, 'Comptable Assistant', 'Alternance', '12 mois', 1150.00, 'Poste chez MoneyGuard', 'Gestion des factures et paie', '2024-09-01', 4, 'Finance', 'Sage, Excel, Fiscalité', 2, 4, 'une'),
(18, 'UX Researcher', 'Stage', '4 mois', 950.00, 'Poste chez UserFirst', 'Tests utilisateurs et interviews', '2024-05-01', 5, 'Design', 'Figma, Miro, Maze', 2, 2, 'new'),
(19, 'Elie Franck Sèmèvo ACACHA', 'Stage', '2 mois', 400.00, 'swvfgsd', 'svdfDsqd', '2026-03-20', 2, 'informatique', 'cool', 7, 6, 'une');

-- --------------------------------------------------------

--
-- Structure de la table `parler`
--

CREATE TABLE `parler` (
  `id_user` int NOT NULL,
  `id_langues` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pilote`
--

CREATE TABLE `pilote` (
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Posseder`
--

CREATE TABLE `Posseder` (
  `id_user` int NOT NULL,
  `id_competences` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Postuler`
--

CREATE TABLE `Postuler` (
  `id_offres` int NOT NULL,
  `id_user` int NOT NULL,
  `Date_candidature` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('En attente','En cours','Entretien','Refusé','Accepté') DEFAULT 'En attente',
  `lettre_motivation` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `id_user` int NOT NULL,
  `nom_user` varchar(50) NOT NULL,
  `prenom_user` varchar(50) NOT NULL,
  `mail_user` varchar(100) NOT NULL,
  `mdp_user` varchar(255) NOT NULL,
  `role_user` enum('admin','pilote','etudiant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`id_user`, `nom_user`, `prenom_user`, `mail_user`, `mdp_user`, `role_user`) VALUES
(7, 'test', 'test', 'test', 'test', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_user`);

--
-- Index pour la table `Ajouter_favoris`
--
ALTER TABLE `Ajouter_favoris`
  ADD PRIMARY KEY (`id_offres`,`id_user`),
  ADD KEY `Favoris_Candidats_FK` (`id_user`);

--
-- Index pour la table `Candidats`
--
ALTER TABLE `Candidats`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `Candidats_pilote_FK` (`id_user_pilote`);

--
-- Index pour la table `Competences`
--
ALTER TABLE `Competences`
  ADD PRIMARY KEY (`id_competences`);

--
-- Index pour la table `Demander`
--
ALTER TABLE `Demander`
  ADD PRIMARY KEY (`id_offres`,`id_competences`),
  ADD KEY `Demander_Competences_FK` (`id_competences`);

--
-- Index pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Index pour la table `Langues`
--
ALTER TABLE `Langues`
  ADD PRIMARY KEY (`id_langues`);

--
-- Index pour la table `Localisation`
--
ALTER TABLE `Localisation`
  ADD PRIMARY KEY (`id_localisation`);

--
-- Index pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD PRIMARY KEY (`id_offres`),
  ADD KEY `Offres_Entreprises_FK` (`id_entreprise`),
  ADD KEY `Offres_Localisation_FK` (`id_localisation`);

--
-- Index pour la table `parler`
--
ALTER TABLE `parler`
  ADD PRIMARY KEY (`id_user`,`id_langues`),
  ADD KEY `parler_Langues_FK` (`id_langues`);

--
-- Index pour la table `pilote`
--
ALTER TABLE `pilote`
  ADD PRIMARY KEY (`id_user`);

--
-- Index pour la table `Posseder`
--
ALTER TABLE `Posseder`
  ADD PRIMARY KEY (`id_user`,`id_competences`),
  ADD KEY `Posseder_Competences_FK` (`id_competences`);

--
-- Index pour la table `Postuler`
--
ALTER TABLE `Postuler`
  ADD PRIMARY KEY (`id_offres`,`id_user`),
  ADD KEY `Postuler_Candidats_FK` (`id_user`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `mail_user` (`mail_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Competences`
--
ALTER TABLE `Competences`
  MODIFY `id_competences` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Entreprises`
--
ALTER TABLE `Entreprises`
  MODIFY `id_entreprise` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `Langues`
--
ALTER TABLE `Langues`
  MODIFY `id_langues` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Localisation`
--
ALTER TABLE `Localisation`
  MODIFY `id_localisation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `Offres`
--
ALTER TABLE `Offres`
  MODIFY `id_offres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_Utilisateurs_FK` FOREIGN KEY (`id_user`) REFERENCES `Utilisateurs` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Ajouter_favoris`
--
ALTER TABLE `Ajouter_favoris`
  ADD CONSTRAINT `Favoris_Candidats_FK` FOREIGN KEY (`id_user`) REFERENCES `Candidats` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `Favoris_Offres_FK` FOREIGN KEY (`id_offres`) REFERENCES `Offres` (`id_offres`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Candidats`
--
ALTER TABLE `Candidats`
  ADD CONSTRAINT `Candidats_pilote_FK` FOREIGN KEY (`id_user_pilote`) REFERENCES `pilote` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `Candidats_Utilisateurs_FK` FOREIGN KEY (`id_user`) REFERENCES `Utilisateurs` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Demander`
--
ALTER TABLE `Demander`
  ADD CONSTRAINT `Demander_Competences_FK` FOREIGN KEY (`id_competences`) REFERENCES `Competences` (`id_competences`) ON DELETE CASCADE,
  ADD CONSTRAINT `Demander_Offres_FK` FOREIGN KEY (`id_offres`) REFERENCES `Offres` (`id_offres`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Offres`
--
ALTER TABLE `Offres`
  ADD CONSTRAINT `Offres_Entreprises_FK` FOREIGN KEY (`id_entreprise`) REFERENCES `Entreprises` (`id_entreprise`) ON DELETE CASCADE,
  ADD CONSTRAINT `Offres_Localisation_FK` FOREIGN KEY (`id_localisation`) REFERENCES `Localisation` (`id_localisation`);

--
-- Contraintes pour la table `parler`
--
ALTER TABLE `parler`
  ADD CONSTRAINT `parler_Candidats_FK` FOREIGN KEY (`id_user`) REFERENCES `Candidats` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `parler_Langues_FK` FOREIGN KEY (`id_langues`) REFERENCES `Langues` (`id_langues`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pilote`
--
ALTER TABLE `pilote`
  ADD CONSTRAINT `pilote_Utilisateurs_FK` FOREIGN KEY (`id_user`) REFERENCES `Utilisateurs` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Posseder`
--
ALTER TABLE `Posseder`
  ADD CONSTRAINT `Posseder_Candidats_FK` FOREIGN KEY (`id_user`) REFERENCES `Candidats` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `Posseder_Competences_FK` FOREIGN KEY (`id_competences`) REFERENCES `Competences` (`id_competences`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Postuler`
--
ALTER TABLE `Postuler`
  ADD CONSTRAINT `Postuler_Candidats_FK` FOREIGN KEY (`id_user`) REFERENCES `Candidats` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `Postuler_Offres_FK` FOREIGN KEY (`id_offres`) REFERENCES `Offres` (`id_offres`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
