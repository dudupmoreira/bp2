<?php
/**
 * Teste de Limitações do Shared Hosting - Hostinger
 * 
 * Este arquivo testa especificamente as limitações que podem estar causando HTTP 422
 */

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 Teste de Limitações - Shared Hosting</h1>";
echo "<p><strong>Domínio:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>Servidor:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

$tests = [];
$success = 0;
$errors = 0;

// Teste 1: Verificar se cURL funciona com HTTPS básico
echo "<h2>🧪 Teste 1: cURL HTTPS Básico</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://httpbin.org/get');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Hostinger-Test/1.0');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($http_code == 200 && !$error) {
    echo "✅ cURL HTTPS funcionando<br>";
    $tests[] = ['cURL HTTPS', 'success'];
    $success++;
} else {
    echo "❌ cURL HTTPS falhou: HTTP $http_code, Erro: $error<br>";
    $tests[] = ['cURL HTTPS', 'error'];
    $errors++;
}

// Teste 2: Verificar se consegue acessar API externa sem autenticação
echo "<h2>🧪 Teste 2: API Externa Sem Auth</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.nuvemshop.com.br/v1/2446542/products?limit=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Hostinger-Test/1.0');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($http_code == 401) {
    echo "✅ API externa acessível (401 é esperado sem token)<br>";
    $tests[] = ['API Externa', 'success'];
    $success++;
} else {
    echo "❌ API externa não acessível: HTTP $http_code, Erro: $error<br>";
    $tests[] = ['API Externa', 'error'];
    $errors++;
}

// Teste 3: Verificar se consegue fazer POST com JSON
echo "<h2>🧪 Teste 3: POST com JSON</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://httpbin.org/post');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['test' => 'data']));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: Hostinger-Test/1.0'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($http_code == 200 && !$error) {
    echo "✅ POST com JSON funcionando<br>";
    $tests[] = ['POST JSON', 'success'];
    $success++;
} else {
    echo "❌ POST com JSON falhou: HTTP $http_code, Erro: $error<br>";
    $tests[] = ['POST JSON', 'error'];
    $errors++;
}

// Teste 4: Verificar headers específicos da NuvemShop
echo "<h2>🧪 Teste 4: Headers da NuvemShop</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.nuvemshop.com.br/v1/2446542/products?limit=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authentication: bearer 96685dd9b9c0c82b5d613b3d5dd466f1d6418083',
    'Content-Type: application/json',
    'User-Agent: Hostinger-Test/1.0'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$response_headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
curl_close($ch);

echo "Headers enviados:<br>";
echo "<pre>" . htmlspecialchars($response_headers) . "</pre>";
echo "HTTP Code: $http_code<br>";
echo "Erro: $error<br>";

if ($http_code == 200) {
    echo "✅ Headers da NuvemShop aceitos<br>";
    $tests[] = ['Headers NuvemShop', 'success'];
    $success++;
} else {
    echo "❌ Headers da NuvemShop rejeitados<br>";
    $tests[] = ['Headers NuvemShop', 'error'];
    $errors++;
}

// Teste 5: Verificar timeout e memória
echo "<h2>🧪 Teste 5: Recursos do Sistema</h2>";
echo "Timeout: " . ini_get('max_execution_time') . "s<br>";
echo "Memória: " . ini_get('memory_limit') . "<br>";
echo "Upload: " . ini_get('upload_max_filesize') . "<br>";
echo "Post: " . ini_get('post_max_size') . "<br>";

$tests[] = ['Recursos', 'success'];
$success++;

// Teste 6: Verificar se é problema de proxy/firewall
echo "<h2>🧪 Teste 6: Proxy/Firewall</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.nuvemshop.com.br/v1/2446542/products?limit=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authentication: bearer 96685dd9b9c0c82b5d613b3d5dd466f1d6418083',
    'Content-Type: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
curl_close($ch);

echo "Tempo total: " . round($total_time, 2) . "s<br>";
echo "HTTP Code: $http_code<br>";
echo "Erro: $error<br>";

if ($http_code == 200) {
    echo "✅ Sem limitações de proxy/firewall<br>";
    $tests[] = ['Proxy/Firewall', 'success'];
    $success++;
} else {
    echo "❌ Possível limitação de proxy/firewall<br>";
    $tests[] = ['Proxy/Firewall', 'error'];
    $errors++;
}

// Teste 7: Verificar se é problema de SSL/TLS
echo "<h2>🧪 Teste 7: SSL/TLS</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.nuvemshop.com.br/v1/2446542/products?limit=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authentication: bearer 96685dd9b9c0c82b5d613b3d5dd466f1d6418083',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($http_code == 200) {
    echo "✅ SSL/TLS funcionando<br>";
    $tests[] = ['SSL/TLS', 'success'];
    $success++;
} else {
    echo "❌ Problema com SSL/TLS: $error<br>";
    $tests[] = ['SSL/TLS', 'error'];
    $errors++;
}

// Resumo
echo "<h2>📊 Resumo dos Testes</h2>";
echo "✅ Sucessos: $success<br>";
echo "❌ Erros: $errors<br>";

if ($errors == 0) {
    echo "<h3>🎉 Nenhuma limitação detectada!</h3>";
    echo "<p>O problema pode estar em outro lugar.</p>";
} else {
    echo "<h3>⚠️ Limitações detectadas!</h3>";
    echo "<p>O shared hosting pode estar bloqueando algumas requisições.</p>";
}

// Recomendações
echo "<h2>💡 Recomendações</h2>";
if ($errors > 0) {
    echo "<ul>";
    echo "<li><strong>Contate o suporte da Hostinger</strong> sobre limitações de API</li>";
    echo "<li><strong>Considere um VPS</strong> para mais controle</li>";
    echo "<li><strong>Use um proxy externo</strong> como alternativa</li>";
    echo "<li><strong>Configure whitelist</strong> para o domínio da API</li>";
    echo "</ul>";
} else {
    echo "<p>✅ O hosting parece estar funcionando normalmente.</p>";
    echo "<p>O problema pode estar na configuração da API ou no código.</p>";
}

// Informações adicionais
echo "<h2>🔧 Informações Técnicas</h2>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
echo "<strong>cURL Version:</strong> " . curl_version()['version'] . "<br>";
echo "<strong>OpenSSL Version:</strong> " . OPENSSL_VERSION_TEXT . "<br>";
echo "<strong>IP Local:</strong> " . $_SERVER['SERVER_ADDR'] . "<br>";
echo "<strong>User Agent:</strong> " . $_SERVER['HTTP_USER_AGENT'] . "<br>";
?> 