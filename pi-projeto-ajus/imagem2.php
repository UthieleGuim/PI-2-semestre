<?php
// Client ID of Imgur App 
$IMGUR_CLIENT_ID = "4c8c63aa6d1a2e5"; 

$statusMsg = '';
$valErr = ''; 
$status = 'danger'; 
$imgurData = array();

//Função Principal de conexão
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Conecta ao banco de dados
  $conn = conectar();

  // Verifica se a conexão foi bem sucedida
  if (mysqli_connect_error()) {
    die('Erro ao conectar ao banco de dados');
  }

  if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Validate form input fields 
    if(empty($_FILES["inputURL"]["name"])){ 
      $valErr .= 'Por favor, selecione um arquivo para carregar..<br/>'; 
    }
    // Check whether user inputs are empty 
    if(empty($valErr)){     
        // Get file info 
        $fileName = basename($_FILES["inputURL"]["name"]); 
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('jpg','png','jpeg','gif'); 
        if(in_array($fileType, $allowTypes)){ 
            // Source image 
            $image_source = file_get_contents($_FILES['inputURL']['tmp_name']); 
             
            // API post parameters 
            $postFields = array('image' => base64_encode($image_source)); 
             
            // Post image to Imgur via API 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image'); 
            curl_setopt($ch, CURLOPT_POST, TRUE); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $IMGUR_CLIENT_ID)); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields); 
            $response = curl_exec($ch); 
            curl_close($ch); 
             
            // Decode API response to array 
            $responseArr = json_decode($response); 
             
            // Check image upload status 
            if(!empty($responseArr->data->link)){ 
                $inputURL = $responseArr->data->link;
                //Insere dados no banco
                $sql = "UPDATE PRODUTO_IMAGEM SET IMAGEM_URL = '$inputURL' WHERE IMAGEM_ID = $id";
                $resultado = $conn->query($sql);
                //informa ao achar erro
                if (!$resultado) {              
                  $statusMsg = 'Erro ao executar a consulta: ' . $conn->error.'<br/>';               
                } else {
                  header("Location: imagem.php");
                }
            }else{ 
                $statusMsg = 'O upload da imagem falhou. Tente novamente depois de algum tempo.<br/>'; 
            } 
        }else{ 
            $statusMsg = 'Desculpe, apenas um arquivo de imagem pode ser carregado.<br/>'; 
        } 
    }else{ 
      $statusMsg = 'Por favor, preencha todos os campos obrigatórios:<br/>'.trim($valErr, '<br/>');
    } 
  } else {
    // Obtém os dados do formulário
    //$inputURL = $_POST['inputURL'];
    $produto = $_POST['produto'];
  
    //Query que guarda valor de ordem para ser usada posteriormente
    $query2 = "SELECT count(IMAGEM_ORDEM) , PRODUTO_ID FROM PRODUTO_IMAGEM WHERE PRODUTO_ID = '$produto' group by PRODUTO_ID";
    $result = $conn->query($query2);
  
    $countImagemOrdem = 0; // Variável para armazenar o valor de count(IMAGEM_ORDEM)
  
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $countImagemOrdem = $row['count(IMAGEM_ORDEM)'];  
    }
  
    //soma mais 1 na ordem.
    if($countImagemOrdem < 1){    
      $countImagemOrdem++;
    }
  
    $resultado='';
    if(isset($_POST['submit'])){     
      // Validate form input fields 
      if(empty($_POST['produto'])){ 
        $valErr .= 'Por favor, selecione um produto <br/>'; 
      } 
       
      // Validate form input fields 
      if(empty($_FILES["inputURL"]["name"])){ 
        $valErr .= 'Por favor, selecione um arquivo para carregar..<br/>'; 
      } 
       
      // Check whether user inputs are empty 
      if(empty($valErr)){     
          // Get file info 
          $fileName = basename($_FILES["inputURL"]["name"]); 
          $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
           
          // Allow certain file formats 
          $allowTypes = array('jpg','png','jpeg','gif'); 
          if(in_array($fileType, $allowTypes)){ 
              // Source image 
              $image_source = file_get_contents($_FILES['inputURL']['tmp_name']); 
               
              // API post parameters 
              $postFields = array('image' => base64_encode($image_source)); 
               
              // Post image to Imgur via API 
              $ch = curl_init(); 
              curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image'); 
              curl_setopt($ch, CURLOPT_POST, TRUE); 
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
              curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $IMGUR_CLIENT_ID)); 
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields); 
              $response = curl_exec($ch); 
              curl_close($ch); 
               
              // Decode API response to array 
              $responseArr = json_decode($response); 
               
              // Check image upload status 
              if(!empty($responseArr->data->link)){ 
                  $inputURL = $responseArr->data->link; 
                  $produto = $_POST['produto'];
  
                  //Insere dados no banco
                  $sql = "INSERT INTO PRODUTO_IMAGEM (IMAGEM_URL, IMAGEM_ORDEM, PRODUTO_ID) VALUES ('$inputURL', '$countImagemOrdem', '$produto')";
                  $resultado = $conn->query($sql);
                  //informa ao achar erro
                  if (!$resultado) {              
                    $statusMsg = 'Erro ao executar a consulta: ' . $conn->error.'<br/>';               
                  } else {
                    header("Location: imagem.php");
                  }
              }else{ 
                  $statusMsg = 'O upload da imagem falhou. Tente novamente depois de algum tempo.<br/>'; 
              } 
          }else{ 
              $statusMsg = 'Desculpe, apenas um arquivo de imagem pode ser carregado.<br/>'; 
          } 
      }else{ 
        $statusMsg = 'Por favor, preencha todos os campos obrigatórios:<br/>'.trim($valErr, '<br/>');
      } 
      
    }
    //Finaliza conexão
    $conn = null;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM PRODUTO_IMAGEM WHERE IMAGEM_ID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $id]);
    $resultado = $stmt->fetch();
    if (!$resultado) die("Não existe está imagem.");
    $produtoId = $resultado["PRODUTO_ID"];
    $imageUrl = $resultado["IMAGEM_URL"];
  } else {
    $id = "";
    $nome = "";
    $email = "";
    $senha = "";
  }
}

?>
<!--tela de cadastro de imagem-->
<!DOCTYPE html>
<html lang="pt-br">

<head>

  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Imagem</title>

  <!--Estilização Utilizada-->
  <link rel="stylesheet" type="text/css" href="styleProduto.css" />
  <link rel="stylesheet" type="text/css" href="styleconteudoproduto2.css" />

</head>

<body>
  <main>   
 <!--Navbar Lateral-->
 <?php include "menu-lateral.php"; ?>
  <div class="central">
    <!--Formulario de cadastro-->
    <form action="imagem2.php" method="POST" enctype="multipart/form-data">      
    <?php
      if($statusMsg){
    ?>
        <p class="error-msg"><?=$statusMsg?></p>
    <?php
      }
    ?>

    <h1>Imagem</h1>

    <?php if ($id !== "") { ?><input type="hidden" name="id" value="<?= $id ?>"/><?php } ?>
    <!--laço de repetição que busca IDs dentro do banco e traz numa select options-->
    <label for="Produto">Produto</label>
    <select name="produto" id="produto" required>
      <?php
        $conn = conectar();

        //busca dados em query
        $query = 'SELECT PRODUTO_ID, PRODUTO_NOME FROM PRODUTO';
        $result = $conn->query($query);

        //cria opções enquanto tiverem ids de produto no banco ainda não puxados
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['PRODUTO_ID'];
            $nome = $row['PRODUTO_NOME'];

            $selected = '';

            if($id == $produtoId) {
              $selected = 'selected';
            }

            echo '<option value="' . $id . '"' . $selected . '>' . $nome . '</option>';
        }

        $conn = null;
      ?>
    </select>

      <!-- Input de URL da imagem -->
      <div class="form-group">
        <label for="imagem">Selecione uma imagem</label>
        <input type="file" id="inputURL" name="inputURL" class="form-control" accept="image/png, image/jpeg, image/jpg" value="<?=$imageUrl?>">
      </div>

      <div id="urlImagensContainer"></div> 

      <!--Realiza Cadastro-->
      <button type="submit" name="submit">Cadastrar</button>
    </form>
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="../js/jquery-ui-1.13.2/jquery-ui.js"></script>
  <script>
    const output = document.getElementById("output");
    const inputURL = document.getElementById("inputURL");
    let imagesArray = [];

    function displayImages() {
      let images = "";
      imagesArray.forEach((image, index) => {
        images += `<div class="image">
                      <img src="${image.url}" alt="image">
                      <span onclick="deleteImage(${index})">&times;</span>
                    </div>`;
      });
      output.innerHTML = images;
    }

    function addImage() {
      const url = inputURL.value.trim();
      if (url !== "") {
        const image = { url };
        imagesArray.push(image);
        displayImages();
      }
    }

    function deleteImage(index) {
      imagesArray.splice(index, 1);
      displayImages();
    }
     // Função para enviar os dados do formulário
     function submitForm() {
      // Remove os campos urlImagem[] existentes
      const urlImagensContainer = document.getElementById("urlImagensContainer");
      while (urlImagensContainer.firstChild) {
        urlImagensContainer.firstChild.remove();
      }

      // Cria um novo campo de input para cada URL de imagem
      imagesArray.forEach((image, index) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = `urlImagem[${index}]`;
        input.value = image.url;
        urlImagensContainer.appendChild(input);
      });
    }
  </script>
</body>
</html>
