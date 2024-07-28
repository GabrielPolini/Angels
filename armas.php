<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venda de Armas</title>
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
            max-width: 100px; /* Ajuste o tamanho da imagem conforme necessário */
            height: auto;
        }

        header h1 {
            margin: 10px 0;
            font-size: 2em;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
        }

        .cardapio {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #222;
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 15px;
            width: 200px;
            text-align: center;
        }

        .card h3 {
            margin: 0 0 10px 0;
        }

        .card p {
            margin: 5px 0;
        }

        .card button {
            background-color: #0f0;
            border: none;
            color: #000;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
        }

        .card button:hover {
            background-color: #00cc00;
        }

        .carrinho {
            margin-top: 30px;
            background-color: #111;
            border: 2px solid #0f0;
            padding: 15px;
            border-radius: 10px;
        }

        .carrinho h2 {
            margin: 0 0 10px 0;
        }

        .carrinho ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .carrinho ul li {
            margin: 5px 0;
        }

        .carrinho .button-container {
            display: flex;
            gap: 10px; /* Espaçamento entre os botões */
        }

        .carrinho button.clear-cart {
            background-color: #ff0000; /* Vermelho */
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            flex: 1;
        }

        .carrinho button.clear-cart:hover {
            background-color: #cc0000; /* Vermelho mais escuro */
        }

        .generate-pdf-button {
            background-color: #0000ff; /* Azul */
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            flex: 1;
        }

        .generate-pdf-button:hover {
            background-color: #0000cc; /* Azul mais escuro */
        }

        .add-weapon-form {
            margin-top: 20px;
            background-color: #222;
            border: 2px solid #0f0;
            padding: 15px;
            border-radius: 10px;
        }

        .add-weapon-form select,
        .add-weapon-form input {
            margin: 5px 0;
            padding: 5px;
            font-size: 1em;
            border: 1px solid #0f0;
            border-radius: 5px;
            width: calc(100% - 12px);
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
            margin-top: 20px;
            background-color: #0000ff; /* Azul */
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0000cc; /* Azul mais escuro */
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
    </style>
</head>
<body>
    <header>
        <img src="Aodmcls.webp" alt="Logo">
        <h1>Venda de Armas</h1>
    </header>
    <div class="container">
        <button class="back-button" onclick="window.location.href='index.php'">Voltar à Página Principal</button>

        <div class="cardapio">
            <!-- Cards de Armas -->
        </div>

        <div class="add-weapon-form">
            <h2>Adicionar Arma ao Carrinho</h2>
            <select id="weapon-name" onchange="showNewWeaponInput()">
                <option value="">Selecione uma arma</option>
                <?php
                $sql = "SELECT name FROM inventory";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row["name"]) . "'>" . htmlspecialchars($row["name"]) . "</option>";
                    }
                }
                ?>
                <option value="other">Outra arma</option>
            </select>
            <input type="text" id="new-weapon-name" placeholder="Nome da nova arma" style="display: none;">
            <input type="number" id="weapon-price" placeholder="Preço da Arma">
            <input type="number" id="weapon-quantity" placeholder="Quantidade" min="1" value="1">
            <button onclick="addCustomWeapon()">Adicionar ao Carrinho</button>
        </div>

        <div class="carrinho">
            <h2>Carrinho</h2>
            <ul id="cart-items"></ul>
            <p>Total: $<span id="total-price">0.00</span></p>
            <div class="button-container">
                <button class="clear-cart" onclick="clearCart()">Limpar Carrinho</button>
                <button class="generate-pdf-button" onclick="generatePDF()">Gerar Extrato em PDF</button>
            </div>
        </div>

        <!-- Tabela de Itens do Estoque -->
        <h2>Itens do Estoque</h2>
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Nome da Arma</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consultar e exibir itens do estoque
                $inventoryResult = $conn->query("SELECT name, quantity FROM inventory");

                if ($inventoryResult->num_rows > 0) {
                    while ($item = $inventoryResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum item em estoque</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        let cart = [];
        let userName = prompt("Nome do cliente:"); // Solicita o nome do usuário

        function addToCart(name, price, quantity) {
            const existingItemIndex = cart.findIndex(item => item.name === name);
            if (existingItemIndex > -1) {
                cart[existingItemIndex].quantity += quantity;
            } else {
                cart.push({ name, price, quantity });
            }
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const totalPriceElem = document.getElementById('total-price');
            cartItems.innerHTML = '';
            let totalPrice = 0;

            cart.forEach(item => {
                totalPrice += item.price * item.quantity;
                cartItems.innerHTML += `<li>${item.name} - $${item.price.toFixed(2)} x ${item.quantity}</li>`;
            });

            totalPriceElem.textContent = totalPrice.toFixed(2);
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
        }

        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Adicionando título e logo
            doc.setFontSize(18);
            doc.setFont("courier", "bold");
            doc.text('Angels MC', 105, 20, { align: 'center' });

            // Adicionando a logo
            doc.addImage('Aodmcls.webp', 'WEBP', 10, 10, 30, 30);

            // Definindo o título da nota fiscal
            doc.setFontSize(16);
            doc.text('Nota Fiscal de Venda', 10, 50);

            // Definindo o nome do cliente
            doc.setFontSize(12);
            doc.text(`Cliente: ${userName}`, 10, 60);

            // Linha separadora
            doc.setDrawColor(0, 255, 0); // Verde
            doc.line(10, 65, 200, 65);

            // Adicionando itens do carrinho em formato de tabela
            let yPosition = 70;
            const columnWidth = 190; // Largura das colunas
            doc.setFontSize(12);
            doc.text('Item', 10, yPosition);
            doc.text('Preço Unitário', 80, yPosition);
            doc.text('Quantidade', 130, yPosition);
            doc.text('Total', 170, yPosition);

            doc.setDrawColor(0, 255, 0); // Verde
            doc.line(10, yPosition + 5, 200, yPosition + 5);

            yPosition += 10;

            cart.forEach(item => {
                doc.text(item.name, 10, yPosition);
                doc.text(`$${item.price.toFixed(2)}`, 80, yPosition);
                doc.text(item.quantity.toString(), 130, yPosition);
                doc.text(`$${(item.price * item.quantity).toFixed(2)}`, 170, yPosition);
                yPosition += 10;
            });

            // Adicionando total
            doc.setFontSize(14);
            doc.text('Total Geral:', 10, yPosition);
            doc.text(`$${document.getElementById('total-price').textContent}`, 170, yPosition);

            // Linha separadora
            doc.setDrawColor(0, 255, 0); // Verde
            doc.line(10, yPosition + 5, 200, yPosition + 5);

            // Adicionando rodapé
            yPosition += 15;
            doc.setFontSize(10);
           
            doc.save('nota_fiscal.pdf');
        }

        function showNewWeaponInput() {
            const weaponSelect = document.getElementById('weapon-name');
            const newWeaponInput = document.getElementById('new-weapon-name');
            if (weaponSelect.value === 'other') {
                newWeaponInput.style.display = 'block';
            } else {
                newWeaponInput.style.display = 'none';
            }
        }

        function addCustomWeapon() {
            const weaponSelect = document.getElementById('weapon-name');
            const selectedWeapon = weaponSelect.value;
            const newWeaponInput = document.getElementById('new-weapon-name');
            const weaponPrice = parseFloat(document.getElementById('weapon-price').value);
            const weaponQuantity = parseInt(document.getElementById('weapon-quantity').value);

            let weaponName;

            if (selectedWeapon === 'other') {
                weaponName = newWeaponInput.value.trim();
                if (!weaponName) {
                    alert('Por favor, insira o nome da nova arma.');
                    return;
                }
            } else {
                weaponName = selectedWeapon;
            }

            if (weaponPrice <= 0 || weaponQuantity <= 0) {
                alert('Por favor, insira um preço e uma quantidade válidos.');
                return;
            }

            addToCart(weaponName, weaponPrice, weaponQuantity);

            // Atualiza o estoque
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_stock.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("weapon_name=" + encodeURIComponent(weaponName) + "&quantity=" + encodeURIComponent(weaponQuantity));

            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // Mensagem de sucesso do servidor
                    // Opcionalmente, recarregue a tabela de estoque
                    // loadInventory();
                } else {
                    console.error("Erro ao atualizar o estoque.");
                }
            };
        }
    </script>
</body>
</html>
