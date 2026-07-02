<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os cargos do banco de dados
    $sql = "SELECT * FROM usuario WHERE id_usuario<> 1";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os cargos em um array associativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cargos
        echo"<select name='id_usuario' class='form-control select2' style='width: 100%;'>";
        echo"<option value=''>Selecione o usuário</option>";
        foreach($usuarios as $usuario){
            echo"
                <option value='". $usuario['id_usuario'] . "'>" . $usuario['nome'] . "</option>
            ";           
        }
    echo"</select>";
}