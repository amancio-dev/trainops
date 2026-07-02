<?php
    //iniciando sessão
    session_start(); 
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $id_tipo = $_POST['id_tipo'];
        $descricao = $_POST['descricao'];
        //atualizando os dados no banco de dados
        $sql = "UPDATE tipos_treinamento SET descricao = :descricao WHERE id_tipo = :id_tipo";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id_tipo', $id_tipo);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a atualização foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Tipo de treinamento atualizado com sucesso.'); 
                        window.location.href = 'gerenciar_tipo_treinamento.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao atualizar tipo de treinamento.'); 
                        window.location.href = 'gerenciar_tipo_treinamento.php';
                    </script>
                ";
        }
    }
?>
