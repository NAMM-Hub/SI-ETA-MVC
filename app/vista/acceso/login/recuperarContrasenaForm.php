<?php
session_start();

$pageTitle = "Gestión usuario";

require_once __DIR__ . '/../../_partials/_headRecovery.php';
?>
<div class="container_ingresarCedula1">
	
<main>
<form action="<?php echo BASE_URL;?>login/check_usuario" method="post">
	
		
	<div class="container_ingresarCedula2">
		<h1 >Gestión de usuario</h1>
		<label for="cedula"><h3 class="h1_tituloRecuperarContrasena">Ingresar cédula de identidad</h3></label>
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
	<div class="container_ingresarCedula2">
		<input type="text" id="identificador" class="cedula" name="identificador" pattern="[0-9]{1,10}" required placeholder="Cédula de identidad" title="Escriba la cédula de identidad que colocó al registrarse">
	</div>
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