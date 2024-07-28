<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = $_POST['cart'];
    $userName = $_POST['userName'];
    $cart = json_decode($cart, true);

    if (empty($cart)) {
        echo 'Erro: Carrinho vazio.';
        exit;
    }

    foreach ($cart as $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        // Verifica se a arma está disponível em estoque
        $stmt = $conn->prepare("SELECT quantity FROM inventory WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $inventoryItem = $result->fetch_assoc();

        if (!$inventoryItem) {
            echo "Erro: A arma '$name' não foi encontrada no estoque.";
            exit;
        }

        if ($inventoryItem['quantity'] < $quantity) {
            echo "Erro: Quantidade insuficiente para a arma '$name'.";
            exit;
        }

        // Atualiza o estoque
        $stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE name = ?");
        $stmt->bind_param("is", $quantity, $name);
        $stmt->execute();
    }

    echo 'Pedido processado com sucesso.';
} else {
    echo 'Método de solicitação inválido.';
}
?>
