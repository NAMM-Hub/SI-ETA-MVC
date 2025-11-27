<?php 
class UsuarioModelo{
	private $db;

	public function __construct(Database $db){
		$this->db = $db;
	}

	public function check_usuario_login($data){
		$conexion = $this->db->getConexion();
		$stmtCheckLogin = $conexion->prepare("SELECT
													us.persona_id,
													us.nombre_usuario,
													us.password,
													us.rol_usuario,
													us.preguntas_seguridad_configuradas
												FROM usuarios as us
												WHERE us.nombre_usuario = ?");
			if($stmtCheckLogin === false){
				$error_log = "Error en la consulta de login: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtCheckLogin->bind_param("s",$data['nombre_usuario']);
			if(!$stmtCheckLogin->execute()){
				$error_log = "Error en ejecutar la consulta de login: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtCheckLogin->get_result();
		if($resultado->num_rows === 1){
			$info_usuario[] = $resultado->fetch_object();
		}else{
			$info_usuario = '';
		}
		$stmtCheckLogin->close();
		return $info_usuario;
	}

	public function check_usuarioDB($datos){
		$conexion = $this->db->getConexion();
		$stmtCheckUsuario = $conexion->prepare("SELECT
													us.persona_id,
													us.nombre_usuario,
													us.preguntas_seguridad_configuradas,
													p.fecha_nacimiento
													FROM usuarios as us
													INNER JOIN persona as p ON p.id_persona = us.persona_id
												WHERE p.cedula = ? ");
			if($stmtCheckUsuario === false){
				$error_log = "Error en la consulta para comprobar datos usuario: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtCheckUsuario->bind_param("i",$datos['identificador']);
			if(!$stmtCheckUsuario->execute()){
				$error_log = "Error en ejecutar la consulta para comprobar datos usuario: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtCheckUsuario->get_result();
		if ($resultado->num_rows === 1) {			
			$info_usuario[] = $resultado->fetch_object();
		}else{
			$info_usuario = '';
		}

		$stmtCheckUsuario->close();
		return $info_usuario;
	}

	public function check_preguntasSeguridad($id_usuario){
		$conexion = $this->db->getConexion();
		$stmtCheckPreguntas = $conexion->prepare("SELECT
														id_pregunt_resp,
														pregunta_texto,
														respuesta_hash
													FROM preguntas_respuestas_seguridad_usuario
													WHERE usuario_id = ?");
			if($stmtCheckPreguntas === false){
				$error_log = "Error en la consulta para obtener preguntas y respuestas de seguridad: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtCheckPreguntas->bind_param("i",$id_usuario);
			if(!$stmtCheckPreguntas->execute()){
				$error_log = "Error en ejecutar la consulta para obtener preguntas y respuestas de seguridad: ".$conexio->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtCheckPreguntas->get_result();
			if($resultado->num_rows > 0){
				while($row = $resultado->fetch_object()){
					$datos_recovery[] = $row;
				}
			}elseif($resultado->num_rows < 1){
				$datos_recovery = '';
			}
		$stmtCheckPreguntas->close();
		return $datos_recovery;
	}

	public function check_preguntasSeguridad_form($id_recovery){
		$conexion = $this->db->getConexion();
		$stmtCheckPreguntas = $conexion->prepare("SELECT
														id_pregunt_resp,
														pregunta_texto,
														respuesta_hash
													FROM preguntas_respuestas_seguridad_usuario
													WHERE id_pregunt_resp = ?");
			if($stmtCheckPreguntas === false){
				$error_log = "Error en la consulta para obtener preguntas y respuestas de seguridad: ".$conexion->error;
				Database::log_error($error_log);
				return false;
			}
		$stmtCheckPreguntas->bind_param("i",$id_recovery);
			if(!$stmtCheckPreguntas->execute()){
				$error_log = "Error en ejecutar la consulta para obtener preguntas y respuestas de seguridad: ".$conexio->error;
				Database::log_error($error_log);
				return false;
			}
		$resultado = $stmtCheckPreguntas->get_result();
			if($resultado->num_rows > 0){
				while($row = $resultado->fetch_object()){
					$datos_recovery[] = $row;
				}
			}elseif($resultado->num_rows < 1){
				$datos_recovery = '';
			}
		$stmtCheckPreguntas->close();
		return $datos_recovery;
	}

	public function prepare_tokens_recovery($id_usuario, $data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtPrepareTokens = $conexion->prepare("INSERT INTO tokens_recuperacion (user_id, token, expires_at) VALUES(?,?,?)");
				if($stmtPrepareTokens === false){
					$error_log = "Erro en la consulta para preparar token de recuperacion: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtPrepareTokens->bind_param("iss",
											$id_usuario,
											$data['token'],
											$data['expires_at']);
				if(!$stmtPrepareTokens->execute()){
					$error_log = "Error en ejecutar la consulta para preparar token de recuperacion: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtPrepareTokens->close();
			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);

			return false;
		}
	}

	public function check_tokens_recovery($data){
		$conexion = $this->db->getConexion();
		$stmtCheckTokens = $conexion->prepare("SELECT
													id,
													expires_at,
													used_at
													FROM tokens_recuperacion
												WHERE user_id = ? and token = ?");
		if($stmtCheckTokens === false){
			$error_log = "Error en la consulta para comprobar los tokens de recovery: ".$conexion->error;
			Databas::log_error($error_log);
			return false;
		}
		$stmtCheckTokens->bind_param("is",
									$data['user_id'],
									$data['token']);
		if(!$stmtCheckTokens->execute()){
			$error_log = "Error en ejecutar la consulta para comprobar token de recovery: ".$conexion->error;
			Database::log_error($error_log);
			return false;
		}
		$resultados = $stmtCheckTokens->get_result();
		if ($resultados->num_rows === 1) {
			$info_tokens[] = $resultados->fetch_object();
		}else{
			$info_tokens = '';
		}

		$stmtCheckTokens->close();
		return $info_tokens;
	}

	public function update_password($data){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtUpdatePassword = $conexion->prepare("UPDATE usuarios set
			 											password = ?
			 											WHERE persona_id = ?");
			 	if($stmtUpdatePassword === false){
			 		$error_log = "Error en la consulta para actualizar la contrasena del usuario: ".$conexion->error;
			 		Database::log_error($error_log);
			 		throw new Exception($error_log);
				}
			$stmtUpdatePassword->bind_param("si",
			 								$data['password_new'],
			 								$data['id_usuario']);
			if(!$stmtUpdatePassword->execute()){
			 	$error_log = "Error en ejecutar la consulta para actualizar la contrasena de usuario: ".$conexion->error;
			 	Database::log_error($error_log);
			 	throw new Exception($error_log);
			}
			$stmtUpdatePassword->close();

			$stmtUpdateToken = $conexion->prepare("UPDATE tokens_recuperacion set
													used_at = NOW()
													WHERE id = ?");
				if($stmtUpdateToken === false){
					$error_log = "Error en actualizar estatus token: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateToken->bind_param("i", $data['id_token']);
			if(!$stmtUpdateToken->execute()){
				$error_log = "Error en ejecutar la consulta para actualizar estatus token: ".$conexion->error;
				Database::log_error($error_log);
				throw new Exception($error_log);
			}
			$stmtUpdateToken->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rolback();
			$conexion->autocommit(FALSE);
			return false;
		}
	}

	public function insert_preguntas_seguridad($data, $estatus_pregunta, $id){
		$conexion = $this->db->getConexion();
		$conexion->autocommit(FALSE);
		try{
			$stmtPreguntasUsuario = $conexion->prepare("INSERT INTO preguntas_respuestas_seguridad_usuario (usuario_id, pregunta_texto, respuesta_hash) VALUES(?,?,?)");
				if($stmtPreguntasUsuario === false){
					$error_log = "Error en la consulta insertar preguntas de seguridad: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}else{
					foreach ($data as $preguntas_seguridad) {
						$preguntas_saneada = htmlspecialchars($preguntas_seguridad['pregunta'], ENT_QUOTES, 'UTF-8');
						$respuestas_hash = password_hash($preguntas_seguridad['respuesta'], PASSWORD_DEFAULT);
						$stmtPreguntasUsuario->bind_param("iss",$id,$preguntas_saneada,$respuestas_hash);
							if(!$stmtPreguntasUsuario->execute()){
								$error_log = "Error en ejecutar la consulta insertar preguntas de seguridad: ".$conexion->error;
								Database::log_error($error_log);
								throw new Exception($error_log);
							}
					}
				}
			
			$stmtPreguntasUsuario->close();

			$stmtUpdateConfigPreguntas = $conexion->prepare("UPDATE 										usuarios set
														preguntas_seguridad_configuradas = ?
														WHERE persona_id = ?");
				if($stmtUpdateConfigPreguntas === false){
					$error_log = "Error en la consulta update configuracion preguntas usuario: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateConfigPreguntas->bind_param("ii",$estatus_pregunta,$id);
				if(!$stmtUpdateConfigPreguntas->execute()){
					$error_log = "Error en ejecutar la consulta update configuracion preguntas usuario: ".$conexion->error;
					Database::log_error($error_log);
					throw new Exception($error_log);
				}
			$stmtUpdateConfigPreguntas->close();

			$conexion->commit();
			$conexion->autocommit(TRUE);
			return true;
		}catch(Exception $e){
			$conexion->rollback();
			$conexion->autocommit(FALSE);
			return false;
		}
	}
}