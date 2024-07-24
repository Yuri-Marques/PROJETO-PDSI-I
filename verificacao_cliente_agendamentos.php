<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['nome']) && !empty($_POST['telefone'])) {
    include_once('conexao.php');
    $nm = $_POST['nome'];
    $tel = $_POST['telefone'];
    
    // Verifica se o cliente já existe
    $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE telefone = '$tel'");
    if (mysqli_num_rows($consulta) < 1) {
        // Insere o novo cliente
        $result = mysqli_query($conex, "INSERT INTO cliente(nome, telefone) VALUES ('$nm', '$tel')");
        if ($result) {
            // Obtém o ID do novo cliente
            $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE telefone = '$tel'");
            $cliente = mysqli_fetch_assoc($consulta);
            $_SESSION['cliente_id'] = $cliente['idcliente'];
            $_SESSION['nome'] = $nm;
            $_SESSION['telefone'] = $tel;
            header('Location: ver_agendamentos.php');
            exit();
        }
    } else {
        // Verifica se o nome e o telefone correspondem a um cliente existente
        $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE nome = '$nm' AND telefone = '$tel'");
        if (mysqli_num_rows($consulta) >= 1) {
            $cliente = mysqli_fetch_assoc($consulta);
            $_SESSION['cliente_id'] = $cliente['idcliente'];
            $_SESSION['nome'] = $nm;
            $_SESSION['telefone'] = $tel;
            header('Location: ver_agendamentos.php');
            exit();
        } else {
            unset($_SESSION['nome']);
            unset($_SESSION['telefone']);
            header('Location: cliente_agendamentos.php');
            echo "Este número de telefone já existe no banco de dados. Por favor, insira outro número.";
            exit();
        }
    }
} else {
    unset($_SESSION['nome']);
    unset($_SESSION['telefone']);
    header('Location: cliente_agendamentos.php');
    // exit();
}
?>
