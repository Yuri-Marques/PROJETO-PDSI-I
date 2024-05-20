<?php
session_start();
if((!isset($_SESSION['nome']) == true) and (!isset($_SESSION['telefone']) == true)){
	unset($_SESSION['nome']);
	unset($_SESSION['telefone']);
	header('Location: cliente_agendar.php');
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
    <img src="./logo.png"></img>
    <div>
       <h1> AGENDAR</h1>
        <br><br>
        <a href="sair.php">sair</a> 
    </div>
</body>
</html>
