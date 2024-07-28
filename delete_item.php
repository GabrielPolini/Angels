<?php
include 'db_config.php';

// Verificar se o ID do item foi fornecido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar e executar a consulta SQL para excluir o item
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Redirecionar para a página de controle de estoque
        header("Location: estoque.php");
        exit();
    } else {
        echo "Erro ao excluir item: " . $stmt->error;
    }

    // Fechar a instrução e a conexão
    $stmt->close();
} else {
    die("ID inválido.");
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
