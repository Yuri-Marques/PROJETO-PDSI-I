<?php
session_start();
if((!isset($_SESSION['nome']) == true) and (!isset($_SESSION['telefone']) == true)){
	unset($_SESSION['nome']);
	unset($_SESSION['telefone']);
	header('Location: cliente_agendamentos.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> VER AGENDAMENTOS</h1>
    <br><br>
    <a href="sair.php">sair</a>
</body>
</html>
