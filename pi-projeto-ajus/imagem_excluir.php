<?php

//Função de conexãoi principal
Include 'conexao.php';

//Atribui conexão
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] != 'GET') die(); 
  
//Verifica se a conexão foi bem sucedida
if (!$conn) die('Erro ao conectar ao banco de dados');

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "DELETE FROM PRODUTO_IMAGEM WHERE IMAGEM_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);

  header("Location: imagem.php");
} else {
  header("Location: imagem.php");
}

