<?php
require 'fpdf.php';
require __DIR__ . '/../../Modelo/Conexion.php';


class PDF extends FPDF
{
    private $tipoLibro;
    private $folio;
    private $acta;
    private $cnn;

    // Constructor para recibir parámetros
    function __construct($tipoLibro, $folio, $acta, $cnn) {
        parent::__construct();
        $this->tipoLibro = $tipoLibro;
        $this->folio = $folio;
        $this->acta = $acta;
        $this->cnn = $cnn;
    }

    // Cabecera de página
    function Header()
    {
        
       // Escudo
        $this->Image(__DIR__ . '/../imagenes/logo_diocesis.png', 10, 20, 12);
        

        // Encabezado
        $this->SetFont('Arial','I',9);
        $this->Cell(0, 5, utf8_decode('AUTENTICACIÓN ECLESIÁSTICA'), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('CURIA DIÓCESANA DE SOACHA'), 0, 1, 'C');

        $this->Ln(2);
        $this->SetFont('Arial','B',8);
        $this->Cell(0, 5, utf8_decode('CRA 7 # 12 - 22 PARQUE PRINCIPAL DE SOACHA Celular: 3203018354'), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('HORARIO DE ATENCIÓN LUNES A VIERNES 9:00a.m. A 12:00m. y 2:00p.m. A 5:00p.m'), 0, 1, 'C');

        $this->Ln(5);
        $this->SetFont('Arial','B',12);
        $this->Cell(0, 6, utf8_decode('DIÓCESIS DE SOACHA-BOSA'), 0, 1, 'C');
        $this->Cell(0, 6, utf8_decode('PARROQUIA SAN FRANCISCO DE ASÍS'), 0, 1, 'C');
        $this->Cell(0, 6, utf8_decode('BOSA SAN DIEGO'), 0, 1, 'C');
        $this->Cell(0, 6, utf8_decode('TEL: 6014023026'), 0, 1, 'C');

        $this->Ln(8);


        // 📌 Consultar datos según el tipo de libro
        if ($this->tipoLibro == "bautizo") {
            $sql = "SELECT nombre_bautizado, padre, madre, padrinos, fecha_bautizo
                    FROM bautizos
                    WHERE folio = '{$this->folio}' AND acta = '{$this->acta}'";
        } elseif ($this->tipoLibro == "confirmacion") {
            $sql = "SELECT nombre_confirmado, ministro, padrino, parroquia_bautismo, fecha_confirmacion
                    FROM confirmaciones
                    WHERE folio = '{$this->folio}' AND acta = '{$this->acta}'";
        } elseif ($this->tipoLibro == "matrimonio") {
            $sql = "SELECT nombre_novio, nombre_novia, testigos, fecha_matrimonio
                    FROM matrimonios
                    WHERE folio = '{$this->folio}' AND acta = '{$this->acta}'";
        } elseif ($this->tipoLibro == "defuncion") {
            $sql = "SELECT nombre_fallecido, fecha_defuncion, parroquia
                    FROM defunciones
                    WHERE folio = '{$this->folio}' AND acta = '{$this->acta}'";
        } else {
            $sql = null;
        }

        if ($sql) {
            $result = $this->cnn->query($sql);
            $row = $result ? $result->fetch_assoc() : null;
        } else {
            $row = null;
        }

        // 📌 Mostrar dinámicamente según resultados
        if ($this->tipoLibro == "bautizo" && $row) {    
            $this->Cell(0, 10, "ACTA DE BAUTIZO", 0, 1, 'C');
            $this->Cell(0, 10, "LIBRO DE BAUTIZOS - Folio {$this->folio} - Acta {$this->acta}", 0, 1, 'L');
            $this->Ln(5);
            $this->Cell(0, 10, "Nombre: ".$row['nombre_bautizado'], 0, 1, 'L');
            $this->Cell(0, 10, "Padre: ".$row['padre'], 0, 1, 'L');
            $this->Cell(0, 10, "Madre: ".$row['madre'], 0, 1, 'L');
            $this->Cell(0, 10, "Padrinos: ".$row['padrinos'], 0, 1, 'L');
        } elseif ($this->tipoLibro == "confirmacion" && $row) {
            $this->Cell(0, 10, "ACTA DE CONFIRMACION", 0, 1, 'C');
            $this->Cell(0, 10, "LIBRO DE CONFIRMACIONES - Folio {$this->folio} - Acta {$this->acta}", 0, 1, 'L');
            $this->Ln(5);
            $this->Cell(0, 10, "Nombre: ".$row['nombre_confirmado'], 0, 1, 'L');
            $this->Cell(0, 10, "Ministro: ".$row['ministro'], 0, 1, 'L');
            $this->Cell(0, 10, "Padrino: ".$row['padrino'], 0, 1, 'L');
            $this->Cell(0, 10, "Parroquia de Bautismo: ".$row['parroquia_bautismo'], 0, 1, 'L');
        } elseif ($this->tipoLibro == "matrimonio" && $row) {
            $this->Cell(0, 10, "ACTA DE MATRIMONIO", 0, 1, 'C');
            $this->Cell(0, 10, "LIBRO DE MATRIMONIOS - Folio {$this->folio} - Acta {$this->acta}", 0, 1, 'L');
            $this->Ln(5);
            $this->Cell(0, 10, "Novio: ".$row['nombre_novio'], 0, 1, 'L');
            $this->Cell(0, 10, "Novia: ".$row['nombre_novia'], 0, 1, 'L');
            $this->Cell(0, 10, "Testigos: ".$row['testigos'], 0, 1, 'L');
        } elseif ($this->tipoLibro == "defuncion" && $row) {
            $this->Cell(0, 10, "ACTA DE DEFUNCION", 0, 1, 'C');
            $this->Cell(0, 10, "LIBRO DE DEFUNCIONES - Folio {$this->folio} - Acta {$this->acta}", 0, 1, 'L');
            $this->Ln(5);
            $this->Cell(0, 10, "Nombre: ".$row['nombre_fallecido'], 0, 1, 'L');
            $this->Cell(0, 10, "Fecha: ".$row['fecha_defuncion'], 0, 1, 'L');
            $this->Cell(0, 10, "Parroquia: ".$row['parroquia'], 0, 1, 'L');
        } else {
            $this->Cell(0, 10, "ACTA - Tipo de Libro Desconocido o sin datos", 0, 1, 'C');
        }
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// 🔹 Recibir parámetros desde la URL
$tipoLibro = isset($_GET["tipoLibro"]) ? $_GET["tipoLibro"] : "desconocido";
$folio = isset($_GET["folio"]) ? $_GET["folio"] : "";
$acta  = isset($_GET["acta"]) ? $_GET["acta"] : "";

// Crear conexión
$cnn = new mysqli("localhost", "root", "", "parroquia");

// Crear PDF pasándole el parámetro
$pdf = new PDF($tipoLibro, $folio, $acta, $cnn);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->Output();
