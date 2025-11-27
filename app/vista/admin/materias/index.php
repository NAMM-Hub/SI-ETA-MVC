<?php
$pageTitle = "Lista de materias";

require_once __DIR__ . '/../../_partials/_headList.php';

?>
<div class="container_listMaterias">
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

				default:
				require_once __DIR__ . '/../../_partials/header_default.php';
				break;
				}
			?></header>
	<div class="dashboard-grid">	
	<main class="main_ListMateria">
		<div class="card">
			<h3 class="h3_tituloListMateria">Lista de materias</h3>
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
			<table class="tabla" >
			<thead>
			<tr>
				<th class="th_sinAjuste">
					<div class="card_table">Nº</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Nombre de la materia</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Descripción</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Año/Grado</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">NRO de Profesores asignados</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Profesores asignados</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Eliminar materia</div>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php 	
				$contador = 100;
			 	foreach($data['lista_materias'] as $lista) {
			 			$id_materias = encrypt_id($lista->id_materias);
			 			$nombre_materias = $lista->nombre_materias;
			 			$descripcion = $lista->descripcion_materias;
			 			$ano_grado = $lista->ano_grado;
			 			$cantidad_profesor = $lista->total_profesores;
			 			 ?>
			 					 		
			 	<tr>
					<td class="th_sinAjuste">
						<div class="card_table"><?php echo $contador; ?></div>
					</td>
					<td class="th_sinAjuste">
						<div class="card_table"><?php echo htmlspecialchars($nombre_materias); ?></div>
					</td>
			 		<td class="th_sinAjuste">
			 			<div class="card_table"><?php echo htmlspecialchars($descripcion); ?></div>
			 		</td>
			 		<td>
			 			<div class="card_table"><?php echo htmlspecialchars($ano_grado);?></a></div>
			 		</td>
			 		<td>
			 			<div class="card_table"><?php echo htmlspecialchars($cantidad_profesor);?></div>
			 		</td>
			 		<td class="th_sinAjuste">
			 			<div class="card_table"><a href="<?php echo BASE_URL?>admin/list_allocationProfesorMateria?id=<?php echo htmlspecialchars($id_materias);?>">Ver profesores</a></div>
			 		</td>
			 		<td class="th_sinAjuste">
			 			<div class="card_table"><a href="<?php echo BASE_URL;?>admin/delete/materia/<?php echo htmlspecialchars($id_materias)?>">Eliminar</a></div>
			 		</td>
			 		<td class="th_sinAjuste">
			 			<div class="card_table"><a href="<?php echo BASE_URL;?>admin/update_simpleRule/materia/<?php echo $id_materias;?>">Actualizar</a></div>
			 		</td>
			 				
			 				
			 	</tr>
			 		<?php 
			 			$contador++;
			 		} 
			 		
			 	if(empty($data['lista_materias'])){
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "<td>No datos</td>";
			 		echo "</tr>";
			 	}
			 	?>
			 </tbody>
			</table>
	</main>
	</div>
<footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>