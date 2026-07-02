<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $nome_curso = $_POST['nome_curso'];
        $carga_horaria = $_POST['carga_horaria'];
        $id_tipo = $_POST['id_tipo'];
        //inserindo os dados no banco de dados
        $sql = "INSERT INTO curso (nome_curso,carga_horaria,id_tipo) VALUES (:nome_curso,:carga_horaria,:id_tipo)";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':nome_curso', $nome_curso);
        $stmt->bindParam(':carga_horaria', $carga_horaria);
        $stmt->bindParam(':id_tipo', $id_tipo);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a inserção foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Curso cadastrado com sucesso.'); 
                        window.location.href = 'gerenciar_curso.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao cadastrar curso.'); 
                        window.location.href = 'gerenciar_curso.php';
                    </script>
                ";
        }

    }
?>