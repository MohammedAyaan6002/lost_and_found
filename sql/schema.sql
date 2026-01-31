CREATE DATABASE IF NOT EXISTS loyola_lost_and_found CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loyola_lost_and_found;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    role ENUM('student','staff','admin') DEFAULT 'student',
    password_hash VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_type ENUM('lost','found') NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    contact_name VARCHAR(120) NOT NULL,
    contact_email VARCHAR(160) NOT NULL,
    contact_phone VARCHAR(50) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS match_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lost_item_name VARCHAR(150) NOT NULL,
    found_item_name VARCHAR(150) NOT NULL,
    score DECIMAL(5,4) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    channel ENUM('email','sms','in-app') DEFAULT 'email',
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

INSERT INTO items (item_type, item_name, description, location, event_date, contact_name, contact_email, contact_phone, status)
VALUES
('lost', 'Black Backpack', 'Black backpack with Loyola crest, contains calculus notebook.', 'Science Block', '2025-11-20', 'Maria D', 'maria@example.com', '555-1234', 'approved'),
('found', 'Calculator', 'Texas Instruments calculator with stickers.', 'Library - 2nd Floor', '2025-11-21', 'John P', 'john@example.com', '555-6789', 'approved'),
('lost', 'Silver Water Bottle', 'Metal bottle with Loyola sticker.', 'Gym', '2025-11-19', 'Leah F', 'leah@example.com', NULL, 'pending');

