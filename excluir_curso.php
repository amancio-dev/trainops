<?php
//iniciando sessão
session_start();
//incluindo conexão com o banco de dados
include 'connection.php';
//verificando se o ID do curso foi passado como parâmetro
$id_curso = $_GET['id_curso'];
//verificando se o ID do curso existe
if(isset($id_curso)){
    //excluir o curso do banco de dados
    $sql = "DELETE FROM curso WHERE id_curso = :id_curso";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //vinculando o parâmetro ID do curso
    $stmt->bindParam(':id_curso', $id_curso);
    //executando a consulta SQL
    if($stmt->execute()){
        echo"
        <script>
            alert('Curso excluído com sucesso.');
            window.location.href = 'gerenciar_curso.php';
        </script>";
    }else{
        echo"
        <script>
            alert('Curso não encontrado.');
            window.location.href = 'gerenciar_curso.php';
        </script>";
    }
}
