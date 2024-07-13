<?php
session_start();

if(isset($_POST['submit']) && !empty($_POST['nome']) && !empty($_POST['telefone'])){
    include_once('conexao.php');
    
    $nm = $_POST['nome'];
    $tel = $_POST['telefone'];
    
    // Verifica se o cliente já existe pelo telefone
    $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE telefone = '$tel'");
    
    if(mysqli_num_rows($consulta) < 1){
        // Se não existe, insere o cliente
        $result = mysqli_query($conex, "INSERT INTO cliente(nome, telefone) VALUES ('$nm', '$tel')");
        
        if($result) {
            // Obtém o ID do cliente inserido
            $cliente_id = mysqli_insert_id($conex);
            
            // Define as variáveis de sessão
            $_SESSION['cliente_id'] = $cliente_id;
            $_SESSION['nome'] = $nm;
            $_SESSION['telefone'] = $tel;
            
            // Redireciona para a página de agendamento
            header('Location: agendar.php');
            exit();
        }
    } else {
        // Se já existe, verifica se nome e telefone correspondem
        $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE nome = '$nm' and telefone = '$tel'");
        
        if(mysqli_num_rows($consulta) >= 1){
            // Obtém o ID do cliente existente
            $cliente = mysqli_fetch_assoc($consulta);
            $cliente_id = $cliente['idcliente'];
            
            // Define as variáveis de sessão
            $_SESSION['cliente_id'] = $cliente_id;
            $_SESSION['nome'] = $nm;
            $_SESSION['telefone'] = $tel;
            
            // Redireciona para a página de agendamento
            header('Location: agendar.php');
            exit();
        }
        
        // Caso contrário, limpa a sessão e redireciona para a página de login
        unset($_SESSION['cliente_id']);
        unset($_SESSION['nome']);
        unset($_SESSION['telefone']);
        header('Location: cliente_agendar.php');
        echo "Este número de telefone já existe no banco de dados. Por favor, insira outro número.";
        exit();
    }
} else {
    // Se não foram enviados os dados corretos, limpa a sessão e redireciona para a página de login
    unset($_SESSION['cliente_id']);
    unset($_SESSION['nome']);
    unset($_SESSION['telefone']);
    header('Location: cliente_agendar.php');
    //exit();
}
?>
