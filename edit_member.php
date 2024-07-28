<?php
include 'db_config.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE members SET name = ?, age = ?, role = ? WHERE id = ?");
    $stmt->bind_param('sisi', $name, $age, $role, $id);

    if ($stmt->execute()) {
        header("Location: membros.php");
        exit();
    } else {
        die("Erro ao atualizar membro: " . $stmt->error);
    }
}

// Consultar dados do membro para exibir no formulário
$result = $conn->query("SELECT * FROM members WHERE id = $id");

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$member = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Membro</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1e1e1e;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #4caf50;
        }

        .logo {
            width: 100px;
        }

        .members-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .add-member-form h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #4caf50;
        }

        .add-member-form input, .add-member-form select {
            background-color: #333;
            border: 1px solid #4caf50;
            color: #e0e0e0;
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .add-member-form button {
            background-color: #4caf50;
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .add-member-form button:hover {
            background-color: #45a049;
        }

        .button-back {
            display: block;
            background-color: #f44336;
            color: #fff;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 1em;
        }

        .button-back:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <header>
        <img src="./Aodmcls.webp" alt="Logo do Clube" class="logo">
        <h1>Editar Membro</h1>
    </header>
    
    <div class="members-container">
        <div class="add-member-form">
            <h2>Editar Membro</h2>
            <form action="edit_member.php?id=<?php echo $member['id']; ?>" method="POST">
                <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" placeholder="Nome" required>
                <input type="number" name="age" value="<?php echo htmlspecialchars($member['age']); ?>" placeholder="Idade" required>
                <input type="text" name="role" value="<?php echo htmlspecialchars($member['role']); ?>" placeholder="Cargo" required>
                <button type="submit">Atualizar</button>
            </form>
        </div>
        
        <a href="membros.php" class="button-back">Voltar</a>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
