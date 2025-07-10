<?php
// Incluir configurações de produção
require_once 'config-production.php';

// Headers de resposta
header('Content-Type: text/html; charset=utf-8');

echo "<h1>=== TESTE DE EXTRAÇÃO DE VOLUME ===</h1>";

// 1. Buscar produtos da API
echo "<h2>1. Buscando produtos da API...</h2>";

$store_id = NUVEMSHOP_STORE_ID;
$token = NUVEMSHOP_TOKEN;

$url = "https://api.nuvemshop.com.br/v1/{$store_id}/products?fields=id,name,variants,images,description,handle,price,compare_at_price,stock,weight,height,width,length&limit=30";

debugLog("Fazendo requisição para: " . $url);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authentication: bearer {$token}",
    "Content-Type: application/json",
    "User-Agent: Surfboard-Calculator/1.0"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, API_TIMEOUT);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

debugLog("HTTP Code: " . $httpcode);
debugLog("cURL Info: " . json_encode($info));

if ($error) {
    echo "<p style='color: red;'>❌ Erro de conexão: " . $error . "</p>";
    exit;
}

if ($httpcode !== 200) {
    echo "<p style='color: red;'>❌ Erro da API: HTTP " . $httpcode . "</p>";
    echo "<p>Resposta: " . substr($response, 0, 500) . "</p>";
    exit;
}

$products = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p style='color: red;'>❌ Erro ao decodificar JSON: " . json_last_error_msg() . "</p>";
    exit;
}

echo "<p style='color: green;'>✅ Produtos encontrados: " . count($products) . "</p>";

// 2. Testar extração de volume
echo "<h2>2. Testando extração de volume...</h2>";

$volumePatterns = [
    '/(\d{1,3}(?:[\.,]\d{1,2})?)L/i',
    '/(\d{1,3}(?:[\.,]\d{1,2})?) ?l/i',
    '/(\d{1,3}(?:[\.,]\d{1,2})?) ?litros/i',
    '/(\d{1,3}(?:[\.,]\d{1,2})?) ?vol/i',
];

$sizePatterns = [
    '/(\d{1,2}[\'′][ ]?\d{0,2}\s?x\s?\d{1,2}[ ]?\d{0,2}\/\d{0,2}\s?x\s?\d{1,2}[ ]?\d{0,2}\/\d{0,2})/i',
];

$comVolume = 0;
$semVolume = 0;
$produtosComVolume = [];

foreach ($products as $product) {
    // Extrair nome do produto (tratando multilíngue)
    $productName = $product['name'] ?? '';
    if (is_array($productName)) {
        $productName = reset($productName);
    }
    if (!is_string($productName)) {
        $productName = '';
    }
    
    $volume = null;
    $size = null;
    
    // Buscar volume nas variações primeiro
    if (isset($product['variants']) && is_array($product['variants']) && count($product['variants']) > 0) {
        foreach ($product['variants'] as $variant) {
            $variantName = $variant['name'] ?? '';
            if (is_array($variantName)) $variantName = reset($variantName);
            if (!is_string($variantName)) $variantName = '';
            
            foreach ($volumePatterns as $pattern) {
                if (preg_match($pattern, $variantName, $matches)) {
                    $volume = floatval(str_replace(',', '.', $matches[1]));
                    if ($volume >= 10 && $volume <= 200) {
                        break;
                    } else {
                        $volume = null;
                    }
                }
            }
            
            if ($volume) break;
        }
    }
    
    // Se não achou nas variações, buscar no nome/descrição
    if (!$volume) {
        foreach ($volumePatterns as $pattern) {
            if (preg_match($pattern, $productName, $matches)) {
                $volume = floatval(str_replace(',', '.', $matches[1]));
                if ($volume >= 10 && $volume <= 200) {
                    break;
                } else {
                    $volume = null;
                }
            }
        }
    }
    
    if (!$volume && isset($product['description'])) {
        $desc = $product['description'];
        if (is_array($desc)) $desc = reset($desc);
        if (is_string($desc)) {
            foreach ($volumePatterns as $pattern) {
                if (preg_match($pattern, $desc, $matches)) {
                    $volume = floatval(str_replace(',', '.', $matches[1]));
                    if ($volume >= 10 && $volume <= 200) {
                        break;
                    } else {
                        $volume = null;
                    }
                }
            }
        }
    }
    
    if ($volume) {
        $comVolume++;
        $produtosComVolume[] = [
            'name' => $productName,
            'volume' => $volume,
            'size' => $size
        ];
    } else {
        $semVolume++;
    }
}

echo "<p style='color: green;'>✅ Produtos COM volume: " . $comVolume . "</p>";
echo "<p style='color: orange;'>❌ Produtos SEM volume: " . $semVolume . "</p>";

// 3. Mostrar produtos com volume
echo "<h2>3. Produtos com volume encontrado:</h2>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px; font-family: monospace;'>";
echo "------------------------------------------------------------<br>";
foreach ($produtosComVolume as $produto) {
    echo $produto['name'] . " | - | " . $produto['volume'] . "L<br>";
}
echo "------------------------------------------------------------<br>";
echo "</div>";

// 4. Mostrar produtos sem volume (primeiros 10)
echo "<h2>4. Exemplos de produtos SEM volume (primeiros 10):</h2>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px; font-family: monospace;'>";
echo "------------------------------------------------------------<br>";

$count = 0;
foreach ($products as $product) {
    if ($count >= 10) break;
    
    $productName = $product['name'] ?? '';
    if (is_array($productName)) {
        $productName = reset($productName);
    }
    
    $temVolume = false;
    foreach ($volumePatterns as $pattern) {
        if (preg_match($pattern, $productName, $matches)) {
            $temVolume = true;
            break;
        }
    }
    
    if (!$temVolume) {
        echo $productName . "<br>";
        $count++;
    }
}

echo "------------------------------------------------------------<br>";
echo "</div>";

echo "<h2>✅ Teste concluído!</h2>";
echo "<p>Se você vê produtos com volume extraído corretamente, a API está funcionando.</p>";
echo "<p>Se todos os produtos aparecem sem volume, pode haver um problema na extração.</p>";
?> 