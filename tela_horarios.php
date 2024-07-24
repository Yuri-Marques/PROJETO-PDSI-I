<?php
session_start();

// Verifica se o cliente está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['telefone'])) {
    header('Location: cliente_agendar.php');
    exit;
}

// Verifica se foi recebido um ID de barbeiro válido via POST
if (!isset($_POST['barbeiro_id']) || empty($_POST['barbeiro_id'])) {
    header('Location: escolha_barbeiro.php');
    exit;
}

$barbeiro_id = $_POST['barbeiro_id'];

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

// Função para obter os horários disponíveis que ainda não foram agendados
function getHorariosDisponiveis($conex, $barbeiro_id) {
    $data_atual = date('Y-m-d');
    
    // Consulta SQL para obter horários disponíveis que não foram agendados
    $sql = "SELECT hd.hora_inicio, hd.hora_fim 
            FROM horarios_disponiveis hd
            LEFT JOIN agendamentos a 
            ON hd.barbeiro_idbarbeiro = a.barbeiro_idbarbeiro 
            AND hd.data = a.data 
            AND hd.hora_inicio = a.hora_inicio
            WHERE hd.barbeiro_idbarbeiro = ? 
            AND hd.data = ?
            AND a.idagendamentos IS NULL
            ORDER BY hd.hora_inicio";

    $stmt = $conex->prepare($sql);
    $stmt->bind_param("is", $barbeiro_id, $data_atual);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $horarios[] = $row;
        }
        return $horarios;
    } else {
        return []; // Retorna array vazio se não houver horários disponíveis
    }
}

// Obtém o nome do barbeiro selecionado
$sql_nome_barbeiro = "SELECT nome FROM barbeiro WHERE idbarbeiro = ?";
$stmt_nome_barbeiro = $conex->prepare($sql_nome_barbeiro);
$stmt_nome_barbeiro->bind_param("i", $barbeiro_id);
$stmt_nome_barbeiro->execute();
$result_nome_barbeiro = $stmt_nome_barbeiro->get_result();
$row_nome_barbeiro = $result_nome_barbeiro->fetch_assoc();
$nome_barbeiro = $row_nome_barbeiro['nome'];

// Obtém a lista de serviços disponíveis
$sql_servicos = "SELECT idservico, nome_servico FROM servico";
$result_servicos = $conex->query($sql_servicos);
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
       <h1> Horários Disponíveis - <?php echo $nome_barbeiro; ?> </h1>
       <form method="POST" action="processar_agendamento.php">
            <input type="hidden" name="barbeiro_id" value="<?php echo $barbeiro_id; ?>">
            <label for="horario">Escolha o horário:</label>
            <select name="hora_inicio" id="horario">
                <?php
                    // Verifica se há horários disponíveis
                    $horarios_disponiveis = getHorariosDisponiveis($conex, $barbeiro_id);
                    if (!empty($horarios_disponiveis)) {
                        foreach ($horarios_disponiveis as $horario) {
                            echo "<option value='" . $horario['hora_inicio'] . "'>" . $horario['hora_inicio'] . " - " . $horario['hora_fim'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum horário disponível para hoje.</option>";
                    }
                ?>
            </select>
            <br><br>
            <label for="servico">Escolha o serviço:</label>
            <select name="servico_id" id="servico">
                <?php
                    // Loop para exibir os serviços disponíveis
                    while ($row = $result_servicos->fetch_assoc()) {
                        echo "<option value='" . $row['idservico'] . "'>" . $row['nome_servico'] . "</option>";
                    }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Agendar">
       </form>
       <br><br>
       <a href="sair.php">Sair</a> <!-- Link para logout -->
    </div>
</body>
</html>