<?php
$servername = "127.0.0.1";
$username = "root"; // Usuário padrão do MySQL no XAMPP
$password = ""; // Senha padrão do MySQL no XAMPP, geralmente vazia
$dbname = "angels_mc";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}
?>
