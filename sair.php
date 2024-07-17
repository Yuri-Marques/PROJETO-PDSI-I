<?php
session_start();
unset($_SESSION['usuario']);
unset($_SESSION['senha']);
unset($_SESSION['nome']);
unset($_SESSION['telefone']);
header('Location: barbearia.php');
?>