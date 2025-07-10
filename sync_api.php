<?php
// Sincroniza banco local de produtos via API
$proxy_url = 'http://localhost/nuvemshop-proxy-vps.php';
$outfile = 'produtos-bp.json';

$ch = curl_init($proxy_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode === 200 && $response) {
    $data = json_decode($response, true);
    if (isset($data['success']) && $data['success'] && isset($data['products']) && count($data['products']) > 0) {
        file_put_contents($outfile, json_encode($data['products'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "Banco local atualizado com ".count($data['products'])." produtos.";
    } else {
        echo "Nenhum produto encontrado ou resposta inesperada.";
    }
} else {
    echo "Erro ao consultar proxy/API. HTTP $httpcode";
} 