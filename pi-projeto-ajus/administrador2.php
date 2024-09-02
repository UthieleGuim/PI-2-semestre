<?php

//Função de conexãoi principal
Include 'conexao.php';

//Atribui conexão
$conn = conectar();

//Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
  
  //Verifica se a conexão foi bem sucedida
  if (!$conn) {
    die('Erro ao conectar ao banco de dados');
  } 

  //Obtém os dados do formulário
  $nome = $_POST['usuarioAdm'];
  $email = $_POST['emailAdm'];
  $senha = $_POST['senhaAdm'];
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    //Insere os dados no banco de dados
    $sql = "UPDATE ADMINISTRADOR SET ADM_NOME = :nome, ADM_EMAIL = :email, ADM_SENHA = :senha WHERE ADM_ID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["nome" => $nome, "email" => $email, "senha" => $senha, "id" => $id]);
  } else {
    //Insere os dados no banco de dados
    $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) VALUES (:nome, :email, :senha, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["nome" => $nome, "email" => $email, "senha" => $senha]);
  }

  header("Location: administrador.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT ADM_NOME, ADM_EMAIL, ADM_SENHA FROM ADMINISTRADOR WHERE ADM_ID = :id AND ADM_ATIVO = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $id]);
    $resultado = $stmt->fetch();
    if (!$resultado) die("Não existe este administrador.");
    $nome = $resultado["ADM_NOME"];
    $email = $resultado["ADM_EMAIL"];
    $senha = $resultado["ADM_SENHA"];
  } else {
    $id = "";
    $nome = "";
    $email = "";
    $senha = "";
  }
}
?>
<!--Tela de cadastro de administradores do site-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title>Administrador</title>
  
  <!--Estilização Utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudoproduto2.css" />

</head>

<body>
  <main>
  <?php include "menu-lateral.php"; ?>
 
  <!--Formulário de cadastro de adminstradores-->
  <div class="central">
    <form action="administrador2.php" method="POST">
      <h1>Administrador</h1>
    
      <?php if ($id !== "") { ?><input type="hidden" name="id" value="<?= $id ?>"/><?php } ?>
      <input type="text" name="usuarioAdm" placeholder="Usuário" value="<?= $nome ?>"/> <!--Pega Nome do Administrador-->
      <input type="email" name="emailAdm" placeholder="Email" value="<?= $email ?>" />  <!--Pega Email do Administrador-->
      <input type="password" name="senhaAdm" placeholder="Senha" value="<?= $senha ?>" /> <!--Pega Senha do Administrador-->

      <!--Ativa cadastro do formulario-->
      <button type="submit">Cadastrar</button>
    
    </form>
    <?php if ($id !== "") { ?>
        <form action="administrador-excluir.php" method='POST'>
          <input type="hidden" name="id" value="<?= $id ?>"/>
          <button type="submit">DELETAR</button>
        </form>
      <?php } ?>

  </div>

</body>

</html>