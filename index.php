<?php
include 'db_config.php'; 
session_start(); // Inicie a sessão

// Função para registrar a atividade
function logActivity($action, $details) {
    global $conn;
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Desconhecido';
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details, timestamp, section_name) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->bind_param("ssss", $userId, $action, $details, $sectionName);
    $sectionName = 'Página Inicial';
    $stmt->execute();
    $stmt->close();
}

// Coloca segurança na página
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Recupera o nome do usuário da sessão, se disponível
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Visitante';

// Lista de usuários autorizados a ver a opção "Pedido Armas" e outras opções específicas
$authorized_users = ['Daemon', 'admin', 'Jacob', 'Elijah', 'Ethan', 'Alice', 'Hyugo', 'Dylan', 'Ezekiel', 'Otto', 'Philip', 'Sarah'];

// Define se o usuário está autorizado a ver todas as opções
$is_authorized = in_array($username, $authorized_users);

// Define se o usuário tem permissão para ver a opção "Membros"
$is_members_authorized = in_array($username, ['Daemon', 'admin', 'Sarah']);

// Registrar o acesso à página inicial
logActivity('Acesso à Página', 'Usuário acessou a página inicial.');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            color: #0f0;
            background-color: #000;
            overflow-x: hidden;
        }

        /* Estilos para o vídeo de fundo */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        header {
            background-color: rgba(17, 17, 17, 0.8);
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #0f0;
            position: relative;
            z-index: 1;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        }

        header .logo {
            max-width: 150px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }

        header .logo:hover {
            transform: scale(1.1);
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
            animation: glitch 1s infinite;
        }

        @keyframes glitch {
            0% {
                text-shadow: 2px 0 red, -2px 0 blue;
            }
            25% {
                text-shadow: -2px 0 red, 2px 0 blue;
            }
            50% {
                text-shadow: 2px 0 red, -2px 0 blue;
            }
            75% {
                text-shadow: -2px 0 red, 2px 0 blue;
            }
            100% {
                text-shadow: 2px 0 red, -2px 0 blue;
            }
        }

        .welcome-container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: rgba(17, 17, 17, 0.8);
            border: 2px solid #0f0;
            border-radius: 10px;
            position: relative;
            z-index: 1;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .welcome-container h2 {
            font-size: 2em;
            margin-bottom: 20px;
            border-bottom: 1px solid #0f0;
            padding-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            z-index: 2;
        }

        .menu a {
            color: #0f0;
            text-decoration: none;
            font-size: 1.2em;
            border: 1px solid #0f0;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            display: block;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .menu a i {
            margin-right: 8px;
        }

        .menu a:hover {
            background-color: #0f0;
            color: #000;
            transform: translateY(-3px);
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.5);
        }

        .logout-button {
            background-color: #ff0000;
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .logout-button:hover {
            background-color: #cc0000;
            transform: scale(1.05);
        }

        .notification {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 1.1em;
        }

        .notification.success {
            background-color: #dff0d8;
            color: #3c763d;
        }

        .notification.error {
            background-color: #f2dede;
            color: #a94442;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2em;
            }

            .welcome-container h2 {
                font-size: 1.5em;
            }

            .menu a {
                font-size: 1em;
            }

            .logout-button {
                font-size: 0.9em;
                padding: 8px;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background-color: rgba(17, 17, 17, 0.9);
            margin: 15% auto;
            padding: 20px;
            border: 2px solid #0f0;
            width: 80%;
            max-width: 600px;
            text-align: center;
            border-radius: 10px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="background-video.mp4" type="video/mp4">
    </video>
    
    <header>
        <img src="Aodmcls.webp" alt="Logo" class="logo">
        <h1>Bem-vindo ao Angels MC</h1>
    </header>

    <div class="welcome-container">
        <h2>Bem-vindo, <?php echo $username; ?>!</h2>

        <div class="menu">
            <a href="estoque.php"><i class="fas fa-warehouse"></i> Estoque</a>
            <a href="bar.php"><i class="fas fa-glass-whiskey"></i> Estoque do Bar</a>
            <?php if ($is_authorized): ?>
                <a href="armas.php"><i class="fas fa-gun"></i> Pedido de Armas</a>
            <?php endif; ?>
            <?php if ($is_members_authorized): ?>
                <a href="membros.php"><i class="fas fa-users"></i> Membros</a>
            <?php endif; ?>
            <a href="logs.php"><i class="fas fa-list"></i> Logs</a>
        </div>
        
        <a href="logout.php" class="logout-button"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Modal de Boas-Vindas -->
    <div id="welcomeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Boas-Vindas ao Angels MC!</h2>
            <p>Tudo o que você ver aqui, fica aqui!, <?php echo $username; ?>.</p>
        </div>
    </div>

    <script>
        // Script para modal de boas-vindas
        window.onload = function() {
            var modal = document.getElementById("welcomeModal");
            var span = document.getElementsByClassName("close")[0];

            modal.style.display = "block";

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
