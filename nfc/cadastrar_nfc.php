<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
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

    <title>Cadastrar cartão | PulseLog</title>

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

            <div class="nfc-icon">
                NFC
            </div>

            <h1>
                Cadastrar cartão
            </h1>

            <p class="nfc-description">

                Cadastre um cartão NFC para utilizá-lo
                no registro dos seus episódios.

            </p>

            <div class="nfc-info">

                <h2>
                    Como funciona?
                </h2>

                <p>
                    Ao cadastrar o cartão, o PulseLog irá gerar
                    um código único para ele.
                </p>

                <p>
                    Depois, esse código será utilizado na URL
                    gravada no cartão NFC.
                </p>

            </div>

            <form
                action="salvar_tag.php"
                method="post"
                class="nfc-form"
            >

                <button
                    type="submit"
                    class="nfc-button"
                >
                    Cadastrar cartão
                </button>

            </form>

            <a
                href="../dashboard/index.php"
                class="back-link"
            >
                Voltar para o dashboard
            </a>

        </section>

    </main>

</body>

</html>