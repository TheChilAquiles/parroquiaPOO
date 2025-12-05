# Guía de Despliegue en ByetHost

Sigue estos pasos detallados para subir tu proyecto a ByetHost.

## 1. Preparación de la Base de Datos

1.  Inicia sesión en tu **VistaPanel** (Panel de Control de ByetHost) en [byet.host/login](https://byet.host/login).
2.  Busca la sección **DATABASES** y haz clic en **MySQL Databases**.
3.  En "Create New Database", escribe `parroquia` y haz clic en **Create Database**.
    *   *Nota:* El sistema le asignará un prefijo. Tu base de datos final será algo como `b7_40249021_parroquia`.
4.  Anota los siguientes datos que aparecen en esa página:
    *   **MySQL Host Name** (ej. `sql302.byethost7.com`)
    *   **MySQL User Name** (ej. `b7_40249021`)
    *   **MySQL Database Name** (ej. `b7_40249021_parroquia`)
5.  Haz clic en **Admin** (o abre phpMyAdmin desde el panel principal).
6.  En phpMyAdmin, selecciona tu base de datos a la izquierda.
7.  Ve a la pestaña **Importar**.
8.  Selecciona el archivo `Data/parroquia.sql` de tu proyecto local y haz clic en **Continuar**.

## 2. Configuración del Entorno

1.  En tu proyecto local, abre el archivo `.env.production` que acabo de crear.
2.  **IMPORTANTE:** Verifica que `DB_HOST` y `DB_NAME` coincidan EXACTAMENTE con los que anotaste en el paso 1.4. Si son diferentes, edita el archivo.
## 4. Verificación

1.  Abre tu navegador y entra a: `http://b7_40249021.byethost7.com/parroquiaPOO`
2.  Deberías ver la página de inicio.

## Solución de Problemas

*   **Error de Base de Datos:** Verifica el archivo `.env` en el servidor. Asegúrate de que la contraseña y el nombre de la base de datos sean correctos.
*   **Error 404 o 500:** Verifica que el archivo `.htaccess` se haya subido correctamente.
*   **Página en blanco:** En `index.php`, descomenta las líneas de `ini_set('display_errors', 1);` temporalmente para ver el error.
