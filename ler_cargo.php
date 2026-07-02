<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os cargos do banco de dados
    $sql = "SELECT * FROM cargo WHERE id_cargo <> 1";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os cargos em um array associativo
    $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cargos
        echo"<tr>";
        foreach($cargos as $cargo){
            echo"
                <td>" . $cargo['id_cargo'] . "</td>
                <td>" . $cargo['nome'] . "</td>
                    <td>
                        <a href='editar_cargo.php?id_cargo=".$cargo['id_cargo']."' class='btn btn-warning'>Editar</a>
                        <a href='excluir_cargo.php?id_cargo=".$cargo['id_cargo']."' class='btn btn-danger'>Excluir</a>
                    </td>
                </tr>
            ";           
        }
}else{
    echo "<tr>
        <td colspan='3'>Nenhum cargo encontrado.</td>
    </tr>";
}