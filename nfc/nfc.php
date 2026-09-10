<?php

include '../includes/conn.php';


// 1. Recebe o código do cartão

$codigo = trim($_GET['codigo'] ?? '');


// 2. Verifica se o código foi enviado

if ($codigo === '') {

    die("Cartão NFC inválido.");
}


// 3. Procura o cartão e o usuário dono dele

$sql = "SELECT
            nfc_tags.id,
            nfc_tags.usuario_id,
            usuarios.nome
        FROM nfc_tags
        INNER JOIN usuarios
            ON usuarios.id = nfc_tags.usuario_id
        WHERE nfc_tags.codigo = ?
        AND nfc_tags.ativo = TRUE";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("s", $codigo);

$stmt->execute();

$result = $stmt->get_result();

$tag = $result->fetch_assoc();

$stmt->close();


// 4. Verifica se o cartão existe

if (!$tag) {

    die("Cartão NFC não encontrado ou inativo.");
}


$usuario_id = $tag['usuario_id'];

$nome = $tag['nome'];


// 5. Procura um episódio que ainda não terminou

$sql = "SELECT id, inicio
        FROM crises
        WHERE usuario_id = ?
        AND fim IS NULL
        ORDER BY inicio DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("i", $usuario_id);

$stmt->execute();

$result = $stmt->get_result();

$crise = $result->fetch_assoc();

$stmt->close();


// 6. Se existe episódio aberto, finaliza

if ($crise) {

    $crise_id = $crise['id'];

    $token = bin2hex(random_bytes(32));

    $sql = "UPDATE crises
        SET fim = NOW(), token_nfc = ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $token, $crise_id);
    $stmt->execute();
    $stmt->close();

    header("Location: dados.php?token=" . urlencode($token));
    exit();

    // 7. Caso contrário, inicia um novo episódio

} else {

    $sql = "INSERT INTO crises
            (usuario_id, inicio)
            VALUES (?, NOW())";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        die("Erro ao preparar o registro.");
    }

    $stmt->bind_param("i", $usuario_id);

    $stmt->execute();

    $stmt->close();

    $acao = "iniciada";
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>PulseLog | NFC</title>

    <link
        rel="stylesheet"
        href="../assets/css/nfc.css">

</head>

<body>

    <main class="nfc-container">

        <div class="nfc-logo">
            Pulse<span>Log</span>
        </div>

        <section class="nfc-card">

            <?php if ($acao === 'iniciada'): ?>

                <div class="success-icon">
                    ▶
                </div>

                <h1>
                    Episódio iniciado
                </h1>

                <p class="nfc-description">

                    O registro foi iniciado com sucesso.

                </p>

                <div class="nfc-info">

                    <h2>
                        Registro ativo
                    </h2>

                    <p>
                        O PulseLog está registrando
                        o horário de início.
                    </p>

                    <p>
                        Toque novamente no cartão
                        quando quiser finalizar o registro.
                    </p>

                </div>

            <?php else: ?>

                <div class="success-icon">
                    ✓
                </div>

                <h1>
                    Episódio finalizado
                </h1>

                <p class="nfc-description">

                    O registro foi encerrado com sucesso.

                </p>

                <div class="nfc-info">

                    <h2>
                        Registro encerrado
                    </h2>

                    <p>
                        Agora você poderá informar a intensidade
                        e as observações desse episódio.
                    </p>

                </div>

            <?php endif; ?>


            <a
                href="../dashboard/index.php"
                class="nfc-button">
                Ir para o dashboard
            </a>

        </section>

    </main>

</body>

</html>