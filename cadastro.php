<?php 
include "db.php";

//função para validar e sanitizar dados

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

//adicionar as informações do produto no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset ($_POST["nome"]) ? sanitize_input($_POST['nome']) : '';
    $descricao = isset ($_POST["descricao"]) ? sanitize_input($_POST['descricao']) : '';
    $quantidade = isset($_POST["quantidade"]) ? sanitize_input($_POST['quantidade']) : '';
    $preco = isset($_POST["preco"]) ? sanitize_input($_POST['preco']) : '';

   
    //verificar se os campos não estão vazios
    if (empty($nome) || empty($quantidade) || empty($preco) || empty($descricao)) {
        // se algum campo estiver vazio, redireciona de volta com uma mensagem de eerro
        header('location: index.php?error=Todos os campos devem ser preenchidos');
        exit();
    }

    //Verifica se a quantidade e o Preço são números
    if (!is_numeric($quantidade) || !is_numeric($preco)) {
        // Se a quantidade ou valor não forem números, ewdireciona de volta com uma mensagem de erro
        header('location: index.php?error=Quantidade e valor  devem ser números');
        exit();
    }

    //usar consultas preparadas para evitar sql injection
    $stmt = $connection->prepare("INSERT INTO produtos (nome, descricao, quantidade, preco) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $connection->error);
    }

    //Vincular os parâmetros e executar a consulta
    $stmt->bind_param("ssid", $nome, $descricao, $quantidade, $preco);
    if($stmt->execute() === false) {
        die('erro na execução da consulta: ' . $stmt->error);
    }

    //Fechar a consulta
    $stmt->close();

    //Fechar a conexão com o banco de dados
    $connection->close();

    //redirecionar para a pagina inicial sem mensagem de erro
    header('location: index.php?success=Produto cadastrado com Sucesso');
    
    exit();
    
}

?>