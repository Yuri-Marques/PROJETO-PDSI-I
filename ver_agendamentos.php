<?php
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['telefone'])) {
    header('Location: cliente_agendamentos.php'); // Redireciona se não estiver logado
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

// Obtém o ID do cliente da sessão
$cliente_id = $_SESSION['cliente_id'];

// Consulta SQL para obter os agendamentos do cliente
$sql_agendamentos = "SELECT a.idagendamentos, b.nome AS nome_barbeiro, s.nome_servico, a.data, a.hora_inicio
                     FROM agendamentos a
                     INNER JOIN barbeiro b ON a.barbeiro_idbarbeiro = b.idbarbeiro
                     INNER JOIN servico s ON a.servico_idservico = s.idservico
                     WHERE a.cliente_idcliente = ?
                     ORDER BY a.data DESC, a.hora_inicio DESC";

$stmt = $conex->prepare($sql_agendamentos);
$stmt->bind_param("i", $cliente_id);
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
        <h1>Ver Agendamentos</h1>
        <br>
        <table>
            <tr>
                <th>Barbeiro</th>
                <th>Serviço</th>
                <th>Data</th>
                <th>Horário</th>
            </tr>
            <?php
            // Loop para exibir os agendamentos
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['nome_barbeiro'] . "</td>";
                echo "<td>" . $row['nome_servico'] . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($row['data'])) . "</td>";
                echo "<td>" . $row['hora_inicio'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <br>
        <a href="cancelar_agendamento.php">Cancelar agendamento</a>
        <br>
        <a href="sair.php">Sair</a> <!-- Link para logout -->
    </div>
</body>
</html>

<?php
// Fecha o statement e a conexão
$stmt->close();
$conex->close();
?>
