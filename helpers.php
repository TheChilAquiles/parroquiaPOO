<?php
/**
 * Helpers.php
 * Funciones auxiliares para el sistema
 */

/**
 * Genera una URL limpia para el sistema de rutas
 *
 * @param string $route Ruta sin el prefijo "?route="
 * @param array $params Parámetros adicionales (opcional)
 * @return string URL formateada
 */
function url($route, $params = []) {
    $url = "index.php?route=" . urlencode($route);

    if (!empty($params)) {
        foreach ($params as $key => $value) {
            $url .= "&" . urlencode($key) . "=" . urlencode($value);
        }
    }

    return $url;
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
 * Redirecciona a una ruta específica
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
