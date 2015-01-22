-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: custsql-ipg82.eigbox.net
-- Généré le : Samedi 01 Novembre 2014 à 20:20
-- Version du serveur: 5.5.32
-- Version de PHP: 4.4.9
-- 
-- Base de données: `zdev_campuslms`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `cie`
-- 

CREATE TABLE `cie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `datedebut` date NOT NULL,
  `datefin` date NOT NULL,
  `ajoutepar` int(11) NOT NULL,
  `etat` enum('actif','inactif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cie_license_users`
-- 

CREATE TABLE `cie_license_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_license` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cie_licenses`
-- 

CREATE TABLE `cie_licenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cie` int(11) NOT NULL,
  `id_cours` int(11) NOT NULL,
  `nblicenses` int(11) NOT NULL,
  `datedebut` date NOT NULL,
  `datefin` date NOT NULL,
  `etat` enum('active','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cie_users`
-- 

CREATE TABLE `cie_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cie` int(11) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `niveau` enum('deleted','disabled','etudiant','admin') NOT NULL DEFAULT 'disabled',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours`
-- 

CREATE TABLE `cours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `type` enum('standard','group') NOT NULL,
  `etat` enum('deleted','inactif','actif') NOT NULL DEFAULT 'actif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_groupe`
-- 

CREATE TABLE `cours_groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cours` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon`
-- 

CREATE TABLE `cours_lecon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cours` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `media` text NOT NULL,
  `etat` enum('deleted','inactif','actif') NOT NULL DEFAULT 'actif',
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_fichiers`
-- 

CREATE TABLE `cours_lecon_fichiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lecon` int(11) NOT NULL,
  `id_upload` int(11) NOT NULL,
  `type` enum('fichier','devoir','lien','tp') NOT NULL,
  `titre` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `valeur` decimal(10,4) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_fichiers_remise`
-- 

CREATE TABLE `cours_lecon_fichiers_remise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note` decimal(10,2) DEFAULT NULL,
  `dateNote` datetime NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_formation` int(11) NOT NULL,
  `id_fichier` int(11) NOT NULL,
  `id_upload` int(11) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_prealable`
-- 

CREATE TABLE `cours_lecon_prealable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lecon` int(11) NOT NULL,
  `id_prealable` int(11) NOT NULL,
  `cond` enum('read','10','20','30','40','50','60','70','80','90','100','1star','2star','3star','4star','5star') CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_quizz`
-- 

CREATE TABLE `cours_lecon_quizz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_lecon` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `randomize` tinyint(1) NOT NULL,
  `voir` tinyint(1) NOT NULL,
  `refaire` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `valeur` decimal(10,4) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_quizz_question`
-- 

CREATE TABLE `cours_lecon_quizz_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_quizz` int(11) NOT NULL,
  `question` text NOT NULL,
  `valeur` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `randomize` tinyint(1) NOT NULL,
  `multi` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_quizz_reponse`
-- 

CREATE TABLE `cours_lecon_quizz_reponse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_question` int(11) NOT NULL,
  `reponse` text NOT NULL,
  `valeur` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_quizz_session`
-- 

CREATE TABLE `cours_lecon_quizz_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `id_formation` int(11) NOT NULL,
  `datedebut` datetime NOT NULL,
  `datefin` datetime NOT NULL,
  `pointage` decimal(10,2) NOT NULL,
  `valeur` decimal(10,2) NOT NULL,
  `read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `cours_lecon_quizz_session_reponse`
-- 

CREATE TABLE `cours_lecon_quizz_session_reponse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_session` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `id_reponse` int(11) NOT NULL,
  `valeur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `demandes`
-- 

CREATE TABLE `demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_cours` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `etat` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `formation_points`
-- 

CREATE TABLE `formation_points` (
  `id` int(11) NOT NULL,
  `id_formation` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `type` enum('quizz','devoir') NOT NULL,
  `details` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Structure de la table `formations`
-- 

CREATE TABLE `formations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cours` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `message` text NOT NULL,
  `etat` enum('actif','partiel','inactif','deleted') NOT NULL,
  `date` datetime NOT NULL,
  `datedebut` datetime DEFAULT NULL,
  `datefin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `groupe_users`
-- 

CREATE TABLE `groupe_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `etat` enum('inactif','actif') NOT NULL DEFAULT 'inactif',
  `date` datetime NOT NULL,
  `datefin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `groupes`
-- 

CREATE TABLE `groupes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `etat` enum('deleted','inactif','actif') NOT NULL DEFAULT 'inactif',
  `date` datetime NOT NULL,
  `datedebut` datetime NOT NULL,
  `datefin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `groupes_message`
-- 

CREATE TABLE `groupes_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `logs`
-- 

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cie` tinyint(1) NOT NULL DEFAULT '0',
  `texte` varchar(255) NOT NULL COMMENT 'enum : ''login'',''logout'',''logErr'',''newUser'',''newGroup'',''newCours'',''user2group'',''formation2group'',''tryDelUser'',''delUser'',''tryDelGroup'',''delGroup'',''tryDelCours'',''delCours'',''openedLecon'',''openedCours'',''remiseFichier'',''openedQuiz'',''remiseQuiz'',''newCie''',
  `ref` int(11) NOT NULL,
  `ref2` int(11) NOT NULL,
  `details` text NOT NULL,
  `ip` varchar(45) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=547 DEFAULT CHARSET=utf8 AUTO_INCREMENT=547 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `messages`
-- 

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `lu` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `notes`
-- 

CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_ref` int(11) NOT NULL,
  `ref_type` enum('quiz','devoir','formation') NOT NULL,
  `note` decimal(10,2) NOT NULL,
  `max` decimal(10,2) NOT NULL,
  `valeur` decimal(10,2) NOT NULL COMMENT 'useless?',
  `ajuste` decimal(10,2) NOT NULL COMMENT 'useless?',
  `comment` text NOT NULL,
  `autocomment` text NOT NULL,
  `final` tinyint(1) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `notifications`
-- 

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_ref` int(11) NOT NULL,
  `type` enum('message','note') NOT NULL,
  `date` datetime NOT NULL,
  `lu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `statistiques`
-- 

CREATE TABLE `statistiques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `type` enum('login','reponse') NOT NULL,
  `infos` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `uploadRef`
-- 

CREATE TABLE `uploadRef` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dateCreated` datetime NOT NULL,
  `dateUploaded` datetime NOT NULL,
  `etat` enum('attente','envoye','recu') NOT NULL DEFAULT 'attente',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=316 DEFAULT CHARSET=utf8 AUTO_INCREMENT=316 ;

-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cie` int(11) NOT NULL DEFAULT '0' COMMENT '0 for "standard"',
  `usercode` varchar(32) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `niveau` enum('deleted','disabled','etudiant','enseignant','collaborateur','admin','sadmin') NOT NULL DEFAULT 'disabled',
  `date` datetime NOT NULL,
  `profile` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;
INSERT INTO `users` (`id`, `id_cie`, `usercode`, `prenom`, `nom`, `email`, `pass`, `skype`, `niveau`, `date`, `profile`) VALUES
(59, 0, 'admin', 'Admin', '', '', 'a96053805f3f01b004e7211f6d3ecbc61c15fe70', '', 'sadmin', '2015-01-15 06:23:17', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `users_lecons`
-- 

CREATE TABLE `users_lecons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_lecon` int(11) NOT NULL,
  `datefin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
