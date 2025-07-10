<?php
/**
 * Configurações Específicas do Site - bp.ocoworks.com
 * 
 * Este arquivo contém configurações específicas para o domínio
 * bp.ocoworks.com da Board's Point.
 */

// Informações do Site
define('SITE_NAME', 'Board\'s Point');
define('SITE_DOMAIN', 'bp.ocoworks.com');
define('SITE_URL', 'https://bp.ocoworks.com');
define('SITE_EMAIL', 'contato@boardspoint.com');

// Configurações da Loja
define('STORE_NAME', 'Board\'s Point');
define('STORE_DESCRIPTION', 'Calculadora de Volume de Prancha');
define('STORE_KEYWORDS', 'surf, prancha, volume, calculadora, board point');

// Configurações de Contato
define('CONTACT_PHONE', '(11) 99999-9999');
define('CONTACT_WHATSAPP', '5511999999999');
define('CONTACT_EMAIL', 'contato@boardspoint.com');

// Configurações de Redes Sociais
define('SOCIAL_INSTAGRAM', '@boardspoint');
define('SOCIAL_FACEBOOK', 'boardspoint');
define('SOCIAL_YOUTUBE', 'boardspoint');

// Configurações de Analytics (opcional)
define('GOOGLE_ANALYTICS_ID', ''); // Adicione seu GA ID aqui
define('FACEBOOK_PIXEL_ID', ''); // Adicione seu Pixel ID aqui

// Configurações de SEO
define('SEO_TITLE', 'Calculadora de Volume de Prancha - Board\'s Point');
define('SEO_DESCRIPTION', 'Encontre a prancha perfeita com nossa calculadora de volume. Board\'s Point - Especialistas em surf.');
define('SEO_IMAGE', SITE_URL . '/og-image.jpg');

// Configurações de Performance
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hora
define('COMPRESSION_ENABLED', true);

// Configurações de Segurança
define('SECURITY_HEADERS_ENABLED', true);
define('CORS_ENABLED', true);
define('RATE_LIMITING_ENABLED', true);

// Configurações de Debug
define('DEBUG_MODE', false);
define('LOG_ERRORS', true);
define('LOG_FILE', 'error.log');

// Função para obter URL completa
function getSiteUrl($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

// Função para obter meta tags
function getMetaTags() {
    return [
        'title' => SEO_TITLE,
        'description' => SEO_DESCRIPTION,
        'keywords' => STORE_KEYWORDS,
        'og:title' => SEO_TITLE,
        'og:description' => SEO_DESCRIPTION,
        'og:image' => SEO_IMAGE,
        'og:url' => SITE_URL,
        'og:type' => 'website',
        'twitter:card' => 'summary_large_image',
        'twitter:title' => SEO_TITLE,
        'twitter:description' => SEO_DESCRIPTION,
        'twitter:image' => SEO_IMAGE
    ];
}

// Função para obter informações de contato
function getContactInfo() {
    return [
        'phone' => CONTACT_PHONE,
        'whatsapp' => CONTACT_WHATSAPP,
        'email' => CONTACT_EMAIL,
        'instagram' => SOCIAL_INSTAGRAM,
        'facebook' => SOCIAL_FACEBOOK,
        'youtube' => SOCIAL_YOUTUBE
    ];
}

// Função para verificar se é HTTPS
function isSecure() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
           $_SERVER['SERVER_PORT'] == 443;
}

// Função para obter URL atual
function getCurrentUrl() {
    $protocol = isSecure() ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// Função para log de acesso
function logAccess($page = '') {
    if (DEBUG_MODE) {
        $log = date('Y-m-d H:i:s') . ' - ' . $_SERVER['REMOTE_ADDR'] . ' - ' . $page . "\n";
        file_put_contents('access.log', $log, FILE_APPEND | LOCK_EX);
    }
}

// Configurações de erro para produção
if (!DEBUG_MODE) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    ini_set('display_errors', 0);
    if (LOG_ERRORS) {
        ini_set('log_errors', 1);
        ini_set('error_log', LOG_FILE);
    }
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Verificar se o arquivo de configuração está sendo incluído
if (!defined('SITE_CONFIG_LOADED')) {
    define('SITE_CONFIG_LOADED', true);
}
?> 