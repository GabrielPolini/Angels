<?php
header('Content-Type: application/json');
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

if (isset($_GET['id'])) {
    $log_id = intval($_GET['id']);
    $sql = "SELECT * FROM logs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $log_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(null);
    }

    $stmt->close();
}

$conn->close();
?>
