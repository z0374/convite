<?php
header("Content-Type: application/json");

// O arquivo JSON será criado na mesma pasta do convite
$arquivo = __DIR__ . "/confirmados.json";

// Inicializa o arquivo se não existir ou se estiver vazio
if (!file_exists($arquivo) || filesize($arquivo) == 0) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT));
}

// Lê os dados atuais
$conteudo = file_get_contents($arquivo);
$dados = json_decode($conteudo, true);
if (!is_array($dados)) $dados = [];

// --- SE FOR POST: SALVAR NOVO REGISTRO ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $nome = isset($input["nome"]) ? trim($input["nome"]) : "";
    $qtd  = isset($input["qtd"]) ? intval($input["qtd"]) : 0;

    if ($nome !== "" && $qtd > 0) {
        $dados[] = [
            "familia" => $nome,
            "quantidade" => $qtd,
            "data" => date("d/m/Y H:i:s")
        ];

        if (file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            echo json_encode(["sucesso" => true, "mensagem" => "Presença confirmada!"]);
        } else {
            http_response_code(500);
            echo json_encode(["sucesso" => false, "mensagem" => "Erro ao gravar no servidor."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["sucesso" => false, "mensagem" => "Por favor, preencha o nome corretamente."]);
    }
    exit;
}

// --- SE FOR GET (ou após o post): RETORNAR TOTAL ---
$total = 0;
foreach ($dados as $item) {
    $total += intval($item["quantidade"]);
}

echo json_encode(["total" => $total]);