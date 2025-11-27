<?php
$pageTitle = "Lista de estudiantes";

require_once __DIR__ . '/../../_partials/_headList.php';

?>
<div class="container_mainListEstudiante">
	<header><?php $rol = $_SESSION['rol_usuario'] ?? 'invitado';
switch ($rol) {
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
        // Maneja un caso por defecto para un header genérico
        require_once __DIR__ . '/../../_partials/header_default.php';
        break;
}
?></header>
	<div class="dashboard-grid">
	
	<main class="mainListEstud">
		<div class="card">
		
		<h3 class="h3_tituloListStudent">Lista de estudiantes</h3>
		</div>	
		<div class="card">
			<a href="<?php BASE_URL;?>download_listEstudiante" title="Descargar lista"><span class="fa fa-floppy-o">Descargar lista</span></a>
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
				<th colspan="2" class="th_sinAjuste">
					<div class="card_table">Total de estudiantes</div>
				</th>
				<?php
				
			if ($data['total_general'] > 0) {
				echo "<th colspan='8' class='th_sinAjuste'><div class='card_table'>".htmlspecialchars($data['total_general'])."</div></th>";
			}else{
				echo "<th colspan='8' class='th_sinAjuste'><div class='card_table'>Sin datos</div></th>";
			}
				

			?>
			</tr>
			<tr>
				<th colspan="5" class="th_sinAjuste">
					<div class="card_table">Masculino</div>
				</th>
				<th colspan="5" class="th_sinAjuste">
					<div class="card_table">Femenino</div>
				</th>
			</tr>
			<tr>	
		<?php
		if ($data['total_masculino'] > 0) {
			echo "<th colspan='5' class='th_sinAjuste'><div class='card_table'>".htmlspecialchars($data['total_masculino'])."</div></th>";
		}else{
			echo "<th colspan='5' class='th_sinAjuste'><div class='card_table'>Sin datos para masculino</div></th>";
		}

		if ($data['total_femenino'] > 0) {
			echo "<th colspan='5' class='th_sinAjuste'><div class='card_table'>".htmlspecialchars($data['total_femenino'])."</div></th>";
		}else{
			echo "<th colspan='5' class='th_sinAjuste'><div class='card_table'>Sin datos para femenino</div></th>";
		}
		?>
			</tr>
			<tr>
				<th class="th_sinAjuste">
					<div class="card_table">NRO</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Nombres</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Apellidos</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Sexo</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Cédula<br>de identidad</div>
				</th>			
				<th class="th_sinAjuste">
					<div class="card_table">Año/Grado</div>
				</th>			
				<th class="th_sinAjuste">
					<div class="card_table">Estatus</div>
				</th>			
				<th class="th_sinAjuste">
					<div class="card_table">Periodo escolar</div>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>

		<?php
		if (!empty($data['lista_estudiantes'])) {			
		
		$contador = 1;	 		
		foreach ($data['lista_estudiantes'] as $lista){
			
			$id_persona = encrypt_id($lista->id_persona);
			$nombre1 =$lista->nombre1;
			$apellido1 =$lista->apellido1;
			$sexo =$lista->sexo;
			$cedula =$lista->cedula;
			$ano_grado =$lista->ano_grado;
			$estatus =$lista->estatus;
			$ano_periodo1 =$lista->ano_periodo1;
			$ano_periodo2 =$lista->ano_periodo2;
    	?>
			<tr>
				<td  class="th_sinAjuste"><div class="card_table">
					<?php echo $contador; ?></div>
				</td>
			 	<td  class="th_sinAjuste"><div class="card_table">
			 		<?php echo htmlspecialchars($nombre1); ?></div>
			 	</td>
			 	<td class="th_sinAjuste"><div class="card_table">
			 		<?php echo htmlspecialchars($apellido1); ?></div>
			 	</td>
			 	<td class="th_sinAjuste"><div class="card_table">
			 		<?php 
			 		if($sexo === 'M'){
			 			echo "Masculino";
			 		}elseif ($sexo === 'F') {
			 			echo "Femenino";
			 		}
			 		?></div>
			 	</td>
			 	<td>
			 		<div class="card_table">
			 		<?php echo htmlspecialchars($cedula); ?></div>
			 	</td>
			 	<td >
			 		<div class="card_table">
			 		<?php echo htmlspecialchars($ano_grado). 'º'; ?></div>
			 	</td>
			 	<td class="th_sinAjuste">
			 		<div class="card_table">
			 		<?php echo htmlspecialchars($estatus); ?></div>
			 	</td>
			 	<td>
			 		<div class="card_table">
			 		<?php if(!empty($ano_periodo1) and !empty($ano_periodo2)){
			 				echo htmlspecialchars($ano_periodo1); echo "-"; echo htmlspecialchars($ano_periodo2); 
			 				}else {
			 					echo "Periodo escolar no asignado";
			 				}?></div>
			 	</td>
			 	</tr>
				<?php
					$contador++;
				}
						 	
				}elseif(empty($data['lista_estudiantes'])){
					echo "<td>No datos</td>";
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
			</tr>
			 					 	
			</tr>
			</tbody>
			</table>		
	</main>
	</div>
<footer><?php require_once __DIR__ . '/../../_partials/footer.php';?></footer>
</div>
</body>
</html>