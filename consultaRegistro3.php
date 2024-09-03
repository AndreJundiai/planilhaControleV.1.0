<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Registros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f2f2f2;
        }
        
        .success {
            color: green;
        }
        
        .error {
            color: red;
        }

        /* Adiciona estilo para a animação de carregamento */
        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        /* Estilo para a mensagem de carregamento */
        #loading-msg {
            font-style: italic;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#nomeSelecionado').change(function(){
                var nomeSelecionado = $(this).val();

                // Exibe a mensagem de carregamento após 4 segundos
                setTimeout(function() {
                    $('#loading').show();
                    $('#loading-msg').text('Carregando registros...');
                }, 4000);

                $.ajax({
                    url: 'consulta.php', // Arquivo PHP que processa a consulta
                    type: 'POST',
                    data: {nomeSelecionado: nomeSelecionado},
                    dataType: 'html',
                    success: function(response){
                        // Esconde a mensagem de carregamento quando os registros são carregados
                        $('#loading').hide();
                        $('#resultado').html(response);
                    },
                    error: function(xhr, status, error){
                        console.log(error);
                    }
                });
            });
        });
    </script>
</head>
<body>
<h1>Consulta de Registros</h1>
<form>
    <label for="nomeSelecionado">Selecione o nome:</label>
    <select id="nomeSelecionado" name="nomeSelecionado">
        <option value="">Selecione...</option>
        <?php
        // Conexão com o banco de dados (substitua os valores conforme sua configuração)
        $host = 'localhost';
        $usuario = 'root';
        $senha = '1234'; // Coloque aspas simples na senha
        $banco = 'novoBD';
        $conexao = mysqli_connect($host, $usuario, $senha, $banco);

        // Verifica se a conexão foi estabelecida corretamente
        if (mysqli_connect_errno()) {
            echo 'Falha na conexão com o banco de dados: ' . mysqli_connect_error();
            exit;
        }

        // Consulta os nomes de usuários que têm registros associados
        $queryNomes = "SELECT DISTINCT u.id, u.nome FROM usuarius u INNER JOIN pontoRegistro p ON u.id = p.idUsuario";
        $resultadoNomes = mysqli_query($conexao, $queryNomes);

        if (!$resultadoNomes) {
            die("Erro na consulta: " . mysqli_error($conexao));
        }

        // Exibe os nomes de usuários no dropdown
        while ($rowNome = mysqli_fetch_assoc($resultadoNomes)) {
            $id = $rowNome['id'];
            $nome = $rowNome['nome'];
            echo "<option value='$nome'>$nome (ID: $id)</option>";
        }
        ?>
    </select>
</form>

<!-- Mensagem de carregamento -->
<div id="loading">
    <p id="loading-msg"></p>
</div>

<!-- Conteúdo dos registros exibidos aqui -->
<div id="resultado"></div>

</body>
</html>
