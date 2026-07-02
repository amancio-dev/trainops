<?php
include 'connection.php';
$sql = "SELECT
            oa.valor_total AS orcamento_anual,
            (
                SELECT SUM(atu.valor_total)
                FROM acompanhamento_treinamento_usuario atu
            ) AS TotalConsumido,
            oa.valor_total -
            (
                SELECT SUM(atu.valor_total)
                FROM acompanhamento_treinamento_usuario atu
            ) AS saldo_restante
        FROM orcamento_anual oa
        WHERE oa.ano = YEAR(CURDATE())";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$orcamento_anual = $row ? (float) $row['orcamento_anual'] : 0;
$totalConsumido = $row ? (float) $row['TotalConsumido'] : 0;
$saldo_restante = $row ? (float) $row['saldo_restante'] : 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento Consumido</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <section class="content">
        <canvas id="meuGrafico" style="aspect-ratio: 16/9; width: 100%; max-width: 900px; height: 400px; padding: 20px; border: 1px solid #ddd; border-radius: 10px"></canvas>
    </section>

    <script>
        const orcamentoAnual = <?php echo json_encode($orcamento_anual, JSON_NUMERIC_CHECK); ?>;
        const totalConsumido = <?php echo json_encode($totalConsumido, JSON_NUMERIC_CHECK); ?>;
        const saldoRestante = <?php echo json_encode($saldo_restante, JSON_NUMERIC_CHECK); ?>;

        const ctx = document.getElementById('meuGrafico').getContext('2d');
        const meuGrafico = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Orçamento Anual', 'Consumido', 'Saldo Restante'],
                datasets: [{
                    label: 'Valores em Reais',
                    data: [orcamentoAnual, totalConsumido, saldoRestante],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                    borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => {
                                return ' R$ ' + context.parsed.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    },
                    legend: {
                        position: 'left',
                        labels: {
                            font: { size: 14 }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribuição de Valores',
                        font: { size: 20 },
                        padding: { bottom: 20 }
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
