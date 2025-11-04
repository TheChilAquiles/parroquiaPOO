# Guía de Pruebas Manuales - Módulo Libros-Sacramentos

## Información General

**Fecha:** 2025-11-03
**Módulo:** Sistema de navegación Libros → Sacramentos
**Objetivo:** Validar el flujo completo de navegación refactorizado de sesiones a rutas RESTful

---

## Prerrequisitos

### 1. Servidor y Base de Datos
- ✅ Laragon corriendo en puerto 80
- ✅ Base de datos `parroquia` con datos de prueba:
  - 8 libros de Bautizos (números 1-8)
  - 30 sacramentos registrados
  - 5 usuarios (roles: Administrador, Feligres)

### 2. Credenciales de Prueba
Para iniciar sesión, usa cualquiera de estos usuarios:

| Usuario | Rol | Permisos |
|---------|-----|----------|
| admin@test.com | Administrador | Acceso completo |
| secretario@test.com | Secretario | Gestión de sacramentos |

**Nota:** Verifica las contraseñas en la tabla `usuarios` o solicita acceso.

### 3. Navegador
- Chrome/Edge/Firefox actualizado
- JavaScript habilitado
- Consola de desarrollador abierta (F12) para detectar errores

---

## Caso de Prueba 1: Navegación Inicial - Selección de Tipo de Libro

### Objetivo
Verificar que el usuario puede acceder a la vista de tipos de libro y seleccionar uno.

### Pasos
1. Iniciar sesión en `http://parroquiapoo.test`
2. Navegar al menú "Libros" o acceder directamente a `?route=libros/tipos`
3. Observar las 4 tarjetas de tipos de libro:
   - ✝️ Bautizos
   - ✝️ Confirmaciones
   - ✝️ Defunciones
   - ✝️ Matrimonios

### Acción
Hacer clic en el botón "Ver Libros" de **Bautizos**

### Resultado Esperado
- ✅ Redirección a `?route=libros/seleccionar-tipo` (POST)
- ✅ La URL NO debe contener variables de sesión
- ✅ Se muestra la vista `libros.php` con:
  - Título: "Libros de Bautizos"
  - Listado de 8 libros numerados (1-8)

### Datos de POST Enviados
```
tipo: 1 (INT, NO STRING)
```

### Errores Comunes
- ❌ **Error:** "Tipo de libro inválido" → Verificar que el formulario envía `tipo=1` (número, no string)
- ❌ **Error 404:** Ruta no encontrada → Verificar que `Router.php` tiene la ruta `libros/seleccionar-tipo`

---

## Caso de Prueba 2: Selección de Número de Libro

### Objetivo
Verificar que el usuario puede seleccionar un libro específico por número.

### Pasos
1. Desde la vista de libros de Bautizos (resultado del Caso 1)
2. Observar los botones numerados del 1 al 8

### Acción
Hacer clic en el botón "Libro 1"

### Resultado Esperado
- ✅ Redirección a `?route=sacramentos/libro&tipo=1&numero=1` (GET)
- ✅ La URL debe ser visible y contener parámetros GET
- ✅ Se muestra la vista `sacramentos.php` con:
  - Título: "Bautizos 1" (tipo + número)
  - Tabla DataTables con ID `recordListing`
  - Datos cargando vía AJAX

### Parámetros GET en URL
```
route: sacramentos/libro
tipo: 1
numero: 1
```

### Validaciones en Consola
Abrir la consola del navegador (F12) y verificar:
- ✅ NO hay errores 404 o 500
- ✅ Petición AJAX a `?route=sacramentos/listar` con estado 200
- ✅ JSON de respuesta contiene `{"data": [...]}`

### Errores Comunes
- ❌ **Error:** "Tipo de libro inválido" → Verificar que LibrosController.php pasa `$tipoId` (INT) a la vista
- ❌ **Error:** "Número de libro inválido" → Verificar que el formulario envía `numero` (INT)
- ❌ **DataTables error:** "Requested unknown parameter" → Verificar que las columnas coinciden con el modelo

---

## Caso de Prueba 3: Carga de Sacramentos en DataTables

### Objetivo
Verificar que DataTables carga correctamente los sacramentos del libro seleccionado.

### Pasos
1. Desde `?route=sacramentos/libro&tipo=1&numero=1` (resultado del Caso 2)
2. Esperar 2-3 segundos para que DataTables cargue los datos vía AJAX

### Resultado Esperado

#### Estructura de Tabla
La tabla debe tener estas columnas:
| Columna | Datos Mostrados |
|---------|----------------|
| ➕ (toggle) | Icono para expandir detalles |
| Tipo Sacramento | "Bautizo" |
| Participantes | Lista concatenada: "Juan Pérez (Bautizado), María López (Madre), ..." |
| Fecha | Formato: "03/11/2025" (dd/mm/yyyy) |
| Lugar | Nombre del lugar o "N/A" |

#### Validaciones Técnicas

**1. Petición AJAX Correcta**
Abrir Network tab en DevTools y buscar la petición a `sacramentos/listar`:

```http
POST ?route=sacramentos/listar
Content-Type: application/x-www-form-urlencoded

tipo=1&numero=1
```

**2. Respuesta JSON Correcta**
El servidor debe responder con:
```json
{
  "data": [
    {
      "id": 1,
      "tipo_sacramento": "Bautizo",
      "participantes": "Juan Pérez (Bautizado), María López (Madre), José Pérez (Padre)",
      "fecha_generacion": "2025-01-15 10:30:00",
      "lugar": "Parroquia San José"
    },
    ...
  ]
}
```

**3. Verificar Columnas DataTables**
En `Vista/sacramentos.php` líneas 811-830, las columnas deben coincidir con el JSON:
- ✅ `data: 'tipo_sacramento'` existe en respuesta
- ✅ `data: 'participantes'` existe en respuesta
- ✅ `data: 'fecha_generacion'` existe en respuesta
- ✅ `data: 'lugar'` existe en respuesta

### Errores Comunes
- ❌ **DataTables muestra "No data available"**:
  - Verificar que el libro tiene sacramentos registrados
  - Revisar en MySQL: `SELECT * FROM sacramentos WHERE libro_id = (SELECT id FROM libros WHERE libro_tipo_id=1 AND numero=1)`

- ❌ **Error en consola:** "Requested unknown parameter 'participante_principal'":
  - Esto indica que las columnas NO fueron actualizadas correctamente
  - Verificar líneas 811-830 de `sacramentos.php`

- ❌ **Error 500 al llamar listar endpoint**:
  - Revisar logs de PHP: `C:\laragon\www\parroquiaPOO\logs\php_error.log`
  - Verificar que `SacramentosController::listar()` existe
  - Verificar que `ModeloSacramento::mdlObtenerPorLibro()` existe

- ❌ **Fecha se muestra mal formateada**:
  - Verificar función `render` en columna fecha (línea 822-824)
  - Debe formatear con `toLocaleDateString('es-ES')`

---

## Caso de Prueba 4: Expansión de Detalles de Participantes

### Objetivo
Verificar que al hacer clic en el ícono ➕, se expande una fila con detalles de los participantes.

### Pasos
1. Desde la tabla de sacramentos (resultado del Caso 3)
2. Identificar un sacramento que tenga participantes
3. Hacer clic en el ícono ➕ en la primera columna

### Resultado Esperado

#### Visual
- ✅ El ícono cambia de ➕ a ➖
- ✅ Se inserta una nueva fila debajo con fondo diferente
- ✅ La fila expandida muestra una lista de participantes con formato:
  ```
  • Bautizado: Juan Pérez García
  • Madre: María López Ramírez
  • Padre: José Pérez Sánchez
  • Padrino: Carlos Gómez Díaz
  ```

#### Validaciones Técnicas

**1. Petición AJAX Correcta**
```http
POST ?route=sacramentos/participantes
Content-Type: application/x-www-form-urlencoded

sacramento_id=1
```

**2. Respuesta JSON Correcta**
```json
[
  {
    "rol": "Bautizado",
    "nombre": "Juan Pérez García"
  },
  {
    "rol": "Madre",
    "nombre": "María López Ramírez"
  },
  ...
]
```

**3. Verificar Método en Modelo**
En `ModeloSacramento.php` líneas 32-59, el método `getParticipantes()` debe:
- ✅ Recibir un ID numérico válido
- ✅ Validar que `$sacramentoId` es numérico y > 0
- ✅ Retornar array con campos `rol` y `nombre`

### Errores Comunes
- ❌ **Error:** "ID de sacramento inválido":
  - Verificar línea 845 de `sacramentos.php`: debe ser `row.data().id` (NO `sacramento_id`)

- ❌ **Error 400:** "ID de sacramento inválido" desde el servidor:
  - Verificar que `SacramentosController::getParticipantes()` recibe `sacramento_id` en POST
  - Verificar validación en líneas 103-107

- ❌ **Respuesta vacía** `[]` pero el sacramento SÍ tiene participantes:
  - Verificar tabla `participantes` en MySQL:
    ```sql
    SELECT p.*, pr.rol, f.primer_nombre, f.primer_apellido
    FROM participantes p
    JOIN participantes_rol pr ON pr.id = p.rol_participante_id
    JOIN feligreses f ON f.id = p.feligres_id
    WHERE p.sacramento_id = 1;
    ```

- ❌ **Error de sintaxis SQL** en `getParticipantes()`:
  - Verificar líneas 43-50 de `ModeloSacramento.php`
  - Debe usar `COALESCE()` para manejar nombres nulos

---

## Caso de Prueba 5: Funcionalidad de DataTables (Búsqueda, Ordenamiento, Paginación)

### Objetivo
Verificar que las funciones estándar de DataTables funcionan correctamente.

### 5.1 Búsqueda

#### Pasos
1. En la tabla de sacramentos, localizar el campo de búsqueda (esquina superior derecha)
2. Escribir un nombre de participante, por ejemplo: "Juan"

#### Resultado Esperado
- ✅ La tabla filtra en tiempo real mostrando solo filas que contienen "Juan"
- ✅ Si no hay resultados, muestra "No se encontraron registros coincidentes"

### 5.2 Ordenamiento

#### Pasos
1. Hacer clic en el encabezado de columna "Fecha"
2. Hacer clic nuevamente

#### Resultado Esperado
- ✅ Primera clic: orden ascendente (más antiguo primero) - flecha ↑
- ✅ Segunda clic: orden descendente (más reciente primero) - flecha ↓

### 5.3 Paginación

#### Pasos
1. Si hay más de 10 sacramentos, observar los controles de paginación en la parte inferior
2. Hacer clic en "Siguiente"

#### Resultado Esperado
- ✅ Se muestran los siguientes 10 registros
- ✅ Botones "Anterior" y "Siguiente" se habilitan/deshabilitan según corresponda
- ✅ Texto muestra: "Mostrando 11 a 20 de X registros"

### Errores Comunes
- ❌ **Idioma en inglés:** Verificar configuración de idioma en líneas 779-794 de `sacramentos.php`
- ❌ **Ordenamiento no funciona:** Verificar que `orderable: true` en columnas (excepto la de toggle)

---

## Caso de Prueba 6: Navegación Entre Diferentes Tipos de Libro

### Objetivo
Verificar que se puede navegar entre diferentes tipos de libro sin interferencias.

### Pasos
1. Desde la vista de sacramentos de Bautizos Libro 1
2. Navegar de regreso a `?route=libros/tipos`
3. Seleccionar **Confirmaciones**
4. Seleccionar "Libro 1"

### Resultado Esperado
- ✅ La URL muestra: `?route=sacramentos/libro&tipo=2&numero=1`
- ✅ El título cambia a: "Confirmaciones 1"
- ✅ La tabla muestra solo sacramentos de tipo "Confirmación"
- ✅ NO se mezclan datos de Bautizos

### Validación Crítica
**Verificar aislamiento de datos:**
Abrir la petición AJAX en Network tab y confirmar que envía `tipo=2` (no tipo=1)

### Errores Comunes
- ❌ **Los datos no cambian:** Posible cache de navegador → Refrescar con Ctrl+F5
- ❌ **Se muestran sacramentos del tipo anterior:** Revisar que `$tipo` se pasa correctamente en línea 799 de `sacramentos.php`

---

## Caso de Prueba 7: Creación de Nuevo Sacramento (Opcional)

### Objetivo
Verificar el flujo completo de creación de un sacramento desde la vista de libro.

**Nota:** Este caso asume que el formulario de creación está implementado.

### Pasos
1. Desde `?route=sacramentos/libro&tipo=1&numero=1`
2. Localizar botón "Registrar Nuevo Sacramento"
3. Hacer clic

### Resultado Esperado
- ✅ Se muestra un modal o formulario con campos:
  - Fecha del sacramento
  - Lugar
  - Integrantes (participantes con roles)
  - Botón "Buscar Feligrés" por documento
- ✅ Al guardar, se crea el sacramento y se recarga la tabla

### Validaciones POST
```
route: sacramentos/crear
tipo: 1
numero: 1
integrantes[0][tipoDoc]: 1
integrantes[0][numeroDoc]: "12345678"
integrantes[0][rolParticipante]: 1
...
```

### Errores Comunes
- ❌ **Error:** "Debe agregar al menos un integrante" → Verificar validación en `SacramentosController::crear()` línea 51-55

---

## Caso de Prueba 8: Validación de Permisos y Autenticación

### Objetivo
Verificar que usuarios no autenticados no pueden acceder al módulo.

### Pasos
1. Cerrar sesión (logout)
2. Intentar acceder directamente a `?route=sacramentos/libro&tipo=1&numero=1`

### Resultado Esperado
- ✅ Redirección a `?route=login`
- ✅ Mensaje: "Debe iniciar sesión para continuar"

### Validación en Router
Verificar que `sacramentos/libro` NO está en `$rutasPublicas` de `Router.php`

---

## Checklist de Validación General

Antes de considerar el módulo funcional, verificar:

### Funcionalidad
- [ ] Usuario puede navegar de Tipos → Libros → Sacramentos
- [ ] DataTables carga datos correctamente
- [ ] Detalles de participantes se expanden sin errores
- [ ] Búsqueda, ordenamiento y paginación funcionan
- [ ] Se pueden consultar diferentes tipos de libro sin conflictos

### Código (MVC)
- [ ] Vistas NO acceden directamente a `$_POST`
- [ ] Controladores pasan variables explícitas a vistas
- [ ] Modelos NO echan HTML
- [ ] AJAX usa rutas del Router, no archivos directos

### Seguridad
- [ ] Rutas requieren autenticación
- [ ] Parámetros GET/POST se validan (tipo INT, rango)
- [ ] SQL usa prepared statements
- [ ] Salida HTML usa `htmlspecialchars()`

### Performance
- [ ] No hay consultas N+1
- [ ] DataTables usa paginación del lado del servidor
- [ ] No hay errores de JavaScript en consola

---

## Registro de Pruebas

**Tester:** _______________________
**Fecha:** _______________________

| Caso | Estado | Notas |
|------|--------|-------|
| CP1: Selección de tipo | ⬜ Pasa / ⬜ Falla | |
| CP2: Selección de número | ⬜ Pasa / ⬜ Falla | |
| CP3: Carga DataTables | ⬜ Pasa / ⬜ Falla | |
| CP4: Expansión detalles | ⬜ Pasa / ⬜ Falla | |
| CP5: Funciones DataTables | ⬜ Pasa / ⬜ Falla | |
| CP6: Navegación entre tipos | ⬜ Pasa / ⬜ Falla | |
| CP7: Creación sacramento | ⬜ Pasa / ⬜ Falla | |
| CP8: Validación permisos | ⬜ Pasa / ⬜ Falla | |

---

## Problemas Conocidos (Post-Refactorización)

### Resueltos
- ✅ Formularios sin atributo `action`
- ✅ Tipos de libro enviados como STRING en vez de INT
- ✅ Inputs vestigiales (`action="DefinirTipolibro"`, `sub-action`)
- ✅ Vistas accediendo directamente a `$_POST['numero-libro']`
- ✅ AJAX llamando a archivos inexistentes (`Controlador/ControladorSacramento.php`)
- ✅ DataTables esperando columnas incorrectas
- ✅ Referencia incorrecta a `sacramento_id` en vez de `id`

### Pendientes de Verificar
- ⚠️ Validación de entrada en `ModeloSacramento::getParticipantes()` (línea 35-38)
- ⚠️ Manejo de libros sin sacramentos (debería mostrar mensaje amigable)
- ⚠️ Comportamiento en navegadores antiguos (IE11)

---

## Comandos Útiles para Debugging

### Verificar datos en MySQL
```sql
-- Ver libros disponibles
SELECT l.id, lt.tipo, l.numero, COUNT(s.id) as total_sacramentos
FROM libros l
JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
LEFT JOIN sacramentos s ON s.libro_id = l.id
WHERE l.estado_registro IS NULL
GROUP BY l.id;

-- Ver sacramentos de un libro específico
SELECT s.*, st.tipo
FROM sacramentos s
JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
WHERE s.libro_id = (SELECT id FROM libros WHERE libro_tipo_id=1 AND numero=1);

-- Ver participantes de un sacramento
SELECT p.*, pr.rol, f.primer_nombre, f.primer_apellido
FROM participantes p
JOIN participantes_rol pr ON pr.id = p.rol_participante_id
JOIN feligreses f ON f.id = p.feligres_id
WHERE p.sacramento_id = 1;
```

### Verificar logs de PHP
```bash
# En Laragon
tail -f C:\laragon\www\parroquiaPOO\logs\php_error.log

# O verificar configuración de error_log
php -i | grep error_log
```

### Verificar rutas en Router
```bash
# Buscar una ruta específica
grep -n "sacramentos/libro" Router.php
```

---

## Contacto de Soporte

Para reportar bugs o solicitar aclaraciones:
- **Repositorio:** (agregar URL si aplica)
- **Issue tracker:** (agregar URL si aplica)
- **Desarrollador:** (agregar contacto)

---

**Fin del documento de pruebas**
