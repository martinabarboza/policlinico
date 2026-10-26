<?php

class Centro_ConsultasController extends Controller
{
    public function nueva(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearConsultas()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden crear consultas.';
            return;
        }

        $especie = $_GET['especie'] ?? 'Equino';
        if (!is_string($especie) || !in_array($especie, ['Perro', 'Gato', 'Equino'], true)) {
            http_response_code(400);
            $this->mostrarFormularioNuevaConsulta('Equino', 'La especie seleccionada no es válida.');
            return;
        }

        $pacienteSeleccionado = $_GET['paciente'] ?? '';
        $oldInput = [];
        if (
            is_string($pacienteSeleccionado)
            && ctype_digit($pacienteSeleccionado)
            && (int) $pacienteSeleccionado > 0
        ) {
            $oldInput['id_Paciente'] = $pacienteSeleccionado;
        }
        $mensajePaciente = $_SESSION['flash_paciente_creado'] ?? null;
        unset($_SESSION['flash_paciente_creado']);

        $this->mostrarFormularioNuevaConsulta(
            $especie,
            null,
            $oldInput,
            is_string($mensajePaciente) ? $mensajePaciente : null
        );
    }

    public function guardar(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCrearConsultas()) {
            http_response_code(403);
            echo 'Solo estudiantes, profesores y directores pueden crear consultas.';
            return;
        }

        $especie = $_POST['especie'] ?? 'Equino';
        if (!is_string($especie) || !in_array($especie, ['Perro', 'Gato', 'Equino'], true)) {
            http_response_code(400);
            $this->mostrarFormularioNuevaConsulta('Equino', 'La especie seleccionada no es válida.', $_POST);
            return;
        }

        $token = $_POST['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            http_response_code(403);
            $this->mostrarFormularioNuevaConsulta($especie, 'El formulario venció. Revisá los datos y volvé a enviarlo.', $_POST);
            return;
        }

        $paciente = $_POST['id_Paciente'] ?? '';
        $estudiantes = $_POST['estudiantes'] ?? [];
        $profesor = $_POST['id_Profesor'] ?? '';
        $fechaHora = $_POST['fecha_hora_Consulta'] ?? '';
        $tipo = $_POST['tipo_Consulta'] ?? '';
        $motivo = $_POST['motivo_Consulta'] ?? '';
        $estudiantesValidos = is_array($estudiantes);
        if ($estudiantesValidos) {
            foreach ($estudiantes as $estudianteId) {
                if (!is_string($estudianteId) || !ctype_digit($estudianteId) || (int) $estudianteId < 1) {
                    $estudiantesValidos = false;
                    break;
                }
            }
        }

        if (
            !is_string($paciente)
            || !ctype_digit($paciente)
            || (int) $paciente < 1
            || !$estudiantesValidos
            || !is_string($profesor)
            || ($profesor !== '' && (!ctype_digit($profesor) || (int) $profesor < 1))
            || !is_string($fechaHora)
            || !is_string($tipo)
            || !in_array($tipo, ['Consulta general', 'Control', 'Consulta equina', 'Hospitalización'], true)
            || !is_string($motivo)
            || trim($motivo) === ''
            || strlen(trim($motivo)) > 65535
        ) {
            http_response_code(422);
            $this->mostrarFormularioNuevaConsulta($especie, 'Completá los datos obligatorios con valores válidos.', $_POST);
            return;
        }

        $fechaValidada = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $fechaHora);
        if (!$fechaValidada || $fechaValidada->format('Y-m-d\TH:i') !== $fechaHora) {
            http_response_code(422);
            $this->mostrarFormularioNuevaConsulta($especie, 'La fecha y hora ingresadas no son válidas.', $_POST);
            return;
        }

        $nombresCampos = [
            'sanitaria_Anamnesis',
            'ambiental_Anamnesis',
            'remota_fisiologica_Anamnesis',
            'remota_patologica_Anamnesis',
            'proxima_fisiologica_Anamnesis',
            'proxima_patologica_Anamnesis',
            'peso_ExamenG',
            'CC_ExamenG',
            'TR_ExamenG',
            'FC_ExamenG',
            'FR_ExamenG',
            'TLLC_ExamenG',
            'sensorio_ExamenG',
            'facies_ExamenG',
            'piel_subcutaneo_ExamenG',
            'mucosas_ExamenG',
            'grandes_funciones_ExamenG',
            'actitudes_anomalas_ExamenG',
            'hidratacion_ExamenG',
            'ganglios_linfaticos_ExamenG',
            'aparentes_ExamenG',
            'temperatura_cascos_ExamenEq',
            'pulso_digital_ExamenEq',
            'senos_ExamenEq',
            'csd_ExamenEq',
            'cid_ExamenEq',
            'csi_ExamenEq',
            'cii_ExamenEq',
        ];
        $datos = [
            'fecha_hora_Consulta' => $fechaValidada->format('Y-m-d H:i:s'),
            'tipo_Consulta' => $tipo,
            'motivo_Consulta' => trim($motivo),
        ];

        foreach ($nombresCampos as $nombreCampo) {
            $valor = $_POST[$nombreCampo] ?? '';
            if (!is_string($valor)) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta($especie, 'Uno de los campos enviados no es válido.', $_POST);
                return;
            }
            $datos[$nombreCampo] = trim($valor);
        }

        $diagnosticos = [];
        foreach (['Provisorio' => 'diagnostico_provisorio', 'Definitivo' => 'diagnostico_definitivo'] as $tipoDiagnostico => $nombreCampo) {
            $descripcion = $_POST[$nombreCampo] ?? '';
            if (!is_string($descripcion) || strlen(trim($descripcion)) > 65535) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta($especie, 'El diagnóstico enviado no es válido.', $_POST);
                return;
            }
            if (trim($descripcion) !== '') {
                $diagnosticos[] = [
                    'tipo' => $tipoDiagnostico,
                    'descripcion' => trim($descripcion),
                ];
            }
        }

        $paraclinicosRecibidos = $_POST['paraclinicos'] ?? [];
        $tratamiento = $_POST['tratamiento'] ?? '';
        $pronosticos = $_POST['pronosticos'] ?? [];
        $pronosticosPermitidos = ['Vital', 'Reproductivo', 'Deportivo', 'Reservado', 'Malo'];
        if (
            !is_array($paraclinicosRecibidos)
            || count($paraclinicosRecibidos) > 20
            || !is_string($tratamiento)
            || strlen(trim($tratamiento)) > 65535
            || !is_array($pronosticos)
        ) {
            http_response_code(422);
            $this->mostrarFormularioNuevaConsulta($especie, 'Los datos clínicos complementarios no son válidos.', $_POST);
            return;
        }
        $paraclinicos = [];
        $archivosParaclinicosRecibidos = $_FILES['paraclinicos'] ?? null;
        foreach ($paraclinicosRecibidos as $indice => $paraclinico) {
            if (!is_array($paraclinico)) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta($especie, 'Los datos de los paraclínicos no son válidos.', $_POST);
                return;
            }

            try {
                $archivoParaclinico = $this->obtenerArchivoSubidoParaclinico(
                    $archivosParaclinicosRecibidos,
                    $indice
                );
            } catch (InvalidArgumentException $error) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta($especie, $error->getMessage(), $_POST);
                return;
            }

            $tipoParaclinico = $paraclinico['tipo'] ?? '';
            $resultadoParaclinico = $paraclinico['resultado'] ?? '';
            if (
                !is_string($tipoParaclinico)
                || strlen(trim($tipoParaclinico)) > 100
                || !is_string($resultadoParaclinico)
                || strlen(trim($resultadoParaclinico)) > 65535
                || (
                    trim($tipoParaclinico) === ''
                    && (trim($resultadoParaclinico) !== '' || $archivoParaclinico !== null)
                )
            ) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta(
                    $especie,
                    'Completá el nombre del estudio y revisá su resultado o archivo.',
                    $_POST
                );
                return;
            }

            if (trim($tipoParaclinico) !== '') {
                $paraclinicos[] = [
                    'tipo' => trim($tipoParaclinico),
                    'resultado' => trim($resultadoParaclinico),
                    'indice_archivo' => $indice,
                ];
            }
        }
        foreach ($pronosticos as $pronostico) {
            if (!is_string($pronostico) || !in_array($pronostico, $pronosticosPermitidos, true)) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta($especie, 'El pronóstico seleccionado no es válido.', $_POST);
                return;
            }
        }
        $datos['diagnosticos'] = $diagnosticos;
        $datos['paraclinicos'] = $paraclinicos;
        $datos['pronosticos'] = array_values(array_unique($pronosticos));
        $datos['tratamiento'] = trim($tratamiento);

        $limitesCampos = [
            'peso_ExamenG' => 20,
            'CC_ExamenG' => 20,
            'TR_ExamenG' => 20,
            'FC_ExamenG' => 20,
            'FR_ExamenG' => 20,
            'TLLC_ExamenG' => 20,
            'sensorio_ExamenG' => 100,
            'facies_ExamenG' => 100,
            'piel_subcutaneo_ExamenG' => 100,
            'mucosas_ExamenG' => 100,
            'hidratacion_ExamenG' => 50,
            'aparentes_ExamenG' => 100,
            'temperatura_cascos_ExamenEq' => 50,
            'pulso_digital_ExamenEq' => 50,
            'senos_ExamenEq' => 100,
            'csd_ExamenEq' => 50,
            'cid_ExamenEq' => 50,
            'csi_ExamenEq' => 50,
            'cii_ExamenEq' => 50,
        ];
        foreach ($limitesCampos as $nombreCampo => $limite) {
            if (strlen($datos[$nombreCampo]) > $limite) {
                http_response_code(422);
                $this->mostrarFormularioNuevaConsulta(
                    $especie,
                    'Uno de los campos supera la longitud permitida.',
                    $_POST
                );
                return;
            }
        }

        $archivoResenia = null;
        $archivosParaclinicosGuardados = [];
        try {
            $archivoResenia = $this->guardarArchivoResenia($_FILES['archivo_resenia'] ?? null);
            foreach ($datos['paraclinicos'] as &$paraclinico) {
                $archivoParaclinico = $this->obtenerArchivoSubidoParaclinico(
                    $archivosParaclinicosRecibidos,
                    $paraclinico['indice_archivo']
                );
                $paraclinico['archivo'] = $this->guardarArchivoParaclinico($archivoParaclinico);
                if ($paraclinico['archivo'] !== null) {
                    $archivosParaclinicosGuardados[] = $paraclinico['archivo'];
                }
                unset($paraclinico['indice_archivo']);
            }
            unset($paraclinico);

            $datos['archivo_resenia'] = $archivoResenia;
            $consultaId = $this->model('ConsultaModel')->crear(
                $datos,
                (int) $paciente,
                $especie,
                array_map('intval', $estudiantes),
                $profesor === '' ? null : (int) $profesor
            );
        } catch (InvalidArgumentException $error) {
            $this->eliminarArchivoResenia($archivoResenia);
            $this->eliminarArchivosParaclinicos($archivosParaclinicosGuardados);
            http_response_code(422);
            $this->mostrarFormularioNuevaConsulta($especie, $error->getMessage(), $_POST);
            return;
        } catch (mysqli_sql_exception $error) {
            $this->eliminarArchivoResenia($archivoResenia);
            $this->eliminarArchivosParaclinicos($archivosParaclinicosGuardados);
            error_log('No se pudo registrar la consulta: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarFormularioNuevaConsulta($especie, 'No se pudo guardar la consulta. Revisá los datos e intentá nuevamente.', $_POST);
            return;
        } catch (RuntimeException $error) {
            $this->eliminarArchivoResenia($archivoResenia);
            $this->eliminarArchivosParaclinicos($archivosParaclinicosGuardados);
            error_log('No se pudieron procesar los archivos de la consulta: ' . $error->getMessage());
            http_response_code(500);
            $this->mostrarFormularioNuevaConsulta($especie, 'No se pudieron procesar los archivos adjuntos. Intentá nuevamente.', $_POST);
            return;
        }

        header('Location: ' . url('centro-consultas') . '?consulta=' . $consultaId);
        exit;
    }

    private function obtenerArchivoSubidoParaclinico($archivos, $indice): ?array
    {
        if ($archivos === null) {
            return null;
        }
        if (!is_array($archivos)) {
            throw new InvalidArgumentException('Los archivos de los paraclínicos no son válidos.');
        }

        $archivo = [];
        foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $campo) {
            if (
                !isset($archivos[$campo])
                || !is_array($archivos[$campo])
                || !isset($archivos[$campo][$indice])
                || !is_array($archivos[$campo][$indice])
                || !array_key_exists('archivo', $archivos[$campo][$indice])
            ) {
                throw new InvalidArgumentException('Los archivos de los paraclínicos no son válidos.');
            }
            $archivo[$campo] = $archivos[$campo][$indice]['archivo'];
        }

        if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return $archivo;
    }

    private function guardarArchivoParaclinico(?array $archivo): ?string
    {
        if ($archivo === null) {
            return null;
        }
        if (
            !isset($archivo['error'], $archivo['tmp_name'], $archivo['size'])
            || $archivo['error'] !== UPLOAD_ERR_OK
            || !is_string($archivo['tmp_name'])
            || !is_uploaded_file($archivo['tmp_name'])
        ) {
            throw new InvalidArgumentException('No se pudo recibir un archivo de paraclínico. Seleccionalo nuevamente.');
        }
        if (!is_int($archivo['size']) || $archivo['size'] < 1 || $archivo['size'] > 5 * 1024 * 1024) {
            throw new InvalidArgumentException('Cada archivo de paraclínico debe pesar hasta 5 MB.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
        $extensiones = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!is_string($mime) || !isset($extensiones[$mime])) {
            throw new InvalidArgumentException('Los paraclínicos solo aceptan PDF, JPG, PNG o WebP.');
        }

        $directorio = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'paraclinicos';
        if (!is_dir($directorio) && !@mkdir($directorio, 0750, true) && !is_dir($directorio)) {
            throw new RuntimeException('No se pudo crear el directorio privado de paraclínicos.');
        }
        if (!is_writable($directorio)) {
            throw new RuntimeException('El directorio privado de paraclínicos no tiene permisos de escritura.');
        }

        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
        $destino = $directorio . DIRECTORY_SEPARATOR . $nombreArchivo;
        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            throw new RuntimeException('No se pudo guardar un archivo de paraclínico.');
        }

        return $nombreArchivo;
    }

    private function eliminarArchivosParaclinicos(array $nombres): void
    {
        foreach ($nombres as $nombre) {
            $ruta = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'paraclinicos'
                . DIRECTORY_SEPARATOR . basename($nombre);
            if (is_file($ruta) && !unlink($ruta)) {
                error_log('No se pudo eliminar un archivo de paraclínico sin consulta asociada: ' . $ruta);
            }
        }
    }

    private function guardarArchivoResenia($archivo): ?string
    {
        if ($archivo === null) {
            return null;
        }
        if (!is_array($archivo) || !isset($archivo['error'], $archivo['tmp_name'], $archivo['size'])) {
            throw new InvalidArgumentException('El archivo de reseña enviado no es válido.');
        }
        if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($archivo['error'] !== UPLOAD_ERR_OK || !is_string($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            throw new InvalidArgumentException('No se pudo recibir la imagen de la reseña. Seleccioná el archivo nuevamente.');
        }
        if (!is_int($archivo['size']) || $archivo['size'] < 1 || $archivo['size'] > 5 * 1024 * 1024) {
            throw new InvalidArgumentException('La imagen de reseña debe pesar hasta 5 MB.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
        $extensiones = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!is_string($mime) || !isset($extensiones[$mime])) {
            throw new InvalidArgumentException('La reseña debe ser una imagen JPG, PNG o WebP.');
        }

        $directorio = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'resenas';
        if (!is_dir($directorio) && !@mkdir($directorio, 0750, true) && !is_dir($directorio)) {
            throw new RuntimeException('No se pudo crear el directorio privado de reseñas.');
        }
        if (!is_writable($directorio)) {
            throw new RuntimeException('El directorio privado de reseñas no tiene permisos de escritura.');
        }

        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
        $destino = $directorio . DIRECTORY_SEPARATOR . $nombreArchivo;
        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            throw new RuntimeException('No se pudo guardar la imagen recibida.');
        }

        return $nombreArchivo;
    }

    private function eliminarArchivoResenia(?string $nombreArchivo): void
    {
        if ($nombreArchivo === null) {
            return;
        }

        $ruta = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'resenas'
            . DIRECTORY_SEPARATOR . basename($nombreArchivo);
        if (is_file($ruta) && !unlink($ruta)) {
            error_log('No se pudo eliminar un archivo de reseña sin consulta asociada: ' . $ruta);
        }
    }

    public function mostrarResenia(): void
    {
        Auth::requireLogin();

        $reseniaId = $_GET['id'] ?? '';
        if (!is_string($reseniaId) || !ctype_digit($reseniaId) || (int) $reseniaId < 1) {
            http_response_code(400);
            echo 'El identificador de la reseña no es válido.';
            return;
        }

        try {
            $nombreArchivo = $this->model('ConsultaModel')->obtenerArchivoResenia((int) $reseniaId);
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo consultar la imagen de reseña: ' . $error->getMessage());
            http_response_code(500);
            echo 'No se pudo cargar la imagen de la reseña.';
            return;
        }

        if ($nombreArchivo === null || basename($nombreArchivo) !== $nombreArchivo) {
            http_response_code(404);
            echo 'No se encontró la imagen de reseña.';
            return;
        }

        $ruta = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'resenas'
            . DIRECTORY_SEPARATOR . $nombreArchivo;
        $rutaReal = realpath($ruta);
        $directorioReal = realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'resenas');
        if ($rutaReal === false || $directorioReal === false || !str_starts_with($rutaReal, $directorioReal . DIRECTORY_SEPARATOR) || !is_file($rutaReal)) {
            http_response_code(404);
            echo 'No se encontró la imagen de reseña.';
            return;
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($rutaReal);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            http_response_code(415);
            echo 'El archivo de reseña no tiene un formato permitido.';
            return;
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($rutaReal));
        header('Content-Disposition: inline; filename="resena.' . pathinfo($nombreArchivo, PATHINFO_EXTENSION) . '"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');
        readfile($rutaReal);
    }

    public function mostrarArchivoParaclinico(): void
    {
        Auth::requireLogin();

        $paraclinicoId = $_GET['id'] ?? '';
        if (!is_string($paraclinicoId) || !ctype_digit($paraclinicoId) || (int) $paraclinicoId < 1) {
            http_response_code(400);
            echo 'El identificador del paraclínico no es válido.';
            return;
        }

        try {
            $nombreArchivo = $this->model('ConsultaModel')->obtenerArchivoParaclinico((int) $paraclinicoId);
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo consultar el archivo del paraclínico: ' . $error->getMessage());
            http_response_code(500);
            echo 'No se pudo cargar el archivo del paraclínico.';
            return;
        }

        if ($nombreArchivo === null || basename($nombreArchivo) !== $nombreArchivo) {
            http_response_code(404);
            echo 'No se encontró el archivo del paraclínico.';
            return;
        }

        $directorio = ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'paraclinicos';
        $rutaReal = realpath($directorio . DIRECTORY_SEPARATOR . $nombreArchivo);
        $directorioReal = realpath($directorio);
        if (
            $rutaReal === false
            || $directorioReal === false
            || !str_starts_with($rutaReal, $directorioReal . DIRECTORY_SEPARATOR)
            || !is_file($rutaReal)
        ) {
            http_response_code(404);
            echo 'No se encontró el archivo del paraclínico.';
            return;
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($rutaReal);
        $tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'];
        if (!is_string($mime) || !in_array($mime, $tiposPermitidos, true)) {
            http_response_code(415);
            echo 'El archivo del paraclínico no tiene un formato permitido.';
            return;
        }

        $disposicion = $mime === 'application/pdf' ? 'attachment' : 'inline';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($rutaReal));
        header('Content-Disposition: ' . $disposicion . '; filename="paraclinico.' . pathinfo($nombreArchivo, PATHINFO_EXTENSION) . '"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');
        readfile($rutaReal);
    }

    public function actualizarEstado(): void
    {
        Auth::requireLogin();
        if (!$this->puedeCambiarEstado()) {
            http_response_code(403);
            echo 'Solo profesores y directores pueden cambiar el estado de una consulta.';
            return;
        }

        $consultaId = $_POST['consulta_id'] ?? '';
        $vista = $_POST['vista'] ?? 'listado';
        $urlRetorno = $this->urlRetornoEstado($consultaId, $vista);

        $token = $_POST['csrf_token'] ?? null;
        if (!is_string($token) || !Csrf::validar($token)) {
            $_SESSION['consulta_estado_error'] = 'El formulario venció. Volvé a intentarlo.';
            header('Location: ' . $urlRetorno, true, 303);
            exit;
        }

        $estado = $_POST['estado'] ?? '';
        if (!is_string($consultaId) || !ctype_digit($consultaId) || (int) $consultaId < 1 || !is_string($estado)) {
            $_SESSION['consulta_estado_error'] = 'La consulta o el estado seleccionado no son válidos.';
            header('Location: ' . $urlRetorno, true, 303);
            exit;
        }

        try {
            $this->model('ConsultaModel')->actualizarEstado((int) $consultaId, $estado);
            $_SESSION['consulta_estado_mensaje'] = 'El estado de la consulta se actualizó correctamente.';
        } catch (InvalidArgumentException $error) {
            $_SESSION['consulta_estado_error'] = $error->getMessage();
        } catch (mysqli_sql_exception $error) {
            error_log('No se pudo actualizar el estado de la consulta: ' . $error->getMessage());
            $_SESSION['consulta_estado_error'] = 'No se pudo actualizar el estado. Intentá nuevamente.';
        }

        header('Location: ' . $urlRetorno, true, 303);
        exit;
    }

    private function urlRetornoEstado($consultaId, $vista): string
    {
        if ($vista === 'detalle' && is_string($consultaId) && ctype_digit($consultaId) && (int) $consultaId > 0) {
            return url('centro-consultas') . '?consulta=' . (int) $consultaId;
        }

        $parametros = [];
        $busqueda = $_POST['return_q'] ?? '';
        if (is_string($busqueda) && strlen($busqueda) <= 100 && $busqueda !== '') {
            $parametros['q'] = $busqueda;
        }

        $estado = $_POST['return_estado'] ?? '';
        if (is_string($estado) && in_array($estado, ['', 'Pendiente', 'En curso', 'Finalizada'], true) && $estado !== '') {
            $parametros['estado'] = $estado;
        }

        $tipo = $_POST['return_tipo'] ?? '';
        if (is_string($tipo) && in_array($tipo, ['', 'Consulta general', 'Consulta equina', 'Control', 'Hospitalización'], true) && $tipo !== '') {
            $parametros['tipo'] = $tipo;
        }

        foreach (['return_paciente' => 'paciente'] as $campo => $parametro) {
            $valor = $_POST[$campo] ?? '';
            if (is_string($valor) && ctype_digit($valor) && (int) $valor > 0) {
                $parametros[$parametro] = (string) (int) $valor;
            }
        }

        $query = http_build_query($parametros);
        return url('centro-consultas') . ($query === '' ? '' : '?' . $query);
    }

    private function puedeCrearConsultas(): bool
    {
        return in_array(strtoupper(trim(Auth::rol() ?? '')), ['ESTUDIANTE', 'PROFESOR', 'DIRECTOR'], true);
    }

    private function puedeCambiarEstado(): bool
    {
        return in_array(strtoupper(trim(Auth::rol() ?? '')), ['PROFESOR', 'DIRECTOR'], true);
    }

    private function mostrarFormularioNuevaConsulta(
        string $especie,
        ?string $error = null,
        array $oldInput = [],
        ?string $mensajePaciente = null
    ): void {
        $consultaModel = $this->model('ConsultaModel');
        $this->view(
            'dashboard/nueva-consulta',
            [
                'especieSeleccionada' => $especie,
                'pacientes' => $consultaModel->listarPacientesPorEspecie($especie),
                'estudiantes' => $consultaModel->listarEstudiantes(),
                'profesores' => $consultaModel->listarProfesores(),
                'csrfToken' => Csrf::token(),
                'errorFormulario' => $error,
                'mensajePaciente' => $mensajePaciente,
                'oldInput' => $oldInput,
            ],
            'app',
            ['app-layout.css', 'nueva-consulta.css'],
            ['dashboard.js', 'nueva-consulta.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    /**
     * Lee `?busqueda=` de la URL y muestra coincidencias de pacientes.
     * Si falta el término, deja el centro listo para continuar el desarrollo.
     */
    public function index(): void
    {
        Auth::requireLogin();
        $mensajeExito = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);
        $mensajeEstado = $_SESSION['consulta_estado_mensaje'] ?? null;
        $errorEstado = $_SESSION['consulta_estado_error'] ?? null;
        unset($_SESSION['consulta_estado_mensaje'], $_SESSION['consulta_estado_error']);

        if (array_key_exists('consulta', $_GET)) {
            $consultaId = $_GET['consulta'];
            if (!is_string($consultaId) || !ctype_digit($consultaId) || (int) $consultaId < 1) {
                http_response_code(400);
                $this->mostrarDetalleConsulta(null, 'El identificador de la consulta no es válido.', $mensajeEstado, $errorEstado);
                return;
            }

            $detalle = $this->model('ConsultaModel')->obtenerDetalle((int) $consultaId);
            if ($detalle === null) {
                http_response_code(404);
                $this->mostrarDetalleConsulta(null, 'No se encontró la consulta solicitada.', $mensajeEstado, $errorEstado);
                return;
            }

            $this->mostrarDetalleConsulta($detalle, null, $mensajeEstado, $errorEstado);
            return;
        }

        $busqueda = $_GET['busqueda'] ?? null;
        $errorBusqueda = isset($_GET['error']) ? 'Ingresa un término de búsqueda válido.' : null;
        $pacientes = [];

        if ($busqueda !== null) {
            if (!is_string($busqueda)) {
                http_response_code(400);
                $errorBusqueda = 'El término de búsqueda no es válido.';
                $busqueda = '';
            } else {
                $busqueda = trim($busqueda);
                if ($busqueda === '' || strlen($busqueda) > 100) {
                    $errorBusqueda = 'Ingresa un término de búsqueda de hasta 100 caracteres.';
                } else {
                    $pacientes = $this->model('PacienteModel')->buscar($busqueda);
                }
            }
        }

        $errorFiltros = null;
        $q = $_GET['q'] ?? '';
        if (!is_string($q)) {
            http_response_code(400);
            $errorFiltros = 'Uno o más filtros enviados no son válidos.';
            $q = '';
        } else {
            $q = trim($q);
            if (strlen($q) > 100) {
                http_response_code(400);
                $errorFiltros = 'Uno o más filtros enviados no son válidos.';
                $q = '';
            }
        }

        $estadosPermitidos = ['', 'Pendiente', 'En curso', 'Finalizada'];
        $estado = $_GET['estado'] ?? '';
        if (!is_string($estado) || !in_array($estado, $estadosPermitidos, true)) {
            http_response_code(400);
            $errorFiltros = 'Uno o más filtros enviados no son válidos.';
            $estado = '';
        }

        $tiposPermitidos = ['', 'Consulta general', 'Consulta equina', 'Control', 'Hospitalización'];
        $tipo = $_GET['tipo'] ?? '';
        if (!is_string($tipo) || !in_array($tipo, $tiposPermitidos, true)) {
            http_response_code(400);
            $errorFiltros = 'Uno o más filtros enviados no son válidos.';
            $tipo = '';
        }

        $paciente = $_GET['paciente'] ?? '';
        if (
            !is_string($paciente)
            || ($paciente !== '' && (!ctype_digit($paciente) || (int) $paciente < 1))
        ) {
            http_response_code(400);
            $errorFiltros = 'Uno o más filtros enviados no son válidos.';
            $paciente = '';
        }

        $filtros = [
            'q' => $q,
            'estado' => $estado,
            'tipo' => $tipo,
            'paciente' => $paciente,
        ];

        $consultaModel = $this->model('ConsultaModel');
        $consultas = $consultaModel->listar($filtros);
        $resumen = $consultaModel->obtenerResumen();
        $puedeCambiarEstado = $this->puedeCambiarEstado();
        $proximosControles = $consultaModel->obtenerProximosControles();

        $urlCentro = '/centro-consultas';
        $hospitalizadosDashboard = $this->model('DashboardModel')->obtenerPacientesHospitalizados();
        $hospitalizados = [];
        foreach ($hospitalizadosDashboard as $hospitalizado) {
            $hospitalizados[] = [
                'paciente' => $hospitalizado['nombre_paciente'],
                'motivo' => $hospitalizado['motivo'],
                'ingreso' => $hospitalizado['fecha_ingreso'],
            ];
        }

        $this->view(
            'dashboard/centro-consultas',
            [
                'urlCentro' => $urlCentro,
                'busqueda' => $busqueda,
                'pacientes' => $pacientes,
                'errorBusqueda' => $errorBusqueda,
                'errorFiltros' => $errorFiltros,
                'filtros' => $filtros,
                'resumen' => $resumen,
                'consultas' => $consultas,
                'hospitalizados' => $hospitalizados,
                'proximosControles' => $proximosControles,
                'puedeCambiarEstado' => $puedeCambiarEstado,
                'csrfToken' => Csrf::token(),
                'mensajeEstado' => $mensajeEstado,
                'mensajeExito' => $mensajeExito,
                'errorEstado' => $errorEstado,
            ],
            'app',
            ['app-layout.css','centro-consulta.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }

    private function mostrarDetalleConsulta(
        ?array $detalle,
        ?string $errorDetalle = null,
        ?string $mensajeEstado = null,
        ?string $errorEstado = null
    ): void
    {
        $this->view(
            'dashboard/consulta-detalle',
            [
                'detalle' => $detalle,
                'errorDetalle' => $errorDetalle,
                'csrfToken' => Csrf::token(),
                'mensajeEstado' => $mensajeEstado,
                'errorEstado' => $errorEstado,
            ],
            'app',
            ['app-layout.css', 'centro-consulta.css'],
            ['dashboard.js'],
            ['bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'jquery-4.0.0.min.js.js']
        );
    }
}
