<?php
/**
 * Roteador do Módulo Convite - Versão Switch Case
 */
define("ROOT_PATH_CONVITE", __DIR__);

// Limpa qualquer saída acidental para não quebrar o JSON
ob_start();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$subRoute = rtrim(str_replace('/convite', '', $requestUri), '/');

// Se a sub-rota ficar vazia após o replace, tratamos como raiz
$subRoute = ($subRoute === '') ? '/' : $subRoute;

switch (true) {
    // --- ROTA DE API (CONFIRMAR) ---
    case ($subRoute === '/confirmar'):
        ob_clean();
        require_once ROOT_PATH_CONVITE . "/confirmar.php";
        exit;

    // --- ROTA DE ADMINISTRAÇÃO ---
    case ($subRoute === '/lista'):
        ob_clean();
        require_once ROOT_PATH_CONVITE . '/list/lista.php';
        exit;

    // --- ROTA DE VISUALIZAÇÃO DIRETA (HTML) ---
    case ($subRoute === '/visualizar'):
        $arquivoHtml = ROOT_PATH_CONVITE . "/convite.html";
        if (file_exists($arquivoHtml)) {
            ob_clean();
            header("Content-Type: text/html; charset=UTF-8");
            readfile($arquivoHtml);
        } else {
            echo "Erro: Arquivo convite.html não encontrado.";
        }
        exit;

    // --- ROTA DA PÁGINA PRINCIPAL (INTERFACE VIA LIB) ---
    case ($subRoute === '/'):
        $libPath = dirname(ROOT_PATH_CONVITE, 3) . "/lib/index.php";
        if (file_exists($libPath)) {
            require_once($libPath);
        }

        $style[] = file_exists(ROOT_PATH_CONVITE . "/style.css") ? file_get_contents(ROOT_PATH_CONVITE . "/style.css") : "";
        $body[] = file_exists(ROOT_PATH_CONVITE . "/body.html") ? file_get_contents(ROOT_PATH_CONVITE . "/body.html") : "";
        $script[] = file_exists(ROOT_PATH_CONVITE . "/script.js") ? file_get_contents(ROOT_PATH_CONVITE . "/script.js") : "";
        $favicon[] = "/convite/ayla.png";
        $title[] = "Aniversário da Ayla - 1 Aninho";

        if (function_exists('html')) {
            echo html();
        } else {
            echo "<!DOCTYPE html><html><head><title>$title</title><style>$style</style></head><body>$body<script>$script</script></body></html>";
        }
        break;

    // --- ROTA PADRÃO (404) ---
    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404</h1>Página não encontrada: " . htmlspecialchars($subRoute);
        break;
}