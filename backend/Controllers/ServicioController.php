<?php

/** Administra el catálogo de servicios; todas las operaciones son exclusivas de Dirección. */
class ServicioController extends Controller
{
    public function index(): void
    {
        Auth::requirePermiso('admin.total');

        $modelo = $this->model('Servicio');
        $editarId = $_GET['editar'] ?? null;
        $servicioEditando = null;
        $error = $_SESSION['servicio_error'] ?? null;
        $mensaje = $_SESSION['servicio_mensaje'] ?? null;
        $datosFormulario = $_SESSION['servicio_datos'] ?? null;
        $idFormulario = $_SESSION['servicio_editar_id'] ?? null;
        unset($_SESSION['servicio_error'], $_SESSION['servicio_mensaje'], $_SESSION['servicio_datos'], $_SESSION['servicio_editar_id']);

        if ($editarId !== null) {
            if (!is_string($editarId) || !ctype_digit($editarId) || (int) $editarId < 1) {
                http_response_code(400);
                $error = 'El identificador del servicio no es válido.';
            } else {
                $servicioEditando = $modelo->obtener((int) $editarId);
                if ($servicioEditando === null) {
                    http_response_code(404);
                    $error = 'No se encontró el servicio seleccionado.';
                }
            }
        }

        if (is_array($datosFormulario)) {
            if (is_string($idFormulario) && ctype_digit($idFormulario) && (int) $idFormulario > 0) {
                $servicioEditando ??= $modelo->obtener((int) $idFormulario);
                $servicioEditando ??= ['id_Servicio' => (int) $idFormulario];
            }
            $servicioEditando = array_merge($servicioEditando ?? [], $datosFormulario);
        }

        $this->view(
            'dashboard/servicios',
            [
                'servicios' => $modelo->listar(),
                'servicioEditando' => $servicioEditando,
                'csrfToken' => Csrf::token(),
                'error' => is_string($error) ? $error : null,
                'mensaje' => is_string($mensaje) ? $mensaje : null,
            ],
            'app',
            ['app-layout.css', 'agenda.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    public function guardar(): void
    {
        Auth::requirePermiso('admin.total');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        $datos = $_POST;
        $servicioId = $datos['servicio_id'] ?? '';
        if (
            !is_string($servicioId)
            || ($servicioId !== '' && (!ctype_digit($servicioId) || (int) $servicioId < 1))
        ) {
            http_response_code(400);
            echo 'El identificador del servicio no es válido.';
            return;
        }

        $token = $datos['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            $this->guardarErrorFormulario($datos, $servicioId, 'El formulario venció. Revisá los datos y volvé a enviarlo.');
        }

        $modelo = $this->model('Servicio');
        $servicioActual = $servicioId === '' ? null : $modelo->obtener((int) $servicioId);
        if ($servicioId !== '' && $servicioActual === null) {
            http_response_code(404);
            $_SESSION['servicio_error'] = 'No se encontró el servicio seleccionado.';
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        $archivosNuevos = [];
        try {
            $imagenNueva = $this->guardarImagenServicio($_FILES['imagen_archivo'] ?? null);
            if ($imagenNueva !== null) {
                $archivosNuevos[] = $imagenNueva;
            }
            $iconoNuevo = $this->guardarImagenServicio($_FILES['icono_archivo'] ?? null);
            if ($iconoNuevo !== null) {
                $archivosNuevos[] = $iconoNuevo;
            }

            $datos['imagenURL_Servicio'] = $imagenNueva
                ?? (($datos['quitar_imagen'] ?? '') === '1' ? null : ($servicioActual['imagenURL_Servicio'] ?? null));
            $datos['iconoURL_Servicio'] = $iconoNuevo
                ?? (($datos['quitar_icono'] ?? '') === '1' ? null : ($servicioActual['iconoURL_Servicio'] ?? null));

            if ($servicioId === '') {
                $modelo->crear($datos, (int) Auth::id());
                $_SESSION['servicio_mensaje'] = 'El servicio se creó correctamente.';
            } else {
                $modelo->actualizar((int) $servicioId, $datos);
                $_SESSION['servicio_mensaje'] = 'El servicio se actualizó correctamente.';

                if ($imagenNueva !== null || ($datos['quitar_imagen'] ?? '') === '1') {
                    $this->eliminarImagenServicio($servicioActual['imagenURL_Servicio'] ?? null);
                }
                if ($iconoNuevo !== null || ($datos['quitar_icono'] ?? '') === '1') {
                    $this->eliminarImagenServicio($servicioActual['iconoURL_Servicio'] ?? null);
                }
            }
        } catch (InvalidArgumentException $error) {
            $this->eliminarImagenesServicio($archivosNuevos);
            $datos = $this->restaurarRecursosFormulario($datos, $servicioActual);
            $this->guardarErrorFormulario($datos, $servicioId, $error->getMessage());
        } catch (Throwable $error) {
            $this->eliminarImagenesServicio($archivosNuevos);
            $datos = $this->restaurarRecursosFormulario($datos, $servicioActual);
            error_log('No se pudo guardar el servicio: ' . $error->getMessage());
            $this->guardarErrorFormulario($datos, $servicioId, 'No se pudo guardar el servicio. Intentá nuevamente.');
        }

        header('Location: ' . url('servicios'), true, 303);
        exit;
    }

    public function eliminar(): void
    {
        Auth::requirePermiso('admin.total');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        $servicioId = $_POST['servicio_id'] ?? null;
        $token = $_POST['csrf_token'] ?? null;
        if (
            !is_string($servicioId) || !ctype_digit($servicioId) || (int) $servicioId < 1
            || !is_string($token) || !Csrf::validar($token)
        ) {
            http_response_code(403);
            $_SESSION['servicio_error'] = 'La solicitud de eliminación no es válida o venció.';
            header('Location: ' . url('servicios'), true, 303);
            exit;
        }

        try {
            $modelo = $this->model('Servicio');
            $servicio = $modelo->obtener((int) $servicioId);
            $modelo->eliminar((int) $servicioId);
            $this->eliminarImagenServicio($servicio['imagenURL_Servicio'] ?? null);
            $this->eliminarImagenServicio($servicio['iconoURL_Servicio'] ?? null);
            $_SESSION['servicio_mensaje'] = 'El servicio se eliminó correctamente.';
        } catch (InvalidArgumentException $error) {
            $_SESSION['servicio_error'] = $error->getMessage();
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo eliminar el servicio: ' . $error->getMessage());
            $_SESSION['servicio_error'] = 'No se pudo eliminar el servicio. Intentá nuevamente.';
        }

        header('Location: ' . url('servicios'), true, 303);
        exit;
    }

    private function guardarErrorFormulario(array $datos, string $servicioId, string $mensaje): never
    {
        $_SESSION['servicio_error'] = $mensaje;
        $_SESSION['servicio_datos'] = $datos;
        $_SESSION['servicio_editar_id'] = $servicioId;
        header('Location: ' . url('servicios'), true, 303);
        exit;
    }

    /** Valida una subida y guarda únicamente imágenes rasterizadas permitidas. */
    private function guardarImagenServicio($archivo): ?string
    {
        if ($archivo === null) {
            return null;
        }
        if (!is_array($archivo) || !isset($archivo['error'], $archivo['tmp_name'], $archivo['size'])) {
            throw new InvalidArgumentException('El archivo de imagen no tiene un formato válido.');
        }
        if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($archivo['error'] !== UPLOAD_ERR_OK || !is_string($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            throw new InvalidArgumentException('No se pudo recibir la imagen. Seleccionala nuevamente.');
        }
        if (!is_int($archivo['size']) || $archivo['size'] < 1 || $archivo['size'] > 5 * 1024 * 1024) {
            throw new InvalidArgumentException('Cada imagen debe pesar hasta 5 MB.');
        }

        $extensiones = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
        if (!is_string($mime) || !isset($extensiones[$mime])) {
            throw new InvalidArgumentException('Las imágenes solo aceptan JPG, PNG o WebP.');
        }

        $directorio = ROOT_PATH . '/public/activos/imgs/servicios';
        if (!is_dir($directorio) && !@mkdir($directorio, 02775, true) && !is_dir($directorio)) {
            throw new RuntimeException('No se pudo preparar el directorio de imágenes de servicios.');
        }
        if (!is_writable($directorio)) {
            throw new RuntimeException('El directorio de imágenes de servicios no permite escritura.');
        }

        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
        if (!move_uploaded_file($archivo['tmp_name'], $directorio . '/' . $nombreArchivo)) {
            throw new RuntimeException('No se pudo guardar la imagen del servicio.');
        }

        return '/activos/imgs/servicios/' . $nombreArchivo;
    }

    /** Borra solo archivos generados dentro del directorio de imágenes de servicios. */
    private function eliminarImagenServicio(?string $url): void
    {
        if ($url === null) {
            return;
        }

        $ruta = parse_url($url, PHP_URL_PATH);
        $prefijo = '/activos/imgs/servicios/';
        if (!is_string($ruta) || !str_starts_with($ruta, $prefijo)) {
            return;
        }

        $nombre = substr($ruta, strlen($prefijo));
        if (!preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/i', $nombre)) {
            return;
        }

        $archivo = ROOT_PATH . '/public/activos/imgs/servicios/' . $nombre;
        if (is_file($archivo) && !unlink($archivo)) {
            error_log('No se pudo eliminar una imagen de servicio: ' . $archivo);
        }
    }

    /** Elimina archivos subidos en una operación que no llegó a guardarse en la base. */
    private function eliminarImagenesServicio(array $urls): void
    {
        foreach ($urls as $url) {
            $this->eliminarImagenServicio($url);
        }
    }

    /** Evita conservar en el formulario rutas a archivos nuevos que se revirtieron. */
    private function restaurarRecursosFormulario(array $datos, ?array $servicioActual): array
    {
        foreach (['imagenURL_Servicio', 'iconoURL_Servicio'] as $campo) {
            if ($servicioActual === null) {
                unset($datos[$campo]);
            } else {
                $datos[$campo] = $servicioActual[$campo] ?? '';
            }
        }

        return $datos;
    }
}