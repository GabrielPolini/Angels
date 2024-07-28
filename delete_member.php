<?php
include 'db_config.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header("Location: membros.php");
    exit();
} else {
    die("Erro ao excluir membro: " . $stmt->error);
}
?>
