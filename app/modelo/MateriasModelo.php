<?php
class MateriasModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function insertarMateriaDB($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtMaterias = $conexion->prepare("INSERT INTO materias(nombre_materias, descripcion_materias, ano_grado) VALUES(?,?,?)");
				if ($stmtMaterias === false) {
					$error_log = "Error en la consulta de materia para insertar materia: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtMaterias->bind_param("sss",
										$data['nombre_materia'],
										$data['description_materia'],
										$data['ano_grado']);
				if(!$stmtMaterias->execute()){
					$error_log = "Error en ejecutar la consulta de materia: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtMaterias->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function obtener_listaMaterias(){
		$conexion = $this->db->getConexion();
		$stmtMaterias = $conexion->prepare("SELECT
											m.id_materias,
											m.nombre_materias,
											m.descripcion_materias,
											m.ano_grado,
											count(pr_m.persona_id_profesor) as total_profesores,
											pr_m.materia_id
											FROM materias as m
											LEFT JOIN profesores_materias as pr_m ON pr_m.materia_id = m.id_materias
											GROUP BY m.id_materias, m.nombre_materias, m.ano_grado");
			if($stmtMaterias === false){
				$error_log = "Error en la consulta de materias para obtener la lista de los datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error en obtener los datos de materias";
				return $error;
			}
			if(!$stmtMaterias->execute()){
				$error_log = "Error en ejecutar la consulta de materias para obtener la lista de los datos: ".$conexion->error();
				Database::log_error($error_log);
				$error = "Error al insertar obtener los datos de materias";
				return $error;
			}
			$resultados = $stmtMaterias->get_result();
			$lista_materias = [];
			while($row = $resultados->fetch_object()){
				$lista_materias[] = $row;
			}

			$stmtMaterias->close();
			return $lista_materias;
	}

	public function obtener_listaMaterias_array(array $id_materias){
		$conexion = $this->db->getConexion();

		$lista_ids = array_map('intval',$id_materias);
		$num_ids = count($lista_ids);

		if ($num_ids === 0) {
			return [];
		}

		$placeholders = implode(',', array_fill(0, $num_ids, '?'));
		$types = str_repeat('i', $num_ids);

		$stmtMaterias_array = $conexion->prepare("SELECT nombre_materias, ano_grado FROM materias WHERE id_materias IN ($placeholders)");
		
		$lista_materias_array = [];
		
		if ($stmtMaterias_array) {
			$stmtMaterias_array->bind_param($types,...$lista_ids);
			$stmtMaterias_array->execute();

			$resultados = $stmtMaterias_array->get_result();
			
				while($row = $resultados->fetch_assoc()){
					$lista_materias_array[] = $row;
				}

			$stmtMaterias_array->close();
			
		}
		return $lista_materias_array;
	}

	public function obtener_relacionProfesorMateria($id){
		$conexion = $this->db->getConexion();
		$stmtProfesorMateria = $conexion->prepare("SELECT
													pr_m.persona_id_profesor,
													pr_m.materia_id,
													p.nombre1,
													p.apellido1,
													p.cedula,
													m.nombre_materias
										FROM profesores_materias as pr_m
										INNER JOIN profesor AS pr ON pr.persona_id = pr_m.persona_id_profesor
												LEFT JOIN persona as p ON p.id_persona = pr.persona_id
												LEFT JOIN materias as m ON m.id_materias = pr_m.materia_id
												WHERE pr_m.materia_id = ?");
			if($stmtProfesorMateria === false){
				$error_log = "Error en la consulta de profesor-materias para obtener lista relacionada de datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al obtener lista de datos profesor-materias";
				return $error;
			}
		$stmtProfesorMateria->bind_param("i",$id);
			if(!$stmtProfesorMateria->execute()){
				$error_log = "Error al ejecutar la consulta para obtener lista relacionada de datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al insertar obtener datos de profesor-materias";
				return $error;
			}
		$resultados = $stmtProfesorMateria->get_result();
		$lista_profesor_materias = [];

		while($row = $resultados->fetch_object()){
			$lista_profesor_materias[] = $row;
		}

		$stmtProfesorMateria->close();
		return $lista_profesor_materias;
	}

	public function obtener_relacionProfesor($id){
		$conexion = $this->db->getConexion();
		$stmtProfesorMateria = $conexion->prepare("SELECT
													pr_m.persona_id_profesor,
													pr_m.materia_id,
													p.nombre1,
													p.apellido1,
													p.cedula,
													m.nombre_materias
										FROM profesores_materias as pr_m
										INNER JOIN profesor AS pr ON pr.persona_id = pr_m.persona_id_profesor
												LEFT JOIN persona as p ON p.id_persona = pr.persona_id
												LEFT JOIN materias as m ON m.id_materias = pr_m.materia_id
												WHERE pr_m.persona_id_profesor = ?");
			if($stmtProfesorMateria === false){
				$error_log = "Error en la consulta de profesor-materias para obtener lista relacionada de datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al obtener lista de datos profesor-materias";
				return $error;
			}
		$stmtProfesorMateria->bind_param("i",$id);
			if(!$stmtProfesorMateria->execute()){
				$error_log = "Error al ejecutar la consulta para obtener lista relacionada de datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al insertar obtener datos de profesor-materias";
				return $error;
			}
		$resultados = $stmtProfesorMateria->get_result();
		$lista_profesor_materias = [];

		while($row = $resultados->fetch_object()){
			$lista_profesor_materias[] = $row;
		}

		$stmtProfesorMateria->close();
		return $lista_profesor_materias;
	}

	public function obtener_materia($id_materia){
		$conexion = $this->db->getConexion();
		$stmtMateria = $conexion->prepare("SELECT nombre_materias, descripcion_materias FROM materias WHERE id_materias = ?");
			if ($stmtMateria === false) {
				$error_log = "Error en la consulta para obtener materia: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtMateria->bind_param("i",$id_materia);
			if(!$stmtMateria->execute()){
				$error_log = "Error en ejecutar la consulta para eliminar materia: ".$conexino->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtMateria->get_result();
		$info_materia[] = ($resultado->num_rows === 1) ? $resultado->fetch_object(): null;
		$stmtMateria->close();
		return $info_materia;
	}

	public function update_materia($data, $id_materia){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdateMateria = $conexion->prepare("UPDATE materias set
															nombre_materias = ?,
															descripcion_materias = ?
															WHERE id_materias = ?");
				if($stmtUpdateMateria === false){
					$error_log = "Error en la consulta para actualizar materia: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateMateria->bind_param("ssi",
											$data['nombre_materia'],
											$data['descripcion_materia'],
											$id_materia);
				if(!$stmtUpdateMateria->execute()){
					$error_log = "Error en ejecutar la consulta para actualizar materia: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateMateria->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);
			return false;
		}
	}

	public function delete_materia($id_materia){
		$conexion = $this->db->getConexion();
		$stmtDeleteMateria = $conexion->prepare("DELETE FROM materias WHERE id_materias = ?");
			if ($stmtDeleteMateria === false) {
				$error_log = "Error en la consulta para eliminar datos materia: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteMateria->bind_param("i",$id_materia);
			if(!$stmtDeleteMateria->execute()){
				$error_log = "Error en ejecutar la consulta para eliminar datos materia: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteMateria->close();
		return true;
	}
}
?>