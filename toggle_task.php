<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $current_status = $_POST['current_status'];
    
    $new_status = $current_status ? 0 : 1;
    
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = :status WHERE id = :id");
    $stmt->execute([
        ':status' => $new_status,
        ':id' => $task_id
    ]);
}

header('Location: index.php');
exit;
?>