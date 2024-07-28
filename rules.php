<?php
include 'db_config.php'; // Inclui a configuração do banco de dados

$sql = "SELECT * FROM rules";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Título: " . $row["rule_title"]. " - Descrição: " . $row["rule_description"]. "<br>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
