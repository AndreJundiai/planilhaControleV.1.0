<?php
include 'conexao.php';

$mysqli = new mysqli("localhost", "root", "1234", "novoBD");

if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

session_start();
$idUsuario = $_SESSION["id_usuario"];
$idUsuario = $mysqli->real_escape_string($idUsuario);

$query = "SELECT dia,
                  hora_entrada,
                  hora_almoço_entrada,
                  hora_almoço_saida,
                  hora_saida,
                  TIMESTAMPDIFF(MINUTE, hora_entrada, hora_saida) - TIMESTAMPDIFF(MINUTE, hora_almoço_entrada, hora_almoço_saida) AS minutosExtras
           FROM pontoRegistro
           WHERE idUsuario = '$idUsuario'";
$result = $mysqli->query($query);

// Criação de uma tabela HTML que se assemelha a uma planilha Excel
$html = "<table>
<thead>
<tr>
<th>Dia</th>
<th>Hora Entrada</th>
<th>Início do Almoço</th>
<th>Fim do Almoço</th>
<th>Hora da Saída</th>
<th>Banco de Horas</th>
</tr>
</thead>
<tbody>";

while ($ret = $result->fetch_assoc()) {
    $retorno_dia = $ret['dia'];
    $retorno_hora_entrada = $ret['hora_entrada'];
    $retorno_horario_almoco_entrada = $ret['hora_almoço_entrada'];
    $retorno_horario_almoco_saida = $ret['hora_almoço_saida'];
    $retorno_saida = $ret['hora_saida'];
    $retorno_minutosExtras = $ret['minutosExtras'];

    // Criação de uma linha de tabela para cada registro
    $html .= "<tr>
        <td>$retorno_dia</td>
        <td>$retorno_hora_entrada</td>
        <td>$retorno_horario_almoco_entrada</td>
        <td>$retorno_horario_almoco_saida</td>
        <td>$retorno_saida</td>
        <td>$retorno_minutosExtras</td>
    </tr>";
}

$html .= "</tbody></table>";

// Cabeçalhos para forçar o download como um arquivo Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=controleJornada.xls");

// Saída do HTML como um arquivo Excel
echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
echo '<head>';
echo '<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
echo '</head>';
echo '<body>';
echo $html;
echo '</body>';
echo '</html>';

// Agora você pode enviar o mesmo conteúdo por e-mail se desejar
?>
