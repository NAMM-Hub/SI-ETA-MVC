<?php
class PersonaModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function obtener_persona($id){
		$conexion = $this->db->getConexion();

		$stmtPersona = $conexion->prepare("SELECT
												nombre1,
												nombre2,
												apellido1,
												apellido2,
												cedula,
												sexo,
												fecha_nacimiento,
												estado_civil
												FROM persona WHERE id_persona = ?");
			if ($stmtPersona === false) {
				$error_log = "Error en la consulta de persona para obtener datos de persona: ".$conexion->error;
				Database::log_error($error_log);
				return null;
			}
		$stmtPersona->bind_param("i",$id);
			if (!$stmtPersona->execute()) {
				$error_log = "Error al ejecutar la consulta de persona para obtener datos de persona: ".$stmtPersona->error;
				Database::log_error($error_log);
				return null;
			}
		$resultados = $stmtPersona->get_result();
		$datosPersona[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() : null;

		$stmtPersona->close();
		return $datosPersona;
	}

	public function check_persona($cedula){
		$conexion = $this->db->getConexion();
		$stmtCheckPersona = $conexion->prepare("SELECT
													cedula
													FROM persona
												WHERE cedula = ?");
		if($stmtCheckPersona === false){
				$error_log = "Error en la consulta para verificar persona: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtCheckPersona->bind_param("i",$cedula);
		if(!$stmtCheckPersona->execute()){
			$error_log = "Error en ejecutar la consulta para verificar persona: ".$conexion->error;
			Database::log_error($error_log);
			return false;
		}
		$resultados = $stmtCheckPersona->get_result();
		if($resultados->num_rows === 1){
			$check_persona[] = $resultados->fetch_object();
		}else{
			$check_persona = '';
		}
		$stmtCheckPersona->close();
		return $check_persona;
	}
}