<?php
// Importa produtos do CSV para banco local JSON
$csv = $argv[1] ?? 'tiendanube-2446542-17521119982065355178.csv';
$outfile = 'produtos-bp.json';

if (!file_exists($csv)) {
    echo "Arquivo CSV não encontrado: $csv\n";
    exit(1);
}

$handle = fopen($csv, 'r');
$header = fgetcsv($handle, 0, ';');
$produtos = [];
while (($row = fgetcsv($handle, 0, ';')) !== false) {
    $data = array_combine($header, $row);
    // Filtros: estoque > 0, Exibir na loja = SIM
    $estoque = (int)($data['Estoque'] ?? 0);
    $exibir = strtoupper(trim($data['Exibir na loja'] ?? ''));
    if ($estoque <= 0 || $exibir !== 'SIM') continue;
    // Marca
    $marca = $data['Marca'] ?? '';
    // Volume
    $volume = null;
    if (!empty($data['Volume'])) {
        if (preg_match('/(\d{1,3}(?:[\.,]\d{1,2})?)/', $data['Volume'], $m)) {
            $volume = floatval(str_replace(',', '.', $m[1]));
        }
    }
    // Preço
    $preco = floatval(str_replace([',','.'], ['','.'], $data['Preço'] ?? '0'))/100;
    // Preço promocional
    $preco_promo = floatval(str_replace([',','.'], ['','.'], $data['Preço promocional'] ?? '0'))/100;
    // Categoria
    $categoria = $data['Categorias'] ?? '';
    // Nome
    $nome = $data['Nome'] ?? '';
    // Handle
    $handle = $data['Identificador URL'] ?? '';
    $produtos[] = [
        'name' => $nome,
        'volume' => $volume,
        'categoria' => $categoria,
        'brand' => $marca,
        'price' => $preco,
        'price_promo' => $preco_promo,
        'stock' => $estoque,
        'handle' => $handle
    ];
}
fclose($handle);
file_put_contents($outfile, json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Banco local atualizado com ".count($produtos)." produtos do CSV.\n"; 