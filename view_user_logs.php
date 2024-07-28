<?php
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

// Verifica se o user_id foi passado
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die('ID de usuário inválido.');
}

$user_id = intval($_GET['user_id']);

// Consulta para recuperar os logs do usuário específico
$sql = "SELECT * FROM audit_logs WHERE user_id = ? ORDER BY timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs do Usuário</title>
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
            background: rgba(17, 17, 17, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 255, 0, 0.7);
            color: #0f0;
            border: 1px solid #0f0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 2.2em;
            color: #0f0;
            text-shadow: 0 0 15px #0f0;
            animation: glitch-text 1.5s infinite;
        }

        .button {
            background-color: #333;
            color: #0f0;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #0f0;
            margin-left: 10px;
        }

        .button:hover {
            background-color: #444;
            color: #0f0;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #0f0;
        }

        th, td {
            padding: 12px;
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

        tbody td {
            font-size: 0.9em;
        }

        tbody td a {
            color: #0f0;
            text-decoration: none;
        }

        tbody td a:hover {
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
    <div class="container">
        <header>
            <h1>Logs do Usuário</h1>
            <div>
                <a href="logs.php" class="button">Voltar para Logs</a>
            </div>
        </header>
        <main>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ação</th>
                        <th>Detalhes</th>
                        <th>Timestamp</th>
                        <th>Seção</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['action']); ?></td>
                                <td><?php echo htmlspecialchars($row['details']); ?></td>
                                <td><?php echo $row['timestamp']; ?></td>
                                <td><?php echo htmlspecialchars($row['section_name']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum log encontrado para este usuário.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
