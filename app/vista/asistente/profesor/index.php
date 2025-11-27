<?php
$pageTitle = "Lista de profesor";

require_once __DIR__ . '/../../_partials/_headList.php';

?>
<div class="container_mainListProfesor">
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
} ?></header>
	<div class="dashboard-grid">
	
	<main class="main_ListProfe">
	<div class="card">
		<h3 class="h3_tituloListProfesor">Lista de profesores</h3>
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
			<table class="tabla_listProfesor" >
			<thead>
			<tr>
				<th colspan="2" class="th_sinAjuste"><div class="card_table">Total de profesores:</th>
				<?php
				
			 	if ($data['total_general'] > 0) {
			 		echo "<th colspan='6'><div class='card_table'>".htmlspecialchars($data['total_general'])."</div></th>";
			 	}else{
			 		echo "<th colspan='6'><div class='card_table'>Sin datos</div></th>";
			 	}
			 	
				?>
				
			</tr>
			<tr>
				<th colspan="4" class="th_sinAjuste">
					<div class="card_table">Masculino</div>
				</th>
				<th colspan="4" class="th_sinAjuste">
					<div class="card_table">Femenino</div>
				</th>
			</tr>
			<tr>
	<?php
	if ($data['total_masculino'] > 0) {
	echo "<th colspan='4' class='th_sinAjuste'><div class='card_table'>".$data['total_masculino']."</div></th>";
	}else{
	echo "<th colspan='4' class='th_sinAjuste'><div class='card_table'>Sin datos para masculino</div></th>";
	}

	if($data['total_femenino'] > 0){
	echo "<th colspan='4' class='th_sinAjuste'><div class='card_table'>".$data['total_femenino']."</div></th>";
	}else{
	echo "<th colspan='4' class='th_sinAjuste'><div class='card_table'>Sin datos para femenino</div></th>";
			 			}
				?>
			</tr>
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
					<div class="card_table">Cédula de identidad</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Estatus</div>
				</th>			
			</tr>
			</thead>
			<tbody>
			<?php 	
			 		$contador = 1;
			 		foreach ($data['lista_profesor'] as $lista){ 
			 			$persona_id = encrypt_id($lista->id_persona);
			 			$nombre1 = $lista->nombre1;
			 			$apellido1 = $lista->apellido1;
			 			$sexo = $lista->sexo;
			 			$cedula = $lista->cedula;
			 			$estatus = $lista->estatus;
			 			?>
			 			<tr>
			 				<td align="center">
			 					<div class="card_table"><?php echo $contador; ?></div>
			 				</td>
			 				<td align="center" class="th_sinAjuste">
			 					<div class="card_table"><?php echo htmlspecialchars($nombre1); ?></div>
			 				</td>
			 				<td align="center" class="th_sinAjuste">
			 					<div class="card_table"><?php echo htmlspecialchars($apellido1); ?></td>
			 				<td align="center" class="th_sinAjuste">
			 					<div class="card_table"><?php
			 									if($sexo === 'M'){
			 										echo "Masculino";
			 									}elseif ($sexo === 'F') {
			 										echo "Femenino";
			 									} ?></div>
			 				</td>
			 				<td align="center">
			 					<div class="card_table"><?php echo htmlspecialchars($cedula); ?></div>
			 				</td>
			 				<td align="center">
			 					<div class="card_table"><?php echo htmlspecialchars($estatus); ?></div>
			 				</td>
						<?php 
							$contador++;
							}
						if(empty($data['lista_profesor'])){
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
			 	
			 </tbody>
			</table>
		</div>
		</div>
		
	</div>
	</main>
	</div>
	<footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>