<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $descricao = $_POST['descricao'];
        //inserindo os dados no banco de dados
        $sql = "INSERT INTO tipos_treinamento (descricao) VALUES (:descricao)";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':descricao', $descricao);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a inserção foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Tipo treinamento cadastrado com sucesso.'); 
                        window.location.href = 'gerenciar_tipo_treinamento.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao cadastrar cargo tipo treinamento.'); 
                        window.location.href = 'gerenciar_tipo_treinamento.php';
                    </script>
                ";
        }

    }
?>