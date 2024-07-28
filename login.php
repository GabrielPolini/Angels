<?php
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados
include 'log.php'; // Inclua a função de logging

// Defina os dados de acesso
$users = [
    'admin' => '159753',
    'Daemon' => '3467',
    'Harry' => '2204',
    'Jacob' => '4721',
    'Elijah' => '6359',
    'Ethan' => '2847',
    'Alice' => '9173',
    'Hyugo' => '0516',
    'Dylan' => '8420',
    'Ezekiel' => '3598',
    'Otto' => '4602',
    'Philip' => '7314',
    'Sarah' => '2985',
    // Adicione mais usuários e senhas conforme necessário
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Verifica se o nome de usuário e a senha estão corretos
    if (isset($users[$input_username]) && $users[$input_username] === $input_password) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = rand(1, 1000); // Gera um ID de usuário aleatório
        $_SESSION['username'] = $input_username;
        $_SESSION['role'] = 'Member'; // Define um papel padrão

        // Registra o log de login com sucesso
        writeLog($_SESSION['user_id'], 'Login', 'Usuário fez login com sucesso: ' . $input_username);

        header('Location: index.php'); // Redireciona para a página inicial
        exit();
    } else {
        $error = 'Nome de usuário ou senha incorretos.';

        // Registra o log de tentativa de login falhada
        writeLog(0, 'Login', 'Tentativa de login falhou para o usuário: ' . $input_username);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Inclua o Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            font-family: 'Courier New', Courier, monospace;
            color: #0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            text-align: center;
        }

        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.5; /* Define a transparência do vídeo */
            z-index: -1; /* Faz o vídeo ficar atrás de todos os outros elementos */
        }

        .content {
            position: relative;
            z-index: 1; /* Garante que o conteúdo fique na frente do vídeo */
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
            animation: glitch 1s infinite;
        }

        .login-container {
            background-color: rgba(17, 17, 17, 0.8); /* Fundo semi-transparente */
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
            margin: 0 auto; /* Centraliza o conteúdo horizontalmente */
            position: relative; /* Necessário para animação */
            animation: move 3s infinite alternate; /* Adiciona a animação */
        }

        .login-container h1 {
            margin: 0 0 20px;
            font-size: 2em;
            animation: glitch-text 1.5s infinite;
        }

        .login-container input {
            background-color: #222;
            border: 1px solid #0f0;
            color: #0f0;
            padding: 10px;
            margin: 10px 0;
            width: calc(100% - 24px); /* Ajusta a largura para a borda */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .login-container button {
            background-color: #0f0;
            border: none;
            color: #000;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            width: calc(100% - 24px); /* Ajusta a largura para a borda */
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .login-container button:hover {
            background-color: #00cc00;
        }

        .login-container a {
            color: #0f0;
            text-decoration: none;
            margin-top: 10px;
            display: block;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .messages {
            margin-top: 20px; /* Espaço acima das mensagens */
            text-align: left;
            font-size: 0.9em;
            white-space: pre-wrap; /* Preserve formatting */
            min-height: 50px; /* Define uma altura mínima para o contêiner de mensagens */
        }

        .footer {
            margin-top: 20px;
            font-size: 0.8em;
            color: #0f0;
        }

        .social-icons a {
            color: #0f0;
            margin: 0 10px;
            font-size: 1.5em;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #00cc00;
        }

        .tooltips {
            position: relative;
            display: inline-block;
        }

        .tooltips .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: #222;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Posiciona o tooltip acima do elemento */
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltips .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #222 transparent transparent transparent;
        }

        .tooltips:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        @keyframes glitch {
            0% {
                transform: translate(0, 0);
            }
            20% {
                transform: translate(-10px, -10px);
            }
            40% {
                transform: translate(10px, 10px);
            }
            60% {
                transform: translate(-10px, 10px);
            }
            80% {
                transform: translate(10px, -10px);
            }
            100% {
                transform: translate(0, 0);
            }
        }

        @keyframes glitch-text {
            0% {
                text-shadow: 0 0 5px #0f0;
            }
            20% {
                text-shadow: 0 0 10px #0f0, 0 0 15px #0f0;
            }
            40% {
                text-shadow: 0 0 15px #0f0, 0 0 20px #0f0;
            }
            60% {
                text-shadow: 0 0 20px #0f0, 0 0 25px #0f0;
            }
            80% {
                text-shadow: 0 0 25px #0f0, 0 0 30px #0f0;
            }
            100% {
                text-shadow: 0 0 30px #0f0, 0 0 35px #0f0;
            }
        }

        @keyframes move {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <video autoplay loop muted class="background-video">
        <source src="fundo.mp4" type="video/mp4">
        Seu navegador não suporta o vídeo de fundo.
    </video>
    <div class="content">
        <img src="Aodmcls.webp" alt="Logo" class="logo">
        <div class="login-container">
            <h1>Login</h1>
            <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" name="username" placeholder="Nome de usuário" required>
                <input type="password" name="password" placeholder="Senha" required>
                <button type="submit">Entrar</button>
            </form>
            <div class="messages" id="messages"></div>
            <a href="#" class="tooltips">
                Esqueceu a senha?
                <span class="tooltiptext">Entre em contato com o administrador.</span>
            </a>
        </div>
        <div class="footer">
            <div class="social-icons">
                <a href="#" class="tooltips"><i class="fab fa-twitter"></i><span class="tooltiptext">Twitter</span></a>
                <a href="#" class="tooltips"><i class="fab fa-facebook"></i><span class="tooltiptext">Facebook</span></a>
                <a href="#" class="tooltips"><i class="fab fa-instagram"></i><span class="tooltiptext">Instagram</span></a>
            </div>
            &copy; 2024 AngelsMC. Todos os direitos reservados.
        </div>
    </div>
    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Impede o envio do formulário
            var form = this;
            var messages = [
                'Connecting to server...',
                'Connection established...',
                'Authenticating user...',
                'Login successful!'
            ];

            var messageContainer = document.getElementById("messages");
            var index = 0;

            function showMessage() {
                if (index < messages.length) {
                    messageContainer.innerHTML += messages[index] + '<br>';
                    index++;
                    setTimeout(showMessage, 1000); // Exibe a próxima mensagem após 1 segundo
                } else {
                    form.submit(); // Envia o formulário após a última mensagem
                }
            }

            messageContainer.innerHTML = ''; // Limpa as mensagens anteriores
            showMessage();
        });
    </script>
</body>
</html>
