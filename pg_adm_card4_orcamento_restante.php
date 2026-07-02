<?php
    include 'connection.php';
    $sql = "SELECT 
                oa.valor_total - 
                (
                    SELECT SUM(atu.valor_total)
                    FROM acompanhamento_treinamento_usuario atu
                )   AS saldo_restante
            FROM orcamento_anual oa";
    // Executa a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Verifica se a consulta retornou resultados
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Armazena o total de colaboradores em uma variável
    @$total = $row['saldo_restante'];
    if($total == null or $total == 0){
        echo "0,00";
    } else{
        echo number_format($total, 2, ',', '.');
    }
?>