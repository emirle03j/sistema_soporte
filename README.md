# Sistema de Gestión de Soporte Técnico

Un sistema web moderno y robusto diseñado para gestionar tickets de soporte, personal técnico y departamentos empresariales. Desarrollado con un enfoque en la experiencia de usuario (UX) utilizando tecnologías web estándares.

## 🚀 Características Principales

- **Gestión de Tickets de Soporte**: Creación, edición y eliminación de tickets con seguimiento de estados.
- **Filtrado Avanzado (AJAX)**:
    - Búsqueda en tiempo real por asunto, descripción, técnico o departamento.
    - Filtro por técnico asignado.
    - **Pestañas de Estado**: Filtro rápido por Pendientes, En Proceso y Resueltos con contadores dinámicos.
- **Administración de Personal**: Gestión completa (CRUD) de técnicos y sus cargos.
- **Gestión de Departamentos**: Registro y control de los departamentos de la organización.
- **Sistema de Autenticación**: Login seguro y registro de usuarios con recuperación de contraseña mediante preguntas de seguridad.
- **Navegación Inteligente**: Persistencia de filtros y paginación al editar o eliminar registros (soporte para historial del navegador).
- **Diseño Premium**: Interfaz moderna construida con Tailwind CSS, optimizada para claridad y eficiencia.

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7+ / 8+
- **Base de Datos**: MySQL / MariaDB
- **Frontend**: HTML5, JavaScript (Vanilla ES6+), Tailwind CSS (v4)
- **Servidor Recomendado**: XAMPP / Laragon / WAMP

## 📋 Requisitos del Sistema

1. **XAMPP** instalado (con módulos Apache y MySQL activos).
2. Navegador web moderno (Chrome, Firefox, Edge, etc.).

## 🔧 Instalación

1. **Clonar o descargar** el repositorio dentro de la carpeta `htdocs` de tu instalación de XAMPP (ej. `C:/xampp/htdocs/sistema_soporte`).
2. **Activar XAMPP**: Inicia los servicios de **Apache** y **MySQL**.
3. **Configuración Automática**: El sistema está diseñado para autoconfigurarse. Simplemente accede a la URL del proyecto:
   - Abre tu navegador y ve a: `http://localhost/sistema_soporte/`
   - El archivo `conexion.php` se encargará de:
     - Crear la base de datos `sistema_soporte` si no existe.
     - Crear todas las tablas necesarias (`tecnicos`, `departamentos`, `soportes`, `usuarios`) automáticamente.
4. **Primer Inicio**:
   - Deberás registrar un usuario en la opción de "Registro" para poder iniciar sesión.
   - Una vez registrado, inicia sesión para acceder al panel principal.

## 📂 Estructura del Proyecto

- `index.php`: Panel principal con estadísticas rápidas.
- `lista_soporte.php`: Listado avanzado de tickets con filtros AJAX.
- `crear_soporte.php` / `editar_soporte.php`: Formularios de gestión de tickets.
- `lista_tecnico.php` / `crear_tecnico.php`: Gestión de personal técnico.
- `lista_departamento.php`: Gestión de departamentos.
- `conexion.php`: Lógica de conexión y creación automática de esquemas de BD.
- `auth_check.php`: Middleware de seguridad para proteger rutas privadas.

## 💡 Notas de Uso

- **Filtros AJAX**: Al buscar o cambiar de pestaña, la URL se actualiza automáticamente. Esto permite refrescar la página sin perder la búsqueda actual.
- **Redirecciones**: Al editar un registro, el sistema te devolverá exactamente a la misma página y filtro desde el cual iniciaste la edición.

---
*Desarrollado para la optimización de procesos de soporte técnico interno.*
