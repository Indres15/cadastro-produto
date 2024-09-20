<?php
include "db.php";

// Função para exibir produtos com formulário para edição e exclusão
function exibirProdutos($connection) {
    $selectProdutos = "SELECT * FROM produtos";
    $queryProdutos = $connection->query($selectProdutos);

    if ($queryProdutos->num_rows > 0) {
        echo "
        <div class='card mt-4'>
        <div class='card-header'>
            <b>Produtos Cadastrado</b>
        </div>
        <div class='card-body'>
            <table class='table table-hover' style='width: 100%; border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Valor por unidade (R$)</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            ";

        while ($row = $queryProdutos->fetch_assoc()) {
            echo "<tr>";
            echo "<form method='post' action='processar.php'>"; // Formulário para cada linha
            echo "<td><input type='text' name='nome' value='" . htmlspecialchars($row['nome']) . "'></td>";
            echo "<td><input type='text' name='descricao' value='" . htmlspecialchars($row['descricao']) . "'></td>";
            echo "<td><input type='number' name='quantidade' value='" . htmlspecialchars($row['quantidade']) . "'></td>";
            echo "<td><input type='text' name='preco' value='" . htmlspecialchars($row['preco']) . "'></td>";
            echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
            echo "<td><button type='submit' name='action' value='put'>Salvar alterações</button> ";
            echo "<button type='submit' name='action' value='delete'>Apagar</button></td>";
            echo "</form>";
            echo "</tr>";
        }

        echo "</tbody></table></div></div>";

    } else {
        echo "<p>Sem produtos cadastrados</p>";
    }
}

// Chama a função para exibir os produtos
exibirProdutos($connection);

// Fecha a conexão com o banco de dados
$connection->close();

?>
