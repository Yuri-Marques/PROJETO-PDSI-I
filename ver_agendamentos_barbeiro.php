<?php
session_start();

// Verifica se o barbeiro está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    header('Location: barbeiro_agendamentos.php'); // Redireciona se não estiver logado
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

// Prepara a consulta SQL para obter o ID do barbeiro usando usuário e senha
$sql_auth = "SELECT idbarbeiro, nome FROM barbeiro WHERE usuario = ? AND senha = ?";
$stmt_auth = $conex->prepare($sql_auth);
$stmt_auth->bind_param("ss", $_SESSION['usuario'], $_SESSION['senha']);
$stmt_auth->execute();
$result_auth = $stmt_auth->get_result();

// Verifica se encontrou o barbeiro
if ($result_auth->num_rows == 1) {
    // Obtém o ID e o nome do barbeiro
    $row_auth = $result_auth->fetch_assoc();
    $barbeiro_id = $row_auth['idbarbeiro'];
    $barbeiro_nome = $row_auth['nome'];
} else {
    // Caso não encontre o barbeiro, redireciona para a página de login
    header('Location: barbeiro_agendamentos.php');
    exit;
}

// Data atual
$data_atual = date('Y-m-d');

// Consulta SQL para obter os agendamentos do barbeiro no dia atual
$sql_agendamentos = "SELECT a.idagendamentos, c.nome AS nome_cliente, s.nome_servico, a.data, a.hora_inicio
                     FROM agendamentos a
                     INNER JOIN cliente c ON a.cliente_idcliente = c.idcliente
                     INNER JOIN servico s ON a.servico_idservico = s.idservico
                     WHERE a.barbeiro_idbarbeiro = ? AND a.data = ?
                     ORDER BY a.hora_inicio DESC";

$stmt = $conex->prepare($sql_agendamentos);
$stmt->bind_param("is", $barbeiro_id, $data_atual);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARBEARIA</title>
    <link rel="stylesheet" href="telas_cliente.css">
</head>
<body>
    <img src="./logo.png" alt="Logo da Barbearia">
    <div>
        <h1>Ver Agendamentos - <?php echo $barbeiro_nome; ?></h1>
        <br>
        <table>
            <tr>
                <th>Barbeiro</th> <!-- Novo cabeçalho -->
                <th>Cliente</th>
                <th>Serviço</th>
                <th>Data</th>
                <th>Horário</th>
            </tr>
            <?php
            // Loop para exibir os agendamentos
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $barbeiro_nome . "</td>"; // Exibe o nome do barbeiro
                echo "<td>" . $row['nome_cliente'] . "</td>";
                echo "<td>" . $row['nome_servico'] . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($row['data'])) . "</td>";
                echo "<td>" . $row['hora_inicio'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <br>
        <a href="cancelar_agendamento_barbeiro.php">Cancelar agendamento</a>
        <br>
        <a href="aba_adm.php">Voltar</a> <!-- Link para logout -->
    </div>
</body>
</html>

<?php
// Fecha o statement e a conexão
$stmt_auth->close();
$stmt->close();
$conex->close();
?>
