<?php
/**
 * Roteador do Módulo Convite
 * Local: src/pages/convite/index.php
 */

define("ROOT_PATH_CONVITE", __DIR__);

// Sobe os níveis para carregar a sua LIB
$libPath = dirname(ROOT_PATH_CONVITE, 3) . "/lib/index.php";
if (file_exists($libPath)) {
    require_once($libPath);
}

// Carrega os arquivos de suporte
$style[]  = file_exists(ROOT_PATH_CONVITE . "/style.css") ? file_get_contents(ROOT_PATH_CONVITE . "/style.css") : "";
$body[]   = file_exists(ROOT_PATH_CONVITE . "/body.html") ? file_get_contents(ROOT_PATH_CONVITE . "/body.html") : "";
$script[] = file_exists(ROOT_PATH_CONVITE . "/script.js")  ? file_get_contents(ROOT_PATH_CONVITE . "/script.js") : "";
$favicon[] = "/convite/ayla.png";
$title[] = "Aniversário da Ayla - 1 Aninho";

// Lógica de sub-rotas interna
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$subRoute = str_replace('/convite', '', $requestUri);
if ($subRoute === '' || $subRoute === '/') $subRoute = '/';

if ($subRoute === '/') {
    // Chama a função da sua LIB para montar o HTML
    if (function_exists('html')) {
        echo html();
    } else {
        echo "Erro: Função html() não encontrada na lib.";
    }
} elseif ($subRoute === '/confirmar') {
    require_once ROOT_PATH_CONVITE . "/confirmar.php";
}