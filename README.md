
# Proyecto: parroquiaPOO

Este proyecto implementa un sistema de gestión parroquial basado en arquitectura **MVC** en PHP, orientado a la administración de sacramentos, feligreses, eventos y recursos.

---

## 📌 Descripción general

El sistema permite:
- Registrar y administrar información de feligreses.
- Gestionar sacramentos y certificados.
- Organizar eventos y catequesis.
- Controlar donaciones, caja y contabilidad.
- Administrar inventario y recursos.
- Definir roles y permisos para usuarios.
- Generar reportes y configurar parámetros de la parroquia.

---

## ✅ Módulos funcionales

1. **Personas / Feligreses**  
   Registro de datos, historial, estado sacramental, certificados solicitados.  
   [Ver análisis](PERSONAS_FELIGRESES.md)

2. **Sacramentos**  
   Bautismo, Primera Comunión, Confirmación, Matrimonio, Unción de los enfermos. Reglas, requisitos, actas, certificaciones.  
   [Ver análisis](SACRAMENTOS.md)

3. **Certificados**  
   Emisión de constancias: bautismo, confirmación, matrimonio, vida cristiana, etc.  
   [Ver análisis](CERTIFICADOS.md)

4. **Agenda / Eventos**  
   Misas, catequesis, reuniones, reservas de salones; calendario y asistencia.  
   [Ver análisis](AGENDA_EVENTOS.md)

5. **Catequesis / Formación**  
   Inscripciones a cursos, control de grupos, evaluaciones (si aplica).  
   [Ver análisis](CATEQUESIS_FORMACION.md)

6. **Donaciones / Diezmo**  
   Registro de aportes, recibos, reportes de contribuciones.  
   [Ver análisis](DONACIONES.md)

7. **Caja / Ingresos & Egresos**  
   Arqueos, comprobantes, conciliaciones básicas (si el proyecto contempla contabilidad).  
   [Ver análisis](CAJA_CONTABILIDAD.md)

8. **Inventario / Recursos**  
   Salones, equipos, libros, hostias, vino, ornamentos; reservas y disponibilidad.  
   [Ver análisis](INVENTARIO.md)

9. **Usuarios / Roles / Permisos**  
   Accesos para párroco, secretaria, catequista, tesorero, etc.  
   [Ver análisis](USUARIOS_ROLES.md)

10. **Comunidades / Grupos**  
    Pastoral juvenil, liturgia, coro, grupos de oración.  
    [Ver análisis](COMUNIDADES_GRUPOS.md)

11. **Reportes**  
    Estadísticas, listados por sacramento, aportes mensuales, actividades.  

12. **Configuración**  
    Parámetros de parroquia, plantillas de certificados, datos de contacto, numeraciones.  

---

## 🔍 Recomendaciones generales de arquitectura

- Mantener separación estricta entre Controlador, Servicio y Modelo.
- Implementar repositorios para desacoplar persistencia.
- Centralizar validaciones en Servicios.
- Añadir pruebas unitarias y funcionales para cada módulo.
- Configurar CI/CD para ejecutar `phpstan` y `codeception`.
- Documentar flujos y diagramas en `docs/`.

---

## 🛡️ Buenas prácticas de seguridad

- Asegurar que `.env` esté en `.gitignore`.
- Bloquear acceso directo a carpetas sensibles mediante `.htaccess`.
- Usar consultas preparadas para evitar SQL Injection.
- Validar y escapar datos en vistas para prevenir XSS.
- Implementar cifrado seguro para contraseñas (bcrypt o Argon2).

---

## 📐 Visualización de diagramas Mermaid

GitHub soporta diagramas Mermaid en Markdown de forma nativa. Para visualizarlos localmente:
- Usar VS Code con extensión *Markdown Preview Mermaid Support*.
- O herramientas como *Mermaid Live Editor*.

