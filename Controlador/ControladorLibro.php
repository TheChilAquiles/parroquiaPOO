<?php

class LibroController {


    
    public function __construct()
    {
        require_once('Modelo/ModeloLibro.php');
        require_once('Modelo/Conexion.php');
    }


    public function ctrlConsultarCantidadLibros($tipo){

        $libros = new  ModeloLibro();

        $cantidad = $libros->mdlConsultarCantidadLibros($tipo);

        return $cantidad ;

    }


        public function ctrlCrearLibro($tipo,$cantidad){

        $libros = new  ModeloLibro();

        $cantidad = $libros->mdlAñadirLibro($tipo,$cantidad);

        return $cantidad ;

    }

}








class records {
    
    private $recordsTable = 'usuarios';
    private $conn;

    public $id;
    public $name;
    public $parroquia;
    public $fechaFallecimiento;
    public $lugarNacimiento;
    public $age;
    public $causaMuerte;
    public $hijoDe;
    public $estadoCivil;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listRecords() {
        $sqlQuery = "SELECT * FROM {$this->recordsTable} ";

        // Búsqueda
        if (!empty($_POST["search"]["value"])) {
            $search = $this->conn->real_escape_string($_POST["search"]["value"]);
            $sqlQuery .= "WHERE (
                id LIKE '%{$search}%' OR
                name LIKE '%{$search}%' OR
                parroquia LIKE '%{$search}%' OR
                lugarNacimiento LIKE '%{$search}%' OR
                causaMuerte LIKE '%{$search}%'
            ) ";
        }

        // Orden
        if (!empty($_POST["order"])) {
            $columns = ["id", "name", "parroquia", "fechaFallecimiento", "lugarNacimiento", "age", "causaMuerte", "hijoDe", "estadoCivil"];
            $colIndex = intval($_POST["order"][0]["column"]);
            $orderDir = $_POST["order"][0]["dir"] === "desc" ? "DESC" : "ASC";
            $orderColumn = $columns[$colIndex] ?? "id";
            $sqlQuery .= "ORDER BY {$orderColumn} {$orderDir} ";
        } else {
            $sqlQuery .= "ORDER BY id DESC ";
        }

        // Paginación
        if ($_POST["length"] != -1) {
            $start = intval($_POST["start"]);
            $length = intval($_POST["length"]);
            $sqlQuery .= "LIMIT {$start}, {$length}";
        }

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmtTotal = $this->conn->prepare("SELECT * FROM {$this->recordsTable}");
        $stmtTotal->execute();
        $allResult = $stmtTotal->get_result();
        $allRecords = $allResult->num_rows;

        $records = [];
        while ($record = $result->fetch_assoc()) {
            $row = [];
            $row[] = $record["id"];
            $row[] = ucfirst($record["name"]);
            $row[] = $record["parroquia"];
            $row[] = $record["fechaFallecimiento"];
            $row[] = $record["lugarNacimiento"];
            $row[] = $record["age"];
            $row[] = $record["causaMuerte"];
            $row[] = $record["hijoDe"];
            $row[] = $record["estadoCivil"];
            $row[] = '<button type="button" name="update" id="'.$record["id"].'" class="btn btn-warning btn-xs update">Actualizar</button>';
            $row[] = '<button type="button" name="delete" id="'.$record["id"].'" class="btn btn-danger btn-xs delete">Borrar</button>';
            $records[] = $row;
        }

        echo json_encode([
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $allRecords,
            "recordsFiltered" => $allRecords,
            "data" => $records
        ]);
    }

    public function addRecord() {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->recordsTable}
            (name, parroquia, fechaFallecimiento, lugarNacimiento, age, causaMuerte, hijoDe, estadoCivil)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssisss",
            $this->name,
            $this->parroquia,
            $this->fechaFallecimiento,
            $this->lugarNacimiento,
            $this->age,
            $this->causaMuerte,
            $this->hijoDe,
            $this->estadoCivil
        );
        return $stmt->execute();
    }

    public function getRecord() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->recordsTable} WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->fetch_assoc());
    }

    public function updateRecord() {
        $stmt = $this->conn->prepare("
            UPDATE {$this->recordsTable}
            SET name = ?, parroquia = ?, fechaFallecimiento = ?, lugarNacimiento = ?, age = ?, causaMuerte = ?, hijoDe = ?, estadoCivil = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssssisssi",
            $this->name,
            $this->parroquia,
            $this->fechaFallecimiento,
            $this->lugarNacimiento,
            $this->age,
            $this->causaMuerte,
            $this->hijoDe,
            $this->estadoCivil,
            $this->id
        );
        return $stmt->execute();
    }

    public function deleteRecord() {
        $stmt = $this->conn->prepare("DELETE FROM {$this->recordsTable} WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}