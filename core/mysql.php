<?php

function insere(string $entidade, array $dados) : bool
{
    $retorno = false;
    $coringa = [];
    $tipos = '';
    $valores = [];

    foreach ($dados as $campo => $dado){
        $coringa[$campo] = '?';
        $tipos .= gettype($dado)[0];
        $valores[] = $dado;
    }

    $instrucao = insert($entidade, $coringa);
    $conexao = conecta();
    $stmt = mysqli_prepare($conexao, $instrucao);

    if (!$stmt) {
        $_SESSION['errors'] = [mysqli_error($conexao)];
        desconecta($conexao);
        return false;
    }

    // Bind dos parâmetros usando call_user_func_array
    mysqli_stmt_bind_param($stmt, $tipos, ...$valores);

    mysqli_stmt_execute($stmt);

    $retorno = (bool) mysqli_stmt_affected_rows($stmt);

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);
    desconecta($conexao);

    return $retorno;
}

function atualiza(string $entidade, array $dados, array $criterio = []) : bool
{
    $retorno = false;
    $coringa_dados = [];
    $tipos = '';
    $valores = [];

    foreach ($dados as $campo => $dado) {
        $coringa_dados[$campo] = '?';
        $tipos .= gettype($dado)[0];
        $valores[] = $dado;
    }

    $coringa_criterio = [];
    $valores_criterio = [];
    $tipos_criterio = '';

    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];
        $tipos_criterio .= gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;
        $valores_criterio[] = $dado;
    }

    $instrucao = update($entidade, $coringa_dados, $coringa_criterio);
    $conexao = conecta();
    $stmt = mysqli_prepare($conexao, $instrucao);

    if (!$stmt) {
        $_SESSION['errors'] = [mysqli_error($conexao)];
        desconecta($conexao);
        return false;
    }

    if ($tipos !== '' || $tipos_criterio !== '') {
        $tipos_completo = $tipos . $tipos_criterio;
        $valores_completo = array_merge($valores, $valores_criterio);
        mysqli_stmt_bind_param($stmt, $tipos_completo, ...$valores_completo);
    }

    mysqli_stmt_execute($stmt);

    $retorno = (bool) mysqli_stmt_affected_rows($stmt);

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);
    desconecta($conexao);

    return $retorno;
}

function deleta(string $entidade, array $criterio = []) : bool
{
    $retorno = false;
    $coringa_criterio = [];
    $tipos = '';
    $valores = [];

    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];
        $tipos .= gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;
        $valores[] = $dado;
    }

    $instrucao = delete($entidade, $coringa_criterio);
    $conexao = conecta();
    $stmt = mysqli_prepare($conexao, $instrucao);

    if (!$stmt) {
        $_SESSION['errors'] = [mysqli_error($conexao)];
        desconecta($conexao);
        return false;
    }

    if ($tipos !== '') {
        mysqli_stmt_bind_param($stmt, $tipos, ...$valores);
    }

    mysqli_stmt_execute($stmt);

    $retorno = (bool) mysqli_stmt_affected_rows($stmt);

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);
    desconecta($conexao);

    return $retorno;
}

function buscar(string $entidade, array $campos = ['*'], array $criterio = [], string $ordem = null) : array
{
    $retorno = [];
    $coringa_criterio = [];
    $tipos = '';
    $valores = [];

    foreach ($criterio as $expressao) {
        $dado = $expressao[count($expressao) - 1];
        $tipos .= gettype($dado)[0];
        $expressao[count($expressao) - 1] = '?';
        $coringa_criterio[] = $expressao;
        $valores[] = $dado;
    }

    $instrucao = select($entidade, $campos, $coringa_criterio, $ordem);

    $conexao = conecta();
    $stmt = mysqli_prepare($conexao, $instrucao);

    if (!$stmt) {
        $_SESSION['errors'] = [mysqli_error($conexao)];
        desconecta($conexao);
        return [];
    }

    if ($tipos !== '') {
        mysqli_stmt_bind_param($stmt, $tipos, ...$valores);
    }

    mysqli_stmt_execute($stmt);

    if ($result = mysqli_stmt_get_result($stmt)) {
        $retorno = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }

    $_SESSION['errors'] = mysqli_stmt_error_list($stmt);

    mysqli_stmt_close($stmt);
    desconecta($conexao);

    return $retorno;
}

?>
