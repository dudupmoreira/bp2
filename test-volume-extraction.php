<?php
/**
 * Teste de Extração de Volume
 * Testa a extração de volume dos nomes dos produtos da NuvemShop
 */

// Configurações
$config = [
    'api_url' => 'https://api.nuvemshop.com.br/v1/2446542/products',
    'token' => '96685dd9b9c0c82b5d613b3d5dd466f1d6418083',
    'debug' => true
];

// Função para fazer log
function logDebug($message) {
    if ($GLOBALS['config']['debug']) {
        echo "[DEBUG] " . date('Y-m-d H:i:s') . " - $message\n";
    }
}

// Função para extrair volume do nome do produto
function extractVolumeFromName($productName) {
    // Se o nome for um array (com idiomas), pegar o primeiro valor
    if (is_array($productName)) {
        $productName = reset($productName); // Pega o primeiro valor do array
    }
    
    // Se ainda não for string, retornar null
    if (!is_string($productName)) {
        return null;
    }
    
    $volumePatterns = [
        '/(\d{1,3}(?:[\.,]\d{1,2})?)L/i',  // 28L, 28.5L
        '/(\d{1,3}(?:[\.,]\d{1,2})?) ?l/i', // 28 l, 28.5 l
        '/(\d{1,3}(?:[\.,]\d{1,2})?) ?litros/i', // 28 litros
        '/(\d{1,3}(?:[\.,]\d{1,2})?) ?vol/i', // 28 vol
    ];
    
    foreach ($volumePatterns as $pattern) {
        if (preg_match($pattern, $productName, $matches)) {
            $volume = floatval(str_replace(',', '.', $matches[1]));
            if ($volume >= 10 && $volume <= 200) {
                return $volume;
            }
        }
    }
    
    return null;
}

// Função para buscar produtos da API
function fetchProducts() {
    $ch = curl_init();
    
    // Headers corretos para a API da NuvemShop
    $headers = [
        'Authentication: bearer ' . $GLOBALS['config']['token'],
        'Content-Type: application/json',
        'User-Agent: Surfboard-Calculator/1.0'
    ];
    
    logDebug("Fazendo requisição para: " . $GLOBALS['config']['api_url']);
    logDebug("Headers: " . implode(', ', $headers));
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $GLOBALS['config']['api_url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_VERBOSE => true,
        CURLOPT_HEADER => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    logDebug("HTTP Code: $httpCode");
    logDebug("cURL Info: " . json_encode($info));
    
    if ($error) {
        logDebug("Erro cURL: $error");
        return null;
    }
    
    // Separar headers da resposta
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    logDebug("Response Headers: $headers");
    logDebug("Response Body: " . substr($body, 0, 500) . "...");
    
    if ($httpCode !== 200) {
        logDebug("HTTP Error $httpCode - Response: $body");
        return null;
    }
    
    $data = json_decode($body, true);
    
    if (!$data) {
        logDebug("Erro ao decodificar JSON: " . json_last_error_msg());
        logDebug("Body recebido: " . $body);
        return null;
    }
    
    return $data;
}

// Função principal de teste
function testVolumeExtraction() {
    echo "=== TESTE DE EXTRAÇÃO DE VOLUME ===\n\n";
    
    // Buscar produtos
    echo "1. Buscando produtos da API...\n";
    $products = fetchProducts();
    
    if (!$products) {
        echo "❌ Erro ao buscar produtos da API\n";
        return;
    }
    
    echo "✅ Produtos encontrados: " . count($products) . "\n\n";
    
    // Testar extração de volume
    echo "2. Testando extração de volume...\n";
    $productsWithVolume = [];
    $productsWithoutVolume = [];
    
    foreach ($products as $product) {
        $name = $product['name'] ?? '';
        $volume = extractVolumeFromName($name);
        
        if ($volume) {
            $productsWithVolume[] = [
                'name' => $name,
                'volume' => $volume
            ];
        } else {
            $productsWithoutVolume[] = $name;
        }
    }
    
    echo "✅ Produtos COM volume: " . count($productsWithVolume) . "\n";
    echo "❌ Produtos SEM volume: " . count($productsWithoutVolume) . "\n\n";
    
    // Mostrar produtos com volume
    if (!empty($productsWithVolume)) {
        echo "3. Produtos com volume encontrado:\n";
        echo str_repeat("-", 60) . "\n";
        
        foreach ($productsWithVolume as $item) {
            $name = $item['name'];
            if (is_array($name)) $name = reset($name);
            $variant = $item['variant'] ?? null;
            if (is_array($variant)) $variant = reset($variant);
            $size = $item['size'] ?? null;
            $volume = $item['volume'];
            $linha = sprintf("%-30s | %-25s | %5sL",
                substr($name, 0, 30),
                $variant ? substr($variant, 0, 25) : ($size ? $size : '-'),
                $volume
            );
            echo $linha . "\n";
        }
        echo str_repeat("-", 60) . "\n\n";
    }
    
    // Mostrar alguns produtos sem volume (para análise)
    if (!empty($productsWithoutVolume)) {
        echo "4. Exemplos de produtos SEM volume (primeiros 10):\n";
        echo str_repeat("-", 60) . "\n";
        
        $count = 0;
        foreach ($productsWithoutVolume as $name) {
            if ($count >= 10) break;
            echo "- " . substr($name, 0, 50) . "\n";
            $count++;
        }
        echo str_repeat("-", 60) . "\n\n";
    }
    
    // Estatísticas
    echo "5. Estatísticas:\n";
    $totalProducts = count($products);
    $withVolume = count($productsWithVolume);
    $withoutVolume = count($productsWithoutVolume);
    
    echo "Total de produtos: $totalProducts\n";
    echo "Com volume: $withVolume (" . round(($withVolume/$totalProducts)*100, 1) . "%)\n";
    echo "Sem volume: $withoutVolume (" . round(($withoutVolume/$totalProducts)*100, 1) . "%)\n\n";
    
    // Testar padrões específicos
    echo "6. Testando padrões específicos...\n";
    $testNames = [
        "Prancha Surf 28L",
        "Shortboard 30.5L",
        "Longboard 45L",
        "Fish 25.2L",
        "Prancha sem volume",
        "28L Shortboard",
        "30.5 L Fish",
        "45 litros Longboard"
    ];
    
    foreach ($testNames as $testName) {
        $volume = extractVolumeFromName($testName);
        $status = $volume ? "✅ $volume" : "❌ N/A";
        echo sprintf("%-25s | %s\n", $testName, $status);
    }
    
    echo "\n=== FIM DO TESTE ===\n";
}

// Executar teste
testVolumeExtraction();
?> 