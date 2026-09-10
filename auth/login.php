<?php

session_start();

if (isset($_SESSION['id'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

$erro = $_GET['erro'] ?? '';
$cadastro = $_GET['cadastro'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login | PulseLog</title>

    <link
        rel="stylesheet"
        href="../assets/css/auth.css">

</head>

<body>

    <main class="auth-container">

        <div class="logo">
            Pulse<span>Log</span>
        </div>

        <section class="auth-card">

            <h1>
                Bem-vindo de volta
            </h1>

            <p class="auth-description">
                Entre na sua conta para acessar seus registros.
            </p>


            <?php if ($erro === 'login'): ?>

                <div class="form-error">
                    E-mail ou senha incorretos.
                </div>

            <?php endif; ?>


            <?php if ($cadastro === 'sucesso'): ?>

                <div class="form-success">
                    Cadastro realizado com sucesso!
                    Agora você pode entrar.
                </div>

            <?php endif; ?>


            <?php if ($erro === 'acesso'): ?>

                <div class="form-error">
                    Você precisa estar logado para acessar essa página.
                </div>

            <?php endif; ?>


            <form
                action="entrar.php"
                method="post"
                class="auth-form">

                <div class="form-group">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="150"
                        autocomplete="email"
                        placeholder="Digite seu e-mail"
                        required>

                </div>


                <div class="form-group">

                    <label for="senha">
                        Senha
                    </label>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        autocomplete="current-password"
                        placeholder="Digite sua senha"
                        required>

                </div>


                <button
                    type="submit"
                    class="auth-button">
                    Entrar
                </button>

            </form>


            <div class="auth-footer">

                Ainda não possui uma conta?

                <a href="../setup/cadastrar_user.php">
                    Criar conta
                </a>

            </div>

            <div class="legal-footer">
                Projeto acadêmico experimental sem fins comerciais.
                <a href="../termos.php">Termos de Uso</a>
                <a href="../privacidade.php">Privacidade</a>
            </div>

        </section>

    </main>

</body>

</html>