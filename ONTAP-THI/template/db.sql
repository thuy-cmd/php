-- schema_template.sql
CREATE TABLE IF NOT EXISTS nhanvien (
  manv INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Bảng chung cho các bài (ví dụ: items), có thể dùng cho task/product/note...
CREATE TABLE IF NOT EXISTS items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  manv INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  meta VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (manv) REFERENCES nhanvien(manv) ON DELETE CASCADE
);
