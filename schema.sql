DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8mb3_general_ci;

USE yeticave;

CREATE TABLE users (
  id  INT unsigned AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name CHAR(128) NOT NULL,
  email CHAR(128) NOT NULL UNIQUE,
  password CHAR(64) NOT NULL,
  contact CHAR(255) NOT NULL
);

CREATE TABLE categories (
  id INT unsigned AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128) NOT NULL UNIQUE
);

CREATE TABLE lots (
  id INT unsigned AUTO_INCREMENT PRIMARY KEY,
  user_id INT unsigned NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name char(128) NOT NULL,
  description TEXT NOT NULL,
  img_url CHAR(255) NOT NULL,
  cat_id INT unsigned NOT NULL,
  price INT NOT NULL,
  date_end DATE NOT NULL,
  step_bet INT NOT NULL,
  user_win_id INT unsigned NOT NULL
);

CREATE TABLE bets (
  id INT unsigned AUTO_INCREMENT PRIMARY KEY,
  user_id INT unsigned NOT NULL,
  lot_id INT unsigned NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  cost INT NOT NULL
);
