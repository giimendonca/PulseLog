<?php

include '../includes/conn.php';


// 1. Verifica se veio por POST

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../dashboard/index.php");

    exit();

}


// 2. Recebe os dados

$token = $_POST['token_nfc'] ?? '';

$intensidade = $_POST['intensidade'] ?? '';

$observacoes = trim($_POST['observacoes'] ?? '');


// 3. Verifica o token

if ($token === '') {

    die("Registro inválido.");

}


// 4. Verifica a intensidade

if (!in_array($intensidade, ['1', '2', '3', '4', '5'], true)) {

    die("Intensidade inválida.");

}

$intensidade = (int) $intensidade;


// 5. Atualiza a crise usando o token

$sql = "UPDATE crises
        SET intensidade = ?,
            observacoes = ?,
            token_nfc = NULL
        WHERE token_nfc = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Erro ao preparar a atualização.");

}

$stmt->bind_param(
    "iss",
    $intensidade,
    $observacoes,
    $token
);


if (!$stmt->execute()) {

    $stmt->close();

    die("Não foi possível salvar as informações.");

}


// Verifica se alguma crise foi encontrada

if ($stmt->affected_rows === 0) {

    $stmt->close();

    die("Registro não encontrado ou token inválido.");

}

$stmt->close();


// 6. Mostra mensagem de sucesso

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>PulseLog | Informações salvas</title>

    <link
        rel="stylesheet"
        href="../assets/css/nfc.css"
    >

</head>

<body>

    <main class="nfc-container">

        <div class="nfc-logo">
            Pulse<span>Log</span>
        </div>

        <section class="nfc-card">

            <div class="success-icon">
                ✓
            </div>

            <h1>
                Informações salvas
            </h1>

            <p class="nfc-description">

                As informações do episódio foram
                registradas com sucesso.

            </p>

            <a
                href="../dashboard/index.php"
                class="nfc-button"
            >
                Ir para o dashboard
            </a>

        </section>

    </main>

</body>

</html>