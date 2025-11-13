DROP TABLE IF EXISTS books;

CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  published_year INT,
  genre VARCHAR(100),
  stock INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_title (title),
  KEY idx_author (author)
);

TRUNCATE TABLE books;

INSERT INTO books (title, author, published_year, genre, stock) VALUES
-- Cổ tích / truyền thuyết Việt Nam
('Thạch Sanh','Dân gian Việt Nam',NULL,'Truyện cổ tích',5),
('Tấm Cám','Dân gian Việt Nam',NULL,'Truyện cổ tích',5),
('Cây tre trăm đốt','Dân gian Việt Nam',NULL,'Truyện cổ tích',5),
('Sọ Dừa','Dân gian Việt Nam',NULL,'Truyện cổ tích',5),
('Con Rồng Cháu Tiên','Dân gian Việt Nam',NULL,'Truyền thuyết',6),
('Sự tích trầu cau','Dân gian Việt Nam',NULL,'Truyện cổ tích',5),
('Sự tích chú Cuội','Dân gian Việt Nam',NULL,'Truyện cổ tích',4),
('Thánh Gióng','Dân gian Việt Nam',NULL,'Truyền thuyết',6),
('Sự tích dưa hấu','Dân gian Việt Nam',NULL,'Truyền thuyết',6),

-- Thiếu nhi quốc tế (ghi đơn giản)
('Cô bé quàng khăn đỏ','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',7),
('Ba chú heo con','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',7),
('Cô bé Lọ Lem','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',6),
('Bạch Tuyết và bảy chú lùn','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',6),
('Nàng tiên cá','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',6),
('Hoàng tử ếch','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',5),
('Aladdin và cây đèn thần','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',8),
('Alibaba và 40 tên cướp','Tổng hợp thiếu nhi',NULL,'Truyện thiếu nhi',8),

-- Văn học/thiếu nhi Việt Nam hiện đại (đơn giản)
('Dế Mèn phiêu lưu ký','Tô Hoài',NULL,'Thiếu nhi',10),
('Cho tôi xin một vé đi tuổi thơ','Nguyễn Nhật Ánh',NULL,'Thiếu nhi',9),
('Mắt biếc','Nguyễn Nhật Ánh',NULL,'Văn học',4),
('Cô gái đến từ hôm qua','Nguyễn Nhật Ánh',NULL,'Văn học',5),
('Kính vạn hoa (Tập 1)','Nguyễn Nhật Ánh',NULL,'Thiếu nhi',6),

-- Truyện ngắn/tiểu thuyết Việt Nam (điển hình)
('Lão Hạc','Nam Cao',NULL,'Truyện ngắn',4),
('Chí Phèo','Nam Cao',NULL,'Truyện ngắn',3),
('Hai đứa trẻ','Thạch Lam',NULL,'Truyện ngắn',4),
('Vợ nhặt','Kim Lân',NULL,'Truyện ngắn',3),
('Tắt đèn','Ngô Tất Tố',NULL,'Văn học',2),

-- Một mục tổng hợp
('Truyện ngắn – Tuyển tập','Nhiều tác giả',2020,'Văn học',6);
