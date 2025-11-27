<?php
$pageTitle = "Lista de periodo escolar";

require_once __DIR__ . '/../../_partials/_headList.php';
?>
<div class="container_ListPeriodoEscolar">
	<header>
		<?php
		$rol = $_SESSION['rol_usuario'] ?? 'invitado';
		switch ($rol) {
			case 'asistente':
				require_once __DIR__ . '/../../_partials/header_asistente.php';
				break;
			case 'Profesor':
				require_once __DIR__ . '/../../_partials/header_profesor.php';
				break;
			
			default:
				require_once __DIR__ . '/../../_partials/header_default.php';
        		break;
		}
	 ?></header>
	<div class="dashboard-grid">
	
	<main class="main_PeriodoEscolar">
		<div class="card">
		<h3 class="h3_TituloPeriEscolar">Lista de periodos escolares</h3>
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
				<th>
					<div class="card_table">Nº</div>
				</th>
				<th>
					<div class="card_table">Inicia - Culmina</div>
				</th>			
			</tr>
			</thead>
			<tbody>
			<?php
				$contador = 1;
				foreach ($data['lista_periodoEscolar'] as $lista) {
					$id_periodo_escolar = encrypt_id($lista->id_perido_escolar);
					$periodo_escolar = $lista->periodo_escolar;				 		 ?>
			 			<tr>
			 			<td align="center">
			 				<div class="card_table"><?php echo $contador; ?></div>
			 			</td>
			 			<td>
			 				<div class="card_table"><?php echo htmlspecialchars($periodo_escolar); ?></div>
			 			</td>
			 			</tr>
			 		<?php 
			 		$contador++;
			 		} 
			 		
			 	if(empty($data['lista_periodoEscolar'])){
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