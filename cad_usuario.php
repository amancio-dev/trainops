<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $senha_cripto = sha1('senha');
        $id_cargo = $_POST['id_cargo'];
        //inserindo os dados no banco de dados
        $sql = "INSERT INTO usuario (nome,email,senha,id_cargo) VALUES (:nome,:email,:senha,:id_cargo)";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_cripto);
        $stmt->bindParam(':id_cargo', $id_cargo);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a inserção foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Usuário cadastrado com sucesso.'); 
                        window.location.href = 'gerenciar_usuario.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao cadastrar usuário.'); 
                        window.location.href = 'gerenciar_usuario.php';
                    </script>
                ";
        }

    }
?>