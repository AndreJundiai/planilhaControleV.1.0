<?php
session_start();

// Verifique se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = md5($_POST["senha"]); // Criptografe a senha em MD5
    if(isset($_POST['opcoes'])) {
        $opcoes_selecionadas = $_POST['opcoes'];
        foreach($opcoes_selecionadas as $opcao) {
            echo "Opção selecionada: " . $opcao . "<br>";
        }
    } else {
        echo "Nenhuma opção selecionada.";
    }    $jornadadeTrabalho = ($_POST["jornadadeTrabalho"]);

    // Chame a função para inserir um usuário
    inserirUsuario($nome, $email, $senha,$opcao, $jornadadeTrabalho);
}

// Função responsável por criar uma conexão com o banco de dados
function abrirBanco() {
    $conexao = new mysqli("localhost", "root", "1234", "novoBD");
    if ($conexao->connect_error) {
        die("Erro de conexão com o banco de dados: " . $conexao->connect_error);
    }
    return $conexao;
}

// Função responsável por inserir um usuário no banco de dados
function inserirUsuario($nome, $email, $senha,$opcao, $jornadadeTrabalho) {
    $banco = abrirBanco();

    // Insira os dados do usuário no banco de dados sem a imagem
    $sql = "INSERT INTO usuarius (nome, email, senha, jornadadeTrabalho,opcao) VALUES (?, ?, ?, ?,?)";
    $stmt = $banco->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $banco->error);
    }
    $stmt->bind_param("sssss", $nome, $email, $senha, $jornadadeTrabalho,$opcao);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $stmt->error;
    }

    $stmt->close();
    $banco->close();
}

// ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cadastro de Colaboradores</title>
    <!-- Inclua o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <form action="registro.php" method="post">
            <h1 class="mb-4">Cadastro de Colaboradores</h1>

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jornadadeTrabalho">Jornada de Trabalho em minutos:</label>
                <input type="number" id="jornadadeTrabalho" name="jornadadeTrabalho" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>

    <!-- Inclua o JS do Bootstrap e Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
