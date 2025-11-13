<?php
// functions.php

// ===== Cấu hình DB (đổi cho phù hợp môi trường của trẫm) =====
$DB_HOST = 'localhost';
$DB_NAME = 'k9tin';          // Có thể đổi thành notes_db
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHAR = 'utf8mb4';

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHAR}";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Không kết nối được CSDL: ' . htmlspecialchars($e->getMessage()));
}

// ===== Flash message đơn giản =====
function set_flash($type, $msg){
    $_SESSION['__flash'] = ['type'=>$type, 'msg'=>$msg];
}
function get_flash(){
    if (!empty($_SESSION['__flash'])) {
        $f = $_SESSION['__flash'];
        unset($_SESSION['__flash']);
        return $f;
    }
    return null;
}

// ===== CRUD =====
function create_note(PDO $pdo, string $title, string $content, string $label=''): void {
    $sql = "INSERT INTO notes(title, content, label) VALUES(:t, :c, NULLIF(:l,''))";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t'=>$title, ':c'=>$content, ':l'=>$label]);
}

function update_note(PDO $pdo, int $id, string $title, string $content, string $label=''): void {
    $sql = "UPDATE notes SET title=:t, content=:c, label=NULLIF(:l,''), updated_at=NOW() WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t'=>$title, ':c'=>$content, ':l'=>$label, ':id'=>$id]);
}

function delete_note(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}

function get_notes(PDO $pdo, string $q='', string $label=''): array {
    $where = [];
    $params = [];
    if ($q !== '') {
        $where[] = "(title LIKE :kw OR content LIKE :kw OR label LIKE :kw)";
        $params[':kw'] = '%' . $q . '%';
    }
    if ($label !== '') {
        $where[] = "label = :label";
        $params[':label'] = $label;
    }
    $sql = "SELECT id, title, content, label, created_at, updated_at FROM notes";
    if ($where) $sql .= " WHERE " . implode(' AND ', $where);
    $sql .= " ORDER BY updated_at DESC, created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_all_labels(PDO $pdo): array {
    $stmt = $pdo->query("SELECT DISTINCT label FROM notes WHERE label IS NOT NULL AND label<>'' ORDER BY label");
    $labels = [];
    foreach ($stmt as $row) {
        $labels[] = $row['label'];
    }
    return $labels;
}
