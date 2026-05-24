-- ============================================
-- Om Sai Travels - Bus Reservation Database
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS om_sai_travels CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE om_sai_travels;

-- Routes table
DROP TABLE IF EXISTS routes;
CREATE TABLE routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origin VARCHAR(80) NOT NULL,
    destination VARCHAR(80) NOT NULL,
    bus_name VARCHAR(120) NOT NULL,
    bus_type ENUM('AC Sleeper','Non-AC Sleeper','AC Seater','Non-AC Seater','Volvo Multi-Axle') NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    duration VARCHAR(20) NOT NULL,
    fare DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL DEFAULT 40,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bookings table
DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pnr VARCHAR(12) NOT NULL UNIQUE,
    route_id INT NOT NULL,
    passenger_name VARCHAR(100) NOT NULL,
    passenger_age INT NOT NULL,
    passenger_gender ENUM('Male','Female','Other') NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    travel_date DATE NOT NULL,
    seats_booked INT NOT NULL DEFAULT 1,
    seat_numbers VARCHAR(120) NOT NULL,
    total_fare DECIMAL(10,2) NOT NULL,
    status ENUM('Confirmed','Cancelled') NOT NULL DEFAULT 'Confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Admins table
DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact messages
DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============== Seed Data ================
-- Admin login: username = admin , password = admin@123
-- password = admin@123  (bcrypt)
INSERT INTO admins (username, password_hash, name) VALUES
('admin', '$2y$10$Z3KLH70T03XGYFrySPh.1uIEBYxxMxI/vI4G2q3//qgy2OWpPGJGu', 'Om Sai Admin');

-- Sample Maharashtra routes
INSERT INTO routes (origin, destination, bus_name, bus_type, departure_time, arrival_time, duration, fare, total_seats) VALUES
('Pune', 'Parbhani', 'Om Sai Express',     'AC Sleeper',       '21:00:00', '05:30:00', '8h 30m', 850.00, 40),
('Pune', 'Parbhani', 'Sai Krupa Travels',  'Non-AC Sleeper',   '20:30:00', '05:00:00', '8h 30m', 650.00, 40),
('Parbhani', 'Pune', 'Om Sai Express',     'AC Sleeper',       '20:00:00', '04:30:00', '8h 30m', 850.00, 40),
('Mumbai', 'Pune',   'Sai Volvo',          'Volvo Multi-Axle', '07:00:00', '10:30:00', '3h 30m', 450.00, 45),
('Pune', 'Mumbai',   'Sai Volvo',          'Volvo Multi-Axle', '18:00:00', '21:30:00', '3h 30m', 450.00, 45),
('Pune', 'Aurangabad','Sai Shirdi Express','AC Seater',        '08:00:00', '13:00:00', '5h 00m', 550.00, 40),
('Pune', 'Nashik',   'Sai Travels',        'AC Seater',        '09:00:00', '13:00:00', '4h 00m', 400.00, 40),
('Pune', 'Kolhapur', 'Sai Volvo',          'Volvo Multi-Axle', '10:00:00', '14:30:00', '4h 30m', 500.00, 45),
('Pune', 'Lonar',  'Om Sai Travels',     'Non-AC Seater',    '11:00:00', '15:00:00', '4h 00m', 380.00, 40),
('Pune', 'Latur',    'Sai Krupa Travels',  'AC Sleeper',       '22:00:00', '05:00:00', '7h 00m', 700.00, 40),
('Mumbai','Aurangabad','Sai Shirdi Express','AC Sleeper',      '22:30:00', '05:30:00', '7h 00m', 750.00, 40),
('Mumbai','Nashik',  'Sai Travels',        'AC Seater',        '06:30:00', '10:30:00', '4h 00m', 450.00, 40),
('Nashik','Nagpur',  'Om Sai Express',     'AC Sleeper',       '21:00:00', '08:00:00', '11h 00m',950.00, 40),
('Aurangabad','Nagpur','Sai Krupa Travels','Non-AC Sleeper',   '20:00:00', '05:00:00', '9h 00m', 780.00, 40),
('Pune', 'Nagpur',   'Sai Volvo',          'Volvo Multi-Axle', '19:00:00', '08:00:00', '13h 00m',1250.00,45);
