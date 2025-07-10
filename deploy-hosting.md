# 🚀 Deploy na Hostinger - Guia Completo

## 📋 Pré-requisitos
- Conta na Hostinger
- Acesso ao cPanel ou File Manager
- Token da API da NuvemShop

## 📁 Estrutura de Arquivos

```
public_html/
├── surfboard-volume-calculator.html
├── nuvemshop-proxy.php
├── test.html
├── .htaccess
└── cache_products.json (será criado automaticamente)
```

## 🔧 Passo a Passo

### 1. **Acesse o cPanel da Hostinger**
- Faça login na sua conta Hostinger
- Acesse o cPanel
- Vá em "File Manager"

### 2. **Navegue para public_html**
- Abra a pasta `public_html`
- Esta é a raiz do seu domínio

### 3. **Faça Upload dos Arquivos**
- Faça upload de todos os arquivos para `public_html`
- Mantenha a estrutura de pastas

### 4. **Configure as Permissões**
- `nuvemshop-proxy.php`: 644
- `surfboard-volume-calculator.html`: 644
- `test.html`: 644
- `cache_products.json`: 666 (se existir)

### 5. **Configure o Token da API**
Edite `nuvemshop-proxy.php` e atualize:
```php
$store_id = "SEU_STORE_ID";
$token = "SEU_ACCESS_TOKEN";
```

### 6. **Teste a Aplicação**
- Acesse: `https://seudominio.com/surfboard-volume-calculator.html`
- Teste: `https://seudominio.com/test.html`

## ⚙️ Configurações Específicas

### .htaccess (Opcional)
Crie um arquivo `.htaccess` na raiz:
```apache
# Habilitar CORS
Header always set Access-Control-Allow-Origin "*"
Header always set Access-Control-Allow-Methods "GET, POST, OPTIONS"
Header always set Access-Control-Allow-Headers "Content-Type"

# Cache para arquivos estáticos
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Compressão GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

## 🔍 Troubleshooting

### Problema: Erro 500
**Solução:**
1. Verifique se o PHP está habilitado
2. Verifique as permissões dos arquivos
3. Verifique o token da API

### Problema: CORS Error
**Solução:**
1. Adicione o arquivo `.htaccess`
2. Verifique se o domínio está correto

### Problema: Cache não funciona
**Solução:**
1. Verifique permissões da pasta
2. Verifique se o PHP pode escrever arquivos

### Problema: API não responde
**Solução:**
1. Verifique se o cURL está habilitado
2. Verifique o token da API
3. Teste com `test.html`

## 📞 Suporte Hostinger

Se precisar de ajuda:
- **Chat ao vivo**: Disponível no painel
- **Base de conhecimento**: help.hostinger.com
- **Email**: support@hostinger.com

## 🎯 URLs Finais

Após o deploy:
- **Calculadora**: `https://seudominio.com/surfboard-volume-calculator.html`
- **Teste**: `https://seudominio.com/test.html`
- **API**: `https://seudominio.com/nuvemshop-proxy.php`

## 💡 Dicas Importantes

1. **Sempre teste primeiro** com `test.html`
2. **Mantenha backups** dos arquivos
3. **Monitore os logs** de erro
4. **Use HTTPS** para produção
5. **Configure SSL** no painel da Hostinger

---
**Pronto para deploy! 🚀** 