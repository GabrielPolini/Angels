<?php
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

// Verifica se o ID do log foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido.');
}

$log_id = intval($_GET['id']);

// Consulta para recuperar o log detalhado
$sql = "SELECT * FROM logs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $log_id);
$stmt->execute();
$result = $stmt->get_result();

$log = $result->fetch_assoc();

if (!$log) {
    die('Log não encontrado.');
}
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
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            background-color: rgba(17, 17, 17, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            color: #0f0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #0f0;
            padding-bottom: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 1.8em;
            color: #0f0;
            text-shadow: 0 0 10px #0f0;
        }

        .button {
            background-color: #333;
            color: #0f0;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: 1px solid #0f0;
        }

        .button:hover {
            background-color: #444;
            color: #0f0;
        }

        main {
            padding: 20px;
            border: 1px solid #0f0;
            border-radius: 8px;
            background-color: rgba(0, 0, 0, 0.7);
        }

        main h2 {
            margin-top: 0;
            font-size: 1.5em;
            color: #0f0;
        }

        main p {
            font-size: 1em;
            margin: 10px 0;
        }

        strong {
            color: #0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Detalhes do Log</h1>
            <div>
                <a href="index.php" class="button">Voltar para o Index</a>
                <a href="logs.php" class="button">Voltar para Logs</a>
            </div>
        </header>
        <main>
            <h2>Detalhes do Log ID <?php echo htmlspecialchars($log['id']); ?></h2>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($log['user_id']); ?></p>
            <p><strong>Ação:</strong> <?php echo htmlspecialchars($log['action']); ?></p>
            <p><strong>Detalhes:</strong> <?php echo htmlspecialchars($log['details']); ?></p>
            <p><strong>Timestamp:</strong> <?php echo htmlspecialchars($log['timestamp']); ?></p>
        </main>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
