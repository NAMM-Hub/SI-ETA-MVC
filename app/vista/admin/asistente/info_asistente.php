<?php
$pageTitle = "Información personal del asistente";

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
                    <h3>Informacion personal del asistente</h3>
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
                        if(!empty($data['info_asistente'])){

                            foreach($data['info_asistente'] as $asistente) :  ?>
                    <?php
                        $entity_type = "asistent";
                        $id_encrypt = encrypt_id($asistente->persona_id);
                        $nombre1 = $asistente->nombre1;
                        $nombre2 = $asistente->nombre2;
                        $apellido1 = $asistente->apellido1;
                        $apellido2 = $asistente->apellido2;
                        $cedula = $asistente->cedula;
                        $sexo = $asistente->sexo;
                        $fecha_nacimiento = $asistente->fecha_nacimiento;
                        $estado_civil = $asistente->estado_civil;
                        $edad_aproximada = $asistente->edad_aproximada;
                        $nombre_estado = $asistente->nombre_estado;
                        $nombre_municipio = $asistente->nombre_municipio;
                        $municipio_texto = $asistente->municipio_texto;
                        $nombre_comunidad = $asistente->nombre_comunidad;
                        $ciudad_texto = $asistente->ciudad_comunidad_texto;
                    ?>
                            <tr>
                                <th colspan="3" class="head_details">Datos personales</th>
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
                                    <div class="card"><?php echo "Segundo nombre</th>";?></div>
                                </th>
                                <th>
                                    <div class="card"><?php echo "Segundo apellido";?></div>
                                </th>                            
                            </tr>
                            <tr>
                                <td><?php echo htmlspecialchars($nombre2)?></td>
                               <td><?php echo htmlspecialchars($apellido2)?></td>   
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
                                    <div class="card"><?php if ($sexo === 'M') {
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
                                <td colspan="4">
                                    <div class="card"><?php echo htmlspecialchars($estado_civil);?></div>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3" class="head_details">Ubicación</th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="card">Estado</div>
                                </th>
                                <th>
                                    <div class="card">Municipio</div>
                                </th>
                                <th>
                                    <div class="card">Comunidad / Ciudad</div>
                                </th>
                            </tr>
                            <tr>    
                                <td><div class="card"><?php echo htmlspecialchars($nombre_estado ?? 'N/A'); ?></div></td>
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
                    <div class="card">
                    <form action="<?php echo BASE_URL;?>admin/update_rule" method="post">
                        <input type="hidden" name="entity_type" value="<?php echo $entity_type?>">
                        <input type="hidden" name="id_persona" value="<?php echo $id_encrypt?>">
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
                    <?php endforeach;
                        }elseif(empty($data['info_asistente'])) {?>
                            <th colspan='3'>
                                <div class="card">No se encontraron datos.</div>
                            </th>
                        </table>                       
                    <?php }?>
                </div>
            </div>
        </main>
        
    </div>
    <footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>