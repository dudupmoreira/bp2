# 🚀 Deploy no CyberPanel - Calculadora de Volume Board's Point

## 📋 Resumo do Projeto

**Repositório:** https://github.com/dudupmoreira/bp2  
**Domínio:** bp2.ocoworks.com  
**Tecnologia:** PHP + JavaScript + HTML/CSS  
**Proxy:** nuvemshop-proxy-vps.php  

## 🎯 Objetivo
Configurar a calculadora de volume de pranchas de surf no CyberPanel com todas as melhorias implementadas para VPS.

## 📋 Pré-requisitos

### 1. **CyberPanel Configurado**
- ✅ CyberPanel instalado e funcionando
- ✅ Domínio bp2.ocoworks.com configurado
- ✅ SSL/HTTPS ativo
- ✅ PHP 7.4+ instalado

### 2. **Acesso SSH**
- ✅ Acesso root ou sudo
- ✅ Git instalado
- ✅ Permissões de escrita

## 🚀 Passo a Passo - CyberPanel

### **Passo 1: Acessar CyberPanel**
1. Abra o navegador
2. Acesse: `https://seu-servidor:8090`
3. Faça login com suas credenciais
4. Vá para **"Websites"** → **"List Websites"**

### **Passo 2: Criar/Configurar Website**
1. **Se o domínio já existe:**
   - Clique em **"Manage"** ao lado de `bp2.ocoworks.com`
   - Vá para **"File Manager"**

2. **Se precisa criar:**
   - Clique em **"Create Website"**
   - **Domain:** `bp2.ocoworks.com`
   - **Email:** [seu-email@dominio.com]
   - **Package:** Default
   - Clique em **"Create Website"**

### **Passo 3: Acessar File Manager**
1. No website `bp2.ocoworks.com`
2. Clique em **"File Manager"**
3. Navegue até `/home/bp2.ocoworks.com/public_html/`

### **Passo 4: Limpar Diretório (se necessário)**
1. Selecione todos os arquivos existentes
2. Clique em **"Delete"** para limpar
3. Confirme a exclusão

### **Passo 5: Clonar do GitHub**
1. Abra o **Terminal** do CyberPanel
2. Execute os comandos:

```bash
# Navegar para o diretório do website
cd /home/bp2.ocoworks.com/public_html/

# Clonar o repositório
git clone https://github.com/dudupmoreira/bp2.git .

# Verificar se os arquivos foram baixados
ls -la
```

### **Passo 6: Configurar Permissões**
1. No terminal, execute:

```bash
# Definir permissões corretas
chmod 644 *.html *.php
chmod 644 *.md

# Criar arquivos de log e cache
touch debug_vps.log
touch cache_products_vps.json

# Definir permissões de escrita para logs
chmod 666 debug_vps.log
chmod 666 cache_products_vps.json

# Definir proprietário correto
chown -R bp2.ocoworks.com:bp2.ocoworks.com /home/bp2.ocoworks.com/public_html/
```

### **Passo 7: Configurar PHP**
1. No CyberPanel, vá para **"Websites"** → **"List Websites"**
2. Clique em **"Manage"** em `bp2.ocoworks.com`
3. Vá para **"PHP Manager"**
4. **Versão PHP:** Selecione **7.4** ou superior
5. Clique em **"Save"**

### **Passo 8: Configurar SSL (se necessário)**
1. Vá para **"SSL"** no website
2. Se não estiver ativo, clique em **"Issue SSL"**
3. Selecione **"Let's Encrypt"**
4. Clique em **"Issue"**

### **Passo 9: Testar Conectividade**
1. Abra o navegador
2. Acesse: `https://bp2.ocoworks.com/test-vps.html`
3. Execute todos os testes
4. Verifique se não há erros

### **Passo 10: Verificar Logs**
1. No terminal, execute:

```bash
# Verificar logs
tail -f /home/bp2.ocoworks.com/public_html/debug_vps.log

# Verificar cache
ls -la /home/bp2.ocoworks.com/public_html/cache_products_vps.json
```

## 🧪 Testes de Validação

### **Teste 1: Conectividade Básica**
- ✅ Acesse: `https://bp2.ocoworks.com/test-vps.html`
- ✅ Execute "Teste 1: Conectividade Básica"
- ✅ Deve retornar sucesso com tempo < 2s

### **Teste 2: Busca de Produtos**
- ✅ Execute "Teste 2: Busca com Volume 35L"
- ✅ Deve encontrar produtos
- ✅ Cache deve funcionar

### **Teste 3: Performance**
- ✅ Execute "Teste 6: Performance e Cache"
- ✅ Primeira requisição < 3s
- ✅ Requisições subsequentes < 500ms

### **Teste 4: Calculadora Principal**
- ✅ Acesse: `https://bp2.ocoworks.com/surfboard-volume-calculator.html`
- ✅ Teste com diferentes volumes
- ✅ Deve mostrar recomendações

## 🔧 Configurações Avançadas

### **Configurar Cron Job (Opcional)**
Para limpar logs antigos automaticamente:

```bash
# Editar crontab
crontab -e

# Adicionar linha (limpa logs a cada 7 dias)
0 2 * * 0 find /home/bp2.ocoworks.com/public_html/debug_vps.log -mtime +7 -delete
```

### **Configurar Backup (Recomendado)**
1. No CyberPanel, vá para **"Backup"**
2. Configure backup automático do website
3. Frequência: Diário
4. Retenção: 7 dias

### **Monitoramento de Performance**
1. Vá para **"Monitor"** no CyberPanel
2. Configure alertas para:
   - CPU > 80%
   - Memória > 80%
   - Disco > 90%

## 🛠️ Troubleshooting

### **Problema: Erro 500**
**Solução:**
```bash
# Verificar logs de erro
tail -f /home/bp2.ocoworks.com/logs/error.log

# Verificar permissões
ls -la /home/bp2.ocoworks.com/public_html/

# Verificar PHP
php -v
```

### **Problema: Cache não funciona**
**Solução:**
```bash
# Verificar permissões do cache
ls -la /home/bp2.ocoworks.com/public_html/cache_products_vps.json

# Recriar arquivo de cache
rm cache_products_vps.json
touch cache_products_vps.json
chmod 666 cache_products_vps.json
chown bp2.ocoworks.com:bp2.ocoworks.com cache_products_vps.json
```

### **Problema: SSL não funciona**
**Solução:**
1. No CyberPanel, vá para **"SSL"**
2. Clique em **"Issue SSL"**
3. Selecione **"Let's Encrypt"**
4. Aguarde a instalação

### **Problema: Performance lenta**
**Solução:**
1. Verificar uso de recursos no CyberPanel
2. Otimizar PHP (aumentar memory_limit)
3. Verificar se não há outros sites consumindo recursos

## 📊 Monitoramento

### **Logs Importantes**
- `/home/bp2.ocoworks.com/public_html/debug_vps.log` - Log da aplicação
- `/home/bp2.ocoworks.com/logs/error.log` - Log de erros do servidor
- `/home/bp2.ocoworks.com/public_html/cache_products_vps.json` - Cache

### **Métricas a Monitorar**
- Tempo de resposta da API
- Uso de cache vs API
- Número de produtos encontrados
- Taxa de erro das requisições

### **Alertas Recomendados**
- Tempo de resposta > 5s
- Taxa de erro > 5%
- Cache não sendo usado
- Logs muito grandes

## 🔄 Atualizações Futuras

### **Para atualizar o código:**
```bash
cd /home/bp2.ocoworks.com/public_html/
git pull origin main
chmod 644 *.html *.php
chmod 666 debug_vps.log cache_products_vps.json
```

### **Para fazer backup antes de atualizar:**
```bash
cp -r /home/bp2.ocoworks.com/public_html/ /home/bp2.ocoworks.com/public_html_backup_$(date +%Y%m%d)
```

## 📞 Suporte

### **Informações do Deploy**
- **URL:** https://bp2.ocoworks.com
- **Repositório:** https://github.com/dudupmoreira/bp2
- **Proxy:** /nuvemshop-proxy-vps.php
- **Teste:** /test-vps.html
- **Calculadora:** /surfboard-volume-calculator.html

### **Contatos**
- **Desenvolvedor:** [Seu contato]
- **Hosting:** [Contato do VPS]
- **Logs:** debug_vps.log

---

**Status:** ✅ Pronto para deploy
**Data:** $(date)
**Versão:** 1.0 - Deploy CyberPanel 