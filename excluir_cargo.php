<?php
//iniciando sessão
session_start();
//incluindo conexão com o banco de dados
include 'connection.php';
//verificando se o ID do cargo foi passado como parâmetro
$id_cargo = $_GET['id_cargo'];
//verificando se o ID do cargo existe
if(isset($id_cargo)){
    //excluir o cargo do banco de dados
    $sql = "DELETE FROM cargo WHERE id_cargo = :id_cargo";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //vinculando o parâmetro ID do cargo
    $stmt->bindParam(':id_cargo', $id_cargo);
    //executando a consulta SQL
    if($stmt->execute()){
        echo"
        <script>
            alert('Cargo excluído com sucesso.');
            window.location.href = 'gerenciar_cargo.php';
        </script>";
    }else{
        echo"
        <script>
            alert('Cargo não encontrado.');
            window.location.href = 'gerenciar_cargo.php';
        </script>";
    }
}
