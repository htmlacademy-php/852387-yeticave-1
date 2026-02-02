DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_general_ci;

USE yeticave;

CREATE TABLE users (
    id INT AUTO_INCREMENT,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    contact VARCHAR(255) NOT NULL,

    PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE categories (
    id INT AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL UNIQUE,
    code VARCHAR(128) NOT NULL UNIQUE,

    PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE lots (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(128) NOT NULL,
    description TEXT,
    img_url VARCHAR(255) NOT NULL,
    price DECIMAL,
    date_end DATE NOT NULL,
    step_bet INT NOT NULL,
    user_win_id INT,
    cat_id INT NOT NULL,

    PRIMARY KEY (id),

    FOREIGN KEY (user_id)
        REFERENCES users (id),

    FOREIGN KEY (user_win_id)
        REFERENCES users(id),

    FOREIGN KEY (cat_id)
        REFERENCES categories(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE bets (
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    lot_id INT NOT NULL,
    date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cost DECIMAL,

    PRIMARY KEY (id),

    FOREIGN KEY (user_id)
        REFERENCES users(id),

    FOREIGN KEY (lot_id)
        REFERENCES lots(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;
