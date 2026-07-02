<?php
    //iniciando sessão
    //session_start();
    //incluindo conexão com o banco de dados
    include 'connection.php';
    //selecionando os usuarios do banco de dados
    $sql = "SELECT 
	        u.id_usuario, u.nome, u.email, u.id_cargo,
            c.id_cargo, c.nome as nome_cargo
        FROM usuario u, cargo c 
        WHERE u.id_usuario <> 1
        AND u.id_cargo = c.id_cargo
        ";
    //preparando a consulta SQL
    $stmt = $pdo->prepare($sql);
    //executando a consulta SQL
    $stmt->execute();
    //armazenando os usuarios em um array associativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //verificando se a consulta SQL retornou resultados
    if($stmt->rowCount() > 0){
        //exibindo os usuarios
        echo"<tr>";
        foreach($usuarios as $usuario){
            echo"
                <td>" . $usuario['id_usuario'] . "</td>
                <td>" . $usuario['nome'] . "</td>
                <td>" . $usuario['email'] . "</td>
                <td>" . $usuario['nome_cargo'] . "</td>
                
                    <td>
                        <a href='editar_usuario.php?id_usuario=".$usuario['id_usuario']."' class='btn btn-warning'>Editar</a>
                        <a href='excluir_usuario.php?id_usuario=".$usuario['id_usuario']."' class='btn btn-danger'>Excluir</a>
                    </td>
                </tr>
            ";           
        } 
}else{
    echo "<tr>
        <td colspan='4'>Nenhum usuario encontrado.</td>
    </tr>";
}