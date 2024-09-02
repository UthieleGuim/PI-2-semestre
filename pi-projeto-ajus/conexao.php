<?php

//Função de conexão principal
function conectar(){
    
    //dados para conexão com servidor de banco de dados
    $host = "144.22.157.228";
    $porta = "3306";
    $nomeBanco = "Delta";
    $usuarioBanco = "delta";
    $senhaUsuario = "delta";

    try{

        //tentativa de adentrar banco de dados
        $pdo = new PDO(
            'mysql:host='.$host. ':' .$porta. ';dbname=' .$nomeBanco, 
            $usuarioBanco,
            $senhaUsuario
        );
        //Metodo PDO
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        //Metodo PDO
        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        ); 
    }
    
    //Informe de erro ao conectar
    catch(PDOException $ex){
        echo 'Erro ao conectar com o banco de dados: '.$ex->getMessage();
        throw $ex;
    }
    
    //Retorna conexão
    return $pdo;
}