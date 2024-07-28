<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];

    if (!empty($name) && !empty($role)) {
        $stmt = $conn->prepare("INSERT INTO members (name, role) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $role);

        if ($stmt->execute()) {
            header('Location: members.php');
            exit();
        } else {
            echo "Erro ao adicionar membro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

$conn->close();
?>
