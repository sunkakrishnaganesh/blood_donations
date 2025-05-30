CREATE DATABASE IF NOT EXISTS blood_donation;
USE blood_donation;

CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    blood_type VARCHAR(10),
    last_donation DATE,
    location VARCHAR(100),
    medications TEXT,
    allergies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
