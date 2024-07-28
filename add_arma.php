<?php
include 'db_config.php'; // Inclui as configurações de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Insere a arma na tabela armas
    $sql = "INSERT INTO armas (name, price, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $name, $price, $quantity);

    if ($stmt->execute()) {
        echo "Arma adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar arma: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Arma</title>
</head>
<body>
    <form action="adicionar_arma.php" method="post">
        <label for="name">Nome da Arma:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="price">Preço da Arma:</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <br>
        <label for="quantity">Quantidade:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br>
        <input type="submit" value="Adicionar Arma">
    </form>
</body>
</html>
