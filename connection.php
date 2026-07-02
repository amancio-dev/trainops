<?php
include 'env.php';
//condexão com o banco de dados usando PDO
$dsn = "mysql:host=$ENV_HOST;dbname=$ENV_DATABASE";
// Tente estabelecer a conexão
try {
    // Criar uma nova instância PDO
    $pdo = new PDO($dsn, $ENV_USERNAME, $ENV_PASSWORD);
    // Configurar o modo de erro para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexão estabelecida com sucesso <hr><br>";
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}
?>