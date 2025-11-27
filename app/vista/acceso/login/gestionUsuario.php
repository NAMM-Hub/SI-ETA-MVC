<?php 
if($_SESSION['preguntas_configuradas'] === 1){
    $this->redirect('login/index');
}
if ($_SESSION['rol_usuario'] == 'administrador') {
    if (!isset($_SESSION['id'])) {
    $this->redirect('login/index');
}
}elseif ($_SESSION['rol_usuario'] == 'asistente') {
    if (!isset($_SESSION['id'])) {
    $this->redirect('login/index');
}
}elseif ($_SESSION['rol_usuario'] == 'Profesor') {
    if (!isset($_SESSION['id'])) {
    $this->redirect('login/index'); 
}
}else{
    $this->redirect('login/index');
}

require_once __DIR__ . '/../../_partials/_head_gestion.php';
?>
    <div class="container_MainAsistentePreguntasSeguridad">
    <div class="dashboard-grid">
<div class="card">
    <h2 class="h3_tituloPreguntaSeguridad">Configura tus Preguntas de Seguridad</h2>
</div>
<main>

<?php

$message_duplicados = $_SESSION['repetidos'] ?? '';
unset($_SESSION['repetidos']);
if(!empty($message_duplicados)){
    echo "<table>";
    foreach ($message_duplicados as $duplicado) {
        echo "<th>".htmlspecialchars($duplicado)."</th>";
    }
    echo "</table>";
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
if(!empty($message_duplicados)){
    echo "<table>";
    foreach ($message_duplicados as $duplicado) {
        echo "<th>".htmlspecialchars($duplicado)."</th>";
    }
    echo "</table>";
}
?>
<div class="card">
    <p class="p_mensajeImportante">Por favor, selecciona 3 preguntas y proporciona tus respuestas.</p>
</div>
<div class="card">
    <p class="p_mensajeImportante">
        Por favor, asegúrese de escribir con **precisión** y de **anotar** las respuestas de la preguntas en un lugar seguro.
        Las necesitará exactamente como las escriba para recuperar su cuenta en caso de olvidar su contraseña.
    </p>
</div>
    <form action="<?php echo BASE_URL.'login/'.$_SESSION['next_action'].'/'.$_SESSION['id'];?>" method="POST">
        <div class="card">
        <table>
        <div>
            <tr>
            <th class="th_preguntas"><label for="pregunta_seguridad_1">Pregunta 1:</label></th>
            <th class="th_preguntas"><label for="respuesta_seguridad_1">Respuesta 1:</label></th>
            </tr>
            <tr>

            <th>
                <select name="preguntas_seguridad[0][pregunta]" id="pregunta_seguridad_1" required>
                <option value="">Selecciona una pregunta</option>
            </th>

            <th>
                <input type="password" name="preguntas_seguridad[0][respuesta]" id="respuesta_seguridad_1" required>
            </th>
                <th><input type="checkbox" id="showResponse1"><label for="showResponse1">Mostrar Respuesta</label></th>

            </tr>
        </div>

        <div>
            <tr>

            <th class="th_preguntas">
                <label for="pregunta_seguridad_2">Pregunta 2:</label>
            </th>
            <th>
                <label for="respuesta_seguridad_2">Respuesta 2:</label>
            </th>

            </tr>

            <tr>

            <th>
                <select name="preguntas_seguridad[1][pregunta]" id="pregunta_seguridad_2" required>
                    <option value="">Selecciona una pregunta</option>
                </select>
            </th>
                        
            <th>
                <input type="password" name="preguntas_seguridad[1][respuesta]" id="respuesta_seguridad_2" required>
             </th>    
                <th><input type="checkbox" id="showResponse2"><label for="showResponse2">Mostrar Respuesta</label></th>
           
        </tr>
        </div>
        
            
        <div>
            <tr>

            <th class="th_preguntas">
                <label for="pregunta_seguridad_3">Pregunta 3:</label>
            </th>

            <th>
                <label for="respuesta_seguridad_3">Respuesta 3:</label>
            </th>

            </tr>

            <tr>

            <th>
                <select name="preguntas_seguridad[2][pregunta]" id="pregunta_seguridad_3" required> <option value="">Selecciona una pregunta</option>
                </select>
            </th>   
            
            
            <th><input type="password" name="preguntas_seguridad[2][respuesta]" id="respuesta_seguridad_3" required></th>
            <th><input type="checkbox" id="showResponse3"><label for="showResponse3">Mostrar Respuesta</label></th>
            </tr>
        </div>
    </table>
    </div>

    <div class="container_InputSubmi_reinicio">
        <button type="submit" name="Confirmar">Confirmar Preguntas de Seguridad</button>
    </div>
        </form>
    </main>
    </div>
    </div>
</body>
<script src="<?php echo BASE_URL;?>public/js/script_preguntas_seguridad_hidden_next.js"></script>
<script src="<?php echo BASE_URL;?>public/js/scripts_mostrar_respuestas.js"></script>
</html>