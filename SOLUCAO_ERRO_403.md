# 🔧 Solução para Erro 403 - Forbidden

## 🚨 Problema Identificado
Erro 403 indica que o servidor web não tem permissão para acessar os arquivos.

## 🔧 Soluções

### **Solução 1: Verificar e Corrigir Permissões**
```bash
# Verificar permissões atuais
ls -la /home/bp2.ocoworks.com/public_html/

# Corrigir permissões do diretório
chmod 755 /home/bp2.ocoworks.com/public_html/

# Corrigir permissões dos arquivos
chmod 644 /home/bp2.ocoworks.com/public_html/*.html
chmod 644 /home/bp2.ocoworks.com/public_html/*.php
chmod 644 /home/bp2.ocoworks.com/public_html/*.md

# Corrigir permissões dos logs
chmod 666 /home/bp2.ocoworks.com/public_html/debug_vps.log
chmod 666 /home/bp2.ocoworks.com/public_html/cache_products_vps.json

# Definir proprietário correto
chown -R bpoco5558:bpoco5558 /home/bp2.ocoworks.com/public_html/
```

### **Solução 2: Verificar Seletor de Usuário**
```bash
# Verificar qual usuário o servidor web usa
ps aux | grep apache
ps aux | grep nginx

# Verificar se o usuário tem acesso
ls -la /home/bp2.ocoworks.com/
```

### **Solução 3: Verificar Configuração do CyberPanel**
1. Acesse o CyberPanel
2. Vá para **"Websites"** → **"List Websites"**
3. Clique em **"Manage"** em `bp2.ocoworks.com`
4. Vá para **"File Manager"**
5. Verifique se os arquivos estão lá
6. Se não estiverem, recrie o website

### **Solução 4: Recriar Website (se necessário)**
```bash
# Fazer backup primeiro
cp -r /home/bp2.ocoworks.com/public_html/ /tmp/backup_bp2/

# Recriar diretório
rm -rf /home/bp2.ocoworks.com/public_html/
mkdir /home/bp2.ocoworks.com/public_html/

# Clonar novamente
cd /home/bp2.ocoworks.com/public_html/
git clone https://github.com/dudupmoreira/bp2.git .

# Configurar permissões
chmod 755 /home/bp2.ocoworks.com/public_html/
chmod 644 *.html *.php *.md
touch debug_vps.log cache_products_vps.json
chmod 666 debug_vps.log cache_products_vps.json
chown -R bpoco5558:bpoco5558 /home/bp2.ocoworks.com/public_html/
```

### **Solução 5: Verificar Logs de Erro**
```bash
# Verificar logs do Apache/Nginx
tail -f /var/log/httpd/error_log
# ou
tail -f /var/log/nginx/error.log

# Verificar logs do CyberPanel
tail -f /usr/local/lscp/logs/error.log
```

## 🧪 Testes de Validação

### **Teste 1: Verificar Arquivos**
```bash
# Verificar se os arquivos existem
ls -la /home/bp2.ocoworks.com/public_html/

# Verificar se o arquivo específico existe
ls -la /home/bp2.ocoworks.com/public_html/test-vps.html
```

### **Teste 2: Verificar Permissões**
```bash
# Verificar permissões do diretório
ls -ld /home/bp2.ocoworks.com/public_html/

# Verificar permissões dos arquivos
ls -la /home/bp2.ocoworks.com/public_html/test-vps.html
ls -la /home/bp2.ocoworks.com/public_html/surfboard-volume-calculator.html
```

### **Teste 3: Testar Acesso Local**
```bash
# Testar se consegue ler o arquivo
cat /home/bp2.ocoworks.com/public_html/test-vps.html

# Testar se consegue acessar via curl
curl -I http://localhost/test-vps.html
```

## 🔍 Diagnóstico

### **Comandos de Diagnóstico**
```bash
# Verificar estrutura completa
tree /home/bp2.ocoworks.com/public_html/

# Verificar proprietário
stat /home/bp2.ocoworks.com/public_html/

# Verificar seletor de usuário
id bpoco5558
groups bpoco5558

# Verificar configuração do servidor web
apachectl -S
# ou
nginx -t
```

### **Verificar Configuração do CyberPanel**
1. Acesse o CyberPanel
2. Vá para **"Websites"** → **"List Websites"**
3. Verifique se `bp2.ocoworks.com` está listado
4. Verifique se o status está "Active"
5. Verifique se o SSL está configurado

## 📋 Checklist de Correção

- ✅ [ ] Permissões do diretório (755)
- ✅ [ ] Permissões dos arquivos (644)
- ✅ [ ] Permissões dos logs (666)
- ✅ [ ] Proprietário correto (bpoco5558)
- ✅ [ ] Arquivos existem
- ✅ [ ] Servidor web tem acesso
- ✅ [ ] Website ativo no CyberPanel
- ✅ [ ] SSL configurado

## 🚀 Comandos Rápidos para Executar

```bash
# Sequência completa de correção
cd /home/bp2.ocoworks.com/public_html/
chmod 755 .
chmod 644 *.html *.php *.md
touch debug_vps.log cache_products_vps.json
chmod 666 debug_vps.log cache_products_vps.json
chown -R bpoco5558:bpoco5558 .
ls -la
```

---

**Status:** 🔧 Solução para erro 403
**Última Atualização:** $(date) 