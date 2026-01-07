
# Módulo: Agenda / Eventos

Este documento describe el análisis del módulo **Agenda/Eventos** en el proyecto `parroquiaPOO`, incluyendo arquitectura MVC específica, fortalezas, falencias, oportunidades de mejora y un diagrama Mermaid del flujo.

---

## 1) Resumen del módulo

El módulo **Agenda/Eventos** gestiona:
- Creación y administración de eventos parroquiales (misas, reuniones, catequesis).
- Gestión de calendario y horarios.
- Reservas de salones y recursos.
- Posible integración con notificaciones y otros módulos (Sacramentos, Personas).

### Archivos esperados en la estructura MVC:
- **Controlador**: `Controlador/AgendaController.php` o `EventosController.php`
- **Servicio**: `Servicios/AgendaService.php` o `EventosService.php`
- **Modelo**: `Modelo/Evento.php` o `Modelo/Agenda.php`
- **Vista**: `Vista/agenda/*` o `Vista/eventos/*`
- **Data**: `Data/*` (persistencia), `Logs/*` (auditoría)

---

## 2) Fortalezas
- Arquitectura MVC clara (Controlador, Servicio, Modelo, Vista).
- Uso de `phpstan` para análisis estático.
- Presencia de pruebas (`tests`) y configuración (`codeception.yml`).

## 3) Falencias detectadas
- Posible falta de validación en fechas y horarios (riesgo de conflictos).
- Ausencia de repositorios para desacoplar persistencia.
- Falta de documentación en `README.md`.
- Posible mezcla de lógica en Controlador.

## 4) Oportunidades de optimización
- Implementar validaciones robustas para fechas y recursos.
- Centralizar lógica en Servicios.
- Añadir pruebas unitarias para casos de conflicto de agenda.
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
        C[AgendaController / EventosController]
    end

    subgraph Servicio
        S[AgendaService / EventosService]
    end

    subgraph Modelo
        M[Evento / Agenda]
    end

    subgraph Data
        D[Data/*]
        L[Logs/*]
    end

    subgraph Vista
        V[Vista/agenda/*]
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
- [ ] Añadir pruebas unitarias para AgendaService.
- [ ] Documentar flujos en `README.md` y enlazar este archivo.
- [ ] Configurar CI/CD para ejecutar `phpstan` y `codeception`.

---

## 7) Seguridad y buenas prácticas

- Asegurar que `.env` esté en `.gitignore`.
- Bloquear acceso directo a `Vista/*` y `Data/*` mediante `.htaccess`.
- Usar consultas preparadas para evitar SQL Injection.
- Validar y escapar datos en vistas para prevenir XSS.

