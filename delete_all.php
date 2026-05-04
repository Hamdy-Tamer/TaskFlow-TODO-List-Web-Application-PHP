<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM tasks");
    $stmt->execute();
}

header('Location: index.php');
exit;
?>