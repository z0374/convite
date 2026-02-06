<?php
header("Content-Type: application/json");

// --- CONFIGURAÇÕES DO BANCO (Pegue no painel da InfinityFree) ---
$host   = 'sql213.infinityfree.com'; // Ex: sql102.infinityfree.com
$dbname = 'if0_36885198_convite';
$user   = 'if0_36885198';
$pass   = 'I1l70bveexvs';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro de conexão: " . $e->getMessage()]);
    exit;
}

// --- SE FOR POST: SALVAR NO BANCO ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    $nome = isset($input["nome"]) ? trim($input["nome"]) : "";
    $qtd  = isset($input["qtd"]) ? intval($input["qtd"]) : 0;

    if ($nome !== "" && $qtd > 0) {
        $stmt = $pdo->prepare("INSERT INTO confirmados_Ayla_um_ano  (familia, quantidade) VALUES (:nome, :qtd)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':qtd', $qtd);
        
        if ($stmt->execute()) {
            // Busca o total atualizado imediatamente
            $total = $pdo->query("SELECT SUM(quantidade) FROM confirmados_Ayla_um_ano ")->fetchColumn();
            echo json_encode(["sucesso" => true, "total" => (int)$total]);
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar no banco."]);
        }
        exit;
    }
}

// --- SE FOR GET: RETORNAR TOTAL ---
$total = $pdo->query("SELECT SUM(quantidade) FROM confirmados_Ayla_um_ano ")->fetchColumn();
echo json_encode(["total" => (int)($total ?: 0)]);