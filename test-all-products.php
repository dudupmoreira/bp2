<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Todos os Produtos - Board's Point</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .product { 
            border: 1px solid #ddd; 
            margin: 10px 0; 
            padding: 15px; 
            border-radius: 8px;
            background: #f9f9f9;
        }
        .product h3 { margin: 0 0 10px 0; color: #333; }
        .product-info { font-size: 0.9em; color: #666; }
        .volume { font-weight: bold; color: #007bff; }
        .no-volume { color: #dc3545; }
        .stats { 
            background: #e9ecef; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px;
        }
        .search { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px;
        }
        input, select { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔍 Todos os Produtos - Board's Point</h1>
    
    <div class="search">
        <h3>🔍 Busca Avançada</h3>
        <input type="number" id="searchVolume" placeholder="Volume (ex: 35)" style="width: 150px;">
        <select id="searchCategory">
            <option value="">Todas as categorias</option>
            <option value="shortboard">Shortboard</option>
            <option value="longboard">Longboard</option>
            <option value="funboard">Funboard</option>
            <option value="fish">Fish</option>
            <option value="retro">Retro</option>
            <option value="stepup">Step Up</option>
            <option value="bigwave">Big Wave</option>
            <option value="grom">Grom</option>
        </select>
        <button onclick="filterProducts()">Filtrar</button>
        <button onclick="showAllProducts()">Mostrar Todos</button>
    </div>
    
    <div class="stats" id="stats"></div>
    <div id="products"></div>

    <script>
        let allProducts = [];
        
        async function loadProducts() {
            try {
                const response = await fetch('nuvemshop-proxy.php');
                const data = await response.json();
                
                if (data.error) {
                    document.getElementById('products').innerHTML = `<div style="color: red;">Erro: ${data.error}</div>`;
                    return;
                }
                
                allProducts = data;
                showAllProducts();
            } catch (error) {
                document.getElementById('products').innerHTML = `<div style="color: red;">Erro de conexão: ${error.message}</div>`;
            }
        }
        
        function showAllProducts() {
            displayProducts(allProducts);
        }
        
        function filterProducts() {
            const volume = parseFloat(document.getElementById('searchVolume').value);
            const category = document.getElementById('searchCategory').value;
            
            let filtered = allProducts;
            
            if (volume && !isNaN(volume)) {
                filtered = filtered.filter(prod => {
                    if (prod.volume && prod.volume > 0) {
                        return Math.abs(prod.volume - volume) <= 5;
                    }
                    
                    const texto = (prod.name || '') + ' ' + (prod.description || '');
                    const match = texto.match(/(\d{1,3}(?:[\.,]\d{1,2})?) ?l/i);
                    
                    if (match) {
                        const vol = parseFloat(match[1].replace(',', '.'));
                        return Math.abs(vol - volume) <= 5;
                    }
                    
                    return false;
                });
            }
            
            if (category) {
                filtered = filtered.filter(prod => prod.categoria === category);
            }
            
            displayProducts(filtered);
        }
        
        function displayProducts(products) {
            const statsDiv = document.getElementById('stats');
            const productsDiv = document.getElementById('products');
            
            // Estatísticas
            const total = products.length;
            const comVolume = products.filter(p => p.volume && p.volume > 0).length;
            const semVolume = total - comVolume;
            
            statsDiv.innerHTML = `
                <h3>📊 Estatísticas</h3>
                <p><strong>Total de produtos:</strong> ${total}</p>
                <p><strong>Com volume:</strong> ${comVolume}</p>
                <p><strong>Sem volume:</strong> ${semVolume}</p>
                <p><strong>Porcentagem com volume:</strong> ${total > 0 ? Math.round((comVolume / total) * 100) : 0}%</p>
            `;
            
            // Produtos
            if (products.length === 0) {
                productsDiv.innerHTML = '<div style="color: orange; padding: 20px;">Nenhum produto encontrado com os filtros aplicados.</div>';
                return;
            }
            
            let html = '<h3>📦 Produtos Encontrados</h3>';
            
            products.forEach(prod => {
                const volumeText = prod.volume && prod.volume > 0 
                    ? `<span class="volume">${prod.volume}L</span>` 
                    : '<span class="no-volume">Sem volume</span>';
                
                html += `
                    <div class="product">
                        <h3>${prod.name}</h3>
                        <div class="product-info">
                            <p><strong>ID:</strong> ${prod.id}</p>
                            <p><strong>Categoria:</strong> ${prod.categoria}</p>
                            <p><strong>Volume:</strong> ${volumeText}</p>
                            <p><strong>Preço:</strong> ${prod.price ? 'R$ ' + prod.price : 'N/A'}</p>
                            <p><strong>Estoque:</strong> ${prod.stock || 0}</p>
                            <p><strong>Descrição:</strong> ${prod.description ? prod.description.substring(0, 100) + '...' : 'N/A'}</p>
                            ${prod.images && prod.images.length > 0 ? `<p><strong>Imagens:</strong> ${prod.images.length}</p>` : ''}
                        </div>
                    </div>
                `;
            });
            
            productsDiv.innerHTML = html;
        }
        
        // Carregar produtos ao iniciar
        loadProducts();
    </script>
</body>
</html> 