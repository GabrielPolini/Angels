<?php 
session_start();
include 'db_config.php'; // Inclua o arquivo de configuração do banco de dados

// Adiciona um novo item ao estoque
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_item'])) {
        $item_name = $_POST['item_name'];
        $quantity = $_POST['quantity'];
        $stmt = $conn->prepare("INSERT INTO bar_stock (item_name, quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $item_name, $quantity);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para evitar o reenvio do formulário
        exit();
    }

    // Atualiza um item do estoque
    if (isset($_POST['update_item'])) {
        $id = $_POST['id'];
        $item_name = $_POST['item_name'];
        $quantity = $_POST['quantity'];
        $stmt = $conn->prepare("UPDATE bar_stock SET item_name = ?, quantity = ? WHERE id = ?");
        $stmt->bind_param("sii", $item_name, $quantity, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para evitar o reenvio do formulário
        exit();
    }

    // Remove um item do estoque
    if (isset($_POST['remove_item'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM bar_stock WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']); // Redireciona para evitar o reenvio do formulário
        exit();
    }
}

// Consulta para recuperar os itens do estoque do bar
$sql = "SELECT * FROM bar_stock ORDER BY last_updated DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque do Bar</title>
    <style>
        /* Seu estilo permanece o mesmo */
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #0f0;
            background-color: #000;
            margin: 0;
            padding: 0;
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

        .logout-button {
            background-color: #333;
            color: #0f0;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: 1px solid #0f0;
        }

        .logout-button:hover {
            background-color: #444;
            color: #0f0;
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

        .form-input {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #0f0;
            border-radius: 5px;
            background-color: #222;
            color: #0f0;
        }

        .form-button {
            padding: 10px;
            background-color: #0f0;
            border: none;
            color: #000;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
        }

        .form-button:hover {
            background-color: #0c0;
        }

        .edit-button {
            background-color: #ff0;
            color: #000;
        }

        .edit-button:hover {
            background-color: #cc0;
        }

        .remove-button {
            background-color: #f00;
            color: #fff;
        }

        .remove-button:hover {
            background-color: #c00;
        }

        .edit-form {
            display: none;
        }

        @keyframes glitch-text {
            0% {
                text-shadow: 0 0 5px #0f0;
            }
            20% {
                text-shadow: 0 0 10px #0f0, 0 0 20px #0f0;
            }
            40% {
                text-shadow: 0 0 5px #0f0, 0 0 15px #0f0;
            }
            60% {
                text-shadow: 0 0 10px #0f0, 0 0 25px #0f0;
            }
            80% {
                text-shadow: 0 0 5px #0f0, 0 0 10px #0f0;
            }
            100% {
                text-shadow: 0 0 10px #0f0;
            }
        }
    </style>
    <script>
        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Estoque do Bar</h1>
            <a href="index.php" class="logout-button">Voltar</a>
        </header>
        
        <form class="add-item-form" method="post">
            <input type="text" name="item_name" placeholder="Nome do Item" required class="form-input">
            <input type="number" name="quantity" placeholder="Quantidade" required class="form-input">
            <button type="submit" name="add_item" class="form-button">Adicionar Item</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome do Item</th>
                    <th>Quantidade</th>
                    <th>Última Atualização</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                            <td>
                                <button type="button" class="form-button edit-button" onclick="toggleEditForm(<?php echo $row['id']; ?>)">Editar</button>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="remove_item" class="form-button remove-button" onclick="return confirm('Tem certeza que deseja remover este item?')">Remover</button>
                                </form>
                                <form id="edit-form-<?php echo $row['id']; ?>" class="edit-form" method="post" style="display:none;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="text" name="item_name" value="<?php echo htmlspecialchars($row['item_name']); ?>" class="form-input" placeholder="Nome do Item">
                                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" class="form-input" placeholder="Quantidade">
                                    <button type="submit" name="update_item" class="form-button edit-button">Atualizar</button>
                                    <button type="button" class="form-button" onclick="toggleEditForm(<?php echo $row['id']; ?>)">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhum item encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
