<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os cargos do banco de dados
    $sql = "SELECT * FROM curso";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os cargos em um array associativo
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cursos
        echo"<select name='id_curso' class='form-control select2' style='width: 100%;'>";
        echo"<option value=''>Selecione o curso</option>";
        foreach($cursos as $curso){
            echo"
                <option value='". $curso['id_curso'] . "'>" . $curso['nome_curso'] . "</option>
            ";           
        }
    echo"</select>";
}