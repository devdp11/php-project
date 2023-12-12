<?php

function isExpenseValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] = trim($req[$key]);
    }

    $errors = [];

    // Verifica se o valor do campo 'amount' é numérico e não negativo
    if (!is_numeric($req['amount']) || $req['amount'] < 0) {
        $errors['amount'] = 'The Amount field must be a non-negative numeric value.';
    }

    // Verifica se o campo 'note' tem no máximo 255 caracteres
    if (strlen($req['note']) > 255) {
        $errors['note'] = 'The Note field cannot exceed 255 characters.';
    }

    // Verifica se a data é uma data válida e se é do dia de hoje para a frente
    $currentDate = date('Y-m-d');
    if (empty($req['date']) || strtotime($req['date']) === false || $req['date'] < $currentDate) {
        $errors['date'] = 'The Date field must be a valid date and cannot be in the past.';
    }

    // Se houver erros, retorna a lista de erros
    if (!empty($errors)) {
        return ['invalid' => $errors];
    }

    // Se tudo estiver válido, retorna os dados
    return $req;
}
?>
