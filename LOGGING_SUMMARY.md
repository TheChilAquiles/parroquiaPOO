# Sistema de Logging Comprehensivo - Parroquia POO

## Resumen de Implementación

Este documento detalla el sistema de logging y manejo de errores implementado en toda la aplicación para facilitar el debugging, monitoreo de seguridad y auditoría.

## Componentes con Logging Completo

### 1. **Controladores de Autenticación**

#### LoginController.php
- ✅ Intentos de login (exitosos y fallidos) con IP tracking
- ✅ Validación de emails y credenciales
- ✅ Logout tracking
- ✅ Recuperación de contraseña (solicitud, generación token, envío email)
- ✅ Reseteo de contraseña (validaciones, actualización exitosa)
- ✅ Protección PII: emails parcialmente enmascarados

#### RegistroController.php
- ✅ Intentos de registro con IP y user-agent
- ✅ Validaciones de campos (email, password, confirmación)
- ✅ Detección de emails duplicados
- ✅ Registros exitosos con user_id y rol
- ✅ Protección PII: email masking

### 2. **Controladores de Gestión**

#### PerfilController.php
- ✅ Búsqueda de feligrés (exitosa/fallida)
- ✅ Actualización de perfil (crear vs actualizar)
- ✅ Validación de campos faltantes
- ✅ Actualización de flag datos_completos
- ✅ Protección PII: números de documento enmascarados

#### DashboardController.php
- ✅ Acceso a dashboard con user_id, rol, IP
- ✅ Carga de estadísticas
- ✅ Totales de usuarios y recursos
- ✅ Errores de carga capturados

#### CertificadosController.php
- ✅ Acceso a vista de certificados (admin y feligrés)
- ✅ Generación de certificados (todos los métodos)
- ✅ Validaciones de campos faltantes
- ✅ Creación exitosa de certificados con detalles
- ✅ Generación de PDF automática y manual
- ✅ Solicitud de certificados desde sacramentos
- ✅ Verificación pública de certificados (con IP y user-agent tracking)
- ✅ Obtención de lista de familiares
- ✅ Tracking de certificados no encontrados
- ✅ Tracking de certificados pendientes vs generados
- ✅ Protección PII: números de documento enmascarados

### 3. **Sistema de Routing**

#### Router.php
- ✅ Todas las peticiones logged (route, method, IP, user-agent)
- ✅ Errores 404 (rutas no encontradas)
- ✅ Checks de autenticación
- ✅ Ejecución de controladores (inicio y fin)
- ✅ Errores de controlador (archivos, clases, métodos no encontrados)
- ✅ Excepciones de runtime con stack trace completo

## Niveles de Log

```php
Logger::info()    // Operaciones normales, acciones exitosas
Logger::warning() // Validaciones fallidas, operaciones no exitosas
Logger::error()   // Excepciones, errores críticos
```

## Información Logged en Cada Operación

Todos los logs incluyen contexto relevante:
- **user_id**: ID del usuario (o 'guest' si no autenticado)
- **ip**: Dirección IP del cliente (para tracking de seguridad)
- **route**: Ruta solicitada
- **error**: Mensaje de error (cuando aplica)
- **trace**: Stack trace completo (para excepciones)
- **Datos específicos**: Email, documento, valores de pago, etc.

## Protección de PII (Personally Identifiable Information)

El sistema implementa enmascaramiento automático de información sensible:

```php
// Emails: usuario@dominio.com → xxx***@dominio.com
'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)

// Documentos: 123456789 → 123***
'numero_doc_prefix' => substr($numeroDoc, 0, 3) . '***'

// Tokens: abc123def456 → abc12345...
'token_prefix' => substr($token, 0, 8) . '...'
```

## Beneficios del Sistema

### 1. **Debugging Eficiente**
- Rastrea exactamente dónde fallan las operaciones
- Stack traces completos para excepciones
- Información contextual rica (user, IP, route)

### 2. **Seguridad y Auditoría**
- Detecta intentos de acceso no autorizado
- Monitoring de password recovery abuse
- Trail completo de acciones de usuarios
- Detección de patrones sospechosos

### 3. **Compliance y Regulaciones**
- Logs para cumplimiento de normas de seguridad
- Auditoría de operaciones críticas (pagos, certificados)
- Protección de datos personales (PII masking)

### 4. **Performance Monitoring**
- Identifica rutas lentas o con errores frecuentes
- Monitorea carga del sistema
- Detecta cuellos de botella

## Ubicación de Logs

Los logs se almacenan en:
```
/logs/
├── app.log           # Logs generales de la aplicación
├── error.log         # Solo errores y excepciones
└── security.log      # Eventos de seguridad
```

## Rotación de Logs

El sistema implementa rotación automática de logs para evitar archivos muy grandes:
- Tamaño máximo por archivo: 10MB
- Archivos históricos mantenidos: 30 días
- Compresión automática de logs antiguos

## Consulta de Logs

### Ver logs en tiempo real
```bash
tail -f logs/app.log
```

### Filtrar por usuario
```bash
grep "user_id.*123" logs/app.log
```

### Ver solo errores
```bash
grep "ERROR" logs/app.log
# o
cat logs/error.log
```

### Buscar por IP
```bash
grep "192.168.1.100" logs/app.log
```

## Próximos Pasos

### Componentes Pendientes de Logging Completo:
- [ ] PagosController (métodos de pago críticos)
- [ ] ConfiguracionController (configuraciones del sistema)
- [x] CertificadosController (generación de certificados)
- [ ] ModeloUsuario (operaciones de BD)
- [ ] ModeloPago (transacciones)
- [ ] ModeloCertificados (emisión de documentos)
- [ ] ModeloFeligres (gestión de registros)
- [ ] ModeloSacramento (sacramentos)

## Mantenimiento

### Revisar logs regularmente
```bash
# Ver errores de las últimas 24 horas
find logs/ -name "*.log" -mtime -1 -exec grep "ERROR" {} \;
```

### Limpiar logs antiguos
```bash
# Eliminar logs mayores a 90 días
find logs/ -name "*.log" -mtime +90 -delete
```

## Contacto y Soporte

Para reportar issues o sugerir mejoras al sistema de logging:
- Issues: GitHub repository
- Email: admin@parroquia.com

---

**Última actualización**: 2025-01-15
**Versión del sistema de logging**: 1.0
**Mantenido por**: Equipo de Desarrollo Parroquia POO
