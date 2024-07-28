<?php
// session_check.php

session_start();

// Simulação de tipo de usuário (isto deve vir do seu sistema de autenticação)
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'visitor'; // visitor é um exemplo, ajuste conforme necessário

// Tipo de usuário permitido para visualizar o conteúdo
$allowed_user_types = ['admin', 'manager']; // Exemplo de tipos que podem ver o conteúdo

// Verificar se o usuário tem permissão
if (!in_array($user_type, $allowed_user_types)) {
    echo "Você não tem permissão para acessar esta página.";
    exit; // Impede a execução do restante do código
}
