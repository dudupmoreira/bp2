<?php
/**
 * Proxy Simplificado para Shared Hosting - Board's Point
 * 
 * Versão otimizada para funcionar em ambientes com limitações
 */

// Configurações básicas
$store_id = '2446542';
$token = '96685dd9b9c0c82b5d613b3d5dd466f1d6418083';
$cache_file = 'cache_products_simple.json';
$cache_duration = 86400; // 24 horas

// Headers de resposta
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Função para log simples
function simpleLog($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents('debug_simple.log', $logMessage, FILE_APPEND | LOCK_EX);
}

// Função para buscar produtos com configurações mínimas
function fetchProductsSimple($store_id, $token) {
    $url = "https://api.nuvemshop.com.br/v1/{$store_id}/products?limit=100";
    
    simpleLog("Fazendo requisição para: $url");
    
    // Configuração mínima do cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authentication: bearer {$token}",
        "Content-Type: application/json",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"
    ]);
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    simpleLog("HTTP Code: $httpcode, Erro: $error");
    
    if ($error) {
        return ['error' => 'Erro de conexão: ' . $error];
    }
    
    if ($httpcode !== 200) {
        return ['error' => 'Erro da API: HTTP ' . $httpcode];
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Erro ao decodificar JSON'];
    }
    
    simpleLog("Produtos encontrados: " . count($data));
    return $data;
}

// Função para processar produtos de forma simplificada
function processProductsSimple($products) {
    $processed = [];
    
    foreach ($products as $product) {
        // Extrair nome
        $productName = $product['name'] ?? '';
        if (is_array($productName)) {
            $productName = reset($productName);
        }
        
        // Determinar categoria com análise mais detalhada
        $categoria = 'shortboard'; // padrão
        $text_lower = strtolower($productName);
        
        // Análise detalhada de categorias
        if (strpos($text_lower, 'fish') !== false || strpos($text_lower, 'twin') !== false || strpos($text_lower, 'twinny') !== false) {
            $categoria = 'fish';
        } elseif (strpos($text_lower, 'longboard') !== false || strpos($text_lower, 'long board') !== false || strpos($text_lower, 'malibu') !== false) {
            $categoria = 'longboard';
        } elseif (strpos($text_lower, 'funboard') !== false || strpos($text_lower, 'fun board') !== false || strpos($text_lower, 'mini malibu') !== false) {
            $categoria = 'funboard';
        } elseif (strpos($text_lower, 'gun') !== false || strpos($text_lower, 'big wave') !== false || strpos($text_lower, 'step up') !== false) {
            $categoria = 'gun';
        } elseif (strpos($text_lower, 'hybrid') !== false || strpos($text_lower, 'híbrido') !== false) {
            $categoria = 'hybrid';
        } elseif (strpos($text_lower, 'performance') !== false || strpos($text_lower, 'pro') !== false || strpos($text_lower, 'competition') !== false) {
            $categoria = 'performance';
        } elseif (strpos($text_lower, 'beginner') !== false || strpos($text_lower, 'iniciante') !== false || strpos($text_lower, 'soft') !== false) {
            $categoria = 'beginner';
        } elseif (strpos($text_lower, 'retro') !== false || strpos($text_lower, 'single fin') !== false) {
            $categoria = 'retro';
        }
        
        // Extrair volume das variações
        $volume = null;
        $size = null;
        if (isset($product['variants']) && is_array($product['variants'])) {
            foreach ($product['variants'] as $variant) {
                $variantName = $variant['name'] ?? '';
                if (is_array($variantName)) {
                    $variantName = reset($variantName);
                }
                
                // Buscar volume no nome da variação
                if (preg_match('/(\d{1,3}(?:[\.,]\d{1,2})?)L/i', $variantName, $matches)) {
                    $volume = floatval(str_replace(',', '.', $matches[1]));
                    if ($volume >= 10 && $volume <= 200) {
                        break;
                    }
                }
                
                // Buscar tamanho no nome da variação (ex: 5'8", 6'0", etc)
                if (preg_match('/(\d+\'?\d*["\"]?)/i', $variantName, $matches)) {
                    $size = $matches[1];
                }
                
                // Buscar nas opções da variação
                if (isset($variant['option1']) && !empty($variant['option1'])) {
                    $size = $variant['option1'];
                }
            }
        }
        
        // Se não achou nas variações, buscar no nome do produto
        if (!$volume) {
            if (preg_match('/(\d{1,3}(?:[\.,]\d{1,2})?)L/i', $productName, $matches)) {
                $volume = floatval(str_replace(',', '.', $matches[1]));
                if ($volume < 10 || $volume > 200) {
                    $volume = null;
                }
            }
        }
        
        // Processar imagens
        $images = [];
        if (isset($product['images']) && is_array($product['images'])) {
            foreach ($product['images'] as $image) {
                if (isset($image['src'])) {
                    $images[] = ['src' => $image['src'], 'alt' => $productName];
                }
            }
        }
        
        $processed[] = [
            'id' => $product['id'],
            'name' => $productName,
            'volume' => $volume,
            'size' => $size,
            'categoria' => $categoria,
            'images' => $images,
            'handle' => $product['handle'] ?? '',
            'price' => $product['price'] ?? null,
            'stock' => $product['stock'] ?? null
        ];
    }
    
    return $processed;
}

// Verificar cache
if (file_exists($cache_file)) {
    $lastUpdate = filemtime($cache_file);
    if ((time() - $lastUpdate) < $cache_duration) {
        $cached_data = file_get_contents($cache_file);
        $data = json_decode($cached_data, true);
        if ($data && !isset($data['error'])) {
            simpleLog("Servindo do cache");
            echo json_encode($data);
            exit;
        }
    }
}

// Buscar dados da API
simpleLog("Buscando da API");
$api_response = fetchProductsSimple($store_id, $token);

if (isset($api_response['error'])) {
    http_response_code(500);
    echo json_encode(['error' => $api_response['error']]);
    exit;
}

// Processar produtos
$processed_products = processProductsSimple($api_response);

// Filtrar se necessário
$input = json_decode(file_get_contents('php://input'), true);
if ($input && (isset($input['volume_min']) || isset($input['volume_max']) || isset($input['categoria']))) {
    $volume_min = $input['volume_min'] ?? 0;
    $volume_max = $input['volume_max'] ?? 999;
    $categoria = $input['categoria'] ?? null;
    
    simpleLog("Filtrando: volume $volume_min-$volume_max, categoria: $categoria");
    
    $filtered = array_filter($processed_products, function($product) use ($volume_min, $volume_max, $categoria) {
        $volumeMatch = $product['volume'] && $product['volume'] >= $volume_min && $product['volume'] <= $volume_max;
        
        // Filtro de categoria mais flexível
        $categoriaMatch = true;
        if ($categoria && $categoria !== 'todas') {
            if ($categoria === 'shortboard') {
                // Para shortboard, incluir também performance e hybrid
                $categoriaMatch = in_array($product['categoria'], ['shortboard', 'performance', 'hybrid']);
            } elseif ($categoria === 'fish') {
                // Para fish, incluir também twin e twinny
                $categoriaMatch = in_array($product['categoria'], ['fish', 'twin', 'twinny']);
            } elseif ($categoria === 'longboard') {
                // Para longboard, incluir também malibu
                $categoriaMatch = in_array($product['categoria'], ['longboard', 'malibu']);
            } else {
                // Para outras categorias, match exato
                $categoriaMatch = $product['categoria'] === $categoria;
            }
        }
        
        return $volumeMatch && $categoriaMatch;
    });
    
    $processed_products = array_values($filtered);
    simpleLog("Filtrados: " . count($processed_products) . " produtos");
}

// Salvar no cache
try {
    file_put_contents($cache_file, json_encode($processed_products));
    simpleLog("Cache atualizado");
} catch (Exception $e) {
    simpleLog("Erro ao salvar cache: " . $e->getMessage());
}

// Retornar resposta
echo json_encode(['success' => true, 'products' => $processed_products]);
?> 