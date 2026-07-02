<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os cargos do banco de dados
    $sql = "SELECT * FROM orcamento_anual WHERE ano = YEAR(CURDATE())";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os cargos em um array associativo
    $orcamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cargos
        echo"<select name='id_orcamento' class='form-control select2' style='width: 100%;'>";
        echo"<option value=''>Selecione o orçamento</option>";
        foreach($orcamentos as $orcamento){
            echo"
                <option value='". $orcamento['id_orcamento'] . "'>" . $orcamento['valor_total'] . "</option>
            ";           
        }
    echo"</select>";
}