<?php
header("Content-Type: application/json");

$arquivo = "confirmados.json";

if (!file_exists($arquivo)) {
  file_put_contents($arquivo, json_encode([]));
}

$dados = json_decode(file_get_contents($arquivo), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $input = json_decode(file_get_contents("php://input"), true);

  $nome = trim($input["nome"]);
  $qtd = intval($input["qtd"]);

  if ($nome !== "" && $qtd > 0) {
    $dados[] = [
      "familia" => $nome,
      "quantidade" => $qtd,
      "data" => date("d/m/Y H:i")
    ];

    file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT));
    echo json_encode(["sucesso" => true]);
    exit;
  }
}

/* soma total de pessoas */
$total = 0;
foreach ($dados as $item) {
  $total += intval($item["quantidade"]);
}

echo json_encode(["total" => $total]);
