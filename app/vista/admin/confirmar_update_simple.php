<?php
if (empty($_SESSION['time'])) {
    $this->redirect('admin/dashboard');
}
$pageTitle = "Actualizar datos";
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
            }
        ?></header>
    
        <main class="main">
        <div class="card">
        <h1><?php echo $data['title']; ?></h1>
        </div>
        <div class="card">
        <p><?php echo $data['message']; ?></p>
        </div>
        <?php

    ?>
        <div class="dashboard-grid"> 
        <form id="myForm" action="<?php echo BASE_URL . 'admin/' . $data['next_action'] . '/' .$data['ocupacion']. '/' . $data['id_entity_type']; ?>" method="post">
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
                <tr><?php
                $contador = 0;
                foreach ($data['form_data'] as $key): ?>

                <?php                
                
                echo "<div class='card'>";
                echo "<div class='container_table'>";
                echo "<table>";
                echo "<tr>";
                echo "<th colspan='4' class='head_details'>".$data['headTitle']."</th>";
                echo "</tr>";
                
                echo "<tr>";;
                
                if ($data['ocupacion'] ==='materia') {
                    $nombre_materia = $key->nombre_materias;
                    $descripcion = $key->descripcion_materias;
                    echo "<td><b>Nombre materia: </b></td>";
                    echo "<td><input type='text' value='".$nombre_materia."' name='nombre_materia'></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Descripción materia: </b></td>";
                    echo "<td><input type='text' value='".$descripcion."' name='descripcion_materias'></td>"; 
                    echo "</tr>";
                }elseif($data['ocupacion'] ==='comunidad'){
                    $nombre_comunidad = $key->nombre_comunidad;
                    echo "<td><b>Nombre comunidad</b></td>";
                    echo "<td><input type='text' value='".$nombre_comunidad."' name='nombre_comunidad'></td>";
                }
                
                ?>

                <?php endforeach; ?>


            </table>
            </div>
            </div>                    
            </div>   
            <button type="reset">Restablecer Todo</button>
            <button type="submit" name="update_simple">Confirmar y Guardar</button>
        </form>
        <form action="<?php echo BASE_URL; ?>admin/cancelar" method="post">
            <button type="submit" name="cancelar">Cancelar</button>
        </form>
     
        </main>
        </body>
        </html>
