<?php
header("Content-Type: application/json");

// Caminho absoluto para o arquivo de dados
$arquivo = __DIR__ . "/confirmados.json";

// Cria o arquivo se não existir
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT));
    chmod($arquivo, 0666);
}

// Lê os dados atuais
$dados = json_decode(file_get_contents($arquivo), true);
if (!is_array($dados)) $dados = [];

// --- SE FOR POST: SALVAR ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $nome = isset($input["nome"]) ? trim($input["nome"]) : "";
    $qtd  = isset($input["qtd"]) ? intval($input["qtd"]) : 0;

    if ($nome !== "" && $qtd > 0) {
        $dados[] = [
            "familia" => $nome,
            "quantidade" => $qtd,
            "data" => date("d/m/Y H:i")
        ];

        if (file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX)) {
            // Calcula o total atualizado para retornar
            $total = 0;
            foreach ($dados as $item) { $total += (int)$item["quantidade"]; }
            echo json_encode(["sucesso" => true, "total" => $total]);
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Sem permissão de escrita no servidor."]);
        }
        exit;
    }
    echo json_encode(["sucesso" => false, "mensagem" => "Dados inválidos."]);
    exit;
}

// --- SE FOR GET: RETORNAR TOTAL ---
$total = 0;
foreach ($dados as $item) {
    $total += (int)$item["quantidade"];
}
echo json_encode(["total" => $total]);