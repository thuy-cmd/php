CREATE DATABASE IF NOT EXISTS k9tin;

USE k9tin

CREATE TABLE IF NOT EXISTS mini_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  tag   VARCHAR(50)  NOT NULL DEFAULT '',
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO mini_posts (title, tag, content) VALUES
('Nhật ký hồng pastel', 'life', "Ngày đầu thử mini blog ^^\nHôm nay trời đẹp, mình uống trà hoa hồng và học PHP."),
('Góc học tập mộng mơ', 'study', "Sắp xếp lại bàn học: sổ tay, bút màu, đèn vàng ấm.\nThử kỹ thuật pomodoro 25-5 nè!"),
('Một chút cảm xúc', 'love', "Gửi tớ của tương lai: hãy luôn dịu dàng với chính mình 💗")
