<?php
$pageTitle = "Información personal del estudiante";

require_once __DIR__ .'/../../_partials/_head.php';
?>
    <div class="container_mainInfoEstudiante">
        <header><?php $rol = $_SESSION['rol_usuario'] ?? '';
                    switch($rol){
                        case 'administrador':
                         require_once __DIR__ . '/../../_partials/header_admin.php';
                         break;

                        default:
                        require_once __DIR__ . '/../../_partials/header_default.php';
                        break;
                    }
                    ?>
                
        </header>
        <div class="dashboard-grid">
        <main class="main">
                <div class="container">
                <div class="card">
                    <h3>Informacion personal del estudiante</h3>
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
                    if(!empty($data['lista_estudiante'])){
                     foreach($data['lista_estudiante'] as $estudiante) :  ?>
                    <?php
                        $entity_type = "estudiante";
                        $id_encrypt = encrypt_id($estudiante->persona_id);
                        $nombre1 = $estudiante->nombre1;
                        $nombre2 = $estudiante->nombre2;
                        $apellido1 = $estudiante->apellido1;
                        $apellido2 = $estudiante->apellido2;
                        $cedula = $estudiante->cedula;
                        $sexo = $estudiante->sexo;
                        $fecha_nacimiento = $estudiante->fecha_nacimiento;
                        $estado_civil = $estudiante->estado_civil;
                        $edad_aproximada = $estudiante->edad_aproximada;
                        $fecha_inscripcion = $estudiante->fecha_inscripcion;
                        $ano_grado = $estudiante->ano_grado;
                        $estatus = $estudiante->estatus;
                        $periodo_escolar = $estudiante->periodo_escolar;
                        $nombre_estado = $estudiante->nombre_estado;
                        $nombre_municipio = $estudiante->nombre_municipio;
                        $municipio_texto = $estudiante->municipio_texto;
                        $nombre_comunidad = $estudiante->nombre_comunidad;
                        $ciudad_texto = $estudiante->ciudad_comunidad_texto;
                    ?>
                            <tr>
                                <th colspan="3" class="head_details">Datos personales</th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="card_table">Primer nombre</div>
                                </th>
                                <th>
                                    <div class="card_table">Primer apellido</div>
                                </th>
                                <th>
                                    <div class="card_table">Cédula de identidad</div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($nombre1);?></div>
                                </td>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($apellido1); ?></div>
                                </td>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($cedula); ?></div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <div class="card">Segundo nombre</div>
                                </th>
                                <th>
                                    <div class="card">Segundo apellido</div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card"><?php echo  htmlspecialchars($nombre2)?></div>
                                </td>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($apellido2)?></div>
                                </td>                  
                            </tr>
                            </tr>
                            <tr>
                                <th>
                                    <div class="card">Sexo</div>
                                </th>
                                <th>
                                    <div class="card">Fecha de nacimiento</div>
                                </th>
                                <th>
                                    <div class="card">Edad del estudiante</div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card"><?php if($sexo === 'M') {
                                            echo "Masculino";
                                        }elseif ($sexo === 'F') {
                                            echo "Femenino";
                                        }?></div>
                                </td>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($fecha_nacimiento); ?></div>
                                </td>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($edad_aproximada) . " Años" ?></div>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="4">
                                    <div class="card">Estado Civil</div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <div class="card"><?php echo htmlspecialchars($estado_civil);?></div>
                                </td>
                            </tr>                            
                            <tr>
                                <th colspan="3" class="head_details">Información académica </th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="card">Fecha de inscripcion</div>
                                </th>
                                <th>
                                    <div class="card">Año/grado</div>
                                </th>
                                <th>
                                    <div class="card">Estatus</div>
                                </th>                                
                            </tr>
                            <tr>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($fecha_inscripcion); ?></div>
                                </td>
                                <td align="center">
                                    <div class="card"><?php echo htmlspecialchars($ano_grado)."º"; ?></div>
                                </td>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($estatus); ?></div>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3">
                                    <div class="card">Periodo escolar</div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <div class="card"><?php if(!empty($periodo_escolar)){
                                        echo htmlspecialchars($periodo_escolar); 
                                    }else {
                                        echo "Periodo escolar no asignado";
                                    }?></div>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3" class="head_details">Ubicación</th>
                            </tr>
                            <tr>
                                <th><div class="card">Estado</div>
                                </th>
                                <th><div class="card">Municipio</div>
                                </th>
                                <th><div class="card">Comunidad / Ciudad</div>
                                </th>
                            </tr>
                            <tr>    
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($nombre_estado ?? 'N/A'); ?></div>
                                </td>
                                <td>
                                    <div class="card">
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
                                <td>
                                    <div class="card">
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
                            </table>
                    </div>
                
                <div class="card">
                    <form action="<?php echo BASE_URL;?>admin/update_rule" method="post">
                        <input type="hidden" name="entity_type" value="<?php echo $entity_type?>">
                        <input type="hidden" name="id_persona" value="<?php echo $id_encrypt?>">
                        <label for="update_id">Actualizar:</label>
                        <select name="update_rule" required id="update_id">
                        <option value="">--Seleccionar--</option>
                        <option value="persona">Datos personales</option>
                        <option value="academico">Información académicos</option>
                        <option value="ubicacion">Ubicación</option>
                        <option value="fullDatos">Todo</option>
                        </select>
                    <button type="submit" name="update" class="update">Aceptar</button> 
                    </form>
                </div>
                <?php endforeach;?>
                <?php }elseif (empty($data['lista_estudiante'])) { ?>
                            <th>
                                <div class="card">No se encontraron datos.</div>
                            </th>
                        </table>
                <?php } ?>
            </div>
        </div>
        </main>
        
    </div>
    <footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>