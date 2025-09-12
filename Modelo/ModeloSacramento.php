<?php

class ModeloSacramento
{
    private $nameTable;
    private $libroID;
    private $sacramentoTipo;
    private $tipo;
    private $libroNumero;
    private $numero;
    private $conexion;



    // INSERT INTO libros (numero, acta, folio, fecha_generacion) 
    // SELECT 1, -- numero 
    // COALESCE(MAX(acta), 0) + 1, -- acta: 1 o 2 según lo que ya haya 
    // 2, -- folio 
    // '0000-00-00' -- fecha_generacion 
    // FROM libros WHERE folio = 2 HAVING COALESCE(MAX(acta), 0) < 2;



    public function __construct($tipo, $numero)
    {

        $this->conexion = Conexion::conectar();
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

        $stmt = $this->conexion->prepare($sqlQuery);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtTotal = $this->conexion->prepare("SELECT COUNT(*) FROM {$this->nameTable}");
        $stmtTotal->execute();
        $recordsTotal = $stmtTotal->fetchColumn();

        if (!empty($_POST["search"]["value"])) {
            $stmtFiltered = $this->conexion->prepare("SELECT COUNT(*) FROM {$this->nameTable} {$where}");
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

    public function CrearSacramento($data)
    {

        // $Rusbel->debug($data,true); 

        try {
            // Variables con valores reales
            $tipo_sacramento_id = 2;
            $acta = 10;
            $folio = 5;
            $fecha_generacion = '2025-08-20';

            $stmt = $this->conexion->prepare("
            INSERT INTO {$this->nameTable}
            (libro_id, tipo_sacramento_id, acta, folio, fecha_generacion)
            VALUES (?, ?, ?, ?, ?)
        ");

            // Enlazar variables correctamente
            $stmt->bindParam(1, $this->libroID, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->sacramentoTipo, PDO::PARAM_INT);
            $stmt->bindParam(3, $acta, PDO::PARAM_INT);
            $stmt->bindParam(4, $folio, PDO::PARAM_INT);
            $stmt->bindParam(5, $fecha_generacion, PDO::PARAM_STR);

            $result = $stmt->execute();


            // Obtener el ID del nuevo registro
            $sacramentoID = $this->conexion->lastInsertId();


            include_once(__DIR__ . '/../Controlador/ControladorFeligres.php');
            include_once(__DIR__ . '/../Controlador/ControladorParticipante.php');


            $ControladorFeligres = new FeligresController();
            $ControladorParticipantes  = new ControladorParticipante($sacramentoID);

            file_put_contents('logs/app3.log', "integrantes: " . print_r($data['integrantes'], true), FILE_APPEND);




            foreach ($data['integrantes'] as $integrante) {



                file_put_contents('logs/app3.log', "Procesando integrante: " . print_r($integrante, true), FILE_APPEND);



                $datosFeligres = [];

                $datosFeligres['idUser']  = $integrante['usuario_id'] ?? null;
                $datosFeligres['tipo-doc'] = $integrante['tipoDoc'];
                $datosFeligres['documento'] = $integrante['numeroDoc'];
                $datosFeligres['telefono'] = $integrante['telefono']  ?? null;
                $datosFeligres['direccion'] = $integrante['direccion']  ?? null;
                $datosFeligres['primer-nombre'] = $integrante['primerNombre'];
                $datosFeligres['segundo-nombre']  = $integrante['segundoNombre'] ?? '';
                $datosFeligres['primer-apellido'] = $integrante['primerApellido'];
                $datosFeligres['segundo-apellido']  = $integrante['segundoApellido'] ?? '';
                $dataFeligres['rol-Participante'] =   $dataFeligres['rolParticipante'] ?? null; 
 


                try {

                    $existe =  $ControladorFeligres->ctrlConsularFeligres($integrante['tipoDoc'], $integrante['numeroDoc']);

                    if ($existe['status'] == 'success' && $existe['data']) {
                        $feligresID  = $existe['data']['id'] ?? null;
                    } else {
                        try {

                            $create =  $ControladorFeligres->ctrlCrearFeligres($datosFeligres);

                            if ($create['status'] == 'success') {

                                $feligresID =  $this->conexion->lastInsertId();
                            }
                        } catch (\Throwable $th) {

                            file_put_contents('logs/app3.log', 'erorr 1  doc : ' . print_r($th, true), FILE_APPEND);
                        }
                    }



                    $Partifipante = [
                        'feligres-id' =>  $feligresID,
                        'sacramento-id' =>  $sacramentoID,
                        'participante-id' =>   $dataFeligres['rolParticipante'] ?? null  
                    ];

                    $resultado =   $ControladorParticipantes->ctrlCrearParticipante(datos: $Partifipante);



                    file_put_contents('logs/app3.log', 'Participante user : ' . print_r($resultado, true), FILE_APPEND);
                    file_put_contents('logs/app3.log', 'creado user :  ' . $feligresID   . 'doc : ' . print_r($create, true), FILE_APPEND);
                } catch (\Throwable $th) {
                    file_put_contents('logs/app3.log', 'error  doc : ' . print_r($th, true), FILE_APPEND);
                }









                // try {





                //                         [rolParticipante] => Bautizado
                //     [tipoDoc] => 1
                //     [numeroDoc] => 123
                //     [primerNombre] => Daniel
                //     [segundoNombre] => asd
                //     [primerApellido] => godoy
                //     [segundoApellido] => duran


                //      $ControladorParticipantes->ctrlCrearParticipante()


                // } catch (\Throwable $th) {
                //     //throw $th;
                // }



            }


            file_put_contents('logs/app3.log', "Registro hecho\n id :" .     $sacramentoID, FILE_APPEND);

            return $result;
        } catch (\Throwable $th) {
            file_put_contents('logs/app3.log', "Error: " . $th->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }




    public function setLibroID()
    {
        try {
            //code...

            $stmt = $this->conexion->prepare("SELECT *  FROM `libros` WHERE `libro_tipo_id` = ? AND `numero` = ?; ");

            $stmt->bindParam(1, $this->sacramentoTipo, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->numero, PDO::PARAM_INT);


            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($data)) {
                $this->libroID = $data[0]['id'];
            };
        } catch (\Throwable $th) {

            file_put_contents('logs/app3.log', $th, FILE_APPEND);
            //throw $th;
        }
    }
}
