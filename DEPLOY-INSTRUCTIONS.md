# 🏄‍♂️ Calculadora de Volume de Prancha - Instruções de Deploy

## 📋 **Resumo do Projeto**

Aplicação web para calcular o volume ideal de prancha de surf usando o **Guild Factor** de John Whitney Guild e integrar com a API da NuvemShop para recomendar pranchas.

### 🎯 **Funcionalidades Principais**
- **Cálculo de volume** usando Guild Factor (Peso × Fator de Habilidade + Ajustes)
- **Integração com NuvemShop** para buscar pranchas reais
- **Interface moderna** e responsiva
- **Sistema de debug** para troubleshooting
- **Cache inteligente** para melhor performance

## 🚀 **Deploy na Hostinger**

### **1. Upload dos Arquivos**

Faça upload dos seguintes arquivos para o diretório raiz do seu domínio (`bp.ocoworks.com`):

```
📁 Arquivos Principais:
├── surfboard-volume-calculator.html    # Calculadora principal
├── nuvemshop-proxy.php                # Proxy da API (produção)
├── .htaccess                          # Configurações do servidor
└── README.md                          # Documentação

📁 Ferramentas de Debug:
├── debug-api.php                      # Teste da API
├── test-all-products.php              # Listar todos os produtos
├── test-volume-extraction.php         # Teste de extração de volume
└── check-hosting.php                  # Verificar configurações

📁 Configurações:
├── config-prod.php                    # Configurações de produção
└── DEPLOY-INSTRUCTIONS.md            # Este arquivo
```

### **2. Configuração do Token da API**

**IMPORTANTE:** Configure o token da API no arquivo `nuvemshop-proxy.php`:

```php
// Linha 15 - Substitua pelo seu token real
$API_TOKEN = '96685dd9b9c0c82b5d613b3d5dd466f1d6418083';
```

### **3. URLs de Acesso**

Após o deploy, você terá acesso às seguintes URLs:

| URL | Descrição |
|-----|-----------|
| `https://bp.ocoworks.com/surfboard-volume-calculator.html` | **Calculadora principal** |
| `https://bp.ocoworks.com/debug-api.php` | Teste da API |
| `https://bp.ocoworks.com/test-all-products.php` | Listar todos os produtos |
| `https://bp.ocoworks.com/test-volume-extraction.php` | **Teste de extração de volume** |
| `https://bp.ocoworks.com/check-hosting.php` | Verificar configurações |

## 🔧 **Testes e Troubleshooting**

### **Passo 1: Verificar Configurações**
```bash
# Acesse no navegador:
https://bp.ocoworks.com/check-hosting.php
```

### **Passo 2: Testar a API**
```bash
# Acesse no navegador:
https://bp.ocoworks.com/debug-api.php
```

### **Passo 3: Testar Extração de Volume**
```bash
# Acesse no navegador:
https://bp.ocoworks.com/test-volume-extraction.php
```

### **Passo 4: Verificar Todos os Produtos**
```bash
# Acesse no navegador:
https://bp.ocoworks.com/test-all-products.php
```

## 📊 **Sobre o Guild Factor**

O sistema usa o **Guild Factor** de John Whitney Guild para calcular o volume ideal:

### **Fórmula Base:**
```
Volume = Peso (lbs) × Fator de Habilidade + Ajustes
```

### **Fatores de Habilidade:**
- **Iniciante**: 0.45 (mais volume para estabilidade)
- **Intermediário**: 0.42 (volume equilibrado)
- **Avançado**: 0.38 (menos volume para manobrabilidade)

### **Ajustes Adicionais:**
- **Altura**: +2L por 10cm acima de 160cm
- **Idade**: +3L (40+ anos), +2L (50+ anos)
- **Tipo de prancha**: +8L (longboard), +4L (funboard)
- **Condicionamento**: +3L (baixo), -2L (alto)
- **Frequência**: +2L (pouca), -1L (diária)

## 🐛 **Problemas Comuns**

### **"Nenhuma prancha encontrada"**

1. **Execute o teste de extração:**
   ```
   https://bp.ocoworks.com/test-volume-extraction.php
   ```

2. **Verifique se os produtos têm volume no nome:**
   - Formato esperado: `28L`, `30.5L`, `45L`
   - Exemplo: "Shortboard 28L", "Fish 30.5L"

3. **Ajuste a tolerância se necessário:**
   - O sistema busca ±25% do volume calculado
   - Volume mínimo: 8L de tolerância

### **Erro de API**

1. **Verifique o token:**
   ```php
   // Em nuvemshop-proxy.php, linha 15
   $API_TOKEN = 'seu-token-aqui';
   ```

2. **Teste a API diretamente:**
   ```
   https://bp.ocoworks.com/debug-api.php
   ```

### **Problemas de Performance**

1. **Verifique o cache:**
   - Cache configurado para 1 hora
   - Logs em `/tmp/nuvemshop-debug.log`

2. **Monitore os logs:**
   ```bash
   # No servidor (se tiver acesso SSH)
   tail -f /tmp/nuvemshop-debug.log
   ```

## 📞 **Suporte**

Se encontrar problemas:

1. **Execute todos os testes** listados acima
2. **Verifique os logs** de erro do servidor
3. **Teste a API** diretamente no Postman
4. **Entre em contato** com o suporte técnico

## 🔄 **Atualizações**

Para atualizar a aplicação:

1. **Faça backup** dos arquivos atuais
2. **Substitua os arquivos** pelos novos
3. **Teste todas as funcionalidades**
4. **Verifique se o token** ainda está correto

---

**🎯 Objetivo:** Fornecer uma calculadora precisa e integrada para ajudar surfistas a encontrar a prancha ideal baseada no Guild Factor e produtos reais da loja. 