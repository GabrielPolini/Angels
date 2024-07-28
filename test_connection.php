<?php
include 'db_config.php';

if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}
echo "Conexão com o banco de dados estabelecida com sucesso!";
?>
