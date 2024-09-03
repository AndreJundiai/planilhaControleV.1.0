<?php
session_start(); // Certifique-se de iniciar a sessão

// Verifique se o usuário está autenticado e se a opção é "Administrador"
if (!isset($_SESSION["id_usuario"]) || !isset($_SESSION["opcao"]) || $_SESSION["opcao"] !== "Administrador") {
    // Se não estiver autenticado ou não for administrador, redirecione para a página de login
    header("Location: login.php");
    exit();
}

// O código continua aqui se o usuário for autenticado e a opção for "Administrador"
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Colaboradores</title>
    <!-- Inclua o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <form class="container mt-4" action="registro.php" method="post">
        <h1 class="text-center">Cadastro de Colaboradores</h1>

        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>

        <div class="form-group">
            <label for="opcoes">Opções:</label>
            <select class="form-control" id="opcoes" name="opcoes[]" multiple required>
                <option value="Administrador">Administrador</option>
                <option value="Colaborador">Colaborador</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jornadadeTrabalho">Jornada de Trabalho em minutos:</label>
            <input type="number" class="form-control" id="jornadadeTrabalho" name="jornadadeTrabalho" required>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>

    <!-- Inclua o JS do Bootstrap e Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
