<?php
    include 'connection.php';
    $sql = "SELECT valor_total as orcamento FROM orcamento_anual";
    // Executa a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Verifica se a consulta retornou resultados
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Armazena o total de colaboradores em uma variável
    @$total = $row['orcamento'];
    if($total == null or $total == 0){
        echo "0,00";
    } else{
        echo number_format($total, 2, ',', '.');
    }
?>