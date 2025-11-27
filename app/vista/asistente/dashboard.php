<?php
$pageTitle = "Bienvenidos a Kibsis: Tú Expediente Digital";
require_once __DIR__ . '/../_partials/_head.php'
?>

<div class="containerMain">
    <header><?php $rol = $_SESSION['rol_usuario'] ?? 'invitado';
switch ($rol) {
    case 'asistente':
        require_once __DIR__ . '/../_partials/header_asistente.php';
        break;
    case 'Profesor':
        require_once __DIR__ . '/../_partials/header_profesor.php';
        break;
    default:
        require_once __DIR__ . '/../_partials/header_default.php';
        break;
} ?></header>

    <main class="main">
    <div class="dashboard-grid">
        <div class="card">
            <h1 class="h1_tituloMain">Escuela Técnica Agropecuaria Cachama</h1>
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
    <div class="card">
        <h5>Misión</h5>
            <p class="p_mision">Egresar Técnicos medio agropecuario en la especialidad Ciencia Agricolas
            con alto nivel profesional capaces de asumir la responsabilidad del desarrollo
            endógeno de la región, tomando en consideración los valores y constumbres
            propias de la cultura Kari'ña.
        </p>
    </div>
    <div class="card">
    <h5>Visión</h5>
    <p class="p_vision">Ser la primera Escuela Técnica Agropecuaria del Oriente venezolano, donde
        se formaran profesionales de alta calidad, emprendedores y proactivos
        comprometidos con el resto de impulsar el desarrollo sustentable de nuestros
        pueblos. Así mismo despertar en los jóvenes Kari'ña la pertenencia de 
        liderazgo en sus respetivas comunidades para asumir la consideración
        transformadora de la misma.
    </p>
    </div>
    </div>
    </main>
    <footer><?php require_once __DIR__ . '/../_partials/footer.php'?></footer>
</div>  
</body>
</html>