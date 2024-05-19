<?php
session_start();

if(isset($_POST['submit']) && !empty($_POST['nome']) && !empty($_POST['telefone'])){
    include_once('conexao.php');
    $nm = $_POST['nome'];
    $tel = $_POST['telefone'];
    $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE telefone = '$tel'");
    if(mysqli_num_rows($consulta) < 1){
        $result = mysqli_query($conex, "INSERT INTO cliente(nome, telefone) VALUES ('$nm', '$tel')");
        if($result) {
            $_SESSION['nome'] = $nm;
            $_SESSION['telefone'] = $tel;
            header('Location: ver_agendamentos.php');
            exit();
        }
    }
    else{
        $consulta = mysqli_query($conex, "SELECT * FROM cliente WHERE nome = '$nm' and telefone = '$tel'");
        if(mysqli_num_rows($consulta) >= 1){
            $_SESSION['nome'] = $nm;
            $_SESSION['telefone'] = $tel;
            header('Location: ver_agendamentos.php');
            exit();
        }
        unset($_SESSION['nome']);
        unset($_SESSION['telefone']);
        header('Location: cliente_agendamentos.php');
        echo "Este número de telefone já existe no banco de dados. Por favor, insira outro número.";
        exit();
    }
}
else{
    unset($_SESSION['nome']);
    unset($_SESSION['telefone']);
    header('Location: cliente_agendamentos.php');
   // exit();
}
?>
