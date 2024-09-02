<?php 
include 'conexao.php';
?>
<!--Tela de visualização de registro de produto-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Produto</title>

  <!--Estilização Utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudo.css" />

</head>

<body>

  <!-- Contêiner principal -->
  <main>

    <?php include "menu-lateral.php"; ?>

    <!--Conteudo a ser visualizado-->
    <div class="conteudo-com-tabela">


      <!-- Tabela de registros -->
      <table class="header-title">
          <tr style="position: sticky; top: 0;">
            <th class="Cabecalho">COD</th>
            <th class="Cabecalho">NOME</th>
            <th class="Cabecalho">DESCRIÇÃO</th>
            <th class="Cabecalho">PREÇO</th>
            <th class="Cabecalho">DESC.</th>
            <th class="Cabecalho">QTD</th>
            <th class="Cabecalho">CATEGORIA</th>
            <td class="Cabecalho"><a href="Produto2.php"><button>+</button></a></td>
          </tr>
          
          <?php
            $conn = conectar();
            // Consulta SQL para buscar todos os registros da tabela PRODUTO
            $sql = "SELECT p.PRODUTO_ID, p.PRODUTO_NOME, p.PRODUTO_DESC, p.PRODUTO_PRECO, p.PRODUTO_DESCONTO, e.PRODUTO_QTD, c.CATEGORIA_NOME FROM PRODUTO p LEFT OUTER JOIN PRODUTO_ESTOQUE e ON p.PRODUTO_ID = e.PRODUTO_ID LEFT OUTER JOIN CATEGORIA c ON p.CATEGORIA_ID = c.CATEGORIA_ID WHERE p.PRODUTO_ATIVO = 1";
            $resultado = $conn->query($sql);
            
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td class='registro'>" . $row["PRODUTO_ID"] . "</td>";
              echo "<td class='registro'>" . $row["PRODUTO_NOME"] . "</td>";
              echo "<td class='registro'>" . $row["PRODUTO_DESC"] . "</td>";
              echo "<td class='registro'>" . $row["PRODUTO_PRECO"] . "</td>";
              echo "<td class='registro'>" . $row["PRODUTO_DESCONTO"] . "</td>";
              echo "<td class='registro'>" . $row["PRODUTO_QTD"] . "</td>";
              echo "<td class='registro'>" . $row["CATEGORIA_NOME"] . "</td>";
              echo "<td><a class='editar_produto' href='produto2.php?id=" . $row['PRODUTO_ID'] . "'><img src='lapis.png' /></a></td>";
              echo "<td><a class='produto_excluir' href='produto_excluir.php?id=" . $row['PRODUTO_ID'] . "'><img src='lixeira.png' /></a></td>";
              echo "</tr>";
            }
          ?>
      </table>
    </div>
  </main>
</body>
</html>