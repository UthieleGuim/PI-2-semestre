<?php 
include 'conexao.php';
?>
<!--Tela de visualização de Registros de imagem-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Imagem</title>

  <!--Estilização Utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudo.css" />

</head>

<body>
  <main>
        <!-- Barra de navegação -->

  <?php include "menu-lateral.php"; ?>
    
 <!--Conteudo a ser visualizado-->
<div class="conteudo-com-tabela">


<!-- Tabela de registros -->
<table class="header-title">
      
      <tr style="position: sticky; top: 0;">
        
        <th class="Cabecalho">COD</th>
        <th class="Cabecalho">ORDEM</th>
        <th class="Cabecalho">CODPROD</th>
        <th class="Cabecalho">URL</th>
        <td class="Cabecalho"><a href="imagem2.php"><button>+</button></a></td>
      
      </tr>
      
      <?php
        
        $conn = conectar();
        // Consulta SQL para buscar todos os registros da tabela PRODUTO
        $sql = "SELECT * FROM PRODUTO_IMAGEM";
        $resultado = $conn->query($sql);
        
        while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
          
          echo "<tr>";

              echo "<td class='registro'>" . $row["IMAGEM_ID"] . "</td>";
              echo "<td class='registro'>" . $row["IMAGEM_ORDEM"] . "</td>";
              echo "<td class='registro'>" . $row["PRODUTO_ID"] . "</td>";
              echo "<td class='registro'>" . $row["IMAGEM_URL"] . "</td>";
              echo "<td><a class='editar_produto' href='imagem2.php?id=" . $row['IMAGEM_ID'] . "'><img src='lapis.png' /></a></td>";
              echo "<td><a class='imagem_excluir' href='imagem_excluir.php?id=" . $row['IMAGEM_ID'] . "'><img src='lixeira.png' /></a></td>";
             
             
              
           
    

          echo "</tr>";

          $conn = null;;
      }

      
      ?>

</table>

</div>
    </main>
</body>

</html>