# 🚀 Comandos Corretos para CyberPanel

## ✅ Usuário Identificado: bpoco5558

Baseado na saída do `ls -la /home/`, o usuário correto é `bpoco5558`.

## 🔧 Comandos Corretos

### **Passo 1: Clonar Repositório**
```bash
cd /home/bp2.ocoworks.com/public_html/
git clone https://github.com/dudupmoreira/bp2.git .
```

### **Passo 2: Configurar Permissões**
```bash
# Permissões para arquivos
chmod 644 *.html *.php
chmod 644 *.md

# Criar arquivos de log e cache
touch debug_vps.log
touch cache_products_vps.json

# Permissões de escrita para logs
chmod 666 debug_vps.log
chmod 666 cache_products_vps.json
```

### **Passo 3: Definir Proprietário Correto**
```bash
# Usar o usuário correto identificado
chown -R bpoco5558:bpoco5558 /home/bp2.ocoworks.com/public_html/
```

### **Passo 4: Verificar se Funcionou**
```bash
# Verificar arquivos
ls -la

# Verificar permissões
ls -la debug_vps.log
ls -la cache_products_vps.json

# Testar escrita
echo "test" > debug_vps.log
```

### **Passo 5: Configurar PHP no CyberPanel**
1. Acesse o CyberPanel
2. Vá para **"Websites"** → **"List Websites"**
3. Clique em **"Manage"** em `bp2.ocoworks.com`
4. Vá para **"PHP Manager"**
5. Selecione **PHP 7.4** ou superior
6. Clique em **"Save"**

### **Passo 6: Configurar SSL**
1. No website, vá para **"SSL"**
2. Clique em **"Issue SSL"**
3. Selecione **"Let's Encrypt"**
4. Clique em **"Issue"**

### **Passo 7: Testar**
```bash
# Testar se o site está funcionando
curl -I https://bp2.ocoworks.com/

# Testar o proxy
curl -X POST https://bp2.ocoworks.com/nuvemshop-proxy-vps.php \
  -H "Content-Type: application/json" \
  -d '{"volume_min":0,"volume_max":999,"categoria":"todas"}'
```

## 🧪 Testes de Validação

### **Teste 1: Conectividade**
```
https://bp2.ocoworks.com/test-vps.html
```

### **Teste 2: Calculadora**
```
https://bp2.ocoworks.com/surfboard-volume-calculator.html
```

### **Teste 3: Logs**
```bash
# Verificar logs
tail -f /home/bp2.ocoworks.com/public_html/debug_vps.log

# Verificar cache
ls -la /home/bp2.ocoworks.com/public_html/cache_products_vps.json
```

## 📋 Checklist

- ✅ [ ] Repositório clonado
- ✅ [ ] Permissões configuradas (bpoco5558)
- ✅ [ ] Arquivos de log criados
- ✅ [ ] PHP configurado (7.4+)
- ✅ [ ] SSL ativo
- ✅ [ ] Site acessível
- ✅ [ ] Testes funcionando

---

**Status:** ✅ Comandos corrigidos para usuário bpoco5558
**Data:** $(date) 