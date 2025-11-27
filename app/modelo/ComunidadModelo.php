<?php
class ComunidadModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function insertarComunidadDB($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtComunidad = $conexion->prepare("INSERT INTO comunidades(nombre_comunidad, id_municipio) VALUES(?,?)");
				if ($stmtComunidad === false) {
					$error_log = "Error en la consulta de comunidad para insertar comunidad: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtComunidad->bind_param("si",$data['nombre_comunidad'],$data['id_municipio']);
				if(!$stmtComunidad->execute()){
					$error_log = "Error al ejecutar la consulta de comunidad: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtComunidad->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function obtener_listaComunidades(){
		$conexion = $this->db->getConexion();
		$stmtComunidad = $conexion->prepare("SELECT id_comunidad, nombre_comunidad FROM comunidades");
			if ($stmtComunidad === false) {
				$error_log = "Error en la consulta de comunidad para obtener lista de datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al obtener los datos de comunidades";
				return $error;
			}
			if (!$stmtComunidad->execute()) {
				$error_log = "Error en ejecutar la consulta de comunidad para obtener lista de datos: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al intentar obtener datos de comunidades";
				return $error;
			}
			$resultados = $stmtComunidad->get_result();
			$lista_comunidades = [];

			while($row = $resultados->fetch_object()){
				$lista_comunidades[] = $row;
			}

			$stmtComunidad->close();
			return $lista_comunidades;
	}

	public function obtener_comunidad($id_comunidad){
		$conexion = $this->db->getConexion();
		$stmtComunidad = $conexion->prepare("SELECT nombre_comunidad FROM comunidades WHERE id_comunidad = ?");
			if($stmtComunidad === false){
				$error_log = "Error en la consulta para obtener comunidad: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtComunidad->bind_param("i",$id_comunidad);
			if(!$stmtComunidad->execute()){
				$error_log = "Error en ejecutar la consulta para obtener comunidad: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtComunidad->get_result();

			if($resultado->num_rows === 1){
				$info_comunidad[] = $resultado->fetch_object();
			}else{
				$info_comunidad = '';
			}

		$stmtComunidad->close();
		return $info_comunidad;
	}

	public function update_comunidad($data, $id_comunidad){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdateComunidad = $conexion->prepare("UPDATE comunidades set
															nombre_comunidad = ?
														WHERE id_comunidad = ?");
				if($stmtUpdateComunidad === false){
					$error_log = "Error en la consulta para actualizar comunidad: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateComunidad->bind_param("si",
											$data['nombre_comunidad'],
											$id_comunidad);
			if(!$stmtUpdateComunidad->execute()){
				$error_log = "Error en ejecutar la consulta para actualizar comunidad: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdateComunidad->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);
			return false;
		}
	}

	public function delete_comunidad($id_comunidad){
		$conexion = $this->db->getConexion();
		$stmtDeleteComunidad = $conexion->prepare("DELETE FROM comunidades WHERE id_comunidad = ?");
			if($stmtDeleteComunidad === false){
				$error_log = "Error en la consulta para eliminar comunidad: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteComunidad->bind_param("i",$id_comunidad);
			if(!$stmtDeleteComunidad->execute()){
				$error_log = "Error en ejecutar la consulta para eliminar comunidad: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeleteComunidad->close();
		return true;
	}
}
?>