-- Active: 1764673028424@@127.0.0.1@3306@smart-wallet
create table incomes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    date DATE NULL
);
create table expenses(
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255) not NULL,
    date DATE NOT NULL
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE incomes ADD user_id INT NOT NULL;
ALTER TABLE expenses ADD user_id INT NOT NULL;

CREATE TABLE cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider VARCHAR(100) NOT NULL,
    card_last4 VARCHAR(4) NOT NULL,
    limit_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    expiry_date DATE NOT NULL,
    is_main TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cards_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);



ALTER TABLE incomes ADD card_id INT NOT NULL;
ALTER TABLE expenses ADD card_id INT NOT NULL;

CREATE TABLE transfer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    sender_card_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_sender
        FOREIGN KEY (sender_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_receiver
        FOREIGN KEY (receiver_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_sender_card
        FOREIGN KEY (sender_card_id) REFERENCES cards(id)
        ON DELETE CASCADE
);
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);
CREATE TABLE category_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    monthly_limit DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    UNIQUE (user_id, category_id)
);

ALTER TABLE expenses
ADD category_id INT NOT NULL;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);


ALTER TABLE expenses
ADD category_id INT NULL;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

UPDATE expenses
SET category_id = (SELECT id FROM categories WHERE name = 'Food' LIMIT 1)
WHERE category_id IS NULL;
ALTER TABLE expenses
MODIFY category_id INT NOT NULL;
SELECT DISTINCT category_id
FROM expenses
WHERE category_id NOT IN (SELECT id FROM categories)
   OR category_id IS NULL;

INSERT INTO categories (name)
VALUES ('Other');
SELECT id FROM categories WHERE name = 'Other';
UPDATE expenses
SET category_id = 6
WHERE category_id NOT IN (SELECT id FROM categories)
   OR category_id IS NULL;
ALTER TABLE expenses
MODIFY category_id INT NOT NULL;
ALTER TABLE expenses
ADD CONSTRAINT fk_expenses_category
FOREIGN KEY (category_id)
REFERENCES categories(id)
ON DELETE RESTRICT;


SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM expenses;
DELETE FROM category_limits;
DELETE FROM categories;

ALTER TABLE categories AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO categories (name) VALUES
('Food'),
('Transport'),
('Rent'),
('Shopping'),
('Health'),
('Internet'),
('Other');
