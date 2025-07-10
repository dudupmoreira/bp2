<?php
/**
 * Configurações de Produção - Board's Point Calculator
 * 
 * Este arquivo contém as configurações necessárias para rodar
 * a aplicação em bp.ocoworks.com
 */

// Configurações da API NuvemShop
define('NUVEMSHOP_STORE_ID', '2446542'); // Substitua pelo seu Store ID
define('NUVEMSHOP_TOKEN', '96685dd9b9c0c82b5d613b3d5dd466f1d6418083'); // Substitua pelo seu Token

// Configurações de Cache
define('CACHE_DURATION', 3600); // 1 hora
define('CACHE_FILE', 'cache_products.json');

// Configurações de Debug
define('DEBUG_MODE', false); // Mude para true se precisar de logs
define('LOG_FILE', 'debug.log');

// Configurações de Segurança
define('ALLOWED_ORIGINS', ['https://bp.ocoworks.com', 'http://bp.ocoworks.com']); // Domínio específico
define('MAX_REQUESTS_PER_MINUTE', 60);

// Configurações de Performance
define('API_TIMEOUT', 30);
define('MAX_PRODUCTS_PER_REQUEST', 100);

// Configurações do Domínio
define('SITE_DOMAIN', 'bp.ocoworks.com');
define('SITE_URL', 'https://bp.ocoworks.com');

// Função para verificar se está em produção
function isProduction() {
    return $_SERVER['HTTP_HOST'] === 'bp.ocoworks.com';
}

// Função para log de debug
function debugLog($message) {
    if (DEBUG_MODE) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        file_put_contents(LOG_FILE, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

// Função para verificar rate limiting
function checkRateLimit() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = "rate_limit_$ip";
    $cacheFile = "cache_$cacheKey.json";
    
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if ($data && (time() - $data['timestamp']) < 60) {
            if ($data['count'] >= MAX_REQUESTS_PER_MINUTE) {
                http_response_code(429);
                echo json_encode(['error' => 'Rate limit exceeded']);
                exit;
            }
            $data['count']++;
        } else {
            $data = ['timestamp' => time(), 'count' => 1];
        }
    } else {
        $data = ['timestamp' => time(), 'count' => 1];
    }
    
    file_put_contents($cacheFile, json_encode($data));
}

// Função para verificar CORS
function checkCORS() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $allowed = ALLOWED_ORIGINS;
    
    if (in_array($origin, $allowed)) {
        header("Access-Control-Allow-Origin: $origin");
    } elseif (in_array('*', $allowed)) {
        header("Access-Control-Allow-Origin: $origin");
    }
    
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

// Função para limpar cache antigo
function cleanOldCache() {
    $files = glob('cache_*.json');
    $now = time();
    
    foreach ($files as $file) {
        if (is_file($file)) {
            $fileTime = filemtime($file);
            if (($now - $fileTime) > 86400) { // 24 horas
                unlink($file);
            }
        }
    }
}

// Configurações de erro para produção
if (isProduction()) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', 'error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Verificar se o arquivo de configuração está sendo incluído
if (!defined('BOARDPOINT_CONFIG_LOADED')) {
    define('BOARDPOINT_CONFIG_LOADED', true);
}
?> 