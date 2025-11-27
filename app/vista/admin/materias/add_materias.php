<?php
$pageTitle = "Ingresar materia";

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
					case 'profesor':
					require_once __DIR__ . '/../../_partials/header_profesor.php';
					break;

				}
			?></header>
	<div class="dashboard-grid">


	<?php if (!empty($mess)) : ?>

		<center><p class="message"><?= $mess ?></p></center>

	<?php endif;?>

	<main class="main_Materias">
		
	<div class="container_table">
	<div class="card">
		<h3 class="h3_tituloAddMateria">Registrar materias</h3>
	</div>
	<form action="<?php echo BASE_URL;?>admin/insertarMaterias" method="post">
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

		$anoGradoModelo = new EstudianteModelo($this->db);
		?>
	<div class="card">
			<table class="table">
			<tr>
			<th><p align="left">Nombre de materia</p><input type="text" name="nombreMateria" pattern="[A-Za-z]{1,15}" placeholder="Nombre de la materia" value="<?php echo $data['nombreMateria'] ?? '';?>"></th>

			<th><p align="left">Descripcion de materia</p><input type="text" name="descripcion" pattern="[A-Za-z_- ]{1,50}" required placeholder="Descripción de la materia" value="<?php echo $data['descripcion'] ?? '';?>"></th>
			</tr>

			<tr>
			<th><p align="left">Año/Grado</p></th>

			</tr>
			<tr>
			<td>
			<select name="anoGrado" required >
                <option value="">--Seleccionar--</option>
                <?php echo $anoGradoModelo->generarOpcionAnoGrado($data['anoGrado'] ?? '');?>
            </select>
        	</tr>
			</td>
		</table>
		</div>
		
			<input type="submit" name="ConfirmarMa" value="Enviar">

		</form>
		</div>
	</main>
	</div>
	<footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>