# Consultas SQL ejecutadas por la aplicación

Este documento describe las consultas SQL reales que utilizan los modelos PHP mediante `mysqli::prepare()`. Los signos `?` representan parámetros enlazados mediante `bind_param()`; no se concatenan valores directamente en el SQL.

La conexión se configura en `backend/Core/Database.php` y usa una base MariaDB con `utf8mb4`. Al abrir la conexión, la aplicación ejecuta:

```sql
SET time_zone = '<desplazamiento-horario-local>';
```

Por ejemplo: `SET time_zone = '-03:00'`.

## 1. Autenticación y usuarios

Archivo: `backend/Models/UsuarioModel.php`

### Buscar una cuenta por cédula

Permite iniciar sesión y recuperar los datos almacenados de la cuenta.

```sql
SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario,
       rol_Usuario, email_Usuario, passw_Usuario, estado_Usuario
FROM USUARIO
WHERE cedula_Usuario = ?
LIMIT 1;
```

### Registrar el último acceso

Actualiza el campo de último inicio de sesión después de autenticar al usuario.

```sql
UPDATE USUARIO
SET lastlogin_Usuario = NOW()
WHERE id_Usuario = ?;
```

### Listar usuarios activos o inactivos

Permite administrar cuentas según su estado y filtrar por cédula, nombre o apellido.

```sql
SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario,
       rol_Usuario, email_Usuario
FROM USUARIO
WHERE estado_Usuario = 'activo';
```

Con búsqueda:

```sql
SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario,
       rol_Usuario, email_Usuario
FROM USUARIO
WHERE (
    cedula_Usuario LIKE ?
    OR nombre_Usuario LIKE ?
    OR apellido_Usuario LIKE ?
    OR CONCAT_WS(' ', nombre_Usuario, apellido_Usuario) LIKE ?
)
AND estado_Usuario = 'activo';
```

La variante con `estado_Usuario = 'inactivo'` muestra las cuentas desactivadas.

### Crear una cuenta

Registra una cuenta con contraseña ya procesada mediante `password_hash()` en el controlador.

```sql
INSERT INTO USUARIO (
    cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario,
    email_Usuario, passw_Usuario, lastlogin_Usuario, dateupdate_Usuario
)
VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW());
```

### Modificar una cuenta

Edita los datos de una cuenta y conserva o reemplaza la contraseña según la solicitud.

```sql
UPDATE USUARIO
SET cedula_Usuario = ?, nombre_Usuario = ?, apellido_Usuario = ?,
    rol_Usuario = ?, email_Usuario = ?, passw_Usuario = ?,
    dateupdate_Usuario = NOW()
WHERE cedula_Usuario = ?;
```

### Activar o desactivar una cuenta

Cambia el estado funcional de la cuenta sin eliminar su historial.

```sql
UPDATE USUARIO
SET estado_Usuario = ?
WHERE cedula_Usuario = ?;
```

## 2. Tutores

Archivo: `backend/Models/TutorModel.php`

### Obtener un tutor

Carga los datos de un tutor en el formulario de edición.

```sql
SELECT id_Tutor, documento_Tutor, nombre_Tutor, telefono_Tutor,
       direccion_Tutor, email_Tutor, notas_Tutor
FROM TUTOR
WHERE id_Tutor = ?
LIMIT 1;
```

### Buscar tutores y contar sus pacientes

Permite buscar por nombre, documento o teléfono y muestra cuántos pacientes tiene cada tutor.

```sql
SELECT t.id_Tutor AS id,
       t.documento_Tutor AS documento,
       t.nombre_Tutor AS nombre,
       t.telefono_Tutor AS telefono,
       t.email_Tutor AS email,
       COUNT(p.id_Paciente) AS cantidad_pacientes
FROM TUTOR t
LEFT JOIN PACIENTE p ON p.id_Tutor = t.id_Tutor
WHERE (
    ? = ''
    OR t.nombre_Tutor LIKE ?
    OR t.documento_Tutor LIKE ?
    OR t.telefono_Tutor LIKE ?
)
GROUP BY t.id_Tutor, t.documento_Tutor, t.nombre_Tutor,
         t.telefono_Tutor, t.email_Tutor
ORDER BY t.nombre_Tutor
LIMIT 100;
```

### Validar y crear un tutor

Antes del alta se verifica que el documento no esté registrado.

```sql
SELECT id_Tutor
FROM TUTOR
WHERE documento_Tutor = ?
LIMIT 1;

INSERT INTO TUTOR (
    documento_Tutor, nombre_Tutor, telefono_Tutor,
    direccion_Tutor, email_Tutor, notas_Tutor
)
VALUES (?, ?, ?, ?, ?, ?);
```

### Actualizar un tutor

También verifica que otro tutor no utilice el nuevo documento.

```sql
SELECT id_Tutor
FROM TUTOR
WHERE documento_Tutor = ? AND id_Tutor <> ?
LIMIT 1;

UPDATE TUTOR
SET documento_Tutor = ?, nombre_Tutor = ?, telefono_Tutor = ?,
    direccion_Tutor = ?, email_Tutor = ?, notas_Tutor = ?
WHERE id_Tutor = ?;
```

## 3. Pacientes y razas

Archivo: `backend/Models/PacienteModel.php`

### Catálogos de especies y razas

```sql
SELECT id_Especie, nombre_Especie
FROM ESPECIE
ORDER BY nombre_Especie;

SELECT r.id_Raza, r.nombre_Raza
FROM RAZA r
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE e.nombre_Especie = ?
ORDER BY r.nombre_Raza;

SELECT r.id_Raza, r.nombre_Raza
FROM RAZA r
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE LOWER(TRIM(e.nombre_Especie)) = 'equino'
ORDER BY r.nombre_Raza;
```

Estas consultas alimentan los formularios y mantienen los valores disponibles según la especie seleccionada.

### Cargar la ficha de un paciente

```sql
SELECT p.*, t.nombre_Tutor AS nombre_tutor,
       t.documento_Tutor AS documento_tutor,
       r.nombre_Raza AS nombre_raza,
       e.nombre_Especie AS nombre_especie
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE p.id_Paciente = ?
LIMIT 1;
```

### Crear paciente con tutor

La operación se ejecuta dentro de una transacción. Si el tutor ya existe, reutiliza su identificador; si no existe, crea primero el tutor y después el paciente.

```sql
SELECT id_Tutor
FROM TUTOR
WHERE documento_Tutor = ?
LIMIT 1;

INSERT INTO TUTOR (
    documento_Tutor, nombre_Tutor, telefono_Tutor,
    direccion_Tutor, email_Tutor, notas_Tutor
)
VALUES (?, ?, ?, ?, ?, ?);

INSERT INTO PACIENTE (
    id_Tutor, id_Raza, numchip_Paciente, nombre_Paciente,
    fecha_nac_Paciente, edad_Paciente, sexo_Paciente, color_Paciente
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?);
```

Antes de guardar se valida además la raza, el chip y el documento del tutor.

### Actualizar un paciente

```sql
UPDATE TUTOR
SET documento_Tutor = ?, nombre_Tutor = ?, telefono_Tutor = ?,
    direccion_Tutor = ?, email_Tutor = ?, notas_Tutor = ?
WHERE id_Tutor = ?;

UPDATE PACIENTE
SET id_Raza = ?, numchip_Paciente = ?, nombre_Paciente = ?,
    fecha_nac_Paciente = ?, edad_Paciente = ?, sexo_Paciente = ?,
    color_Paciente = ?
WHERE id_Paciente = ?;
```

Las validaciones usan consultas auxiliares para impedir chips duplicados y comprobar que la raza corresponda a la especie.

```sql
SELECT id_Paciente
FROM PACIENTE
WHERE numchip_Paciente = ?
LIMIT 1;

SELECT r.id_Raza
FROM RAZA r
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE LOWER(TRIM(e.nombre_Especie)) = ?
  AND LOWER(TRIM(r.nombre_Raza)) = ?
LIMIT 1;
```

### Buscar pacientes

```sql
SELECT p.id_Paciente AS id,
       p.nombre_Paciente AS nombre,
       p.numchip_Paciente AS chip,
       t.nombre_Tutor AS tutor,
       t.id_Tutor AS tutor_id
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE p.nombre_Paciente LIKE ? OR p.numchip_Paciente LIKE ?
ORDER BY p.nombre_Paciente
LIMIT 50;
```

## 4. Servicios

Archivo: `backend/Models/Servicio.php`

### Administrar el catálogo

Lista los servicios disponibles para administración y presenta uno por su identificador.

```sql
SELECT id_Servicio, id_Usuario, nombre_Servicio, descripcion_Servicio,
       precio_Servicio, duracion_Servicio,
       imagenURL_Servicio, iconoURL_Servicio
FROM SERVICIO
ORDER BY nombre_Servicio, id_Servicio;

SELECT id_Servicio, id_Usuario, nombre_Servicio, descripcion_Servicio,
       precio_Servicio, duracion_Servicio,
       imagenURL_Servicio, iconoURL_Servicio
FROM SERVICIO
WHERE id_Servicio = ?
LIMIT 1;
```

### Crear, actualizar y eliminar servicios

```sql
INSERT INTO SERVICIO (
    id_Usuario, nombre_Servicio, descripcion_Servicio, precio_Servicio,
    duracion_Servicio, imagenURL_Servicio, iconoURL_Servicio
)
VALUES (?, ?, ?, ?, ?, ?, ?);

UPDATE SERVICIO
SET nombre_Servicio = ?, descripcion_Servicio = ?, precio_Servicio = ?,
    duracion_Servicio = ?, imagenURL_Servicio = ?, iconoURL_Servicio = ?
WHERE id_Servicio = ?;

DELETE FROM SERVICIO
WHERE id_Servicio = ?;
```

La portada obtiene los servicios publicados mediante:

```sql
SELECT nombre_Servicio AS titulo,
       descripcion_Servicio AS descripcion,
       precio_Servicio AS precio,
       duracion_Servicio AS duracion,
       imagenURL_Servicio AS imagen,
       iconoURL_Servicio AS icono
FROM SERVICIO;
```

## 5. Agenda y turnos

Archivo: `backend/Models/AgendaModel.php`

### Listar turnos por período

Incluye paciente, tutor, especie y los profesionales asociados a la consulta.

```sql
SELECT c.id_Consulta AS id,
       c.fecha_hora_Consulta AS fecha_hora,
       c.estado_proceso_Consulta AS estado,
       c.tipo_Consulta AS tipo,
       c.motivo_Consulta AS motivo,
       p.nombre_Paciente AS paciente,
       t.nombre_Tutor AS tutor,
       e.nombre_Especie AS especie,
       COALESCE(
           (
               SELECT GROUP_CONCAT(
                   DISTINCT TRIM(CONCAT_WS(' ', ua.nombre_Usuario, ua.apellido_Usuario))
                   ORDER BY ua.nombre_Usuario SEPARATOR ', '
               )
               FROM USUARIO_CONSULTA uca
               INNER JOIN USUARIO ua ON ua.id_Usuario = uca.id_Usuario
               WHERE uca.id_Consulta = c.id_Consulta
           ),
           'Sin usuarios asociados'
       ) AS usuarios
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE c.fecha_hora_Consulta >= ? AND c.fecha_hora_Consulta < ?
ORDER BY c.fecha_hora_Consulta, c.id_Consulta;
```

### Crear un turno

Se crea la consulta en estado `Pendiente` y se vincula con un usuario dentro de una transacción.

```sql
INSERT INTO CONSULTA (
    id_Paciente, tipo_Consulta, fecha_hora_Consulta,
    estado_proceso_Consulta, motivo_Consulta
)
VALUES (?, ?, ?, 'Pendiente', ?);

INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta)
VALUES (?, ?);
```

### Reprogramar o cancelar

Solo permite modificar turnos cuyo estado normalizado sea `pendiente`.

```sql
SELECT id_Paciente
FROM CONSULTA
WHERE id_Consulta = ?
  AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente'
FOR UPDATE;

UPDATE CONSULTA
SET fecha_hora_Consulta = ?
WHERE id_Consulta = ?
  AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente';

UPDATE CONSULTA
SET estado_proceso_Consulta = 'Cancelada', fecha_fin_Consulta = NULL
WHERE id_Consulta = ?
  AND LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente';
```

## 6. Consultas clínicas

Archivo: `backend/Models/ConsultaModel.php`

### Listar pacientes disponibles por especie

```sql
SELECT p.id_Paciente, p.nombre_Paciente, p.numchip_Paciente,
       t.nombre_Tutor, e.nombre_Especie AS especie
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE e.nombre_Especie = ?
ORDER BY p.nombre_Paciente;
```

### Crear o editar una consulta clínica

La operación reemplaza los datos clínicos asociados al editar una consulta pendiente o en curso.

```sql
INSERT INTO CONSULTA (
    id_Paciente, tipo_Consulta, fecha_hora_Consulta,
    estado_proceso_Consulta, fecha_fin_Consulta, motivo_Consulta
)
VALUES (?, ?, ?, ?, ?, ?);

UPDATE CONSULTA
SET id_Paciente = ?, tipo_Consulta = ?, fecha_hora_Consulta = ?,
    estado_proceso_Consulta = ?, fecha_fin_Consulta = ?, motivo_Consulta = ?
WHERE id_Consulta = ?;
```

Las relaciones y bloques clínicos se guardan en tablas asociadas:

```sql
INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta)
VALUES (?, ?);

INSERT INTO ANAMNESIS (
    id_Consulta, sanitaria_Anamnesis, ambiental_Anamnesis,
    remota_fisiologica_Anamnesis, remota_patologica_Anamnesis,
    proxima_fisiologica_Anamnesis, proxima_patologica_Anamnesis
)
VALUES (?, ?, ?, ?, ?, ?, ?);

INSERT INTO EXAMEN_GENERAL (id_Consulta)
VALUES (?);

INSERT INTO EXAMEN_EQUINO (id_ExamenG, tipo_ExamenE)
VALUES (?, ?);

INSERT INTO `RESEÑA` (id_ExamenG, archivoURL_RESEÑA)
VALUES (?, ?);

INSERT INTO DIAGNOSTICO (
    id_Consulta, tipo_Diagnostico, descripcion_Diagnostico
)
VALUES (?, ?, ?);

INSERT INTO PARACLINICO (
    id_Consulta, tipo_Paraclinico, resultado_Paraclinico,
    archivoURL_Paraclinico
)
VALUES (?, ?, ?, ?);

INSERT INTO PRONOSTICO (id_Consulta, tipo_Pronostico)
VALUES (?, ?);

INSERT INTO TRATAMIENTO (id_Consulta, descripcion_Tratamiento)
VALUES (?, ?);
```

Al editar, se eliminan los detalles anteriores relacionados con la consulta:

```sql
DELETE FROM EXAMEN_EQUINO WHERE id_ExamenG = ?;
DELETE FROM USUARIO_CONSULTA WHERE id_Consulta = ?;
DELETE FROM ANAMNESIS WHERE id_Consulta = ?;
DELETE FROM DIAGNOSTICO WHERE id_Consulta = ?;
DELETE FROM TRATAMIENTO WHERE id_Consulta = ?;
DELETE FROM PRONOSTICO WHERE id_Consulta = ?;
DELETE FROM PARACLINICO WHERE id_Consulta = ?;
```

### Validar participantes

Comprueba que el usuario exista, tenga el rol requerido y esté activo.

```sql
SELECT id_Usuario
FROM USUARIO
WHERE id_Usuario = ?
  AND LOWER(TRIM(rol_Usuario)) = ?
  AND LOWER(TRIM(estado_Usuario)) = 'activo'
LIMIT 1;
```

### Cambiar el estado de una consulta

```sql
UPDATE CONSULTA
SET estado_proceso_Consulta = ?,
    fecha_fin_Consulta = CASE
        WHEN ? = 'Finalizada' THEN CURDATE()
        ELSE NULL
    END
WHERE id_Consulta = ?;
```

### Buscar consultas clínicas

Permite combinar filtros de texto, estado, tipo y paciente.

```sql
SELECT c.id_Consulta, c.tipo_Consulta, c.estado_proceso_Consulta,
       c.fecha_hora_Consulta, p.nombre_Paciente, t.nombre_Tutor
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE (? = '' OR p.nombre_Paciente LIKE ?
       OR p.numchip_Paciente LIKE ?
       OR t.nombre_Tutor LIKE ?
       OR t.documento_Tutor LIKE ?)
  AND (? = '' OR LOWER(TRIM(c.estado_proceso_Consulta)) = LOWER(?))
  AND (? = '' OR LOWER(TRIM(c.tipo_Consulta)) = LOWER(?))
  AND (? = 0 OR c.id_Paciente = ?)
ORDER BY c.fecha_hora_Consulta DESC
LIMIT 100;
```

### Historial clínico y detalle

El historial obtiene primero la consulta principal y después carga, por separado, las tablas `ANAMNESIS`, `EXAMEN_GENERAL`, `EXAMEN_EQUINO`, `RESEÑA`, `DIAGNOSTICO`, `TRATAMIENTO`, `PARACLINICO`, `PRONOSTICO`, `USUARIO_CONSULTA` y `HOSPITALIZACION`. Todas usan `id_Consulta` como clave de búsqueda.

También existen consultas para descargar o localizar archivos adjuntos:

```sql
SELECT archivoURL_RESEÑA
FROM `RESEÑA`
WHERE id_Reseña = ?
LIMIT 1;

SELECT archivoURL_Paraclinico
FROM PARACLINICO
WHERE id_Paraclinico = ?
LIMIT 1;
```

### Resúmenes y próximos controles

```sql
SELECT
    (SELECT COUNT(*) FROM CONSULTA) AS total,
    (SELECT COUNT(*) FROM CONSULTA
     WHERE LOWER(TRIM(estado_proceso_Consulta)) = 'en curso') AS en_curso,
    (SELECT COUNT(*) FROM CONSULTA
     WHERE LOWER(TRIM(estado_proceso_Consulta)) = 'pendiente') AS pendientes,
    (SELECT COUNT(*) FROM HOSPITALIZACION
     WHERE fecha_egreso_Hospitalizacion IS NULL
       AND LOWER(TRIM(estado_Hospitalizacion)) = 'activa') AS hospitalizaciones;

SELECT p.nombre_Paciente AS paciente,
       c.fecha_proximo_control_Consulta AS fecha,
       c.motivo_Consulta AS motivo
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
WHERE c.fecha_proximo_control_Consulta >= CURDATE()
ORDER BY c.fecha_proximo_control_Consulta
LIMIT 10;
```

## 7. Dashboard

Archivo: `backend/Models/DashboardModel.php`

### Consultas del día

Muestra las consultas dentro del intervalo `[00:00, 24:00)` de una fecha seleccionada.

```sql
SELECT c.id_Consulta AS id_consulta,
       c.fecha_hora_Consulta AS fecha_hora,
       c.tipo_Consulta AS tipo,
       c.estado_proceso_Consulta AS estado,
       c.motivo_Consulta AS motivo,
       p.nombre_Paciente AS nombre_paciente,
       t.nombre_Tutor AS nombre_tutor
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE c.fecha_hora_Consulta >= ?
  AND c.fecha_hora_Consulta < ?
ORDER BY c.fecha_hora_Consulta ASC;
```

### Consultas incompletas y próximas

```sql
SELECT COUNT(*) AS cantidad
FROM CONSULTA
WHERE LOWER(TRIM(estado_proceso_Consulta)) IN ('pendiente', 'en curso');

SELECT c.id_Consulta AS id_consulta,
       c.fecha_hora_Consulta AS fecha_hora,
       c.tipo_Consulta AS tipo,
       c.estado_proceso_Consulta AS estado,
       c.motivo_Consulta AS motivo,
       p.nombre_Paciente AS nombre_paciente,
       t.nombre_Tutor AS nombre_tutor
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE LOWER(TRIM(c.estado_proceso_Consulta)) IN ('pendiente', 'en curso')
ORDER BY c.fecha_hora_Consulta DESC;

SELECT c.id_Consulta AS id_consulta,
       c.fecha_hora_Consulta AS fecha_hora,
       c.tipo_Consulta AS tipo,
       c.estado_proceso_Consulta AS estado,
       c.motivo_Consulta AS motivo,
       p.nombre_Paciente AS nombre_paciente,
       t.nombre_Tutor AS nombre_tutor
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE c.fecha_hora_Consulta >= NOW()
  AND COALESCE(LOWER(TRIM(c.estado_proceso_Consulta)), '')
      NOT IN ('finalizada', 'cancelada', 'cancelado')
ORDER BY c.fecha_hora_Consulta ASC;
```

### Pacientes hospitalizados activos

```sql
SELECT h.fecha_ingreso_Hospitalizacion AS fecha_ingreso,
       p.nombre_Paciente AS nombre_paciente,
       t.nombre_Tutor AS nombre_tutor,
       h.motivo_Hospitalizacion AS motivo,
       COALESCE(
           NULLIF(TRIM(CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario)), ''),
           'Sin asignar'
       ) AS responsable,
       h.estado_Hospitalizacion AS estado
FROM HOSPITALIZACION h
INNER JOIN PACIENTE p ON p.id_Paciente = h.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
WHERE h.fecha_egreso_Hospitalizacion IS NULL
  AND LOWER(TRIM(h.estado_Hospitalizacion)) = 'activa'
ORDER BY h.fecha_ingreso_Hospitalizacion ASC;
```

## 8. Hospitalizaciones

Archivo: `backend/Models/HospitalizacionModel.php`

### Listar internaciones activas

```sql
SELECT h.id_Hospitalizacion, h.id_Paciente, h.id_Consulta,
       h.fecha_ingreso_Hospitalizacion, h.motivo_Hospitalizacion,
       h.estado_Hospitalizacion,
       p.nombre_Paciente,
       t.nombre_Tutor,
       CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS responsable,
       CONCAT_WS(
           ' · ', c.tipo_Consulta,
           DATE_FORMAT(c.fecha_hora_Consulta, '%d/%m/%Y %H:%i')
       ) AS consulta
FROM HOSPITALIZACION h
INNER JOIN PACIENTE p ON p.id_Paciente = h.id_Paciente
LEFT JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
LEFT JOIN CONSULTA c ON c.id_Consulta = h.id_Consulta
WHERE h.fecha_egreso_Hospitalizacion IS NULL
  AND LOWER(TRIM(h.estado_Hospitalizacion)) = 'activa'
ORDER BY h.fecha_ingreso_Hospitalizacion DESC;
```

### Historial por paciente

```sql
SELECT h.id_Hospitalizacion, h.id_Consulta,
       h.fecha_ingreso_Hospitalizacion, h.fecha_egreso_Hospitalizacion,
       h.motivo_Hospitalizacion, h.estado_Hospitalizacion,
       CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS responsable,
       c.tipo_Consulta, c.fecha_hora_Consulta
FROM HOSPITALIZACION h
LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
LEFT JOIN CONSULTA c ON c.id_Consulta = h.id_Consulta
WHERE h.id_Paciente = ?
ORDER BY h.fecha_ingreso_Hospitalizacion DESC;
```

### Registrar ingreso y alta

```sql
INSERT INTO HOSPITALIZACION (
    id_Paciente, id_Consulta, id_Usuario,
    fecha_ingreso_Hospitalizacion, motivo_Hospitalizacion,
    estado_Hospitalizacion
)
VALUES (?, ?, ?, NOW(), ?, 'Activa');

UPDATE HOSPITALIZACION
SET fecha_egreso_Hospitalizacion = NOW(),
    estado_Hospitalizacion = 'Finalizada'
WHERE id_Hospitalizacion = ?
  AND fecha_egreso_Hospitalizacion IS NULL
  AND LOWER(TRIM(estado_Hospitalizacion)) = 'activa';
```

Las validaciones impiden ingresar dos veces al mismo paciente, requieren que la consulta pertenezca al paciente y bloquean registros existentes con `FOR UPDATE` durante la transacción.

## 9. Notificaciones

Archivo: `backend/Models/NotificacionModel.php`

La aplicación utiliza la vista SQL `vw_alertas_consultas_atrasadas`, definida en `database/policlinico_db.sql`. Esta vista devuelve consultas pendientes o en curso cuya fecha ya pasó.

```sql
CREATE ALGORITHM=UNDEFINED
SQL SECURITY DEFINER
VIEW vw_alertas_consultas_atrasadas AS
SELECT uc.id_Usuario AS id_usuario,
       c.id_Consulta,
       c.fecha_hora_Consulta,
       c.estado_proceso_Consulta,
       c.tipo_Consulta,
       c.motivo_Consulta,
       p.nombre_Paciente,
       t.nombre_Tutor
FROM USUARIO_CONSULTA uc
INNER JOIN CONSULTA c ON c.id_Consulta = uc.id_Consulta
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE c.fecha_hora_Consulta < CURRENT_TIMESTAMP()
  AND LOWER(TRIM(c.estado_proceso_Consulta)) IN ('pendiente', 'en curso');
```

La vista se consulta para mostrar alertas al usuario asociado a la consulta:

```sql
SELECT id_Consulta AS id_consulta,
       fecha_hora_Consulta AS fecha_hora,
       estado_proceso_Consulta AS estado,
       tipo_Consulta AS tipo,
       motivo_Consulta AS motivo,
       nombre_Paciente AS nombre_paciente,
       nombre_Tutor AS nombre_tutor
FROM vw_alertas_consultas_atrasadas
WHERE id_usuario = ?
ORDER BY fecha_hora_Consulta ASC
LIMIT ?;
```

`LIMIT` se incluye solamente cuando el componente solicita un máximo de resultados; el navbar usa el límite y el listado completo omite esta cláusula.

## 10. Usuarios activos por rol

Archivo: `backend/Models/ConsultaModel.php`

Se utiliza para completar los participantes disponibles en las consultas clínicas.

```sql
SELECT id_Usuario AS id,
       cedula_Usuario AS cedula,
       CONCAT_WS(' ', nombre_Usuario, apellido_Usuario) AS nombre
FROM USUARIO
WHERE LOWER(TRIM(rol_Usuario)) = ?
  AND LOWER(TRIM(estado_Usuario)) = 'activo'
ORDER BY apellido_Usuario, nombre_Usuario;
```

La aplicación usa esta consulta para obtener estudiantes y profesores activos por separado.

## Convenciones y consideraciones

- Las operaciones críticas usan transacciones: creación y reprogramación de turnos, alta de pacientes, actualización de fichas, creación o edición de consultas y registro de hospitalizaciones.
- Las validaciones adicionales usan `SELECT` para comprobar existencia, duplicados, estados, pertenencia de registros y especies válidas.
- Las comparaciones funcionales de estados y roles suelen usar `LOWER(TRIM(...))`, por lo que esas consultas toleran mayúsculas y espacios adicionales.
