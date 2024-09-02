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
  $nome = $_POST['categNome'];
  $desc = $_POST['categDesc'];

  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    //Insere os dados no banco de dados
    $sql = "UPDATE CATEGORIA SET CATEGORIA_NOME = :nome, CATEGORIA_DESC = :desc WHERE CATEGORIA_ID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["nome" => $nome, "desc" => $desc, "id" => $id]);
  } else {
    //Insere os dados no banco de dados
    $sql = "INSERT INTO CATEGORIA (CATEGORIA_NOME, CATEGORIA_DESC, CATEGORIA_ATIVO) VALUES (:nome, :desc, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["nome" => $nome, "desc" => $desc]);
  }

  header("Location: categoria.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT CATEGORIA_NOME, CATEGORIA_DESC FROM CATEGORIA WHERE CATEGORIA_ID = :id AND CATEGORIA_ATIVO = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $id]);
    $resultado = $stmt->fetch();
    if (!$resultado) die("Não existe esta categoria.");
    $nome = $resultado["CATEGORIA_NOME"];
    $desc = $resultado["CATEGORIA_DESC"];
  } else {
    $id = "";
    $nome = "";
    $desc = "";
  }
}
?>

<!--Tela de cadastro de categorias-->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Categoria</title>
    
    <!--Estilização Utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudoproduto2.css" />

</head>

<body>

  <main>
    <!--Navbar Lateral-->
    <?php include "menu-lateral.php"; ?>

<div class="central">

    <!--Formulario de cadstro de categoria-->
    <form action="categoria2.php" method="POST">
    
        <h1>Categoria</h1>
        <?php if ($id !== "") { ?><input type="hidden" name="id" value="<?= $id ?>"/><?php } ?>
        <input type="text" name="categNome" placeholder="Nome Categoria" value="<?= $nome ?>"/>
        <textarea name="categDesc" placeholder="Descreva a categoria que será cadastrada..." ><?= $desc ?></textarea>
        <!--Realiza a ativação do cadastro-->
        <button class="">Cadastrar</button>
    
    </form>
    <?php if ($id !== "") { ?>
        <form action="categoria-excluir.php" method='POST'>
          <input type="hidden" name="id" value="<?= $id ?>"/>
          <button type="submit">DELETAR</button>
        </form>
      <?php } ?>

</div>

</body>

</html>