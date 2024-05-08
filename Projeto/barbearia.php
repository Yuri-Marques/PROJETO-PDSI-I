<?php
session_start();
unset($_SESSION['usuario']);
unset($_SESSION['senha']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARBEARIA</title>
    <link rel="stylesheet" href="barbearia.css">
</head>
<body>
    <img src="./logo.png"></img>
    <div class="botoes">
        <a href="login.php">ADMINISTRADOR</a>
        <br><br>
        <a href="cliente.php">AGENDAMENTOS</a>
        <br><br>
        <a href="cliente.php">AGENDAR</a>
    </div>
    <footer>
        <a href="https://web.whatsapp.com" target="_blank" rel="noopener noreferrer">
            <img src="pngegg.png">
            <p>WHATSAPP</p>
        </a>
        <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer">
            <img src="instagram.png">
            <p>INSTAGRAM</p>
        </a>
    </footer>
</body>
</html>