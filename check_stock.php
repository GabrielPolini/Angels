<?php
include 'db_config.php';

header('Content-Type: application/json');

// Receber os dados da requisição POST
$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$quantity = (int)$data['quantity'];

$response = ['inStock' => false, 'availableQuantity' => 0];

// Prevenir injeção SQL
$stmt = $conn->prepare("SELECT quantity FROM armas WHERE name = ?");
$stmt->bind_param('s', $name);
$stmt->execute();
$result = $stmt->get_result();

if ($item = $result->fetch_assoc()) {
    if ($item['quantity'] >= $quantity) {
        $response['inStock'] = true;
    }
    $response['availableQuantity'] = $item['quantity'];
}

echo json_encode($response);

$conn->close();
?>
