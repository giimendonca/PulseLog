<?php

session_start();

include '../includes/conn.php';


// 1. Verifica se a requisição veio do formulário

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: login.php");

    exit();
}


// 2. Recebe os dados

$email = trim($_POST['email'] ?? '');

$senha = $_POST['senha'] ?? '';


// 3. Verifica se os campos foram preenchidos

if ($email === '' || $senha === '') {

    header("Location: login.php?erro=login");

    exit();
}


// 4. Busca o usuário pelo email

$sql = "SELECT id, nome, email, senha
        FROM usuarios
        WHERE email = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Erro ao preparar a consulta.");

}

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

$usuario = $result->fetch_assoc();

$stmt->close();


// 5. Verifica se o usuário existe
// e se a senha corresponde ao hash

if (!$usuario || !password_verify($senha, $usuario['senha'])) {

    header("Location: login.php?erro=login");

    exit();
}


// 6. Gera um novo ID de sessão

session_regenerate_id(true);


// 7. Guarda os dados necessários na sessão

$_SESSION['id'] = $usuario['id'];

$_SESSION['nome'] = $usuario['nome'];

$_SESSION['email'] = $usuario['email'];


// 8. Vai para o dashboard

header("Location: ../dashboard/index.php");

exit();