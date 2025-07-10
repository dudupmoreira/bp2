<?php
/**
 * Teste Simples da API NuvemShop
 * Para diagnosticar problemas de autenticação
 */

// Configurações
$config = [
    'api_url' => 'https://api.nuvemshop.com.br/v1/2446542/products',
    'token' => '96685dd9b9c0c82b5d613b3d5dd466f1d6418083',
    'debug' => true
];

// Função para fazer log
function logDebug($message) {
    echo "[DEBUG] " . date('Y-m-d H:i:s') . " - $message\n";
}

// Teste 1: Verificar se o token está correto
function testToken() {
    echo "=== TESTE 1: VERIFICAÇÃO DO TOKEN ===\n";
    
    $token = $GLOBALS['config']['token'];
    echo "Token: " . substr($token, 0, 10) . "..." . substr($token, -10) . "\n";
    echo "Comprimento: " . strlen($token) . " caracteres\n";
    
    // Verificar se o token tem o formato correto
    if (strlen($token) !== 40) {
        echo "❌ ERRO: Token deve ter 40 caracteres\n";
        return false;
    }
    
    if (!preg_match('/^[a-f0-9]{40}$/', $token)) {
        echo "❌ ERRO: Token deve conter apenas caracteres hexadecimais\n";
        return false;
    }
    
    echo "✅ Token parece estar no formato correto\n";
    return true;
}

// Teste 2: Testar diferentes headers
function testHeaders() {
    echo "\n=== TESTE 2: TESTE DE HEADERS ===\n";
    
    $url = $GLOBALS['config']['api_url'];
    $token = $GLOBALS['config']['token'];
    
    // Teste com diferentes formatos de header
            $headerTests = [
            [
                'name' => 'Authentication: bearer',
                'headers' => ["Authentication: bearer $token", 'Content-Type: application/json', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36']
            ],
            [
                'name' => 'Authorization: Bearer',
                'headers' => ["Authorization: Bearer $token", 'Content-Type: application/json', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36']
            ],
            [
                'name' => 'X-Auth-Token',
                'headers' => ["X-Auth-Token: $token", 'Content-Type: application/json', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36']
            ]
        ];
    
    foreach ($headerTests as $test) {
        echo "\nTestando: " . $test['name'] . "\n";
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $test['headers'],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo "HTTP Code: $httpCode\n";
        
        if ($error) {
            echo "Erro cURL: $error\n";
        } else {
            // Separar headers da resposta
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
            
            echo "Response: " . substr($body, 0, 200) . "\n";
            
            if ($httpCode === 200) {
                echo "✅ SUCESSO com " . $test['name'] . "\n";
                return $test['headers'];
            } elseif ($httpCode === 401) {
                echo "❌ Não autorizado\n";
            } elseif ($httpCode === 400) {
                echo "❌ Bad Request\n";
            } else {
                echo "❌ Erro HTTP $httpCode\n";
            }
        }
    }
    
    return null;
}

// Teste 3: Verificar se a URL está correta
function testURL() {
    echo "\n=== TESTE 3: VERIFICAÇÃO DA URL ===\n";
    
    $url = $GLOBALS['config']['api_url'];
    echo "URL: $url\n";
    
    // Verificar se a URL está no formato correto
    if (!preg_match('/^https:\/\/api\.nuvemshop\.com\.br\/v1\/\d+\/products$/', $url)) {
        echo "❌ ERRO: URL não está no formato esperado\n";
        return false;
    }
    
    // Extrair Store ID
    if (preg_match('/\/v1\/(\d+)\//', $url, $matches)) {
        $storeId = $matches[1];
        echo "Store ID: $storeId\n";
        
        if (strlen($storeId) !== 7) {
            echo "❌ ERRO: Store ID deve ter 7 dígitos\n";
            return false;
        }
        
        echo "✅ Store ID parece estar correto\n";
    }
    
    return true;
}

// Teste 4: Testar com Postman-like request
function testPostmanStyle() {
    echo "\n=== TESTE 4: ESTILO POSTMAN ===\n";
    
    $url = $GLOBALS['config']['api_url'];
    $token = $GLOBALS['config']['token'];
    
    // Simular exatamente o request do Postman
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authentication: bearer ' . $token,
            'Content-Type: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Cookie: __cf_bm=JljN3h7Indt3ANHFnMV833zfmitWy4wVx9pN_aFdr0Q-1752019838-1.0.1.1-Yd8xUGVjoc2z.SH_R9kg1fyZfIzpZ6jXD749KkGzlhaepwRoqd7PH7pb5HnP8qN4.aVJWeSpaXgOCD1mfhQ0wZEq6DLQIoxdVihDqLYuPGg'
        ],
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HEADER => true
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    
    if ($error) {
        echo "Erro cURL: $error\n";
        return false;
    }
    
    // Separar headers da resposta
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    echo "Response: " . substr($body, 0, 300) . "\n";
    
    if ($httpCode === 200) {
        echo "✅ SUCESSO com estilo Postman\n";
        return true;
    } else {
        echo "❌ Falhou com estilo Postman\n";
        return false;
    }
}

// Executar todos os testes
echo "🔍 DIAGNÓSTICO DA API NUVEMSHOP\n";
echo "================================\n\n";

$tokenOk = testToken();
$urlOk = testURL();

if ($tokenOk && $urlOk) {
    $workingHeaders = testHeaders();
    $postmanOk = testPostmanStyle();
    
    if ($workingHeaders || $postmanOk) {
        echo "\n✅ PROBLEMA IDENTIFICADO E RESOLVIDO\n";
        if ($workingHeaders) {
            echo "Headers funcionais encontrados: " . implode(', ', $workingHeaders) . "\n";
        }
    } else {
        echo "\n❌ TODOS OS TESTES FALHARAM\n";
        echo "Verifique:\n";
        echo "1. Se o token está correto\n";
        echo "2. Se o Store ID está correto\n";
        echo "3. Se a API está funcionando\n";
    }
} else {
    echo "\n❌ CONFIGURAÇÃO BÁSICA INCORRETA\n";
    echo "Corrija o token ou a URL antes de continuar\n";
}

echo "\n=== FIM DO DIAGNÓSTICO ===\n";
?> 