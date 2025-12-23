CREATE DATABASE IF NOT EXISTS student_manager;
USE student_manager;

-- bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- bảng students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_code VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    birthday DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    user_id INT,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (user_name, password, role)
VALUES ('admin', '$2y$12$vM8UUTWWx/6Y71eR0T3vuOUDG.JMtTzu9063ogrjL6eEedu0RaS4i','admin');