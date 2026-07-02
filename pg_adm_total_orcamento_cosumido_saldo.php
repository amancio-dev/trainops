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
                WHERE oa.ano = year(curdate())
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Valores numéricos para o gráfico (sem formatação)
    $orcamento_anual_num = $row ? (float) $row['orcamento_anual'] : 0;
    $totalConsumido_num  = $row ? (float) $row['TotalConsumido'] : 0;
    $saldo_restante_num  = $row ? (float) $row['saldo_restante'] : 0;

    // Valores formatados para exibição nos parágrafos
    $orcamento_anual_fmt = number_format($orcamento_anual_num, 2, ',', '.');
    $totalConsumido_fmt  = number_format($totalConsumido_num,  2, ',', '.');
    $saldo_restante_fmt  = number_format($saldo_restante_num,  2, ',', '.');
?>



<!-- Canvas do gráfico -->
<section class="content">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Gráfico de Orçamento Anual, Total Consumido e Saldo Restante</h3>
      </div>
    <div class="card-body">
        <div class='col-md-12'>
            <div class='row'>
                <canvas id="meuGrafico" style="aspect-ratio: 16/9; width: 100%;  height: 400px; padding: 20px; border: 1px solid #ddd; border-radius: 10px"></canvas>
            </div>
        </div>
    </div> 
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Valores numéricos (float) passados diretamente do PHP — sem formatação BR
    const orcamentoAnual  = <?php echo $orcamento_anual_num; ?>;
    const totalConsumido  = <?php echo $totalConsumido_num; ?>;
    const saldoRestante   = <?php echo $saldo_restante_num; ?>;

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
