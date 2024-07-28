<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar armas disponíveis no estoque
$sql = "SELECT id, name FROM armas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Arma</title>
</head>
<body>
    <h1>Adicionar Arma</h1>
    <form action="processar_pedido.php" method="POST">
        <label for="arma">Selecione a Arma:</label>
        <select name="arma" id="arma" required>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhuma arma disponível</option>";
            }
            ?>
        </select>
        <input type="submit" value="Adicionar ao Pedido">
    </form>

    <?php $conn->close(); ?>
</body>
</html>
