<?php
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: cliente_agendar.php');
    exit;
}

// Verifica se foram recebidos os dados necessários via POST
if (!isset($_POST['barbeiro_id']) || !isset($_POST['hora_inicio'])) {
    header('Location: escolha_barbeiro.php');
    exit;
}

$barbeiro_id = $_POST['barbeiro_id'];
$hora_inicio = $_POST['hora_inicio'];

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

// Obtém a data atual
$data_atual = date('Y-m-d');

// Insere o agendamento na tabela 'agendamentos'
$sql_agendar = "INSERT INTO agendamentos (barbeiro_idbarbeiro, cliente_idcliente, data, hora_inicio, hora_fim)
                VALUES (?, ?, ?, ?, ADDTIME(?, '00:30:00'))";

// Obtém o ID do cliente da sessão
$cliente_id = $_SESSION['cliente_id'];

// Prepara a declaração SQL
$stmt = $conex->prepare($sql_agendar);
$stmt->bind_param("iisss", $barbeiro_id, $cliente_id, $data_atual, $hora_inicio, $hora_inicio);

// Executa a declaração SQL
if ($stmt->execute()) {
    // Redireciona para página de sucesso
    header('Location: barbearia.php');
    exit;
} else {
    // Em caso de erro, redireciona para página de erro
    header('Location: agendamento_erro.php');
    exit;
}

$stmt->close();
$conex->close();
?>
