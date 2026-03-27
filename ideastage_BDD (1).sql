-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 27 mars 2026 à 14:05
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

--
-- Déchargement des données de la table `Ajouter_favoris`
--

INSERT INTO `Ajouter_favoris` (`id_offres`, `id_user`, `Etat`) VALUES
(1, 13, 1);

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

--
-- Déchargement des données de la table `Candidats`
--

INSERT INTO `Candidats` (`id_user`, `titre_profil`, `cv`, `photo`, `add_doc`, `disponibilite`, `id_user_pilote`) VALUES
(13, 'Etudiant passionne=é', '', '', '', 'Septembre 2026', NULL);

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
(7, 'zeubh', '', '', 0),
(8, 'TechVision', 'techvision.png', 'Éditeur de logiciels SaaS innovants pour la Fintech.', 5),
(9, 'DataCorp', 'datacorp.png', 'Spécialiste du Big Data et de l\'Intelligence Artificielle.', 4),
(10, 'WebInnov', 'webinnov.png', 'Agence web spécialisée dans les expériences immersives.', 4),
(11, 'GreenTech IT', 'greentech.png', 'Solutions numériques éco-responsables et durables.', 5),
(12, 'CyberShield', 'cybershield.png', 'Leader européen de la cybersécurité des infrastructures.', 4),
(13, 'E-Shop Solutions', 'eshop.png', 'Plateforme e-commerce haute performance.', 3),
(14, 'CloudArchitects', 'cloud.png', 'Consulting et déploiement d\'infrastructures Cloud.', 5),
(15, 'GameStudio FR', 'gamestudio.png', 'Studio de développement de jeux vidéo indépendants.', 4),
(16, 'HealthTech', 'healthtech.png', 'Applications médicales et suivi patient à distance.', 5),
(17, 'AgileDev', 'agiledev.png', 'ESN spécialisée dans les méthodes agiles et le dev sur mesure.', 4);

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
(6, '1 Av. Albert Einstein', 'Villeurbanne', '69100'),
(7, '15 Rue de la Paix', 'Paris', '75'),
(8, '10 Avenue Jean Jaurès', 'Lyon', '69'),
(9, '5 Place du Capitole', 'Toulouse', '31'),
(10, '42 Rue de la République', 'Marseille', '13'),
(11, '8 Boulevard de la Liberté', 'Lille', '59'),
(12, '12 Cours de l\'Intendance', 'Bordeaux', '33'),
(13, '9 Quai de la Fosse', 'Nantes', '44'),
(14, '22 Rue de la Mésange', 'Strasbourg', '67'),
(15, '3 Place Sainte-Anne', 'Rennes', '35'),
(16, '11 Rue de la Loge', 'Montpellier', '34');

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
  `tag` enum('une','new','') NOT NULL,
  `skils` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Offres`
--

INSERT INTO `Offres` (`id_offres`, `nom_offres`, `type_offres`, `duree_offres`, `salaire_offres`, `description_offres`, `missions`, `date_debut`, `note`, `secteur_offres`, `Profil_recherche`, `id_entreprise`, `id_localisation`, `tag`, `skils`) VALUES
(1, 'Développeur Full-Stack React/Node', 'Alternance', '24 mois', 1200.00, 'Poste chez TechVision', 'Développement React/Node', '2024-09-01', 5, 'Informatique', 'Autodidacte ou Bac+2', 1, 1, 'une', 'React, Node.js, TypeScript'),
(2, 'Stage UX/UI Designer', 'Stage', '6 mois', 900.00, 'Poste chez DesignStudio', 'Design Figma', '2024-06-01', 5, 'Design', 'Autodidacte ou Bac+2', 2, 2, 'new', 'Figma, Sketch'),
(3, 'Alternance Data Analyst', 'Alternance', '12 mois', 1100.00, 'Poste chez DataCorp', 'Analyse SQL/Python', '2024-09-01', 5, 'Data', 'Autodidacte ou Bac+2', 3, 3, 'une', 'Python, SQL, Power BI'),
(4, 'Stage Marketing Digital', 'Stage', '4 mois', 700.00, 'Poste chez GrowthHive', 'SEO et Ads', '2024-05-01', 4, 'Marketing', 'Autodidacte ou Bac+2', 4, 4, 'new', 'SEO, Google Ads'),
(5, 'Alternance Cybersécurité', 'Alternance', '24 mois', 1300.00, 'Poste chez SecureNet', 'Pentest réseau', '2024-10-01', 5, 'Sécurité', 'Autodidacte ou Bac+2', 5, 5, '', 'Réseau, SIEM'),
(6, 'Stage Finance d\'Entreprise', 'Stage', '6 mois', 1000.00, 'Poste chez FinGroup', 'Audit financier', '2024-06-01', 4, 'Finance', 'Autodidacte ou Bac+2', 6, 1, '', 'Excel, Audit'),
(13, 'Développeur Mobile iOS', 'Alternance', '12 mois', 1250.00, 'Poste chez AppMaster', 'Développement Swift/SwiftUI', '2024-09-01', 5, 'Informatique', 'Autodidacte ou Bac+2', 1, 2, 'une', 'Swift, iOS, Git'),
(14, 'Chargé de Recrutement Tech', 'Stage', '6 mois', 850.00, 'Poste chez TalentHunter', 'Sourcing et entretiens', '2024-06-15', 4, 'RH', 'Autodidacte ou Bac+2', 3, 1, 'new', 'LinkedIn, Recrutement, ATS'),
(15, 'Chef de Projet Digital', 'Alternance', '24 mois', 1400.00, 'Poste chez DigitalFlow', 'Pilotage de projets Web', '2024-10-01', 5, 'Marketing', 'Autodidacte ou Bac+2', 3, 3, 'une', 'Agile, Scrum, Trello'),
(16, 'Analyste Cybersécurité Junior', 'Stage', '6 mois', 1100.00, 'Poste chez ShieldCorp', 'Surveillance des logs et audit', '2024-07-01', 5, 'Sécurité', 'Autodidacte ou Bac+2', 5, 5, 'new', 'Wireshark, Kali, Linux'),
(17, 'Comptable Assistant', 'Alternance', '12 mois', 1150.00, 'Poste chez MoneyGuard', 'Gestion des factures et paie', '2024-09-01', 4, 'Finance', 'Autodidacte ou Bac+2', 2, 4, 'une', 'Sage, Excel, Fiscalité'),
(18, 'UX Researcher', 'Stage', '4 mois', 950.00, 'Poste chez UserFirst', 'Tests utilisateurs et interviews', '2024-05-01', 5, 'Design', 'Autodidacte ou Bac+2', 2, 2, 'new', 'Figma, Miro, Maze'),
(19, 'Elie Franck Sèmèvo ACACHA', 'Stage', '2 mois', 400.00, 'swvfgsd', 'svdfDsqd', '2026-03-20', 2, 'informatique', 'cool', 7, 6, 'une', 'cool'),
(20, 'Développeur Full-Stack React/Node', 'Alternance', '24 mois', 1200.00, 'Rejoignez TechVision pour développer notre nouvelle plateforme SaaS.', 'Développer de nouvelles features\nMaintenir l\'API REST\nTests unitaires', '2026-09-01', 42, 'Fintech', 'Étudiant Master ou Ingénieur Bac+4/5', 1, 1, 'une', 'React, Node.js, TypeScript'),
(21, 'Développeur Full-Stack Vue/Laravel', 'CDI', NULL, 3500.00, 'WebInnov cherche un profil autonome pour gérer des projets clients.', 'Conception architecture\nDéveloppement front/back\nDéploiement', '2026-04-15', 12, 'Agence Web', 'Bac+3 avec 2 ans d\'expérience minimum', 3, 2, 'new', 'Vue.js, Laravel, PHP, MySQL'),
(22, 'Ingénieur Full-Stack Java/Angular', 'CDI', NULL, 4200.00, 'Travaillez sur des applications critiques dans un environnement sécurisé.', 'Développement backend Java\nInterface Angular\nCode review', '2026-05-01', 5, 'Cybersécurité', 'Ingénieur Bac+5, 3 ans d\'expérience', 5, 3, '', 'Java, Spring Boot, Angular'),
(23, 'Stage Développeur Full-Stack JS', 'Stage', '6 mois', 800.00, 'Stage de fin d\'étude pour participer à la création d\'un MVP.', 'Création de composants UI\nCréation de routes Express', '2026-06-01', 25, 'E-commerce', 'Étudiant Bac+5 en recherche de stage', 6, 4, '', 'JavaScript, Express, React'),
(24, 'Développeur MERN Stack', 'Alternance', '12 mois', 1100.00, 'GreenTech IT recherche son nouvel alternant pour son pôle R&D.', 'Participation aux sprints\nDev React/Node\nTests E2E', '2026-09-15', 18, 'Green IT', 'Bac+3/4 Informatique', 4, 5, 'new', 'MongoDB, Express, React, Node'),
(25, 'Dev Full-Stack Python/React', 'CDD', '18 mois', 3200.00, 'Projet de R&D de 18 mois au sein de DataCorp.', 'Intégration modèles IA\nDashboard React\nAPI Python', '2026-07-01', 8, 'Big Data', 'Master Informatique / Data', 2, 6, '', 'Python, FastAPI, React'),
(26, 'Développeur Full-Stack PHP/Symfony', 'CDI', NULL, 3100.00, 'AgileDev recrute pour un client grand compte dans le retail.', 'Maintenance évolutive\nRefonte legacy\nOptimisation BDD', '2026-03-01', 4, 'ESN', 'Bac+3 avec expérience significative', 10, 7, '', 'PHP, Symfony, Twig, MariaDB'),
(27, 'Développeur Full-Stack C#/.NET', 'CDI', NULL, 3800.00, 'HealthTech cherche un dev .NET pour son logiciel médical.', 'Développement logiciel\nGestion base SQL Server', '2026-05-15', 10, 'Santé', 'Bac+5 avec profil technique robuste', 9, 8, 'une', 'C#, .NET Core, Angular, SQL Server'),
(28, 'Alternance Full-Stack Ruby on Rails', 'Alternance', '12 mois', 1000.00, 'Rejoignez une équipe de passionnés pour faire évoluer notre produit.', 'Tickets de bug\nNouvelles fonctionnalités\nTests', '2026-09-01', 30, 'Startup', 'Bac+4', 8, 9, 'new', 'Ruby, Rails, Hotwire, PostgreSQL'),
(29, 'Lead Dev Full-Stack TypeScript', 'CDI', NULL, 5500.00, 'Prenez le lead sur notre équipe de 5 développeurs.', 'Architecture\nMentorat\nChoix techniques', '2026-04-01', 2, 'Cloud', 'Expert technique, 5 ans d\'expérience', 7, 10, 'une', 'TypeScript, Node.js, Next.js, AWS'),
(30, 'Développeur Front-End React', 'CDI', NULL, 3400.00, 'Refonte complète de l\'interface utilisateur de notre outil principal.', 'Développement UI\nAccessibilité\nAnimations', '2026-06-01', 15, 'SaaS', 'Bac+3, portfolio exigé', 1, 2, 'new', 'React, Redux, TailwindCSS'),
(31, 'Intégrateur Web / Vue.js', 'CDD', '6 mois', 2500.00, 'Contrat court pour un pic de charge sur la création de landing pages.', 'Intégration maquettes Figma\nComposants Vue.js', '2026-03-15', 12, 'Agence Web', 'Autodidacte ou Bac+2', 3, 1, '', 'HTML, CSS, Vue.js, SASS'),
(32, 'Développeur Front-End Angular', 'Alternance', '24 mois', 1300.00, 'Alternance au sein du pôle cyber pour créer des dashboards sécurisés.', 'Data visualization\nComposants stricts', '2026-09-01', 20, 'Cybersécurité', 'Étudiant en cycle ingénieur', 5, 4, '', 'Angular, RxJS, D3.js'),
(33, 'Stage Front-End UI/UX', 'Stage', '4 mois', 700.00, 'Stage axé sur l\'expérience utilisateur et le design system.', 'Prototypage\nIntégration CSS/JS', '2026-05-01', 50, 'Green IT', 'Étudiant design ou web', 4, 3, 'une', 'Figma, CSS, JS, React'),
(34, 'Développeur Mobile React Native', 'CDI', NULL, 3900.00, 'E-Shop Solutions lance son application mobile.', 'Développement app iOS/Android\nLien avec l\'API', '2026-07-01', 8, 'E-commerce', 'Bac+5 avec 2 ans XP mobile', 6, 5, 'new', 'React Native, Expo, Redux'),
(35, 'Expert Front-End WebGL', 'CDI', NULL, 4800.00, 'GameStudio cherche un expert pour ses jeux par navigateur.', 'Optimisation rendu\nDéveloppement 3D', '2026-08-01', 3, 'Jeux Vidéo', 'Expertise WebGL obligatoire', 8, 6, '', 'WebGL, Three.js, JavaScript'),
(36, 'Développeur Front-End Svelte', 'Alternance', '12 mois', 1150.00, 'Découvrez Svelte avec notre équipe d\'innovation.', 'Migration d\'anciennes apps\nTests front', '2026-09-01', 14, 'Cloud', 'Curieux, Bac+3', 7, 7, '', 'Svelte, JavaScript, CSS'),
(37, 'Lead Développeur Front', 'CDI', NULL, 5000.00, 'Garant de la qualité front-end de toutes nos applications santé.', 'Architecture Front\nCI/CD Front\nTests E2E', '2026-04-15', 5, 'Santé', 'Bac+5, profil senior', 9, 8, 'une', 'React, TypeScript, Cypress'),
(38, 'Stage Intégration Web Accessibilité', 'Stage', '6 mois', 900.00, 'Rendre nos applications accessibles à tous (normes RGAA).', 'Audit d\'accessibilité\nCorrection de code HTML/CSS', '2026-06-01', 22, 'SaaS', 'Passionné par l\'accessibilité', 1, 9, '', 'HTML5, ARIA, CSS3'),
(39, 'Développeur Front-End Next.js', 'CDD', '12 mois', 3200.00, 'Création du nouveau portail B2B.', 'SSR/SSG\nPerformance Web', '2026-05-01', 11, 'ESN', 'Bac+3 avec expérience Next', 10, 10, 'new', 'Next.js, React, Vercel'),
(40, 'Développeur Back-End Node.js', 'CDI', NULL, 3600.00, 'Rejoignez le cœur du moteur de DataCorp.', 'Microservices\nOptimisation BDD', '2026-06-15', 9, 'Big Data', 'Bac+5, 2 ans d\'expérience', 2, 1, 'une', 'Node.js, Express, MongoDB'),
(41, 'Ingénieur Back-End Java', 'CDI', NULL, 4200.00, 'Pôle architecture de CloudArchitects.', 'Création d\'API haute dispo\nKafka', '2026-04-01', 6, 'Cloud', 'Ingénieur expérimenté', 7, 2, '', 'Java, Spring, Kafka'),
(42, 'Développeur Back-End Python/Django', 'Alternance', '12 mois', 1250.00, 'Equipe interne pour l\'outil de gestion de l\'entreprise.', 'Dev de features back\nScripts d\'automatisation', '2026-09-01', 25, 'SaaS', 'Étudiant Bac+4', 1, 3, 'new', 'Python, Django, PostgreSQL'),
(43, 'Stage Back-End Go', 'Stage', '6 mois', 1000.00, 'Récriture d\'un microservice en Golang pour la performance.', 'Apprentissage de Go\nTests unitaires', '2026-07-01', 40, 'Cybersécurité', 'Étudiant Ingénieur', 5, 4, '', 'Go, Docker, SQL'),
(44, 'Développeur PHP/Laravel', 'CDD', '6 mois', 2800.00, 'Renfort d\'équipe pour finaliser la marketplace.', 'API REST\nPaiement Stripe', '2026-03-01', 15, 'E-commerce', 'Bac+2/3 avec expérience', 6, 5, '', 'PHP, Laravel, MySQL'),
(45, 'Développeur C++ / Rust', 'CDI', NULL, 4500.00, 'Développement du moteur physique du prochain jeu.', 'Programmation système\nOptimisation mémoire', '2026-05-15', 4, 'Jeux Vidéo', 'Passionné système, C++', 8, 6, 'une', 'C++, Rust, CMake'),
(46, 'Architecte Back-End', 'CDI', NULL, 6000.00, 'Conception de l\'architecture logicielle globale.', 'Design de système\nSécurité\nChoix BDD', '2026-08-01', 2, 'Santé', 'Expert, 8+ ans d\'expérience', 9, 7, '', 'Architecture, Microservices, SQL/NoSQL'),
(47, 'Alternance Back-End C#', 'Alternance', '24 mois', 1100.00, 'Apprentissage du framework .NET sur des projets clients divers.', 'Développement d\'API\nEntity Framework', '2026-09-01', 18, 'ESN', 'Étudiant Bac+3', 10, 8, 'new', 'C#, .NET, SQL Server'),
(48, 'Développeur Back-End Ruby', 'CDI', NULL, 3500.00, 'Maintenance et évolution de notre outil de ticketing.', 'Dev Ruby\nOptimisation requêtes', '2026-04-01', 10, 'Agence Web', 'Bac+3', 3, 9, '', 'Ruby, PostgreSQL, Redis'),
(49, 'Stage Ingénieur Cloud', 'Stage', '6 mois', 900.00, 'Déploiement d\'infrastructures as Code.', 'Terraform\nScripts AWS', '2026-06-01', 35, 'Green IT', 'Cycle Ingénieur', 4, 10, 'une', 'AWS, Terraform, Python'),
(50, 'Data Scientist', 'CDI', NULL, 4500.00, 'Analyse prédictive des données utilisateurs.', 'Création modèles ML\nAnalyse de données', '2026-05-01', 14, 'Big Data', 'Master ou PhD Data', 2, 1, 'une', 'Python, Scikit-learn, SQL'),
(51, 'Ingénieur DevOps', 'CDI', NULL, 4800.00, 'Mise en place des pipelines CI/CD pour toute l\'entreprise.', 'Gestion Docker/K8s\nGitLab CI', '2026-06-15', 7, 'SaaS', '3 ans d\'XP DevOps', 1, 2, 'new', 'Docker, Kubernetes, CI/CD'),
(52, 'Data Analyst', 'Alternance', '12 mois', 1200.00, 'Création de dashboards pour les équipes métiers.', 'Requêtes SQL complexes\nOutils BI', '2026-09-01', 28, 'E-commerce', 'Étudiant Bac+4 Data', 6, 3, '', 'SQL, Tableau, Python'),
(53, 'Stage Data Engineer', 'Stage', '6 mois', 1000.00, 'Construction de pipelines de données temps réel.', 'ETL\nSpark\nKafka', '2026-07-01', 12, 'Cloud', 'Étudiant en dernière année', 7, 4, '', 'Python, Spark, Kafka'),
(54, 'Administrateur Systèmes et Réseaux', 'CDI', NULL, 3200.00, 'Gestion du parc informatique et des serveurs internes.', 'Support niveau 2/3\nGestion serveurs Linux', '2026-04-15', 5, 'Santé', 'Bac+2/3 Réseaux', 9, 5, '', 'Linux, Bash, Réseaux'),
(55, 'DevSecOps', 'CDI', NULL, 5000.00, 'Intégration de la sécurité dans nos cycles de développement.', 'Tests de pénétration automatisés\nAudit de code', '2026-08-01', 3, 'Cybersécurité', 'Ingénieur Sécurité', 5, 6, 'une', 'Sécurité, Python, CI/CD'),
(56, 'Cloud FinOps', 'CDD', '18 mois', 4000.00, 'Optimisation des coûts de notre infrastructure Cloud.', 'Analyse factures Cloud\nOptimisation ressources', '2026-05-01', 6, 'Cloud', 'Profil technique et financier', 7, 7, 'new', 'AWS, Azure, Excel'),
(57, 'Alternance DevOps', 'Alternance', '24 mois', 1300.00, 'Assister l\'équipe DevOps sur la conteneurisation.', 'Dockerisation d\'apps\nMonitoring', '2026-09-15', 22, 'ESN', 'Étudiant ingénieur', 10, 8, '', 'Docker, Linux, Prometheus'),
(58, 'Machine Learning Engineer', 'CDI', NULL, 4700.00, 'Mise en production des modèles de l\'équipe Data Science.', 'MLOps\nOptimisation de l\'inférence', '2026-06-01', 8, 'Big Data', 'Bac+5, profil hybride Dev/Data', 2, 9, 'une', 'Python, TensorFlow, Docker'),
(59, 'Stage Analyste Cybersécurité', 'Stage', '6 mois', 900.00, 'Surveillance des logs et détection d\'anomalies.', 'Analyse SIEM\nRédaction de rapports', '2026-05-15', 45, 'Cybersécurité', 'Passionné par la sécurité', 5, 10, '', 'SIEM, Réseau, Linux'),
(60, 'Chef de Projet Web', 'CDI', NULL, 3600.00, 'Gestion de projets clients pour notre agence.', 'Animation réunions\nGestion plannings Jira', '2026-04-01', 18, 'Agence Web', 'Bac+5 Management IT', 3, 1, 'new', 'Agile, Scrum, Jira, Trello'),
(61, 'Product Owner', 'CDI', NULL, 4200.00, 'Responsable de la vision produit de notre app phare.', 'Rédaction User Stories\nGestion Backlog', '2026-05-15', 25, 'SaaS', '3 ans d\'XP en PO', 1, 2, 'une', 'Agile, Product Management'),
(62, 'Alternance UI/UX Designer', 'Alternance', '12 mois', 1100.00, 'Création des maquettes pour les futurs jeux du studio.', 'Wireframing\nTests utilisateurs', '2026-09-01', 60, 'Jeux Vidéo', 'Étudiant en école de design', 8, 3, '', 'Figma, Adobe XD, Photoshop'),
(63, 'Scrum Master', 'CDI', NULL, 4000.00, 'Facilitateur pour 2 équipes de développement agiles.', 'Animation rituels Scrum\nLevée des blocages', '2026-06-01', 11, 'ESN', 'Certification Scrum appréciée', 10, 4, '', 'Scrum, Kanban, Communication'),
(64, 'Stage Chef de Projet Digital', 'Stage', '6 mois', 800.00, 'Assister le chef de projet sur le déploiement E-commerce.', 'Recette métier\nSuivi des KPIs', '2026-07-01', 35, 'E-commerce', 'Étudiant Bac+4/5', 6, 5, '', 'Gestion de projet, Excel'),
(65, 'Product Manager Data', 'CDI', NULL, 5000.00, 'Gérer le cycle de vie de nos produits orientés données.', 'Roadmap produit\nStratégie Data', '2026-08-15', 5, 'Big Data', 'Bac+5 avec vernis technique', 2, 6, 'une', 'Product, Data, SQL'),
(66, 'UX Researcher', 'CDD', '12 mois', 3200.00, 'Analyser le comportement de nos utilisateurs pour améliorer l\'outil.', 'Interviews utilisateurs\nAnalyse de heatmaps', '2026-05-01', 15, 'Santé', 'Profil psychologie/ergonomie', 9, 7, 'new', 'Recherche, Hotjar, Analytics'),
(67, 'Alternance Assistant PO', 'Alternance', '24 mois', 1200.00, 'Apprendre le métier de Product Owner au sein de la R&D.', 'Aide à la rédaction des specs\nRecette', '2026-09-01', 40, 'Green IT', 'Étudiant curieux et organisé', 4, 8, '', 'Rédaction, Analyse, Jira'),
(68, 'QA Tester / Automaticien', 'CDI', NULL, 3400.00, 'Assurer la qualité de nos livraisons par des tests automatisés.', 'Création de scripts Cypress\nTests manuels', '2026-04-15', 12, 'Agence Web', 'Bac+2/3 Informatique', 3, 9, '', 'Cypress, Selenium, QA'),
(69, 'Directeur Technique (CTO)', 'CDI', NULL, 7500.00, 'Prendre la direction technique de notre startup en forte croissance.', 'Stratégie technique\nManagement de 20 personnes', '2026-07-01', 1, 'Cloud', 'Minimum 10 ans d\'expérience, leadership', 7, 10, 'une', 'Management, Architecture, Vision');

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

--
-- Déchargement des données de la table `Postuler`
--

INSERT INTO `Postuler` (`id_offres`, `id_user`, `Date_candidature`, `statut`, `lettre_motivation`) VALUES
(1, 13, '2026-03-27 14:32:32', 'En attente', '');

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
(10, 'ACACHA', 'Elie', 'acachaelie@gmail.com', '$2y$10$vAUZd29JBlGdDuxMe7iKAe/tWnMY2/8wKcrz/Fjt0ZKEmOi13ykNC', 'admin'),
(13, 'stud', 'stud', 'etudiant@gmail.com', '$2y$10$UEjFcyjvoJdh/YqDtKUY7.miDTVxDyFl2u.V3Yw6owK1SiKWXL6Ea', 'etudiant');

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
  MODIFY `id_entreprise` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `Langues`
--
ALTER TABLE `Langues`
  MODIFY `id_langues` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Localisation`
--
ALTER TABLE `Localisation`
  MODIFY `id_localisation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `Offres`
--
ALTER TABLE `Offres`
  MODIFY `id_offres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  ADD CONSTRAINT `Candidats_Utilisateurs_FK` FOREIGN KEY (`id_user`) REFERENCES `Utilisateurs` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

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
