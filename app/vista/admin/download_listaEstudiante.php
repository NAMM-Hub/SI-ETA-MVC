<?php
$estudianteModelo = new EstudianteModelo($this->db);

$pageTitle = "Descargar lista";

require_once __DIR__. '/../_partials/_head.php';

?>
<div class="container_descargarListaEstudiante">
<header><?php require_once __DIR__. '/../_partials/header_admin.php';?></header>
	<div class="dashboard-grid">

<main class="main_descargarListaEstudiantil">
<div class="container_table">
<div class="card">
	<h3 class="titulo3LoadSeccion">Descargar lista estudiantil</h3>
</div>
    <form action="<?php BASE_URL;?>generarReporte" method="post">
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
    
    <table>
		<div class="nombreApellido">
					<tr>
					<th><p align="left">Periodo escolar</p></th>
					</tr>
				<tr>					
				<td>
				<select name="periodoEscolar" required>
					<option value="">--Seleccionar--</option>
<?php
foreach ($data['lista_periodoEscolar'] as $lista) {
		$id_periodoEscolar = $lista->id_perido_escolar;
		$periodo1 = $lista->ano_periodo1;
		$periodo2 = $lista->ano_periodo2;

			echo'<option value='.$id_periodoEscolar.'>' .$periodo1. ' - ' .$periodo2.'</option>';

 }?>
				</select>

				</td>

				</tr>
				
                <tr>
                    <th><p align="left">Año/Grado</p></th>
                </tr>                
                <tr>
                 <td align="center">
                <select name="anoGrado" required>
                	<option value="">--Seleccionar--</option>
                	<?php echo $estudianteModelo->generarOpcionAnoGrado();?>
                </select>
                </td>
             
		</div>
    </table>
    </div>    
        <input type="submit" value="Descargar" name="reporteEstudiante">
    </div>
    </form>
</main>
</div>
<footer><?php require_once __DIR__ .'/../_partials/footer.php';?></footer>
</div>
</body>
</html>