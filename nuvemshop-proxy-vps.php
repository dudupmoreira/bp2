<?php
/**
 * Proxy Otimizado para VPS - Board's Point
 * 
 * Versão para bp2.ocoworks.com com configurações robustas
 */

// Configurações
$store_id = '2446542';
$token = '96685dd9b9c0c82b5d613b3d5dd466f1d6418083';
$cache_file = 'cache_products_vps.json';
$cache_duration = 3600; // 1 hora para VPS

// Headers de resposta
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, must-revalidate');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Função para log detalhado
function vpsLog($message, $level = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$level] $message\n";
    file_put_contents('debug_vps.log', $logMessage, FILE_APPEND | LOCK_EX);
}

// Função para buscar produtos com configurações robustas
function fetchProductsVPS($store_id, $token) {
    $url = "https://api.nuvemshop.com.br/v1/{$store_id}/products?limit=100";
    
    vpsLog("Iniciando requisição para: $url");
    
    // Configuração robusta do cURL para VPS
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_HTTPHEADER => [
            "Authentication: bearer {$token}",
            "Content-Type: application/json",
            "User-Agent: BoardPoint-Calculator/1.0",
            "Accept: application/json"
        ],
        CURLOPT_ENCODING => 'gzip,deflate',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
    ]);
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    vpsLog("HTTP Code: $httpcode, Tempo: {$info['total_time']}s, Erro: $error");
    
    if ($error) {
        vpsLog("Erro de conexão: $error", 'ERROR');
        return ['error' => 'Erro de conexão: ' . $error];
    }
    
    if ($httpcode !== 200) {
        vpsLog("Erro da API: HTTP $httpcode", 'ERROR');
        return ['error' => 'Erro da API: HTTP ' . $httpcode];
    }
    
    if (empty($response)) {
        vpsLog("Resposta vazia da API", 'ERROR');
        return ['error' => 'Resposta vazia da API'];
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        vpsLog("Erro ao decodificar JSON: " . json_last_error_msg(), 'ERROR');
        return ['error' => 'Erro ao decodificar JSON: ' . json_last_error_msg()];
    }
    
    if (!is_array($data)) {
        vpsLog("Resposta não é um array: " . gettype($data), 'ERROR');
        return ['error' => 'Formato de resposta inválido'];
    }
    
    vpsLog("Produtos encontrados: " . count($data));
    return $data;
}

// Função para processar produtos com extração robusta
function processProductsVPS($products) {
    $processed = [];
    $stats = ['total' => 0, 'com_volume' => 0, 'com_tamanho' => 0, 'categorias' => []];
    
    foreach ($products as $product) {
        $stats['total']++;
        
        // Extrair nome
        $productName = $product['name'] ?? '';
        if (is_array($productName)) {
            $productName = reset($productName);
        }
        
        // Extrair volume das variações
        $volume = null;
        $size = null;
        $variant = null;
        
        if (isset($product['variants']) && is_array($product['variants'])) {
            foreach ($product['variants'] as $var) {
                $variantName = $var['name'] ?? '';
                if (is_array($variantName)) {
                    $variantName = reset($variantName);
                }
                
                // Buscar volume no nome da variação
                if (preg_match('/(\d{1,3}(?:[\.,]\d{1,2})?)L/i', $variantName, $matches)) {
                    $volume = floatval(str_replace(',', '.', $matches[1]));
                    if ($volume >= 10 && $volume <= 200) {
                        $variant = $variantName;
                        break;
                    }
                }
                
                // Buscar tamanho no nome da variação
                if (preg_match('/(\d+\'?\d*["\"]?)/i', $variantName, $matches)) {
                    $size = $matches[1];
                }
                
                // Buscar nas opções da variação
                if (isset($var['option1']) && !empty($var['option1'])) {
                    $size = $var['option1'];
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
        
        if ($volume) $stats['com_volume']++;
        if ($size) $stats['com_tamanho']++;
        
        // Processar imagens
        $images = [];
        if (isset($product['images']) && is_array($product['images'])) {
            foreach ($product['images'] as $image) {
                if (isset($image['src'])) {
                    $images[] = ['src' => $image['src'], 'alt' => $productName];
                }
            }
        }
        
        // Determinar categoria com análise detalhada
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
        
        $stats['categorias'][$categoria] = ($stats['categorias'][$categoria] ?? 0) + 1;
        
        $processed[] = [
            'id' => $product['id'],
            'name' => $productName,
            'volume' => $volume,
            'size' => $size,
            'variant' => $variant,
            'categoria' => $categoria,
            'images' => $images,
            'handle' => $product['handle'] ?? '',
            'price' => $product['price'] ?? null,
            'stock' => $product['stock'] ?? null
        ];
    }
    
    vpsLog("Estatísticas: " . json_encode($stats));
    return $processed;
}

// Verificar cache
if (file_exists($cache_file)) {
    $lastUpdate = filemtime($cache_file);
    if ((time() - $lastUpdate) < $cache_duration) {
        $cached_data = file_get_contents($cache_file);
        $data = json_decode($cached_data, true);
        if ($data && !isset($data['error'])) {
            vpsLog("Servindo do cache (atualizado há " . (time() - $lastUpdate) . "s)");
            
            // Aplicar filtros se necessário
            $input = json_decode(file_get_contents('php://input'), true);
            if ($input && (isset($input['volume_min']) || isset($input['volume_max']) || isset($input['categoria']))) {
                $volume_min = $input['volume_min'] ?? 0;
                $volume_max = $input['volume_max'] ?? 999;
                $categoria = $input['categoria'] ?? null;
                
                vpsLog("Filtrando cache: volume $volume_min-$volume_max, categoria: $categoria");
                
                $filtered = array_filter($data, function($product) use ($volume_min, $volume_max, $categoria) {
                    $volumeMatch = $product['volume'] && $product['volume'] >= $volume_min && $product['volume'] <= $volume_max;
                    
                    $categoriaMatch = true;
                    if ($categoria && $categoria !== 'todas') {
                        if ($categoria === 'shortboard') {
                            $categoriaMatch = in_array($product['categoria'], ['shortboard', 'performance', 'hybrid']);
                        } elseif ($categoria === 'fish') {
                            $categoriaMatch = in_array($product['categoria'], ['fish', 'twin', 'twinny']);
                        } elseif ($categoria === 'longboard') {
                            $categoriaMatch = in_array($product['categoria'], ['longboard', 'malibu']);
                        } else {
                            $categoriaMatch = $product['categoria'] === $categoria;
                        }
                    }
                    
                    return $volumeMatch && $categoriaMatch;
                });
                
                $data = array_values($filtered);
                vpsLog("Filtrados: " . count($data) . " produtos");
            }
            
            echo json_encode(['success' => true, 'products' => $data, 'from_cache' => true]);
            exit;
        }
    }
}

// Buscar dados da API
vpsLog("Buscando da API (cache expirado ou inexistente)");
$api_response = fetchProductsVPS($store_id, $token);

if (isset($api_response['error'])) {
    vpsLog("Erro da API: " . $api_response['error'], 'ERROR');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $api_response['error']]);
    exit;
}

// Processar produtos
$processed_products = processProductsVPS($api_response);

// Salvar no cache
try {
    file_put_contents($cache_file, json_encode($processed_products));
    vpsLog("Cache atualizado com " . count($processed_products) . " produtos");
} catch (Exception $e) {
    vpsLog("Erro ao salvar cache: " . $e->getMessage(), 'ERROR');
}

// Aplicar filtros se necessário
$input = json_decode(file_get_contents('php://input'), true);
if ($input && (isset($input['volume_min']) || isset($input['volume_max']) || isset($input['categoria']))) {
    $volume_min = $input['volume_min'] ?? 0;
    $volume_max = $input['volume_max'] ?? 999;
    $categoria = $input['categoria'] ?? null;
    
    vpsLog("Filtrando: volume $volume_min-$volume_max, categoria: $categoria");
    
    $filtered = array_filter($processed_products, function($product) use ($volume_min, $volume_max, $categoria) {
        $volumeMatch = $product['volume'] && $product['volume'] >= $volume_min && $product['volume'] <= $volume_max;
        
        $categoriaMatch = true;
        if ($categoria && $categoria !== 'todas') {
            if ($categoria === 'shortboard') {
                $categoriaMatch = in_array($product['categoria'], ['shortboard', 'performance', 'hybrid']);
            } elseif ($categoria === 'fish') {
                $categoriaMatch = in_array($product['categoria'], ['fish', 'twin', 'twinny']);
            } elseif ($categoria === 'longboard') {
                $categoriaMatch = in_array($product['categoria'], ['longboard', 'malibu']);
            } else {
                $categoriaMatch = $product['categoria'] === $categoria;
            }
        }
        
        return $volumeMatch && $categoriaMatch;
    });
    
    $processed_products = array_values($filtered);
    vpsLog("Filtrados: " . count($processed_products) . " produtos");
}

// Retornar resposta
echo json_encode(['success' => true, 'products' => $processed_products, 'from_cache' => false]);
?> 