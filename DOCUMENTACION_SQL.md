# Consultas SQL ejecutadas por la aplicación

Este documento describe las consultas SQL reales que utilizan los modelos PHP mediante `mysqli::prepare()`. Los signos `?` representan parámetros enlazados mediante `bind_param()`; no se concatenan valores directamente en el SQL.

La conexión se configura en `backend/Core/Database.php` y usa una base MariaDB con `utf8mb4`. Al abrir la conexión, la aplicación ejecuta:

#### Consulta SQL N.º 1 - Configuración de zona horaria de la conexión

**Archivo de origen:**

- `backend/Core/Database.php`

**Descripción funcional:** Configura la zona horaria de la sesión MariaDB para que funciones temporales como `NOW()` y `CURDATE()` se expresen en hora local.

**Descripción técnica:** Es una sentencia ejecutada directamente con `mysqli::query()`. El desplazamiento se calcula en `Database.php` y se incorpora como literal de configuración, por ejemplo `-03:00`.

**Contexto de utilización:** Inicialización de la conexión compartida por todos los modelos.

**Sentencia SQL:**

```sql
SET time_zone = '<desplazamiento-horario-local>'
```

## 1. Autenticación y usuarios

#### Consulta SQL N.º 2 - Buscar una cuenta por cédula

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Descripción funcional:** Permite iniciar sesión y recuperar los datos almacenados de la cuenta.

**Descripción técnica:** `USUARIO` se filtra por `cedula_Usuario = ?`; `LIMIT 1` garantiza devolver una sola coincidencia y el parámetro contiene la cédula validada.
**Contexto de utilización:** Autenticación de usuarios.

**Sentencia SQL:**

```sql
SELECT id_Usuario, cedula_Usuario, nombre_Usuario, apellido_Usuario,
       rol_Usuario, email_Usuario, passw_Usuario, estado_Usuario
FROM USUARIO
WHERE cedula_Usuario = ?
LIMIT 1;
```

#### Consulta SQL N.º 3 - Registrar el último acceso

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Descripción funcional:** Actualiza el campo de último inicio de sesión después de autenticar al usuario.

**Descripción técnica:** `USUARIO` se identifica por `id_Usuario = ?` y `NOW()` registra la hora actual de la sesión de base de datos en `lastlogin_Usuario`.

**Contexto de utilización:** Autenticación de usuarios.

**Sentencia SQL:**

```sql
UPDATE USUARIO
SET lastlogin_Usuario = NOW()
WHERE id_Usuario = ?;
```

#### Consulta SQL N.º 4 - Listar usuarios activos o inactivos

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Descripción funcional:** Permite administrar cuentas según su estado y filtrar por cédula, nombre o apellido.

**Descripción técnica:** La tabla `USUARIO` se filtra por el estado activo o inactivo. Las variantes de búsqueda usan `LIKE` sobre cédula, nombre, apellido y nombre completo, y reciben el mismo patrón en cuatro parámetros.

**Contexto de utilización:** Administración de usuarios.

**Sentencia SQL:**

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

Variante con usuarios inactivos y la misma búsqueda:

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
AND estado_Usuario = "inactivo";
```

La variante con `estado_Usuario = 'inactivo'` muestra las cuentas desactivadas.
#### Consulta SQL N.º 5 - Crear una cuenta

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Descripción funcional:** Registra una cuenta con contraseña ya procesada mediante `password_hash()` en el controlador.

**Descripción técnica:** Se inserta en `USUARIO` cédula, nombre, apellido, rol, correo y hash. `NOW()` llena `lastlogin_Usuario` y `dateupdate_Usuario`; no intervienen otras tablas.

**Contexto de utilización:** Administración de usuarios.

**Sentencia SQL:**

```sql
INSERT INTO USUARIO (
    cedula_Usuario, nombre_Usuario, apellido_Usuario, rol_Usuario,
    email_Usuario, passw_Usuario, lastlogin_Usuario, dateupdate_Usuario
)
VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW());
```

#### Consulta SQL N.º 6 - Modificar una cuenta

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Descripción funcional:** Edita los datos de una cuenta y conserva o reemplaza la contraseña según la solicitud.

**Descripción técnica:** Se actualiza `USUARIO` por la cédula anterior, reemplazando cédula, nombre, apellido, rol, correo y contraseña. `dateupdate_Usuario` recibe `NOW()`.

**Contexto de utilización:** Administración de usuarios.

**Sentencia SQL:**

```sql
UPDATE USUARIO
SET cedula_Usuario = ?, nombre_Usuario = ?, apellido_Usuario = ?,
    rol_Usuario = ?, email_Usuario = ?, passw_Usuario = ?,
    dateupdate_Usuario = NOW()
WHERE cedula_Usuario = ?;
```

#### Consulta SQL N.º 7 - Activar o desactivar una cuenta

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Descripción funcional:** Cambia el estado funcional de la cuenta sin eliminar su historial.

**Descripción técnica:** `USUARIO` cambia `estado_Usuario` según el primer parámetro y selecciona el registro por `cedula_Usuario` con el segundo parámetro.

**Contexto de utilización:** Administración de usuarios.

**Sentencia SQL:**

```sql
UPDATE USUARIO
SET estado_Usuario = ?
WHERE cedula_Usuario = ?;
```

#### Consulta SQL N.º 8 - Eliminación lógica y borrado legado de usuario
**Descripción funcional:** Cambia o elimina cuentas según el método invocado.

**Descripción técnica:** La operación normal no borra físicamente y usa `cambiarStatusUsuario()` con `UPDATE USUARIO`. Existe además un método legado `borrarUsuario()` que ejecuta `DELETE` por cédula, pero no es el flujo administrativo documentado ni se usa para desactivar cuentas.

**Archivo de origen:**

- `backend/Models/UsuarioModel.php`

**Contexto de utilización:** Administración de usuarios; el borrado físico queda en el método legado.

**Sentencia SQL:**

```sql
DELETE FROM USUARIO
WHERE cedula_Usuario = ?
```

## 2. Tutores

#### Consulta SQL N.º 9 - Obtener un tutor

**Archivo de origen:**

- `backend/Models/TutorModel.php`

**Descripción funcional:** Carga los datos de un tutor en el formulario de edición.

**Descripción técnica:** `TUTOR` se busca por `id_Tutor = ?` y se limita a una fila para devolver los datos editables del tutor.

**Contexto de utilización:** Centro de tutores.

**Sentencia SQL:**

```sql
SELECT id_Tutor, documento_Tutor, nombre_Tutor, telefono_Tutor,
       direccion_Tutor, email_Tutor, notas_Tutor
FROM TUTOR
WHERE id_Tutor = ?
LIMIT 1;
```

#### Consulta SQL N.º 10 - Buscar tutores y contar sus pacientes

**Archivo de origen:**

- `backend/Models/TutorModel.php`

**Descripción funcional:** Permite buscar por nombre, documento o teléfono y muestra cuántos pacientes tiene cada tutor.

**Descripción técnica:** Inicia en `TUTOR t`, usa `LEFT JOIN` con `PACIENTE p`, cuenta pacientes por tutor con `COUNT`, agrupa los datos mostrados, filtra por nombre/documento/teléfono y limita a 100 resultados.

**Contexto de utilización:** Centro de tutores.

**Sentencia SQL:**

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

#### Consulta SQL N.º 11 - Validar y crear un tutor

**Archivo de origen:**

- `backend/Models/TutorModel.php`

**Descripción funcional:** Antes del alta se verifica que el documento no esté registrado.

**Descripción técnica:** Primero busca `TUTOR.documento_Tutor = ?` para impedir duplicados. Después inserta documento, nombre, teléfono, dirección, correo y notas mediante seis parámetros.

**Contexto de utilización:** Alta de tutores.

**Sentencia SQL:**

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

#### Consulta SQL N.º 12 - Actualizar un tutor

**Archivo de origen:**

- `backend/Models/TutorModel.php`

**Descripción funcional:** También verifica que otro tutor no utilice el nuevo documento.

**Descripción técnica:** Comprueba que otro registro no tenga el nuevo documento y después actualiza los seis campos editables de `TUTOR` filtrando por `id_Tutor = ?`.

**Contexto de utilización:** Edición de tutores.

**Sentencia SQL:**

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

#### Consulta SQL N.º 13 - Verificar documento de tutor distinto del actual
**Descripción funcional:** Impide que al editar un paciente o tutor se reutilice el documento de otra persona.

**Descripción técnica:** Busca en `TUTOR` por documento y excluye el identificador del registro que está siendo editado. Recibe documento y identificador, y limita a una fila.

**Archivo de origen:**

- `backend/Models/TutorModel.php`
- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Validación de unicidad documental en formularios de tutor y edición de paciente.

**Sentencia SQL:**

```sql
SELECT id_Tutor
FROM TUTOR
WHERE documento_Tutor = ? AND id_Tutor <> ?
LIMIT 1
```

## 3. Pacientes y razas

#### Consulta SQL N.º 14 - Catálogos de especies y razas

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Descripción funcional:** Recupera especies y razas disponibles para poblar los selectores de alta y edición de pacientes.

**Descripción técnica:** `ESPECIE` se lista por nombre. `RAZA` se relaciona con `ESPECIE` por `id_Especie`, filtra por especie recibida o equino normalizado y ordena por nombre.

**Contexto de utilización:** Alta y edición de pacientes.

**Sentencia SQL:**

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
#### Consulta SQL N.º 15 - Cargar la ficha de un paciente

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Descripción funcional:** Recupera la ficha completa de un paciente y los datos asociados de tutor, raza y especie para visualizar o editar su registro.

**Descripción técnica:** Parte de `PACIENTE`, relaciona `TUTOR` mediante `id_Tutor` y raza/especie mediante `LEFT JOIN`; filtra por `id_Paciente = ?` y limita a una ficha.

**Contexto de utilización:** Ficha y edición de pacientes.

**Sentencia SQL:**

```sql
SELECT
    p.id_Paciente,
    p.id_Tutor,
    p.id_Raza,
    p.numchip_Paciente,
    p.nombre_Paciente,
    p.fecha_nac_Paciente,
    p.edad_Paciente,
    p.sexo_Paciente,
    p.color_Paciente,
    t.documento_Tutor,
    t.nombre_Tutor,
    t.telefono_Tutor,
    t.direccion_Tutor,
    t.email_Tutor,
    t.notas_Tutor,
    e.nombre_Especie
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE p.id_Paciente = ?
LIMIT 1
```

#### Consulta SQL N.º 16 - Crear paciente con tutor

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Descripción funcional:** La operación se ejecuta dentro de una transacción. Si el tutor ya existe, reutiliza su identificador; si no existe, crea primero el tutor y después el paciente.

**Descripción técnica:** Busca o crea `TUTOR` por documento dentro de una transacción y después inserta `PACIENTE` con tutor, raza y datos personales; usa `insert_id` para obtener ambos identificadores.

**Contexto de utilización:** Alta de pacientes y tutores.

**Sentencia SQL:**

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
#### Consulta SQL N.º 17 - Actualizar un paciente

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Descripción funcional:** Actualiza los datos del tutor vinculado y los datos editables del paciente, preservando la operación dentro de una transacción.

**Descripción técnica:** Actualiza `TUTOR` por `id_Tutor` y `PACIENTE` por `id_Paciente` dentro de una transacción. Validaciones previas verifican chip disponible y compatibilidad de raza/especie.

**Contexto de utilización:** Edición de pacientes y tutores.

**Sentencia SQL:**

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

#### Consulta SQL N.º 18 - Buscar pacientes

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Descripción funcional:** Busca pacientes por nombre o número de chip y devuelve sus datos principales junto con el tutor para centros y formularios de selección.

**Descripción técnica:** Une `PACIENTE p` con `TUTOR t`, busca nombre o chip mediante `LIKE`, recibe el patrón en dos parámetros, ordena por nombre y limita a 50 filas.

**Contexto de utilización:** Centro de pacientes y selección de registros.

**Sentencia SQL:**

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

#### Consulta SQL N.º 19 - Listar todas las razas con su especie
**Descripción funcional:** Recupera el catálogo completo de razas y añade el nombre de la especie a cada fila.

**Descripción técnica:** Hace un `INNER JOIN` desde `RAZA` hacia `ESPECIE` mediante `id_Especie`. El alias `especie` corresponde a `nombre_Especie`. Ordena primero por especie y después por raza. No recibe parámetros.

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Catálogos y administración relacionados con el formulario de pacientes.

**Sentencia SQL:**

```sql
SELECT r.id_Raza, r.nombre_Raza, e.nombre_Especie AS especie
FROM RAZA r
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
ORDER BY e.nombre_Especie, r.nombre_Raza
```

#### Consulta SQL N.º 20 - Cargar ficha completa de edición de paciente
**Descripción funcional:** Obtiene los datos editables de un paciente y los datos asociados de su tutor y especie.

**Descripción técnica:** Comienza en `PACIENTE`, relaciona mediante `INNER JOIN` al tutor y mediante `LEFT JOIN` la raza y la especie. La consulta permite que raza o especie sean nulas sin perder la ficha del paciente. Recibe `id_Paciente` como parámetro entero y limita el resultado a una fila.

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Precarga del formulario de edición de pacientes.

**Sentencia SQL:**

```sql
SELECT
    p.id_Paciente,
    p.id_Tutor,
    p.id_Raza,
    p.numchip_Paciente,
    p.nombre_Paciente,
    p.fecha_nac_Paciente,
    p.edad_Paciente,
    p.sexo_Paciente,
    p.color_Paciente,
    t.documento_Tutor,
    t.nombre_Tutor,
    t.telefono_Tutor,
    t.direccion_Tutor,
    t.email_Tutor,
    t.notas_Tutor,
    e.nombre_Especie
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE p.id_Paciente = ?
LIMIT 1
```

#### Consulta SQL N.º 21 - Obtener raza por identificador y especie
**Descripción funcional:** Verifica que una raza exista y que pertenezca a la especie de trabajo seleccionada.

**Descripción técnica:** Relaciona `RAZA` con `ESPECIE`; recibe el identificador de raza y el nombre de especie. El resultado limita a una fila y se usa para rechazar combinaciones incompatibles.

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Validación al crear o actualizar pacientes.

**Sentencia SQL:**

```sql
SELECT r.id_Raza
FROM RAZA r
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE r.id_Raza = ? AND e.nombre_Especie = ?
LIMIT 1
```

#### Consulta SQL N.º 22 - Obtener raza predeterminada por especie
**Descripción funcional:** Localiza la raza predeterminada de una especie, definida como `sin especificar` para equinos o `mestizo` para caninos y felinos.

**Descripción técnica:** Relaciona `RAZA` y `ESPECIE`, recibe especie y nombre normalizado de raza, y limita a una coincidencia. No aplica normalización al nombre de especie, pero sí compara la raza con `LOWER(TRIM(...))`.

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Alta y edición de pacientes cuando no se envía una raza explícita.

**Sentencia SQL:**

```sql
SELECT r.id_Raza
FROM RAZA r
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE e.nombre_Especie = ?
  AND LOWER(TRIM(r.nombre_Raza)) = ?
LIMIT 1
```

#### Consulta SQL N.º 23 - Validar disponibilidad de chip
**Descripción funcional:** Comprueba que el número de chip no esté asociado a otro paciente.

**Descripción técnica:** Consulta `PACIENTE` por `numchip_Paciente`. En edición agrega `AND id_Paciente <> ?` para excluir el propio paciente. Es una consulta dinámica: el alta enlazado solo el chip; la edición enlaza chip e identificador.

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Validación previa a alta y actualización de pacientes.

**Sentencia SQL:**

```sql
SELECT id_Paciente FROM PACIENTE WHERE numchip_Paciente = ?
```

Variante usada al editar:

```sql
SELECT id_Paciente FROM PACIENTE
WHERE numchip_Paciente = ? AND id_Paciente <> ?
LIMIT 1
```

#### Consulta SQL N.º 24 - Listar pacientes por tutor
**Descripción funcional:** Muestra hasta 50 pacientes asociados a un tutor determinado.

**Descripción técnica:** Consulta `PACIENTE` por `id_Tutor`, devuelve nombre e identificador de chip, y ordena alfabéticamente por nombre. El parámetro es el identificador entero del tutor.

**Archivo de origen:**

- `backend/Models/PacienteModel.php`

**Contexto de utilización:** Centro de tutores y selección de pacientes vinculados.

**Sentencia SQL:**

```sql
SELECT
    p.id_Paciente AS id,
    p.nombre_Paciente AS nombre,
    p.numchip_Paciente AS chip
FROM PACIENTE p
WHERE p.id_Tutor = ?
ORDER BY p.nombre_Paciente ASC
LIMIT 50
```

## 4. Servicios

#### Consulta SQL N.º 25 - Administrar el catálogo

**Archivo de origen:**

- `backend/Models/Servicio.php`

**Descripción funcional:** Lista los servicios disponibles para administración y presenta uno por su identificador.

**Descripción técnica:** Lista `SERVICIO` con un `ORDER BY` por nombre e ID o obtiene una fila por `id_Servicio = ?` mediante `LIMIT 1`.

**Contexto de utilización:** Administración de servicios.

**Sentencia SQL:**

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

#### Consulta SQL N.º 26 - Crear, actualizar y eliminar servicios

**Archivo de origen:**

- `backend/Models/Servicio.php`

**Descripción funcional:** Agrupa la creación, modificación y eliminación de registros del catálogo de servicios de la clínica.

**Descripción técnica:** Inserta `id_Usuario` y los datos de `SERVICIO`, actualiza los campos editables por `id_Servicio` o elimina por ese identificador.

**Contexto de utilización:** Administración de servicios.

**Sentencia SQL:**

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

#### Consulta SQL N.º 27 - Listar turnos por período

**Archivo de origen:**

- `backend/Models/AgendaModel.php`

**Descripción funcional:** Incluye paciente, tutor, especie y los profesionales asociados a la consulta.

**Descripción técnica:** Une `CONSULTA`, `PACIENTE`, `TUTOR`, `RAZA` y `ESPECIE`; agrupa usuarios asociados con un subselect sobre `USUARIO_CONSULTA` y `USUARIO`, y aplica el rango `[desde, hasta)`.

**Contexto de utilización:** Agenda y turnos.

**Sentencia SQL:**

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

#### Consulta SQL N.º 28 - Crear un turno

**Archivo de origen:**

- `backend/Models/AgendaModel.php`

**Descripción funcional:** Se crea la consulta en estado `Pendiente` y se vincula con un usuario dentro de una transacción.

**Descripción técnica:** Inserta `CONSULTA` como `Pendiente` y luego vincula el usuario a la consulta mediante `USUARIO_CONSULTA`, dentro de una transacción.

**Contexto de utilización:** Agenda y creación de turnos.

**Sentencia SQL:**

```sql
INSERT INTO CONSULTA (
    id_Paciente, tipo_Consulta, fecha_hora_Consulta,
    estado_proceso_Consulta, motivo_Consulta
)
VALUES (?, ?, ?, 'Pendiente', ?);

INSERT INTO USUARIO_CONSULTA (id_Usuario, id_Consulta)
VALUES (?, ?);
```

#### Consulta SQL N.º 29 - Reprogramar o cancelar

**Archivo de origen:**

- `backend/Models/AgendaModel.php`

**Descripción funcional:** Solo permite modificar turnos cuyo estado normalizado sea `pendiente`.

**Descripción técnica:** Bloquea o modifica `CONSULTA` solo si el estado normalizado es `pendiente`; reprogramar cambia la fecha y cancelar asigna estado `Cancelada` y egreso nulo.

**Contexto de utilización:** Agenda y gestión de turnos.

**Sentencia SQL:**

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

#### Consulta SQL N.º 30 - Validar usuario y paciente para crear turno
**Descripción funcional:** Comprueba la existencia de los registros involucrados antes de insertar una cita.

**Descripción técnica:** La validación de usuario bloquea la fila de `USUARIO` con `FOR UPDATE`. La validación de paciente usa `PACIENTE`, `RAZA` y `ESPECIE`, exige una especie equina, canina o felina y también bloquea la fila. Ambos resultados deben existir antes de crear la consulta.

**Archivo de origen:**

- `backend/Models/AgendaModel.php`

**Contexto de utilización:** Validaciones transaccionales de `crearTurno()`.

**Sentencia SQL:**

```sql
SELECT id_Usuario FROM USUARIO WHERE id_Usuario = ? FOR UPDATE
```

```sql
SELECT p.id_Paciente
FROM PACIENTE p
INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE p.id_Paciente = ?
  AND e.nombre_Especie IN ('Equino', 'Canino', 'Felino')
FOR UPDATE
```

## 6. Consultas clínicas

#### Consulta SQL N.º 31 - Listar pacientes disponibles por especie

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Lista los pacientes que pertenecen a la especie seleccionada para permitir elegir uno al crear una consulta clínica.

**Descripción técnica:** Une `PACIENTE`, `TUTOR`, `RAZA` y `ESPECIE`, filtra por especie normalizada recibida y ordena los pacientes por nombre.

**Contexto de utilización:** Formulario de consultas clínicas.

**Sentencia SQL:**

```sql
SELECT p.id_Paciente, p.nombre_Paciente, p.numchip_Paciente,
       t.nombre_Tutor, e.nombre_Especie AS especie
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE e.nombre_Especie = ?
ORDER BY p.nombre_Paciente ASC;
```

#### Consulta SQL N.º 32 - Consultar datos básicos de una consulta editable

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Verifica y recupera los datos básicos necesarios para habilitar la edición de una consulta pendiente o en curso.

**Descripción técnica:** Consulta `CONSULTA` por `id_Consulta`, normaliza el estado con `LOWER(TRIM(...))` y solo admite `pendiente` o `en curso`. Devuelve tipo y estado y limita el resultado a una fila.

**Contexto de utilización:** Control previo de edición de consultas clínicas.

**Sentencia SQL:**

```sql
SELECT tipo_Consulta, estado_proceso_Consulta
FROM CONSULTA
WHERE id_Consulta = ?
  AND LOWER(TRIM(estado_proceso_Consulta)) IN ('pendiente', 'en curso')
LIMIT 1
```

#### Consulta SQL N.º 33 - Crear o editar una consulta clínica

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** La operación reemplaza los datos clínicos asociados al editar una consulta pendiente o en curso.

**Descripción técnica:** Inserta o actualiza `CONSULTA` y reemplaza en edición las asociaciones y bloques clínicos de usuarios, anamnesis, examen, diagnósticos, paraclínicos, pronóstico y tratamiento.

**Contexto de utilización:** Creación y edición clínica de consultas.

**Sentencia SQL:**

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

INSERT INTO EXAMEN_EQUINO
    (id_ExamenG, temperatura_cascos_ExamenEq, pulso_digital_ExamenEq,
     senos_ExamenEq, csd_ExamenEq, cid_ExamenEq, csi_ExamenEq, cii_ExamenEq)
VALUES (?, ?, ?, ?, ?, ?, ?, ?);

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

#### Consulta SQL N.º 34 - Validar participantes

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Comprueba que el usuario exista, tenga el rol requerido y esté activo.

**Descripción técnica:** Busca en `USUARIO` por ID, rol normalizado recibido y estado activo normalizado, usando `LIMIT 1`.

**Contexto de utilización:** Asignación de estudiantes y profesores a consultas.

**Sentencia SQL:**

```sql
SELECT id_Usuario
FROM USUARIO
WHERE id_Usuario = ?
  AND LOWER(TRIM(rol_Usuario)) = ?
  AND LOWER(TRIM(estado_Usuario)) = 'activo'
LIMIT 1;
```

#### Consulta SQL N.º 35 - Cambiar el estado de una consulta

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Actualiza el estado operativo de una consulta y establece o limpia su fecha de finalización según el estado seleccionado.

**Descripción técnica:** Actualiza `CONSULTA.estado_proceso_Consulta` por ID; si el estado es `Finalizada`, usa `CURDATE()` en `fecha_fin_Consulta`; en otros casos la deja nula.

**Contexto de utilización:** Seguimiento del estado de una consulta clínica.

**Sentencia SQL:**

```sql
UPDATE CONSULTA
SET estado_proceso_Consulta = ?,
    fecha_fin_Consulta = CASE
        WHEN ? = 'Finalizada' THEN CURDATE()
        ELSE NULL
    END
WHERE id_Consulta = ?;
```

#### Consulta SQL N.º 36 - Buscar consultas clínicas

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Permite combinar filtros de texto, estado, tipo y paciente.

**Descripción técnica:** Une `CONSULTA`, `PACIENTE` y `TUTOR`; aplica filtros opcionales de texto, estado, tipo y paciente y limita a 100 filas ordenadas por fecha descendente.

**Contexto de utilización:** Centro de consultas y filtros de listado.

**Sentencia SQL:**

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

#### Consulta SQL N.º 37 - Historial clínico y detalle

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** El historial obtiene primero la consulta principal y después carga, por separado, las tablas `ANAMNESIS`, `EXAMEN_GENERAL`, `EXAMEN_EQUINO`, `RESEÑA`, `DIAGNOSTICO`, `TRATAMIENTO`, `PARACLINICO`, `PRONOSTICO`, `USUARIO_CONSULTA` y `HOSPITALIZACION`. Todas usan `id_Consulta` como clave de búsqueda.

También existen consultas para descargar o localizar archivos adjuntos:

**Descripción técnica:** Carga primero `CONSULTA` con sus pacientes/tutores y después consulta por `id_Consulta` participantes y tablas clínicas y hospitalizaciones.

**Contexto de utilización:** Historial y detalle clínico del paciente.

**Sentencia SQL:**

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

#### Consulta SQL N.º 38 - Resúmenes y próximos controles

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Devuelve contadores globales del centro clínico y los próximos controles programados para la agenda.

**Descripción técnica:** Subconsultas cuentan total, estados y hospitalizaciones; otra consulta une `CONSULTA` y `PACIENTE`, filtra controles desde `CURDATE()` y limita a 10.

**Contexto de utilización:** Resumen del centro y controles programados.

**Sentencia SQL:**

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

#### Consulta SQL N.º 39 - Cargar borrador clínico editable
**Descripción funcional:** Recupera la consulta principal y su especie solo si la consulta está pendiente o en curso.

**Descripción técnica:** Relaciona `CONSULTA`, `PACIENTE`, `RAZA` y `ESPECIE`. Normaliza el estado con `LOWER(TRIM(...))` y restringe la edición a `pendiente` o `en curso`. Recibe el identificador de consulta y limita a una fila.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Precarga del formulario de nueva consulta o edición clínica.

**Sentencia SQL:**

```sql
SELECT
    c.id_Consulta,
    c.id_Paciente,
    c.tipo_Consulta,
    c.fecha_hora_Consulta,
    c.motivo_Consulta,
    c.estado_proceso_Consulta,
    e.nombre_Especie
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE c.id_Consulta = ?
  AND LOWER(TRIM(c.estado_proceso_Consulta)) IN ('pendiente', 'en curso')
LIMIT 1
```

#### Consulta SQL N.º 40 - Reconstruir anamnesis y examen al editar
**Descripción funcional:** Carga por separado los datos clínicos almacenados para volver a mostrarlos en el formulario de edición.

**Descripción técnica:** La anamnesis se obtiene de `ANAMNESIS` por `id_Consulta`, ordenada por ID y limitada a una fila. El examen general se une opcionalmente con `EXAMEN_EQUINO` mediante `id_ExamenG`, y cada tabla usa el identificador de consulta o examen correspondiente.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Reconstrucción del borrador clínico antes de reemplazar los bloques asociados.

**Sentencia SQL:**

```sql
SELECT sanitaria_Anamnesis, ambiental_Anamnesis,
       remota_fisiologica_Anamnesis, remota_patologica_Anamnesis,
       proxima_fisiologica_Anamnesis, proxima_patologica_Anamnesis
FROM ANAMNESIS WHERE id_Consulta = ? ORDER BY id_Anamnesis LIMIT 1
```

```sql
SELECT eg.peso_ExamenG, eg.CC_ExamenG, eg.TR_ExamenG, eg.FC_ExamenG,
       eg.FR_ExamenG, eg.TLLC_ExamenG, eg.sensorio_ExamenG, eg.facies_ExamenG,
       eg.piel_subcutaneo_ExamenG, eg.mucosas_ExamenG, eg.grandes_funciones_ExamenG,
       eg.actitudes_anomalas_ExamenG, eg.hidratacion_ExamenG,
       eg.ganglios_linfaticos_ExamenG, eg.aparentes_ExamenG,
       eq.temperatura_cascos_ExamenEq, eq.pulso_digital_ExamenEq,
       eq.senos_ExamenEq, eq.csd_ExamenEq, eq.cid_ExamenEq,
       eq.csi_ExamenEq, eq.cii_ExamenEq
FROM EXAMEN_GENERAL eg
LEFT JOIN EXAMEN_EQUINO eq ON eq.id_ExamenG = eg.id_ExamenG
WHERE eg.id_Consulta = ? ORDER BY eg.id_ExamenG LIMIT 1
```

#### Consulta SQL N.º 41 - Cargar participantes, diagnósticos y tratamientos editables
**Descripción funcional:** Recupera las asociaciones de participantes y los datos clínicos que deben reaparecer en una edición.

**Descripción técnica:** `USUARIO_CONSULTA` se une con `USUARIO` para conocer el rol; `DIAGNOSTICO`, `TRATAMIENTO`, `PRONOSTICO` y `PARACLINICO` se consultan por `id_Consulta`. Todas reciben el identificador de consulta y ordenan por su clave primaria o por la fecha definida por el código.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Precarga del formulario clínico editable.

**Sentencia SQL:**

```sql
SELECT uc.id_Usuario, LOWER(TRIM(u.rol_Usuario)) AS rol
FROM USUARIO_CONSULTA uc
INNER JOIN USUARIO u ON u.id_Usuario = uc.id_Usuario
WHERE uc.id_Consulta = ?
```

```sql
SELECT tipo_Diagnostico, descripcion_Diagnostico
FROM DIAGNOSTICO WHERE id_Consulta = ? ORDER BY id_Diagnostico
```

```sql
SELECT descripcion_Tratamiento
FROM TRATAMIENTO WHERE id_Consulta = ? ORDER BY id_Tratamiento
```

```sql
SELECT tipo_Pronostico
FROM PRONOSTICO WHERE id_Consulta = ? ORDER BY id_Pronostico
```

```sql
SELECT id_Paraclinico, tipo_Paraclinico, resultado_Paraclinico,
       archivoURL_Paraclinico
FROM PARACLINICO WHERE id_Consulta = ? ORDER BY id_Paraclinico
```

#### Consulta SQL N.º 42 - Localizar reseñas asociadas al examen de una consulta
**Descripción funcional:** Obtiene los identificadores de las reseñas clínicas de una consulta editable o consultable.

**Descripción técnica:** Une `RESEÑA` con `EXAMEN_GENERAL` mediante `id_ExamenG`, filtra por `eg.id_Consulta` y ordena por identificador de reseña.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Reconstrucción de archivos adjuntos de reseña y detalle clínico.

**Sentencia SQL:**

```sql
SELECT r.id_Reseña AS id
FROM `RESEÑA` r
INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = r.id_ExamenG
WHERE eg.id_Consulta = ? ORDER BY r.id_Reseña
```

#### Consulta SQL N.º 43 - Verificar consulta editable y bloquearla
**Descripción funcional:** Determina si a consulta puede editarse y reserva su fila durante la operación.

**Descripción técnica:** Busca el identificador en `CONSULTA`, normaliza el estado y acepta solamente `pendiente` o `en curso`. `FOR UPDATE` evita cambios concurrentes durante la transacción.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Validación al iniciar la edición de una consulta clínica.

**Sentencia SQL:**

```sql
SELECT id_Consulta
FROM CONSULTA
WHERE id_Consulta = ?
  AND LOWER(TRIM(estado_proceso_Consulta)) IN ('pendiente', 'en curso')
FOR UPDATE
```

#### Consulta SQL N.º 44 - Conservar archivos paraclínicos y paraclínicos legacy
**Descripción funcional:** Recupera metadatos necesarios para no perder archivos adjuntos al reemplazar los detalles de una consulta editada.

**Descripción técnica:** La primera consulta obtiene rutas de `PARACLINICO` por `id_Consulta`. La segunda obtiene identificadores de examen general existentes. La tercera obtiene pronósticos fuera de la lista estándar para preservarlos al guardar. Todas reciben `id_Consulta` o `id_ExamenG` según corresponda.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Preparación de una edición clínica que reemplaza algunos datos conservando adjuntos y valores legacy.

**Sentencia SQL:**

```sql
SELECT id_Paraclinico, archivoURL_Paraclinico
FROM PARACLINICO WHERE id_Consulta = ?
```

```sql
SELECT id_ExamenG FROM EXAMEN_GENERAL
WHERE id_Consulta = ? ORDER BY id_ExamenG LIMIT 1
```

```sql
SELECT tipo_Pronostico FROM PRONOSTICO
WHERE id_Consulta = ?
  AND tipo_Pronostico NOT IN ('Vital', 'Reproductivo', 'Deportivo', 'Reservado', 'Malo')
```

```sql
SELECT uc.id_Usuario
FROM USUARIO_CONSULTA uc
INNER JOIN USUARIO u ON u.id_Usuario = uc.id_Usuario
WHERE uc.id_Consulta = ?
  AND LOWER(TRIM(u.rol_Usuario)) NOT IN ('estudiante', 'profesor')
```

#### Consulta SQL N.º 45 - Validar paciente de una consulta por especie
**Descripción funcional:** Verifica que el paciente exista y pertenezca a la especie seleccionada en el formulario clínico.

**Descripción técnica:** Relaciona `PACIENTE`, `RAZA` y `ESPECIE`, recibe identificador de paciente y nombre base normalizado de especie, y limita a una fila. Impide guardar datos clínicos para una especie incompatible.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Validación dentro de `crear()` antes de insertar o editar una consulta.

**Sentencia SQL:**

```sql
SELECT p.id_Paciente
FROM PACIENTE p
INNER JOIN RAZA r ON r.id_Raza = p.id_Raza
INNER JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE p.id_Paciente = ? AND e.nombre_Especie = ?
LIMIT 1
```

#### Consulta SQL N.º 46 - Actualizar examen general existente
**Descripción funcional:** Reemplaza los valores de un examen general ya asociado a una consulta editada.

**Descripción técnica:** Actualiza las quince columnas opcionales de `EXAMEN_GENERAL` y se identifica por `id_ExamenG`. Los parámetros opcionales se reciben como cadenas o nulos y el último parámetro enlaza el identificador del examen.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Edición de una consulta que ya contiene un examen general.

**Sentencia SQL:**

```sql
UPDATE EXAMEN_GENERAL
SET peso_ExamenG = ?, CC_ExamenG = ?, TR_ExamenG = ?, FC_ExamenG = ?,
    FR_ExamenG = ?, TLLC_ExamenG = ?, sensorio_ExamenG = ?, facies_ExamenG = ?,
    piel_subcutaneo_ExamenG = ?, mucosas_ExamenG = ?, grandes_funciones_ExamenG = ?,
    actitudes_anomalas_ExamenG = ?, hidratacion_ExamenG = ?,
    ganglios_linfaticos_ExamenG = ?, aparentes_ExamenG = ?
WHERE id_ExamenG = ?
```

#### Consulta SQL N.º 47 - Insertar examen general y examen equino
**Descripción funcional:** Crea el examen general y, cuando corresponde, agrega las mediciones específicas de equinos.

**Descripción técnica:** La inserción inicial devuelve `insert_id` y usa ese identificador como clave `id_ExamenG` para `EXAMEN_EQUINO`. `EXAMEN_GENERAL` recibe la consulta y quince campos opcionales; `EXAMEN_EQUINO` recibe siete mediciones relacionadas con extremidades y cascos.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Alta o edición clínica de pacientes equinos y, según los datos, de otros pacientes.

**Sentencia SQL:**

```sql
INSERT INTO EXAMEN_GENERAL
    (id_Consulta, peso_ExamenG, CC_ExamenG, TR_ExamenG, FC_ExamenG,
     FR_ExamenG, TLLC_ExamenG, sensorio_ExamenG, facies_ExamenG,
     piel_subcutaneo_ExamenG, mucosas_ExamenG, grandes_funciones_ExamenG,
     actitudes_anomalas_ExamenG, hidratacion_ExamenG,
     ganglios_linfaticos_ExamenG, aparentes_ExamenG)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
```

```sql
INSERT INTO EXAMEN_EQUINO
    (id_ExamenG, temperatura_cascos_ExamenEq, pulso_digital_ExamenEq,
     senos_ExamenEq, csd_ExamenEq, cid_ExamenEq, csi_ExamenEq, cii_ExamenEq)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)
```

#### Consulta SQL N.º 48 - Cargar el detalle completo de una consulta
**Descripción funcional:** Construye el detalle principal de una consulta, incluyendo paciente, tutor, raza y especie.

**Descripción técnica:** Une `CONSULTA` con `PACIENTE`, `TUTOR`, `RAZA` y `ESPECIE`. Usa campos con alias descriptivos y limita a una consulta por su identificador.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Vista de detalle de consulta y generación del historial clínico.

**Sentencia SQL:**

```sql
SELECT
    c.id_Consulta AS id,
    p.id_Paciente AS id_paciente,
    c.tipo_Consulta AS `Tipo de consulta`,
    c.fecha_hora_Consulta AS `Fecha y hora`,
    c.estado_proceso_Consulta AS Estado,
    c.motivo_Consulta AS Motivo,
    c.resultado_final_Consulta AS `Resultado final`,
    c.fecha_proximo_control_Consulta AS `Próximo control`,
    c.fecha_fin_Consulta AS `Fecha de finalización`,
    p.nombre_Paciente AS Paciente,
    p.numchip_Paciente AS Chip,
    p.fecha_nac_Paciente AS `Fecha de nacimiento`,
    p.edad_Paciente AS Edad,
    p.sexo_Paciente AS Sexo,
    p.color_Paciente AS Color,
    p.foto_Paciente AS `Archivo de foto`,
    r.nombre_Raza AS Raza,
    e.nombre_Especie AS Especie,
    t.nombre_Tutor AS Tutor,
    t.documento_Tutor AS `Documento del tutor`,
    t.telefono_Tutor AS `Teléfono del tutor`,
    t.email_Tutor AS `Correo del tutor`,
    t.direccion_Tutor AS `Dirección del tutor`,
    t.notas_Tutor AS `Notas del tutor`
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE c.id_Consulta = ?
LIMIT 1
```

#### Consulta SQL N.º 49 - Cargar bloques asociados al detalle clínico
**Descripción funcional:** Recupera participantes y todas las familias de datos clínicos relacionados con una consulta.

**Descripción técnica:** Las consultas usan `USUARIO_CONSULTA`, `ANAMNESIS`, `EXAMEN_GENERAL`, `EXAMEN_PARTICULAR`, `EXAMEN_EQUINO`, `RESEÑA`, `DIAGNOSTICO`, `TRATAMIENTO`, `PARACLINICO`, `PRONOSTICO` y `HOSPITALIZACION`. Se filtran por el identificador de consulta —directamente o mediante `EXAMEN_GENERAL`— y cada resultado se ordena por su clave o por la fecha correspondiente.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Vista de detalle e historial clínico completo.

**Sentencia SQL:**

```sql
SELECT
    CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS Participante,
    u.cedula_Usuario AS `Cédula`,
    u.rol_Usuario AS Rol
FROM USUARIO_CONSULTA uc
INNER JOIN USUARIO u ON u.id_Usuario = uc.id_Usuario
WHERE uc.id_Consulta = ?
  AND LOWER(TRIM(u.rol_Usuario)) IN ('estudiante', 'profesor')
ORDER BY FIELD(LOWER(TRIM(u.rol_Usuario)), 'profesor', 'estudiante'),
         u.apellido_Usuario, u.nombre_Usuario
```

```sql
SELECT
    sanitaria_Anamnesis AS `Antecedentes sanitarios`,
    ambiental_Anamnesis AS Ambiente,
    remota_fisiologica_Anamnesis AS `Fisiológica remota`,
    remota_patologica_Anamnesis AS `Patológica remota`,
    proxima_fisiologica_Anamnesis AS `Fisiológica próxima`,
    proxima_patologica_Anamnesis AS `Patológica próxima`
FROM ANAMNESIS
WHERE id_Consulta = ?
ORDER BY id_Anamnesis
```

```sql
SELECT
    peso_ExamenG AS Peso, CC_ExamenG AS `Condición corporal`,
    TR_ExamenG AS `Temperatura rectal`, FC_ExamenG AS `Frecuencia cardíaca`,
    FR_ExamenG AS `Frecuencia respiratoria`, TLLC_ExamenG AS TLLC,
    sensorio_ExamenG AS Sensorio, facies_ExamenG AS Facies,
    piel_subcutaneo_ExamenG AS `Piel y subcutáneo`, mucosas_ExamenG AS Mucosas,
    grandes_funciones_ExamenG AS `Grandes funciones`,
    actitudes_anomalas_ExamenG AS `Actitudes anómalas`,
    hidratacion_ExamenG AS Hidratación,
    ganglios_linfaticos_ExamenG AS `Ganglios linfáticos`,
    aparentes_ExamenG AS `Otras alteraciones aparentes`
FROM EXAMEN_GENERAL
WHERE id_Consulta = ?
ORDER BY id_ExamenG
```

```sql
SELECT
    ep.texto_libre_ExamenP AS `Examen particular`,
    ep.observaciones_ExamenP AS Observaciones
FROM EXAMEN_PARTICULAR ep
INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = ep.id_ExamenG
WHERE eg.id_Consulta = ?
ORDER BY ep.id_ExamenP
```

```sql
SELECT
    eq.temperatura_cascos_ExamenEq AS `Temperatura de cascos`,
    eq.pulso_digital_ExamenEq AS `Pulso digital`,
    eq.senos_ExamenEq AS Senos,
    eq.csd_ExamenEq AS CSD, eq.cid_ExamenEq AS CID,
    eq.csi_ExamenEq AS CSI, eq.cii_ExamenEq AS CII
FROM EXAMEN_EQUINO eq
INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = eq.id_ExamenG
WHERE eg.id_Consulta = ?
ORDER BY eq.id_ExamenEq
```

```sql
SELECT r.id_Reseña AS id, r.archivoURL_RESEÑA AS archivo
FROM RESEÑA r
INNER JOIN EXAMEN_GENERAL eg ON eg.id_ExamenG = r.id_ExamenG
WHERE eg.id_Consulta = ?
ORDER BY r.id_Reseña
```

```sql
SELECT tipo_Diagnostico AS Tipo, descripcion_Diagnostico AS `Descripción`
FROM DIAGNOSTICO WHERE id_Consulta = ? ORDER BY id_Diagnostico
```

```sql
SELECT descripcion_Tratamiento AS Tratamiento
FROM TRATAMIENTO WHERE id_Consulta = ? ORDER BY id_Tratamiento
```

```sql
SELECT id_Paraclinico AS id, tipo_Paraclinico AS Tipo,
       resultado_Paraclinico AS Resultado,
       archivoURL_Paraclinico AS `Archivo asociado`
FROM PARACLINICO WHERE id_Consulta = ? ORDER BY id_Paraclinico
```

```sql
SELECT tipo_Pronostico AS `Pronóstico`
FROM PRONOSTICO WHERE id_Consulta = ? ORDER BY id_Pronostico
```

```sql
SELECT
    h.fecha_ingreso_Hospitalizacion AS `Fecha de ingreso`,
    h.fecha_egreso_Hospitalizacion AS `Fecha de egreso`,
    h.motivo_Hospitalizacion AS Motivo,
    h.estado_Hospitalizacion AS Estado,
    CONCAT_WS(' ', u.nombre_Usuario, u.apellido_Usuario) AS Responsable
FROM HOSPITALIZACION h
LEFT JOIN USUARIO u ON u.id_Usuario = h.id_Usuario
WHERE h.id_Consulta = ?
ORDER BY h.fecha_ingreso_Hospitalizacion
```

#### Consulta SQL N.º 50 - Historial de un paciente
**Descripción funcional:** Obtiene los datos principales del paciente y, por una segunda consulta, todos los identificadores de sus consultas clínicas.

**Descripción técnica:** La primera consulta relaciona `PACIENTE`, `TUTOR`, `RAZA` y `ESPECIE`, limita a una ficha por paciente y recibe `id_Paciente`. La segunda consulta de identificadores utiliza únicamente `CONSULTA`, filtra por paciente y ordena cronológicamente. Luego el modelo llama a `obtenerDetalle()` para cada ID.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Vista de historial clínico del paciente.

**Sentencia SQL:**

```sql
SELECT
    p.id_Paciente AS id,
    p.nombre_Paciente AS nombre,
    p.numchip_Paciente AS chip,
    p.fecha_nac_Paciente AS fecha_nacimiento,
    p.edad_Paciente AS edad,
    p.sexo_Paciente AS sexo,
    p.color_Paciente AS color,
    r.nombre_Raza AS raza,
    e.nombre_Especie AS especie,
    t.nombre_Tutor AS tutor,
    t.documento_Tutor AS documento_tutor,
    t.telefono_Tutor AS telefono_tutor,
    t.email_Tutor AS email_tutor,
    t.direccion_Tutor AS direccion_tutor,
    t.notas_Tutor AS notas_tutor
FROM PACIENTE p
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
LEFT JOIN RAZA r ON r.id_Raza = p.id_Raza
LEFT JOIN ESPECIE e ON e.id_Especie = r.id_Especie
WHERE p.id_Paciente = ?
LIMIT 1
```

```sql
SELECT id_Consulta
FROM CONSULTA
WHERE id_Paciente = ?
ORDER BY fecha_hora_Consulta ASC, id_Consulta ASC
```

#### Consulta SQL N.º 51 - Verificar existencia de consulta al cambiar estado
**Descripción funcional:** Comprueba si una consulta existe cuando el `UPDATE` no reporta filas afectadas.

**Descripción técnica:** Consulta `CONSULTA` por identificador y limita a una coincidencia. Puede ser necesaria porque algunos motores reportan cero filas afectadas si se asigna el mismo estado.

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Contexto de utilización:** Validación posterior a `actualizarEstado()`.

**Sentencia SQL:**

```sql
SELECT id_Consulta FROM CONSULTA WHERE id_Consulta = ? LIMIT 1
```

## 7. Dashboard

#### Consulta SQL N.º 52 - Consultas del día

**Archivo de origen:**

- `backend/Models/DashboardModel.php`

**Descripción funcional:** Muestra las consultas dentro del intervalo `[00:00, 24:00)` de una fecha seleccionada.

**Descripción técnica:** Une `CONSULTA`, `PACIENTE` y `TUTOR` y aplica un rango `[00:00, día siguiente 00:00)` mediante dos parámetros.

**Contexto de utilización:** Dashboard y agenda por fecha.

**Sentencia SQL:**

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

#### Consulta SQL N.º 53 - Consultas incompletas y próximas

**Archivo de origen:**

- `backend/Models/DashboardModel.php`

**Descripción funcional:** Cuenta consultas pendientes o en curso y lista las consultas pendientes y las próximas no finalizadas ni canceladas.

**Descripción técnica:** Cuenta o lista `CONSULTA` con estados `pendiente`/`en curso`; la variante próxima exige fecha mayor o igual a `NOW()` y excluye estados finalizados/cancelados.

**Contexto de utilización:** Dashboard y seguimiento de consultas pendientes.

**Sentencia SQL:**

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

#### Consulta SQL N.º 54 - Pacientes hospitalizados activos

**Archivo de origen:**

- `backend/Models/DashboardModel.php`

**Descripción funcional:** Lista las hospitalizaciones activas sin egreso con paciente, tutor y responsable para el dashboard.

**Descripción técnica:** Une `HOSPITALIZACION` con `PACIENTE`, `TUTOR` y opcionalmente `USUARIO`; exige egreso nulo y estado activo y ordena por ingreso ascendente.

**Contexto de utilización:** Dashboard de hospitalizaciones activas.

**Sentencia SQL:**

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

#### Consulta SQL N.º 55 - Contar consultas de un día
**Descripción funcional:** Cuenta el número de consultas programadas dentro de una fecha concreta, independientemente de su estado.

**Descripción técnica:** Utiliza la tabla `CONSULTA` y aplica un intervalo semiabierto `[inicio del día, inicio del día siguiente)`, de modo que se excluye exactamente la medianoche del día posterior. Los dos parámetros reciben el inicio y el final del intervalo. Devuelve una cantidad bajo el alias `cantidad`.

**Archivo de origen:**

- `backend/Models/DashboardModel.php`

**Contexto de utilización:** Contadores de la vista principal del dashboard asociados al selector de fecha.

**Sentencia SQL:**

```sql
SELECT COUNT(*) AS cantidad
FROM CONSULTA
WHERE fecha_hora_Consulta >= ?
  AND fecha_hora_Consulta < ?
```

#### Consulta SQL N.º 56 - Contar consultas próximas
**Descripción funcional:** Cuenta las consultas futuras que no están finalizadas ni canceladas.

**Descripción técnica:** Consulta `CONSULTA`, compara `fecha_hora_Consulta` con `NOW()` y excluye estados normalizados mediante `LOWER(TRIM(...))` iguales a `finalizada`, `cancelada` o `cancelado`. El uso de `COALESCE` trata estados nulos como cadenas vacías. No tiene parámetros enlazados.

**Archivo de origen:**

- `backend/Models/DashboardModel.php`

**Contexto de utilización:** Contador de próximas consultas del dashboard.

**Sentencia SQL:**

```sql
SELECT COUNT(*) AS cantidad
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
WHERE c.fecha_hora_Consulta >= NOW()
  AND COALESCE(LOWER(TRIM(c.estado_proceso_Consulta)), '') NOT IN
      ('finalizada', 'cancelada', 'cancelado')
```

## 8. Hospitalizaciones

#### Consulta SQL N.º 57 - Listar internaciones activas

**Archivo de origen:**

- `backend/Models/HospitalizacionModel.php`

**Descripción funcional:** Muestra las hospitalizaciones activas con datos de paciente, tutor, responsable y consulta asociada.

**Descripción técnica:** Une `HOSPITALIZACION` con paciente, tutor, usuario y consulta; filtra egreso nulo y estado activo y ordena ingreso descendente.

**Contexto de utilización:** Centro de hospitalizaciones.

**Sentencia SQL:**

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

#### Consulta SQL N.º 58 - Historial por paciente

**Archivo de origen:**

- `backend/Models/HospitalizacionModel.php`

**Descripción funcional:** Recupera todas las hospitalizaciones de un paciente, incluidas las finalizadas, junto con responsable y consulta.

**Descripción técnica:** Relaciona `HOSPITALIZACION` opcionalmente con `USUARIO` y `CONSULTA`, filtra por `id_Paciente` y devuelve activas o finalizadas ordenadas por ingreso descendente.

**Contexto de utilización:** Historial de hospitalizaciones del paciente.

**Sentencia SQL:**

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

#### Consulta SQL N.º 59 - Registrar ingreso y alta

**Archivo de origen:**

- `backend/Models/HospitalizacionModel.php`

**Descripción funcional:** Registra el ingreso de un paciente y permite marcar una hospitalización activa como finalizada con fecha de egreso.

**Descripción técnica:** Inserta en `HOSPITALIZACION` paciente, consulta, usuario, ingreso actual y estado activo; el alta modifica egreso y estado solo si la internación sigue activa.

**Contexto de utilización:** Registro de ingreso y egreso hospitalario.

**Sentencia SQL:**

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
#### Consulta SQL N.º 60 - Validaciones de hospitalización
**Descripción funcional:** Comprueba que el paciente exista, que no tenga una hospitalización activa y que una consulta opcional pertenezca al mismo paciente.

**Descripción técnica:** `pacienteExiste()` usa `PACIENTE` y puede agregar `FOR UPDATE`. `estaHospitalizado()` consulta `HOSPITALIZACION` exigiendo egreso nulo y estado activo normalizado. `consultaPerteneceAPaciente()` exige coincidencia de ambos identificadores.

**Archivo de origen:**

- `backend/Models/HospitalizacionModel.php`

**Contexto de utilización:** Validación transaccional antes de registrar un ingreso.

**Sentencia SQL:**

```sql
SELECT 1 FROM PACIENTE WHERE id_Paciente = ? LIMIT 1
```

```sql
SELECT 1 FROM PACIENTE WHERE id_Paciente = ? LIMIT 1 FOR UPDATE
```

```sql
SELECT 1 FROM HOSPITALIZACION
WHERE id_Paciente = ?
  AND fecha_egreso_Hospitalizacion IS NULL
  AND LOWER(TRIM(estado_Hospitalizacion)) = 'activa'
```

```sql
SELECT 1 FROM CONSULTA
WHERE id_Consulta = ? AND id_Paciente = ?
LIMIT 1
```

#### Consulta SQL N.º 61 - Listar consultas disponibles para hospitalización
**Descripción funcional:** Presenta las consultas registradas junto con el paciente al que pertenecen.

**Descripción técnica:** Une `CONSULTA` con `PACIENTE` mediante `id_Paciente`, devuelve identificadores, tipo y fecha y ordena por fecha descendente.

**Archivo de origen:**

- `backend/Models/HospitalizacionModel.php`

**Contexto de utilización:** Selector de consulta asociada al ingreso hospitalario.

**Sentencia SQL:**

```sql
SELECT
    c.id_Consulta,
    c.id_Paciente,
    c.tipo_Consulta,
    c.fecha_hora_Consulta,
    p.nombre_Paciente
FROM CONSULTA c
INNER JOIN PACIENTE p ON p.id_Paciente = c.id_Paciente
ORDER BY c.fecha_hora_Consulta DESC
```

## 9. Notificaciones

#### Consulta SQL N.º 62 - Vista de alertas de consultas atrasadas

**Archivo de origen:**

- `database/policlinico_db.sql`
- `database/policlinico_db_local_2026-11-16_105824.sql`

**Descripción funcional:** Define una vista con las consultas vencidas que permanecen pendientes o en curso y las vincula con el usuario asignado, el paciente y su tutor.

**Descripción técnica:** Une `USUARIO_CONSULTA` con `CONSULTA`, `PACIENTE` y `TUTOR`. Filtra fechas menores que `CURRENT_TIMESTAMP()` y estados normalizados `pendiente` o `en curso`. Expone `id_usuario` y los datos principales de la alerta.

**Contexto de utilización:** Fuente de datos para las notificaciones y alertas atrasadas.

**Sentencia SQL:**

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

#### Consulta SQL N.º 63 - Consultar alertas atrasadas por usuario

**Archivo de origen:**

- `backend/Models/NotificacionModel.php`

**Descripción funcional:** Obtiene las alertas vencidas asignadas al usuario autenticado y las ordena cronológicamente. Puede limitar el resultado para el menú o devolver todas las alertas.

**Descripción técnica:** Consulta la vista `vw_alertas_consultas_atrasadas`, filtra por `id_usuario = ?` y ordena por `fecha_hora_Consulta ASC`. `LIMIT ?` se agrega dinámicamente solo cuando se solicita un máximo; al no solicitar límite no se enlaza ningún máximo.

**Contexto de utilización:** Navbar y listado completo de notificaciones.

**Sentencia SQL:**

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

## 10. Usuarios activos por rol

#### Consulta SQL N.º 64 - Usuarios activos por rol

**Archivo de origen:**

- `backend/Models/ConsultaModel.php`

**Descripción funcional:** Completa las listas de estudiantes y profesores disponibles para asignar a una consulta clínica.

**Descripción técnica:** Consulta `USUARIO` por el rol recibido normalizado con `LOWER(TRIM(...))` y exige estado activo normalizado. Devuelve ID, cédula y nombre completo concatenado, ordenados por apellido y nombre.

**Contexto de utilización:** Selección de participantes en el formulario de consultas clínicas.

**Sentencia SQL:**

```sql
SELECT id_Usuario AS id,
       cedula_Usuario AS cedula,
       CONCAT_WS(' ', nombre_Usuario, apellido_Usuario) AS nombre
FROM USUARIO
WHERE LOWER(TRIM(rol_Usuario)) = ?
  AND LOWER(TRIM(estado_Usuario)) = 'activo'
ORDER BY apellido_Usuario, nombre_Usuario;
```
