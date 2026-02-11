database.sql

CREATE DATABASE IF NOT EXISTS pengaduan_sekolah;
USE pengaduan_sekolah;

CREATE TABLE Siswa (
    nis INT(10) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10) NOT NULL
);

CREATE TABLE Admin (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Kategori (
    id_kategori INT(5) AUTO_INCREMENT PRIMARY KEY,
    ket_kategori VARCHAR(30) NOT NULL
);

CREATE TABLE Aspirasi (
    id_aspirasi INT(5) AUTO_INCREMENT PRIMARY KEY,
    nis INT(10),
    id_kategori INT(5),
    lokasi VARCHAR(50),
    keterangan VARCHAR(255),
    status ENUM('Menunggu', 'Proses', 'Selesai') DEFAULT 'Menunggu',
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nis) REFERENCES Siswa(nis),
    FOREIGN KEY (id_kategori) REFERENCES Kategori(id_kategori)
);

-- Seed Data
INSERT INTO Kategori (ket_kategori) VALUES ('Sarana'), ('Prasarana'), ('Lainnya');

-- Sample Admin (password: admin123)
INSERT INTO Admin (username, password) VALUES ('admin', '$2y$10$uTzw/rC1.BEq/5IG2MclO.YHF1JWyIapzdpZUpgsdB2fJZ2dH636O');

-- Sample Siswa
INSERT INTO Siswa (nis, nama, kelas) VALUES (12345, 'Budi Santoso', 'XII RPL 1');