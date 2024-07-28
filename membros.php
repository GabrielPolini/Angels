<?php
include 'db_config.php';
session_start();

// Função para registrar a atividade
function logActivity($action, $details, $sectionName) {
    global $conn;
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Desconhecido';
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details, timestamp, section_name) VALUES (?, ?, ?, NOW(), ?)");
    $stmt->bind_param("ssss", $userId, $action, $details, $sectionName);
    $stmt->execute();
    $stmt->close();
}

// Coloca segurança na página
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Obtenha o ID do usuário logado
$user_id = $_SESSION['user_id']; // Substitua com a forma apropriada de obter o ID do usuário

// Dados da ação
$action = "Acessou página";
$action_details = "Usuário acessou a página 'Membros'.";
$section_name = 'Membros';

// Registra a atividade
logActivity($action, $action_details, $section_name);

// Prepare e execute a inserção no banco de dados
$stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, action_details, timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $user_id, $action, $action_details);
$stmt->execute();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membros</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo básico */
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

        .members-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .members-container h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .members-container p {
            margin: 0 0 20px;
        }

        .button-back {
            background-color: #ff0000; /* Vermelho */
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            text-align: center;
            display: block;
            width: 100%;
            text-decoration: none;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .button-back:hover {
            background-color: #cc0000; /* Vermelho mais escuro */
        }

        .add-member-form {
            background-color: #111;
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .add-member-form input, .add-member-form select {
            background-color: #222;
            border: 1px solid #0f0;
            color: #0f0;
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        .add-member-form button {
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

        .add-member-form button:hover {
            background-color: #00cc00;
        }

        /* Estilo específico para links de editar e excluir */
        .members a {
            color: #fff;
            text-decoration: none;
            padding: 0 5px;
        }

        .members a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <img src="./Aodmcls.webp" alt="Logo do Clube" class="logo">
        <h1 class="glitch">Membros</h1>
    </header>
    
    <div class="add-member-form">
        <h2>Adicionar Novo Membro</h2>
        <form action="add_member.php" method="POST">
            <input type="text" name="name" placeholder="Nome" required>
            <select name="role" required>
                <option value="" disabled selected>Selecione o Cargo</option>
                <option value="President">President</option>
                <option value="Vice President">Vice President</option>
                <option value="Enforcer">Enforcer</option>
                <option value="Lieutenant">Lieutenant</option>
                <option value="Sergeant at Arms">Sergeant at Arms</option>
                <option value="Treasurer">Treasurer</option>
                <option value="Secretary">Secretary</option>
                <option value="Road Captain">Road Captain</option>
                <option value="Member">Member</option>
                <option value="Prospect">Prospect</option>
                <option value="Funcionário">Funcionário</option>
            </select>
            <button type="submit">Adicionar</button>
        </form>
    </div>

    <div class="members-container">
        <?php
        include 'db_config.php';

        // Consultar membros
        $result = $conn->query("SELECT * FROM members ORDER BY FIELD(role, 'President', 'Vice President', 'Enforcer', 'Lieutenant', 'Sergeant at Arms', 'Treasurer', 'Secretary', 'Road Captain', 'Member', 'Prospect', 'Funcionário')");

        if (!$result) {
            die("Erro na consulta: " . $conn->error);
        }

        $members_by_role = [];

        while ($row = $result->fetch_assoc()) {
            $members_by_role[$row['role']][] = $row;
        }

        $roles = [
            'President', 'Vice President', 'Enforcer', 'Lieutenant', 'Sergeant at Arms',
            'Treasurer', 'Secretary', 'Road Captain', 'Member', 'Prospect', 'Funcionário'
        ];

        foreach ($roles as $role) {
            echo "<section class='members'>";
            echo "<h2>" . htmlspecialchars($role) . "</h2>";

            if (isset($members_by_role[$role]) && count($members_by_role[$role]) > 0) {
                foreach ($members_by_role[$role] as $member) {
                    echo "<p>" . htmlspecialchars($member['name']) . " ";
                    echo "<a href='edit_member.php?id=" . $member['id'] . "'>Editar</a> ";
                    echo "<a href='delete_member.php?id=" . $member['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este membro?\")'>Excluir</a>";
                    echo "</p>";
                }
            } else {
                echo "<p>Nenhum membro encontrado.</p>";
            }

            echo "</section>";
        }

        $conn->close();
        ?>
        
        <a href="index.php" class="button-back">Voltar para a Página Inicial</a>
    </div>
</body>
</html>
