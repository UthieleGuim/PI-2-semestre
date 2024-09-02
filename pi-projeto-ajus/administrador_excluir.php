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
  $sql = "DELETE FROM ADMINISTRADOR WHERE ADM_ID = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(["id" => $id]);

  header("Location: administrador.php");
} else {
  header("Location: administrador.php");
}
