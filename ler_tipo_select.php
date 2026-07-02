<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os cargos do banco de dados
    $sql = "SELECT * FROM tipos_treinamento WHERE id_tipo<> 1";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os cargos em um array associativo
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cargos
        echo"<select name='id_tipo' class='form-control select2' style='width: 100%;'>";
        echo"<option value=''>Selecione o tipo</option>";
        foreach($tipos as $tipo){
            echo"
                <option value='". $tipo['id_tipo'] . "'>" . $tipo['descricao'] . "</option>
            ";           
        }
    echo"</select>";
}