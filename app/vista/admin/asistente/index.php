<?php
$pageTitle = "Lista de asistentes";

require_once __DIR__ . '/../../_partials/_headList.php';

?>
<div class="container_listAsistente">
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
        require_once __DIR__ . '/../../_partials/header_default.php';
        break;
} ?></header>
	
	<main class="main_listAsistent">
		<div class="dashboard-grid">
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
				<div class="card">
					<h3 class="h3_tituloListAsistente">Lista de asistentes</h3>
				</div>
			
				<table class="tabla" >
				<thead>
				<tr>
					<th class="th_sinAjuste">
						<div class="card_table">Nº</div>
					</th>
					<th class="th_sinAjuste">
						<div class="card_table">Nombres</div>
					</th>
					<th class="th_sinAjuste">
						<div class="card_table">Apellidos</div>
					</th>
					<th>
						<div class="card_table">Cédula de identidad</div>
					</th>
					<th class="th_sinAjuste">
						<div class="card_table">Sexo</div>
					</th>		
					<th class="th_sinAjuste">
						<div class="card_table">Información asistente</div>
					</th>		
					<th class="th_sinAjuste">
						<div class="card_table">Eliminar</div>
					</th>			
				</tr>
				</thead>
				<tbody>
					<tr>
					<?php
					$contador = 1;
					foreach($data['lista_asistente'] as $lista) {
						$id_persona = encrypt_id($lista->id_persona);
						$nombre1 = $lista->nombre1;
						$apellido1 = $lista->apellido1;
						$cedula = $lista->cedula;
						$sexo = $lista->sexo;
					        ?>
					    <td class="th_sinAjuste">
					       	<div class="card_table"><?php echo $contador;?></div>
					    </td> 
					    <td class="td_sinAjuste">
					       	<div class="card_table"><?php echo htmlspecialchars($nombre1);?></div>
					    </td> 
					    <td class="td_sinAjuste">
					       	<div class="card_table"><?php echo htmlspecialchars($apellido1);?></div>
					    </td> 
					    <td>
					      	<div class="card_table"><?php echo htmlspecialchars($cedula);?></div>
					    </td> 
					    <td class="td_sinAjuste">
					       	<div class="card_table"><?php
					        	if($sexo === 'M'){
					        		echo "Masculino";
					        	}elseif($sexo === 'F'){
					        		echo "Femenino";
					        	}?></div>
					    </td>
						<td aling="center" class="td_sinAjuste">
							<div class="card_table"><a href="<?php echo BASE_URL;?>admin/obtener_asistent/<?php echo htmlspecialchars($id_persona);?>" class="bInfo">Ver infomación</a></div>
						</td>
						<td aling="center" class="td_sinAjuste">
							<div class="card_table"><a href="<?php echo BASE_URL;?>admin/delete/asistent/<?php echo htmlspecialchars($id_persona)?>">Eliminar</a></div>
						</td>
						</tr>
							<?php
							$contador ++;
						    }
						    
						
					if(empty($data['lista_asistente'])){
						echo "<td class='td_sinAjuste'>Sin datos</td>";
						echo "<td>Sin datos</td>";
						echo "<td>Sin datos</td>";
						echo "<td>Sin datos</td>";
						echo "<td>Sin datos</td>";
						echo "<td>Sin datos</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				</table>
			</div>	
	</main>
<footer><?php require_once __DIR__ . "/../../_partials/footer.php"?></footer>
</div>
</body>
</html>