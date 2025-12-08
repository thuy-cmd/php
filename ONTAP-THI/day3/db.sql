-- Tạo bảng nhân viên
CREATE TABLE IF NOT EXISTS nhanvien (
  manv INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Tạo bảng sản phẩm (inventory)
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  manv INT NOT NULL,                -- người tạo / quản lý sản phẩm
  sku VARCHAR(60) NOT NULL UNIQUE,  -- mã sản phẩm
  name VARCHAR(255) NOT NULL,
  qty INT NOT NULL DEFAULT 0,
  price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (manv) REFERENCES nhanvien(manv) ON DELETE CASCADE
);
