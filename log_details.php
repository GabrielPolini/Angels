<?php
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

if (!isset($_GET['id'])) {
    die('ID de log não fornecido.');
}

$log_id = intval($_GET['id']);

// Consulta para recuperar os detalhes do log
$stmt = $conn->prepare("SELECT * FROM audit_logs WHERE id = ?");
$stmt->bind_param("i", $log_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('Log não encontrado.');
}

$log = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Log</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #00ff00;
            background-color: #000000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
            border: 1px solid #00ff00;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #00ff00;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
            animation: glitch-text 1.5s infinite;
        }

        .button {
            background-color: #222;
            color: #00ff00;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #00ff00;
            margin-left: 10px;
            font-size: 0.9em;
        }

        .button:hover {
            background-color: #333;
            color: #00ff00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #00ff00;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #111;
            color: #00ff00;
        }

        tbody tr:nth-child(even) {
            background-color: #1a1a1a;
        }

        tbody tr:hover {
            background-color: #333;
        }

        tbody td {
            font-size: 0.9em;
        }

        tbody td a {
            color: #00ff00;
            text-decoration: none;
        }

        tbody td a:hover {
            text-decoration: underline;
        }

        @keyframes glitch-text {
            0% {
                text-shadow: 0 0 5px #00ff00;
            }
            20% {
                text-shadow: 0 0 10px #00ff00, 0 0 15px #00ff00;
            }
            40% {
                text-shadow: 0 0 15px #00ff00, 0 0 20px #00ff00;
            }
            60% {
                text-shadow: 0 0 20px #00ff00, 0 0 25px #00ff00;
            }
            80% {
                text-shadow: 0 0 25px #00ff00, 0 0 30px #00ff00;
            }
            100% {
                text-shadow: 0 0 30px #00ff00, 0 0 35px #00ff00;
            }
        }

        table {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalhes do Log</h1>
            <div>
                <a href="logs.php" class="button">Voltar para Logs</a>
            </div>
        </header>
        <main>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo htmlspecialchars($log['id']); ?></td>
                </tr>
                <tr>
                    <th>User ID</th>
                    <td><?php echo htmlspecialchars($log['user_id']); ?></td>
                </tr>
                <tr>
                    <th>Ação</th>
                    <td><?php echo htmlspecialchars($log['action']); ?></td>
                </tr>
                <tr>
                    <th>Detalhes da Ação</th>
                    <td><pre><?php echo htmlspecialchars($log['action_details']); ?></pre></td>
                </tr>
                <tr>
                    <th>Timestamp</th>
                    <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                </tr>
            </table>
        </main>
    </div>
</body>
</html>
<?php
$conn->close();
?>
