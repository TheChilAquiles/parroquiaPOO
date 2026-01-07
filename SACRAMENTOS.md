
# Módulo: Sacramentos

Este documento describe el análisis del módulo **Sacramentos** en el proyecto `parroquiaPOO`, incluyendo arquitectura MVC específica, fortalezas, falencias, oportunidades de mejora y un diagrama Mermaid del flujo.

---

## 1) Resumen del módulo

El módulo **Sacramentos** gestiona:
- Registro y administración de sacramentos (Bautismo, Comunión, Confirmación, Matrimonio, Unción).
- Validación de requisitos previos.
- Emisión de actas y certificados.
- Consulta de historial sacramental por persona.

### Archivos esperados en la estructura MVC:
- **Controlador**: `Controlador/SacramentosController.php`
- **Servicio**: `Servicios/SacramentosService.php`
- **Modelo**: `Modelo/Sacramento.php`
- **Vista**: `Vista/sacramentos/*` (formularios, listados)
- **Data**: `Data/*` (persistencia), `Logs/*` (auditoría)

---

## 2) Fortalezas
- Arquitectura MVC clara (Controlador, Servicio, Modelo, Vista).
- Uso de `phpstan` para análisis estático.
- Presencia de pruebas (`tests`) y configuración (`codeception.yml`).

## 3) Falencias detectadas
- Posible mezcla de lógica en Controlador (debe delegarse al Servicio).
- Validación insuficiente en datos (riesgo XSS/SQL Injection).
- Falta de documentación en `README.md`.
- Ausencia de repositorios para desacoplar persistencia.

## 4) Oportunidades de optimización
- Implementar repositorios para sacramentos.
- Centralizar validaciones en Servicios.
- Añadir pruebas unitarias para lógica de negocio.
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
        C[SacramentosController]
    end

    subgraph Servicio
        S[SacramentosService]
    end

    subgraph Modelo
        M[Sacramento]
    end

    subgraph Data
        D[Data/*]
        L[Logs/*]
    end

    subgraph Vista
        V[Vista/sacramentos/*]
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
- [ ] Añadir pruebas unitarias para SacramentosService.
- [ ] Documentar flujos en `README.md` y enlazar este archivo.
- [ ] Configurar CI/CD para ejecutar `phpstan` y `codeception`.

---

## 7) Seguridad y buenas prácticas

- Asegurar que `.env` esté en `.gitignore`.
- Bloquear acceso directo a `Vista/*` y `Data/*` mediante `.htaccess`.
- Usar consultas preparadas para evitar SQL Injection.
- Validar y escapar datos en vistas para prevenir XSS.

