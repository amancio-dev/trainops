<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os tipos de treinamento do banco de dados
    $sql = "SELECT * FROM tipos_treinamento";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os tipos de treinamento em um array associativo
    $tipos_treinamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cargos
        echo"<tr>";
        foreach($tipos_treinamento as $tipo_treinamento){
            echo"
                <td>" . $tipo_treinamento['id_tipo'] . "</td>
                <td>" . $tipo_treinamento['descricao'] . "</td>
                    <td>
                        <a href='editar_tipos_treinamento.php?id_tipo=".$tipo_treinamento['id_tipo']."' class='btn btn-warning'>Editar</a>
                        <a href='excluir_tipos_treinamento.php?id_tipo=".$tipo_treinamento['id_tipo']."' class='btn btn-danger'>Excluir</a>
                    </td>
                </tr>
            ";           
        }
}else{
    echo "<tr>
        <td colspan='3'>Nenhum tipo de treinamento encontrado.</td>
    </tr>";
}