<?php
$pageTitle = "Eliminar datos";
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

    ?>
        <div class="dashboard-grid"> 
        <form id="myForm" action="<?php echo BASE_URL . 'admin/' . $data['next_action'] . '/' .$data['ocupacion']. '/' . $data['id_entity']; ?>" method="post">
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
                if($data['ocupacion'] === 'comunidad'){
                    $nombre_comunidad = $key->nombre_comunidad;
                    echo "<td><b>Nombre comunidad: </b></td>";
                    echo "<td><strong>".$nombre_comunidad. "</strong></td>";
                    echo "<tr>";
                }elseif($data['ocupacion'] === 'materia'){
                    $nombre_materias = $key->nombre_materias;
                    echo "<td><b>Nombre materia: </b></td>";
                    echo "<td><strong>".$nombre_materias. "</strong></td>";
                    echo "<tr>";
                }elseif ($data['ocupacion'] === 'periodo_escolar') {
                    $periodo_escolar = $key->periodo_escolar;
                    
                    echo "<td><b>Periodo escolar: </b></td>";
                    echo "<td><strong>".$periodo_escolar. "</strong></td>";
                    echo "<tr>";
                }else{
                    $nombre1 = $key->nombre1;
                    $apellido1 = $key->apellido1;
                    $cedula = $key->cedula;
                    $sexo = $key->sexo;
                    
                    echo "<td><b>Cédula: </b></td>";
                    echo "<td><strong>".$cedula. "</strong></td>";
                    echo "<tr>";
                    echo "<td><b>Nombre y apellido: </b></td>";
                    echo "<td><strong>".$nombre1 ." ".$apellido1. "</strong></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Sexo: </b></td>";
                        if($sexo === 'M'){
                            echo "<td><strong>Masculino</strong></td>";
                        }elseif($sexo === 'F'){
                            echo "<td><strong>Femenino</strong></td>";
                        }
                
                    echo "</tr>";                 
                }                
                
                
                ?>

                <?php endforeach; ?>

                <?php if ($contador > 0): ?>
                </tr>    <?php endif; ?>

            </table>
            </div>
            </div>
                  
            </div>   
            <button type="submit">Confirmar y Eliminar</button>
        </form>
        <form action="<?php echo BASE_URL; ?>admin/cancelar" method="post">
            <button type="submit" name="cancelar">Cancelar</button>
        </form>
     
        </main>
        </body>
        </html>
