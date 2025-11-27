<?php
$pageTitle = "Información personal";
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
<main class="main_mostrarInfo_profesor">
	<div class="container">
    <div class="card">
		<h3>Informacion personal</h3>
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
			<table class="tabla">
				<?php
					
					if(!empty($data['info_profesor'])){
					   foreach ($data['info_profesor'] as $row)  :  ?>
                    <?php 
                        $nombre1 = $row->nombre1;
                        $nombre2 = $row->nombre2;
                        $apellido1 = $row->apellido1;
                        $apellido2 = $row->apellido2;
                        $cedula = $row->cedula;
                        $sexo = $row->sexo;
                        $fecha_nacimiento = $row->fecha_nacimiento;
                        $estatus = $row->estatus;
                        $estado = $row->nombre_estado;
                        $nombre_municipio = $row->nombre_municipio;
                        $nombre_comunidad = $row->nombre_comunidad;
                        $ciudad_texto = $row->ciudad_comunidad_texto;
                        $municipio_texto = $row->municipio_texto;

                    ?>
                        <tr>
                            <th colspan="3" class="head_details">
                                    Datos personales
                            </th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="card">Primer nombre</div>
                                </th>
                                <th>
                                    <div class="card">Primer apellido</div>
                                </th>
                                <th>
                                    <div class="card">Cédula de identidad</div>
                                </th>
                            </tr>
                            <tr>
                                <td><div class="card"><?php echo htmlspecialchars($nombre1);?></div></td>
                                <td><div class="card"><?php echo htmlspecialchars($apellido1); ?></div></td>
                                <td align="center"><div class="card"><?php echo htmlspecialchars($cedula); ?></div></td>
                            </tr>
                            <tr>
                            <th><div class="card">Segundo nombre</div></th>
                            <th><div class="card">Segundo apellido</div></th>
                                
                            </tr>
                            <tr>
                             <td><div class="card"><?php echo htmlspecialchars($nombre2); ?></div></td>
                             <td><div class="card"><?php echo htmlspecialchars($apellido2); ?></div></td>                           
                            </tr>
                            <tr>
                                <th><div class="card">Sexo</div></th>
                                <th colspan="2"><div class="card">Fecha de nacimiento</div></th>
                            </tr>
                            <tr>
                                <td><div class="card"><?php if ($sexo === 'M') {
                                    echo "Masculino";
                                }else{
                                    if ($sexo === 'F') {
                                    echo "Femenino";
                                }
                                }  ?></div></td>
                                <td colspan="2" align="center"><div class="card"><?php echo htmlspecialchars($fecha_nacimiento); ?></div></td>
                            </tr>
                            <tr>
                                <th colspan="3" class="head_details">Información laboral </th>
                            </tr>
                            <tr>
                                <th colspan="3"><div class="card">Estatus</div></th>                                
                            </tr>
                            <tr>
                                <td colspan="3"><div class="card"><?php echo htmlspecialchars($estatus); ?></div></td>
                            </tr>
                            <tr>
                                <th colspan="3" class="head_details">Ubicación</th>
                            </tr>
                            <tr>
                                <th><div class="card">Estado</div></th>
                                <th><div class="card">Municipio</div></th>                              
                                <th><div class="card">Comunidad / Ciudad</div></th>
                            </tr>
                            <tr>
                                <td><div class="card"><?php echo htmlspecialchars(($estado) ?? 'N/A'); ?></div></td>
                                <td><div class="card">
                                    <?php
                                   if (!empty($nombre_municipio)) {
                                        echo htmlspecialchars($nombre_municipio);
                                    } elseif (!empty($municipio_texto)) {
                                        echo htmlspecialchars($municipio_texto);
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?></div>
                                </td>
                                <td><div class="card">
                                    <?php
                                    if (!empty($nombre_comunidad)) {
                                        echo htmlspecialchars($nombre_comunidad);
                                    } elseif (!empty($ciudad_texto)) {
                                        echo htmlspecialchars($ciudad_texto);
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?></div>
                                </td>
                            </tr>
                    <?php 

                        endforeach;
                        }else{
                            echo "<th colspan='3'><div class='card_table'>Sin datos</div></th>";
                        }
                    ?>

                    </table>

        <div class="card">
            <form action="<?php echo BASE_URL;?>profesor/update_rule" method="post">
            <input type="hidden" name="entity_type" value="<?php echo $data['entity_type']?>">
            <input type="hidden" name="id_persona" value="<?php echo $data['id_profesor']?>">
            <label for="update_id">Actualizar:</label>
            <select name="update_rule" required id="update_id">
                <option value="">--Seleccionar--</option>
                <option value="persona">Datos personales</option>
                <option value="ubicacion">Ubicación</option>
                <option value="fullDatos">Todo</option>
            </select>
            <button type="submit" name="update" class="update">Aceptar</button> 
            </form>
        </div>
	
    </div>
</main>
</div>
    
</div>
</div>
    <footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</body>
</html>