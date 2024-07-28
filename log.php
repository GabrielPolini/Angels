<?php
include 'db_config.php';

function writeLog($userId, $action, $details) {
    global $conn; // Usa a conexão mysqli com o banco de dados

    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $action, $details);
    $stmt->execute();
    $stmt->close();
}
?>
