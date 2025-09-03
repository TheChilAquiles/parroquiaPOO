<?php
class CrearPagos {
    private $conn;
    private $table = "pagos";

    public $id;
    public $certificado_id;
    public $valor;
    public $estado;
    public $metodo_de_pago;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear pago
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (certificado_id, valor, estado, metodo_de_pago) 
                  VALUES (:certificado_id, :valor, :estado, :metodo_de_pago)";
        
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->certificado_id = htmlspecialchars(strip_tags($this->certificado_id));
        $this->valor = htmlspecialchars(strip_tags($this->valor));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->metodo_de_pago = htmlspecialchars(strip_tags($this->metodo_de_pago));

        // Bind
        $stmt->bindParam(":certificado_id", $this->certificado_id);
        $stmt->bindParam(":valor", $this->valor);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":metodo_de_pago", $this->metodo_de_pago);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}
