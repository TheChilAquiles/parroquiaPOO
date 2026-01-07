
# Módulo: Inventario / Recursos

Este documento describe el análisis del módulo **Inventario/Recursos** en el proyecto `parroquiaPOO`, incluyendo arquitectura MVC específica, fortalezas, falencias, oportunidades de mejora y un diagrama Mermaid del flujo.

---

## 1) Resumen del módulo

El módulo **Inventario/Recursos** gestiona:
- Registro y control de recursos (salones, equipos, ornamentos).
- Disponibilidad y reservas.
- Movimientos de entrada/salida.
- Integración con Agenda/Eventos.

### Archivos esperados en la estructura MVC:
- **Controlador**: `Controlador/InventarioController.php` o `RecursosController.php`
- **Servicio**: `Servicios/InventarioService.php`
- **Modelo**: `Modelo/Recurso.php` o `Modelo/Inventario.php`
- **Vista**: `Vista/inventario/*`
- **Data**: `Data/*` (persistencia), `Logs/*` (auditoría)

---

## 2) Fortalezas
- Arquitectura MVC clara (Controlador, Servicio, Modelo, Vista).
- Uso de `phpstan` para análisis estático.
- Presencia de pruebas (`tests`) y configuración (`codeception.yml`).

## 3) Falencias detectadas
- Posible falta de validación en cantidades y disponibilidad.
- Ausencia de repositorios para desacoplar persistencia.
- Falta de documentación en `README.md`.
- Posible mezcla de lógica en Controlador.

## 4) Oportunidades de optimización
- Implementar validaciones robustas para cantidades y reservas.
- Centralizar lógica en Servicios.
- Añadir pruebas unitarias para casos de disponibilidad y conflictos.
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
        C[InventarioController / RecursosController]
    end

    subgraph Servicio
        S[InventarioService]
    end

    subgraph Modelo
        M[Inventario / Recurso]
    end

    subgraph Data
        D[Data/*]
        L[Logs/*]
    end

    subgraph Vista
        V[Vista/inventario/*]
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
- [ ] Añadir pruebas unitarias para InventarioService.
- [ ] Documentar flujos en `README.md` y enlazar este archivo.
- [ ] Configurar CI/CD para ejecutar `phpstan` y `codeception`.

---

## 7) Seguridad y buenas prácticas

- Asegurar que `.env` esté en `.gitignore`.
- Bloquear acceso directo a `Vista/*` y `Data/*` mediante `.htaccess`.
- Usar consultas preparadas para evitar SQL Injection.
- Validar y escapar datos en vistas para prevenir XSS.

