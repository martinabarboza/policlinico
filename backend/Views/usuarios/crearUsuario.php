<?php
$usuarioEdit = $usuarioEdit ?? [];
$rolesUsuario = [
    'Director' => 'Director',
    'Estudiante' => 'Estudiante',
    'Profesor' => 'Profesor'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Crear Usuario</h1><br>
        <form action="/usuarios/crear" method="POST">
            <label for="Cedula">Cédula:</label>
            <input type="text" id="Cedula" name="cedula" required><br>
            <label for="Nombre">Nombre:</label>
            <input type="text" id="Nombre" name="nombre" required><br>
            <label for="Apellido">Apellido:</label>
            <input type="text" id="Apellido" name="apellido" required><br>
            <label for="Rol">Rol:</label>
            <select id="Rol" name="rol" required>
                <option value="">Seleccione un rol</option>
                <?php foreach ($rolesUsuario as $valor => $etiqueta): ?>
                    <option value="<?php echo htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
            </select><br>
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="email" required><br>
            <label for="Password">Contraseña:</label>
            <input type="password" id="Password" name="passw" required><br>
            <button type="submit">Crear Usuario</button>
            <button href=/usuarios>Cancelar</button>
        </form>
        <?php if (isset($error) && !empty($error)) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
        } ?>
    </div>
</body>
</html>