<<<<<<< HEAD
# BP-Buscador
=======
# BP-Buscador - Board's Point Surfboard Volume Calculator

Este projeto contém:
- `surfboard-volume-calculator.html`: Calculadora de volume de prancha com recomendações automáticas do catálogo da loja.
- `nuvemshop-proxy.php`: Proxy seguro em PHP para buscar produtos da API da NuvemShop sem expor o token.

## Como publicar na Hostinger (bp.ocoworks.com)

1. **Faça upload dos arquivos**
   - Envie `surfboard-volume-calculator.html` e `nuvemshop-proxy.php` para a raiz do seu domínio (pasta `public_html` ou equivalente) usando FTP ou o Gerenciador de Arquivos do painel da Hostinger.

2. **Acesse a calculadora**
   - No navegador, acesse: `https://bp.ocoworks.com/surfboard-volume-calculator.html`

3. **Funcionamento**
   - O HTML faz requisições para `nuvemshop-proxy.php`, que busca os produtos da loja Board's Point na NuvemShop e retorna as recomendações de pranchas conforme o volume calculado.

4. **Segurança**
   - O token da API da NuvemShop está protegido no backend PHP e não é exposto ao navegador.

## Observações
- Se quiser incorporar a calculadora em outra página, use um iframe ou copie o conteúdo do HTML.
- Para personalizar o layout, edite o CSS no arquivo HTML.
- Para alterar o token ou store_id, edite o arquivo `nuvemshop-proxy.php`.

---

Dúvidas? Entre em contato com o desenvolvedor responsável pelo projeto.
>>>>>>> 9402095 (Initial commit for BP Buscador project)
