CREATE DATABASE ql_banhang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ql_banhang;

-- Bảng sản phẩm
CREATE TABLE sanpham (
  masp VARCHAR(10) PRIMARY KEY,
  tensp VARCHAR(100),
  dongia FLOAT,
  soluong INT
);

-- Bảng khách hàng
CREATE TABLE khachhang (
  makh VARCHAR(10) PRIMARY KEY,
  tenkh VARCHAR(100),
  sdt VARCHAR(15)
);

-- Bảng hóa đơn
CREATE TABLE hoadon (
  mahd VARCHAR(10) PRIMARY KEY,
  makh VARCHAR(10),
  ngaylap DATE,
  tongtien FLOAT,
  FOREIGN KEY (makh) REFERENCES khachhang(makh)
);

-- Bảng chi tiết hóa đơn
CREATE TABLE chitiethd (
  mahd VARCHAR(10),
  masp VARCHAR(10),
  soluong INT,
  dongia FLOAT,
  thanhtien FLOAT,
  PRIMARY KEY (mahd, masp),
  FOREIGN KEY (mahd) REFERENCES hoadon(mahd),
  FOREIGN KEY (masp) REFERENCES sanpham(masp)
);
