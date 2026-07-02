<?php
//iniciando sessão
session_start();
//incluindo conexão com o banco de dados
include 'connection.php';
//verificando se o ID do tipo de treinamento foi passado como parâmetro
$id_tipo = $_GET['id_tipo'];
//verificando se o ID do tipo de treinamento existe
if(isset($id_tipo)){
    //excluir o tipo de treinamento do banco de dados
    $sql = "DELETE FROM tipos_treinamento WHERE id_tipo = :id_tipo";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //vinculando o parâmetro ID do tipo de treinamento
    $stmt->bindParam(':id_tipo', $id_tipo);
    //executando a consulta SQL
    if($stmt->execute()){
        echo"
        <script>
            alert('Tipo de treinamento excluído com sucesso.');
            window.location.href = 'gerenciar_tipo_treinamento.php';
        </script>";
    }else{
        echo"
        <script>
            alert('Tipo de treinamento não encontrado.');
            window.location.href = 'gerenciar_tipo_treinamento.php';
        </script>";
    }
}
