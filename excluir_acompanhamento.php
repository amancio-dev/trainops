<?php
//iniciando sessão
session_start();
//incluindo conexão com o banco de dados
include 'connection.php';
//verificando se o ID do usuario foi passado como parâmetro
$id_gastos = $_GET['id_gastos'];
//verificando se o ID do usuario existe
if(isset($id_gastos)){
    //excluir o usuario do banco de dados
    $sql = "DELETE FROM acompanhamento_treinamento_usuario WHERE id_gastos = :id_gastos";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //vinculando o parâmetro ID do usuario
    $stmt->bindParam(':id_gastos', $id_gastos);
    //executando a consulta SQL
    if($stmt->execute()){
        echo"
        <script>
            alert('Acompanhamento excluído com sucesso.');
            window.location.href = 'gerenciar_acompanhar_treinamento.php';
        </script>";
    }else{
        echo"
        <script>
            alert('Acompanhamento não encontrado.');
            window.location.href = 'gerenciar_acompanhar-treinamento.php';
        </script>";
    }
}
