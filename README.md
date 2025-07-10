# 🏄‍♂️ Calculadora de Volume de Prancha - Board's Point

Uma ferramenta inteligente para calcular o volume ideal de prancha de surf baseado no perfil do surfista e recomendar produtos da loja.

## ✨ Funcionalidades

### 🧮 Calculadora Avançada
- **Algoritmo inteligente** que considera múltiplos fatores
- **Validação em tempo real** dos dados inseridos
- **Opções avançadas** para surfistas experientes
- **Interface moderna** e responsiva

### 📊 Fatores Considerados
- **Peso e altura** do surfista
- **Idade** e condicionamento físico
- **Frequência** de surf
- **Nível de habilidade** (iniciante, intermediário, avançado)
- **Tipo de prancha** desejada
- **Condições da onda** (altura, velocidade)
- **Paddle power** do surfista

### 🛍️ Integração com Loja
- **Busca automática** de pranchas na NuvemShop
- **Filtro por volume** calculado
- **Categorização inteligente** de produtos
- **Cache otimizado** para melhor performance

## 🚀 Como Usar

1. **Acesse** a calculadora em `surfboard-volume-calculator.html`
2. **Preencha** seus dados básicos (peso, altura, idade)
3. **Configure** seu perfil de surf (habilidade, frequência, etc.)
4. **Use as opções avançadas** para maior precisão
5. **Clique em calcular** e veja suas recomendações

## 🛠️ Tecnologias

- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Backend**: PHP 7.4+
- **API**: NuvemShop REST API
- **Cache**: Sistema de cache local

## 📁 Estrutura do Projeto

```
BP Buscador/
├── surfboard-volume-calculator.html  # Interface principal
├── nuvemshop-proxy.php              # Proxy da API
├── cache_products.json              # Cache de produtos (gerado automaticamente)
└── README.md                        # Documentação
```

## 🔧 Configuração

### Pré-requisitos
- Servidor web com PHP 7.4+
- Extensão cURL habilitada
- Acesso à API da NuvemShop

### Configuração da API
Edite `nuvemshop-proxy.php` e atualize:
```php
$store_id = "SEU_STORE_ID";
$token = "SEU_ACCESS_TOKEN";
```

## 📈 Melhorias Implementadas

### v2.0 - Interface Moderna
- ✅ Design responsivo com gradientes
- ✅ Validação em tempo real
- ✅ Feedback visual melhorado
- ✅ Layout em grid para mobile

### v2.0 - Algoritmo Aprimorado
- ✅ Consideração de altura e idade
- ✅ Fatores mais precisos
- ✅ Limites de volume (20-100L)
- ✅ Recomendações personalizadas

### v2.0 - Backend Robusto
- ✅ Sistema de cache
- ✅ Tratamento de erros
- ✅ Processamento inteligente de produtos
- ✅ Extração automática de volume

## 🎯 Próximas Funcionalidades

- [ ] **Histórico de cálculos**
- [ ] **Comparação de pranchas**
- [ ] **Filtros avançados**
- [ ] **Sistema de favoritos**
- [ ] **Notificações de estoque**
- [ ] **Integração com redes sociais**

## 🤝 Contribuição

Para contribuir com o projeto:

1. Faça um fork do repositório
2. Crie uma branch para sua feature
3. Implemente as melhorias
4. Teste thoroughly
5. Envie um pull request

## 📞 Suporte

Para dúvidas ou suporte:
- Email: contato@boardspoint.com
- WhatsApp: (11) 99999-9999

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo LICENSE para mais detalhes.

---

**Desenvolvido com ❤️ pela equipe Board's Point**
