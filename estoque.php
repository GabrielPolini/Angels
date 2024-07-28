<?php
include 'db_config.php';
session_start();

// Função para registrar atividades
function logActivity($action, $details = '') {
    global $conn;
    $username = $_SESSION['username'];
    $timestamp = date('Y-m-d H:i:s');
    $query = "INSERT INTO logs (username, action, details, timestamp) VALUES ('$username', '$action', '$details', '$timestamp')";
    $conn->query($query);
}

// Verifique se o usuário está autenticado e tem a permissão necessária
if (!isset($_SESSION['username'])) {
    die("Você precisa estar logado para acessar esta página.");
}

// Verificar se o usuário tem a permissão necessária
$authorizedUsers = ['Ezekiel', 'Daemon', 'Sarah', 'admin'];
$isAuthorized = in_array($_SESSION['username'], $authorizedUsers);

// Consultar itens do estoque
$result = $conn->query("SELECT * FROM inventory");

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

// Registrar visualização da página com detalhes
logActivity('Detalhes', 'Usuário acessou a página de controle de estoque');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estoque</title>
    <style>
        body {
            background-color: #000;
            color: #0f0;
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #111;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #0f0;
        }

        header img {
            max-width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        header h1 {
            margin: 10px 0 0 0;
            font-size: 2em;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .inventory-table th, .inventory-table td {
            border: 2px solid #0f0;
            padding: 10px;
            text-align: center;
        }

        .inventory-table th {
            background-color: #222;
        }

        .inventory-table td {
            background-color: #111;
        }

        .add-weapon-form {
            background-color: #222;
            border: 2px solid #0f0;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: <?php echo $isAuthorized ? 'block' : 'none'; ?>;
        }

        .add-weapon-form input {
            margin: 5px 0;
            padding: 5px;
            font-size: 1em;
            border: 1px solid #0f0;
            border-radius: 5px;
            width: calc(100% - 22px);
        }

        .add-weapon-form button {
            margin-top: 10px;
            background-color: #0f0;
            border: none;
            color: #000;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            width: 100%;
        }

        .add-weapon-form button:hover {
            background-color: #00cc00;
        }

        .back-button {
            background-color: #ff0000;
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #cc0000;
        }

        .remove-button {
            background-color: #ff0000;
            border: none;
            color: #fff;
            padding: 5px;
            cursor: pointer;
            font-size: 0.9em;
            border-radius: 5px;
        }

        .remove-button:hover {
            background-color: #cc0000;
        }

        .edit-button {
            background-color: #ffcc00;
            border: none;
            color: #000;
            padding: 5px;
            cursor: pointer;
            font-size: 0.9em;
            border-radius: 5px;
        }

        .edit-button:hover {
            background-color: #ff9900;
        }

        .total-stock {
            background-color: #222;
            border: 2px solid #0f0;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }

        .calculator {
            background-color: #222;
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            width: 300px;
            margin: 20px auto;
        }

        .calculator h2 {
            margin-top: 0;
            text-align: center;
            color: #0f0;
        }

        #calculator-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #display {
            width: 100%;
            padding: 15px;
            font-size: 2em;
            text-align: right;
            border: 2px solid #0f0;
            border-radius: 5px;
            background-color: #111;
            color: #0f0;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        #buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background-color: #333;
            border: none;
            color: #0f0;
            padding: 15px;
            cursor: pointer;
            font-size: 1.5em;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        button:hover {
            background-color: #0f0;
            color: #000;
        }

        button:active {
            background-color: #0c0;
        }
    </style>

    
</head>
<body>
    <header>
        <img src="Aodmcls.webp" alt="Logo">
        <h1>Controle de Estoque</h1>
    </header>
    <div class="container">
        <!-- Formulário para adicionar novo item -->
        <div class="add-weapon-form">
            <h2>Adicionar Nova Arma ao Estoque</h2>
            <form action="add_item.php" method="POST">
                <input type="text" name="name" placeholder="Nome da Arma" required>
                <input type="number" name="quantity" placeholder="Quantidade" required>
                <button type="submit">Adicionar ao Estoque</button>
            </form>
        </div>

        <!-- Tabela de itens do estoque -->
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Nome da Arma</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Exibir itens do estoque
                while ($item = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                    if ($isAuthorized) {
                        echo "<td>";
                        echo "<a href='edit_item.php?id=" . $item['id'] . "' class='edit-button'>Editar</a> ";
                        echo "<a href='delete_item.php?id=" . $item['id'] . "' class='remove-button' onclick='return confirm(\"Tem certeza que deseja excluir este item?\")'>Remover</a>";
                        echo "</td>";
                    } else {
                        echo "<td>N/A</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Total de itens no estoque -->
        <div class="total-stock">
            Total em Estoque: <span id="total-quantity">
                <?php
                $total_quantity = $conn->query("SELECT SUM(quantity) AS total FROM inventory")->fetch_assoc()['total'];
                echo htmlspecialchars($total_quantity);
                ?>
            </span>
        </div>

        <!-- Seção da Calculadora -->
        <div class="calculator">
            <h2>Calculadora</h2>
            <div id="calculator-container">
                <input type="text" id="display" readonly>
                <div id="buttons">
                    <button onclick="clearDisplay()">C</button>
                    <button onclick="appendToDisplay('7')">7</button>
                    <button onclick="appendToDisplay('8')">8</button>
                    <button onclick="appendToDisplay('9')">9</button>
                    <button onclick="appendToDisplay('/')">/</button>
                    <button onclick="appendToDisplay('4')">4</button>
                    <button onclick="appendToDisplay('5')">5</button>
                    <button onclick="appendToDisplay('6')">6</button>
                    <button onclick="appendToDisplay('*')">*</button>
                    <button onclick="appendToDisplay('1')">1</button>
                    <button onclick="appendToDisplay('2')">2</button>
                    <button onclick="appendToDisplay('3')">3</button>
                    <button onclick="appendToDisplay('-')">-</button>
                    <button onclick="appendToDisplay('0')">0</button>
                    <button onclick="appendToDisplay('.')">.</button>
                    <button onclick="calculateResult()">=</button>
                    <button onclick="appendToDisplay('+')">+</button>
                </div>
            </div>
        </div>

        <!-- Botão para voltar -->
        <button class="back-button" onclick="window.location.href='index.php'">Voltar</button>
    </div>
    <script>
        function clearDisplay() {
            document.getElementById('display').value = '';
        }

        function appendToDisplay(value) {
            document.getElementById('display').value += value;
        }

        function calculateResult() {
            var display = document.getElementById('display');
            try {
                display.value = eval(display.value);
            } catch (e) {
                display.value = 'Erro';
            }
        }
    </script>
</body>
</html>
