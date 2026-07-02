<?php
    //iniciando sessão
    session_start(); 
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário;
        $id_curso = $_POST['id_curso'];
        $nome_curso = $_POST['nome_curso'];
        $carga_horaria = $_POST['carga_horaria'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $id_tipo = $_POST['id_tipo'];
        $id_usuario = $_POST['id_usuario'];
        //atualizando os dados no banco de dados
        $sql = "UPDATE curso SET nome_curso = :nome_curso, carga_horaria = :carga_horaria WHERE id_curso = :id_curso";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':nome_curso', $nome_curso);
        $stmt->bindParam(':carga_horaria', $carga_horaria);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a atualização foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Curso atualizado com sucesso.'); 
                        window.location.href = 'gerenciar_curso.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao atualizar curso.'); 
                        window.location.href = 'gerenciar_curso.php';
                    </script>
                ";
        }
    }
?>
