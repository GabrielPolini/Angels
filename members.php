<?php
include 'db_config.php'; // Inclui a configuração do banco de dados

$sql = "SELECT * FROM members";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Nome: " . $row["name"]. " - Idade: " . $row["age"]. " - Cargo: " . $row["role"]. "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
