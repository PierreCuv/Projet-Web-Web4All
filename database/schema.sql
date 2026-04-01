DROP DATABASE IF EXISTS web4all;
CREATE DATABASE web4all
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE web4all;

CREATE TABLE `role` (
    `id_role` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `libelle` VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `utilisateur` (
    `id_utilisateur` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom`            VARCHAR(100) NOT NULL,
    `prenom`         VARCHAR(100) NOT NULL,
    `email`          VARCHAR(150) NOT NULL UNIQUE,
    `mot_de_passe`   VARCHAR(255) NOT NULL,
    `id_role`        INT UNSIGNED NOT NULL,
    `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_utilisateur_role`
        FOREIGN KEY (`id_role`) REFERENCES `role`(`id_role`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `etudiant` (
    `id_etudiant`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `promotion`      VARCHAR(50)  NULL,
    `cv`             VARCHAR(255) NULL,
    `id_utilisateur` INT UNSIGNED NOT NULL UNIQUE,
    `id_pilote`      INT UNSIGNED NULL,
    CONSTRAINT `fk_etudiant_utilisateur`
        FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur`(`id_utilisateur`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pilote` (
    `id_pilote`      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `id_utilisateur` INT UNSIGNED NOT NULL UNIQUE,
    CONSTRAINT `fk_pilote_utilisateur`
        FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur`(`id_utilisateur`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `etudiant`
    ADD CONSTRAINT `fk_etudiant_pilote`
        FOREIGN KEY (`id_pilote`) REFERENCES `pilote`(`id_pilote`)
        ON DELETE SET NULL;

CREATE TABLE `entreprise` (
    `id_entreprise` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom`           VARCHAR(150) NOT NULL,
    `secteur`       VARCHAR(100) NULL,
    `adresse`       VARCHAR(255) NULL,
    `email`         VARCHAR(150) NOT NULL,
    `telephone`     VARCHAR(20)  NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `offre` (
    `id_offre`      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `titre`         VARCHAR(150)  NOT NULL,
    `description`   TEXT          NOT NULL,
    `lieu`          VARCHAR(150)  NULL,
    `date_debut`    DATE          NULL,
    `date_fin`      DATE          NULL,
    `remuneration`  DECIMAL(8,2)  NULL,
    `id_entreprise` INT UNSIGNED  NOT NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_offre_entreprise`
        FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise`(`id_entreprise`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `competence` (
    `id_competence` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom`           VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `offre_competence` (
    `id_offre`      INT UNSIGNED NOT NULL,
    `id_competence` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id_offre`, `id_competence`),
    CONSTRAINT `fk_oc_offre`
        FOREIGN KEY (`id_offre`) REFERENCES `offre`(`id_offre`) ON DELETE CASCADE,
    CONSTRAINT `fk_oc_competence`
        FOREIGN KEY (`id_competence`) REFERENCES `competence`(`id_competence`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `candidature` (
    `id_candidature`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `lettre_motivation` TEXT         NULL,
    `cv`                VARCHAR(255) NOT NULL,
    `statut`            VARCHAR(50)  NOT NULL DEFAULT 'en_attente',
    `date_candidature`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id_etudiant`       INT UNSIGNED NOT NULL,
    `id_offre`          INT UNSIGNED NOT NULL,
    UNIQUE KEY `uq_candidature` (`id_etudiant`, `id_offre`),
    CONSTRAINT `fk_cand_etudiant`
        FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant`(`id_etudiant`) ON DELETE CASCADE,
    CONSTRAINT `fk_cand_offre`
        FOREIGN KEY (`id_offre`) REFERENCES `offre`(`id_offre`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wishlist` (
    `id_etudiant` INT UNSIGNED NOT NULL,
    `id_offre`    INT UNSIGNED NOT NULL,
    `date_ajout`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_etudiant`, `id_offre`),
    CONSTRAINT `fk_wl_etudiant`
        FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant`(`id_etudiant`) ON DELETE CASCADE,
    CONSTRAINT `fk_wl_offre`
        FOREIGN KEY (`id_offre`) REFERENCES `offre`(`id_offre`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `evaluation` (
    `id_evaluation`   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `note`            TINYINT      NOT NULL CHECK (`note` BETWEEN 1 AND 5),
    `commentaire`     TEXT         NULL,
    `date_evaluation` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id_etudiant`     INT UNSIGNED NOT NULL,
    `id_entreprise`   INT UNSIGNED NOT NULL,
    CONSTRAINT `fk_eval_etudiant`
        FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant`(`id_etudiant`) ON DELETE CASCADE,
    CONSTRAINT `fk_eval_entreprise`
        FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise`(`id_entreprise`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Données de démo ───────────────────────────────────────────────────────

INSERT INTO `role` (`libelle`) VALUES
('administrateur'), ('pilote'), ('etudiant');

INSERT INTO `utilisateur` (`nom`, `prenom`, `email`, `mot_de_passe`, `id_role`) VALUES
('Admin', 'Web4All', 'admin@web4all.fr',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('Dupont', 'Marie', 'marie.dupont@cesi.fr',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Martin', 'Lucas', 'lucas.martin@cesi.fr',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);

INSERT INTO `pilote` (`id_utilisateur`) VALUES (2);

INSERT INTO `etudiant` (`promotion`, `id_utilisateur`, `id_pilote`) VALUES
('CPI A2 2026', 3, 1);

INSERT INTO `entreprise` (`nom`, `secteur`, `adresse`, `email`, `telephone`) VALUES
('Accenture France', 'Conseil IT', '118 av. de France, Paris', 'recrutement@accenture.fr', '01 53 23 00 00'),
('Capgemini', 'Services numériques', '11 rue de Tilsitt, Paris', 'stages@capgemini.com', '01 47 54 50 00'),
('OVHcloud', 'Cloud & Hébergement', '2 rue Kellermann, Roubaix', 'rh@ovhcloud.com', '03 20 44 64 00');

INSERT INTO `competence` (`nom`) VALUES
('PHP'), ('MySQL'), ('JavaScript'), ('HTML/CSS'), ('Linux'), ('Docker'), ('REST API');

INSERT INTO `offre` (`titre`, `description`, `lieu`, `date_debut`, `date_fin`, `remuneration`, `id_entreprise`) VALUES
('Développeur PHP Backend', 'Stage développement backend.', 'Paris', '2025-09-01', '2025-11-30', 800.00, 1),
('Développeur Full Stack', 'Développement application SaaS.', 'Paris', '2025-09-15', '2026-01-15', 850.00, 2),
('Administrateur Linux', 'Administration serveurs cloud.', 'Roubaix', '2025-10-01', '2025-12-31', 900.00, 3);

INSERT INTO `offre_competence` (`id_offre`, `id_competence`) VALUES
(1,1),(1,2),(1,7),
(2,1),(2,3),(2,4),
(3,5),(3,6);
