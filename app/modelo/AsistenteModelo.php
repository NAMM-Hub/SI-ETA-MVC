<?php
class AsistenteModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function insertarAsistenteDB($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtPersona = $conexion->prepare("INSERT INTO persona(nombre1, nombre2, apellido1, apellido2, cedula, sexo, fecha_nacimiento, estado_civil) VALUES(?,?,?,?,?,?,?,?)");
				if ($stmtPersona === false) {
					$error_log = "Error en la consulta de persona para insertar asistente: ".$conexion->error;
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
					$error_log = "Error al ejecutar la consulta de persona para insertar asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$id_persona_insertada = $conexion->insert_id;
			$stmtPersona->close();

			$stmtUbicacion = $conexion->prepare("INSERT INTO ubicacion(id_persona_ubicacion, id_estado, id_municipio_u, id_comunidad, municipio_texto, ciudad_comunidad_texto) VALUES(?,?,?,?,?,?)");
				if ($stmtUbicacion === false) {
					$error_log = "Error en la consulta de ubicacion para asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUbicacion->bind_param("iiiiss",
										$id_persona_insertada,
										$data['estado'],
										$data['id_municipio'],
										$data['id_comunidad'],
										$data['municipio_texto'],
										$data['comunidad_texto']);
				if (!$stmtUbicacion->execute()) {
					$error_log = "Error al ejecutar la consulta de ubicación para asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUbicacion->close();

			$stmtUsuario = $conexion->prepare("INSERT INTO usuarios(persona_id, nombre_usuario, password, rol_usuario) VALUES(?,?,?,?)");
				if ($stmtUsuario === false) {
					$error_log = "Error en la consulta de usuario para asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUsuario->bind_param("isss",
									$id_persona_insertada,
									$data['nombre_usuario'],
									$data['contrasena'],
									$data['ocupacion']);
				if(!$stmtUsuario->execute()){
					$error_log = "Error al ejecutar la consulta de usuarios para profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUsuario->close();


			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function obtener_listaAsistente(){
		$conexion = $this->db->getConexion();
		$rol_user = "asistente";
		$stmtAsistente = $conexion->prepare("SELECT
												u.persona_id,
												p.id_persona,
												p.nombre1,
												p.apellido1,
												p.cedula,
												p.sexo
												FROM usuarios AS u
												INNER JOIN persona AS p ON p.id_persona = u.persona_id
												WHERE u.rol_usuario = ?");
			if($stmtAsistente === false){
				$error_log = "Error en la consulta de usuarios para obtener lista de asistente: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al intentar obtener datos de asistente: ".$conexion->error;
				return $error;
			}
		$stmtAsistente->bind_param("s",$rol_user);
			if (!$stmtAsistente->execute()) {
				$error_log = "Error al ejecutar la consulta de usuario para obtener lista de asistente: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error para obtener los datos de asistente";
				return $error;
			}
		$resultados = $stmtAsistente->get_result();
		$lista_asistente = [];
			while($row = $resultados->fetch_object()){
				$lista_asistente[] = $row;
			}

		$stmtAsistente->close();
		return $lista_asistente;
	}

	public function obtener_personaAsistente($id_asistente){
		$conexion = $this->db->getConexion();
		$stmtPersona = $conexion->prepare("SELECT
											p.nombre1,
											p.nombre2,
											p.apellido1,
											p.apellido2,
											p.cedula,
											p.sexo,
											p.fecha_nacimiento,
											p.estado_civil
											FROM usuarios as us
											INNER JOIN persona as p ON p.id_persona = us.persona_id
											WHERE p.id_persona = ? and us.rol_usuario = 'asistente'");
			if($stmtPersona === false){
				$error_log = "Error en la consulta para obtener persona asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtPersona->bind_param("i",$id_asistente);
			if(!$stmtPersona->execute()){
				$error_log = "Error en ejecutar la consulta para obtener persona asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultados = $stmtPersona->get_result();
		$info_persona[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() :null;
		$stmtPersona->close();
		return $info_persona;
	}

	public function obtener_asistente($id_asistente){
		$conexion = $this->db->getConexion();
		$stmtAsistente = $conexion->prepare("SELECT
												us.persona_id,
												p.nombre1,
												p.nombre2,
												p.apellido1,
												p.apellido2,
												p.cedula,
												p.sexo,
												p.fecha_nacimiento,
												p.estado_civil,
												u.municipio_texto,
												u.ciudad_comunidad_texto,
												edo.id_estados,
												edo.nombre_estado,
												m.id_municipio,
												m.nombre_municipio,
												c.id_comunidad,
												c.nombre_comunidad,
												YEAR(CURDATE()) - YEAR(p.fecha_nacimiento) as edad_aproximada
												FROM usuarios as us
												INNER JOIN persona as p ON p.id_persona = us.persona_id
												INNER JOIN ubicacion as u ON u.id_persona_ubicacion = p.id_persona
												LEFT JOIN estados as edo ON edo.id_estados = u.id_estado
												LEFT JOIN municipios as m ON u.id_municipio_u = m.id_municipio
												LEFT JOIN comunidades as c ON u.id_comunidad = c.id_comunidad
												WHERE us.persona_id = ?");
			if ($stmtAsistente === false) {
				$error_log = "Error en la consulta para obtener datos asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtAsistente->bind_param("i",$id_asistente);
			if (!$stmtAsistente->execute()) {
				$error_log = "Error en ejecutar la consulta para obtener datos asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultados = $stmtAsistente->get_result();
		$info_asistente[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() :null;

		$stmtAsistente->close();
		return $info_asistente;
	}

	public function obtener_ubicacionAsistente($id_asistente){
		$conexion = $this->db->getConexion();
		$stmtUbicacion = $conexion->prepare("SELECT
												p.nombre1,
												p.apellido1,
												p.cedula,
												u.municipio_texto,
												u.ciudad_comunidad_texto,
												edo.id_estados,
												edo.nombre_estado,
												m.id_municipio,
												m.nombre_municipio,
												c.id_comunidad,
												c.nombre_comunidad
												FROM persona as p
												INNER JOIN ubicacion as u ON u.id_persona_ubicacion = p.id_persona
												LEFT JOIN estados as edo ON edo.id_estados = u.id_estado
												LEFT JOIN municipios as m ON m.id_municipio = u.id_municipio_u
												LEFT JOIN comunidades as c ON c.id_comunidad = u.id_comunidad
												WHERE p.id_persona = ?");
			if($stmtUbicacion === false){
				$error_log = "Error en la consulta para obtener ubicacion asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtUbicacion->bind_param("i",$id_asistente);
			if(!$stmtUbicacion->execute()){
				$error_log = "Error en ejecutar la consulta para obtener ubicacion asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultados = $stmtUbicacion->get_result();
		$info_ubicacion[] = ($resultados->num_rows === 1) ? $resultados->fetch_object():null;
		$stmtUbicacion->close();
		return $info_ubicacion;
	}

	public function update_personaAsistente($data, $id_asistente){
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
				if($stmtUpdatePersona === false){
					$error_log = "Error en la consulta para actualizar persona asistente: ".$conexion->error;
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
											$id_asistente);
				if (!$stmtUpdatePersona->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar persona asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdatePersona->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);
			return false;
		}
	}

	public function update_ubicacionAsistente($data, $id_asistente){
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
				if($stmtUpdateUbicacion === false){
					$error_log = "Error en la consulta para actualizar ubicacion asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateUbicacion->bind_param("iiissi",
											$data['estado_id'],
											$data['municipio_id'],
											$data['comunidad_id'],
											$data['municipio_texto'],
											$data['comunidad_texto'],
											$id_asistente);
				if(!$stmtUpdateUbicacion->execute()){
					$error_log = "Error en ejecutar la consulta para actualizar ubicacion asistente: ".$conexion->error;
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

	public function update_asistente_todo($data, $id_asistente){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdatePersona = $conexion->prepare("UPDATE persona set
															nombre1 = ?,
															nombre2 = ?,
															apellido1 = ?,
															apellido2 = ?,
															sexo = ?,
															estado_civil = ?
															WHERE id_persona = ?");
				if ($stmtUpdatePersona === false) {
					$error_log = "Error en la consulta para actualizar persona asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdatePersona->bind_param("ssssssi",
											$data['nombre1'],
											$data['nombre2'],
											$data['apellido1'],
											$data['apellido2'],
											$data['sexo'],
											$data['estado_civil'],
											$id_asistente);
				if (!$stmtUpdatePersona->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar persona asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdatePersona->close();

			$stmtUpdateUbicacion = $conexion->prepare("UPDATE ubicacion set
															id_estado = ?,
															id_municipio_u = ?,
															id_comunidad = ?,
															municipio_texto = ?,
															ciudad_comunidad_texto = ?
															WHERE id_persona_ubicacion = ?");
				if($stmtUpdateUbicacion === false){
					$error_log = "Error en la consulta para actualizar ubicacion asistente: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateUbicacion->bind_param("iiissi",
											$data['estado_id'],
											$data['municipio_id'],
											$data['comunidad_id'],
											$data['municipio_texto'],
											$data['comunidad_texto'],
											$id_asistente);
				if (!$stmtUpdateUbicacion->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar ubicacion asistente: ".$conexion->error;
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

	public function delete_asistente($id_asistente){
		$conexion = $this->db->getConexion();
		$stmtDeleteAsistente = $conexion->prepare("DELETE FROM persona WHERE id_persona = ?");
			if ($stmtDeleteAsistente === false) {
				$error_log = "Error en la consulta para eliminar datos asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteAsistente->bind_param("i",$id_asistente);
			if (!$stmtDeleteAsistente->execute()) {
				$error_log = "Error en ejecutar la consulta para eliminar datos asistente: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteAsistente->close();
		return true;
	}
}
?>