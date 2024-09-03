<?php
include 'conexao.php';

$mysqli = new mysqli("localhost", "root", "1234", "novoBD");

if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

$query = mysqli_query($mysqli, "select nome,modelo,memoriaRam,sistemaOperacional from notebook ");
$contar = mysqli_num_rows($query);

// Criação de uma tsdsdsdsabela HTML dque se parece com uma planilha Excel
$html = "<table>
<thead>
<tr>
<th>Nome </th>
<th>Modelo </th>
<th> Memoria Ram </th>
<th> Sistema Operacional  </th>
</tr>
</thead>
<tbody>";

while ($ret = mysqli_fetch_array($query)) {
    $retorno_nome = $ret['nome'];
    $retorno_modelo = $ret['modelo'];
    $retorno_memoriaRam = $ret['memoriaRam'];
    $retorno_sistemaOperacional = $ret['sistemaOperacional'];

    // Criação de uma linha de tabela para cada registro
    $html .= "<tr>
        <td>$retorno_nome</td>
        <td>$retorno_modelo</td>
        <td>$retorno_memoriaRam</td>
        <td>$retorno_sistemaOperacional</td>
    </tr>";
}

$html .= "</tbody></table>";

// Cabeçalhos para forçar o download como um arquivo Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=InventarioNotebook.xls");

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
