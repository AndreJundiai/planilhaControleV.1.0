<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar CSV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="file"] {
            display: block;
            margin: 0 auto 20px;
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .success-message, .error-message {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Importar CSV</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv" required>
            <input type="submit" value="Importar CSV">
        </form>
        <?php
        // Configurações do banco de dados
        $host = 'localhost';
        $db = 'novoBD';
        $user = 'root';
        $pass = '1234';

        try {
            // Conexão com o banco de dados
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

                    // Verificar se a tabela "pontoRegistro" existe
                    $stmt = $pdo->query("SHOW TABLES LIKE 'pontoRegistro'");
                    if ($stmt->rowCount() == 0) {
                        die('<p class="error-message">Erro: Tabela "pontoRegistro" não existe no banco de dados.</p>');
                    }

                    // Ignorar a primeira linha (cabeçalhos)
                    fgetcsv($csvFile);

                    // Ler e inserir ou atualizar os dados no banco de dados
                    while (($row = fgetcsv($csvFile)) !== FALSE) {
                        // Verificar se o registro já existe na tabela
                        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM pontoRegistro WHERE dia = ? AND idUsuario = ?");
                        $checkStmt->execute([$row[0], $row[5]]);
                        $exists = $checkStmt->fetchColumn();

                        if (!$exists) {
                            // Se o registro não existir, inserir um novo
                            $insertStmt = $pdo->prepare("INSERT INTO pontoRegistro (dia, hora_entrada, hora_almoço_entrada, hora_almoço_saida, hora_saida, idUsuario, diaSemana) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $insertStmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]]);
                        } else {
                            // Atualizar a hora de saída
                            $updateStmt = $pdo->prepare("UPDATE pontoRegistro SET hora_saida = ? WHERE dia = ? AND idUsuario = ?");
                            $updateStmt->execute([$row[4], $row[0], $row[5]]);
                        }

                        // Recuperar os horários de entrada e saída para calcular as horas trabalhadas
                        $fetchStmt = $pdo->prepare("SELECT hora_entrada, hora_saida FROM pontoRegistro WHERE dia = ? AND idUsuario = ?");
                        $fetchStmt->execute([$row[0], $row[5]]);
                        $result = $fetchStmt->fetch(PDO::FETCH_ASSOC);

                        if ($result && $result['hora_entrada'] && $result['hora_saida']) {
                            $horaEntrada = new DateTime($result['hora_entrada']);
                            $horaSaida = new DateTime($result['hora_saida']);
                            $interval = $horaEntrada->diff($horaSaida);

                            // Calcular total de segundos trabalhados
                            $totalSeconds = ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

                            // Atualizar o campo "horas_trabalhadas" com o valor calculado (em segundos)
                            $updateHoursStmt = $pdo->prepare("UPDATE pontoRegistro SET horas_trabalhadas = ? WHERE dia = ? AND idUsuario = ?");
                            $updateHoursStmt->execute([$totalSeconds, $row[0], $row[5]]);
                        }
                    }

                    fclose($csvFile);
                    echo "<p class='success-message'>Dados importados e atualizados com sucesso!</p>";
                } else {
                    die('<p class="error-message">Erro ao enviar o arquivo: ' . $_FILES['file']['error'] . '</p>');
                }
            }
        } catch (PDOException $e) {
            die("<p class='error-message'>Erro na conexão com o banco de dados: " . $e->getMessage() . "</p>");
        }
        ?>
    </div>
</body>
</html>
