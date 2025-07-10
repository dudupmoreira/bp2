# 🚀 Deploy no CyberPanel - CORRIGIDO

## 🔧 Comandos Corrigidos para CyberPanel

### **Passo 1: Verificar Usuário Correto**
```bash
# Verificar qual usuário existe
ls -la /home/

# Verificar usuário do website
ps aux | grep apache
ps aux | grep nginx

# Verificar proprietário do diretório
ls -la /home/bp2.ocoworks.com/
```

### **Passo 2: Comandos Corrigidos**

#### **Opção A - Se o usuário for 'www-data':**
```bash
cd /home/bp2.ocoworks.com/public_html/
git clone https://github.com/dudupmoreira/bp2.git .

# Permissões corretas
chmod 644 *.html *.php
chmod 644 *.md

# Criar arquivos de log e cache
touch debug_vps.log
touch cache_products_vps.json

# Permissões de escrita
chmod 666 debug_vps.log
chmod 666 cache_products_vps.json

# Definir proprietário (www-data é comum no CyberPanel)
chown -R www-data:www-data /home/bp2.ocoworks.com/public_html/
```

#### **Opção B - Se o usuário for o nome do domínio:**
```bash
cd /home/bp2.ocoworks.com/public_html/
git clone https://github.com/dudupmoreira/bp2.git .

# Permissões corretas
chmod 644 *.html *.php
chmod 644 *.md

# Criar arquivos de log e cache
touch debug_vps.log
touch cache_products_vps.json

# Permissões de escrita
chmod 666 debug_vps.log
chmod 666 cache_products_vps.json

# Definir proprietário (sem pontos no nome)
chown -R bp2ocoworkscom:bp2ocoworkscom /home/bp2.ocoworks.com/public_html/
```

#### **Opção C - Se não souber o usuário:**
```bash
cd /home/bp2.ocoworks.com/public_html/
git clone https://github.com/dudupmoreira/bp2.git .

# Permissões corretas
chmod 644 *.html *.php
chmod 644 *.md

# Criar arquivos de log e cache
touch debug_vps.log
touch cache_products_vps.json

# Permissões de escrita
chmod 666 debug_vps.log
chmod 666 cache_products_vps.json

# Deixar o CyberPanel gerenciar as permissões
# Não definir chown manualmente
```

### **Passo 3: Verificar se Funcionou**
```bash
# Verificar se os arquivos foram criados
ls -la

# Verificar permissões
ls -la debug_vps.log
ls -la cache_products_vps.json

# Testar se consegue escrever
echo "test" > debug_vps.log
```

### **Passo 4: Configurar PHP no CyberPanel**
1. Acesse o CyberPanel
2. Vá para **"Websites"** → **"List Websites"**
3. Clique em **"Manage"** em `bp2.ocoworks.com`
4. Vá para **"PHP Manager"**
5. Selecione **PHP 7.4** ou superior
6. Clique em **"Save"**

### **Passo 5: Configurar SSL**
1. No website, vá para **"SSL"**
2. Clique em **"Issue SSL"**
3. Selecione **"Let's Encrypt"**
4. Clique em **"Issue"**

### **Passo 6: Testar**
```bash
# Testar se o site está funcionando
curl -I https://bp2.ocoworks.com/

# Testar o proxy
curl -X POST https://bp2.ocoworks.com/nuvemshop-proxy-vps.php \
  -H "Content-Type: application/json" \
  -d '{"volume_min":0,"volume_max":999,"categoria":"todas"}'
```

## 🛠️ Troubleshooting

### **Problema: Permissões incorretas**
```bash
# Verificar qual usuário o servidor web usa
ps aux | grep apache
ps aux | grep nginx

# Verificar permissões atuais
ls -la /home/bp2.ocoworks.com/public_html/

# Ajustar permissões se necessário
chmod 755 /home/bp2.ocoworks.com/public_html/
chmod 644 /home/bp2.ocoworks.com/public_html/*.php
chmod 644 /home/bp2.ocoworks.com/public_html/*.html
```

### **Problema: Arquivos não criados**
```bash
# Verificar se o diretório existe
ls -la /home/bp2.ocoworks.com/

# Criar manualmente se necessário
mkdir -p /home/bp2.ocoworks.com/public_html/
cd /home/bp2.ocoworks.com/public_html/
```

### **Problema: Git não funciona**
```bash
# Verificar se git está instalado
git --version

# Instalar git se necessário (CentOS/RHEL)
yum install git -y

# Ou (Ubuntu/Debian)
apt-get update && apt-get install git -y
```

## 📋 Checklist Final

- ✅ [ ] Repositório clonado
- ✅ [ ] Permissões configuradas
- ✅ [ ] Arquivos de log criados
- ✅ [ ] PHP configurado (7.4+)
- ✅ [ ] SSL ativo
- ✅ [ ] Site acessível
- ✅ [ ] Testes funcionando

## 🔍 Comandos de Verificação

```bash
# Verificar estrutura
ls -la /home/bp2.ocoworks.com/public_html/

# Verificar logs
tail -f /home/bp2.ocoworks.com/public_html/debug_vps.log

# Verificar cache
ls -la /home/bp2.ocoworks.com/public_html/cache_products_vps.json

# Testar PHP
php -v

# Testar conectividade
curl -I https://bp2.ocoworks.com/
```

---

**Status:** ✅ Comandos corrigidos para CyberPanel
**Última Atualização:** $(date) 