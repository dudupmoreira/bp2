<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Ajuste para o domínio do seu site em produção

$url = "https://api.tiendanube.com/v1/2446542/products?fields=id,name,variants,images,description";
$token = "96685dd9b9c0c82b5d613b3d5dd466f1d6418083";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Accept: application/json",
    "User-Agent: NuvemShop-API-Client"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpcode);
echo $response;
