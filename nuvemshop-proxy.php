<?php
// Incluir configurações de produção
require_once 'config-production.php';

// Headers de resposta
header('Content-Type: application/json');
checkCORS();

// Verificar rate limiting em produção
if (isProduction()) {
    checkRateLimit();
}

// Configurações da API
$store_id = NUVEMSHOP_STORE_ID;
$token = NUVEMSHOP_TOKEN;
$cache_file = CACHE_FILE;
$cache_duration = CACHE_DURATION;

// Função para log de erros
function logError($message) {
    debugLog("ERROR: " . $message);
    if (isProduction()) {
        error_log("[BoardPoint Calculator] " . $message);
    }
}

// Função para buscar produtos da API
function fetchProducts($store_id, $token) {
    // URL simples - apenas buscar todos os produtos sem filtros
    $url = "https://api.nuvemshop.com.br/v1/{$store_id}/products?fields=id,name,variants,images,description,handle,price,compare_at_price,stock,weight,height,width,length&limit=" . MAX_PRODUCTS_PER_REQUEST;
    
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
        logError("Erro de conexão: " . $error);
        return ['error' => 'Erro de conexão: ' . $error];
    }
    
    if ($httpcode !== 200) {
        logError("Erro da API: HTTP " . $httpcode . " - Resposta: " . substr($response, 0, 500));
        return ['error' => 'Erro da API: HTTP ' . $httpcode];
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        logError("Erro ao decodificar JSON: " . json_last_error_msg());
        return ['error' => 'Erro ao decodificar resposta JSON'];
    }
    
    debugLog("API response: " . count($data) . " products");
    return $data;
}

// Função para processar e filtrar produtos
function processProducts($products) {
    $processed = [];
    if (!is_array($products)) {
        logError("Produtos não é um array: " . gettype($products));
        return [];
    }
    foreach ($products as $product) {
        // Extrair nome do produto (tratando multilíngue)
        $productName = $product['name'] ?? '';
        if (is_array($productName)) {
            $productName = reset($productName);
        }
        if (!is_string($productName)) {
            $productName = '';
        }
        // Extrair categoria
        $categoria = 'shortboard';
        $text_lower = strtolower($productName);
        if (strpos($text_lower, 'longboard') !== false || strpos($text_lower, 'long board') !== false) {
            $categoria = 'longboard';
        } elseif (strpos($text_lower, 'funboard') !== false || strpos($text_lower, 'fun board') !== false) {
            $categoria = 'funboard';
        } elseif (strpos($text_lower, 'fish') !== false) {
            $categoria = 'fish';
        } elseif (strpos($text_lower, 'retro') !== false) {
            $categoria = 'retro';
        } elseif (strpos($text_lower, 'step') !== false || strpos($text_lower, 'stepup') !== false) {
            $categoria = 'stepup';
        } elseif (strpos($text_lower, 'big wave') !== false || strpos($text_lower, 'bigwave') !== false) {
            $categoria = 'bigwave';
        } elseif (strpos($text_lower, 'grom') !== false) {
            $categoria = 'grom';
        }
        // Processar imagens
        $images = [];
        if (isset($product['images']) && is_array($product['images'])) {
            foreach ($product['images'] as $image) {
                if (isset($image['src'])) {
                    $images[] = [
                        'src' => $image['src'],
                        'alt' => $productName
                    ];
                }
            }
        }
        // Extrair volume/tamanho das variações (prioritário)
        $volumePatterns = [
            '/(\d{1,3}(?:[\.,]\d{1,2})?)L/i',
            '/(\d{1,3}(?:[\.,]\d{1,2})?) ?l/i',
            '/(\d{1,3}(?:[\.,]\d{1,2})?) ?litros/i',
            '/(\d{1,3}(?:[\.,]\d{1,2})?) ?vol/i',
        ];
        $sizePatterns = [
            '/(\d{1,2}[\'′][ ]?\d{0,2}\s?x\s?\d{1,2}[ ]?\d{0,2}\/\d{0,2}\s?x\s?\d{1,2}[ ]?\d{0,2}\/\d{0,2})/i', // Ex: 5'5 x 19 1/2 x 2 7/16
        ];
        if (isset($product['variants']) && is_array($product['variants']) && count($product['variants']) > 0) {
            foreach ($product['variants'] as $variant) {
                $variantName = $variant['name'] ?? '';
                if (is_array($variantName)) $variantName = reset($variantName);
                if (!is_string($variantName)) $variantName = '';
                $variantVolume = null;
                $variantSize = null;
                foreach ($volumePatterns as $pattern) {
                    if (preg_match($pattern, $variantName, $matches)) {
                        $variantVolume = floatval(str_replace(',', '.', $matches[1]));
                        if ($variantVolume >= 10 && $variantVolume <= 200) {
                            break;
                        } else {
                            $variantVolume = null;
                        }
                    }
                }
                // Extrair do nome da variação (regex)
                foreach ($sizePatterns as $pattern) {
                    if (preg_match($pattern, $variantName, $matches)) {
                        $variantSize = $matches[1];
                        break;
                    }
                }
                // Se não achou, tentar em option1/option2/option3
                if (!$variantSize) {
                    foreach (['option1', 'option2', 'option3'] as $opt) {
                        if (!empty($variant[$opt])) {
                            $variantSize = $variant[$opt];
                            break;
                        }
                    }
                }
                $processed[] = [
                    'id' => $product['id'],
                    'name' => $productName,
                    'variant' => $variantName,
                    'volume' => $variantVolume,
                    'size' => $variantSize,
                    'categoria' => $categoria,
                    'images' => $images,
                    'price' => $variant['price'] ?? null,
                    'compare_price' => $variant['compare_at_price'] ?? null,
                    'stock' => $variant['stock'] ?? null,
                    'handle' => $product['handle'] ?? '',
                    'weight' => $variant['weight'] ?? null,
                    'dimensions' => [
                        'height' => $variant['height'] ?? null,
                        'width' => $variant['width'] ?? null,
                        'length' => $variant['length'] ?? null
                    ]
                ];
            }
        } else {
            // Se não houver variantes, buscar no nome/descrição do produto
            $mainVolume = null;
            foreach ($volumePatterns as $pattern) {
                if (preg_match($pattern, $productName, $matches)) {
                    $mainVolume = floatval(str_replace(',', '.', $matches[1]));
                    if ($mainVolume >= 10 && $mainVolume <= 200) {
                        break;
                    } else {
                        $mainVolume = null;
                    }
                }
            }
            if (!$mainVolume && isset($product['description'])) {
                $desc = $product['description'];
                if (is_array($desc)) $desc = reset($desc);
                if (is_string($desc)) {
                    foreach ($volumePatterns as $pattern) {
                        if (preg_match($pattern, $desc, $matches)) {
                            $mainVolume = floatval(str_replace(',', '.', $matches[1]));
                            if ($mainVolume >= 10 && $mainVolume <= 200) {
                                break;
                            } else {
                                $mainVolume = null;
                            }
                        }
                    }
                }
            }
            $processed[] = [
                'id' => $product['id'],
                'name' => $productName,
                'variant' => null,
                'volume' => $mainVolume,
                'size' => null,
                'categoria' => $categoria,
                'images' => $images,
                'price' => $product['price'] ?? null,
                'compare_price' => $product['compare_at_price'] ?? null,
                'stock' => $product['stock'] ?? null,
                'handle' => $product['handle'] ?? '',
                'weight' => $product['weight'] ?? null,
                'dimensions' => [
                    'height' => $product['height'] ?? null,
                    'width' => $product['width'] ?? null,
                    'length' => $product['length'] ?? null
                ]
            ];
        }
    }
    $total = count($processed);
    $comVolume = count(array_filter($processed, function($p) { return $p['volume'] && $p['volume'] > 0; }));
    $semVolume = $total - $comVolume;
    debugLog("Processed $total products: $comVolume with volume, $semVolume without volume");
    return $processed;
}

// Atualização de cache: só atualiza se passou 1 dia desde a última atualização
function shouldUpdateCache($cache_file, $cache_duration = 86400) {
    if (!file_exists($cache_file)) return true;
    $lastUpdate = filemtime($cache_file);
    return (time() - $lastUpdate) > $cache_duration;
}

// Verificar cache
if (file_exists($cache_file) && !shouldUpdateCache($cache_file)) {
    $cached_data = file_get_contents($cache_file);
    $data = json_decode($cached_data, true);
    if ($data && !isset($data['error'])) {
        debugLog("Serving from cache");
        echo json_encode($data);
        exit;
    }
}

// Buscar dados da API
debugLog("Fetching from API");
$api_response = fetchProducts($store_id, $token);

if (isset($api_response['error'])) {
    http_response_code(500);
    echo json_encode(['error' => $api_response['error']]);
    exit;
}

// Processar produtos
$processed_products = processProducts($api_response);

// Filtrar por volume e categoria se especificado via POST
$input = json_decode(file_get_contents('php://input'), true);
if ($input && (isset($input['volume_min']) || isset($input['volume_max']) || isset($input['categoria']))) {
    $volume_min = $input['volume_min'] ?? 0;
    $volume_max = $input['volume_max'] ?? 999;
    $categoria = $input['categoria'] ?? null;
    
    debugLog("Filtrando: volume $volume_min-$volume_max, categoria: $categoria");
    
    $filtered = array_filter($processed_products, function($product) use ($volume_min, $volume_max, $categoria) {
        $volumeMatch = $product['volume'] && $product['volume'] >= $volume_min && $product['volume'] <= $volume_max;
        $categoriaMatch = !$categoria || $product['categoria'] === $categoria || $categoria === 'todas';
        return $volumeMatch && $categoriaMatch;
    });
    
    $processed_products = array_values($filtered);
    debugLog("Filtrados: " . count($processed_products) . " produtos");
}

// Salvar no cache
try {
    file_put_contents($cache_file, json_encode($processed_products));
    debugLog("Cache updated");
} catch (Exception $e) {
    logError("Erro ao salvar cache: " . $e->getMessage());
}

// Retornar resposta
echo json_encode(['success' => true, 'products' => $processed_products]);
?>
