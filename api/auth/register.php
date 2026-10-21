<?php

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

$erros = validar_cadastro($dados);

if (!empty($erros)) {
    json_response(
        false,
        'Dados inválidos.',
        ['erros' => $erros],
        422
    );
    exit;
}

$nome = trim($dados['nome']);
$email = strtolower(trim($dados['email']));
$senha = $dados['senha'];

try {

    $stmt = $pdo->prepare(
        'SELECT id FROM users WHERE email = :email'
    );

    $stmt->execute([
        ':email' => $email
    ]);

    if ($stmt->fetch()) {
        json_response(
            false,
            'E-mail já cadastrado.',
            null,
            409
        );
        exit;
    }

    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO users (nome, email, senha_hash)
         VALUES (:nome, :email, :senha_hash)
         RETURNING id'
    );

    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha_hash' => $hash
    ]);

    $id = $stmt->fetchColumn();

    json_response(
        true,
        'Usuário cadastrado com sucesso.',
        [
            'usuario' => [
                'id' => $id,
                'nome' => $nome,
                'email' => $email
            ]
        ],
        201
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