<?php
$pageTitle = "Confirmar datos";
require_once __DIR__ . '/../_partials/_headConfirm.php';
?>
<header><?php
        $rol = $_SESSION['rol_usuario'] ?? 'invitado';
            switch($rol){
            case 'administrador':
            require_once __DIR__ . '/../_partials/header_admin.php';
            break;
            case 'asistente':
            require_once __DIR__ . '/../_partials/header_asistente.php';
            break;
            case 'Profesor':
            require_once __DIR__ . '/../_partials/header_profesor.php';
            break;
            default:
            require_once __DIR__ . '/../_partials/header_default.php';
            break;
            }?></header>
    
        <main class="main">
        <div class="card">
        <h1><?php echo $data['title']; ?></h1>
        </div>
        <div class="card">
        <p><?php echo $data['message']; ?></p>
        </div>
        <?php


    $formData = $_SESSION['formulario_data'] ?? [];
    unset($_SESSION['formulario_data']);
    ?>
        <div class="dashboard-grid">
        <form action="<?php echo BASE_URL . 'admin/' . $data['next_action']; ?>" method="post">
<?php
    $errores = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
    if (!empty($errores)) {
        echo "<center><table>";
        foreach ($errores as $error) {
            echo "<th class='th_DatosIncorrect'>".htmlspecialchars($error)."</th>";
        }
        echo "</table></center>";
    }

    $message = $_SESSION['success'] ?? '';
    unset($_SESSION['success']);
    if (!empty($message)) {
        echo "<center><table>";
            echo "<th class='th_DatosActualizado'>".htmlspecialchars($message)."</th>";
        echo "</table></center>";
    }

?>
            <div class="card">
            <div class="container_table">
            <table>
                <tr>
                    <th colspan="4" class="head_details"><?php echo $data['headTitle']?></th>
                </tr>
                <?php
                    $contador = 0;
                    $filaAbierta = false;
                ?>
                <tr><?php foreach ($data['form_data'] as $key => $value): ?>
                <?php if(!empty($value)): ?>
                    <?php if($contador > 0 && $contador % 2 == 0): ?>
                    </tr><tr><?php endif; ?>

                        <th><?php echo ucfirst($key); ?>:</th>

                        <td> <?php echo htmlspecialchars($value); ?></td>
                    
                    
                    <?php $contador++; ?>
                <?php endif; ?>
                <?php endforeach; ?>

                    <?php if ($contador > 0): ?>
                    </tr>    <?php endif; ?>
            <?php
                $materias_array = $data['form_data_materiasArray'] ?? [];
                $profesor = $data['form_data_profesor'] ?? '';
                if (!empty($materias_array) && is_array($materias_array)){
                    echo "<th>Materias<th>";
                    echo "<th>Profesor<th>";
                    echo "<tr>";

                    echo "<td>";
                    foreach($materias_array as $materias) {
                    echo htmlspecialchars($materias['nombre_materias'])." - ";
                    }
                    echo "</td>";
                    echo "<th> se asignará a: </th>";
                }
                    
                if (!empty($profesor) && is_array($profesor)) {

                    foreach($profesor as $info){
                        echo "<td>".htmlspecialchars($info->nombre1)." ".htmlspecialchars($info->apellido1)."</td>";
                    }
                }
                ?>
            </table>
            </div>
            </div>

            
                <?php if (isset($data['form_ubicacion'])) :?>
                <div class="card">
                <div class="container_table">
                <table>
                    <tr>
                        <th colspan="4" class="head_details">ubicación</th>
                    </tr>
                    <tr>
                        
                        <?php foreach($data['form_ubicacion'] as $key => $value):?>
                            <?php if(!empty($value)): ?>
                            </tr><tr><?php if($contador > 0 && $contador % 2 == 0):?>
                            </tr><tr><?php endif; ?>
                            <th> <?php echo ucfirst($key); ?></th>
                            <td> <?php echo htmlspecialchars($value); ?></td>
                            
                            <?php $contador++;?>
                            <?php endif;?>
                        <?php endforeach;?>

                        <?php if($contador > 0):?>
                            </tr><?php endif;?>
                        </tr>

                    </table>
                </div>
                </div>
                <?php endif;?>
                <?php if(!empty($data['dataExtra'])){
                    echo '<div class="card">';
                    echo '<div class="container_table">';
                    echo '<table>';
                    echo '<tr>';
                    
                    
                    echo "<th colspan='4' class='head_details'>" .$data['dataExtra']. " :</th>";                    
                 }

                echo "</tr>";
            if ($data['valorCarga'] === 'user') {
                    
                if($data['ocupacion'] === "Profesor"){
                echo "<tr>";
                echo "<th>";
                echo "<label for='fechaContratacion'>Fecha de contratación</label>";
                echo "</th>";                     
                echo "<th colspan='2'>";
                echo "<label for='estatus'>Estatus</label>";
                echo "</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>";
                echo    "<input type='date' name='fecha_contratacion' id='fechaContratacion' required>";
                echo "</td>";
                echo "<td colspan='2'>";
                echo "<select name='selectEstatusProfesor' id='estatus' required>";
                echo "<option value=''>--Seleccionar--</option>";
                echo "<option value='Activo'?>Activo</option>";
                echo "<option value='De licencia'?>De licencia</option>";
                echo "<option value='Jubilado'?>Jubilado</option>";
                echo "<option value='Contrato Temporal'?>Contrato temporal</option>";
                echo "<option value='Inactivo'?>Inactivo</option>";
                echo "<option value='Despedido'?>Despedido</option>";
                echo "</select>";
                echo "</td>";
                        
                echo "</tr>";
                echo "</table>";
                echo "</div>";
                echo "</div>";

                echo '<div class="card">';
                echo '<div class="container_table">';
                echo "<table>";
                    echo "<tr>";
                    echo '<th colspan="4" class="head_details">Datos de usuario</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo "<th><label for='nombre_usuario'>Nombre de usuario</label></th>";
                    echo "<td align='center'><input type='text' name='nombre_usuario' id='nombre_usuario' required></td>";
                    echo "<td>";
                    echo "</td>"; 
                    echo "</tr>";
                
                    echo "<tr>";                   
                    echo "<th>";
                    echo "<label for='contrasena'>Contraseña</label>";
                    echo "</th>";
                    echo "<td><input type='password' name='contrasena' id='contrasena' required></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td></td>";
                    echo '<td><input type="checkbox" id="showPassword"><label for="showPassword">Mostrar Contraseña</label></td>';
                    echo "</tr>"; 
                    echo "</table>";
                echo "</div>";
                echo "</div>";
                }elseif ($data['ocupacion'] === 'asistent') {
                    echo '</tr>';
                    echo '<tr>';
                    echo "<th><label for='nombre_usuario'>Nombre de usuario</label></th>";
                    echo "<td align='center'><input type='text' name='nombre_usuario' id='nombre_usuario' required value='".($data['nombre_usuario'] ?? '')."'></td>";
                    echo "<td>";
                    echo "</td>"; 
                    echo "</tr>";
                
                    echo "<tr>";                   
                    echo "<th>";
                    echo "<label for='contrasena'>Contraseña</label>";
                    echo "</th>";
                    echo "<td><input type='password' name='contrasena' id='contrasena' required value='".($data['contrasena'] ?? '')."'></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td></td>";
                    echo '<td><input type="checkbox" id="showPassword"><label for="showPassword">Mostrar Contraseña</label></td>';
                    echo "</tr>"; 
                    echo "</table>";
                echo "</div>";
                echo "</div>";
                }
            }else{
                if ($data['ocupacion'] === 'Estudiante') {;

                    $modelo = new PeriodoEscolarModelo($this->db);
                    $periodos = $modelo->obtener_periodoEscolar();
                    echo "<tr>";                     
                    echo "<th><label for='periodoEscolar'>Perido escolar</label></th>";                     
                    echo "<th><label for='idAnoGrado'>Año/Grado</label></th>";
                    echo "</tr>";
                    echo "<tr>";                     
                    echo "<th><select name='periodo_escolar' id='periodoEscolar'>";
                        echo "<option value=''>--Seleccionar--</option>";
                        foreach($periodos as $periodo){
                            $id = $periodo->id_perido_escolar;
                            $periodo = $periodo->periodo_escolar;
                            echo "<option value='".$id."'>";
                            echo htmlspecialchars($periodo);
                            echo "</option>";
                        }
                    echo "</selects></th>";                     
                    echo "<th>";
                    echo "<select name='ano_grado' id='idAnoGrado'>";
                    echo "<option value=''>--Seleccionar--</option>";

                        $estudianteModelo = new EstudianteModelo($this->db);
                        echo $estudianteModelo->generarOpcionAnoGrado($data['ano_grado'] ?? '');
                    echo "</select>";
                    echo "</th>";
                    echo "</tr>";

                    echo "<tr>";                     
                    echo "<th><label for='fechaInscripcion'>Fecha inscripción</label></th>";                     
                    echo "<th><label for='idEstatus'>Estatus</label></th>";
                    echo "</tr>";
                    echo "<tr>";                     
                    echo "<th><input type='date' name='fecha_inscripcion' id='fechaInscripcion'></th>";                     
                    echo "<th>";
                    echo "<select name='estatus' id='idEstatus'>";
                    echo "<option value=''>--Seleccionar--</option>";
                        $selectEstatus = $estudianteModelo->obtener_estatus_estudinate();
                        foreach($selectEstatus as $select){
                            echo "<option value='".$select['valor']."' ".($data['estatus'] == $select['valor'] ? 'selected' : '').">".$select['texto']."</option>";
                        }
                    
                    echo "</select>";
                    echo "</th>";
                    echo "</tr>";

                    echo "</table>";
                    echo "</div>";
                    echo "</div>";
                }
            }

            
                
            ?>   
            </div>   
            <button type="submit">Confirmar y Guardar</button>
        </form>
        <form action="<?php echo BASE_URL; ?>admin/cancelar" method="post">
            <button type="submit" name="cancelar">Cancelar</button>
        </form>
     
        </main>
        <script src="<?php echo BASE_URL; ?>public/js/scripts.js">
        </script>
        </body>
        </html>
