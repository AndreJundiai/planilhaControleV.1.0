<?php
session_start(); // Certifique-//se de iniciar a sessão antes de acessar $_SESSION

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se a ação foi recebida
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];
        echo "Ação recebida: " . $acao . "<br>"; // Depuração
    }

    // Verifica se o feriado foi recebido
    if (isset($_POST['feriado'])) {
        $feriado = $_POST['feriado'];
        echo "Feriado recebido: " . $feriado . "<br>"; // Depuração
    }

    // Verifica se o atestado foi recebido
    if (isset($_POST['atestado'])) {
        $atestado = $_POST['atestado'];
        echo "Atestado recebido: " . $atestado . "<br>"; // Depuração
    }

    // Informações de conexão com o banco de dados
    $host = 'localhost';
    $usuario = 'root';
    $senha = '1234';
    $banco = 'novoBD';

    // Conexão com o banco de dados
    $conexao = mysqli_connect($host, $usuario, $senha, $banco);

    // Verifica se a conexão foi estabelecida
    if (!$conexao) {
        echo 'Falha na conexão com o banco de dados: ' . mysqli_connect_error();
        exit;
    }

    // Função para obter o dia da semana
    function obterDiaSemana() {
        $diaDaSemana = date("N");
        $dias = [
            1 => "Segunda-feira",
            2 => "Terça-feira",
            3 => "Quarta-feira",
            4 => "Quinta-feira",
            5 => "Sexta-feira",
            6 => "Sábado",
            7 => "Domingo"
        ];

        return $dias[$diaDaSemana] ?? "Dia inválido";
    }




    // Função para registrar o ponto
    function registrarPonto($tipoRegistro) {
        global $conexao, $dia, $diaSemana, $horaAtual, $idUsuario;
    
        // Verifica se há justificativa
        $justificativa = isset($_POST['mostrarTexto']) ? $_POST['textoParaMostrar'] : "";


        if ($tipoRegistro == "hora_entrada") {
            $query = "INSERT INTO pontoRegistro (dia, diaSemana, $tipoRegistro, idUsuario, justificativa) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $query);
        } else {
            $query = "UPDATE pontoRegistro SET $tipoRegistro = ? WHERE dia = ?";
            $stmt = mysqli_prepare($conexao, $query);
            mysqli_stmt_bind_param($stmt, "ss", $horaAtual, $dia);
        }
        


    
        // Verifica se a preparação da query foi bem-sucedida
        if ($stmt) {
            // Liga os parâmetros
            mysqli_stmt_bind_param($stmt, "sssss", $dia, $diaSemana, $horaAtual, $idUsuario, $justificativa);
    
            // Executa a query
            if (mysqli_stmt_execute($stmt)) {
                echo "Registro de ponto realizado com sucesso!";
            } else {
                echo "Erro ao registrar ponto!";
            }
    
            // Fecha a declaração
            mysqli_stmt_close($stmt);
        } else {
            echo "Erro na preparação da query!";
        }
    }
}
    






    // Obtém informações do usuário logado
    $idUsuario = $_SESSION["id_usuario"];
    $dia = date('Y-m-d');
    $diaSemana = obterDiaSemana();
    $horaAtual = date('H:i:s');

    // Verifica se a ação foi definida
    if (isset($acao)) {
        $acao = mysqli_real_escape_string($conexao, $acao);

        // Verifica a ação a ser realizada
        switch ($acao) {
            case "Registrar Entrada":
                registrarPonto("hora_entrada");
                break;
            case "Registrar Saída":
                registrarPonto("hora_saida");
                break;
            case "Registrar Inicio Almoço":
                registrarPonto("hora_almoço_entrada");
                break;
            case "Registrar Fim Almoço":
                registrarPonto("hora_almoço_saida");
                break;
            // Caso não haja uma ação válida
            default:
                echo "Ação inválida!";
        }
    }

    // Função para registrar o ponto