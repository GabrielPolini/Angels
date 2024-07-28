<?php
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

// Defina as permissões
$allowed_users = ['Daemon', 'Sarah', 'admin'];

// Verifique se o usuário está logado e tem permissões
$can_clear_logs = isset($_SESSION['username']) && in_array($_SESSION['username'], $allowed_users);

// Consulta para recuperar os logs do banco de dados
$sql = "SELECT * FROM logs ORDER BY timestamp DESC";
$result = $conn->query($sql);

// Limpar logs se solicitado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $can_clear_logs) {
    $delete_sql = "DELETE FROM logs";
    if ($conn->query($delete_sql) === TRUE) {
        $message = "Logs limpos com sucesso.";
    } else {
        $message = "Erro ao limpar logs: " . $conn->error;
    }
    // Recarregar a lista de logs após a limpeza
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Atividade</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #0f0;
            background-color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: rgba(17, 17, 17, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
            color: #0f0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
            color: #0f0;
            text-shadow: 0 0 10px #0f0;
            animation: glitch-text 1.5s infinite;
        }

        .button {
            background-color: #333;
            color: #0f0;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: 1px solid #0f0;
            margin-left: 10px;
        }

        .button:hover {
            background-color: #444;
            color: #0f0;
        }

        .clear-logs-button {
            background-color: #f00; /* Fundo vermelho */
            border: 1px solid #c00; /* Borda vermelha escura */
            color: #fff; /* Texto branco */
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .clear-logs-button:hover {
            background-color: #d00; /* Vermelho mais escuro ao passar o mouse */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #0f0;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #222;
        }

        tbody tr:nth-child(even) {
            background-color: #111;
        }

        tbody tr:hover {
            background-color: #333;
        }

        .message {
            margin: 10px 0;
            font-size: 1em;
            color: #0f0;
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
    <div class="container">
        <header>
            <h1>Logs de Atividade</h1>
            <div>
                <a href="index.php" class="button">Voltar para o Index</a>
                <a href="logout.php" class="button">Logout</a>
                <?php if ($can_clear_logs): ?>
                    <form method="POST" style="display: inline;">
                        <button type="submit" class="clear-logs-button">Limpar Logs</button>
                    </form>
                <?php endif; ?>
            </div>
        </header>
        <main>
            <?php if (isset($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Ação</th>
                        <th>Detalhes</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($row['action']); ?></td>
                                <td><?php echo htmlspecialchars($row['details']); ?></td>
                                <td><?php echo $row['timestamp']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum log encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
<?php
$conn->close();
?>
