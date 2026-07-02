<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $ano = $_POST['ano'];
        $valor_total = $_POST['valor_total'];
        $id_usuario = $_POST['id_usuario'];
        //inserindo os dados no banco de dados
        $sql = "INSERT INTO orcamento_anual (ano,valor_total,id_usuario) VALUES (:ano,:valor_total,:id_usuario)";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':ano', $ano);
        $stmt->bindParam(':valor_total', $valor_total);
        $stmt->bindParam(':id_usuario', $id_usuario);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a inserção foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Orçamento anual cadastrado com sucesso.'); 
                        window.location.href = 'gerenciar_orcamento.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao cadastrar o orçamento anual.'); 
                        window.location.href = 'gerenciar_orcamento.php';
                    </script>
                ";
        }

    }
?>