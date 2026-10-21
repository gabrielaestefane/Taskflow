<?php

session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/helpers/response.php';
require_once __DIR__ . '/../../src/helpers/validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(
        false,
        'Método não permitido.',
        null,
        405
    );
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);

if (!is_array($dados)) {
    $dados = [];
}

$erros = validar_login($dados);

if (!empty($erros)) {
    json_response(
        false,
        'Dados inválidos.',
        ['erros' => $erros],
        422
    );
    exit;
}

$email = strtolower(trim($dados['email']));
$senha = $dados['senha'];

try {

    $stmt = $pdo->prepare(
        'SELECT id, nome, email, senha_hash
         FROM users
         WHERE email = :email'
    );

    $stmt->execute([
        ':email' => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$usuario ||
        !password_verify($senha, $usuario['senha_hash'])
    ) {
        json_response(
            false,
            'Credenciais inválidas.',
            null,
            401
        );
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];

    json_response(
        true,
        'Login realizado com sucesso.',
        [
            'usuario' => [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email']
            ]
        ],
        200
    );

    exit;

} catch (PDOException $e) {

    json_response(
        false,
        'Erro interno do servidor.',
        null,
        500
    );

    exit;
}