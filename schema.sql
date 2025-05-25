DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE yeticave;

CREATE TABLE users (
  id  INT unsigned AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(128) NOT NULL,
  email VARCHAR(128) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  contact VARCHAR(255) NOT NULL
) ENGINE=INNODB;

CREATE TABLE categories (
  id INT unsigned AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL UNIQUE
) ENGINE=INNODB;

CREATE TABLE lots (
  id INT unsigned AUTO_INCREMENT PRIMARY KEY,
  user_id INT unsigned NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(128) NOT NULL,
  description TEXT NOT NULL,
  img_url VARCHAR(255) NOT NULL,
  cat_id INT unsigned NOT NULL,
  price INT NOT NULL,
  date_end DATE NOT NULL,
  step_bet INT NOT NULL,
  user_win_id INT unsigned,

  FOREIGN KEY (user_id, user_win_id)
                  REFERENCES users(id),

  FOREIGN KEY (cat_id)
                  REFERENCES categories(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE bets (
  id INT unsigned AUTO_INCREMENT PRIMARY KEY,
  user_id INT unsigned NOT NULL,
  lot_id INT unsigned NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  cost INT NOT NULL,

  FOREIGN KEY (user_id)
                  REFERENCES users(id),

  FOREIGN KEY (lot_id)
                  REFERENCES lots(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;
