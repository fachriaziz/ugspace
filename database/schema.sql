-- ============================================
-- UGSPACE DATABASE SCHEMA
-- ============================================

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    npm VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_npm (npm),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rooms Table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    capacity INT NOT NULL DEFAULT 30,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (active),
    INDEX idx_capacity (capacity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    date DATE NOT NULL,
    start_hour TINYINT NOT NULL,
    end_hour TINYINT NOT NULL,
    purpose VARCHAR(500) NOT NULL,
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    INDEX idx_booking_code (booking_code),
    INDEX idx_user (user_id),
    INDEX idx_room_date (room_id, date),
    INDEX idx_status (status),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DUMMY DATA
-- ============================================

-- Dummy Rooms
INSERT INTO rooms (code, name, location, capacity) VALUES
('E432', 'Lab Komputer E1', 'Gedung 4 Lantai 3', 40),
('E433', 'Lab Komputer E2', 'Gedung 4 Lantai 3', 40),
('H331', 'Lab Praktikum H1', 'Gedung 3 Lantai 3', 35),
('H332', 'Lab Praktikum H2', 'Gedung 3 Lantai 3', 35),
('G221', 'Kelas G1', 'Gedung 2 Lantai 2', 50),
('G222', 'Kelas G2', 'Gedung 2 Lantai 2', 50),
('D411', 'Ruang Seminar', 'Gedung 4 Lantai 1', 100),
('D412', 'Ruang Meeting', 'Gedung 4 Lantai 1', 20),
('F101', 'Aula Utama', 'Gedung 1 Lantai 1', 200),
('F201', 'Ruang Diskusi', 'Gedung 2 Lantai 1', 15),
('F301', 'Lab Multimedia', 'Gedung 3 Lantai 1', 30),
('F302', 'Studio Rekaman', 'Gedung 3 Lantai 2', 10);