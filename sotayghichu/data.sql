-- schema.sql
-- Tạo database + bảng + dữ liệu mẫu cho “Sổ tay ghi chú”

CREATE DATABASE IF NOT EXISTS k9tin
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE k9tin;

CREATE TABLE IF NOT EXISTS notes (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(200) NOT NULL,
  content    TEXT         NOT NULL,
  label      VARCHAR(50)  NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO notes (title, content, label) VALUES
('Ý tưởng UI', 'Dùng tông lam nhạt, card bo góc, padding thoáng, icon nhỏ.', 'design'),
('Checklist học PHP', "- PDO\n- Prepared statements\n- CRUD\n- CSRF token\n- XSS escape", 'study'),
('Việc hôm nay', "1) Viết báo cáo\n2) Gọi điện cô giáo\n3) Ôn Bootstrap 5", 'daily');
