<?php
session_start();
include 'db_config.php';

// Defina usuários autorizados para visualizar o estoque
$authorized_users = ['Daemon', 'Jacob', 'Elijah', 'Ethan', 'Alice', 'Hyugo', 'Dylan', 'Ezekiel', 'Otto', 'Philip', 'Sarah'];

function check_access($page) {
    global $authorized_users;

    // Verifica se o usuário está logado
    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }

    $username = $_SESSION['username'];

    // Verifica se o usuário tem permissão para acessar a página
    if ($page === 'estoque' && !in_array($username, $authorized_users)) {
        header('Location: index.php');
        exit();
    }
}
?>
