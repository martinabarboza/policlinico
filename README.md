# policlinico
sistema de gestión de consultas veterinaria

## Guía del dashboard y sus búsquedas

### Secciones de trabajo

Las páginas de **Consultorio**, **Tutores**, **Agenda** y **Hospitalización** son puntos de partida para continuar el desarrollo. Sus vistas están en `backend/Views/dashboard/`; los controladores asociados reciben las solicitudes. Por ahora, el texto “Próximos pasos” de cada página indica qué funcionalidad falta implementar.

### Reglas que muestran los contadores y listas

- **Agenda del día:** muestra todas las consultas cuya `fecha_hora_Consulta` cae en la fecha elegida en el dashboard, sin filtrar por estado.
- **Pacientes en espera:** muestra solo las consultas `Pendiente` dentro de la fecha elegida.
- **Consultas diarias:** el contador y su tarjeta muestran las consultas de hoy, sin importar su estado. El enlace abre ese mismo listado.
- **Consultas incompletas:** considera consultas de cualquier fecha con estado `Pendiente` o `En curso`. Las finalizadas, canceladas y otros estados se excluyen. El contador y el listado deben mantener este mismo criterio.
- **Pacientes hospitalizados:** solo aparecen filas de `HOSPITALIZACION` con estado `Activa` y `fecha_egreso_Hospitalizacion` vacía (`NULL`). Para dar el alta se registra la fecha de egreso; el historial se conserva, pero deja de aparecer entre las activas.

Los valores de estado se comparan ignorando mayúsculas y espacios al inicio o al final. Al cargar consultas, usar los estados habituales `Pendiente`, `En curso`, `Finalizada` y `Cancelada` para que el dashboard aplique las clases visuales esperadas.

### Búsqueda del navbar

El buscador envía datos por GET a `/buscar-centro`:

- `tipoBusqueda=paciente` redirige a `/centro-consultas?busqueda=...`. Busca por nombre del paciente o número de chip.
- `tipoBusqueda=tutor` redirige a `/centro-tutores?busqueda=...`. Busca por nombre, documento o teléfono.
- El término debe contener entre 1 y 100 caracteres después de quitar espacios externos. Si no es válido, el centro muestra un aviso.
- No hay búsqueda por médico en el navbar.

Ejemplos: `/buscar-centro?tipoBusqueda=paciente&busqueda=Nala` y `/buscar-centro?tipoBusqueda=tutor&busqueda=Laura`.

Las rutas están declaradas en `routes/web.php`. La decisión del destino está en `DashboardController::buscarEnCentro()` y cada centro interpreta el parámetro `busqueda` en su controlador. Las consultas SQL de búsqueda están en `PacienteModel::buscar()` y `TutorModel::buscar()`; cada búsqueda muestra como máximo 50 coincidencias. Mantener los parámetros preparados al modificar las búsquedas.

El recorrido es: formulario del navbar → ruta `/buscar-centro` → redirección al centro elegido con `?busqueda=...` → controlador del centro → modelo que consulta la base → vista que muestra resultados. Si se agrega otro tipo de búsqueda, hay que actualizar el selector del navbar, la ruta de destino y su controlador/modelo.

### Cómo se muestra una pantalla

Una URL se declara en `routes/web.php`; la ruta indica qué método de controlador ejecutarla. El controlador valida los datos recibidos y pide información al modelo. El modelo consulta la base de datos. Finalmente, el controlador entrega los datos a una vista dentro del layout del dashboard. Para continuar una sección, suele ser suficiente seguir ese recorrido: ruta → controlador → modelo → vista.

Los cuatro centros mencionados arriba son actualmente pantallas iniciales, no formularios completos. Sus vistas indican un orden posible para agregar cada función sin mezclar la presentación con las consultas SQL.

### Archivos principales

- `backend/Controllers/DashboardController.php`: reúne los datos del dashboard y atiende los listados enlazados desde sus tarjetas.
- `backend/Models/DashboardModel.php`: contiene las consultas y los criterios de estado del dashboard.
- `backend/Views/dashboard/index.php`: muestra tarjetas, agenda, espera e internaciones activas.
- `backend/Views/dashboard/listado-consultas.php`: tabla compartida por los listados diarios e incompletos.
- `backend/Controllers/Centro_ConsultasController.php` y `Centro_TutoresController.php`: leen `?busqueda=` y piden los resultados al modelo correspondiente.
- `backend/Models/PacienteModel.php` y `TutorModel.php`: buscan pacientes y tutores en la base de datos.
- `routes/web.php`: conecta las direcciones del dashboard y de las dos búsquedas con sus métodos de controlador.
- `backend/Views/layouts/navbar_Dashboard.php`: formulario de búsqueda y botón para colapsar el menú lateral.
- `backend/Views/layouts/header_Dashboard.php`: carga Bootstrap CSS y los archivos de librerías, estilos y scripts recibidos por el layout. En `Controller::view()`, las listas `$libs`, `$hojasEstilo` y `$scriptsJS` indican qué archivos cargar desde `public/activos/`. Los scripts ya no se repiten en el pie de página. Mantener jQuery en `$libs` antes de `dashboard.js`, porque ese script usa jQuery.
- `public/activos/js/dashboard.js`: espera a que se cargue el documento y conecta el botón lateral con las clases CSS `abierto`.
- `public/activos/css/app-layout.css`: define el aspecto y el comportamiento adaptable del menú lateral.

El JavaScript del dashboard se carga desde el `<head>`, por eso conecta los botones cuando el documento está listo; si se mueve ese script, hay que mantener este orden para que el botón del navbar exista al registrar el clic.
