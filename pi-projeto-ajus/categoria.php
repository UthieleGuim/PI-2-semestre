<?php 
include 'conexao.php';
?>
<!--Tela de visualização de registros de categorias-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title>Categoria</title>
  
  <!--Estilização utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudo.css" />

</head>

<body>

  <!-- Contêiner principal -->
  <main>
  
  <!-- Barra de navegação -->
  <?php include "menu-lateral.php"; ?>

<!--Conteudo a ser visualizado-->
<div class="conteudo-com-tabela">


  <!-- Tabela de registros -->
  <table class="header-title">
        
        <tr style="position: sticky; top: 0;">
          
          <th class="Cabecalho">COD</th>
          <th class="Cabecalho">NOME</th>
          <th class="Cabecalho">DESC</th>
          <td class="Cabecalho"><a href="categoria2.php"><button>+</button></a></td>
        
        </tr>
        
        <?php
          
          $conn = conectar();
          // Consulta SQL para buscar todos os registros da tabela PRODUTO
          $sql = "SELECT * FROM CATEGORIA WHERE CATEGORIA_ATIVO = 1 ";
          $resultado = $conn->query($sql);
          
          while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            
            echo "<tr>";

                echo "<td class='registro'>" . $row["CATEGORIA_ID"] . "</td>";
                echo "<td class='registro'>" . $row["CATEGORIA_NOME"] . "</td>";
                echo "<td class='registro'>" . $row["CATEGORIA_DESC"] . "</td>";
                echo "<td><a class='editar_produto' href='categoria2.php?id=" . $row['CATEGORIA_ID'] . "'><img src='lapis.png' /></a></td>";
                echo "<td><a class='categoria_excluir' href='categoria_excluir.php?id=" . $row['CATEGORIA_ID'] . "'><img src='lixeira.png' /></a></td>";
                
            echo "</tr>";

            $conn = null;;
        }

        
        ?>

  </table>

</div>

</main>

</body>

</html>
