<?php

include '../includes/conn.php';


// ========================================
// 1. Verifica o método da requisição
// ========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido.");
}


// ========================================
// 2. Recebe os dados
// ========================================

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$aceite_lgpd = $_POST['aceite_lgpd'] ?? '';


// ========================================
// 3. Verifica campos obrigatórios
// ========================================

if ($nome === '' || $email === '' || $senha === '' || $aceite_lgpd !== '1') {
    die("Todos os campos são obrigatórios.");
}


// ========================================
// 4. Valida o tamanho dos dados
// ========================================

if (strlen($nome) > 100) {
    die("O nome deve ter no máximo 100 caracteres.");
}

if (strlen($email) > 150) {
    die("O e-mail deve ter no máximo 150 caracteres.");
}


// ========================================
// 5. Valida o formato do e-mail
// ========================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Informe um e-mail válido.");
}


// ========================================
// 6. Valida a senha
// ========================================

if (strlen($senha) < 8) {
    die("A senha deve ter pelo menos 8 caracteres.");
}


// ========================================
// 7. Verifica se o e-mail já existe
// ========================================

$sql = "SELECT id FROM usuarios WHERE email = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta.");
}

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $stmt->close();

    die("O e-mail já está cadastrado.");
}

$stmt->close();


// ========================================
// 8. Cria o hash da senha
// ========================================

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);


// ========================================
// 9. Insere o usuário
// ========================================

$sql = "INSERT INTO usuarios (nome, email, senha, aceite_lgpd, aceite_lgpd_em)
    VALUES (?, ?, ?, 1, NOW())";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar o cadastro.");
}

$stmt->bind_param("sss", $nome, $email, $senhaHash);


// ========================================
// 10. Executa o cadastro
// ========================================

if (!$stmt->execute()) {

    $stmt->close();

    die("Não foi possível realizar o cadastro.");
}


$stmt->close();


// ========================================
// 11. Cadastro realizado
// ========================================

header("Location: ../auth/login.php?cadastro=sucesso");
exit();
