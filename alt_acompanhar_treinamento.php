<?php
    //iniciando sessão
    session_start(); 
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário;
        $id_gastos = $_POST['id_gastos'];
        $instituicao = $_POST['instituicao'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $inscricao = $_POST['inscricao'];
        $hospedagem = $_POST['hospedagem'];
        $passagem = $_POST['passagem'];
        $translado = $_POST['translado'];
        $diaria = $_POST['diaria'];
        $valor_total = $_POST['valor_total'];
        //atualizando os dados no banco de dados
        $sql = "UPDATE acompanhamento_treinamento_usuario 
                SET instituicao = :instituicao, 
                    data_inicio = :data_inicio, 
                    data_fim = :data_fim, 
                    inscricao = :inscricao, 
                    hospedagem = :hospedagem, 
                    passagem = :passagem, 
                    translado = :translado, 
                    diaria = :diaria, 
                    valor_total = :valor_total 
                WHERE id_gastos = :id_gastos";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':instituicao', $instituicao);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->bindParam(':inscricao', $inscricao);
        $stmt->bindParam(':hospedagem', $hospedagem);
        $stmt->bindParam(':passagem', $passagem);
        $stmt->bindParam(':translado', $translado);
        $stmt->bindParam(':diaria', $diaria);
        $stmt->bindParam(':valor_total', $valor_total);
        $stmt->bindParam(':id_gastos', $id_gastos);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a atualização foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Informações atualizadas com sucesso.'); 
                        window.location.href = 'gerenciar_acompanhar_treinamento.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao atualizar, tente novamente.'); 
                        window.location.href = 'gerenciar_acompanhar_treinamento.php';
                    </script>
                ";
        }
    }
?>
