<?php
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['telefone'])) {
    header('Location: cliente_agendar.php');
    exit;
}

// Configurações de conexão
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '#Yuri9874';
$dbName = 'barbearia';

// Conexão com o banco de dados
$conex = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Verifica se houve erro na conexão
if ($conex->connect_error) {
    die("Erro na conexão: " . $conex->connect_error);
}

// Obtém a lista de barbeiros da tabela 'barbeiro'
$sql_barbeiros = "SELECT idbarbeiro, nome FROM barbeiro";
$result_barbeiros = $conex->query($sql_barbeiros);

// Obtém a lista de serviços da tabela 'servico'
$sql_servicos = "SELECT idservico, nome_servico FROM servico";
$result_servicos = $conex->query($sql_servicos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha um Barbeiro e Serviço</title>
    <link rel="stylesheet" href="telas_cliente.css">
</head>
<body>
    <img src="./logo.png" alt="Logo da Barbearia">
    <div>
       <h1> Escolha um Barbeiro e Serviço </h1>
       <form method="POST" action="tela_horarios.php">
            <label for="barbeiro">Escolha o barbeiro:</label>
            <select name="barbeiro_id" id="barbeiro">
                <option value=""></option>
                <?php
                    // Loop para exibir os barbeiros disponíveis
                    while ($row = $result_barbeiros->fetch_assoc()) {
                        echo "<option value='" . $row['idbarbeiro'] . "'>" . $row['nome'] . "</option>";
                    }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Selecionar">
       </form>
       <br><br>
       <a href="sair.php">Sair</a> <!-- Link para logout -->
    </div>
</body>
</html>
