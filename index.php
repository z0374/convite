<?php
/**
 * Roteador do Módulo Convite - Versão Completa e Silenciosa
 */
define("ROOT_PATH_CONVITE", __DIR__);

// Inicia o buffer para capturar qualquer saída indesejada antes da hora
ob_start();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove o prefixo /convite para processar a sub-rota
$subRoute = rtrim(str_replace('/convite', '', $requestUri), '/');

// Se a sub-rota ficar vazia, define como raiz
$subRoute = ($subRoute === '') ? '/' : $subRoute;

switch (true) {
    // --- ROTA DE API (CONFIRMAR) ---
    case ($subRoute === '/confirmar'):
        ob_clean(); // Limpa lixo anterior
        require_once ROOT_PATH_CONVITE . "/confirmar.php";
        exit;

    // --- ROTA DE ADMINISTRAÇÃO ---
    case ($subRoute === '/lista'):
        ob_clean();
        require_once ROOT_PATH_CONVITE . '/list/lista.php';
        exit;

    // --- ROTA DE VISUALIZAÇÃO DIRETA (HTML ESTÁTICO) ---
    case ($subRoute === '/visualizar'):
        $arquivoHtml = ROOT_PATH_CONVITE . "/convite.html";
        if (file_exists($arquivoHtml)) {
            ob_clean();
            header("Content-Type: text/html; charset=UTF-8");
            readfile($arquivoHtml);
        } else {
            http_response_code(404);
            echo "Erro: Arquivo convite.html não encontrado.";
        }
        exit;

    // --- ROTA DA PÁGINA PRINCIPAL (INTERFACE DINÂMICA) ---
    case ($subRoute === '/'):
        // 1. Limpeza Crítica: Remove qualquer erro/aviso anterior do buffer
        ob_clean();
        
        // 2. Silenciar Erros: Impede que Warnings do PHP apareçam na tela do usuário
        ini_set('display_errors', 0);
        error_reporting(0);

        // Inicializa arrays para evitar erro de variável indefinida
        $style = [];
        $body = [];
        $script = [];
        $favicon = [];
        $title = [];

        // Carrega a biblioteca principal (com @ para suprimir erros se falhar)
        $libPath = dirname(ROOT_PATH_CONVITE, 3) . "/lib/index.php";
        if (file_exists($libPath)) {
            @require_once($libPath);
        }

        // --- CARREGAMENTO DE ASSETS ---
        
        // CSS
        if (file_exists(ROOT_PATH_CONVITE . "/style.css")) {
            $style[] = file_get_contents(ROOT_PATH_CONVITE . "/style.css");
        }

        // HTML do Corpo
        if (file_exists(ROOT_PATH_CONVITE . "/body.html")) {
            $body[] = file_get_contents(ROOT_PATH_CONVITE . "/body.html");
        }

        // JavaScript Principal
        if (file_exists(ROOT_PATH_CONVITE . "/script.js")) {
            $script[] = file_get_contents(ROOT_PATH_CONVITE . "/script.js");
        }
        
        // JavaScript do Áudio (Correção do Player)
        if (file_exists(ROOT_PATH_CONVITE . "/audio.js")) {
            $script[] = file_get_contents(ROOT_PATH_CONVITE . "/audio.js");
        }

        // Metadados
        $favicon[] = "/convite/ayla.png";
        $title[] = "Aniversário da Ayla - 1 Aninho";

        // --- RENDERIZAÇÃO ---
        
        // Se a função da lib existir, usa ela. Senão, usa o fallback.
        if (function_exists('html')) {
            echo html('real-time');
        } else {
            // Fallback de segurança (caso a lib falhe)
            echo "<!DOCTYPE html>
            <html lang='pt-br'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>" . implode('', $title) . "</title>
                    <style>" . implode("\n", $style) . "</style>
                </head>
                <body>
                    " . implode("\n", $body) . "
                    <script>" . implode("\n", $script) . "</script>
                </body>
            </html>";
        }
        break;

    // --- ROTA PADRÃO (404) ---
    default:
        http_response_code(404);
        echo "<h1>404</h1>Página não encontrada: " . htmlspecialchars($subRoute);
        break;
}