<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os cursos do banco de dados
    $sql = "SELECT 
                c.id_curso, 
                c.nome_curso, 
                c.carga_horaria,
                c.id_tipo, 
                tt.id_tipo,
                tt.descricao
            FROM curso c
            LEFT JOIN tipos_treinamento tt ON tt.id_tipo = c.id_tipo
        ";
    
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os cursos em um array associativo
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
   //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os cursos
        echo"<tr>";
        foreach($cursos as $curso){
            echo"
               <td>".$curso['id_curso']."</td>
                <td>".$curso['nome_curso']."</td>
                <td>".$curso['carga_horaria']."</td>
                <td>".$curso['descricao']."</td>
                    <td>
                        <a href='editar_curso.php?id_curso=".$curso['id_curso']."' class='btn btn-warning'>Editar</a>
                        <a href='excluir_curso.php?id_curso=".$curso['id_curso']."' class='btn btn-danger'>Excluir</a>
                    </td>
                </tr>
            ";        
        } 
}else{
    echo "<tr>
        <td colspan='4'>Nenhum curso encontrado.</td>
    </tr>";
}