<?php
include 'db_config.php';

header('Content-Type: application/json');

// Consultar armas no estoque
$sql = "SELECT name FROM armas";
$result = $conn->query($sql);

$weapons = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $weapons[] = $row['name'];
    }
}

echo json_encode($weapons);
?>
