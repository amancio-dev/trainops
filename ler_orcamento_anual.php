<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os orcamentos do banco de dados
    $sql = "SELECT 
	        o.id_orcamento, o.ano, o.valor_total, o.id_usuario,
            u.nome as nome_usuario
             FROM orcamento_anual o, usuario u 
             WHERE o.id_usuario = u.id_usuario
            ";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os orcamentos em um array associativo
    $orcamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os orçamentos
        echo"<tr>";
        foreach($orcamentos as $orcamento){
            echo"
                <td>" . $orcamento['id_orcamento'] . "</td>
                <td>" . $orcamento['ano'] . "</td>
                <td>" . $orcamento['valor_total'] . "</td>
                <td>" . $orcamento['nome_usuario'] . "</td>
                
                    <td>
                        <a href='editar_orcamento.php?id_orcamento=".$orcamento['id_orcamento']."' class='btn btn-warning'>Editar</a>
                        <a href='excluir_orcamento.php?id_orcamento=".$orcamento['id_orcamento']."' class='btn btn-danger'>Excluir</a>
                    </td>
                </tr>
            ";           
        } 
}else{
    echo "<tr>
        <td colspan='4'>Nenhum orçamento encontrado.</td>
    </tr>";
}