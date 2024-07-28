<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validar dados do usuário
    if (!empty($username) && !empty($password) && in_array($role, ['admin', 'user'])) {
        // Hash da senha
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Inserir o novo usuário no banco de dados
        $stmt = $pdo->prepare("INSERT INTO members (username, password, role) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $hashed_password, $role])) {
            $message = "Usuário adicionado com sucesso.";
        } else {
            $message = "Erro ao adicionar usuário.";
        }
    } else {
        $message = "Por favor, preencha todos os campos corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
</head>
<body>
    <h2>Adicionar Novo Usuário</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    <form method="post" action="">
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Função:</label>
        <select id="role" name="role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <br>
        <button type="submit">Adicionar Usuário</button>
    </form>
    <a href="admin_dashboard.php">Voltar ao Painel de Administração</a>
</body>
</html>
