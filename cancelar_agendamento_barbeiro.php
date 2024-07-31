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

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hora_inicio = $_POST['hora_inicio'];
    
    // Consulta SQL para verificar se o agendamento existe
    $sql_verificar = "SELECT idagendamentos FROM agendamentos 
                      WHERE barbeiro_idbarbeiro = ? AND hora_inicio = ?";
    $stmt_verificar = $conex->prepare($sql_verificar);
    $stmt_verificar->bind_param("is", $barbeiro_id, $hora_inicio);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    
    if ($result_verificar->num_rows > 0) {
        // Agendamento existe, cancelar (deletar) o agendamento
        $sql_cancelar = "DELETE FROM agendamentos 
                         WHERE barbeiro_idbarbeiro = ? AND hora_inicio = ?";
        $stmt_cancelar = $conex->prepare($sql_cancelar);
        $stmt_cancelar->bind_param("is", $barbeiro_id, $hora_inicio);
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
        <h1>Cancelar Agendamento - <?php echo $barbeiro_nome; ?></h1>
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
            <br>
            <br>
            <a href="ver_agendamentos_barbeiro.php">Voltar</a>
        </form> 
    </div>
</body>
</html>

<?php
$conex->close();
?>
