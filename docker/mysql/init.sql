-- Autoriser root depuis n'importe quelle IP Docker
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'Woai6O4NkCjM7AEWvAVlVgDds9jR0OV6';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

-- Créer les bases de données
CREATE DATABASE IF NOT EXISTS gpswox_web CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE DATABASE IF NOT EXISTS gpswox_traccar CHARACTER SET utf8 COLLATE utf8_unicode_ci;

FLUSH PRIVILEGES;
