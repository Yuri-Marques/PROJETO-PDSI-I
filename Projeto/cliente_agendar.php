<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BARBEARIA</title>
    <link rel="stylesheet" href="cliente.css">
</head>
<body>
    <img src="./logo.png"></img>
    <div>
        <h1>INFORME SEUS DADOS</h1>
        <form action="verificacao_cliente_agenda.php" method="POST">
        <input type="text" name="nome" placeholder="Nome">
        <br><br>
        <input type="tel" name="telefone" placeholder="Telefone (DDD)">
        <br><br>
        <input type="submit" name="submit" id="submit" class="button">
        <br><br>
    </div>
</body>
</html>
