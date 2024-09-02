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

  $sql = "DELETE FROM PRODUTO_ESTOQUE WHERE PRODUTO_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);

  $sql = "DELETE FROM PRODUTO_IMAGEM WHERE PRODUTO_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);
  
  $sql = "DELETE FROM PRODUTO WHERE PRODUTO_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);

  header("Location: produto.php");
} else {
  header("Location: produto.php");
}