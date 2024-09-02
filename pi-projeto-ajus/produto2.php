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
  $nome = $_POST['nome'];
  $descri = $_POST['descri'];
  $categoria = $_POST['categoria'];
  $preco = $_POST['preco'];
  $desconto = $_POST['desconto'];
  $estoque = $_POST['estoque'];

  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    //Insere os dados no banco de dados
    $sql = "UPDATE PRODUTO SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descri, CATEGORIA_ID = :categoria, PRODUTO_PRECO = :preco, PRODUTO_DESCONTO = :desconto WHERE PRODUTO_ID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["nome" => $nome, "descri" => $descri, "categoria" => $categoria, "preco" => $preco, "desconto" => $desconto, "id" => $id]);

    //Insere os dados no banco de dados
    $sql = "UPDATE PRODUTO_ESTOQUE SET PRODUTO_QTD = :estoque WHERE PRODUTO_ID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["estoque" => $estoque, "id" => $id]);
  } else {
    //Insere os dados no banco de dados
    $sql = "INSERT INTO PRODUTO (PRODUTO_NOME, PRODUTO_DESC, CATEGORIA_ID, PRODUTO_PRECO, PRODUTO_DESCONTO, PRODUTO_ATIVO) VALUES (:nome, :descri, :categoria, :preco, :desconto, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["nome" => $nome, "descri" => $descri, "categoria" => $categoria, "preco" => $preco, "desconto" => $desconto]);
    $id = $conn->lastInsertId();

    //Insere os dados no banco de dados
    $sql = "INSERT INTO PRODUTO_ESTOQUE (PRODUTO_ID, PRODUTO_QTD) VALUES (:id, :estoque)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["estoque" => $estoque, "id" => $id]);
  }

  header("Location: produto.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT p.PRODUTO_ID, p.PRODUTO_NOME, p.PRODUTO_DESC, p.PRODUTO_PRECO, p.PRODUTO_DESCONTO, e.PRODUTO_QTD, c.CATEGORIA_ID FROM PRODUTO p LEFT OUTER JOIN PRODUTO_ESTOQUE e ON p.PRODUTO_ID = e.PRODUTO_ID LEFT OUTER JOIN CATEGORIA c ON p.CATEGORIA_ID = c.CATEGORIA_ID WHERE p.PRODUTO_ID = :id AND p.PRODUTO_ATIVO = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $id]);
    $resultado = $stmt->fetch();
    if (!$resultado) die("Não existe este produto.");
    $nome = $resultado["PRODUTO_NOME"];
    $descri = $resultado["PRODUTO_DESC"];
    $categoria = $resultado["CATEGORIA_ID"];
    $preco = $resultado["PRODUTO_PRECO"];
    $desconto = $resultado["PRODUTO_DESCONTO"];
    $estoque = $resultado["PRODUTO_QTD"];
  } else {
    $id = "";
    $nome = "";
    $descri = "";
    $categoria = "";
    $preco = "";
    $desconto = "";
    $estoque = "";
  }
}
?>

<!--Tela de cadastro de produto-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Produto</title>

  <!--Estilização Utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudoproduto2.css" />
</head>

<body>
  <main>
  <?php include "menu-lateral.php"; ?>


  <div class="central">

      <!--Formulario de cadstro-->
      <form action="produto2.php" method='POST'>
      
        <h1>Produto</h1>
        <?php if ($id !== "") { ?><input type="hidden" name="id" value="<?= $id ?>"/><?php } ?>
        <p>
          <label for="nome">Nome do produto</label> <!--Pega dados de nome do produto-->
          <input type="text" placeholder="" id="nome" name="nome" value="<?= $nome ?>" required />
        </p>

        <p>
          <label for="descri">Descrição</label> <!--Pega dados de descrição do produto-->
          <input type="text" placeholder="" id="descri" name="descri" value="<?= $descri ?>" required/>
        </p>

        <p>
          <label for="estoque">Estoque</label> <!--Pega dados de descrição do produto-->
          <input type="number" placeholder="" id="estoque" name="estoque" value="<?= $estoque ?>" required/>
        </p>

        <p>
          <label for="categoria">Categoria</label> <!--Pega dados de categoria do produto-->
          <select name="categoria" id="categoria" value="<?= $categoria ?>" required>

            <!--Faz select de options que busca id de categorias do banco-->
            <?php
              $conn = conectar();

              $query = 'SELECT CATEGORIA_ID, CATEGORIA_NOME FROM CATEGORIA WHERE CATEGORIA_ATIVO = 1';
              $result = $conn->query($query);

              while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                  $categoria_id = $row['CATEGORIA_ID'];
                  $nome = $row['CATEGORIA_NOME'];
                  echo "<option value='$categoria_id' " . ($categoria_id === $categoria ? 'selected' : '') . ">$nome</option>";
                }

              $conn = null;
            ?>

          </select>
        </p>
          
        <!--Switch que mostra ativado ou desativado-->
        <!--<label class="switch">
            <input type="checkbox" checked name="ativo">
            <span class="slider round"></span>
        </label>-->
      
        <p>
          <label for="preco">Preço</label> <!--Pega dados de preço do produto-->
          <input type="number" step="0.01" placeholder="" value="<?= $preco ?>" name="preco" min="0" max="999.99" required>
        </p>
        <p>
          <label for="desconto">Desconto</label> <!--Pega dados de desconto no preço do produto-->
          <input type="number" step="0.01" placeholder=""  value="<?= $desconto ?>" name="desconto" min="0" max="999.99" required>
        </p>
        
        <!--Botão de cadastro-->
        <button type="submit">Cadastrar</button>

      </form>

      <?php if ($id !== "") { ?>
        <form action="produto-excluir.php" method='POST'>
          <input type="hidden" name="id" value="<?= $id ?>"/>
          <button type="submit">DELETAR</button>
        </form>
      <?php } ?>

    </div> 
  </main>
</body>

</html>
