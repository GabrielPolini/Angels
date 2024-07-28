<?php
include 'db_config.php';
session_start();

//Coloca segurança na página
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}
// Obtenha o ID do usuário logado
$user_id = $_SESSION['user_id']; // Substitua com a forma apropriada de obter o ID do usuário

// Dados da ação
$action = "Acessou página";
$action_details = "Usuário acessou a página 'Regras e Leis'.";

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
    <title>Regras e Leis</title>
    <link rel="stylesheet" href="styles.css">
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

        header .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        .rules-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .rules h2 {
            font-size: 1.5em;
            margin-top: 20px;
        }

        .rules ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .rules ul li {
            margin-bottom: 10px;
        }

        .fines h2 {
            font-size: 1.5em;
            margin-top: 20px;
        }

        .fines ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .fines ul li {
            margin-bottom: 10px;
        }

        .back-button {
            background-color: #0f0;
            border: none;
            color: #000;
            padding: 10px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 5px;
            display: block;
            width: 150px;
            text-align: center;
            text-decoration: none;
            margin: 20px auto;
        }

        .back-button:hover {
            background-color: #00cc00;
        }
    </style>
</head>
<body>
    <header>
        <img src="../angels4php/Aodmcls.webp" alt="Logo do Clube" class="logo">
        <h1 class="glitch">Regras e Leis</h1>
    </header>
    <div class="rules-container">
        <section class="rules">
            <h2>Uso de Veículos</h2>
            <p>É proibido comparecer ao clube de carro, exceto se solicitado pela presidência ou em situações excepcionais. Os membros que necessitarem utilizar carros devem optar por modelos Muscles, alinhados com a identidade do clube.</p>

            <h2>Posse de Moto Custom</h2>
            <p>Para integrar o clube, seja como membro do bar até a presidência, é obrigatório possuir uma moto custom.</p>

            <h2>O Clube (Local)</h2>
            <p>Sempre que visitar o bar ou outras instalações do clube, deve ir com sua moto custom.</p>

            <h2>Uso do Colete</h2>
            <p>O colete do clube deve ser usado em todas as ocasiões e não deve ser removido em hipótese alguma, salvo exceções previamente aprovadas pela presidência.</p>

            <h2>Ajuda Mútua</h2>
            <p>É imperativo que todos os membros ajudem seus irmãos de clube sempre que necessário. A fraternidade e o apoio mútuo são pilares do nosso grupo.</p>

            <h2>Imagem do Clube</h2>
            <p>Manter e defender a imagem do clube é uma responsabilidade de todos. Comportamentos que prejudiquem a reputação do clube não serão tolerados.</p>

            <h2>Sigilo e Confidencialidade</h2>
            <p>Todos os segredos e informações do clube devem permanecer estritamente dentro do clube. Não devem ser compartilhados ou divulgados a quem não seja membro, seja dentro ou fora do clube. A quebra desta regra será tratada com extrema seriedade, resultando em punições severas, levando até mesmo a sua morte.</p>
        </section>

        <section class="fines">
            <h2>Multas</h2>
            <p><strong>Chegar de carro no clube sem autorização:</strong> Multa: $200</p>
            <p><strong>Jogar lixo na área do clube:</strong> Multa: $100</p>
            <p><strong>Negar ajuda aos colegas do clube:</strong> Multa: $250 + Reclusão 5 min na prisão do clube</p>
        </section>

        <a href="index.php" class="back-button">Voltar</a>
    </div>
    <script>
        // JavaScript para interatividade adicional, se necessário
    </script>
</body>
</html>
