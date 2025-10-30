DROP TABLE IF EXISTS `products`;

CREATE TABLE products (
   id           BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
   type         ENUM('DienThoai','Sach') NOT NULL,
   ten          VARCHAR(191)             NOT NULL,
   gia_vnd      INT UNSIGNED             NOT NULL,
   -- Thuộc tính cho Điện thoại
   hang_sx      VARCHAR(191)             NULL,
   bao_hanh_th  SMALLINT UNSIGNED        NULL,
   -- Thuộc tính cho Sách
   tac_gia      VARCHAR(191)             NULL,
   so_trang     INT UNSIGNED             NULL,

   created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   INDEX idx_type (type),
   INDEX idx_ten (ten)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO products
(type, ten, gia_vnd, hang_sx, bao_hanh_th, tac_gia, so_trang)
VALUES
-- Điện thoại
('DienThoai','iPhone 15 Pro Max 256GB',32990000,'Apple',12,NULL,NULL),
('DienThoai','Samsung Galaxy A35 5G 8/256', 7990000,'Samsung',18,NULL,NULL),
('DienThoai','Xiaomi Redmi Note 13 Pro 8/256',9490000,'Xiaomi',12,NULL,NULL),
('DienThoai','OPPO Reno11 F 5G 8/256',     7490000,'OPPO',18,NULL,NULL),
('DienThoai','Vivo V30 12/256',            10990000,'vivo',12,NULL,NULL),
('DienThoai','Nokia G42 5G 6/128',          4690000,'Nokia',12,NULL,NULL),
-- Sách
('Sach','Lập trình PHP hiện đại',          95000, NULL,NULL,'Nguyễn Văn A',320),
('Sach','Cấu trúc dữ liệu & Giải thuật',  129000, NULL,NULL,'Lê Minh B', 480),
('Sach','Clean Code (bản dịch)',          199000, NULL,NULL,'Robert C. Martin',464),
('Sach','Thiết kế cơ sở dữ liệu',         159000, NULL,NULL,'Trần Minh C',380);
