<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #000;
            color: #0f0;
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }

        .registration-container {
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

        .registration-container h1 {
            margin: 0 0 20px;
            font-size: 2em;
            position: relative;
            animation: glitch-text 1.5s infinite;
        }

        .registration-container input, 
        .registration-container select, 
        .registration-container button {
            background-color: #222;
            border: 1px solid #0f0;
            color: #0f0;
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .registration-container button {
            background-color: #0f0;
            border: none;
            color: #000;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
        }

        .registration-container button:hover {
            background-color: #00cc00;
        }

        .login-link a {
            color: #0f0;
            text-decoration: none;
            margin-top: 10px;
            display: block;
        }

        .login-link a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <div class="registration-container">
        <h1 class="glitch">Cadastro</h1>
        <form class="registration-form" action="index.php" method="POST">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required>

            <label for="age">Idade:</label>
            <input type="number" id="age" name="age" required>

            <label for="role">Função:</label>
            <select id="role" name="role" required>
                <option value="">Selecione uma função</option>
                <option value="Funcionario">Funcionário</option>
                <option value="Prospect">Prospect</option>
                <option value="Membro">Membro</option>
                <option value="Secretary">Secretary</option>
                <option value="Sergeant at Arms">Sergeant at Arms</option>
                <option value="Road Captain">Road Captain</option>
                <option value="Treasurer">Treasurer</option>
                <option value="Enforcer">Enforcer</option>
                <option value="Lieutenant">Lieutenant</option>
                <option value="Vice President">Vice President</option>
                <option value="President">President</option>
            </select>

            <button type="submit" class="hack-button">Cadastrar</button>
        </form>
        <div class="login-link">
            <p>Já tem uma conta?</p>
            <a href="login.php" class="hack-button">Login</a>
        </div>
    </div>

    <!-- Adicionar o elemento de áudio -->
    <audio id="background-music" controls autoplay loop style="display:none;">
        <source src="musica.mp3" type="audio/mpeg">
        Seu navegador não suporta o elemento de áudio.
    </audio>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const audio = document.getElementById('background-music');

            if (!localStorage.getItem('audioPlayed')) {
                audio.play();
                localStorage.setItem('audioPlayed', 'true');
            } else {
                audio.play();
            }
        });
    </script>
</body>
</html>
