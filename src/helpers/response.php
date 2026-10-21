<?php

function json_response(
    bool $sucesso,
    string $mensagem,
    mixed $dados = null,
    int $codigo = 200
): void {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');

    $resposta = [
        'sucesso' => $sucesso,
        'mensagem' => $mensagem,
    ];

    if ($dados !== null) {
        $resposta['dados'] = $dados;
    }

    echo json_encode($resposta, JSON_UNESCAPED_UNICODE);
    exit;
}