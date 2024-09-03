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

if(isset($_POST['nomeSelecionado'])) {
    $nomeSelecionado = $_POST['nomeSelecionado'];
    
    // Consulta o idUsuario correspondente ao nome selecionado
    $queryIdUsuario = "SELECT id FROM usuarius WHERE nome = '$nomeSelecionado'";
    $resultadoIdUsuario = mysqli_query($conexao, $queryIdUsuario);
    
    if (!$resultadoIdUsuario) {
        die("Erro na consulta: " . mysqli_error($conexao));
    }
    
    // Verifica se a consulta retornou algum resultado
    $rowIdUsuario = mysqli_fetch_assoc($resultadoIdUsuario);
    $idUsuario = $rowIdUsuario['id'] ?? null; // Define idUsuario como null se não houver correspondência
    
    if($idUsuario) {
        // Consulta os registros para o usuário selecionado
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
        
        // Constrói a tabela de registros
        $output = '<table>';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>Dia</th>';
        $output .= '<th>Hora Entrada</th>';
        $output .= '<th>Início do Almoço</th>';
        $output .= '<th>Fim do Almoço</th>';
        $output .= '<th>Hora da Saída</th>';
        $output .= '<th>Minutos Trabalhados</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        
        while ($registro = mysqli_fetch_assoc($resultado)) {
            $output .= '<tr>';
            $output .= '<td>' . $registro['dia'] . '</td>';
            $output .= '<td>' . $registro['hora_entrada'] . '</td>';
            $output .= '<td>' . $registro['hora_almoço_entrada'] . '</td>';
            $output .= '<td>' . $registro['hora_almoço_saida'] . '</td>';
            $output .= '<td>' . $registro['hora_saida'] . '</td>';
            $output .= '<td>' . $registro['minutosTrabalhados'] . '</td>';
            $output .= '</tr>';
        }
        
        $output .= '</tbody>';
        $output .= '</table>';
        
        echo $output;
    }
}
?>
