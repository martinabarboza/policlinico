# Policlínico Veterinario

Aplicación web para apoyar la gestión de consultas y pacientes de un policlínico veterinario. Permite registrar información clínica de equinos, caninos y felinos, y consultar la agenda y los datos de sus tutores.

## Funcionalidades

- Inicio de sesión para usuarios registrados.
- Dashboard con agenda diaria, consultas pendientes o en curso, próximas consultas e internaciones activas.
- Registro y edición de consultas clínicas, con guardado como pendiente, guardado de avances y finalización.
- Búsqueda de pacientes por nombre o chip y de tutores por nombre, documento o teléfono.
- Registro y edición de pacientes y tutores. El alta de pacientes admite las especies Equino, Canino y Felino.
- Agenda diaria y semanal; permite agendar, reprogramar y cancelar turnos. El backend considera turnos de 30 minutos y rechaza superposiciones para un mismo veterinario.
- Descarga en PDF de la historia clínica de un paciente.
- Listado y edición de usuarios para el rol Director.

## Tecnologías

- PHP 8.2 con Apache.
- MariaDB 10.11 y `mysqli`.
- Bootstrap 5.3.8, JavaScript y jQuery incluido localmente.
- FPDF, incluido en el proyecto, para generar historias clínicas en PDF.
- Docker Compose para ejecutar la aplicación y sus servicios.

## Arquitectura

La aplicación usa una estructura MVC propia, sin un framework PHP identificado. El flujo web general es:

`public/index.php` → `routes/web.php` → controlador → modelo → vista.

El router despacha las rutas; los controladores coordinan las operaciones y los modelos acceden a MariaDB.

## Estructura principal

- `public/`: punto de entrada web y recursos estáticos.
- `routes/`: definición de rutas.
- `backend/Controllers/`: controladores.
- `backend/Models/`: acceso a datos.
- `backend/Views/`: vistas y layouts.
- `backend/Core/`: router, conexión, autenticación y componentes comunes.
- `backend/Db/`: configuración de conexión.
- `database/`: volcados SQL y diagramas de datos.
- `storage/`: archivos adjuntos de reseñas y estudios paraclínicos.
- `docker/`: configuración de Apache para el contenedor.

## Requisitos

- Docker Engine o Docker Desktop con Docker Compose.
- Navegador web.
- La red externa de Docker `proxy` debe existir antes de iniciar los servicios.

## Instalación y ejecución

1. Clonar o descargar el proyecto.
2. Desde el directorio que contiene `docker-compose.yml`, crear la red externa si aún no existe:
   ```sh
   docker network create proxy
   ```
3. Revisar las credenciales de MariaDB en `docker-compose.yml` y los parámetros de conexión en `backend/Db/config.php`. Deben coincidir.
4. Iniciar los servicios:
   ```sh
   docker compose up --build
   ```
5. Importar manualmente `database/26-10-2026.sql` en la base `policlinico_db`; Compose no importa el esquema automáticamente.
6. Abrir la aplicación en `http://localhost:8080`. phpMyAdmin está disponible en `http://localhost:8081`.

Compose conserva los datos de MariaDB en un volumen y prepara los directorios de archivos adjuntos dentro de `storage/`.

**Nota:** los pasos describen la configuración presente en el proyecto; Docker no fue probado de extremo a extremo.

## Estado y limitaciones conocidas

- Hospitalización está parcialmente implementada: el dashboard muestra internaciones activas, pero la pantalla del módulo es informativa; no se encontró un flujo de interfaz para registrar ingresos, hacer seguimiento o dar altas.
- La gestión de usuarios ofrece rutas de listado y edición; no se encontró una ruta de alta de usuarios en el router.
- La estructura de la base se entrega como volcados SQL, no como migraciones ni inicialización automática.
- No se identificó un conjunto de pruebas automatizadas ni manifiestos de Composer o npm en el proyecto.
- La configuración Docker contiene credenciales de desarrollo. Deben reemplazarse y gestionarse de forma segura antes de un despliegue real.
- Algunos iconos y estilos se cargan desde servicios externos y requieren conexión a Internet.
