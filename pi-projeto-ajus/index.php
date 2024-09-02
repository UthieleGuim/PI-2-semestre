<?php
include 'conexao.php';

 
$statusMsg = '';
$valErr = ''; 
$status = 'danger';

$email = isset($_POST["email"]) ? $_POST['email'] : '';
$senha = isset($_POST["senha"]) ? $_POST['senha'] : '';

session_start();
    $senha_errada = false;
    if (isset($_SESSION["adm"])) {
        $adm = $_SESSION["adm"];
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["senha"])) {
        $conn = conectar();   
        $sql = "SELECT ADM_ID, ADM_NOME, ADM_EMAIL, ADM_SENHA FROM ADMINISTRADOR WHERE ADM_EMAIL='$email'";
        $resultado = $conn->query($sql);
        $result = $resultado->fetch(PDO::FETCH_ASSOC);

        if (isset($result['ADM_ID'])) {
            if($result['ADM_SENHA'] == $senha) {
                $_SESSION["adm"] = $result;
                $adm = true;
            } else {
                $statusMsg = 'E-mail ou senha incorreto!';
                $adm = false;
            }
        } else {
            $statusMsg = 'E-mail ou senha incorreto!';
            $adm = false;
        }
    } else {
        $adm = false;
    }

    if ($adm) {
        header("Location: menu.php");
        exit();
    }
?>




<!--Tela de Login-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!--Estilização utilizada-->
    <link rel="stylesheet" type="text/css" href="styles.css"/>

    <title>PI</title>

</head>

<body>

    <header id="logoLogin">

        <img src="Delta.jpg" alt=""/>

    </header>

    <div class="filete-difente"></div>

    <section class="central">

        <!--Formulario que realiza Login-->
        <form action="" method="POST">

            <?php
                if($statusMsg){
            ?>
                <p class="error-msg"><?=$statusMsg?></p>
            <?php
                }
            ?>

            <!--Puxa dado de email-->
            <label for="email">Email</label>
            <input type="email" placeholder="Email" name="email" required/>

            <!--Puxa dado de senha-->
            <label for="senha">Senha</label>
            <input type="password" placeholder="senha" name="senha" required/>

            <button class="btn">Enviar</button>

            <p>Entre com seus dados para o Login</p>

        </form>

    </section>

</body>

</html>

