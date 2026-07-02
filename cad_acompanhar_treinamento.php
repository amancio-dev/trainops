<?php
    //iniciando sessão
    session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //pegando o id do treinamento com base no id do curso
        $sql = "SELECT * FROM curso c WHERE c.id_curso = :id_curso";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando o parâmetro id_cargo com o valor da variável $_GET['id_usuario']
        $stmt->bindParam(':id_curso', $_POST['id_curso'], PDO::PARAM_INT);
        //executando a consulta SQL
        $stmt->execute();
        //armazenando o curso em um array associativo
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        
        //Recebendo os dados do formulário
        $instituicao = $_POST['instituicao'];
        $id_curso = $_POST['id_curso'];
        $id_tipo_treinamento = $dados['id_tipo'];
        $id_orcamento = $_POST['id_orcamento'];
        $id_usuario = $_POST['id_usuario'];
        $id_usuario_logado = $_SESSION['id_usuario'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $hospedagem = $_POST['hospedagem'];
        $passagem = $_POST['passagem'];
        $translado = $_POST['translado'];
        $inscricao = $_POST['inscricao'];
        $diaria = $_POST['diaria'];
        $valor_total = $_POST['valor_total'];

        //Inserindo os dados no banco de dados
        $sql = "INSERT INTO acompanhamento_treinamento_usuario (instituicao, id_curso, id_tipo_treinamento, id_orcamento, id_usuario, id_usuario_logado, data_inicio, data_fim, hospedagem, passagem, translado, valor_total, inscricao, diaria) 
                VALUES (:instituicao, :id_curso, :id_tipo_treinamento, :id_orcamento, :id_usuario, :id_usuario_logado, :data_inicio, :data_fim, :hospedagem, :passagem, :translado, :valor_total, :inscricao, :diaria)
                ";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':instituicao', $instituicao, PDO::PARAM_STR);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $stmt->bindParam(':id_tipo_treinamento', $id_tipo_treinamento, PDO::PARAM_INT);
        $stmt->bindParam(':id_orcamento', $id_orcamento, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario_logado', $id_usuario_logado, PDO::PARAM_INT);
        $stmt->bindParam(':data_inicio', $data_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':data_fim', $data_fim, PDO::PARAM_STR);
        $stmt->bindParam(':hospedagem', $hospedagem, PDO::PARAM_STR);
        $stmt->bindParam(':passagem', $passagem, PDO::PARAM_STR);
        $stmt->bindParam(':translado', $translado, PDO::PARAM_STR);
        $stmt->bindParam(':valor_total', $valor_total, PDO::PARAM_STR);
        $stmt->bindParam(':inscricao', $inscricao, PDO::PARAM_STR);
        $stmt->bindParam(':diaria', $diaria, PDO::PARAM_STR);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a inserção foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Cadastrado realizado com sucesso.'); 
                        window.location.href = 'gerenciar_acompanhar_treinamento.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao cadastrar, tente novamente.'); 
                        window.location.href = 'gerenciar_acompanhar_treinamento.php';
                    </script>
                ";
        }
        

    }
?>