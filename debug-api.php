<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Debug API - Board's Point</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .debug-section { margin: 20px 0; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .warning { background: #fff3cd; color: #856404; }
        .info { background: #d1ecf1; color: #0c5460; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; max-height: 400px; overflow-y: auto; }
        .test-btn { padding: 10px 20px; margin: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .test-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔍 Debug API - Board's Point</h1>
    
    <div class="debug-section info">
        <h2>📋 Informações do Sistema</h2>
        <p><strong>Domínio:</strong> <?php echo $_SERVER['HTTP_HOST'] ?? 'N/A'; ?></p>
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>cURL:</strong> <?php echo extension_loaded('curl') ? '✅ Habilitado' : '❌ Não encontrado'; ?></p>
        <p><strong>JSON:</strong> <?php echo extension_loaded('json') ? '✅ Habilitado' : '❌ Não encontrado'; ?></p>
    </div>

    <div class="debug-section">
        <h2>🧪 Teste 1: API Direta</h2>
        <button class="test-btn" onclick="testDirectAPI()">Testar API Direta</button>
        <div id="direct-api-result"></div>
    </div>

    <div class="debug-section">
        <h2>🧪 Teste 2: Proxy Local</h2>
        <button class="test-btn" onclick="testProxyAPI()">Testar Proxy Local</button>
        <div id="proxy-api-result"></div>
    </div>

    <div class="debug-section">
        <h2>🧪 Teste 3: Filtro de Produtos</h2>
        <button class="test-btn" onclick="testProductFilter()">Testar Filtro</button>
        <div id="filter-result"></div>
    </div>

    <div class="debug-section">
        <h2>🧪 Teste 4: Busca com Volume</h2>
        <input type="number" id="testVolume" placeholder="Volume (ex: 35)" value="35" style="padding: 8px; margin: 10px;">
        <button class="test-btn" onclick="testVolumeSearch()">Buscar por Volume</button>
        <div id="volume-result"></div>
    </div>

    <script>
        async function testDirectAPI() {
            const resultDiv = document.getElementById('direct-api-result');
            resultDiv.innerHTML = '<p>Testando...</p>';
            
            try {
                const response = await fetch('nuvemshop-proxy.php');
                const data = await response.json();
                
                if (data.error) {
                    resultDiv.innerHTML = `
                        <div class="error">
                            <h3>❌ Erro na API</h3>
                            <p><strong>Erro:</strong> ${data.error}</p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ API funcionando!</h3>
                            <p><strong>Total de produtos:</strong> ${data.length}</p>
                            <p><strong>Primeiros 3 produtos:</strong></p>
                            <pre>${JSON.stringify(data.slice(0, 3), null, 2)}</pre>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="error">
                        <h3>❌ Erro de conexão</h3>
                        <p><strong>Erro:</strong> ${error.message}</p>
                    </div>
                `;
            }
        }

        async function testProxyAPI() {
            const resultDiv = document.getElementById('proxy-api-result');
            resultDiv.innerHTML = '<p>Testando proxy...</p>';
            
            try {
                const response = await fetch('nuvemshop-proxy.php');
                const data = await response.json();
                
                if (data.error) {
                    resultDiv.innerHTML = `
                        <div class="error">
                            <h3>❌ Erro no Proxy</h3>
                            <p><strong>Erro:</strong> ${data.error}</p>
                        </div>
                    `;
                } else {
                    // Analisar produtos
                    const produtosComVolume = data.filter(p => p.volume && p.volume > 0);
                    const produtosSemVolume = data.filter(p => !p.volume || p.volume <= 0);
                    
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ Proxy funcionando!</h3>
                            <p><strong>Total de produtos:</strong> ${data.length}</p>
                            <p><strong>Produtos com volume:</strong> ${produtosComVolume.length}</p>
                            <p><strong>Produtos sem volume:</strong> ${produtosSemVolume.length}</p>
                            <p><strong>Exemplo de produto com volume:</strong></p>
                            <pre>${JSON.stringify(produtosComVolume.slice(0, 2), null, 2)}</pre>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="error">
                        <h3>❌ Erro no Proxy</h3>
                        <p><strong>Erro:</strong> ${error.message}</p>
                    </div>
                `;
            }
        }

        async function testProductFilter() {
            const resultDiv = document.getElementById('filter-result');
            resultDiv.innerHTML = '<p>Testando filtro...</p>';
            
            try {
                const response = await fetch('nuvemshop-proxy.php');
                const data = await response.json();
                
                if (data.error) {
                    resultDiv.innerHTML = `<div class="error"><h3>❌ Erro na API</h3><p>${data.error}</p></div>`;
                    return;
                }
                
                // Testar diferentes filtros
                const filtros = [
                    { nome: 'Todos os produtos', filtro: () => data },
                    { nome: 'Com volume', filtro: () => data.filter(p => p.volume && p.volume > 0) },
                    { nome: 'Shortboard', filtro: () => data.filter(p => p.categoria === 'shortboard') },
                    { nome: 'Com descrição', filtro: () => data.filter(p => p.description && p.description.length > 0) },
                    { nome: 'Com imagens', filtro: () => data.filter(p => p.images && p.images.length > 0) }
                ];
                
                let resultado = '<div class="success"><h3>📊 Resultados dos Filtros</h3>';
                
                filtros.forEach(filtro => {
                    const produtos = filtro.filtro();
                    resultado += `<p><strong>${filtro.nome}:</strong> ${produtos.length} produtos</p>`;
                });
                
                resultado += '</div>';
                resultDiv.innerHTML = resultado;
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="error">
                        <h3>❌ Erro no Filtro</h3>
                        <p><strong>Erro:</strong> ${error.message}</p>
                    </div>
                `;
            }
        }

        async function testVolumeSearch() {
            const volume = parseFloat(document.getElementById('testVolume').value);
            const resultDiv = document.getElementById('volume-result');
            resultDiv.innerHTML = '<p>Buscando...</p>';
            
            try {
                const response = await fetch('nuvemshop-proxy.php');
                const data = await response.json();
                
                if (data.error) {
                    resultDiv.innerHTML = `<div class="error"><h3>❌ Erro na API</h3><p>${data.error}</p></div>`;
                    return;
                }
                
                // Buscar produtos por volume
                const recomendadas = data.filter(prod => {
                    if (prod.volume && prod.volume > 0) {
                        return Math.abs(prod.volume - volume) <= 5;
                    }
                    
                    // Fallback: buscar no texto
                    const texto = (prod.name || '') + ' ' + (prod.description || '');
                    const match = texto.match(/(\d{1,3}(?:[\.,]\d{1,2})?) ?l/i);
                    
                    if (match) {
                        const vol = parseFloat(match[1].replace(',', '.'));
                        return Math.abs(vol - volume) <= 5;
                    }
                    
                    return false;
                });
                
                if (recomendadas.length === 0) {
                    resultDiv.innerHTML = `
                        <div class="warning">
                            <h3>⚠️ Nenhuma prancha encontrada</h3>
                            <p>Volume buscado: ${volume}L</p>
                            <p>Total de produtos na loja: ${data.length}</p>
                            <p>Produtos com volume: ${data.filter(p => p.volume && p.volume > 0).length}</p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ ${recomendadas.length} pranchas encontradas</h3>
                            <p>Volume buscado: ${volume}L</p>
                            <p>Pranchas encontradas:</p>
                            <pre>${JSON.stringify(recomendadas.slice(0, 3), null, 2)}</pre>
                        </div>
                    `;
                }
                
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="error">
                        <h3>❌ Erro na Busca</h3>
                        <p><strong>Erro:</strong> ${error.message}</p>
                    </div>
                `;
            }
        }
    </script>
</body>
</html> 