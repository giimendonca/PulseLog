<?php

include '../includes/conn.php';

$token = $_GET['token'] ?? '';

if ($token === '') {
    die("Registro inválido.");
}   

// Busca a crise

$sql = "SELECT id, inicio, fim
        FROM crises
        WHERE token_nfc = ?
        AND fim IS NOT NULL";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Erro ao preparar a consulta.");

}

$stmt->bind_param(
    "s",
    $token
);

$stmt->execute();

$result = $stmt->get_result();

$crise = $result->fetch_assoc();

$stmt->close();


// Verifica se a crise existe

if (!$crise) {

    die("Registro não encontrado.");

}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Registrar informações | PulseLog</title>

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

            <h1>
                Como foi o episódio?
            </h1>

            <p class="nfc-description">

                Registre algumas informações sobre
                o episódio que acabou de terminar.

            </p>


            <form
                action="salvar_dados.php"
                method="post"
                class="nfc-form"
            >

                <input
                    type="hidden"
                    name="token_nfc"
                    value="<?= htmlspecialchars($token) ?>"
                >


                <div class="form-group">

                    <label for="intensidade">
                        Intensidade
                    </label>

                    <select
                        id="intensidade"
                        name="intensidade"
                        required
                    >

                        <option value="">
                            Selecione
                        </option>

                        <option value="1">
                            1 - Muito leve
                        </option>

                        <option value="2">
                            2 - Leve
                        </option>

                        <option value="3">
                            3 - Moderada
                        </option>

                        <option value="4">
                            4 - Forte
                        </option>

                        <option value="5">
                            5 - Muito forte
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="observacoes">
                        Observações
                    </label>

                    <textarea
                        id="observacoes"
                        name="observacoes"
                        rows="5"
                        maxlength="1000"
                        placeholder="Registre alguma observação sobre o episódio..."
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="nfc-button"
                >
                    Salvar informações
                </button>

            </form>


            <a
                href="../dashboard/index.php"
                class="back-link"
            >
                Voltar ao dashboard
            </a>

        </section>

    </main>

</body>

</html>