<?php

/** Provee los servicios publicados que consume la página de inicio. */
class Servicio extends Model
{
    /** Lista servicios para el panel administrativo. */
    public function listar(): array
    {
        $stmt = $this->db->prepare(
            'SELECT id_Servicio, id_Usuario, nombre_Servicio, descripcion_Servicio,
                    precio_Servicio, duracion_Servicio, imagenURL_Servicio, iconoURL_Servicio
             FROM SERVICIO
             ORDER BY nombre_Servicio ASC, id_Servicio ASC'
        );
        $stmt->execute();
        $servicios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $servicios;
    }

    /** Obtiene un servicio para precargar el formulario de edición. */
    public function obtener(int $servicioId): ?array
    {
        if ($servicioId < 1) {
            return null;
        }

        $stmt = $this->db->prepare(
            'SELECT id_Servicio, nombre_Servicio, descripcion_Servicio, precio_Servicio,
                    duracion_Servicio, imagenURL_Servicio, iconoURL_Servicio
             FROM SERVICIO
             WHERE id_Servicio = ?
             LIMIT 1'
        );
        $stmt->bind_param('i', $servicioId);
        $stmt->execute();
        $servicio = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $servicio ?: null;
    }

    /** Crea un servicio y registra como responsable al director autenticado. */
    public function crear(array $datos, int $usuarioId): int
    {
        if ($usuarioId < 1) {
            throw new InvalidArgumentException('No se pudo identificar al usuario responsable.');
        }

        $valores = $this->validarDatos($datos);
        $stmt = $this->db->prepare(
            'INSERT INTO SERVICIO
                (id_Usuario, nombre_Servicio, descripcion_Servicio, precio_Servicio,
                 duracion_Servicio, imagenURL_Servicio, iconoURL_Servicio)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'issssss',
            $usuarioId,
            $valores['nombre'],
            $valores['descripcion'],
            $valores['precio'],
            $valores['duracion'],
            $valores['imagen'],
            $valores['icono']
        );
        $stmt->execute();
        $servicioId = (int) $stmt->insert_id;
        $stmt->close();

        return $servicioId;
    }

    /** Actualiza los campos editables sin cambiar quién registró el servicio. */
    public function actualizar(int $servicioId, array $datos): void
    {
        if ($servicioId < 1 || $this->obtener($servicioId) === null) {
            throw new InvalidArgumentException('No se encontró el servicio seleccionado.');
        }

        $valores = $this->validarDatos($datos);
        $stmt = $this->db->prepare(
            'UPDATE SERVICIO
             SET nombre_Servicio = ?, descripcion_Servicio = ?, precio_Servicio = ?,
                 duracion_Servicio = ?, imagenURL_Servicio = ?, iconoURL_Servicio = ?
             WHERE id_Servicio = ?'
        );
        $stmt->bind_param(
            'ssssssi',
            $valores['nombre'],
            $valores['descripcion'],
            $valores['precio'],
            $valores['duracion'],
            $valores['imagen'],
            $valores['icono'],
            $servicioId
        );
        $stmt->execute();
        $stmt->close();
    }

    /** Elimina el servicio seleccionado; devuelve error claro si el ID no existe. */
    public function eliminar(int $servicioId): void
    {
        if ($servicioId < 1) {
            throw new InvalidArgumentException('El identificador del servicio no es válido.');
        }

        $stmt = $this->db->prepare('DELETE FROM SERVICIO WHERE id_Servicio = ?');
        $stmt->bind_param('i', $servicioId);
        $stmt->execute();
        $eliminado = $stmt->affected_rows > 0;
        $stmt->close();

        if (!$eliminado) {
            throw new InvalidArgumentException('No se encontró el servicio seleccionado.');
        }
    }

    /** @return array{nombre: string, descripcion: ?string, precio: string, duracion: ?string, imagen: ?string, icono: ?string} */
    private function validarDatos(array $datos): array
    {
        foreach (['nombre_Servicio', 'precio_Servicio'] as $campo) {
            if (!is_string($datos[$campo] ?? null) || trim($datos[$campo]) === '') {
                throw new InvalidArgumentException('Completá el nombre y el precio del servicio.');
            }
        }

        $nombre = trim($datos['nombre_Servicio']);
        $precio = trim($datos['precio_Servicio']);
        if (strlen($nombre) > 100) {
            throw new InvalidArgumentException('El nombre del servicio no puede superar los 100 caracteres.');
        }
        if (!preg_match('/\A\d{1,8}(?:\.\d{1,2})?\z/', $precio)) {
            throw new InvalidArgumentException('Ingresá un precio válido con hasta dos decimales.');
        }

        $descripcion = $this->valorOpcional($datos, 'descripcion_Servicio');
        $duracion = $this->valorOpcional($datos, 'duracion_Servicio');
        $imagen = $this->validarRecurso($datos, 'imagenURL_Servicio');
        $icono = $this->validarRecurso($datos, 'iconoURL_Servicio');

        if (($descripcion !== null && strlen($descripcion) > 65535)
            || ($duracion !== null && strlen($duracion) > 50)) {
            throw new InvalidArgumentException('La descripción o duración supera la longitud permitida.');
        }

        return [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => number_format((float) $precio, 2, '.', ''),
            'duracion' => $duracion,
            'imagen' => $imagen,
            'icono' => $icono,
        ];
    }

    private function valorOpcional(array $datos, string $campo): ?string
    {
        $valor = $datos[$campo] ?? '';
        if (!is_string($valor)) {
            throw new InvalidArgumentException('Los datos del servicio no tienen un formato válido.');
        }

        $valor = trim($valor);
        return $valor === '' ? null : $valor;
    }

    private function validarRecurso(array $datos, string $campo): ?string
    {
        $valor = $this->valorOpcional($datos, $campo);
        if ($valor === null) {
            return null;
        }
        if (strlen($valor) > 255 || preg_match('/[\x00-\x20\\\\]/', $valor)) {
            throw new InvalidArgumentException('La ruta de imagen o icono no es válida.');
        }

        $esquema = parse_url($valor, PHP_URL_SCHEME);
        if ($esquema === null && !str_starts_with($valor, '//')) {
            return $valor;
        }
        if (in_array(strtolower((string) $esquema), ['http', 'https'], true) && filter_var($valor, FILTER_VALIDATE_URL)) {
            return $valor;
        }

        throw new InvalidArgumentException('Las imágenes e iconos deben ser rutas locales o URLs HTTP/HTTPS.');
    }

    /** Devuelve los campos de servicio con los alias esperados por la vista landing. */
    public function getArray_Servicios(): array
    {

        $stmt = $this->db->prepare(
            "SELECT
                nombre_Servicio AS titulo,
                descripcion_Servicio AS descripcion,
                precio_Servicio AS precio,
                duracion_Servicio AS duracion,
                imagenURL_Servicio AS imagen,
                iconoURL_Servicio AS icono
             FROM SERVICIO"
        );

        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $resultado;
    }
}
?>