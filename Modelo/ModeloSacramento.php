<?php

class ModeloSacramento
{
    private $nameTable;
    private $conn;


    private $libroID;
    private $sacramentoTipo;
    private $tipo;
    private $libroNumero;
    private $numero;



    // INSERT INTO libros (numero, acta, folio, fecha_generacion) 
    // SELECT 1, -- numero 
    // COALESCE(MAX(acta), 0) + 1, -- acta: 1 o 2 según lo que ya haya 
    // 2, -- folio 
    // '0000-00-00' -- fecha_generacion 
    // FROM libros WHERE folio = 2 HAVING COALESCE(MAX(acta), 0) < 2;






    public function __construct($tipo, $numero)
    {

        $archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
        file_put_contents($archivo, "Contructor" . $numero, FILE_APPEND);


        // require_once('../Modelo/Conexion.php');
        $this->conn = Conexion::conectar();
        $this->nameTable = 'sacramentos';
        $this->sacramentoTipo = $tipo;
        $this->numero = $numero;

        $this->setLibroID();
        
    }



    public function listRecords()
    {
        $sqlQuery = "SELECT * FROM {$this->nameTable} ";
        $where = "";
        $params = [];

        if (!empty($_POST["search"]["value"])) {
            $where .= " WHERE (
            id LIKE :search
        ) ";
            $params[':search'] = "%" . $_POST["search"]["value"] . "%";
        }

        $sqlQuery .= $where . " "; // <- Asegura el espacio antes de ORDER

        $columns = ["id"];
        if (!empty($_POST["order"])) {
            $colIndex = intval($_POST["order"][0]["column"]);
            $orderDir = $_POST["order"][0]["dir"] === "desc" ? "DESC" : "ASC";
            $orderColumn = $columns[$colIndex] ?? "id";
            $sqlQuery .= "ORDER BY {$orderColumn} {$orderDir} ";
        } else {
            $sqlQuery .= "ORDER BY id DESC ";
        }

        if ($_POST["length"] != -1) {
            $start = intval($_POST["start"]);
            $length = intval($_POST["length"]);
            $sqlQuery .= "LIMIT $start, $length ";
        }

        $stmt = $this->conn->prepare($sqlQuery);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtTotal = $this->conn->prepare("SELECT COUNT(*) FROM {$this->nameTable}");
        $stmtTotal->execute();
        $recordsTotal = $stmtTotal->fetchColumn();

        if (!empty($_POST["search"]["value"])) {
            $stmtFiltered = $this->conn->prepare("SELECT COUNT(*) FROM {$this->nameTable} {$where}");
            foreach ($params as $key => $value) {
                $stmtFiltered->bindValue($key, $value, PDO::PARAM_STR);
            }
            $stmtFiltered->execute();
            $recordsFiltered = $stmtFiltered->fetchColumn();
        } else {
            $recordsFiltered = $recordsTotal;
        }

        $records = [];
        foreach ($result as $record) {
            $row = [];
            foreach ($columns as $col) {
                $row[] = $record[$col] ?? null;
            }
            $records[] = $row;
        }

        $response = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $records
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function addRecords()
    {

        $archivo = 'logs/app.log'; // Carpeta logs/ debe existir o se crea
        file_put_contents($archivo, "addRecordssssssssssss", FILE_APPEND);




        // $stmt = $this->conn->prepare("
        //     INSERT INTO {$this->recordsTable}
        //     (name, parroquia, fechaFallecimiento, lugarNacimiento, age, causaMuerte, hijoDe, estadoCivil)
        //     VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        // ");
        // $stmt->bind_param(
        //     "ssssisss",
        //     $this->name,
        //     $this->parroquia,
        //     $this->fechaFallecimiento,
        //     $this->lugarNacimiento,
        //     $this->age,
        //     $this->causaMuerte,
        //     $this->hijoDe,
        //     $this->estadoCivil
        // );
        // return $stmt->execute();
    }



    public function setLibroID()
    {
        // $this->libroID = $libroID;
        $archivo = 'logs/app.log';

        file_put_contents($archivo, 'sacra tipo' . $this->numero, FILE_APPEND);


        try {
            //code...

            $stmt = $this->conn->prepare("SELECT *  FROM `libros` WHERE `libro_tipo_id` = ? AND `numero` = ?; ");

            $stmt->bindParam(1, $this->sacramentoTipo, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->numero, PDO::PARAM_INT);


            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Carpeta logs/ debe existir o se crea

            file_put_contents($archivo, 'sisas', FILE_APPEND);
            file_put_contents($archivo, $data, FILE_APPEND);
        } catch (\Throwable $th) {

            file_put_contents($archivo, $th, FILE_APPEND);
            //throw $th;
        }
    }
}
