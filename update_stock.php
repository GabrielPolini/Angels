<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $weaponName = $_POST['weapon_name'];
    $quantity = intval($_POST['quantity']);

    // Atualiza a quantidade no banco de dados
    $sql = "UPDATE inventory SET quantity = quantity - ? WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quantity, $weaponName);
    $stmt->execute();
    $stmt->close();

    echo "Estoque atualizado com sucesso.";
}
?>
