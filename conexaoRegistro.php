<?php

include("conexao.php");

if (isset($_POST['submit_button'])) {
    $acao = $_POST['submit_button'];

    if ($acao == 'Registrar Entrada') {
        // Código para redgistrar ad entrada
    } elseif ($acao == 'Registrar Almoço') {
        // Código para registrar o almoço
    } elseif ($acao == 'Registrar Saída') {
        // Código para registrar a saída e
    }
}
?>
