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
                class="auth-form"
            >


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
                        required
                    >

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
                        required
                    >

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
                        required
                    >

                    <p class="password-info">
                        A senha deve possuir pelo menos 8 caracteres.
                    </p>

                </div>


                <button
                    type="submit"
                    class="auth-button"
                >
                    Criar conta
                </button>

            </form>


            <div class="auth-footer">

                Já possui uma conta?

                <a href="../auth/login.php">
                    Entrar
                </a>

            </div>

        </section>

    </main>

</body>

</html>