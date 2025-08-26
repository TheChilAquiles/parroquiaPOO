<?php
// ControladorReportes.php

// Ajusta rutas según tu proyecto
require_once '../modelo/Conexion.php';
require_once '../modelo/ModeloReporte.php';

// Conexión y modelo
$conexion = Conexion::conectar();
$modeloReporte = new ModeloReporte($conexion);

// Acción por defecto
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {

    // Listado de reportes (muestra tabla)
    case 'listar':
        $reportes = $modeloReporte->mdlLeerReportes();
        // Incluir la vista que muestra el listado (ajusta la ruta)
        include_once '../vistas/reportes/listar_reportes.php';
        break;

    // Mostrar formulario para ver/editar un reporte
    case 'ver_editar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $reporte = $modeloReporte->mdlLeerReportePorId((int)$id);
            include_once '../vistas/reportes/editar_reporte.php';
        } else {
            // Si no viene id, abrir formulario en blanco para crear
            $reporte = null;
            include_once '../vistas/reportes/editar_reporte.php';
        }
        break;

    // Guardar (crear o actualizar)
    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Limpieza básica
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');

            // Validaciones mínimas
            if (empty($titulo) || empty($descripcion)) {
                // Puedes redirigir con error o mostrar la vista de edición con mensaje
                header('Location: ControladorReportes.php?accion=listar&error=missing');
                exit;
            }

            if ($id) {
                // Actualizar
                $ok = $modeloReporte->mdlActualizarReporte($id, $titulo, $descripcion, $categoria);
            } else {
                // Crear
                $ok = $modeloReporte->mdlCrearReporte($titulo, $descripcion, $categoria);
            }

            // Redirección según resultado
            if ($ok) {
                header('Location: ControladorReportes.php?accion=listar&success=1');
            } else {
                header('Location: ControladorReportes.php?accion=listar&error=save');
            }
            exit;
        }
        break;

    // Eliminar
    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $modeloReporte->mdlEliminarReporte((int)$id);
        }
        header('Location: ControladorReportes.php?accion=listar');
        exit;
        break;

    // Exportar CSV de los reportes (todos o filtrados)
    case 'exportar':
        // Puedes aceptar filtros por GET si quieres: ?estado=x&desde=...
        $datos = $modeloReporte->mdlLeerReportes(); // o método filtrado
        // Cabeceras CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reportes_export_'.date('Ymd_His').'.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['id','titulo','descripcion','categoria','fecha']);
        foreach ($datos as $r) {
            // Asegúrate de que $r sea array asociativo o conviértelo
            fputcsv($output, [
                $r['id'],
                $r['titulo'],
                $r['descripcion'],
                $r['categoria'],
                $r['fecha'] ?? ''
            ]);
        }
        fclose($output);
        exit;
        break;

    default:
        header('Location: ControladorReportes.php?accion=listar');
        exit;
        break;
}

// Cerrar conexión si tu clase lo requiere (si usas PDO no es necesario)
$conexion = null;
