<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //echo "Método de requisição POST recebido.";
        //recebendo os dados do formulário
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        //hash da senha usando sha1
        $senha_hash = sha1($senha);
        //verificando se o usuário existe no banco de dados
        $sql = "SELECT * FROM usuario WHERE email = :email AND senha = :senha";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':email', $email); 
        $stmt->bindParam(':senha', $senha_hash);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se o usuário existe
        if($stmt->rowCount() == 1){
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            //armazenar os dados do usuário na sessão
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            header('Location: logado.php');
        }else{
            echo "  <script>
                        alert('E-mail ou senha inválidos.'); 
                        window.location.href = 'index.php';
                    </script>
                ";
        }
    }else{
        echo "  <script>
                    alert('Erro, procure o administrador do sistema.'); 
                    window.location.href = 'index.php';
                </script>
            ";
    }
?>