<?php
$pageTitle = "Ingresar periodo escolar";

require_once __DIR__ . '/../../_partials/_head.php';
?>
<div class="container_mainCargarPeriodoEscolar">
	<header><?php
				$rol = $_SESSION['rol_usuario'] ?? 'invitado';
				switch($rol){
				case 'administrador':
					require_once __DIR__ .'/../../_partials/header_admin.php';
				break;
				case 'asistente':
					require_once __DIR__ .'/../../_partials/header_asistente.php';
				break;
				case 'profesor':
					require_once __DIR__ .'/../../_partials/header_profesor.php';
				break;

				default:
					require_once __DIR__ . '/../../_partials/header_default.php';
				break;
			}?></header>
							
	<div class="dashboard-grid">
   	<main class="main_periodoEscolar">
		<div class="container_table">
		<div class="card">
			<h3 class="h3_CargarPeriodoEscolar">Registrar periodo escolar</h3>
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
    ?>
    <?php $success = $_SESSION['success'] ?? '';
                unset($_SESSION['success']);
        if (!empty($success)) {
            echo "<table>";
            foreach ($success as $succes) {
                echo "<th class='th_DatosActualizado'>".$succes."</th>";
        	}
            echo "</table>";
        }
    ?>
		<form action="<?php echo BASE_URL; ?>admin/insertarPeriodoEscolar" method="post">
			<?php
			$opcionPeriodoModelo = new PeriodoEscolarModelo($this->db);

			?>

			<div class="card">
			<table class="tableCargarPeriodo">
			<tr>
			<th class="th_CargarPeriodoEscolar"><p>Inicio</p></th>

			<th class="th_CargarPeriodoEscolar"><p>Concluye</p></th>
			</tr>

			<tr>
			<td>
				<select name="anoPeriodo1">
					<option value="">--Seleccionar--</option>
					<?php
						echo $opcionPeriodoModelo->generarOpcionesPeriodos();
          			?>
				</select>
			</td>

			<td>
				<select name="anoPeriodo2">
					<option value="">--Seleccionar--</option>
					<?php 
          			echo $opcionPeriodoModelo->generarOpcionesPeriodos();
          ?>
				</select>
			</td>

			</tr>

		</table>
		</div>

			<input type="submit" name="ConfirmarPeri" value="Cargar">

		</form>

	</div>
	</main>
</div>
	<footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
	
</div>
</body>

</html>