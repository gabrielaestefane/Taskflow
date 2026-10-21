<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Apaga os dados da sessão
$_SESSION = [];

// Remove o cookie da sessão no cliente
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Encerra a sessão no servidor
session_destroy();

require_once __DIR__ . '/../../src/helpers/response.php';

json_response(true, 'Logout realizado com sucesso.', null, 200);