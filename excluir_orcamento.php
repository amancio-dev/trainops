<?php
//iniciando sessão
session_start();
//incluindo conexão com o banco de dados
include 'connection.php';
//verificando se o ID do orçamento foi passado como parâmetro
$id_orcamento = $_GET['id_orcamento'];
//verificando se o ID do orçamento existe
if(isset($id_orcamento)){
    //excluir o orçamento do banco de dados
    $sql = "DELETE FROM orcamento_anual WHERE id_orcamento = :id_orcamento";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //vinculando o parâmetro ID do orçamento
    $stmt->bindParam(':id_orcamento', $id_orcamento);
    //executando a consulta SQL
    if($stmt->execute()){
        echo"
        <script>
            alert('Orçamento excluído com sucesso.');
            window.location.href = 'gerenciar_orcamento.php';
        </script>";
    }else{
        echo"
        <script>
            alert('Orçamento não encontrado.');
            window.location.href = 'gerenciar_orcamento.php';
        </script>";
    }
}
