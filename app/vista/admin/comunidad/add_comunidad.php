<?php
$pageTitle = "Ingresar comunidades";

require_once __DIR__ . '/../../_partials/_head.php';
?>
<div class="container_addMaterias">
	<header><?php
				$rol = $_SESSION['rol_usuario'] ?? 'invitado';
				switch($rol){
					case 'administrador':
					require_once __DIR__ . '/../../_partials/header_admin.php';
					break;
					case 'asistente':
					require_once __DIR__ . '/../../_partials/header_asistente.php';
					break;
					case 'Profesor':
					require_once __DIR__ . '/../../_partials/header_profesor.php';
					break;

					default:
					require_once __DIR__ . '/../../_partials/header_default.php';
					break;
				}?></header>
	<div class="dashboard-grid">

	<main class="main_Materias">
		<div class="container_table" id="container_mainAgregarMaterias">
		<div class="card">
			<h3 class="h3_tituloAddMateria">Registrar comunidad</h3>
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
                    echo "<th class='th_DatosActualizado'>".$succes."</th>";
                }
                echo "</table>";
            }
        ?>
		<form action="<?php echo BASE_URL;?>admin/insertarComunidad" method="post">
			<div class="card">
			<table class="table">
				<tr>
					<th><p align="left">Nombre de la comunidad</p>
						<input type="text" name="nombre_comunidad" pattern="[A-Za-z_- ]{1,50}" required placeholder="Colocar el nombre de la comunidad" value="<?php echo $data['nombre_comunidad'] ?? ''?>">
					</th>
				</tr>
			</table>
			</div>
		
			<input type="submit" name="ConfirmarComunidad" value="Enviar">

		</form>

		</div>
	</main>
	</div>
	<footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>