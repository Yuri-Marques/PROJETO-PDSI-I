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

// Obtém o id do barbeiro logado
$usuario = $_SESSION['usuario'];
$sql_barbeiro = "SELECT idbarbeiro FROM barbeiro WHERE usuario = '$usuario'";
$result_barbeiro = $conex->query($sql_barbeiro);

if ($result_barbeiro->num_rows > 0) {
    $row_barbeiro = $result_barbeiro->fetch_assoc();
    $barbeiro_id = $row_barbeiro['idbarbeiro'];

    // Obtém a lista de serviços da tabela 'servico'
    $sql_servicos = "SELECT idservico, nome_servico FROM servico";
    $result_servicos = $conex->query($sql_servicos);

    // Obtém a lista de horários disponíveis para o barbeiro logado
    $sql_horarios = "SELECT idhorario, hora_inicio, hora_fim FROM horarios_disponiveis WHERE barbeiro_idbarbeiro = $barbeiro_id AND data >= CURDATE()";
    $result_horarios = $conex->query($sql_horarios);
} else {
    die("Erro: Barbeiro não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARBEARIA</title>
    <link rel="stylesheet" href="telas_cliente.css">
</head>
<body>
    <img src="./logo.png" alt="Logo da Barbearia">
    <div>
       <h1> Agendar Serviço para Cliente </h1>
       <?php
       if(isset($_SESSION['message'])) {
           echo "<p>" . $_SESSION['message'] . "</p>";
           unset($_SESSION['message']);
       }
       ?>
       <form method="POST" action="tela_horarios2.php">
            <label for="nome_cliente">Nome do Cliente:</label>
            <input type="text" id="nome_cliente" name="nome_cliente" required>
            <br><br>
            <label for="telefone_cliente">Telefone do Cliente:</label>
            <input type="text" id="telefone_cliente" name="telefone_cliente" required>
            <br><br>
            <label for="servico">Escolha o serviço:</label>
            <select name="servico_id" id="servico" required>
                <option value=""></option>
                <?php
                    // Loop para exibir os serviços disponíveis
                    while ($row = $result_servicos->fetch_assoc()) {
                        echo "<option value='" . $row['idservico'] . "'>" . $row['nome_servico'] . "</option>";
                    }
                ?>
            </select>
            <br><br>
            <label for="horario">Escolha o horário:</label>
            <select name="horario_id" id="horario" required>
                <option value=""></option>
                <?php
                    // Loop para exibir os horários disponíveis
                    while ($row = $result_horarios->fetch_assoc()) {
                        echo "<option value='" . $row['idhorario'] . "'>" . $row['hora_inicio'] . " - " . $row['hora_fim'] . "</option>";
                    }
                ?>
            </select>
            <br><br>
            <input type="hidden" name="barbeiro_id" value="<?php echo $barbeiro_id; ?>">
            <input type="submit" value="Continuar">
            <br><br>
            <a href="aba_adm.php">Voltar</a> <!-- Link para logout -->
       </form>
    </div>
</body>
</html>
