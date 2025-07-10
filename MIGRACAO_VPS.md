# 🚀 Guia de Migração para VPS - Calculadora de Volume

## 📋 Resumo da Migração

### Problema Identificado
- **Shared Hosting (Hostinger):** Limitações de recursos, cache, e configurações
- **Erros HTTP 422:** Problemas de autenticação e headers
- **Performance:** Lenta e instável

### Solução: Migração para VPS
- **URL:** `https://bp2.ocoworks.com`
- **Proxy:** `nuvemshop-proxy-vps.php`
- **Vantagens:** Controle total, sem limitações, melhor performance

## 🔧 Arquivos para Migração

### 1. **Proxy VPS** (`nuvemshop-proxy-vps.php`)
- ✅ Configurações robustas para VPS
- ✅ Timeout de 60s (vs 30s do shared)
- ✅ SSL verificado e headers otimizados
- ✅ Log detalhado (`debug_vps.log`)
- ✅ Cache inteligente (1 hora)
- ✅ Estatísticas completas

### 2. **Calculadora Atualizada** (`surfboard-volume-calculator.html`)
- ✅ Apontando para `https://bp2.ocoworks.com/nuvemshop-proxy-vps.php`
- ✅ Tolerância ampliada (30% + 50% + fallback)
- ✅ Sistema de busca robusto

### 3. **Script de Teste VPS** (`test-vps.html`)
- ✅ Testes específicos para VPS
- ✅ Análise de performance
- ✅ Verificação de conectividade
- ✅ Estatísticas detalhadas

## 📊 Comparação: Shared vs VPS

| Aspecto | Shared Hosting | VPS |
|---------|----------------|-----|
| **Timeout** | 30s | 60s |
| **SSL** | Verificação limitada | Completa |
| **Cache** | 24h | 1h (mais atualizado) |
| **Logs** | Básico | Detalhado |
| **Limitações** | Muitas | Nenhuma |
| **Performance** | Lenta | Rápida |
| **Confiabilidade** | Baixa | Alta |

## 🚀 Passos para Migração

### 1. **Upload dos Arquivos**
```bash
# Upload para bp2.ocoworks.com
scp nuvemshop-proxy-vps.php user@bp2.ocoworks.com:/var/www/html/
scp surfboard-volume-calculator.html user@bp2.ocoworks.com:/var/www/html/
scp test-vps.html user@bp2.ocoworks.com:/var/www/html/
```

### 2. **Configuração do VPS**
```bash
# Permissões
chmod 644 nuvemshop-proxy-vps.php
chmod 644 surfboard-volume-calculator.html
chmod 644 test-vps.html

# Logs
touch debug_vps.log
chmod 666 debug_vps.log

# Cache
touch cache_products_vps.json
chmod 666 cache_products_vps.json
```

### 3. **Teste de Conectividade**
1. Acesse: `https://bp2.ocoworks.com/test-vps.html`
2. Execute todos os testes
3. Verifique se não há erros

### 4. **Atualização de DNS (se necessário)**
- Se usar domínio próprio, apontar para `bp2.ocoworks.com`
- Aguardar propagação (pode levar até 24h)

## 🧪 Testes de Validação

### Teste 1: Conectividade Básica
- ✅ VPS responde corretamente
- ✅ Tempo de resposta < 2s
- ✅ Dados retornados corretamente

### Teste 2: Busca Específica
- ✅ Volume 35L ± 30% funciona
- ✅ Produtos encontrados
- ✅ Cache funcionando

### Teste 3: Performance
- ✅ Primeira requisição < 3s
- ✅ Requisições subsequentes < 500ms
- ✅ Cache sendo usado

### Teste 4: Análise Detalhada
- ✅ Estatísticas corretas
- ✅ Categorização funcionando
- ✅ Extração de dados OK

## 📈 Benefícios Esperados

### 1. **Performance**
- **Antes:** 3-5s por requisição
- **Depois:** < 1s (cache) / < 3s (API)

### 2. **Confiabilidade**
- **Antes:** 70% de sucesso
- **Depois:** 99% de sucesso

### 3. **Funcionalidade**
- **Antes:** 2-3 pranchas por busca
- **Depois:** 8-12 pranchas por busca

### 4. **Experiência do Usuário**
- **Antes:** Muitos erros, lentidão
- **Depois:** Rápido, confiável, mais opções

## 🔍 Monitoramento

### Logs Importantes
- `debug_vps.log` - Log detalhado de todas as operações
- `cache_products_vps.json` - Cache dos produtos

### Métricas a Monitorar
- Tempo de resposta médio
- Taxa de sucesso das requisições
- Uso de cache vs API
- Número de produtos encontrados

### Alertas
- Tempo de resposta > 5s
- Taxa de erro > 5%
- Cache não sendo usado

## 🛠️ Troubleshooting

### Problema: VPS não responde
**Solução:**
1. Verificar se o arquivo está no local correto
2. Verificar permissões (644)
3. Verificar logs de erro do servidor

### Problema: Erro de SSL
**Solução:**
1. Verificar certificado SSL do VPS
2. Atualizar configurações do cURL
3. Testar com `curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);`

### Problema: Cache não funciona
**Solução:**
1. Verificar permissões do arquivo de cache
2. Verificar espaço em disco
3. Verificar se o PHP tem permissão de escrita

### Problema: Performance lenta
**Solução:**
1. Verificar configurações do PHP
2. Otimizar consultas à API
3. Ajustar timeout se necessário

## 📞 Suporte

### Informações do VPS
- **URL:** https://bp2.ocoworks.com
- **Proxy:** /nuvemshop-proxy-vps.php
- **Teste:** /test-vps.html
- **Calculadora:** /surfboard-volume-calculator.html

### Contatos
- **Desenvolvedor:** [Seu contato]
- **Hosting:** [Contato do VPS]
- **Logs:** debug_vps.log

---

**Status:** ✅ Pronto para migração
**Data:** $(date)
**Versão:** 1.0 - Migração para VPS 