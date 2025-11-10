<?php

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $host = 'localhost';
    $dbname = 'k9tin';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function ensureSchema(): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS mini_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  tag   VARCHAR(50)  NOT NULL DEFAULT '',
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SQL;
    db()->exec($sql);
}

function seedSampleDataIfEmpty(): void {
    $count = (int)db()->query("SELECT COUNT(*) FROM mini_posts")->fetchColumn();
    if ($count > 0) return;

    $samples = [
        ['Nhật ký hồng pastel', 'life', "Ngày đầu thử mini blog ^^\nHôm nay trời đẹp, mình uống trà hoa hồng và học PHP."],
        ['Góc học tập mộng mơ', 'study', "Sắp xếp lại bàn học: sổ tay, bút màu, đèn vàng ấm.\nThử kỹ thuật pomodoro 25-5 nè!"],
        ['Một chút cảm xúc', 'love', "Gửi tớ của tương lai: hãy luôn dịu dàng với chính mình 💗"],
    ];
    $stmt = db()->prepare("INSERT INTO mini_posts (title, tag, content) VALUES (?,?,?)");
    foreach ($samples as $s) $stmt->execute($s);
}

function createPost(string $title, string $content, string $tag=''): int {
    $stmt = db()->prepare("INSERT INTO mini_posts (title, tag, content) VALUES (:t,:g,:c)");
    $stmt->execute([':t'=>$title, ':g'=>$tag, ':c'=>$content]);
    return (int)db()->lastInsertId();
}

function updatePost(int $id, string $title, string $content, string $tag=''): void {
    $stmt = db()->prepare("UPDATE mini_posts SET title=:t, tag=:g, content=:c WHERE id=:id");
    $stmt->execute([':t'=>$title, ':g'=>$tag, ':c'=>$content, ':id'=>$id]);
}

function deletePost(int $id): void {
    $stmt = db()->prepare("DELETE FROM mini_posts WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}

function getPostById(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM mini_posts WHERE id=:id");
    $stmt->execute([':id'=>$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function getPosts(string $q=''): array {
    if ($q === '') {
        $stmt = db()->query("SELECT * FROM mini_posts ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    $like = '%' . str_replace(['\\','%','_'], ['\\\\','\\%','\\_'], $q) . '%';
    $sql = "SELECT * FROM mini_posts
            WHERE title LIKE :kw1 OR content LIKE :kw2 OR tag LIKE :kw3
            ORDER BY id DESC";
    $stmt = db()->prepare($sql);
    $stmt->execute([':kw1'=>$like, ':kw2'=>$like, ':kw3'=>$like]);
    return $stmt->fetchAll();
}
