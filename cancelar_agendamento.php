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

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hora_inicio = $_POST['hora_inicio'];
    
    // Consulta SQL para verificar se o agendamento existe
    $sql_verificar = "SELECT idagendamentos FROM agendamentos 
                      WHERE cliente_idcliente = ? AND hora_inicio = ?";
    $stmt_verificar = $conex->prepare($sql_verificar);
    $stmt_verificar->bind_param("is", $cliente_id, $hora_inicio);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    
    if ($result_verificar->num_rows > 0) {
        // Agendamento existe, cancelar (deletar) o agendamento
        $sql_cancelar = "DELETE FROM agendamentos 
                         WHERE cliente_idcliente = ? AND hora_inicio = ?";
        $stmt_cancelar = $conex->prepare($sql_cancelar);
        $stmt_cancelar->bind_param("is", $cliente_id, $hora_inicio);
        if ($stmt_cancelar->execute()) {
            $mensagem = "Agendamento cancelado com sucesso.";
        } else {
            $mensagem = "Erro ao cancelar o agendamento.";
        }
        $stmt_cancelar->close();
    } else {
        $mensagem = "Agendamento não encontrado.";
    }
    $stmt_verificar->close();
}

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
        <h1>Cancelar Agendamento</h1>
        <br>
        <?php
        if (isset($mensagem)) {
            echo "<p>$mensagem</p>";
        }
        ?>
        <form method="POST" action="">
            <label for="hora_inicio">Horário do agendamento:</label>
            <input type="time" id="hora_inicio" name="hora_inicio" required>
            <br><br>
            <button type="submit">Cancelar agendamento</button>
        </form>
        <br>
        <a href="ver_agendamentos.php">Voltar</a>
    </div>
</body>
</html>

<?php
$conex->close();
?>
