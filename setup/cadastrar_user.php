<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro | PulseLog</title>

    <link rel="stylesheet" href="../assets/css/auth.css">

</head>


<body>

    <main class="auth-container">

        <div class="logo">
            Pulse<span>Log</span>
        </div>


        <section class="auth-card">

            <h1>
                Criar sua conta
            </h1>

            <p class="auth-description">
                Crie sua conta para começar a registrar e acompanhar
                seus episódios.
            </p>


            <form
                action="salvar_user.php"
                method="post"
                class="auth-form">


                <div class="form-group">

                    <label for="nome">
                        Nome
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="100"
                        autocomplete="name"
                        placeholder="Digite seu nome"
                        required>

                </div>


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
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Digite sua senha"
                        required>

                    <p class="password-info">
                        A senha deve possuir pelo menos 8 caracteres.
                    </p>

                </div>


                <label class="consentimento">

                    <input
                        type="checkbox"
                        name="aceite_lgpd"
                        value="1"
                        required>

                    <span>
                        Li e concordo com os
                        <a href="../termos.php" target="_blank" rel="noopener">
                            Termos de Uso
                        </a>
                        e a
                        <a href="../privacidade.php" target="_blank" rel="noopener">
                            Política de Privacidade
                        </a>.
                    </span>

                </label>


                <button
                    type="submit"
                    class="auth-button">
                    Criar conta
                </button>

            </form>


            <div class="auth-footer">

                Já possui uma conta?

                <a href="../auth/login.php">
                    Entrar
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