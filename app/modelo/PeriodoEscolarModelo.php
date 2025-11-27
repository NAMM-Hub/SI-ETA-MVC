<?php
class PeriodoEscolarModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function generarOpcionesPeriodos(){
		$year = date("Y");
		$year_last = date("Y", strtotime("-2 years"));
		$html_options = '';
        for ($i= $year_last; $i < $year+4 ; $i++) {

        	$html_options .= '<option value='.$i.'>' . $i . '</option>'.PHP_EOL;

        }

        return $html_options;
	}

	public function insertarPeriodoEscolarDB($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtPeriodoEscolar = $conexion->prepare("INSERT INTO periodo_escolar(ano_periodo1, ano_periodo2) VALUES(?,?)");
				if ($stmtPeriodoEscolar === false) {
					$error_log = "Error en la consulta para insertar periodo escolar: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtPeriodoEscolar->bind_param("ss",
										$data['periodo1'],
										$data['periodo2']);
				if(!$stmtPeriodoEscolar->execute()){
					$error_log = "Error al ejecutar la consulta para periodo escolar: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtPeriodoEscolar->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(TRUE);

			return false;
		}
	}

	public function obtener_periodoEscolar(){
		$conexion = $this->db->getConexion();
		$stmt = $conexion->prepare("SELECT id_perido_escolar, CONCAT(ano_periodo1, ' - ', ano_periodo2) as periodo_escolar FROM periodo_escolar");
			if ($stmt === false) {
				$log_error = "Error en la consulta de periodo escolar: ".$conexion->error;
				Database::error_log($log_error);
				$error = "Error al obtener el periodo escolar";
				return null;
			}
			if(!$stmt->execute()){
				$log_error = "Error al obtener datos de periodo escolar: ".$conexion->error;
				Database::log_error($error_log);
				$error = "Error al obtener el periodo escolar";
				return null;
			}
		$resultado = $stmt->get_result();
		$lista_periodoEscolar = [];
		while($row = $resultado->fetch_object()){
			$lista_periodoEscolar[] = $row;
		}
		$stmt->close();
		return $lista_periodoEscolar;
	}

	public function obtener_periodoEscolar_uno($id_periodo){
		$conexion = $this->db->getConexion();
		$stmtPeriodoEscolar = $conexion->prepare("SELECT
														CONCAT(ano_periodo1, ' - ', ano_periodo2) as periodo_escolar
													FROM periodo_escolar
													 WHERE id_perido_escolar = ?");
			if ($stmtPeriodoEscolar === false) {
				$error_log = "Error en la consulta para obtener periodo escolar: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtPeriodoEscolar->bind_param("i",$id_periodo);
			if (!$stmtPeriodoEscolar->execute()) {
				$error_log = "Error en ejecutar la consulta para obtener periodo escolar: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultados = $stmtPeriodoEscolar->get_result();
		$datos_periodos[] = ($resultados->num_rows === 1) ? $resultados->fetch_object() :null;
		$stmtPeriodoEscolar->close();
		return $datos_periodos;
	}

	public function delete_periodoEscolar($id_periodo){
		$conexion = $this->db->getConexion();
		$stmtDeletePeriodo = $conexion->prepare("DELETE FROM periodo_escolar WHERE id_perido_escolar = ?");
			if($stmtDeletePeriodo === false){
				$error_log = "Error en la consulta para eliminar datos periodo escolar: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeletePeriodo->bind_param("i",$id_periodo);
			if (!$stmtDeletePeriodo->execute()) {
				$error_log = "Error en ejecutar la consulta para eliminar datos periodo escolar: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtDeletePeriodo->close();
		return true;
	}
}