<?php
/**
 * Helpers.php
 * Funciones auxiliares para el sistema
 */

/**
 * Genera una URL amigable para el sistema de rutas
 *
 * @param string $route Ruta (ej: 'certificados/verificar')
 * @param array $params Parámetros adicionales (opcional)
 * @return string URL formateada
 */
function url($route, $params = []) {
    // Determinar si usamos URLs amigables o no
    $useCleanUrls = true; // Cambiar a false si mod_rewrite no está disponible
    
    if ($useCleanUrls) {
        // Formato amigable: /certificados/verificar/ABC123
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $url = $base . '/' . ltrim($route, '/');
        
        // Si hay un parámetro 'id' o 'codigo', agregarlo como parte de la ruta
        if (isset($params['id'])) {
            $url .= '/' . $params['id'];
            unset($params['id']);
        } elseif (isset($params['codigo'])) {
            $url .= '/' . $params['codigo'];
            unset($params['codigo']);
        }
        
        // El resto de parámetros van como query string
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
    } else {
        // Formato legacy: ?route=certificados/verificar&id=123
        $url = "index.php?route=" . urlencode($route);
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= "&" . urlencode($key) . "=" . urlencode($value);
            }
        }
    }
    
    return $url;
}

/**
 * Obtiene la URL base del sitio
 *
 * @return string
 */
function baseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $base = dirname($_SERVER['SCRIPT_NAME']);
    
    // Eliminar 'index.php' si está en el path
    $base = str_replace('/index.php', '', $base);
    
    return $protocol . "://" . $host . rtrim($base, '/');
}

/**
 * Obtiene la URL actual
 *
 * @return string
 */
function currentUrl() {
    return $_SERVER['REQUEST_URI'] ?? '';
}

/**
 * Redirecciona a una ruta específica (con URL amigable)
 *
 * @param string $route Ruta de destino
 * @param array $params Parámetros adicionales
 */
function redirect($route, $params = []) {
    header('Location: ' . url($route, $params));
    exit;
}

/**
 * Formatea un precio en pesos colombianos
 *
 * @param float $amount Monto
 * @return string Precio formateado
 */
function formatCOP($amount) {
    return '$' . number_format($amount, 0, ',', '.') . ' COP';
}

/**
 * Escapa HTML de forma segura
 *
 * @param string $text Texto a escapar
 * @return string Texto escapado
 */
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}