<?php

class TutorModel extends Model
{
    /**
     * Busca por nombre, documento o teléfono.
     *
     * @return list<array<string, mixed>>
     */
    public function buscar(string $texto = ''): array
    {
        $texto = trim($texto);
        $termino = '%' . $texto . '%';

        $stmt = $this->db->prepare(
            "SELECT
                t.id_Tutor AS id,
                t.documento_Tutor AS documento,
                t.nombre_Tutor AS nombre,
                t.telefono_Tutor AS telefono,
                t.email_Tutor AS email,
                COUNT(p.id_Paciente) AS cantidad_pacientes
             FROM TUTOR t
             LEFT JOIN PACIENTE p ON p.id_Tutor = t.id_Tutor
             WHERE (? = ''
                OR t.nombre_Tutor LIKE ?
                OR t.documento_Tutor LIKE ?
                OR t.telefono_Tutor LIKE ?)
             GROUP BY
                t.id_Tutor,
                t.documento_Tutor,
                t.nombre_Tutor,
                t.telefono_Tutor,
                t.email_Tutor
             ORDER BY t.nombre_Tutor ASC
             LIMIT 100"
        );

        $stmt->bind_param(
            'ssss',
            $texto,
            $termino,
            $termino,
            $termino
        );

        $stmt->execute();
        $tutores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $tutores;
    }

    /**
     * Registra un tutor nuevo.
     *
     * @param array<string, mixed> $datos
     */
    public function crear(array $datos): int
    {
        $documento = $this->valor($datos, 'documento_Tutor');
        $nombre = $this->valor($datos, 'nombre_Tutor');
        $telefono = $this->valor($datos, 'telefono_Tutor');
        $direccion = $this->valor($datos, 'direccion_Tutor');
        $email = $this->valor($datos, 'email_Tutor');
        $notas = $this->valor($datos, 'notas_Tutor');

        if ($documento === null || $nombre === null) {
            throw new InvalidArgumentException(
                'El documento y el nombre son obligatorios.'
            );
        }

        if (
            strlen($documento) > 20 ||
            strlen($nombre) > 150 ||
            ($telefono !== null && strlen($telefono) > 50) ||
            ($direccion !== null && strlen($direccion) > 255) ||
            ($email !== null && strlen($email) > 150)
        ) {
            throw new InvalidArgumentException(
                'Uno de los campos supera la longitud permitida.'
            );
        }

        if (
            $email !== null &&
            filter_var($email, FILTER_VALIDATE_EMAIL) === false
        ) {
            throw new InvalidArgumentException(
                'Ingresá un correo electrónico válido.'
            );
        }

        // Comprobar si el documento ya existe.
        $stmt = $this->db->prepare(
            'SELECT id_Tutor
             FROM TUTOR
             WHERE documento_Tutor = ?
             LIMIT 1'
        );

        $stmt->bind_param('s', $documento);
        $stmt->execute();

        $existe = $stmt->get_result()->fetch_assoc() !== null;
        $stmt->close();

        if ($existe) {
            throw new InvalidArgumentException(
                'Ya existe un tutor registrado con ese documento.'
            );
        }

        $stmt = $this->db->prepare(
            'INSERT INTO TUTOR (
                documento_Tutor,
                nombre_Tutor,
                telefono_Tutor,
                direccion_Tutor,
                email_Tutor,
                notas_Tutor
            ) VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'ssssss',
            $documento,
            $nombre,
            $telefono,
            $direccion,
            $email,
            $notas
        );

        $stmt->execute();
        $id = (int) $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    private function valor(array $datos, string $campo): ?string
    {
        $valor = $datos[$campo] ?? '';

        if (!is_string($valor)) {
            throw new InvalidArgumentException(
                'Los datos del formulario no tienen un formato válido.'
            );
        }

        $valor = trim($valor);

        return $valor === '' ? null : $valor;
    }
}
