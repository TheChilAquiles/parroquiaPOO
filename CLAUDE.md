# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Purpose

**ParroquiaPOO** is an **educational project** designed to teach MVC architecture to junior developers. This is a parish management application built with **pure PHP OOP** - NO frameworks, NO complex abstractions. The goal is to demonstrate clean MVC patterns in the simplest way possible.

**Target Audience:** Junior developers learning OOP and MVC
**Teaching Focus:** Clear separation of concerns, security best practices, and maintainable code structure

## Architecture Pattern - Pure MVC

This project implements a **textbook MVC pattern** with a front controller:

```
HTTP Request
    ↓
index.php (Front Controller)
    ↓
Router.php (Route Dispatcher)
    ↓
CONTROLLER (Business Logic)
    ↓
MODEL (Database Access) → Returns Data
    ↓
CONTROLLER includes VIEW (Presentation)
    ↓
HTML Response
```

**Critical Rules:**
- ✅ **Controller** calls **Model** and includes **View**
- ❌ **Model NEVER calls View**
- ❌ **View NEVER calls Model directly**
- ❌ **Models do NOT echo/print HTML**
- ❌ **Views do NOT contain business logic**

## Directory Structure

```
parroquiaPOO/
├── index.php              # Entry point - initializes app
├── Router.php             # Routes URLs to Controllers
├── autoload.php           # Autoloads classes (PSR-0 style)
├── config.php             # Database configuration
├── Controlador/           # Controllers (business logic)
├── Modelo/                # Models (database access only)
├── Vista/                 # Views (HTML/presentation only)
│   ├── componentes/       # Reusable view parts
│   └── *.php              # Individual page views
├── assets/                # Static files (CSS, JS, images)
├── Data/                  # Database schema and documentation
└── vendor/                # Composer dependencies (minimal)
```

## MVC Responsibilities (Educational Reference)

### CONTROLLER Layer (Controlador/)
**What it does:**
- Receives HTTP requests
- Validates user input
- Checks authentication/authorization
- Calls Model methods to get/save data
- Prepares data for the View
- Includes the appropriate View file
- Handles redirects and responses

**What it does NOT do:**
- ❌ Execute SQL queries directly
- ❌ Echo HTML (except for AJAX JSON responses)
- ❌ Contain HTML templates

**Example:**
```php
class GruposController {
    private $modelo;

    public function __construct() {
        $this->modelo = new ModeloGrupo();
    }

    public function listar() {
        // 1. Check authentication
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            exit;
        }

        // 2. Get data from MODEL
        $grupos = $this->modelo->mdlListarGrupos();

        // 3. Include VIEW (Controller passes data via variable)
        include_once __DIR__ . '/../Vista/grupos.php';
        // Vista/grupos.php has access to $grupos variable
    }
}
```

### MODEL Layer (Modelo/)
**What it does:**
- Connects to database (via Conexion singleton)
- Executes SQL queries (SELECT, INSERT, UPDATE, DELETE)
- Returns data as arrays or objects
- Validates data integrity (uniqueness, foreign keys)
- Logs errors

**What it does NOT do:**
- ❌ Include View files
- ❌ Echo/print anything
- ❌ Access $_SESSION or $_GET directly
- ❌ Handle HTTP redirects

**Example:**
```php
class ModeloGrupo {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::obtenerInstancia()->obtenerBD();
    }

    public function mdlListarGrupos() {
        $sql = "SELECT * FROM grupos WHERE estado_registro IS NULL";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mdlCrearGrupo($nombre) {
        // Validation
        if (empty($nombre)) {
            error_log("Nombre vacío");
            return false;
        }

        // Insert
        $sql = "INSERT INTO grupos (nombre) VALUES (:nombre)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':nombre' => $nombre]);
    }
}
```

### VIEW Layer (Vista/)
**What it does:**
- Displays HTML
- Uses data passed from Controller (via variables)
- Contains only presentation logic (loops, conditionals for display)
- Uses `htmlspecialchars()` to prevent XSS

**What it does NOT do:**
- ❌ Execute database queries
- ❌ Contain business logic
- ❌ Process form submissions
- ❌ Instantiate Model classes

**Example:**
```php
<!-- Vista/grupos.php -->
<div class="container">
    <h1>Grupos Parroquiales</h1>

    <table id="tablaGrupos" class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grupos as $grupo): ?>
                <tr>
                    <td><?= htmlspecialchars($grupo['nombre']) ?></td>
                    <td>
                        <a href="/grupos/editar/<?= $grupo['id'] ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
```

## Routing System (Simple Front Controller)

### How URLs Work

1. **URL Rewriting** (`.htaccess`):
   ```
   /grupos/listar → index.php?route=grupos/listar
   /grupos/editar/5 → index.php?route=grupos/editar&id=5
   ```

2. **Router.php** maps routes to controllers:
   ```php
   private $rutas = [
       'grupos/listar' => [
           'controlador' => 'GruposController',
           'accion' => 'listar'
       ],
       'grupos/editar' => [
           'controlador' => 'GruposController',
           'accion' => 'editar'
       ]
   ];
   ```

3. **Router dispatches** the request:
   ```php
   public function dispatch() {
       $route = $_GET['route'] ?? 'inicio';

       if (isset($this->rutas[$route])) {
           $ctrl = $this->rutas[$route]['controlador'];
           $action = $this->rutas[$route]['accion'];

           require_once "Controlador/$ctrl.php";
           $controlador = new $ctrl();
           $controlador->$action();
       }
   }
   ```

## Database Pattern - Singleton Connection

```php
// Modelo/Conexion.php
class Conexion {
    private static $instancia = null;
    private $bd;

    private function __construct() {
        $this->bd = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function obtenerInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function obtenerBD() {
        return $this->bd;
    }
}
```

**Why Singleton?** One database connection shared across all models (efficiency).

## Authentication System

### Session Variables
```php
$_SESSION['logged'] = true;           // User is authenticated
$_SESSION['user-id'] = 123;           // User ID
$_SESSION['user-rol'] = 'Administrador'; // Role
```

### Authorization Levels
- **Administrador** - Full access (manage all data, users, reports)
- **Secretario** - Manage sacraments, groups, members
- **Feligres** - View own data, request certificates

### Public Routes (No Authentication Required)
```php
// Router.php
private $rutasPublicas = [
    'inicio',
    'login',
    'registro',
    'contacto',
    'informacion'
];
```

## Security Principles (Teaching Standards)

### 1. SQL Injection Prevention
**ALWAYS use prepared statements:**
```php
// ✅ CORRECT
$sql = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $conexion->prepare($sql);
$stmt->execute([':email' => $email]);

// ❌ WRONG - NEVER DO THIS
$sql = "SELECT * FROM usuarios WHERE email = '$email'";
```

### 2. XSS Prevention
**Escape output in views:**
```php
// ✅ CORRECT
<h1><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>

// ❌ WRONG
<h1><?= $titulo ?></h1>
```

### 3. Password Security
**Use password_hash() and password_verify():**
```php
// Storing password
$hash = password_hash($password, PASSWORD_BCRYPT);

// Verifying password
if (password_verify($inputPassword, $hash)) {
    // Password is correct
}
```

### 4. Input Validation
**Validate at Controller level:**
```php
// Required field
if (empty($nombre)) {
    $_SESSION['error'] = 'El nombre es requerido';
    header('Location: /grupos/crear');
    exit;
}

// Type validation
if (!is_numeric($id) || $id <= 0) {
    $_SESSION['error'] = 'ID inválido';
    exit;
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email inválido';
    exit;
}
```

## Frontend Stack (Keep It Simple)

### Technologies Used
- **HTML5** - Semantic markup
- **Tailwind CSS** (CDN) - Utility-first styling
- **jQuery** - DOM manipulation and AJAX
- **DataTables** - Interactive tables (search, sort, pagination)
- **SweetAlert2** - User-friendly alerts

### View Template Structure
```php
// Every page follows this structure:
include 'Vista/componentes/plantillaTop.php';    // Header, nav, CSS
?>
    <!-- Page content here -->
<?php
include 'Vista/componentes/plantillaBottom.php'; // Footer, scripts
```

## Common Development Tasks

### Adding a New Feature (Step-by-Step)

**Example: Adding a "Donations" module**

1. **Create the Database Table:**
   ```sql
   CREATE TABLE donaciones (
       id INT PRIMARY KEY AUTO_INCREMENT,
       monto DECIMAL(10,2),
       donante VARCHAR(100),
       fecha DATE,
       estado_registro DATETIME NULL
   );
   ```

2. **Create the Model** (`Modelo/ModeloDonacion.php`):
   ```php
   <?php
   class ModeloDonacion {
       private $conexion;

       public function __construct() {
           $this->conexion = Conexion::obtenerInstancia()->obtenerBD();
       }

       public function mdlListar() {
           $sql = "SELECT * FROM donaciones WHERE estado_registro IS NULL";
           $stmt = $this->conexion->prepare($sql);
           $stmt->execute();
           return $stmt->fetchAll(PDO::FETCH_ASSOC);
       }

       public function mdlCrear($monto, $donante, $fecha) {
           $sql = "INSERT INTO donaciones (monto, donante, fecha)
                   VALUES (:monto, :donante, :fecha)";
           $stmt = $this->conexion->prepare($sql);
           return $stmt->execute([
               ':monto' => $monto,
               ':donante' => $donante,
               ':fecha' => $fecha
           ]);
       }
   }
   ```

3. **Create the Controller** (`Controlador/DonacionesController.php`):
   ```php
   <?php
   class DonacionesController {
       private $modelo;

       public function __construct() {
           $this->modelo = new ModeloDonacion();
       }

       public function listar() {
           // Check authentication
           if (!isset($_SESSION['logged'])) {
               header('Location: /login');
               exit;
           }

           // Get data from model
           $donaciones = $this->modelo->mdlListar();

           // Include view
           include_once __DIR__ . '/../Vista/donaciones.php';
       }

       public function crear() {
           // Check authentication
           if (!isset($_SESSION['logged'])) {
               header('Location: /login');
               exit;
           }

           // Handle POST request
           if ($_SERVER['REQUEST_METHOD'] === 'POST') {
               $monto = $_POST['monto'] ?? '';
               $donante = $_POST['donante'] ?? '';
               $fecha = $_POST['fecha'] ?? '';

               // Validation
               if (empty($monto) || empty($donante) || empty($fecha)) {
                   $_SESSION['error'] = 'Todos los campos son requeridos';
                   header('Location: /donaciones/crear');
                   exit;
               }

               // Save to database
               if ($this->modelo->mdlCrear($monto, $donante, $fecha)) {
                   $_SESSION['success'] = 'Donación registrada exitosamente';
                   header('Location: /donaciones/listar');
               } else {
                   $_SESSION['error'] = 'Error al registrar donación';
                   header('Location: /donaciones/crear');
               }
               exit;
           }

           // Show form (GET request)
           include_once __DIR__ . '/../Vista/donaciones_crear.php';
       }
   }
   ```

4. **Create the View** (`Vista/donaciones.php`):
   ```php
   <?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

   <div class="container mx-auto p-4">
       <h1 class="text-2xl font-bold mb-4">Donaciones</h1>

       <a href="/donaciones/crear" class="bg-blue-500 text-white px-4 py-2 rounded">
           Nueva Donación
       </a>

       <table id="tablaDonaciones" class="table-auto w-full mt-4">
           <thead>
               <tr>
                   <th>Donante</th>
                   <th>Monto</th>
                   <th>Fecha</th>
               </tr>
           </thead>
           <tbody>
               <?php foreach ($donaciones as $donacion): ?>
                   <tr>
                       <td><?= htmlspecialchars($donacion['donante']) ?></td>
                       <td>$<?= number_format($donacion['monto'], 2) ?></td>
                       <td><?= date('d/m/Y', strtotime($donacion['fecha'])) ?></td>
                   </tr>
               <?php endforeach; ?>
           </tbody>
       </table>
   </div>

   <script>
       $(document).ready(function() {
           $('#tablaDonaciones').DataTable({
               language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' }
           });
       });
   </script>

   <?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
   ```

5. **Add Routes** to `Router.php`:
   ```php
   private $rutas = [
       // ... existing routes
       'donaciones/listar' => [
           'controlador' => 'DonacionesController',
           'accion' => 'listar'
       ],
       'donaciones/crear' => [
           'controlador' => 'DonacionesController',
           'accion' => 'crear'
       ]
   ];
   ```

6. **Add Menu Item** to `Vista/componentes/menubar.php`:
   ```php
   <?php if (in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])): ?>
       <li><a href="/donaciones/listar">Donaciones</a></li>
   <?php endif; ?>
   ```

## Naming Conventions (Educational Consistency)

### Files
- Controllers: `NombreController.php` (e.g., `GruposController.php`)
- Models: `ModeloNombre.php` (e.g., `ModeloGrupo.php`)
- Views: `nombre.php` (e.g., `grupos.php`, `grupos_crear.php`)

### Classes
- Controllers: `class NombreController`
- Models: `class ModeloNombre`

### Methods
- Models: Prefix with `mdl` → `mdlListar()`, `mdlCrear()`, `mdlEliminar()`
- Controllers: Action names → `listar()`, `crear()`, `editar()`, `eliminar()`

### Variables
- Database columns: `snake_case` (e.g., `usuario_id`, `estado_registro`)
- PHP variables: `camelCase` (e.g., `$usuarioId`, `$estadoRegistro`)

## Soft Delete Pattern (Audit Trail)

Instead of permanently deleting records, mark them as deleted:

```php
// Model method
public function mdlEliminar($id) {
    $sql = "UPDATE grupos
            SET estado_registro = NOW()
            WHERE id = :id";
    $stmt = $this->conexion->prepare($sql);
    return $stmt->execute([':id' => $id]);
}

// Always exclude deleted records in SELECT queries
public function mdlListar() {
    $sql = "SELECT * FROM grupos WHERE estado_registro IS NULL";
    // ...
}
```

**Why?** Allows recovery and maintains data integrity for historical records.

## AJAX Pattern (Optional Enhancement)

For dynamic forms without page reload:

```php
// Controller detects AJAX
private function esAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

public function guardar() {
    $resultado = $this->modelo->mdlGuardar($datos);

    if ($this->esAjax()) {
        // Return JSON for AJAX requests
        header('Content-Type: application/json');
        echo json_encode(['success' => $resultado]);
        exit;
    } else {
        // Traditional redirect for form submissions
        $_SESSION['success'] = 'Guardado exitosamente';
        header('Location: /ruta');
        exit;
    }
}
```

## Dependencies (Minimal)

```bash
composer install
```

**Packages:**
- `phpmailer/phpmailer` - Email sending (password recovery)
- `dompdf/dompdf` - PDF generation (certificates)

## Configuration

**Database** (`config.php`):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'parroquia');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## Database Setup

```bash
# Import schema
mysql -u root -p parroquia < Data/parroquia.sql
```

**View ER Diagram:** `Data/diagrama.png`

## Development Server

```bash
# Laragon (current setup)
# Access at: http://parroquiapoo.test

# Or PHP built-in server:
php -S localhost:8000
```

## Common Mistakes to Avoid (Teaching Points)

### ❌ Model echoing HTML
```php
// WRONG
public function mdlListar() {
    $sql = "SELECT * FROM grupos";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();
    echo "<table>...";  // ❌ Model should NOT echo
}
```

### ❌ View accessing database
```php
// WRONG in Vista/grupos.php
<?php
$conexion = new PDO(...);  // ❌ View should NOT connect to DB
$result = $conexion->query("SELECT...");
?>
```

### ❌ SQL injection vulnerability
```php
// WRONG
$sql = "SELECT * FROM usuarios WHERE email = '$email'";  // ❌ NEVER
$result = $conexion->query($sql);

// CORRECT
$sql = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $conexion->prepare($sql);
$stmt->execute([':email' => $email]);
```

### ❌ Missing XSS protection
```php
// WRONG
<h1><?= $userInput ?></h1>  // ❌ Can inject malicious scripts

// CORRECT
<h1><?= htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8') ?></h1>
```

## Refactoring Checklist

When reviewing/refactoring code, verify:

- [ ] **MVC Separation**
  - [ ] Controllers don't have SQL queries
  - [ ] Models don't include views or echo HTML
  - [ ] Views don't instantiate models or execute queries

- [ ] **Security**
  - [ ] All SQL uses prepared statements
  - [ ] All output uses `htmlspecialchars()`
  - [ ] Passwords use `password_hash()`
  - [ ] Input validation exists

- [ ] **Authentication/Authorization**
  - [ ] Routes check authentication where needed
  - [ ] Role-based access is enforced
  - [ ] Sessions are properly initialized

- [ ] **Code Organization**
  - [ ] Naming conventions are consistent
  - [ ] Files are in correct directories
  - [ ] Routes are defined in Router.php

- [ ] **Functionality**
  - [ ] Forms submit to correct routes
  - [ ] Data saves/updates correctly
  - [ ] Soft delete pattern is used
  - [ ] Error messages are user-friendly

## Key Files Reference

- `index.php` - Application entry point
- `Router.php` - URL routing and authentication guard
- `config.php` - Database configuration
- `autoload.php` - Class autoloader
- `Modelo/Conexion.php` - Database singleton
- `Vista/componentes/plantillaTop.php` - Header template
- `Vista/componentes/plantillaBottom.php` - Footer template
- `Data/parroquia.sql` - Database schema
