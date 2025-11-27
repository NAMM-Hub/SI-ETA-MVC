<?php
class ProfesorModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function insertarProfesorDB($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtPersona = $conexion->prepare("INSERT INTO persona(nombre1, nombre2, apellido1, apellido2, cedula, sexo, fecha_nacimiento, estado_civil) VALUES(?,?,?,?,?,?,?,?)");
				if ($stmtPersona === false) {
					$error_log = "Error en la consulta de personas para insertar profesor: ".$conexion->error;
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
				$error_log = "Error al ejecutar la consulta de personas para insertar profesor: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$id_persona_insertada = $conexion->insert_id;
			$stmtPersona->close();

			$stmtUbicacion = $conexion->prepare("INSERT INTO ubicacion(id_persona_ubicacion, id_estado, id_municipio_u, id_comunidad, municipio_texto, ciudad_comunidad_texto) VALUES(?,?,?,?,?,?)");
				if ($stmtUbicacion === false) {
					$error_log = "Error en la consulta de ubicación para insertar profesor: ".$conexion->error;
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
			
				if(!$stmtUbicacion->execute()){
					$error_log = "Error al ejecutar la consulta de ubicación para insertar profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUbicacion->close();

			$stmtProfesor = $conexion->prepare("INSERT INTO profesor(persona_id, fecha_contratacion, estatus) VALUES(?,?,?)");
				if ($stmtProfesor === false) {
					$error_log = "Error en la consulta de profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtProfesor->bind_param("iss",
								$id_persona_insertada,
								$data['fecha_contratacion'],
								$data['estatus']);
			
				if(!$stmtProfesor->execute()){
					$error_log = "Error al ejecutar la consulta de profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtProfesor->close();

			$stmtUsuarios = $conexion->prepare("INSERT INTO usuarios(persona_id, nombre_usuario, password, rol_usuario) VALUES(?,?,?,?)");
				if ($stmtUsuarios === false) {
					$error_log = "Error en la consulta de usuarios para profesor: ". $conexion->error;
					Database::error_log($error_log);
					throw new Exception($error_log);
				}
			$stmtUsuarios->bind_param("isss", $id_persona_insertada, $data['nombre_usuario'],$data['contrasena'], $data['ocupacion']);
				if(!$stmtUsuarios->execute()){
					$error_log = "Error al ejecutar la consulta de usuarios para profesor: ".$stmtUsuarios->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUsuarios->close();


			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch (Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function insertar_allocationProfesorMaterias(int $id_profesor, array $id_materias){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);

		if(empty($id_materias)){
			return true;
		}

		try{
			$id_profesor_ref = (int)$id_profesor;
			$current_materia_id = 0;

			$stmtProfesorMaterias = $conexion->prepare("INSERT INTO profesores_materias(persona_id_profesor, materia_id) VALUES(?,?)");
				if ($stmtProfesorMaterias === false) {
					$error_log = "Error en la consulta de asignacion de profesor-materias: ".$conexion->error();
					Database::log_error($error_log);
					throw new Exception("Error en el proceso para insertar datos de asignación de profesor-materias");
				}
			$stmtProfesorMaterias->bind_param("ii",$id_profesor_ref, $current_materia_id);
				Database::log_error("Debug: Insertando para profesor ID: ".$id_profesor_ref);
			foreach($id_materias as $materia_id) {

				$current_materia_id = (int) $materia_id;
				Database::log_error("Debug: Insertando para materias ID: ".$current_materia_id);
				if(!$stmtProfesorMaterias->execute()){
					Database::log_error("Error en ejecutar la consulta para insertar la asignación de profesor-materias: ".$stmtProfesorMaterias->error);
					throw new Exception("Error en el proceso para insertar datos de asignación de profesor-materias");
				}
			}
			
			$stmtProfesorMaterias->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function obtener_listaProfesor(){
		$conexion = $this->db->getConexion();
		$sql = "SELECT
					pr.persona_id,
					pr.estatus,
					p.id_persona,
					p.nombre1,
					p.nombre2,
					p.apellido1,
					p.apellido2,
					p.cedula,
					p.sexo
					FROM profesor as pr
					INNER JOIN persona as p ON p.id_persona = pr.persona_id";
		$stmtConsultProfesor = $conexion->prepare($sql);
			if ($stmtConsultProfesor === false) {
				$error_log = "Error en la consulta de persona para obtener profesor: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al obtener los datos del profesor";
				return $error;
			}
			if(!$stmtConsultProfesor->execute()){
				$error_log = "Error al ejecutar la consulta de persona para obtener profesor: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error en el proceso de obtener los datos de profesores";
				return $error;
			}
		$resultado = $stmtConsultProfesor->get_result();
		$lista_profesor = [];
			while($row = $resultado->fetch_object()){
				$lista_profesor[] = $row;
			}
		$stmtConsultProfesor->close();
		return $lista_profesor;
	}

	public function obtener_personaProfesor($id_profesor){
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
											FROM profesor as pr
											INNER JOIN persona as p ON p.id_persona = pr.persona_id
											WHERE pr.persona_id = ?");
			if ($stmtPersona === false) {
				$error_log = "Error en la consulta para obtener persona de profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtPersona->bind_param("i",$id_profesor);
			if (!$stmtPersona->execute()) {
				$error_log = "Error en ejecutar la consulta para obtener persona profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtPersona->get_result();
		$datosPersona[] = ($resultado->num_rows === 1) ? $resultado->fetch_object() : null;
		$stmtPersona->close();
		return $datosPersona;
	}

	public function obtener_academicoProfesor($id_profesor){
		$conexion = $this->db->getConexion();
		$stmtAcademicoProfesor = $conexion->prepare("SELECT
														p.nombre1,
														p.apellido1,
														p.cedula,
														pr.estatus
													FROM profesor as pr
													INNER JOIN persona as p ON p.id_persona = pr.persona_id
													WHERE pr.persona_id = ?");
			if ($stmtAcademicoProfesor === false) {
				$error_log = "Error en la consulta para obtener datos academicos profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtAcademicoProfesor->bind_param("i",$id_profesor);
			if (!$stmtAcademicoProfesor->execute()) {
				$error_log = "Error en ejecutar la consulta para obtener datos academicos de profesor";
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtAcademicoProfesor->get_result();
		$datosAcademico[] = ($resultado->num_rows === 1) ? $resultado->fetch_object() : null;

		$stmtAcademicoProfesor->close();
		return $datosAcademico;
	}

	public function obtener_ubicacionProfesor($id_profesor){
		$conexion = $this->db->getConexion();
		$stmtUbicacionProfesor = $conexion->prepare("SELECT
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
													FROM profesor as pr
													INNER JOIN persona as p ON p.id_persona = pr.persona_id
													INNER JOIN ubicacion as u ON u.id_persona_ubicacion = pr.persona_id
													LEFT JOIN estados as edo ON edo.id_estados = u.id_estado
													LEFT JOIN municipios as m ON m.id_municipio = u.id_municipio_u
													LEFT JOIN comunidades as c ON c.id_comunidad = u.id_comunidad
													WHERE pr.persona_id = ?");
			if ($stmtUbicacionProfesor === false) {
				$error_log = "Error en la consulta para obtener datos ubicacion profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtUbicacionProfesor->bind_param("i",$id_profesor);
			if (!$stmtUbicacionProfesor->execute()) {
				$error_log = "Error en ejecutar la consulta para obtener datos ubicacion profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtUbicacionProfesor->get_result();
		$dataProfesor[] = ($resultado->num_rows === 1) ? $resultado->fetch_object(): null;
		return $dataProfesor;
	}

	public function obtener_profesorDB($id_profesor){
		$conexion = $this->db->getConexion();
		$stmtProfesor = $conexion->prepare("SELECT
											pr.persona_id,
											pr.estatus,
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
											FROM profesor as pr
											INNER JOIN persona as p ON p.id_persona = pr.persona_id
											INNER JOIN ubicacion as u ON u.id_persona_ubicacion = pr.persona_id
											LEFT JOIN estados as edo ON edo.id_estados = u.id_estado
											LEFT JOIN municipios as m ON u.id_municipio_u = m.id_municipio
											LEFT JOIN comunidades as c ON u.id_comunidad = c.id_comunidad
											WHERE pr.persona_id = ?");
			if ($stmtProfesor === false) {
				$error_log = "Error en la consulta para obtener datos de profesor: ".$conexion->error;
				Database::log_error($error_log);
				return null;
			}
		$stmtProfesor->bind_param("i", $id_profesor);
		if (!$stmtProfesor->execute()) {
			$error_log = "Error en ejecutar la consulta para obtener datos de profesor: ".$conexion->error;
			Database::log_error($error_log);
			return null;
		}
		$resultado = $stmtProfesor->get_result();
		$dataProfesor[] = ($resultado->num_rows === 1) ? $resultado->fetch_object() : null;
			
		
		$stmtProfesor->close();
		return $dataProfesor;		
	}

	public function update_profesorPersona($data, $id_profesor){
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
					$error_log = "Error en la consulta para actualizar datos de persona profesor: ".$conexion->error;
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
											$id_profesor);
				if(!$stmtUpdatePersona->execute()){
					$error_log = "Error en ejecutar la consulta para actualizar persona profesor: ".$conexion->error;
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

	public function update_profesorAcademico($data, $id_profesor){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdateAcademico = $conexion->prepare("UPDATE profesor set
															estatus = ?
															WHERE persona_id = ?");
				if ($stmtUpdateAcademico === false) {
					$error_log = "Error en la consulta para actualizar datos academicos profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateAcademico->bind_param("si",
											$data['estatus'],
											$id_profesor);
				if(!$stmtUpdateAcademico->execute()){
					$error_log = "Error en ejecutar la consulta para actualizar datos academicos profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateAcademico->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function update_profesorUbicacion($data, $id_profesor){
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
					$error_log = "Error en la consulta para actualizar ubicacion profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateUbicacion->bind_param("iiissi",
												$data['estado_id'],
												$data['municipio_id'],
												$data['comunidad_id'],
												$data['municipio_texto'],
												$data['comunidad_texto'],
												$id_profesor);
				if (!$stmtUpdateUbicacion->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar ubicacion profesor: ".$conexion->error;
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

	public function update_profesor_todo($data, $id_profesor){
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
					$error_log = "Error en la consulta para actualizar datos persona profesor: ".$conexion->error;
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
											$id_profesor);
			if (!$stmtUpdatePersona->execute()) {
				$error_log = "Error en ejecutar la consulta para actualizar datos persona estudiante: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdatePersona->close();

			$stmtUpdateProfesor = $conexion->prepare("UPDATE profesor set
															estatus = ?
														WHERE persona_id = ?");
				if($stmtUpdateProfesor === false){
					$error_log = "Error en la consulta para actualizar datos profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateProfesor->bind_param("si",
											$data['estatus'],
											$id_profesor);
				if (!$stmtUpdateProfesor->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar datos profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateProfesor->close();

			$stmtUpdateUbicacion = $conexion->prepare("UPDATE ubicacion set
															id_estado = ?,
															id_municipio_u = ?,
															id_comunidad = ?,
															municipio_texto = ?,
															ciudad_comunidad_texto = ?
														WHERE id_persona_ubicacion = ?");
				if ($stmtUpdateUbicacion === false) {
					$error_log = "Error en la consulta para actualizar datos ubicacion profesor: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateUbicacion->bind_param("iiissi",
											$data['estado_id'],
											$data['municipio_id'],
											$data['comunidad_id'],
											$data['municipio_texto'],
											$data['comunidad_texto'],
											$id_profesor);
				if (!$stmtUpdateUbicacion->execute()) {
					$error_log = "Error en ejecutar la consulta para actualizar datos ubicacion profesor: ".$conexion->error;
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

	public function delete_profesor($id_profesor){
		$conexion = $this->db->getConexion();
		$stmtDeleteProfesor = $conexion->prepare("DELETE FROM persona WHERE id_persona = ?");
			if ($stmtDeleteProfesor === false) {
				$error_log = "Error en la consulta para eliminar datos de profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteProfesor->bind_param("i",$id_profesor);
			if (!$stmtDeleteProfesor->execute()) {
				$error_log = "Error en ejecutar la consulta para elimnar datos de profesor: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteProfesor->close();
		return true;
	}
}