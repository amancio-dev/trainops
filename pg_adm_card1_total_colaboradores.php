<?php
    include 'connection.php';
    $sql = "SELECT COUNT(*) as total FROM usuario";
    // Executa a consulta SQL
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Verifica se a consulta retornou resultados
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Armazena o total de colaboradores em uma variável
    echo $total_colaboradores = $row['total'];
?>