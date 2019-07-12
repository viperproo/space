CREATE TABLE `users`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(20) NOT NULL,
  `password` VARCHAR(200) NOT NULL,
  `name` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bans`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `users_id` INT NOT NULL,
  `time` DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `logins`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `users_id` INT NOT NULL,
  `ip` VARCHAR(60) NOT NULL,
  `time` DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `moons`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(40) NOT NULL,
  `diameter_(km)` FLOAT DEFAULT NULL,
  `mass_(earth_mass)` FLOAT DEFAULT NULL,
  `planets_id` INT DEFAULT NULL,
  `distance_from_planet_(km)` FLOAT DEFAULT NULL,
  `description` VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `moons_columns_names`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lang` VARCHAR(4) NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `diameter_(km)` VARCHAR(20) NOT NULL,
  `mass_(earth_mass)` VARCHAR(20) NOT NULL,
  `planets_id` VARCHAR(20) NOT NULL,
  `distance_from_planet_(km)` VARCHAR(35) NOT NULL,
  `description` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `planets`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(40) NOT NULL,
  `diameter_(km)` FLOAT DEFAULT NULL,
  `mass_(earth_mass)` FLOAT DEFAULT NULL,
  `stars_id` INT DEFAULT NULL,
  `distance_from_star_(au)` FLOAT DEFAULT NULL,
  `description` VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `planets_columns_names`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lang` VARCHAR(4) NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `diameter_(km)` VARCHAR(20) NOT NULL,
  `mass_(earth_mass)` VARCHAR(20) NOT NULL,
  `stars_id` VARCHAR(20) NOT NULL,
  `distance_from_star_(au)` VARCHAR(35) NOT NULL,
  `description` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `stars`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(40) NOT NULL,
  `diameter_(km)` FLOAT DEFAULT NULL,
  `mass_(sun_mass)` FLOAT DEFAULT NULL,
  `distance_from_earth_(ly)` FLOAT DEFAULT NULL,
  `galaxies_id` INT DEFAULT NULL,
  `description` VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `stars_columns_names`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lang` VARCHAR(4) NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `diameter_(km)` VARCHAR(20) NOT NULL,
  `mass_(sun_mass)` VARCHAR(20) NOT NULL,
  `distance_from_earth_(ly)` VARCHAR(35) NOT NULL,
  `galaxies_id` VARCHAR(20) DEFAULT NULL,
  `description` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `galaxies`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(40) NOT NULL,
  `size_(ly)` FLOAT DEFAULT NULL,
  `distance_from_earth_(ly)` FLOAT DEFAULT NULL,
  `description` VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `galaxies_columns_names`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lang` VARCHAR(4) NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `size_(ly)` VARCHAR(35) NOT NULL,
  `distance_from_earth_(ly)` VARCHAR(35) NOT NULL,
  `description` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `moons` ADD CONSTRAINT `planets` FOREIGN KEY (`planets_id`) REFERENCES `planets` (`id`);
ALTER TABLE `planets` ADD CONSTRAINT `stars` FOREIGN KEY (`stars_id`) REFERENCES `stars` (`id`);
ALTER TABLE `stars` ADD CONSTRAINT `galaxies` FOREIGN KEY (`galaxies_id`) REFERENCES `galaxies` (`id`);
ALTER TABLE `bans` ADD CONSTRAINT `ban` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
ALTER TABLE `logins` ADD CONSTRAINT `login` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

INSERT INTO `users` (`login`, `password`, `name`) VALUES
  ('admin', '$2y$10$vc8cjVO5H7D8EhXO5EvGkeqD4RwIEc/kJT80Icq0mSm.D4hdny7H2', 'Admin');

INSERT INTO `moons_columns_names` (`lang`, `name`, `diameter_(km)`, `planets_id`, `distance_from_planet_(km)`, `mass_(earth_mass)`, `description`) VALUES
  ('pl', 'Nazwa', 'Średnica_km', 'Najbliższa planeta', 'Odległość od planety_km', 'Masa_mas Ziemi', 'Opis');

INSERT INTO `planets_columns_names` (`lang`, `name`, `diameter_(km)`, `stars_id`, `distance_from_star_(au)`, `mass_(earth_mass)`, `description`) VALUES
  ('pl', 'Nazwa', 'Średnica_km', 'Najbliższa gwiazda', 'Odległość od gwiazdy_au', 'Masa_mas Ziemi', 'Opis');

INSERT INTO `stars_columns_names` (`lang`, `name`, `diameter_(km)`, `distance_from_earth_(ly)`, `mass_(sun_mass)`, `galaxies_id`, `description`) VALUES
  ('pl', 'Nazwa', 'Średnica_km', 'Odległość od Ziemi_lat świetlnych', 'Masa_mas Słońca', 'Galaktyka', 'Opis');

INSERT INTO `galaxies_columns_names` (`lang`, `name`, `size_(ly)`, `distance_from_earth_(ly)`, `description`) VALUES
  ('pl', 'Nazwa', 'Rozmiar_lat świetlnych', 'Odległość od Ziemi_lat świetlnych', 'Opis');
