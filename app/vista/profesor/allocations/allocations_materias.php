<?php
$pageTitle = "Información asignación materias";
require_once __DIR__ . '/../../_partials/_head.php'
?>

<div class="containerMain">
    <header><?php $rol = $_SESSION['rol_usuario'] ?? 'invitado';
        switch ($rol) {
            case 'Profesor':
                require_once __DIR__ . '/../../_partials/header_profesor.php';
            break;
            default:
                require_once __DIR__ . '/../../_partials/header_default.php';
            break;
    } ?></header>
	<div class="dashboard-grid">
	
	<main class="main_materias_asignadas_profesor">
	<div class="card">
		<h3 class="h3_tituloListMateria">Materias asignadas</h3>	
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
			<tr>
				<th class="th_sinAjuste">
					<div class="card_table">Nº</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Año/grado</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Lista de estudiantes</div>
				</th>
				<th class="th_sinAjuste">
					<div class="card_table">Nombre de materia</div>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php 
				if(!empty($data['info_allocation'])){
                    $errores[] = "Sin datos disponibles.";					 		
			 		echo "<tr>";
					echo "<td align='center' class='th_sinAjuste'>";
						echo "<div class='card_table'>".$contador."</div>";
					echo "</td>";
					echo "<td align='center' class='th_sinAjuste'>";
						echo "<div class='card_table'>".htmlspecialchars($row['ano_grado'])."</div>";
					echo "</td>";
					echo "<td align='center'>";
						echo "<div class='card_table'><a href='lista_estudiantes_profesor.php?ano_grado=".urlencode($row['ano_grado'])."'>Ver lista de estudiantes de ".htmlspecialchars($row['ano_grado'])."</a></div>" ;
					echo "</td>";
					echo "<td align='center'>";
						echo "<div class='card_table'>".htmlspecialchars($row['materias_asignadas'])."</div>";
					echo "</td>";
					echo "</tr>";

                    $contador++;
                    }else {
                        	echo "<tr>";
                        	echo "<th colspan='4'>";
                        		echo "<div class='card_table'>Sin datos</div>";
                        	echo"</th>";
                        	echo"</tr>";
                        }
			?>
			 </tbody>
			</table>
	</main>
	</div>
<footer>
	<?php require_once __DIR__ . '/../../_partials/footer.php'?>
</footer>
</div>
</body>
</html>