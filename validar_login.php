<?php
session_start();

// Função responsável por criar uma conexão com o banco de dados
function abrirBanco() {
    $conexao = new mysqli("localhost", "root", "1234", "novoBD");
    if ($conexao->connect_error) {
        die("Erro de conexão com o banco de dados: " . $conexao->connect_error);
    }
    return $conexao;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = md5($_POST["senha"]); // Criptografar a senha em MD5

    $banco = abrirBanco();

    // Consulta para verificar se o email e a senha correspondem a um usuário
    $sql = "SELECT * FROM usuarius WHERE email = ? AND senha = ?";
    $stmt = $banco->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $banco->error);
    }
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["id_usuario"] = $row["id"];
        $_SESSION["nome_usuario"] = $row["nome"];
        $_SESSION["opcao"] = $row["opcao"];

        if ($row["opcao"] == "Administrador") {
            header("Location: homeAdministrador.php");
        } elseif ($row["opcao"] == "Colaborador") {
            header("Location: homeColaborador.php");
        }
        exit();
    } else {
        $mensagemErro = "Email ou senha incorretos. <a href='login.html'>Tente novamente</a>";
    }

    $banco->close();
} else {
    header("Location: login.php");
    exit();
}
