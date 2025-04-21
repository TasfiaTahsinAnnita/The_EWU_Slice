CREATE DATABASE domino_pizza;
USE domino_pizza;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) DEFAULT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Stores Table
CREATE TABLE stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(50),
    zip_code VARCHAR(10),
    delivery_zones TEXT,
    operating_hours VARCHAR(50)
);

-- Menu Items Table
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT,
    name VARCHAR(100) NOT NULL,
    category ENUM('main', 'sides', 'desserts') DEFAULT 'main',
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 100,
    FOREIGN KEY (store_id) REFERENCES stores(id)
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    store_id INT,
    total_amount DECIMAL(10, 2),
    status ENUM('pending', 'preparing', 'cooking', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (store_id) REFERENCES stores(id)
);

-- Order Items Table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_item_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- Vouchers Table
CREATE TABLE vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    code VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    expiry_date DATE NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Reviews Table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    user_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Delivery Zones Table
CREATE TABLE delivery_zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT,
    zone_name VARCHAR(100),
    min_order_amount DECIMAL(10, 2),
    delivery_fee DECIMAL(10, 2),
    FOREIGN KEY (store_id) REFERENCES stores(id)
);

-- Sample Data
INSERT INTO stores (name, city, zip_code, operating_hours) 
VALUES 
('Domino\'s Dhaka', 'Dhaka', '1205', '10 AM - 10 PM'),
('Domino\'s Chittagong', 'Chittagong', '4000', '10 AM - 9 PM');

INSERT INTO menu_items (store_id, name, category, price, stock) 
VALUES 
(1, 'Pepperoni Pizza', 'main', 500.00, 50),
(1, 'Margherita Pizza', 'main', 450.00, 60),
(1, 'Garlic Bread', 'sides', 150.00, 100),
(1, 'Chicken Wings', 'sides', 200.00, 80),
(1, 'Chocolate Lava Cake', 'desserts', 200.00, 30),
(1, 'Soft Drink', 'desserts', 50.00, 200),
(2, 'BBQ Chicken Pizza', 'main', 520.00, 40),
(2, 'Cheese Sticks', 'sides', 180.00, 90);

INSERT INTO users (username, email, password, contact_number) 
VALUES 
('john_doe', 'john@example.com', '$2y$10$Q8k7vWj5gZ5vX8k3vZ5vX8k3vZ5vX8k3vZ5vX8k3vZ5vX8k3vZ5v', '1234567890'),
('jane_smith', 'jane@example.com', '$2y$10$Q8k7vWj5gZ5vX8k3vZ5vX8k3vZ5vX8k3vZ5vX8k3vZ5vX8k3vZ5v', '0987654321');

INSERT INTO orders (user_id, store_id, total_amount, status, created_at) 
VALUES 
(1, 1, 650.00, 'delivered', '2025-03-15 10:00:00'),
(1, 1, 500.00, 'preparing', '2025-03-16 12:00:00');

INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
VALUES 
(1, 1, 1, 500.00),
(1, 3, 1, 150.00),
(2, 1, 1, 500.00);

INSERT INTO vouchers (user_id, code, amount, expiry_date) 
VALUES 
(1, 'DOMINO2025', 100.00, '2025-12-31'),
(1, 'PIZZA20', 20.00, '2025-06-30'),
(2, 'WELCOME10', 10.00, '2025-12-31');

INSERT INTO delivery_zones (store_id, zone_name, min_order_amount, delivery_fee) 
VALUES 
(1, 'Downtown Dhaka', 300.00, 50.00),
(1, 'Uttara', 400.00, 70.00),
(2, 'Chittagong Central', 350.00, 60.00);

INSERT INTO reviews (order_id, user_id, rating, comment) 
VALUES 
(1, 1, 4, 'Pizza was great, but delivery was a bit late.');