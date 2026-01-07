
# Módulo: Donaciones / Diezmo

Este documento describe el análisis del módulo **Donaciones/Diezmo** en el proyecto `parroquiaPOO`, incluyendo arquitectura MVC específica, fortalezas, falencias, oportunidades de mejora y un diagrama Mermaid del flujo.

---

## 1) Resumen del módulo

El módulo **Donaciones/Diezmo** gestiona:
- Registro de aportes y diezmos.
- Generación de recibos y comprobantes.
- Reportes de contribuciones.
- Posible integración con caja/contabilidad.

### Archivos esperados en la estructura MVC:
- **Controlador**: `Controlador/DonacionesController.php` o `DiezmoController.php`
- **Servicio**: `Servicios/DonacionesService.php`
- **Modelo**: `Modelo/Donacion.php`
- **Vista**: `Vista/donaciones/*`
- **Data**: `Data/*` (persistencia), `Logs/*` (auditoría)

---

## 2) Fortalezas
- Arquitectura MVC clara (Controlador, Servicio, Modelo, Vista).
- Uso de `phpstan` para análisis estático.
- Presencia de pruebas (`tests`) y configuración (`codeception.yml`).

## 3) Falencias detectadas
- Posible falta de validación en montos y fechas.
- Ausencia de repositorios para desacoplar persistencia.
- Falta de documentación en `README.md`.
- Posible mezcla de lógica en Controlador.

## 4) Oportunidades de optimización
- Implementar validaciones robustas para montos y fechas.
- Centralizar lógica en Servicios.
- Añadir pruebas unitarias para casos de cálculo y reportes.
- Mejorar separación de responsabilidades.

---

## 5) Diagrama Mermaid — Flujo MVC del módulo

```mermaid
flowchart LR
    subgraph Entrada
        IDX[index.php]
        RTR[Router.php]
    end

    subgraph Controlador
        C[DonacionesController / DiezmoController]
    end

    subgraph Servicio
        S[DonacionesService]
    end

    subgraph Modelo
        M[Donacion]
    end

    subgraph Data
        D[Data/*]
        L[Logs/*]
    end

    subgraph Vista
        V[Vista/donaciones/*]
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

- [ ] Validar datos y sanitizar entradas en Controlador y Servicio.
- [ ] Implementar repositorios para desacoplar Modelo y Data.
- [ ] Añadir pruebas unitarias para DonacionesService.
- [ ] Documentar flujos en `README.md` y enlazar este archivo.
- [ ] Configurar CI/CD para ejecutar `phpstan` y `codeception`.

---

## 7) Seguridad y buenas prácticas

- Asegurar que `.env` esté en `.gitignore`.
- Bloquear acceso directo a `Vista/*` y `Data/*` mediante `.htaccess`.
- Usar consultas preparadas para evitar SQL Injection.
- Validar y escapar datos en vistas para prevenir XSS.

