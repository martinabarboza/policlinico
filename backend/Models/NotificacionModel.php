<?php

/** Lee alertas derivadas de la vista SQL, restringidas al usuario asignado. */
class NotificacionModel extends Model
{
	/**
	 * Devuelve consultas vencidas aún abiertas para un usuario.
	 * Un límite null entrega el listado completo; un valor positivo sirve para el navbar.
	 *
	 * @return list<array{
	 *     id_consulta: int,
	 *     fecha_hora: string,
	 *     estado: string,
	 *     tipo: string|null,
	 *     motivo: string|null,
	 *     nombre_paciente: string,
	 *     nombre_tutor: string
	 * }>
	 */
	public function obtenerAlertasAtrasadasPorUsuario(int $usuarioId, ?int $limite = null): array
	{
		if ($usuarioId < 1) {
			throw new InvalidArgumentException('El usuario seleccionado no es válido.');
		}
		if ($limite !== null && $limite < 1) {
			throw new InvalidArgumentException('El límite de alertas debe ser positivo.');
		}

		$limiteSql = $limite === null ? '' : ' LIMIT ?';
		$stmt = $this->db->prepare(
			"SELECT
				id_Consulta AS id_consulta,
				fecha_hora_Consulta AS fecha_hora,
				estado_proceso_Consulta AS estado,
				tipo_Consulta AS tipo,
				motivo_Consulta AS motivo,
				nombre_Paciente AS nombre_paciente,
				nombre_Tutor AS nombre_tutor
			 FROM vw_alertas_consultas_atrasadas
			 WHERE id_usuario = ?
			 ORDER BY fecha_hora_Consulta ASC" . $limiteSql
		);
		if ($limite === null) {
			$stmt->bind_param('i', $usuarioId);
		} else {
			$stmt->bind_param('ii', $usuarioId, $limite);
		}
		$stmt->execute();
		$alertas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		$stmt->close();

		return $alertas;
	}
}
