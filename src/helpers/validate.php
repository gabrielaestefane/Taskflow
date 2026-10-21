<?php

function validar_campo(?string $valor): bool
{
    if ($valor === null) {
        return false;
    }

    if (trim($valor) === '') {
        return false;
    }

    return true;
}

function validar_cadastro(array $dados): array
{
    $erros = [];

    if (!validar_campo($dados['nome'] ?? null)) {
        $erros[] = 'O nome é obrigatório.';
    }

    $email = trim($dados['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido.';
    }

    $senha = $dados['senha'] ?? '';
    if (strlen($senha) < 8) {
        $erros[] = 'A senha deve ter pelo menos 8 caracteres.';
    }

    return $erros;
}

function validar_login(array $dados): array
{
    $erros = [];

    $email = trim($dados['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido.';
    }

    if (!validar_campo($dados['senha'] ?? null)) {
        $erros[] = 'A senha é obrigatória.';
    }

    return $erros;
}