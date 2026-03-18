-- Bases de données GPSWOX
CREATE DATABASE IF NOT EXISTS gpswox_web CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE DATABASE IF NOT EXISTS gpswox_traccar CHARACTER SET utf8 COLLATE utf8_unicode_ci;

-- Permissions utilisateur gpswox sur les deux bases
GRANT ALL PRIVILEGES ON gpswox_web.* TO 'gpswox'@'%';
GRANT ALL PRIVILEGES ON gpswox_traccar.* TO 'gpswox'@'%';
FLUSH PRIVILEGES;
