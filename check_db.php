<?php
require_once 'config/database.php';
$stmt = $pdo->query("SELECT id, title_en, image FROM news");
while ($row = $stmt->fetch()) {
    echo "ID: {$row['id']}, Title: {$row['title_en']}, Image: {$row['image']}\n";
}
?>