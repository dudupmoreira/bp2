# 🏄‍♂️ Calculadora de Volume de Pranchas - Board's Point

## 📋 Sobre o Projeto

Calculadora inteligente de volume de pranchas de surf integrada à loja Board's Point (NuvemShop). O sistema calcula o volume ideal baseado no Guild Factor e recomenda pranchas disponíveis na loja.

### 🌟 Características

- **Cálculo Inteligente:** Baseado no Guild Factor de John Whitney Guild
- **Integração API:** Conecta diretamente com a NuvemShop
- **Recomendações Flexíveis:** Tolerância de 30-50% para encontrar mais opções
- **Interface Moderna:** Design responsivo e intuitivo
- **Performance Otimizada:** Cache inteligente e configurações robustas para VPS

## 🚀 Tecnologias

- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Backend:** PHP 7.4+
- **API:** NuvemShop REST API
- **Cache:** JSON-based caching system
- **Deploy:** CyberPanel + VPS

## 📊 Funcionalidades

### 🧮 Calculadora de Volume
- Cálculo baseado no Guild Factor
- Considera peso, habilidade, idade, condicionamento
- Ajuste manual do Guild Factor
- Recomendações personalizadas

### 🔍 Busca de Pranchas
- Integração com API da NuvemShop
- Extração automática de volume das variações
- Categorização inteligente (shortboard, fish, longboard, etc.)
- Sistema de fallback robusto

### 📱 Interface
- Design responsivo
- Interface moderna e intuitiva
- Feedback visual em tempo real
- Exibição detalhada das recomendações

## 🏗️ Arquitetura

```
📁 bp2/
├── 🎯 surfboard-volume-calculator.html    # Calculadora principal
├── 🔌 nuvemshop-proxy-vps.php            # Proxy otimizado para VPS
├── 🧪 test-vps.html                      # Script de testes
├── 📚 MELHORIAS_IMPLEMENTADAS.md         # Documentação das melhorias
├── 🚀 MIGRACAO_VPS.md                    # Guia de migração
└── 📋 CYBERPANEL_DEPLOY.md               # Guia de deploy
```

## 🚀 Deploy Rápido

### Pré-requisitos
- CyberPanel configurado
- Domínio: bp2.ocoworks.com
- PHP 7.4+
- Git instalado

### Passos

1. **Acessar CyberPanel**
   ```
   https://seu-servidor:8090
   ```

2. **Criar Website**
   - Domain: `bp2.ocoworks.com`
   - Package: Default

3. **Clonar Repositório**
   ```bash
   cd /home/bp2.ocoworks.com/public_html/
   git clone https://github.com/dudupmoreira/bp2.git .
   ```

4. **Configurar Permissões**
   ```bash
   chmod 644 *.html *.php
   touch debug_vps.log cache_products_vps.json
   chmod 666 debug_vps.log cache_products_vps.json
   chown -R bp2.ocoworks.com:bp2.ocoworks.com .
   ```

5. **Configurar PHP**
   - Versão: 7.4+
   - Memory limit: 256M

6. **Configurar SSL**
   - Let's Encrypt automático

7. **Testar**
   ```
   https://bp2.ocoworks.com/test-vps.html
   ```

## 🧪 Testes

### Teste de Conectividade
```bash
# Acessar
https://bp2.ocoworks.com/test-vps.html

# Executar todos os testes
# Verificar logs
tail -f debug_vps.log
```

### Teste da Calculadora
```bash
# Acessar
https://bp2.ocoworks.com/surfboard-volume-calculator.html

# Testar com diferentes volumes
# Verificar recomendações
```

## 📊 Melhorias Implementadas

### v2.0 - Migração para VPS
- ✅ **Tolerância Ampliada:** 30% (inicial) + 50% (ampliada)
- ✅ **Limite API:** 100 produtos (vs 50)
- ✅ **Categorização:** 8 categorias detalhadas
- ✅ **Cache Inteligente:** 1 hora (vs 24h)
- ✅ **Logs Detalhados:** debug_vps.log
- ✅ **Performance:** < 1s (cache) / < 3s (API)

### v1.0 - Funcionalidades Básicas
- ✅ Cálculo de volume com Guild Factor
- ✅ Integração com API NuvemShop
- ✅ Interface responsiva
- ✅ Sistema de recomendações

## 🔧 Configuração

### Variáveis de Ambiente
```php
// nuvemshop-proxy-vps.php
$store_id = '2446542';
$token = '96685dd9b9c0c82b5d613b3d5dd466f1d6418083';
$cache_duration = 3600; // 1 hora
```

### Configurações PHP
```ini
memory_limit = 256M
max_execution_time = 60
display_errors = Off
log_errors = On
```

## 📈 Métricas

### Performance Esperada
- **Tempo de Resposta:** < 2s (cache) / < 5s (API)
- **Taxa de Sucesso:** 99%
- **Produtos Encontrados:** 8-12 por busca
- **Cache Hit Rate:** > 80%

### Monitoramento
- Logs: `debug_vps.log`
- Cache: `cache_products_vps.json`
- Métricas: Tempo de resposta, taxa de erro, uso de cache

## 🛠️ Troubleshooting

### Problemas Comuns

**Erro 500:**
```bash
tail -f /home/bp2.ocoworks.com/logs/error.log
chmod 644 *.php
```

**Cache não funciona:**
```bash
chmod 666 cache_products_vps.json
chown bp2.ocoworks.com:bp2.ocoworks.com cache_products_vps.json
```

**Performance lenta:**
```bash
# Verificar recursos
htop
# Verificar logs
tail -f debug_vps.log
```

## 🔄 Atualizações

### Atualizar Código
```bash
cd /home/bp2.ocoworks.com/public_html/
git pull origin main
chmod 644 *.html *.php
chmod 666 debug_vps.log cache_products_vps.json
```

### Backup Antes de Atualizar
```bash
cp -r /home/bp2.ocoworks.com/public_html/ /home/bp2.ocoworks.com/public_html_backup_$(date +%Y%m%d)
```

## 📞 Suporte

### URLs Importantes
- **Calculadora:** https://bp2.ocoworks.com/surfboard-volume-calculator.html
- **Testes:** https://bp2.ocoworks.com/test-vps.html
- **Proxy:** https://bp2.ocoworks.com/nuvemshop-proxy-vps.php

### Logs
- **Aplicação:** `debug_vps.log`
- **Servidor:** `/home/bp2.ocoworks.com/logs/error.log`
- **Cache:** `cache_products_vps.json`

### Contatos
- **Desenvolvedor:** [Seu contato]
- **Repositório:** https://github.com/dudupmoreira/bp2
- **Issues:** https://github.com/dudupmoreira/bp2/issues

## 📄 Licença

Este projeto é desenvolvido para a Board's Point. Todos os direitos reservados.

---

**Versão:** 2.0 - VPS Optimized  
**Última Atualização:** $(date)  
**Status:** ✅ Produção
