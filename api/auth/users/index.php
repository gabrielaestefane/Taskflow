<?php

require_once __DIR__ . '/../../../src/helpers/response.php';
require_once __DIR__ . '/../../../src/helpers/auth.php';
require_once __DIR__ . '/../../../config/database.php';

verificar_autenticacao();

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo !== 'GET') {
    json_response(false, 'Método não permitido.', null, 405);
}

try {
    $sql = $sql = 'SELECT id, nome, email, created_at FROM users';

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    json_response(true, 'Usuários consultados com sucesso.', $usuarios, 200);

} catch (PDOException $e) {
    json_response(false, 'Erro interno ao consultar usuários.', null, 500);
}