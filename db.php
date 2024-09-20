<?php 
//infformações de login
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "cadastroProdutos";

//conexção com o banco
$connection = new mysqli($servername, $username, $password, $db_name);

if ($connection->connect_error) {
    die ($connection->connect_error);
}

?>