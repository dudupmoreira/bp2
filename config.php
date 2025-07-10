<?php
/**
 * Configurações da Calculadora de Volume de Prancha
 * Board's Point - BP Buscador
 */

// Configurações da API NuvemShop
define('NUVEMSHOP_STORE_ID', '2446542');
define('NUVEMSHOP_ACCESS_TOKEN', '96685dd9b9c0c82b5d613b3d5dd466f1d6418083');
define('NUVEMSHOP_API_URL', 'https://api.tiendanube.com/v1');

// Configurações de Cache
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hora em segundos
define('CACHE_FILE', 'cache_products.json');

// Configurações da Calculadora
define('MIN_VOLUME', 20); // Volume mínimo em litros
define('MAX_VOLUME', 100); // Volume máximo em litros
define('VOLUME_TOLERANCE', 5); // Tolerância para busca de produtos

// Configurações de Debug
define('DEBUG_MODE', false);
define('LOG_FILE', 'debug.log');

// Configurações de Segurança
define('ALLOWED_ORIGINS', ['*']); // Em produção, especifique domínios específicos
define('MAX_REQUESTS_PER_MINUTE', 60);

// Configurações de Email (para futuras funcionalidades)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'contato@boardspoint.com');
define('SMTP_PASSWORD', ''); // Configurar em produção

// Configurações de Analytics
define('GOOGLE_ANALYTICS_ID', ''); // Adicionar ID do GA4
define('FACEBOOK_PIXEL_ID', ''); // Adicionar ID do Facebook Pixel

// Configurações de Redes Sociais
define('FACEBOOK_URL', 'https://facebook.com/boardspoint');
define('INSTAGRAM_URL', 'https://instagram.com/boardspoint');
define('WHATSAPP_NUMBER', '5511999999999');

// Configurações de SEO
define('SITE_TITLE', 'Calculadora de Volume de Prancha - Board\'s Point