<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description'] ?? '');
    
    if (empty($title)) {
        header('Location: edit_task.php?id=' . $task_id . '&error=empty_title&title=' . urlencode($title) . '&description=' . urlencode($description));
        exit;
    }
    
    $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':id' => $task_id
    ]);
}

header('Location: index.php');
exit;
?>