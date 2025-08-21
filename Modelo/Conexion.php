<?php

class Conexion {

    private $mySQLI;
    private $sql;
    private $result;
    private $filasAfectadas;

    public function __construct() {
        $this->abrir();
    }

    public function abrir() {
        $this->mySQLI = new mysqli("localhost", "root", "", "parroquia");

        if ($this->mySQLI->connect_error) {
            die("Error de conexión: " . $this->mySQLI->connect_error);
        }

        return true;
    }

    public function cerrar() {
        $this->mySQLI->close();
    }

    public function consulta($sql) {
        $this->sql = $sql;
        $this->result = $this->mySQLI->query($this->sql);

        if (!$this->result) {
            die("Error en la consulta: " . $this->mySQLI->error);
        }

        $this->filasAfectadas = $this->mySQLI->affected_rows;
        return true;
    }

    public function obtenerMySQLI() {
        return $this->mySQLI;
    }

    public function obtenerSql() {
        return $this->sql;
    }

    public function obtenerResult() {
        return $this->result;
    }

    public function obtenerFilasAfectadas() {
        return $this->filasAfectadas;
    }
}
