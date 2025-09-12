<?php

class DebugController {
    private string $name;
    private bool $table;

    /**
     * Constructor.
     *
     * @param string $name  Nombre del archivo de log.
     * @param bool $table   Si se habilita el volcado adicional de variables.
     */
    public function __construct(string $name, bool $table = false) {
        $this->name = $name;
        $this->table = $table;
    }

    /**
     * Escribe un mensaje en el archivo de log.
     *
     * @param string $text   Texto base a guardar.
     * @param mixed $data    (Opcional) Datos adicionales a imprimir con print_r.
     */
    public function debug(string $text, $data = null): void {
        $logPath = __DIR__ . '/../logs/' . $this->name . 'App.log';

        $output = $text;

        if ($this->table && $data !== null) {
            $output .= ' : ' . print_r($data, true);
        }

        $output .= PHP_EOL;

        file_put_contents($logPath, $output, FILE_APPEND);
    }
}
