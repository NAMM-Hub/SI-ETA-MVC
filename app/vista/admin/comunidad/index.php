<?php
$pageTitle = "Lista de comunidades";

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
					case 'Profesor':
					require_once __DIR__ . '/../../_partials/header_profesor.php';
					break;

					default:
					require_once __DIR__ . '/../../_partials/header_default.php';
				}
				?></header>
	<div class="dashboard-grid">
	
	<main class="main_ListMateria">
		<div class="card">
			<h3 class="h3_tituloListMateria">Lista de comunidades</h3>
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
				<th><div class="card_table">NRO</div></th>
				<th><div class="card_table">Nombre de comunidad</div></th>
				<th><div class="card_table">Actualizar</div></th>
				<th><div class="card_table">Eliminar</div></th>
			</tr>
			</thead>
			<tbody>
			<?php 
			 		
			 	$contador = 1;
			 	foreach($data['lista_comunidades'] as $lista) {
			 		$id_comunidad = encrypt_id($lista->id_comunidad);
			 		$nombre_comunidad = $lista->nombre_comunidad;
			 	 ?>
			 					 		
			 	<tr>
					<td align="center">
						<div class="card_table"><?php echo $contador; ?></div>
					</td>
					<td align="center">
						<div class="card_table"><?php echo htmlspecialchars($nombre_comunidad); ?></div>
					</td>
					<td align="center">
						<div class="card_table"><a href="<?php echo BASE_URL;?>admin/update_simpleRule/comunidad/<?php echo $id_comunidad?>">Actualizar</a></div>
						</td>
					<td align="center">
						<div class="card_table"><a href="<?php echo BASE_URL;?>admin/delete/comunidad/<?php echo htmlspecialchars($id_comunidad)?>">Eliminar</a></div>
					</td>
			 	</tr>
			 		<?php 
			 			$contador++;
			 			} 
			 		
			 	if(empty($data['lista_comunidades'])){
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