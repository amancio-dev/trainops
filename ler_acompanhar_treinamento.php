<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os acompanhamentos do banco de dados
    $sql = "SELECT 
                atu.id_gastos,
                atu.instituicao,
                atu.data_inicio,
                atu.data_fim,
                atu.inscricao,
                atu.hospedagem,
                atu.passagem,
                atu.translado,
                atu.diaria,
                atu.valor_total,
                u.nome as colaborador,
                ul.nome as quem_cadastrou,
                c.nome_curso,
                tt.descricao
            FROM acompanhamento_treinamento_usuario atu
            INNER JOIN usuario u ON u.id_usuario = atu.id_usuario
            INNER JOIN usuario ul ON ul.id_usuario = atu.id_usuario_logado
            INNER JOIN curso c ON c.id_curso = atu.id_curso
            INNER JOIN tipos_treinamento tt ON tt.id_tipo = c.id_tipo 
        ";
    
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os acompanhamentos em um array associativo
    $acompanhamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
   //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os usuarios
        echo"<tr>";
        foreach($acompanhamentos as $acompanhamento){
            echo"
               <td>".$acompanhamento['id_gastos']."</td>
                <td>".$acompanhamento['colaborador']."</td>
                <td>".$acompanhamento['nome_curso']."</td>
                <td>".$acompanhamento['instituicao']."</td>
                <td>".$acompanhamento['data_inicio']."</td>
                <td>".$acompanhamento['data_fim']."</td>
                <td>".$acompanhamento['valor_total']."</td>
                    <td>
                        <a href='editar_acompanhamento.php?id_gastos=".$acompanhamento['id_gastos']."' class='btn btn-warning'>Editar</a>
                        <a href='excluir_acompanhamento.php?id_gastos=".$acompanhamento['id_gastos']."' class='btn btn-danger'>Excluir</a>
                    </td>
                </tr>
            ";        
        } 
}else{
    echo "<tr>
        <td colspan='4'>Nenhum acompanhamento encontrado.</td>
    </tr>";
}