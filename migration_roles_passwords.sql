-- ============================================================
-- OrderFlow CPT - Migration v2
-- Historique des connexions + Réinitialisation mot de passe
-- À exécuter dans phpMyAdmin > orderflow > onglet SQL
-- ============================================================

-- 1) Table historique des connexions
CREATE TABLE IF NOT EXISTS connexion_log (
    idLog          INT AUTO_INCREMENT PRIMARY KEY,
    idUtilisateur  INT NULL,
    login          VARCHAR(100) NOT NULL,
    ip             VARCHAR(45)  NOT NULL,
    user_agent     VARCHAR(500) DEFAULT '',
    statut         ENUM('succes','echec') NOT NULL DEFAULT 'succes',
    dateConnexion  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateur(idUtilisateur) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) Table tokens de réinitialisation de mot de passe
CREATE TABLE IF NOT EXISTS password_reset (
    idReset    INT AUTO_INCREMENT PRIMARY KEY,
    idUtilisateur INT NOT NULL,
    token      VARCHAR(64) NOT NULL UNIQUE,
    expiration DATETIME NOT NULL,
    utilise    TINYINT(1) NOT NULL DEFAULT 0,
    createdAt  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateur(idUtilisateur) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Vérification
SELECT 'Migration v2 OK - Tables connexion_log et password_reset créées.' AS resultat;