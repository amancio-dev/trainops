<?php
//iniciando sessão
session_start();
//incluindo conexão com o banco de dados
include 'connection.php';
//verificando se o ID do usuario foi passado como parâmetro
$id_usuario = $_GET['id_usuario'];
//verificando se o ID do usuario existe
if(isset($id_usuario)){
    //excluir o usuario do banco de dados
    $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //vinculando o parâmetro ID do usuario
    $stmt->bindParam(':id_usuario', $id_usuario);
    //executando a consulta SQL
    if($stmt->execute()){
        echo"
        <script>
            alert('Usuário excluído com sucesso.');
            window.location.href = 'gerenciar_usuario.php';
        </script>";
    }else{
        echo"
        <script>
            alert('Usuário não encontrado.');
            window.location.href = 'gerenciar_usuario.php';
        </script>";
    }
}
