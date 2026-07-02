<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $nome = $_POST['nome'];
        //inserindo os dados no banco de dados
        $sql = "INSERT INTO cargo (nome) VALUES (:nome)";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':nome', $nome);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a inserção foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Cargo cadastrado com sucesso.'); 
                        window.location.href = 'gerenciar_cargo.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao cadastrar cargo.'); 
                        window.location.href = 'gerenciar_cargo.php';
                    </script>
                ";
        }

    }
?>