-- Create Database
CREATE DATABASE IF NOT EXISTS sparks_snack_bar;
USE sparks_snack_bar;

-- Create Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    delivery_address TEXT NOT NULL,
    special_instructions TEXT,
    delivery_option VARCHAR(50) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Create Menu Items Table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(50) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Customers Table
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100),
    address TEXT,
    total_orders INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Sample Menu Items
INSERT INTO menu_items (category, item_name, description, price) VALUES
('Dry Pancakes', 'Treasure Mix (Small)', 'Small portion of delicious dry pancakes', 30),
('Dry Pancakes', 'Marvel Mix (Medium)', 'Medium portion of delicious dry pancakes', 50),
('Dry Pancakes', 'Glorious Mix (Large)', 'Large portion of delicious dry pancakes', 70),
('Fluffy Mini Pancakes', 'Mini Fluff Frenzy (10 pieces)', '10 pieces of fluffy mini pancakes', 30),
('Fluffy Mini Pancakes', 'Double Delight Bites (20 pieces)', '20 pieces of fluffy mini pancakes', 40),
('Fluffy Mini Pancakes', 'Fluff Frenzy Feast (30 pieces)', '30 pieces of fluffy mini pancakes', 50),
('Extras', 'Syrup (Per Cup)', 'Delicious maple syrup', 10);
