<?php
/**
 * PROTEÇÃO DE ACESSO
 * Substitua o usuário e a senha pelos de sua preferência.
 */
$usuario_admin = "ayla"; 
$senha_admin   = "ayla2502"; // Altere sua senha aqui

if (!isset($_SERVER['PHP_AUTH_USER']) || 
    $_SERVER['PHP_AUTH_USER'] !== $usuario_admin || 
    $_SERVER['PHP_AUTH_PW'] !== $senha_admin) {
    
    header('WWW-Authenticate: Basic realm="Acesso Restrito ao Jardim"');
    header('HTTP/1.0 401 Unauthorized');
    echo '<div style="text-align:center; margin-top:50px; font-family:sans-serif;">';
    echo '<h2>🌸 Acesso Restrito</h2><p>Usuário ou senha inválidos.</p></div>';
    exit;
}

/**
 * CONFIGURAÇÕES DO BANCO (MySQL InfinityFree)
 */
$host   = 'sql213.infinityfree.com'; // Ex: sql102.infinityfree.com
$dbname = 'if0_36885198_convite';
$user   = 'if0_36885198';
$pass   = 'I1l70bveexvs';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca os convidados (do mais recente para o mais antigo)
    $stmt = $pdo->query("SELECT * FROM confirmados_Ayla_um_ano ORDER BY data_registro DESC");
    $convidados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Soma o total de pessoas
    $totalPessoas = $pdo->query("SELECT SUM(quantidade) FROM confirmados_Ayla_um_ano")->fetchColumn() ?: 0;
} catch (PDOException $e) {
    die("<div style='color:red; text-align:center;'>Erro de conexão: " . $e->getMessage() . "</div>");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Convidados • Ayla Liz</title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #fde4ec; 
            color: #444; 
            margin: 0; padding: 20px;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        }
        header { text-align: center; margin-bottom: 30px; }
        h1 { color: #e91e63; margin: 0; font-size: 24px; }
        .summary { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-bottom: 25px; 
        }
        .card { 
            background: #fce4ec; 
            padding: 15px 25px; 
            border-radius: 15px; 
            text-align: center; 
            border: 2px solid #f8bbd0;
        }
        .card span { display: block; font-size: 28px; font-weight: bold; color: #ad1457; }
        .card label { font-size: 14px; color: #ad1457; font-weight: bold; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; background: white; }
        th { 
            background: #f8bbd0; 
            color: #880e4f; 
            text-align: left; 
            padding: 12px; 
            font-size: 14px;
        }
        td { 
            padding: 12px; 
            border-bottom: 1px solid #f1f1f1; 
            font-size: 15px;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover { background: #fff9fb; }
        
        .empty { text-align: center; padding: 40px; color: #999; }
        .footer { text-align: center; margin-top: 30px; }
        .btn-site { 
            text-decoration: none; 
            background: #e91e63; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 10px; 
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-site:hover { background: #c2185b; }

        @media (max-width: 600px) {
            .container { padding: 15px; }
            td, th { padding: 8px; font-size: 13px; }
        }
    </style>
</head>
<body>

<audio id="playerAudio" loop preload="auto">
    <source src="http://z0374.github.io/fastAssets/background.wav" type="audio/wav">
</audio>

<div class="container">
    <header>
        <h1>🌸 Lista de Convidados</h1>
        <p>Ayla Liz - 1 Aninho</p>
    </header>

    <div class="summary">
        <div class="card">
            <span><?php echo count($convidados); ?></span>
            <label>Famílias</label>
        </div>
        <div class="card">
            <span><?php echo $totalPessoas; ?></span>
            <label>Total Pessoas</label>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Família</th>
                <th>Pessoas</th>
                <th>Data/Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($convidados) > 0): ?>
                <?php foreach ($convidados as $c): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($c['familia']); ?></strong></td>
                        <td><?php echo (int)$c['quantidade']; ?></td>
                        <td style="color: #888; font-size: 12px;">
                            <?php echo date('d/m/Y H:i', strtotime($c['data_registro'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="empty">Nenhuma confirmação recebida até o momento. 🌷</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <a href="/convite" class="btn-site">Ir para o Convite</a>
    </div>
</div>

<script src="audio.js" defer></script>
</body>
</html>