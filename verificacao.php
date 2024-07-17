<?php
session_start();
if(isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['senha'])){
    include_once('conexao.php');
    $us = $_POST['usuario'];
    $se = $_POST['senha'];
    $sql = "SELECT * FROM barbeiro WHERE usuario = '$us' and senha = '$se'";
    
    $result = $conex->query($sql);
    
    if(mysqli_num_rows($result) < 1){
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        header('Location: login.php');
    }
    else{
        $_SESSION['usuario'] = $us;
        $_SESSION['senha'] = $se;
        header('Location: aba_adm.php');
    }
}
else{
    header('Location: login.php');
}

?>