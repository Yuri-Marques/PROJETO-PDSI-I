<?php
session_start();

// Verifica se o barbeiro está logado
if((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)){
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
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

// Verifica se os dados do formulário foram enviados
if (isset($_POST['nome_cliente']) && isset($_POST['telefone_cliente']) && isset($_POST['servico_id']) && isset($_POST['horario_id']) && isset($_POST['barbeiro_id'])) {
    // Obtém os dados do formulário
    $nome_cliente = $_POST['nome_cliente'];
    $telefone_cliente = $_POST['telefone_cliente'];
    $servico_id = $_POST['servico_id'];
    $horario_id = $_POST['horario_id'];
    $barbeiro_id = $_POST['barbeiro_id'];

    // Obtém a data e hora do horário selecionado
    $sql_horario = "SELECT data, hora_inicio, hora_fim FROM horarios_disponiveis WHERE idhorario = $horario_id";
    $result_horario = $conex->query($sql_horario);

    if ($result_horario->num_rows > 0) {
        $row_horario = $result_horario->fetch_assoc();
        $data = $row_horario['data'];
        $hora_inicio = $row_horario['hora_inicio'];
        $hora_fim = $row_horario['hora_fim'];

        // Insere o cliente na tabela 'cliente'
        $sql_cliente = "INSERT INTO cliente (nome, telefone) VALUES ('$nome_cliente', '$telefone_cliente')";
        if ($conex->query($sql_cliente) === TRUE) {
            $cliente_id = $conex->insert_id;

            // Insere o agendamento na tabela 'agendamentos'
            $sql_agendamento = "INSERT INTO agendamentos (barbeiro_idbarbeiro, cliente_idcliente, servico_idservico, data, hora_inicio, hora_fim) 
                                VALUES ('$barbeiro_id', '$cliente_id', '$servico_id', '$data', '$hora_inicio', '$hora_fim')";

            if ($conex->query($sql_agendamento) === TRUE) {
                $_SESSION['message'] = "Agendamento realizado com sucesso!";
            } else {
                $_SESSION['message'] = "Erro ao realizar o agendamento: " . $conex->error;
            }
        } else {
            $_SESSION['message'] = "Erro ao inserir cliente: " . $conex->error;
        }
    } else {
        $_SESSION['message'] = "Horário não encontrado.";
    }
} else {
    $_SESSION['message'] = "Dados do formulário não enviados corretamente.";
}

$conex->close();
header('Location: agendar2.php');
exit;
?>
