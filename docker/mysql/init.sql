-- Création de la base de données GPSWOX si elle n'existe pas
CREATE DATABASE IF NOT EXISTS gpswox CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON gpswox.* TO 'gpswox'@'%';
FLUSH PRIVILEGES;
