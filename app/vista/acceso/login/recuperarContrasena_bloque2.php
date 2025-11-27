<?php
session_start();

$pageTitle = "Preguntas seguridad usuario";

require_once __DIR__ . '/../../_partials/_headRecovery.php';
?>
<div class="container_ingresarCedula1">
	<?php 
		$errores = $_SESSION['error'] ?? '';
		unset($_SESSION['error']);
		if (!empty($errores)) {
			foreach($errores as $error){
				echo "<p class='message'>".$error."</p>";
			}
	}?>
	
    <div class="container_ingresarCedula2">
            <h2>Recuperar Contraseña - Preguntas de Seguridad</h2>
    </div>
       	<?php $errores = $_SESSION['error'] ?? '';
                unset($_SESSION['error']);
            if (!empty($errores)) {
                echo "<table>";
                foreach ($errores as $error) {
                    echo "<th class='th_DatosIncorrect'>".$error."</th>";
                }
                echo "</table>";
            }
        ?><?php $success = $_SESSION['success'] ?? '';
                unset($_SESSION['success']);
            if (!empty($success)) {
                echo "<table>";
                foreach ($success as $succes) {
                    echo "<th class='th_DatosIncorrect'>".$succes."</th>";
                }
                echo "</table>";
            }
        ?>
<main>        
<form action="<?php echo BASE_URL;?>login/recovery_usuario_check/<?php echo $data['id_usuario'];?>" method="post">
	
		
<?php
if (isset($data['recovery_usuario']) && count($data['recovery_usuario']) >= 2) {
	$preguntas = [];
	$respuesta = [];
	$clave = [];

	foreach($data['recovery_usuario'] as $preguntasDB){
		$preguntas[] = $preguntasDB->pregunta_texto;
		$respuesta[] = $preguntasDB->respuesta_hash;
		$clave[] =  encrypt_id($preguntasDB->id_pregunt_resp);
	}
	echo '<div class="container_ingresarCedula2">';
	echo "<label for='respuesta_1' class='respuesta1'><b>" . htmlspecialchars($preguntas[0]) . "</b></label><br>";
	echo "<input type='password' name='respuestas[0]' id='respuesta_1' required><input type='checkbox' id='showResponse1'><label for='showResponse1'>Mostrar Respuesta</label>";
	echo "<input type='hidden' name='preguntas[0]' id='preguntas' value='".$preguntas[0]."'>";
	echo "<input type='hidden' name='clave[0]' id='preguntas' value='".$clave[0]."'>";
	echo '</div>';

	echo '<div class="container_ingresarCedula2">';
	echo "<label for='respuesta_2' class='respuesta1'><b>" . htmlspecialchars($preguntas[1]) . "</b></label><br>";
	echo "<input type='password' name='respuestas[1]' id='respuesta_2' required><input type='checkbox' id='showResponse2'><label for='showResponse2'>Mostrar Respuesta</label>";
	echo "<input type='hidden' name='preguntas[1]' id='preguntas' value='".$preguntas[1]."'>";
	echo "<input type='hidden' name='clave[1]' id='preguntas' value='".$clave[1]."'>";
	echo '</div>';

    }else {
		echo "<p style='color:red;'>Error: Acceso inválido o sesión de recuperación expirada. Por favor, reinicia el proceso.</p>";       
	}
        ?>
	<div class="container_buttons_recovery">
		<button type="submit" name="buscar_usuario">Ingresar</button>
	
</form>
<form action="<?php echo BASE_URL . 'login/cancelar'?>" method="post">
	<button type="submit" name="cancelar_recovery">Cancelar</button>
	</div>
</form>
</main>
</div>
</body>
<script src="<?php echo BASE_URL;?>public/js/scripts_mostrar_respuestas.js"></script>
</html>