<?php
class EstadosModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function obtener_estado($id){
		$conexion = $this->db->getConexion();

		$stmtEstados = $conexion->prepare("SELECT id_estados, nombre_estado FROM estados WHERE id_estados = ?");
			if ($stmtEstados === false) {
				$error_log = "Error en la consulta de estados para obtener estados: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al intentar obtener datos de estados";
				return $error;
			}
			$stmtEstados->bind_param("i",$id);
			if (!$stmtEstados->execute()) {
				$error_log = "Error en ejecutar la consulta de estados para obtener estados: ".$stmtEstados->error;
				Database::log_error($error_log);
				$error = "Error en obtener los estados";
				return $error;
			}
		$resultados = $stmtEstados->get_result();
			if ($resultados->num_rows === 1) {
				$lista_estados[] = $resultados->fetch_object();
			}else{
				$lista_estados = '';
			}
		
		$stmtEstados->close();
		return $lista_estados;
	}

	public function obtener_listaEstados(){
		$conexion = $this->db->getConexion();

		$stmtEstados = $conexion->prepare("SELECT id_estados, nombre_estado FROM estados");
			if ($stmtEstados === false) {
				$error_log = "Error en la consulta de Estados para obtener datos de Estados: ".$conexion->error;
				Database::log_error($error_log);
				return null;
			}
			if (!$stmtEstados->execute()) {
				$error_log = "Error en ejecutar la consulta de Estados para obtener Estados: ".$stmtEstados->error;
				Database::log_error($error_log);
				return null;
			}
		$resultados = $stmtEstados->get_result();
		$lista_estados = [];
			while ($row = $resultados->fetch_assoc()) {
				$row['id_estados'] = encrypt_id($row['id_estados']);
				$lista_estados[] = $row;
			}
		$stmtEstados->close();
		return $lista_estados;
	}
}