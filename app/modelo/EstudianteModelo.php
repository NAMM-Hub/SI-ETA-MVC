<?php
class EstudianteModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function generarOpcionAnoGrado($grado){
		$ano_grado = 7;
		$html_opcion = '';
		for ($i=1; $i < $ano_grado; $i++) { 
			$selected = ($i == $grado) ? 'selected' : '';
			$html_opcion .= '<option value='.$i.' '.$selected.'>' .$i. 'º año</option>'.PHP_EOL;
		}

		return $html_opcion;
	}

	public function obtener_estatus_estudinate(): array{
		return [['valor'=>'inscrito', 'texto'=>'Inscrito'],
				['valor'=>'expulsado', 'texto'=>'Expulsado'],
				['valor'=>'retirado', 'texto'=>'Retirado'],
				['valor'=>'graduado', 'texto'=>'Graduado']
				];
	}

	public function insertarEstudianteDB($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE); 
		try{
			$stmtPersona = $conexion->prepare("INSERT INTO persona(nombre1, nombre2, apellido1, apellido2, cedula, sexo, fecha_nacimiento, estado_civil) VALUES(?,?,?,?,?,?,?,?)");
				if ($stmtPersona === false) {
					$error_log = "Error en al consulta de persona para insertar estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtPersona->bind_param("ssssisss",
										$data['nombre1'],
										$data['nombre2'],
										$data['apellido1'],
										$data['apellido2'],
										$data['cedula'],
										$data['sexo'],
										$data['fecha_na'],
										$data['estadCivil']);
			if(!$stmtPersona->execute()){
				$error_log = "Error al ejecutar la consulta de persona para insertar estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$id_persona_insertada = $conexion->insert_id;
			$stmtPersona->close();

			$stmtUbicacion = $conexion->prepare("INSERT INTO ubicacion(id_persona_ubicacion, id_estado, id_municipio_u, id_comunidad, municipio_texto, ciudad_comunidad_texto) VALUES(?,?,?,?,?,?)");
				if ($stmtUbicacion === false) {
					$error_log = "Error en la consulta de ubicación para insertar estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUbicacion->bind_param("iiiiss",
										$id_persona_insertada,
										$data['id_estado'],
										$data['id_municipio'],
										$data['id_comunidad'],
										$data['municipio_texto'],
										$data['comunidad_texto']);
			if (!$stmtUbicacion->execute()) {
				$error_log = "Error al ejecutar la consulta de ubicación para insertar estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUbicacion->close();

			$stmtEstudiante = $conexion->prepare("INSERT INTO estudiante(persona_id, ano_grado, fecha_inscripcion, periodo_escolar_id, estatus) VALUES(?,?,?,?,?)");
				if ($stmtEstudiante === false) {
					$error_log = "Error en la consulta de estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtEstudiante->bind_param("issis",
										$id_persona_insertada,
										$data['ano_grado'],
										$data['fecha_inscripcion'],
										$data['periodo_escolar'],
										$data['estatus']);
			if (!$stmtEstudiante->execute()) {
				$error_log = "Error en la consulta de estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtEstudiante->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function obtener_estudianteDB($id_estudiante){
		$conexion = $this->db->getConexion();
		$stmtEstudiante = $conexion->prepare("SELECT
													e.persona_id,
													e.fecha_inscripcion,
													e.ano_grado,
													e.estatus,
													p.nombre1,
													p.nombre2,
													p.apellido1,
													p.apellido2,
													p.cedula,
													p.sexo,
													p.fecha_nacimiento,
													p.estado_civil,
													p_e.id_perido_escolar,
													CONCAT(p_e.ano_periodo1,' - ', p_e.ano_periodo2) as periodo_escolar,
													edo.id_estados,
													edo.nombre_estado,
													u.municipio_texto,
													u.ciudad_comunidad_texto,
													m.id_municipio,
													m.nombre_municipio,
													c.id_comunidad,
													c.nombre_comunidad,
													YEAR(CURDATE()) - YEAR(p.fecha_nacimiento) as edad_aproximada
												FROM
												estudiante as e
												INNER JOIN persona as p ON p.id_persona = e.persona_id
												LEFT JOIN periodo_escolar as p_e ON e.periodo_escolar_id = p_e.id_perido_escolar
												INNER JOIN ubicacion as u ON u.id_persona_ubicacion = p.id_persona
												LEFT JOIN estados as edo ON edo.id_estados = u.id_estado
												LEFT JOIN municipios as m ON u.id_municipio_u = m.id_municipio
												LEFT JOIN comunidades as c ON u.id_comunidad = c.id_comunidad
												WHERE e.persona_id = ?");
			if ($stmtEstudiante === false) {
				$error_log = "Error en la consulta de estudiante para obtener estudiante: ".$conexion->error;
				Database::log_error($error_log);
				return null;
			}
		$stmtEstudiante->bind_param("i",$id_estudiante);
			if (!$stmtEstudiante->execute()) {
				$error_log = "Error en ejecutar la consulta de estudiante para obtener estudiante: ".$stmtEstudiante->error;
				Database::log_error($error_log);
				return null;
			}
		$resultados = $stmtEstudiante->get_result();

		$datosEstudiante[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() : null;

		$stmtEstudiante->close();
		return $datosEstudiante;
	}

	public function obtener_personaEstudiante($id_estudiante){
		$conexion = $this->db->getConexion();
		$stmtPersonaEstudiante = $conexion->prepare("SELECT
														p.nombre1,
														p.nombre2,
														p.apellido1,
														p.apellido2,
														p.cedula,
														p.sexo,
														p.fecha_nacimiento,
														p.estado_civil
													FROM estudiante as es
													INNER JOIN persona as p ON p.id_persona = es.persona_id 
													WHERE es.persona_id = ?");
			if($stmtPersonaEstudiante === false){
				$error_log = "Error en la consulta de estudiante para obtener datos persona: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtPersonaEstudiante->bind_param("i",$id_estudiante);
			if (!$stmtPersonaEstudiante->execute()) {
				$error_log = "Error en ejecutar la consulta de estudiante para obtener persona: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultados = $stmtPersonaEstudiante->get_result();

		$datosPersonaEstudiante[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() : null;
		$stmtPersonaEstudiante->close();
		return $datosPersonaEstudiante;
	}

	public function obtener_academicoEstudiante($id_estudiante){
		$conexion = $this->db->getConexion();

		$stmtAcademicoEstudiante = $conexion->prepare("SELECT
														p.nombre1,
														p.apellido1,
														p.cedula,
														e.fecha_inscripcion,
														e.ano_grado,
														e.estatus,
														p_e.id_perido_escolar,
														CONCAT(p_e.ano_periodo1,' - ', p_e.ano_periodo2) as periodo_escolar
													FROM persona as p
													INNER JOIN estudiante as e ON e.persona_id = p.id_persona
													LEFT JOIN periodo_escolar as p_e ON e.periodo_escolar_id = p_e.id_perido_escolar
													WHERE p.id_persona = ?");
			if($stmtAcademicoEstudiante === false){
				$error_log = "Error en al consulta de estudiante para obtener datos academicos: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtAcademicoEstudiante->bind_param("i", $id_estudiante);
			if (!$stmtAcademicoEstudiante->execute()) {
				$error_log = "Error en ejecutar la consulta de estudiante para obtener datos academicos: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultados = $stmtAcademicoEstudiante->get_result();
		$datosAcademicosEstudiante[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() : null;

		$stmtAcademicoEstudiante->close();
		return $datosAcademicosEstudiante;
	}

	public function obtener_ubicacionEstudiante($id_estudiante){
		$conexion = $this->db->getConexion();
		$stmtUbicacionEstudiante = $conexion->prepare("SELECT
													p.nombre1,
													p.apellido1,
													p.cedula,
													edo.id_estados,
													edo.nombre_estado,
													u.municipio_texto,
													u.ciudad_comunidad_texto,
													m.id_municipio,
													m.nombre_municipio,
													c.id_comunidad,
													c.nombre_comunidad
												FROM
												persona as p
												INNER JOIN ubicacion as u ON u.id_persona_ubicacion = p.id_persona
												LEFT JOIN estados as edo ON edo.id_estados = u.id_estado
												LEFT JOIN municipios as m ON u.id_municipio_u = m.id_municipio
												LEFT JOIN comunidades as c ON u.id_comunidad = c.id_comunidad
												WHERE p.id_persona = ?");
			if ($stmtUbicacionEstudiante === false) {
				$error_log = "Error en la consulta de estudiante para obtener ubicacion: ".$conexion->error;
				Database::log_error($error_log);
				return null;
			}
		$stmtUbicacionEstudiante->bind_param("i",$id_estudiante);
			if (!$stmtUbicacionEstudiante->execute()) {
				$error_log = "Error en ejecutar la consulta de estudiante para obtener ubicacion: ".$stmtUbicacionEstudiante->error;
				Database::log_error($error_log);
				return null;
			}
		$resultados = $stmtUbicacionEstudiante->get_result();

		$datosUbicacionEstudiante[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() : null;

		$stmtUbicacionEstudiante->close();
		return $datosUbicacionEstudiante;
	}

	public function obtener_listaEstudiantes(){
		$conexion = $this->db->getConexion();
		$sql = "SELECT
			e.persona_id,
			e.ano_grado,
			e.periodo_escolar_id,
			e.estatus,
			p.id_persona,
			p.nombre1,
			p.apellido1,
			p.cedula,
			p.sexo,
			p.fecha_nacimiento,
			pe.ano_periodo1,
			pe.ano_periodo2
		FROM estudiante AS e
		INNER JOIN persona AS p ON p.id_persona = e.persona_id
		LEFT JOIN periodo_escolar AS pe ON e.periodo_escolar_id = pe.id_perido_escolar";
		$stmtConsultaEstudiante = $conexion->prepare($sql);
			if ($stmtConsultaEstudiante === false) {
				$error_log = "Error en la consulta de persona para estudiante:".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al obtener los datos de estudiantes";
				return $error;
			}
		if (!$stmtConsultaEstudiante->execute()) {
			$error_log = "Error al ejecutar la consulta de persona para obtener datos de estudiantes: ".$conexion->error;
			Database::log_error($error_log);
			$error = "Error en el proceso de obtener los datos de estudiantes";
			return $error;
		}
		$resultado = $stmtConsultaEstudiante->get_result();
		$lista_estudiante = [];
			while($row = $resultado->fetch_object()){
				$lista_estudiante[] = $row;
			}
		$stmtConsultaEstudiante->close();
		return $lista_estudiante;
	}

	public function obtener_estudiantes_reporte($id_periodo, $ano_grado){
		$conexion = $this->db->getConexion();
		$stmtEstudiante = $conexion->prepare("SELECT
													p.nombre1,
													p.nombre2,
													p.apellido1,
													p.apellido2,
													p.cedula as C_I,
													p.sexo as Sexo
												FROM estudiante as e
												INNER JOIN persona as p ON p.id_persona = e.persona_id
												LEFT JOIN periodo_escolar as p_e ON e.periodo_escolar_id = p_e.id_perido_escolar
												WHERE e.periodo_escolar_id = ? and e.ano_grado = ?");
			if ($stmtEstudiante === false) {
				Database::log_error("Error en la consulta de estudiante para obtener datos estudiante".$conexion->error);
				$errores[] = "Error al obtener datos de estudiantes";
				return $errores;
			}
		$stmtEstudiante->bind_param("is",$id_periodo, $ano_grado);
			if (!$stmtEstudiante->execute()) {
				Database::log_error("Error al ejecutar la consulta de estudiantes para obtener datos de estudiantes: ".$stmtEstudiante->error);
				$errores[] = "Error al intentar obtener datos de estudiantes";
				return $errores;
			}
		$resultados = $stmtEstudiante->get_result();
		$lista_estudiante = [];

			while($row = $resultados->fetch_object()){
				$lista_estudiante[] = $row;
			}

		$stmtEstudiante->close();
		return $lista_estudiante;
	}

	public function update_personaEstudiante($data, $id){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
		$stmtUpdatePersonaEstudiante = $conexion->prepare("UPDATE persona set
																nombre1 = ?,
																nombre2 = ?,
																apellido1 = ?,
																apellido2 = ?,
																sexo = ?,
																fecha_nacimiento = ?,
																estado_civil = ?
															WHERE id_persona = ?");
				if ($stmtUpdatePersonaEstudiante === false) {
					$error_log = "Error en la consulta para actualizar datos estudiante persona: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdatePersonaEstudiante->bind_param("sssssssi",
														$data['nombre1'],
														$data['nombre2'],
														$data['apellido1'],
														$data['apellido2'],
														$data['sexo'],
														$data['fecha_nacimiento'],
														$data['estado_civil'],
														$id);
			if (!$stmtUpdatePersonaEstudiante->execute()) {
				$error_log = "Error en ejecutar la consulta para actualizar datos estudiante persona: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdatePersonaEstudiante->close();
			$conexion->commit();
			$conexion->autocommit(TRUE);

			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function update_academicoEstudiante($data, $id){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdateAcademica = $conexion->prepare("UPDATE estudiante set
														ano_grado = ?,
														periodo_escolar_id = ?,
														estatus = ?
														WHERE persona_id = ?");
				if ($stmtUpdateAcademica === false) {
					$error_log = "Error en al consulta de estudiante para actualizar datos relacionados al estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateAcademica->bind_param("iisi",
												$data['ano_grado'],
												$data['periodo_escolar'],
												$data['estatus'],
												$id);
			if (!$stmtUpdateAcademica->execute()) {
				$error_log = "Error en ejecutar la consulta para actualizar datos de estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdateAcademica->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function update_estudianteUbicacion($data, $id){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdateUbicacion = $conexion->prepare("UPDATE ubicacion set
															id_estado = ?,
															id_municipio_u = ?,
															id_comunidad = ?,
															municipio_texto = ?,
															ciudad_comunidad_texto = ?
													WHERE id_persona_ubicacion = ?");
			if ($stmtUpdateUbicacion === false) {
				$error_log = "Error en la consulta para actualizar ubicacion de estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}

			$stmtUpdateUbicacion->bind_param("iiissi",
											$data['estado_id'],
											$data['municipio_id'],
											$data['comunidad_id'],
											$data['municipio_texto'],
											$data['comunidad_texto'],
											$id);
			if (!$stmtUpdateUbicacion->execute()) {
				$error_log = "Error en ejecutar la consulta de ubicacion relacionada a estudiante para actualizar datos: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdateUbicacion->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function update_estudiante_todo($data, $id){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdatePersona = $conexion->prepare("UPDATE persona set
													nombre1 = ?,
													nombre2 = ?,
													apellido1 = ?,
													apellido2 = ?,
													sexo = ?,
													fecha_nacimiento = ?,
													estado_civil = ?
													WHERE id_persona = ?");
				if ($stmtUpdatePersona === false) {
					$error_log = "Error en la consulta de actualizar datos de personal de estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdatePersona->bind_param("sssssssi",
											$data['nombre1'],
											$data['nombre2'],
											$data['apellido1'],
											$data['apellido2'],
											$data['sexo'],
											$data['fecha_nacimiento'],
											$data['estado_civil'],
											$id);
				if (!$stmtUpdatePersona->execute()) {
					$error_log = "Error al ejecutar la consulta para actualizar datos personal del estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdatePersona->close();

		$stmtUpdateEstudiante = $conexion->prepare("UPDATE estudiante SET
														ano_grado = ?,
														periodo_escolar_id = ?,
														estatus = ?
														WHERE persona_id = ?");
				if ($stmtUpdateEstudiante === false) {
					$error_log = "Error en la consulta de estudiante para actualizar estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateEstudiante->bind_param("iisi",
												$data['ano_grado'],
												$data['periodo_escolar'],
												$data['estatus'],
												$id);
			if(!$stmtUpdateEstudiante->execute()){
				$error_log = "Error al ejecutar la consulta para actualizar datos de estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdateEstudiante->close();

			$stmtUpdateUbicacion = $conexion->prepare("UPDATE ubicacion set
															id_estado = ?,
															id_municipio_u = ?,
															id_comunidad = ?,
															municipio_texto = ?,
															ciudad_comunidad_texto = ?
														WHERE id_persona_ubicacion = ?");
				if ($stmtUpdateUbicacion === false) {
					$error_log = "Error en la consulta para actualizar la ubicacion relacionada al estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateUbicacion->bind_param("iiissi",
												$data['estado_id'],
												$data['municipio_id'],
												$data['comunidad_id'],
												$data['municipio_texto'],
												$data['comunidad_texto'],
												$id);
				if (!$stmtUpdateUbicacion->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar datos relacionados con la ubicacion del estudiante: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateUbicacion->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function delete_estudiante($id_estudiante){
		$conexion = $this->db->getConexion();
		$stmtDeleteEstudiante = $conexion->prepare("DELETE FROM persona WHERE id_persona = ?");
			if ($stmtDeleteEstudiante === false) {
				$error_log = "Error en la consulta para eliminar datos de estudiante: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteEstudiante->bind_param("i",$id_estudiante);
			if(!$stmtDeleteEstudiante->execute()){
				$error_log = "Error en ejecutar la consulta para eliminar datos estudiante: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteEstudiante->close();
		return true;
	}
}