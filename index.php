<?php
/**
 * Roteador do Módulo Convite
 */
define("ROOT_PATH_CONVITE", __DIR__);

// Limpa qualquer saída acidental para não quebrar o JSON
ob_start();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Pega apenas a parte final da rota após /convite
$subRoute = str_replace('/convite', '', $requestUri);
$subRoute = rtrim($subRoute, '/');

// --- ROTA DE API (CONFIRMAR) ---
if ($subRoute === '/confirmar') {
    ob_clean(); // Limpa o buffer antes de entregar o JSON
    require_once ROOT_PATH_CONVITE . "/confirmar.php";
    exit;
}

// ROTA DE ADMINISTRAÇÃO: /convite/lista
if (strpos($requestUri, '/lista') !== false) {
    ob_clean();
    require_once __DIR__ . '/list/lista.php';
    exit;
}

// --- ROTA DA PÁGINA (INTERFACE) ---
if ($subRoute === '' || $subRoute === '/') {
    $libPath = dirname(ROOT_PATH_CONVITE, 3) . "/lib/index.php";
    if (file_exists($libPath)) {
        require_once($libPath);
    }

    $style[] = file_exists(ROOT_PATH_CONVITE . "/style.css") ? file_get_contents(ROOT_PATH_CONVITE . "/style.css") : "";
    $body[] = file_exists(ROOT_PATH_CONVITE . "/body.html") ? file_get_contents(ROOT_PATH_CONVITE . "/body.html") : "";
    $script[] = file_exists(ROOT_PATH_CONVITE . "/script.js")  ? file_get_contents(ROOT_PATH_CONVITE . "/script.js") : "";
    $favicon[] = "/convite/ayla.png";
    $title[] = "Aniversário da Ayla - 1 Aninho";

    if (function_exists('html')) {
        echo html();
    } else {
        echo "<!DOCTYPE html><html><head><title>$title</title><style>$style</style></head><body>$body<script>$script</script></body></html>";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Página não encontrada: " . htmlspecialchars($subRoute);
}