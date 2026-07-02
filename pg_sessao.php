<?php
session_start();
if (!isset($_SESSION['email'])) {
  header('Location: index.php');
  exit();
}   
// Previne cache das páginas protegidas
header("Cache-Control: no-cache, no-store, must-revalidate");
// Define cabeçalhos para evitar cache do navegador
header("Pragma: no-cache");
// Define um tempo de expiração no passado para garantir que o navegador não armazene a página
header("Expires: 0");  
?>