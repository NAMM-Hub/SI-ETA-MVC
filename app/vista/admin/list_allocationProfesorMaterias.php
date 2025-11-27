<?php
$pageTitle = "Materias asignadas";

require_once __DIR__ .'/../_partials/_headList.php';

?>

<div class="container_listMaterias">
	<header><?php
				$rol = $_SESSION['rol_usuario'] ?? '';
				switch($rol){
					case 'administrador':
					require_once __DIR__ . '/../_partials/header_admin.php';
					break;
					case 'asistente':
					require_once __DIR__ . '/../_partials/header_asistente.php';
					break;
					case 'Profesor':
					require_once __DIR__ . '/../_partials/header_profesor.php';
					break;

					default:
					require_once __DIR__ . '/../_partials/header_default.php';
					break;
				}
				?></header>
	<div class="dashboard-grid">
	
<main class="main_ListMateria">		
		<div class="card">		
			<h3 class="h3_tituloListMateria">Profesores vinculados</h3>
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
			<table class="tabla" >
			<thead>
				<?php
                    if(!empty($data['list_allocationProfesorMaterias'])){
                   ?>
        <?php
            $contador = 0;
            	foreach($data['list_allocationProfesorMaterias'] as $lista){
                	$id_persona = encrypt_id($lista->persona_id_profesor);
                      	$nombre = $lista->nombre1;
                      	$apellido = $lista->apellido1;
                      	$nombre_materia = $lista->nombre_materias;
                       	$cedula = $lista->cedula;
                       	$contador++; 
                }?>
		<?php
			if(!empty($nombre_materia)){
			echo "<tr>";
			echo "<th colspan='5' class='th_sinAjuste'><div class='card'>Nombre de materia : " .htmlspecialchars($nombre_materia)."</div></th>";
			echo "</th>";
			echo "</tr>";
			}elseif (empty($nombre_materia)) {
			echo "<tr>";
			echo "<th colspan='5' class='th_sinAjuste'><div class='card'>Nombre de materia : Sin datos</div></th>";
			echo "</th>";
			echo "</tr>";
			}
			 
		?>
			<tr>
				<th class="th_sinAjuste">
					<div class="card">Nº</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card">Nombre</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card">Apellido</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card">Cédula</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card">Eliminar vinculación</div>
				</th>
			</tr>
			</thead>
			<tbody>
			
			 					 		
			 			<tr><?php
								echo "<td align='center' class='th_sinAjuste'><div class='card'>";
								echo $contador;
								echo "</div></td>";
								echo "<td align='center' class='th_sinAjuste'><div class='card'>";
								echo htmlspecialchars($nombre);
								echo "</div></td>";
								echo "<td align='center' class='th_sinAjuste'><div class='card'>";
								echo htmlspecialchars($apellido);
								echo "</div></td>";
								echo "<td align='center' class='th_sinAjuste'><div class='card'>";
								echo htmlspecialchars($cedula);
								echo "</div></td>";
								echo "<td align='center'><div class='card'>";
								echo "<a href='alertEliminar_pr_ma.php?id=".htmlspecialchars($id_persona)."'>Eliminar</a>";
								echo "</div></td></tr>";
							
						}elseif(empty($data['list_allocationProfesorMaterias'])){
							echo "<th><div class='card'>Sin datos</div></th>";
						}
						?>		 				
			 				
			 			</tr>
			 </tbody>
			</table>
	</main>
	</div>
<footer><?php require_once __DIR__ . '/../_partials/footer.php'?></footer>
</div>
</body>
</html>