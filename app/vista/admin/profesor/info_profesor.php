<?php

$pageTitle = "Información personal del profesor";

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
                    <h3>Informacion personal profesor</h3>
                </div>
                    <?php
                    $success = $_SESSION['seccess'] ?? '';
                    unset($_SESSION['seccess']);
                    if(!empty($success)){
                        echo "<ul>";
                        foreach($success as $mensaje){
                            echo "<li>" .htmlspecialchars($mensaje). "</li>";
                        }
                        echo "</ul>";
                    }

                    $errores = $_SESSION['error'] ?? '';
                    unset($_SESSION['error']);
                    if(!empty($errores)){
                        echo "<h2>Se han encontrado los siguientes problemas: </h2>";
                        echo "<ul>";
                        foreach($errores as $error){
                            echo "<li>" .htmlspecialchars($error). "</li>";
                        }
                        echo "</ul>";
                    }
                    ?>

                <table class="tabla">
                    <?php
                    if(!empty($data['info_profesor'])){
                    foreach($data['info_profesor'] as $profesor) :  ?>
                    <?php
                        $entity_type = "profesor";
                        $id_encrypt = encrypt_id($profesor->persona_id);
                        $nombre1 = $profesor->nombre1;
                        $nombre2 = $profesor->nombre2;
                        $apellido1 = $profesor->apellido1;
                        $apellido2 = $profesor->apellido2;
                        $cedula = $profesor->cedula;
                        $sexo = $profesor->sexo;
                        $fecha_nacimiento = $profesor->fecha_nacimiento;
                        $estado_civil = $profesor->estado_civil;
                        $estatus = $profesor->estatus;
                        $edad_aproximada = $profesor->edad_aproximada;
                        $nombre_estado = $profesor->nombre_estado;
                        $nombre_municipio = $profesor->nombre_municipio;
                        $municipio_texto = $profesor->municipio_texto;
                        $nombre_comunidad = $profesor->nombre_comunidad;
                        $ciudad_texto = $profesor->ciudad_comunidad_texto;
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
                                    <div class="card">Segundo nombre</div>
                                </th>
                                <th>
                                    <div class="card">Segundo apellido</div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card"><?php echo htmlspecialchars($nombre2)?></div>
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
                                <td colspan="4" align="center">
                                    <div class="card"><?php echo htmlspecialchars($estado_civil);?></div>
                                </td>
                            </tr>                            
                            <tr>
                                <th colspan="3" class="head_details">Información académica</th>
                            </tr>
                            <tr>
                                <th colspan="4">
                                    <div class="card">Estatus</div>
                                </th>               
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <div class="card"><?php echo htmlspecialchars($estatus); ?></div>
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
                    <?php }elseif (empty($data['info_profesor'])) { ?>
                            <th colspan="3">
                                <div class="card">No se encontraron datos.</div>
                            </th>
                        </table>
                    <?php } ?>
                </div>
        </main>
        
    </div>
    <footer><?php require_once __DIR__ . '/../../_partials/footer.php'?></footer>
</div>
</body>
</html>