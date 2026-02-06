<?php
/**
 * Roteador do Módulo Convite
 */

define("ROOT_PATH_CONVITE", __DIR__);

// Lógica de sub-rotas
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$subRoute = str_replace('/convite', '', $requestUri);
if ($subRoute === '' || $subRoute === '/') $subRoute = '/';

// --- ROTA DE API (CONFIRMAR) ---
if ($subRoute === '/confirmar') {
    $apiPath = ROOT_PATH_CONVITE . "/confirmar.php";
    if (file_exists($apiPath)) {
        require_once $apiPath;
    } else {
        header("Content-Type: application/json");
        echo json_encode(["sucesso" => false, "mensagem" => "Arquivo confirmar.php não encontrado"]);
    }
    exit; // Impede que o HTML da página seja carregado junto com o JSON
}

// --- ROTA DA PÁGINA (INTERFACE) ---
if ($subRoute === '/') {
    // Carrega a LIB
    $libPath = dirname(ROOT_PATH_CONVITE, 3) . "/lib/index.php";
    if (file_exists($libPath)) {
        require_once($libPath);
    }

    // Carrega ativos
    $style[] = file_exists(ROOT_PATH_CONVITE . "/style.css") ? file_get_contents(ROOT_PATH_CONVITE . "/style.css") : "";
    $body[]  = file_exists(ROOT_PATH_CONVITE . "/body.html") ? file_get_contents(ROOT_PATH_CONVITE . "/body.html") : "";
    $script[] = file_exists(ROOT_PATH_CONVITE . "/script.js")  ? file_get_contents(ROOT_PATH_CONVITE . "/script.js") : "";
    $favicon[] = "/convite/ayla.png";
    $title[] = "Aniversário da Ayla - 1 Aninho";

    if (function_exists('html')) {
        echo html();
    } else {
        // Fallback caso a função html() não exista
        echo "<!DOCTYPE html><html lang='pt-br'><head><title>$title</title><style>$style</style></head>";
        echo "<body>$body<script>$script</script></body></html>";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Página não encontrada.";
}