<?php
// Inicie a sessão (se ainda não estiver iniciada)
session_start();

// Verifique se o usuário está autenticado (você pode personalizar isso conforme sua implementação)
if (!isset($_SESSION["id_usuario"])) {
    // Se não estiver autenticado, redirecione para a página de login
    header("Location: login.php");
    exit();
}

// Variável de exemplo para verificar se a entrada do almoço foi registrada
$entradaAlmocoRegistrada = false;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
    <link rel="stylesheet" type="text/css" href="estilo.css"> <!-- Inclua o arquivo CSS -->
    <!-- Não há necessidade de incluir o arquivo JavaScript externo para este exemplo -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
        }
        nav {
            text-align: center;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        nav a.active {
            font-weight: bold;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }
        p {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
        }
        form {
            text-align: center;
            margin-bottom: 50px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        input[type="checkbox"] {
            margin-top: 10px;
        }
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="active">Página Inicial</a>
            <a href="consultaRegistro2.php">Consultar Jornada</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <h1>Auto controle de Jornada</h1>

    <!-- Exiba a mensagem de boas-vindas com o nome do usuário -->
    <p>Bem-vindo, <?php echo $_SESSION["nome_usuario"]; ?>!</p>

    <form action="processamento.php" method="post">
        <input type="submit" name="acao" value="Registrar Entrada">
        <input type="submit" name="acao" value="Registrar Inicio Almoço">
        <input type="submit" name="acao" value="Registrar Fim Almoço">
        <input type="submit" name="acao" value="Registrar Saída">
        <br>
        <input type="checkbox" name="feriado" value="feriado"> Dia de Feriado <br>
    <label for="atestado">Atestado</label>
    <input type="checkbox" id="atestado" name="atestado" value = "atestado" onchange="mostrarCampoTexto()">
    <br><br>
    <div id="campoTexto"   value = "justificativa"style="display:none;">
        <label for="textoParaMostrar">Justificativa:</label><br>
        <textarea id="textoParaMostrar" name="textoParaMostrar" rows="4" cols="50"></textarea>
    </div>
    <br><br>
</form>
    </div>
    <br><br>
</form>

    </form>

    <!-- Adicione um link para baixar a planilha -->
    <p style="text-align: center;"><a href="planilha.php" style="color: #333;">Baixar Planilha</a></p>

    <footer>
        Sobre o Projeto do André, projeto top
    </footer>
    
    
 <script>
function mostrarCampoTexto() {
    var checkBox = document.getElementById("atestado");
    var campoTexto = document.getElementById("campoTexto");
    if (checkBox.checked == true){
        campoTexto.style.display = "block";
    } else {
       campoTexto.style.display = "none";
    }
}
</script>    
    
</body>
</html>
