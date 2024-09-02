<?php 
include 'conexao.php';
?>
<!--Tela de visualização de administradores cadastrados-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Administrador</title>

  <!--links de estilização-->
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
            <th class="Cabecalho">USER</th>
            <th class="Cabecalho">EMAIL</th>
            <td class="Cabecalho"><a href="administrador2.php"><button>+</button></a></td>

          </tr>

          <?php

          $conn = conectar();
          // Consulta SQL para buscar todos os registros da tabela PRODUTO
          $sql = "select ADM_ID, ADM_NOME, ADM_EMAIL, ADM_ATIVO FROM ADMINISTRADOR WHERE ADM_ATIVO = 1";
          $resultado = $conn->query($sql);

          while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";

              echo "<td class='registro'>" . $row["ADM_ID"] . "</td>";
              echo "<td class='registro'>" . $row["ADM_NOME"] . "</td>";
              echo "<td class='registro'>" . $row["ADM_EMAIL"] . "</td>";
              echo "<td><a class='editar_produto' href='administrador2.php?id=" . $row['ADM_ID'] . "'><img src='lapis.png' /></a></td>";
              echo "<td><a class='administrador_excluir' href='administrador_excluir.php?id=" . $row['ADM_ID'] . "'><img src='lixeira.png' /></a></td>";              
            echo "</tr>";
          }


          ?>

       </table>

  </div>

</main>

</body>

</html>