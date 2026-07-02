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
        $email = $_POST['email'];
        $id_usuario = $_POST['id_usuario'];
        //atualizando os dados no banco de dados
        $sql = "UPDATE usuario SET nome = :nome, email = :email, id_cargo = :id_cargo WHERE id_usuario = :id_usuario";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_cargo', $id_cargo);
        $stmt->bindParam(':id_usuario', $id_usuario);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a atualização foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Usuário atualizado com sucesso.'); 
                        window.location.href = 'gerenciar_usuario.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao atualizar usuário.'); 
                        window.location.href = 'gerenciar_usuario.php';
                    </script>
                ";
        }
    }
?>
