<?php
    //iniciando sessão
    session_start(); 
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //verificando se o método de requisição é POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Recebendo os dados do formulário
        $id_orcamento = $_POST['id_orcamento'];
        $ano = $_POST['ano'];
        $valor_total = $_POST['valor_total'];
        //atualizando os dados no banco de dados
        $sql = "UPDATE orcamento_anual SET ano= :ano, valor_total = :valor_total WHERE id_orcamento = :id_orcamento";
        //preparando a consulta SQL
        $stmt = $pdo->prepare($sql);
        //vinculando os parâmetros da consulta SQL
        $stmt->bindParam(':ano', $ano);
        $stmt->bindParam(':valor_total', $valor_total);
        $stmt->bindParam(':id_orcamento', $id_orcamento);
        //executando a consulta SQL
        $stmt->execute();
        //verificando se a atualização foi bem sucedida
        if($stmt->rowCount() == 1){
            echo "  <script>
                        alert('Orçamento atualizado com sucesso.'); 
                        window.location.href = 'gerenciar_orcamento.php';
                    </script>
                ";
        }else{
            echo "  <script>
                        alert('Erro ao atualizar orçamento.'); 
                        window.location.href = 'gerenciar_orcamento.php';
                    </script>
                ";
        }
    }
?>
