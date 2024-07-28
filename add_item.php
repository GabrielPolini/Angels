<?php
include 'db_config.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];

    // Validar os dados
    if (!empty($name) && is_numeric($quantity) && $quantity > 0) {
        // Preparar e executar a consulta SQL para inserir o item no banco de dados
        $stmt = $conn->prepare("INSERT INTO inventory (name, quantity) VALUES (?, ?)");
        $stmt->bind_param('si', $name, $quantity);

        if ($stmt->execute()) {
            // Redirecionar para a página de controle de estoque
            header("Location: estoque.php");
            exit();
        } else {
            echo "Erro ao adicionar item: " . $stmt->error;
        }

        // Fechar a instrução e a conexão
        $stmt->close();
    } else {
        echo "Por favor, insira um nome e uma quantidade válidos.";
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
