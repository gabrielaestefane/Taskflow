<?php

require_once __DIR__ . '/response.php';

function verificar_autenticacao(): void
//Inicia a sessão PHP´, caso ela ainda não estaja ativa.
// Verifica se existe usuario_id na sessão. Se não existir, deve retornar erro 401 usando o helper de resposta.
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['usuario_id'])) {
        json_response(
            false,
            'Autenticação necessária.',
            null,
            401
        );
    }
}

//pressupõe que json_response() encerra a execução da requisição, conforme o material da atividade.