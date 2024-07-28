<?php
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta ao banco de dados
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verificar a senha
        if (password_verify($password, $user['password'])) {
            // Senha correta
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php'); // Redireciona para a página inicial
            exit();
        } else {
            // Senha incorreta
            $error = "Nome de usuário ou senha incorretos.";
        }
    } else {
        // Usuário não encontrado
        $error = "Nome de usuário ou senha incorretos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #000;
            color: #0f0;
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 20px;
            animation: glitch 1s infinite;
        }

        .login-container {
            background-color: #111;
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-container h1 {
            margin: 0 0 20px;
            font-size: 2em;
            position: relative;
            animation: glitch-text 1.5s infinite;
        }

        .login-container input {
            background-color: #222;
            border: 1px solid #0f0;
            color: #0f0;
            padding: 10px;
            margin: 10px 0;
            width: 100%;
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
            width: 100%;
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
            position: relative;
            overflow: hidden;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .messages {
            margin-top: 20px;
            text-align: left;
            font-size: 0.9em;
            white-space: pre-wrap; /* Preserve formatting */
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

        .audio-controls {
            position: fixed;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #111;
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
    </style>
</head>
<body>
    <img src="./Aodmcls.webp" alt="Logo do Clube" class="logo">
    <div class="login-container">
        <h1>Login</h1>
        <form id="login-form" action="login.php" method="POST">
            <input type="text" name="username" placeholder="Nome de Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        
        <div class="messages" id="messages">
            <?php if (isset($error)): ?>
                <script>
                    document.getElementById('messages').innerHTML = <?php echo json_encode($error); ?>;
                </script>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contêiner para o controle de áudio -->
    <div class="audio-controls">
        <audio controls autoplay>
            <source src="musica.mp3" type="audio/mpeg">
            Seu navegador não suporta o elemento de áudio.
        </audio>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio imediato do formulário
            
            const messages = document.getElementById('messages');
            messages.innerHTML = ''; // Limpa mensagens anteriores

            const progressMessages = [
                'Analisando credenciais...',
                'Verificando autenticidade...',
                'Conectando ao servidor...',
                'Autenticação em andamento...',
                'Verificação concluída. Redirecionando...'
            ];

            let messageIndex = 0;
            const interval = setInterval(() => {
                if (messageIndex < progressMessages.length) {
                    messages.textContent = progressMessages[messageIndex];
                    messageIndex++;
                } else {
                    clearInterval(interval);
                    document.getElementById('login-form').submit(); // Envia o formulário após as mensagens
                }
            }, 1000); // Atualiza a cada 1 segundo
        });
    </script>
</body>
</html>
