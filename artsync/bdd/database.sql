CREATE DATABASE IF NOT EXISTS artsync_db;

USE artsync_db;

-- Tabela para armazenar os dados dos usuários (artistas)
-- Atende ao requisito RF01
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Armazenará a senha de forma segura (hash)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela para armazenar os links de redes sociais do artista
-- Atende aos requisitos RF05 e RF11
CREATE TABLE social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    platform VARCHAR(50) NOT NULL, -- Ex: 'spotify', 'instagram', 'youtube'
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabela para armazenar os itens do portfólio (fotos, vídeos)
-- Atende ao requisito RF17
CREATE TABLE portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL, -- Caminho para o arquivo de mídia
    media_type ENUM('image', 'video', 'audio') NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabela para agendamentos e lembretes
-- Atende ao requisito RF15
CREATE TABLE schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_title VARCHAR(200) NOT NULL,
    event_date DATETIME NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);