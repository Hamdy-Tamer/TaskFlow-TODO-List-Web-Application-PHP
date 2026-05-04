<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');
    
    if (empty($title)) {
        header('Location: index.php?error=empty_title&title=' . urlencode($title) . '&description=' . urlencode($description));
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (:title, :description)");
    $stmt->execute([
        ':title' => $title,
        ':description' => $description
    ]);
}

header('Location: index.php');
exit;
?>