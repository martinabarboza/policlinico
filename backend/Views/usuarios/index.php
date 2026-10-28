<?php
$usuarios = $usuarios ?? [];
$totalUsuarios = count($usuarios);
$rolesConteo = [
    'DIRECTOR' => 0,
    'ESTUDIANTE' => 0,
    'VETERINARIO' => 0,
    'PROFESOR' => 0,
];

foreach ($usuarios as $usuario) {
    $rol = $usuario['rol_Usuario'] ?? '';
    if (isset($rolesConteo[$rol])) {
        $rolesConteo[$rol]++;
    }
}
?>

<div class="usuarios-page container-fluid py-4">
    <div class="usuarios-header">
        <div class="usuarios-header__title">
            <div class="usuarios-header__icon">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Administración</p>
                <h1 class="h3 mb-0">Gestión de usuarios</h1>
            </div>
        </div>
        <div class="text-secondary small mb-0">Personal activo del sistema</div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="usuarios-stat h-100">
                <span class="usuarios-stat__label">Total</span>
                <div class="usuarios-stat__value"><?php echo (int) $totalUsuarios; ?></div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="usuarios-stat h-100">
                <span class="usuarios-stat__label">Directores</span>
                <div class="usuarios-stat__value"><?php echo (int) $rolesConteo['DIRECTOR']; ?></div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="usuarios-stat h-100">
                <span class="usuarios-stat__label">Estudiantes</span>
                <div class="usuarios-stat__value"><?php echo (int) $rolesConteo['ESTUDIANTE']; ?></div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="usuarios-stat h-100">
                <span class="usuarios-stat__label">Profesores</span>
                <div class="usuarios-stat__value"><?php echo (int) $rolesConteo['PROFESOR']; ?></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 mb-4">
        <form action="/usuarios" method="GET" class="d-flex gap-2">
            <input type="text" name="busqueda" placeholder="Nombre, apellido o cédula" class="form-control" value="<?php echo htmlspecialchars($_GET['busqueda'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <button class="btn btn-outline-primary" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="status">Usuario actualizado exitosamente.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">Error al actualizar el usuario.</div>
    <?php endif; ?>

    <section class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h5 mb-1">Usuarios registrados</h2>
                    <p class="text-secondary mb-0 small">Listado del personal con acceso al sistema.</p>
                </div>
                <span class="badge bg-light text-dark border"><?php echo $totalUsuarios; ?> resultado(s)</span>
            </div>

            <div class>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Rol</th>
                            <th>Email</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <?php

                                //
                                // Determina la clase de la etiqueta según el rol del usuario
                                //

                                $rol = strtoupper((string) ($usuario['rol_Usuario'] ?? ''));
                                $badgeClass = 'usuarios-role--director';
                                if ($rol === 'ESTUDIANTE') {
                                    $badgeClass = 'usuarios-role--estudiante';
                                } elseif ($rol === 'VETERINARIO') {
                                    $badgeClass = 'usuarios-role--veterinario';
                                } elseif ($rol === 'PROFESOR') {
                                    $badgeClass = 'usuarios-role--profesor';
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['cedula_Usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nombre_Usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['apellido_Usuario']); ?></td>
                                    <td>
                                        <span class="usuarios-role <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($rol); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['email_Usuario'] ?? 'Sin email'); ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="/usuarios/editar?cedula=<?php echo urlencode($usuario['cedula_Usuario']); ?>">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>Modificar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">No hay usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
