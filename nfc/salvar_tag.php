<?php

session_start();

include '../includes/conn.php';

// 1. Verifica se o usuário está logado

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}


// 2. Pega o ID do usuário logado

$usuario_id = $_SESSION['id'];


// 3. Gera um código aleatório para o cartão

$codigo = strtoupper(bin2hex(random_bytes(6)));


// 4. Verifica se esse código já existe

$sql = "SELECT id FROM nfc_tags WHERE codigo = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("s", $codigo);

$stmt->execute();

$result = $stmt->get_result();


// 5. Se o código já existir, gera outro

if ($result->num_rows > 0) {

    $stmt->close();

    $codigo = strtoupper(bin2hex(random_bytes(6)));

}

$stmt->close();


// 6. Cadastra o cartão

$sql = "INSERT INTO nfc_tags (usuario_id, codigo)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar o cadastro.");
}

$stmt->bind_param(
    "is",
    $usuario_id,
    $codigo
);


// 7. Executa o cadastro

if (!$stmt->execute()) {

    $stmt->close();

    die("Não foi possível cadastrar o cartão.");

}

$stmt->close();


// 8. Mostra o resultado

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cartão cadastrado | PulseLog</title>

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
                Cartão cadastrado!
            </h1>

            <p class="nfc-description">

                Seu cartão foi cadastrado com sucesso
                no PulseLog.

            </p>

            <div class="code-box">

                <span class="code-label">
                    Código do cartão
                </span>

                <strong>
                    <?= htmlspecialchars($codigo) ?>
                </strong>

            </div>

            <div class="url-box">

                <span class="code-label">
                    URL para gravar no NFC
                </span>

                <p>
                    http://SEU-IP/PulseLog/nfc.php?codigo=<?= htmlspecialchars($codigo) ?>
                </p>

            </div>

            <p class="nfc-warning">

                ⚠️ Não grave dados pessoais ou informações
                sobre episódios diretamente no cartão.

            </p>

            <a
                href="../dashboard/index.php"
                class="nfc-button"
            >
                Voltar para o dashboard
            </a>

        </section>

    </main>

</body>

</html>