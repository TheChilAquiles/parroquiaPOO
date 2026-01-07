
# Módulo: Reportes

Este documento describe el análisis del módulo **Reportes** en el proyecto `parroquiaPOO`, incluyendo arquitectura MVC específica, fortalezas, falencias, oportunidades de mejora y un diagrama Mermaid del flujo.

---

## 1) Resumen del módulo

El módulo **Reportes** gestiona:
- Generación de estadísticas y listados por sacramento.
- Reportes de aportes mensuales y actividades.
- Exportación en formatos (PDF, CSV) y filtros por rango de fechas.
- Posible programación de reportes (si existe en Servicios).

### Archivos esperados en la estructura MVC:
- **Controlador**: `Controlador/ReportesController.php`
- **Servicio**: `Servicios/ReportesService.php`
- **Modelo**: `Modelo/Reporte.php`
- **Vista**: `Vista/reportes/*` (tablas, gráficos)
- **Data**: `Data/*` (consultas, agregaciones), `Logs/*` (auditoría)

---

## 2) Fortalezas
- Arquitectura MVC clara (Controlador, Servicio, Modelo, Vista).
- Uso de `phpstan` para análisis estático.
- Presencia de pruebas (`tests`) y configuración (`codeception.yml`).

## 3) Falencias detectadas
- Posible ejecución de agregaciones pesadas en Controlador.
- Falta de caché para reportes costosos.
- Validación insuficiente en parámetros de filtros (fechas, estados, rangos).
- Falta de documentación en `README.md`.

## 4) Oportunidades de optimización
- Implementar **capa de consultas** (repositorios) con agregaciones optimizadas.
- Añadir **caching** por ventana de tiempo (ej.: resultados del mes).
- Centralizar validaciones de filtros en Servicio.
- Ofrecer exportaciones consistentes (CSV, PDF) con paginación.
- Separar reportes **sincrónicos** (en pantalla) de **asíncronos** (generados en background si aplica).

---

## 5) Diagrama Mermaid — Flujo MVC del módulo

```mermaid
flowchart LR
    subgraph Entrada
        IDX[index.php]
        RTR[Router.php]
    end

    subgraph Controlador
        C[ReportesController]
    end

    subgraph Servicio
        S[ReportesService]
    end

    subgraph Modelo
        M[Reporte]
    end

    subgraph Data
        D[Data/* (consultas, agregaciones)]
        L[Logs/*]
    end

    subgraph Vista
        V[Vista/reportes/*]
    end

    IDX --> RTR --> C
    C --> S
    S --> M
    M --> D
    D --> L
    C --> V
```

---

## 6) Checklist de mejoras

- [ ] Validar parámetros de filtros (fechas, rangos, estados) en Servicio.
- [ ] Implementar repositorios y consultas optimizadas.
- [ ] Añadir caché para reportes frecuentes.
- [ ] Ofrecer exportación CSV/PDF con paginación y sanitización de datos.
- [ ] Documentar reportes disponibles y sus KPIs.
- [ ] Configurar CI/CD para pruebas de consultas y rendimiento.

---

## 7) Seguridad y buenas prácticas

- Evitar exponer datos sensibles (correos, teléfonos) en exportaciones públicas.
- Usar paginación para evitar fugas de grandes volúmenes de datos.
- Registrar auditoría de acceso a reportes (quién descarga, cuándo).
- Validar permisos por rol antes de generar/descargar.

