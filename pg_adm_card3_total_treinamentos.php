<?php
    include 'connection.php';
    $sql = "SELECT COUNT(*) as total FROM acompanhamento_treinamento_usuario";
    // Executa a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Verifica se a consulta retornou resultados
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Armazena o total de colaboradores em uma variável
    @$total = $row['total'];
    if($total == null or $total == 0){
        echo "0";
    } else{
        echo $total;
    }
?>