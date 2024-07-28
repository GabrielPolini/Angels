<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
</head>
<body>
    <h2>Painel de Administração</h2>
    <a href="admin_add_user.php">Adicionar Novo Usuário</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
