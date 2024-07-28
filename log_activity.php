<?php
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados
session_start(); // Inicie a sessão

// Função para registrar a atividade
function logActivity($action, $details, $sectionName) {
    global $conn;

    // Obter o ID do usuário da sessão, se disponível
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Desconhecido';

    // Preparar a declaração SQL
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, timestamp, section_name) VALUES (?, ?, ?, NOW(), ?)");

    // Verificar se a preparação da declaração foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação da declaração: ' . htmlspecialchars($conn->error));
    }

    // Vincular os parâmetros
    $stmt->bind_param("ssss", $userId, $action, $details, $sectionName);

    // Executar a declaração
    if (!$stmt->execute()) {
        die('Erro na execução da declaração: ' . htmlspecialchars($stmt->error));
    }

    // Fechar a declaração
    $stmt->close();
}

// Coloque segurança na página
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Log da atividade
logActivity('Acesso à Página', 'Usuário acessou a página de membros.', 'Página de Membros');
?>
