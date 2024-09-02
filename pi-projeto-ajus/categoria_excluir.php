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
  $sql = "DELETE FROM CATEGORIA WHERE CATEGORIA_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);

  $id = $_GET['id'];
  $sql = "DELETE FROM PRODUTO WHERE CATEGORIA_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);

  header("Location: categoria.php");
} else {
  header("Location: categoria.php");
}