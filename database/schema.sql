CREATE DATABASE IF NOT EXISTS christian_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE christian_portfolio;
CREATE TABLE IF NOT EXISTS admins (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS projects (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(160) NOT NULL,
 slug VARCHAR(180) NOT NULL UNIQUE,
 category VARCHAR(100) NOT NULL DEFAULT 'Web application',
 summary VARCHAR(500) NOT NULL,
 description TEXT NOT NULL,
 technologies VARCHAR(255) NOT NULL DEFAULT '',
 status ENUM('concept','in_progress','completed') NOT NULL DEFAULT 'in_progress',
 github_url VARCHAR(500) NOT NULL DEFAULT '',
 demo_url VARCHAR(500) NOT NULL DEFAULT '',
 image_path VARCHAR(300) NOT NULL DEFAULT '',
 featured TINYINT(1) NOT NULL DEFAULT 1,
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS messages (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 sender_name VARCHAR(150) NOT NULL,
 sender_email VARCHAR(190) NOT NULL,
 subject VARCHAR(190) NOT NULL,
 message TEXT NOT NULL,
 is_read TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS portfolio_settings (
 setting_key VARCHAR(100) PRIMARY KEY,
 setting_value VARCHAR(500) NOT NULL DEFAULT ''
);
INSERT INTO projects (title,slug,category,summary,description,technologies,status,sort_order) VALUES
('DatscoGo','datscogo','Application','An application project featured in my development portfolio.','Case study in preparation. Project scope, individual contributions, architecture, and verified screenshots will be added after review.','', 'in_progress',1),
('GasGo','gasgo','Web application','A gas-station-focused information system project.','Case study in preparation. Feature descriptions, technical implementation, deployment status, and verified screenshots will be added after review.','PHP, JavaScript, MySQL','in_progress',2),
('TailorTrack','tailortrack','Management system','A tailoring-related management system project.','Case study in preparation. Verified implementation details, contributions, and screenshots will be added after review.','PHP, JavaScript, MySQL','in_progress',3);
