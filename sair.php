<?php
// Inicia a sessão
session_start();
// Limpa todas as variáveis da sessão
$_SESSION = array();
// Se quiser destruir completamente a sessão, também remove o cookie da sessão
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// Destrói a sessão
session_destroy();
// Define cabeçalhos para evitar cache do navegador
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
// Redireciona para a página de login
header("Location: index.php");
exit(); 
// Garante que o script pare de executar
?>