<?php
include_once('conexao.php');

if(isset($_POST['nome'], $_POST['cpf'], $_POST['email'], $_POST['telefone'], $_POST['usuario'], $_POST['senha'])) {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $result = mysqli_query($conex, "INSERT INTO barbeiro(nome, cpf, email, telefone, usuario, senha) VALUES ('$nome', '$cpf', '$email', '$telefone', '$usuario', '$senha')");
    header('Location: aba_adm.php');
} else {
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARBEARIA</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <img src="./logo.png"></img>
    <div class="cad">
        <form action="cadastro.php" method="post">
            <fieldset>
                <legend> <b>Cadastro de barbeiro</b> </legend>
                <br>
                <div class="inputbox">
                    <input type="text" name="nome" id="nome" class="inputuser" required>
                    <label for="nome" class="labelinput">Nome</label>
                </div>
                <br>
                <div class="inputbox">
                    <input type="number" name="cpf" id="cpf" class="inputuser" required>
                    <label for="nome" class="labelinput">CPF</label>
                </div>
                <br>
                <div class="inputbox">
                    <input type="text" name="email" id="email" class="inputuser" required>
                    <label for="nome" class="labelinput">E-mail</label>
                </div>
                <br>
                <div class="inputbox">
                    <input type="tel" name="telefone" id="telefone" class="inputuser" required>
                    <label for="nome" class="labelinput">Telefone</label>
                </div>
                <br>
                <div class="inputbox">
                    <input type="text" name="usuario" id="usuario" class="inputuser" required>
                    <label for="nome" class="labelinput">Usuário</label>
                </div>
                <br>
                <div class="inputbox">
                    <input type="password" name="senha" id="senha" class="inputuser" required>
                    <label for="nome" class="labelinput">Senha</label>
                </div>
                <br>
                <input type="submit" name="submit" value="Enviar">
            </fieldset>
        </form>
    </div>
</body>
</html>