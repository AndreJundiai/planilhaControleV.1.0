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

session_start();
$idUsuario = $_SESSION["id_usuario"];
$idUsuario = mysqli_real_escape_string($conexao, $idUsuario);

$query = "SELECT dia,
                  hora_entrada,
                  hora_almoço_entrada,
                  hora_almoço_saida,
                  hora_saida,
                  TIMESTAMPDIFF(MINUTE, hora_entrada, hora_saida) - TIMESTAMPDIFF(MINUTE, hora_almoço_entrada, hora_almoço_saida) AS minutosTrabalhados
           FROM pontoRegistro
           WHERE idUsuario = '$idUsuario'";

$resultado = mysqli_query($conexao, $query);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}


$query = "SELECT dia,diaSemana,
                  hora_entrada,
                  hora_almoço_entrada,
                  hora_almoço_saida,
                  hora_saida,
                  bancodeHoras
           FROM pontoRegistro
           WHERE idUsuario = '$idUsuario'";

$resultado = mysqli_query($conexao, $query);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}



$query = "SELECT nome FROM usuarius WHERE id = '$idUsuario'";
$resultado2 = mysqli_query($conexao, $query);

if (!$resultado2) {
    die("Erro na consulta: " . mysqli_error($conexao));
}

$nomeUsuario = ""; // Inidciadliza a variável para armazenar o nome do usuário

// Verifica se a consulta retornou algum resultado
if ($row = mysqli_fetch_assoc($resultado2)) {
    $nomeUsuario = $row['nome'];
}







?>

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
    </style>
</head>
<body>
 <h1><?php echo $nomeUsuario; ?></h1>
    <?php if (mysqli_num_rows($resultado) > 0) : ?>
        <table>
            <thead>
                <tr>
                  <th>Dia</th>
                    <th>Dia da Semana</th>
                  <th>Hora Entrada</th>
                  <th>Início do Almoço</th>
                  <th>Fim do Almoço</th>
                  <th>Hora da Saída</th>
                  <th>Banco de Horas em minutos</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($registro = mysqli_fetch_assoc($resultado)) : ?>
                    <tr>
                        <td><?php echo $registro['dia']; ?></td>
                        <td><?php echo $registro['diaSemana']; ?></td>
                        <td><?php echo $registro['hora_entrada']; ?></td>
                        <td><?php echo $registro['hora_almoço_entrada']; ?></td>
                        <td><?php echo $registro['hora_almoço_saida']; ?></td>
                        <td><?php echo $registro['hora_saida']; ?></td>
                        <td><?php echo $registro['bancodeHoras']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="error">Nenhum registro encontrado hoje.</p>
    <?php endif; ?>
</body>
</html>
