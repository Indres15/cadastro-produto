<?php
include "db.php";

// Função para validar e sanitizar dados
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Verifica se a ação foi definida e se o ID do produto está presente
if (isset($_POST['action']) && isset($_POST['id'])) {
    // Sanitiza os dados recebidos
    $id = intval($_POST['id']); // Sanitiza e converte para inteiro
    $nome = isset($_POST['nome']) ? sanitize_input($_POST['nome']) : '';
    $descricao = isset($_POST['descricao']) ? sanitize_input($_POST['descricao']) : '';
    $quantidade = isset($_POST['quantidade']) ? sanitize_input($_POST['quantidade']) : '';
    $valor = isset($_POST['preco']) ? sanitize_input($_POST['preco']) : '';
    
    // Verifica a ação solicitada
    if ($_POST['action'] === 'put') {
        // Verifica se todos os campos necessários estão preenchidos
        if (empty($nome) || empty($descricao) || empty($quantidade) || empty($valor)) {
            header('Location: index.php?error=Todos os campos devem ser preenchidos');
            exit();
        }

        // Verifica se a quantidade e o valor são números
        if (!is_numeric($quantidade) || !is_numeric($valor)) {
            header('Location: index.php?error=Quantidade e valor devem ser números');
            exit();
        }

        // Atualiza o produto no banco de dados
        $stmt = $connection->prepare("UPDATE produtos SET nome = ?, descricao = ?, quantidade = ?, preco = ? WHERE id = ?");
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $connection->error);
        }

        $stmt->bind_param("ssidi", $nome, $descricao, $quantidade, $valor, $id);
        if ($stmt->execute() === false) {
            die('Erro na execução da consulta: ' . $stmt->error);
        }

        $stmt->close();
        header('Location: index.php?success=Produto atualizado com sucesso');
        exit();
    } elseif ($_POST['action'] === 'delete') {
        // Deleta o produto do banco de dados
        $stmt = $connection->prepare("DELETE FROM produtos WHERE id = ?");
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $connection->error);
        }

        $stmt->bind_param("i", $id);
        if ($stmt->execute() === false) {
            die('Erro na execução da consulta: ' . $stmt->error);
        }

        $stmt->close();
        header('Location: index.php?success=Produto excluído com sucesso');
        exit();
    } else {
        header('Location: index.php?error=Ação inválida');
        exit();
    }
} else {
    header('Location: index.php?error=Dados ausentes');
    exit();
}

// Fecha a conexão com o banco de dados
$connection->close();

