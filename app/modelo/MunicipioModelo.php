<?php
class MunicipioModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function obtener_listaMunicipios(){
		$conexion = $this->db->getConexion();
		$stmtMunicipio = $conexion->prepare("SELECT id_municipio, nombre_municipio FROM municipios");
			if ($stmtMunicipio === false) {
				$error_log = "Error en la consulta de municipio para obtener datos de municipio: ".$conexion->error;
				Database::log_error($error_log);
				return null;
			}
			if(!$stmtMunicipio->execute()){
				$error_log = "Error en ejecutar la consulta para obtener datos de municipio: ".$stmtMunicipio->error;
				Database::log_error($error_log);
				return null;
			}
		$resultado = $stmtMunicipio->get_result();
		$lista_municipio = [];
			while ($row = $resultado->fetch_object()) {
				$lista_municipio[] = $row;
			}
		$stmtMunicipio->close();
		return $lista_municipio;
	}

	public function obtener_municipio($id_municipio){
		$conexion = $this->db->getConexion();
		$stmtMunicipio = $conexion->prepare("SELECT
												nombre_municipio
												FROM municipios 
											WHERE id_municipio = ?");
		if($stmtMunicipio === false){
			$error_log = "Error en la consulta para obtener municipio: ".$conexion->error;
			Database::log_error($error_log);
			return false;
		}
		$stmtMunicipio->bind_param("i",$id_municipio);
		if(!$stmtMunicipio->execute()){
			$error_log = "Error en ejecutar la consulta para obtener municipio: ".$conexion->error;
			Database::log_error($error_log);
			return false;
		}
		$resultado = $stmtMunicipio->get_result();
		if($resultado->num_rows === 1){
			$datos_municipios[] = $resultado->fetch_assoc();
		}else{
			$datos_municipios = '';
		}
		$stmtMunicipio->close();
		return $datos_municipios;
	}
}