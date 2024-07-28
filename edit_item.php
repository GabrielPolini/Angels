<?php
include 'db_config.php';

// Verificar se o ID do item foi fornecido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar item para editar
    $result = $conn->query("SELECT * FROM inventory WHERE id = $id");
    if ($result->num_rows === 1) {
        $item = $result->fetch_assoc();
    } else {
        die("ID inválido.");
    }

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];

        // Validar os dados
        if (!empty($name) && is_numeric($quantity) && $quantity >= 0) {
            // Preparar e executar a consulta SQL para atualizar o item
            $stmt = $conn->prepare("UPDATE inventory SET name = ?, quantity = ? WHERE id = ?");
            $stmt->bind_param('sii', $name, $quantity, $id);

            if ($stmt->execute()) {
                // Redirecionar para a página de controle de estoque
                header("Location: estoque.php");
                exit();
            } else {
                echo "Erro ao atualizar item: " . $stmt->error;
            }

            // Fechar a instrução e a conexão
            $stmt->close();
        } else {
            echo "Por favor, insira um nome e uma quantidade válidos.";
        }
    }

} else {
    die("ID inválido.");
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item</title>
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

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        .container {
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            background-color: #222;
            border: 2px solid #0f0;
            border-radius: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form input {
            margin: 10px 0;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #0f0;
            border-radius: 5px;
            background-color: #111;
            color: #0f0;
        }

        form button {
            margin-top: 10px;
            background-color: #0f0;
            border: none;
            color: #000;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
        }

        form button:hover {
            background-color: #00cc00;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #0f0;
            text-decoration: none;
            font-size: 1em;
            padding: 10px;
            border: 1px solid #0f0;
            border-radius: 5px;
            background-color: #111;
        }

        a:hover {
            background-color: #222;
        }
    </style>
</head>
<body>
    <header>
        <h1>Editar Item</h1>
    </header>
    <div class="container">
        <form action="edit_item.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
            <button type="submit">Atualizar Item</button>
        </form>
        <a href="estoque.php">Voltar</a>
    </div>
</body>
</html>
