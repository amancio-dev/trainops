<?php
    include 'connection.php';
    $sql = "SELECT 
                u.nome,
                SUM(atu.valor_total) AS valor_total_gasto,
                ROUND(
                    (
                        SUM(atu.valor_total) /
                        (
                            SELECT oa.valor_total
                            FROM orcamento_anual oa
                            WHERE oa.ano = year(curdate())
                        )
                    ) * 100,
                    2
                ) AS percentual_orcamento
            FROM acompanhamento_treinamento_usuario atu
            INNER JOIN usuario u 
                ON u.id_usuario = atu.id_usuario
            WHERE YEAR(atu.data_inicio) = year(curdate())
            GROUP BY 
                u.id_usuario,
                u.nome
            order BY valor_total_gasto DESC    
                ";
    // Executa a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Verifica se a consulta retornou resultados

    foreach ($stmt as $row) {
        $nome = $row['nome'];
        $valor_total_gasto = number_format($row['valor_total_gasto'], 2, ',', '.');
        $percentual_orcamento = $row['percentual_orcamento'];
    echo"
        <div class='progress-group' title='Colaborador: $nome - Total gasto: R$ $valor_total_gasto - Percentual do orçamento: $percentual_orcamento%'>
            $nome
            <span class='float-right' >
                <b>R$ $valor_total_gasto</b>/$percentual_orcamento%
            </span>
            <div class='progress progress-sm' title='Percentual consumido: $percentual_orcamento%'>
            <div class='progress-bar bg-primary' style='width: $percentual_orcamento%' ></div>
            
            </div>
        </div>
    ";
    }
    
?>