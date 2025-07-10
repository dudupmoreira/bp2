<?php
/**
 * Verificação do Hosting - Board's Point Calculator
 * 
 * Este arquivo verifica se o ambiente está pronto para rodar a aplicação
 */

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 Verificação do Hosting - Board's Point</h1>";

$checks = [];
$success = 0;
$warnings = 0;
$errors = 0;

// 1. Verificar PHP
echo "<h2>PHP</h2>";
$php_version = phpversion();
if (version_compare($php_version, '7.4', '>=')) {
    echo "✅ PHP $php_version (OK)<br>";
    $checks[] = ['PHP', 'success'];
    $success++;
} else {
    echo "❌ PHP $php_version (Versão muito antiga)<br>";
    $checks[] = ['PHP', 'error'];
    $errors++;
}

// 2. Verificar cURL
echo "<h2>cURL</h2>";
if (function_exists('curl_init')) {
    echo "✅ cURL habilitado<br>";
    $checks[] = ['cURL', 'success'];
    $success++;
} else {
    echo "❌ cURL não habilitado<br>";
    $checks[] = ['cURL', 'error'];
    $errors++;
}

// 3. Verificar JSON
echo "<h2>JSON</h2>";
if (function_exists('json_encode') && function_exists('json_decode')) {
    echo "✅ JSON habilitado<br>";
    $checks[] = ['JSON', 'success'];
    $success++;
} else {
    echo "❌ JSON não habilitado<br>";
    $checks[] = ['JSON', 'error'];
    $errors++;
}

// 4. Verificar permissões
echo "<h2>Permissões</h2>";
$test_file = 'test_permissions.txt';
if (file_put_contents($test_file, 'test') !== false) {
    unlink($test_file);
    echo "✅ Pasta gravável<br>";
    $checks[] = ['Permissões', 'success'];
    $success++;
} else {
    echo "❌ Pasta não gravável<br>";
    $checks[] = ['Permissões', 'error'];
    $errors++;
}

// 5. Verificar SSL
echo "<h2>SSL</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.google.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo "✅ SSL habilitado<br>";
    $checks[] = ['SSL', 'success'];
    $success++;
} else {
    echo "⚠️ SSL pode ter problemas (HTTP $http_code)<br>";
    $checks[] = ['SSL', 'warning'];
    $warnings++;
}

// 6. Verificar timeout
echo "<h2>Timeout</h2>";
$timeout = ini_get('max_execution_time');
if ($timeout >= 30 || $timeout == 0) {
    echo "ℹ️ Timeout: $timeout segundos<br>";
    $checks[] = ['Timeout', 'success'];
    $success++;
} else {
    echo "⚠️ Timeout muito baixo: $timeout segundos<br>";
    $checks[] = ['Timeout', 'warning'];
    $warnings++;
}

// 7. Verificar memória
echo "<h2>Memória</h2>";
$memory_limit = ini_get('memory_limit');
echo "ℹ️ Limite de memória: $memory_limit<br>";
$checks[] = ['Memória', 'success'];
$success++;

// 8. Verificar arquivos necessários
echo "<h2>Arquivos</h2>";

$required_files = [
    'surfboard-volume-calculator.html' => 'Calculadora',
    'nuvemshop-proxy.php' => 'Proxy API',
    'test.html' => 'Página de Teste'
];

foreach ($required_files as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $description encontrado<br>";
        $checks[] = [$description, 'success'];
        $success++;
    } else {
        echo "❌ $description não encontrado<br>";
        $checks[] = [$description, 'error'];
        $errors++;
    }
}

// 9. Testar API externa
echo "<h2>API Externa</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.nuvemshop.com.br/v1/2446542/products?limit=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authentication: bearer 96685dd9b9c0c82b5d613b3d5dd466f1d6418083',
    'Content-Type: application/json'
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo "✅ API externa acessível<br>";
    $checks[] = ['API Externa', 'success'];
    $success++;
} else {
    echo "⚠️ Não foi possível testar API externa (HTTP $http_code)<br>";
    $checks[] = ['API Externa', 'warning'];
    $warnings++;
}

// Resumo
echo "<h2>📊 Resumo</h2>";
echo "✅ Sucessos: $success<br>";
echo "⚠️ Avisos: $warnings<br>";
echo "❌ Erros: $errors<br>";

if ($errors == 0) {
    echo "<h3>🎉 Seu hosting está pronto para a aplicação!</h3>";
} else {
    echo "<h3>⚠️ Corrija os erros antes de usar a aplicação.</h3>";
}

// Informações do servidor
echo "<h2>🔧 Informações do Servidor</h2>";
echo "<strong>Sistema:</strong> " . php_uname() . "<br>";
echo "<strong>Servidor:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "<strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "<strong>Script Path:</strong> " . __FILE__ . "<br>";

echo "<h2>📋 Próximos Passos</h2>";
echo "<ul>";
echo "<li>Se todos os testes passaram, configure o token da API em nuvemshop-proxy.php</li>";
echo "<li>Acesse test.html para testar a API</li>";
echo "<li>Acesse surfboard-volume-calculator.html para usar a calculadora</li>";
echo "</ul>";
?> 