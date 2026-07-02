<?php
    //iniciando sessão
    session_start(); 
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $id_cargo = $_POST['id_cargo'];
        $nome = $_POST['nome'];
        //atualizando os dados no banco de dados
        $sql = "UPDATE cargo SET nome = :nome WHERE id_cargo = :id_cargo";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id_cargo', $id_cargo);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a atualização foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Cargo atualizado com sucesso.'); 
                        window.location.href = 'gerenciar_cargo.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao atualizar cargo.'); 
                        window.location.href = 'gerenciar_cargo.php';
                    </script>
                ";
        }
    }
?>
