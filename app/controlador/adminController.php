<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) and !isset($_SESSION['rol_usuario'])) {
    header('Location: '.BASE_URL.'login/index');
    exit();
}
if ($_SESSION['rol_usuario'] != 'administrador') {
    $errores[] = "Credenciales incorrectas";
    $_SESSION['error'] = $errores;
    header('Location: '.BASE_URL.'login/index');
    exit();
}
class adminController {
    protected function redirect(string $path){
        $url = BASE_URL . $path;

        header("Location: {$url}");
        exit();
    }

    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    private function renderView($path, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . '/../vista/admin/' . $path . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo $viewPath;
            echo "Error 404: Vista no encontrada.";
        }
    }


    protected function disableBrowserCache(){
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");   
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    public function dashboard() {
        $this->renderView('dashboard');
    }

    public function add_asistent() {       
            $this->renderView('asistente/add_asistente');
    }

    public function add_estudiante() {
        $this->renderView('estudiante/add_estudiante');
    }

    public function add_profesor() {
        $this->renderView('profesor/add_profesor');
    }

    public function add_periodoEscolar() {
        $this->renderView('periodo_escolar/add_periodoEscolar');
    }

    public function add_materias() {
        $this->renderView('materias/add_materias');
    }

    public function add_comunidad() {
        $this->renderView('comunidad/add_comunidad');
    }

    public function allocation_profesorMateria() {
        $profesorModelo = new ProfesorModelo($this->db);
        $materiasModelo = new MateriasModelo($this->db);

        $lista_profesor = $profesorModelo->obtener_listaProfesor();
        $lista_materias = $materiasModelo->obtener_listaMaterias();

            if (!is_array($lista_profesor)) {
                $lista_profesor = [];
            }

            if (!is_array($lista_materias)) {
                $lista_materias = [];
            }

            $data = [
                    'lista_profesor'=>$lista_profesor,
                    'lista_materias'=>$lista_materias
                    ];
        $this->renderView('allocation_profesorMateria',$data);
    }

    public function list_asistent() {
        $asistenteModelo = new AsistenteModelo($this->db);
        $lista_asistente = $asistenteModelo->obtener_listaAsistente();

            if (!is_array($lista_asistente)) {
                $lista_asistente = [];
            }

        $data = ['lista_asistente'=>$lista_asistente];

        $this->renderView('asistente/index',$data);
    }

    public function obtener_asistent($id){
        $id_asistente = $id;
            if (!empty($id_asistente)) {
                $id_asistente_decrypt = decrypt_id($id_asistente);
            }
        $asistenteModelo = new AsistenteModelo($this->db);
        $info_asistente = $asistenteModelo->obtener_asistente($id_asistente_decrypt);

            if (!is_array($info_asistente)) {
                $this->redirect('admin/list_asistente?error=no_data');
                exit();
            }
        $data = ['info_asistente'=>$info_asistente];

        $this->renderView('asistente/info_asistente',$data);
    }

    public function list_estudiante() {
        $estudianteModelo = new EstudianteModelo($this->db);
        $lista_estudiante = $estudianteModelo->obtener_listaEstudiantes();

        $total_masculino = 0;
        $total_femenino = 0;
        $total_general = 0;

        if (is_array($lista_estudiante)) {
            foreach($lista_estudiante as $estudiante){
                if(isset($estudiante->sexo)){
                    if ($estudiante->sexo === 'M') {
                        $total_masculino++;
                    }
                    if ($estudiante->sexo === 'F') {
                        $total_femenino++;
                    }
                }

            }
        $total_general = count($lista_estudiante);
        }else{
            $lista_estudiante = [];
        }

        $data = [
                'lista_estudiantes'=>$lista_estudiante,
                'total_general'=>$total_general,
                'total_masculino'=>$total_masculino,
                'total_femenino'=>$total_femenino
                ];

        $this->renderView('estudiante/index',$data);
    }

    public function download_listaEstudiante(){
        $periodoEscolarModelo = new PeriodoEscolarModelo($this->db);
        $lista_periodoEscolar = $periodoEscolarModelo->obtener_periodoEscolar();

            if (!is_array($lista_periodoEscolar)) {
                $lista_periodoEscolar = [];
            }

        $data = ['lista_periodoEscolar'=>$lista_periodoEscolar];
        $this->renderView('download_listaEstudiante',$data);
    }

    public function generarReporte(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $errores = [];
            if (isset($_POST['reporteEstudiante'])) {
             
                $id_periodo = trim($_POST['periodoEscolar'] ?? '');
                $ano_grado = trim($_POST['anoGrado'] ?? '');

                if(empty($id_periodo)){
                    $errores[] = "El periodo es obligatorio";
                }

                if (empty($ano_grado)) {
                    $errores[] = "El año/grado es obligatorio";
                }
                if (!empty($errores)) {
                    $_SESSION['error'] = $errores;
                    $this->redirect('admin/download_listaEstudiante');
                }
                if (empty($errores)) {
                    $estudianteModelo = new EstudianteModelo($this->db);
                    $lista_estudiante = $estudianteModelo->obtener_estudiantes_reporte($id_periodo, $ano_grado);

                    if (!empty($lista_estudiante)) {
                        $success[] = "Reporte generado con éxito";
                        $_SESSION['success'] = $success;
                        $titulo_reporte = "Reporte de Estudiantes - Grado ".$ano_grado;
                        $nombre_archivo = 'estudiante_' . date('Ymd').'.pdf';
                        $boletaModelo = new BoletaPDF($data);
                        $boletaModelo->generarDescargarLista($lista_estudiante, $titulo_reporte, $nombre_archivo);
                    }else {
                        $errores[] = "'No se encontraron datos para generar el reporte con los filtros seleccionados.'";
                        $_SESSION['error'] =
                        $this->redirect('admin/download_listaEstudiante');
                    }
                }
            }
        }else{
            $this->redirect('admin/download_listaEstudiante');
        }
    }

    public function obtener_estudiante($id){
        $id_estudiante_encrypt = $id;
            if(!empty($id_estudiante_encrypt)){
                $id_estudiante_decrypt = decrypt_id($id_estudiante_encrypt);
            }

        $estudianteModelo = new EstudianteModelo($this->db);
        $estudiante = $estudianteModelo->obtener_estudianteDB($id_estudiante_decrypt);

            if (!is_array($estudiante)) {
                $this->redirect('admin/list_estudiante?error=no_data');
                exit();
            }
        $data = ['lista_estudiante'=>$estudiante];

        $this->renderView('estudiante/info_estudiante',$data);
    }

    public function list_profesor() {
        $profesorModelo = new ProfesorModelo($this->db);
        $lista_profesor = $profesorModelo->obtener_listaProfesor();

        $total_masculino = 0;
        $total_femenino = 0;
        $total_general = 0;

        if(is_array($lista_profesor)){
            foreach($lista_profesor as $profesor){
                if (isset($profesor)) {
                    if ($profesor->sexo === 'M') {
                        $total_masculino++;
                    }
                    if($profesor->sexo === 'F'){
                        $total_femenino++;
                    }
                }
            }
            $total_general = count($lista_profesor);
        }else{
            $lista_profesor = [];
        }

        $data = [
                'lista_profesor'=>$lista_profesor,
                'total_general'=>$total_general,
                'total_masculino'=>$total_masculino,
                'total_femenino'=>$total_femenino
                ];

        $this->renderView('profesor/index',$data);
    }

    public function obtener_profesor($id){
        $id_profesor_encrypt = $id;
            if (!empty($id_profesor_encrypt)) {
                $id_profesor_decrypt = decrypt_id($id_profesor_encrypt);
            }
        $profesorModelo = new ProfesorModelo($this->db);
        $profesor = $profesorModelo->obtener_profesorDB($id_profesor_decrypt);

            if (!is_array($profesor)) {
                $this->redirect('admin/list_profesor');
                exit();
            }
        $data = ['info_profesor'=>$profesor];

        $this->renderView('profesor/info_profesor',$data);
    }

    public function list_periodoEscolar() {
        $periodoEscolarModelo = new PeriodoEscolarModelo($this->db);

        $lista_periodoEscolar = $periodoEscolarModelo->obtener_periodoEscolar();
        if (!is_array($lista_periodoEscolar)) {
            $lista_periodoEscolar = [];
        }
        $data = [
                'lista_periodoEscolar'=>$lista_periodoEscolar
                ];

        $this->renderView('periodo_escolar/index',$data);
    }

    public function list_materia() {
        $materiasModelo = new MateriasModelo($this->db);

        $lista_materias = $materiasModelo->obtener_listaMaterias();

            if(!is_array($lista_materias)){
                $lista_materias = [];
            }

        $data = [
                'lista_materias'=>$lista_materias
                ];

        $this->renderView('materias/index',$data);
    }

    public function list_allocationProfesorMateria(){
        $id = decrypt_id($_GET['id'] ?? '');

        $materiasModelo = new MateriasModelo($this->db);

        $list_allocationProfesorMateria = $materiasModelo->obtener_relacionProfesorMateria($id);

            if (!is_array($list_allocationProfesorMateria)) {
                $list_allocationProfesorMateria = [];
            }

            $data = ['list_allocationProfesorMaterias'=>$list_allocationProfesorMateria];

        $this->renderView('list_allocationProfesorMaterias',$data);
    }

    public function list_comunidad() {
        $comunidadModelo = new ComunidadModelo($this->db);

        $lista_comunidades = $comunidadModelo->obtener_listaComunidades();

            if (!is_array($lista_comunidades)) {
                $lista_comunidades = [];
            }

        $data = [
                'lista_comunidades'=>$lista_comunidades
                ];
        $this->renderView('comunidad/index',$data);
    }

    public function insertarAsistente(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errores = [];
            $ubicacion_data = [];
            $id_ubicacion_data = [];
            $ocupacion = [];

            $nombre1 = trim($_POST['nombre1'] ?? '');
            $nombre2 = trim($_POST['nombre2'] ?? '');
            $apellido1 = trim($_POST['apellido1'] ?? '');
            $apellido2 = trim($_POST['apellido2'] ?? '');
            $sexo = trim($_POST['sexo'] ?? '');
            $fecha_nacimiento = trim($_POST['fechaNa'] ?? '');
            $cedula = trim($_POST['cedula'] ?? '');
            $id_estado = trim($_POST['estado_id'] ?? '');
            $id_municipio = trim($_POST['municipio_id'] ?? '');
            $id_comunidad = trim($_POST['comunidad_id'] ?? '');
            $municipio_texto = trim($_POST['municipio_texto'] ?? '');
            $comunidad_texto = trim($_POST['ciudad_texto'] ?? '');
            $estado_civil = trim($_POST['estadCivil'] ?? '');

            if (empty($nombre1)) {
                $errores[] = "El nombre es obligatorio";
            }

            if (empty($nombre2)) {
                $nombre2 = null;
            }

            if(empty($apellido1)){
                $errores[] = "El apellido es obligatorio";
            }

            if (empty($apellido2)) {
                $apellido2 = null;
            }

            if (empty($sexo)) {
                $errores[] = "El sexo es obligatorio";
            }else{
                if($sexo === 'M'){
                    $sexo = 'Masculino';
                }elseif($sexo === 'F'){
                    $sexo = 'Femenino';
                }
            }

            if (empty($fecha_nacimiento)) {
                $errores[] = "La fecha de nacimiento es obligatorio";
            }else{
                $fecha_nacimiento_mysql = null;
                $fecha_nacimiento_objeto = DateTime::createFromFormat('Y-m-d',$fecha_nacimiento);
                
                if($fecha_nacimiento_objeto && $fecha_nacimiento_objeto->format('Y-m-d') === $fecha_nacimiento){
                    $fecha_nacimiento_mysql = $fecha_nacimiento;
                }

                if(VALIDATE_EDAD) {
                    $fecha_nacimiento = new DateTime($fecha_nacimiento_mysql);
                    $hoy = new DateTime();
                    $intervalo = $hoy->diff($fecha_nacimiento);
                    $edad = $intervalo->y;
                    $edad_min = 18;
                    $edad_max = 70;
                    if($edad < $edad_min) {
                        $errores[] = "El rango mínimo de edad comprendida para un asistente es de ".$edad_min." años";
                    }
                    if($edad > $edad_max) {
                        $errores[] = "El rango máximo de edad comprendido para asistente es de ".$edad_max." años";
                    }
                }
            }

            if(empty($cedula)) {
                $errores[] = "La cédula es obligatorio";
            }else{
                $personaModelo = new PersonaModelo($this->db);
                $check_persona = $personaModelo->check_persona($cedula);
                if (is_array($check_persona)) {
                    $cedula_db = '';
                    foreach($check_persona as $persona){
                        $cedula_db = $persona->cedula;
                    }
                    if($cedula ==  $cedula_db){
                        $errores[] = "La cédula ya se encuentra registrada";
                    }
                }
            }

            if (empty($id_estado)) {
                $errores[] = "El estado es obligatorio";
            }else{
                $id_estado_decrypt = decrypt_id($id_estado);
                $estadosModelo = new EstadosModelo($this->db);
                $check_estados = $estadosModelo->obtener_estado($id_estado_decrypt);
                if(is_array($check_estados)){
                    $nombre_estado = '';
                    foreach($check_estados as $estados){
                        $nombre_estado = $estados->nombre_estado;
                    }
                }
            }

            if (!empty($id_municipio)) {
                $id_municipio_decrypt = decrypt_id($id_municipio);
                $municipioModelo = new MunicipioModelo($this->db);
                $check_municipio = $municipioModelo->obtener_municipio($id_municipio_decrypt);
                    if(is_array($check_municipio)){
                        foreach($check_municipio as $municipio){
                            $municipio_texto = null;
                            $nombre_municipio = $municipio['nombre_municipio'];
                        }
                    }
            }elseif(!empty($municipio_texto)){
                $nombre_municipio = null;
                $id_municipio = null;
            }elseif (empty($id_municipio) || empty($municipio_texto)) {
                $errores[] = "El municipio es obligatorio";
            }

            if (!empty($id_comunidad)) {
                $id_comunidad_decrypt = decrypt_id($id_comunidad);
                $comunidadModelo = new ComunidadModelo($this->db);
                $check_comunidad = $comunidadModelo->obtener_comunidad($id_comunidad_decrypt);
                    if(is_array($check_comunidad)){
                        foreach($check_comunidad as $comunidad){
                            $comunidad_texto = null;
                            $nombre_comunidad = $comunidad->nombre_comunidad;
                        }
                    }
            }elseif (!empty($comunidad_texto)) {
                $nombre_comunidad = null;
                $id_comunidad = null;
            }elseif(empty($id_comunidad) || empty($comunidad_texto)){
                $errores[] = "La comunidad es obligatoria";
            }

            if (empty($estado_civil)) {
                $errores[] = "El estado civil es obligatorio";
            }
            if (!empty($errores)) {
                $data = $_POST;
                $_SESSION['error'] = $errores;
                $this->renderView('asistente/add_asistente',$data);
            }

            if (empty($errores)) {
                
                $dataExtra = "Información del usuario";
            
                $ocupacion['ocupacion'] = "asistent";

                $asistente = [
                            "Primer nombre" =>$nombre1,
                            "Segundo nombre" =>$nombre2,
                            "Primer apellido" =>$apellido1,
                            "Segundo apellido" =>$apellido2,
                            "sexo" =>$sexo,
                            "fecha de nacimiento" =>$fecha_nacimiento_mysql,
                            "cedula" =>$cedula,
                            "estado civil" =>$estado_civil
                                ];

                $ubicacion_data = [
                                    "Estado" =>$nombre_estado,
                                    "Municipio" =>$nombre_municipio,
                                    "Comunidad" =>$nombre_comunidad,
                                    "municipio" =>$municipio_texto,
                                    "Ciudad" =>$comunidad_texto
                                    ];

                $ubicacion_id_data = [
                                    "id_estado" =>$id_estado,
                                    "id_municipio" =>$id_municipio,
                                    "id_comunidad" =>$id_comunidad
                                    ];
                $valorCarga = "user";
                $_SESSION['confirm_data'] = [
                                            'headTitle'=>'Datos personales',
                                            'form_data'=>$asistente,
                                            'form_ubicacion'=>$ubicacion_data,
                                            'form_id_ubicacion'=>$ubicacion_id_data,
                                            'valorCarga'=>$valorCarga,
                                            'dataExtra'=>$dataExtra,
                                            'ocupacion'=> 'asistent',
                                            'ocupacionDB'=>$ocupacion,
                                            'title'=> 'Confirmar datos de asistente',
                                            'message'=> 'Por favor, revise los datos antes de guardarlos',
                                            'next_action'=> 'guardarAsistente',
                                            ];
                $this->redirect('admin/confirmar');
            }
        }else{
            $this->redirect('admin/add_asistent?message=no_data');
        }
    }

    public function insertarEstudiante(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errores = [];
            $ubicacion_data = [];
            $id_ubicacion_data = [];
            $ocupacion = [];

            $sexo = trim($_POST['sexo'] ?? '');
            $fecha_nacimiento = trim($_POST['fechaNa'] ?? '');
            $id_estado = trim($_POST['estado_id'] ?? '');
            $id_municipio = trim($_POST['municipio_id'] ?? '');
            $id_comunidad = trim($_POST['comunidad_id'] ?? '');
            $municipio_texto = trim($_POST['municipio_texto'] ?? '');
            $comunidad_texto = trim($_POST['ciudad_texto'] ?? '');
            $estado_civil = trim($_POST['estadCivil'] ?? '');

            if (empty($nombre1 = trim($_POST['nombre1'] ?? ''))) {
                $errores[] = "EL nombre es obligatorio";
            }

            if (empty($nombre2 = trim($_POST['nombre2'] ?? ''))) {
                $nombre2 = null;
            }

            if(empty($apellido1 = trim($_POST['apellido1'] ?? ''))){
                $errores[] = "EL apellido es obligatorio";
            }

            if (empty($apellido2 = trim($_POST['apellido2'] ?? ''))) {
                $apellido2 = null;
            }

            if (empty($sexo)) {
                $errores[] = "El sexo es obligatorio";
            }else{
                if ($sexo == 'M') {
                    $sexo = 'Masculino';
                }elseif($sexo == 'F'){
                    $sexo = 'Femenino';
                }
            }

            if(empty($fecha_nacimiento)){
                $errores[] = "La fecha de nacimiento es obligatoria";
            }else{
                $fecha_nacimiento_mysql = null;
                $fecha_nacimiento_objeto = DateTime::createFromFormat('Y-m-d',$fecha_nacimiento);
                if($fecha_nacimiento_objeto && $fecha_nacimiento_objeto->format('Y-m-d') === $fecha_nacimiento){
                    $fecha_nacimiento_mysql = $fecha_nacimiento;
                }
    
                if(VALIDATE_EDAD) {
                    $fecha_nacimiento = new DateTime($fecha_nacimiento_mysql);
                    $hoy = new DateTime();
                    $intervalo = $hoy->diff($fecha_nacimiento);
                    $edad = $intervalo->y;
                    $edad_min = 10;
                    $edad_max = 22;
                    if($edad < $edad_min) {
                        $errores[] = "El rango mínimo de edad comprendida para un estudiante es de ".$edad_min." años";
                    }

                    if($edad > $edad_max) {
                        $errores[] = "El rango máximo de edad comprendido para estudiante es de ".$edad_max." años";
                    }
                }
            }

            if (empty($cedula = trim($_POST['cedula'] ?? ''))) {
                $errores[] = "La cédula es obligatorio";
            }else{
                $personaModelo = new PersonaModelo($this->db);
                $check_persona = $personaModelo->check_persona($cedula);
                    if(is_array($check_persona)){
                        $cedula_db = '';
                        foreach($check_persona as $persona){
                            $cedula_db = $persona->cedula;
                        }
                        if ($cedula == $cedula_db) {
                                $errores[] = "La cédula ya se encuentra registrada";
                        }
                    }
            }

            if(!empty($id_estado)){
                $id_estado_decrypt = decrypt_id($id_estado);
                $estadosModelo = new EstadosModelo($this->db);
                $check_estados = $estadosModelo->obtener_estado($id_estado_decrypt);
                    if (is_array($check_estados)) {
                        foreach($check_estados as $estados){
                            $nombre_estado = $estados->nombre_estado;
                        }
                    }else{
                        $errores[] = "Datos de Estado no encontrado";
                    }
            }elseif (empty($id_estado)) {
                $errores[] = "El estado es obligatorio";
            }

            if (!empty($id_municipio)) {
                $id_municipio_decrypt = decrypt_id($id_municipio);
                $municipioModelo = new MunicipioModelo($this->db);
                $check_municipio = $municipioModelo->obtener_municipio($id_municipio_decrypt);
                    if(is_array($check_municipio)){
                        foreach($check_municipio as $municipio){
                            $municipio_texto = null;
                            $nombre_municipio = $municipio['nombre_municipio'];
                        }
                    }
            }elseif(!empty($municipio_texto)){
                $nombre_municipio = null;
                $id_municipio = null;
            }else{
                $errores[] = "El campo de municipio es obligatorio";
            }

            if (!empty($id_comunidad)) {
                $id_comunidad_decrypt = decrypt_id($id_comunidad);
                $comunidadModelo = new ComunidadModelo($this->db);
                $check_comunidad = $comunidadModelo->obtener_comunidad($id_comunidad_decrypt);
                    if (is_array($check_comunidad)) {
                        foreach ($check_comunidad as $comunidad) {
                            $comunidad_texto = null;
                            $nombre_comunidad = $comunidad->nombre_comunidad;
                        }
                    }
            }elseif(!empty($comunidad_texto)){
                $nombre_comunidad = null;
                $id_comunidad = null;
            }else{
                $errores[] = "La comunidad/ciudad es obligatorio";
            }

            if (empty($estado_civil)) {
                $errores[] = "El estado civil es obligatorio";
            }

            if (!empty($errores)) {
                $data = $_POST;
                $_SESSION['error'] = $errores;
                $this->renderView('estudiante/add_estudiante',$data);
            }

            $dataExtra = "Información académica";

            $ocupacion['ocupacion'] = "Estudiante";

            $estudiante = [
                        "Primer nombre" =>$nombre1,
                        "Segundo nombre" =>$nombre2,
                        "Primer apellido" =>$apellido1,
                        "Segundo apellido" =>$apellido2,
                        "sexo"=>$sexo,
                        "fecha de nacimiento" =>$fecha_nacimiento_mysql,
                        "cedula" =>$cedula,
                        "estado civil" =>$estado_civil
                        ];
            $ubicacion_data = [ 
                                "Estado"=>$nombre_estado,
                                "Municipio"=>$nombre_municipio,
                                "Comunidad"=>$nombre_comunidad,
                                "municipio"=>$municipio_texto,
                                "Ciudad"=>$comunidad_texto
                                ];
            $ubicacion_id_data = [  "id_estado" =>$id_estado,
                                    "id_municipio" => $id_municipio,
                                    "id_comunidad" => $id_comunidad
                                    ];
            $valorCarga = "no_user";
            $_SESSION['confirm_data'] = [
                                            'headTitle'=>'Datos personales',
                                            'form_data' => $estudiante,
                                            'form_ubicacion' => $ubicacion_data,
                                            'form_id_ubicacion' => $ubicacion_id_data,
                                            'valorCarga'=>$valorCarga,
                                            'dataExtra' => $dataExtra,
                                            'ocupacion' => 'Estudiante',
                                            'ocupacionDB' => $ocupacion,
                                            'title' => 'Confirmar Datos del estudiante',
                                            'message' => 'Por favor, revise los datos antes de guardarlos',
                                            'next_action' => 'guardarEstudiante',
                                            ];
            $this->redirect('admin/confirmar');
        }else{
            $this->redirect('admin/add_estudiante');            
        }

    }

    public function insertarProfesor(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errores = [];
            $ubicacion_data = [];
            $id_ubicacion_data = [];
            $ocupacion = [];
            $cedula = trim($_POST['cedula'] ?? '');
            $fecha_nacimiento = $_POST['fechaNa'] ?? '';
            $id_estado = trim($_POST['estado_id'] ?? '');
            $id_municipio = trim($_POST['municipio_id'] ?? '');
            $id_comunidad = trim($_POST['comunidad_id'] ?? '');
            $municipio_texto = trim($_POST['municipio_texto'] ?? '');
            $ciudad_texto = trim($_POST['ciudad_texto'] ?? '');
            $estado_civil = trim($_POST['estadCivil'] ?? '');

            if (empty($nombre1 = trim($_POST['nombre1'] ?? ''))) {
                $errores[] = "El campo de primer nombre está vacío";
            }
            if (empty($nombre2 = trim($_POST['nombre2'] ?? ''))) {
                $nombre2= null;
            }
            if(empty($apellido1 = trim($_POST['apellido1'] ?? ''))){
                $errores[] = "El campo de primer apellido está vacío";
            }
            if(empty($apellido2 = trim($_POST['apellido2'] ?? ''))){
                $apellido2 = null;
            }
            if(empty($sexo = trim($_POST['sexo'] ?? ''))){
                $errores[] = "El campo de sexo está vacío";
            }else{
                if($sexo == 'M'){
                    $sexo = 'Masculino';
                }elseif($sexo == 'F'){
                    $sexo = 'Femenino';
                }
            }  

            if(empty($fecha_nacimiento)){
                $errores[] = "Ingrese la fecha de nacimiento correctamente";
            }else{
                $fecha_nacimiento_mysql = null;
                $fecha_objeto = DateTime::createFromFormat('Y-m-d',$fecha_nacimiento);
                if($fecha_objeto && $fecha_objeto->format('Y-m-d') === $fecha_nacimiento){
                $fecha_nacimiento_mysql = $fecha_nacimiento;
                }

                if(VALIDATE_EDAD) {
                    $fecha_nacimiento_check = new DateTime($fecha_nacimiento_mysql);
                    $hoy = new DateTime();
                    $intervalo = $hoy->diff($fecha_nacimiento_check);
                    $edad = $intervalo->y;
                    $edad_min = 20;
                    $edad_max = 70;
                    if($edad < $edad_min) {
                        $errores[] = "El rango mínimo de edad comprendida para un profesor es de ".$edad_min." años";
                    }

                    if($edad > $edad_max) {
                        $errores[] = "El rango máximo de edad comprendido para profesor es de ".$edad_max." años";
                    }
                }
            }

            if (empty($cedula)) {
                $errores[] = "El campo de cédula está vacío";
            }else{
                $personaModelo = new PersonaModelo($this->db);
                $check_persona = $personaModelo->check_persona($cedula);
                if (is_array($check_persona)) {
                    $cedula_db = '';
                    foreach($check_persona as $persona){
                        $cedula_db = $persona->cedula;
                    }
                    if ($cedula == $cedula_db) {
                        $errores[] = "La cédula ya se encuentra registrada";
                    }
                }                
            }

            if (empty($id_estado)) {
                $errores[] = "El campo de estado está vacío";
            }elseif(!empty($id_estado)){
                $id_estado_decrypt = decrypt_id($id_estado);
                $estadosModelo = new EstadosModelo($this->db);
                $check_estados = $estadosModelo->obtener_estado($id_estado_decrypt);
                    if (is_array($check_estados)) {
                        foreach($check_estados as $estados){
                            $nombre_estado = $estados->nombre_estado;
                        }
                    }
                $ubicacion_data['Estado'] = $id_estado;
            }

            if(!empty($id_municipio)){
                $id_municipio_decrypt = decrypt_id($id_municipio);
                $municipioModelo = new MunicipioModelo($this->db);
                $check_municipio = $municipioModelo->obtener_municipio($id_municipio_decrypt);
                    if(is_array($check_municipio)){
                        foreach($check_municipio as $municipio){
                            $municipio_texto = null;
                            $nombre_municipio = $municipio['nombre_municipio'];
                        }
                    }
            }elseif(empty($municipio_texto)){
                $errores[] = "El campo de municipio está vacío";
            }elseif (!empty($municipio_texto)) {
                $nombre_municipio = null;
                $id_municipio = null;
            }

            if (!empty($id_comunidad)) {
                $id_comunidad_decrypt = decrypt_id($id_comunidad);
                $comunidadModelo = new ComunidadModelo($this->db);
                $check_comunidad = $comunidadModelo->obtener_comunidad($id_comunidad_decrypt);
                    if (is_array($check_comunidad)) {
                        foreach ($check_comunidad as $comunidad) {
                            $comunidad_texto = null;
                            $nombre_comunidad = $comunidad->nombre_comunidad;
                        }
                    }
            }elseif (!empty($ciudad_texto)) {                
                $nombre_comunidad = null;
                $id_comunidad = null;
            }else{
                $errores[] = "EL campo de ciudad/comunidad está vacío";
            }

            if(empty($estado_civil)){
                $errores[] = "El estado civil está vacío";
            }

            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $data = $_POST;
                $this->renderView('profesor/add_profesor',$data);
            }

            

            $dataExtra = "Información laboral";
            
            $ocupacion['ocupacion'] = "Profesor";
            $profesor = [
                        "Primer nombre"=>$nombre1,
                        "Segundo nombre"=>$nombre2,
                        "Primer apellido"=>$apellido1,
                        "Segundo apellido"=>$apellido2,
                        "sexo"=>$sexo,
                        "fecha de nacimiento"=>$fecha_nacimiento_mysql,
                        "cedula"=>$cedula,
                        "estado civil"=>$estado_civil
                        ];
            $ubicacion_data = [
                                "Estado"=>$nombre_estado,
                                "Municipio"=>$nombre_municipio,
                                "Comunidad"=>$nombre_comunidad,
                                "municipio"=>$municipio_texto,
                                "Ciudad"=>$ciudad_texto];
            $id_ubicacion_data = [
                                "id_estado"=>$id_estado,
                                "id_municipio"=>$id_municipio,
                                "id_comunidad"=>$id_comunidad];

            $valorCarga = "user";
            $_SESSION['confirm_data'] = [
            'headTitle'=>'Datos personales',
            'form_data' => $profesor,
            'form_ubicacion' => $ubicacion_data,
            'form_id_ubicacion' => $id_ubicacion_data,
            'dataExtra' => $dataExtra,
            'valorCarga' => $valorCarga,
            'ocupacion' => 'Profesor',
            'ocupacionDB' => $ocupacion,
            'title' => 'Confirmar Datos del Profesor',
            'message' => 'Por favor, revise los siguientes datos antes de guardarlos.',
            'next_action' => 'guardarProfesor' 
             ];
            $this->redirect('admin/confirmar');
        }else{
            $this->redirect('admin/add_profesor');
        }
    }

    public function insertar_allocation_profesorMaterias(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $errores = [];
            $id_profesor_encrypt = trim($_POST['profesor_id'] ?? '');
            $id_materias_encrypt = $_POST['materias_id'] ?? [];

            $materias_datos = [];
            if (empty($id_profesor_encrypt)) {
                $errores[] = "Debe seleccionar un profesor";
            }else{
                $id_profesor = decrypt_id($id_profesor_encrypt);
            }

            if (empty($id_materias_encrypt)) {
                $errores[] = "Debe seleccionar al menos una materia";
            }else{
                $id_materias_decrypt = [];
                foreach($id_materias_encrypt as $encrypt_id){

                    $decrypt_val = decrypt_id($encrypt_id);
                    if(is_numeric($decrypt_val) && $decrypt_val > 0){
                        $id_materias_decrypt[] = (int)$decrypt_val;
                    }
                }
            }

            $id_materias = $id_materias_decrypt;

            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $this->redirect('admin/allocation_profesorMateria');
            }

            if (empty($errores)) {
                $profesorModelo = new ProfesorModelo($this->db);
                $dataProfesor = $profesorModelo->obtener_profesorDB($id_profesor);

                $profesor = '';
                if (!empty($dataProfesor)) {
                    $profesor = $dataProfesor;
                }else{
                    $profesor[] = "El profesor no se encuentra en el sistema";
                }

                $materiasModelo = new MateriasModelo($this->db);
                $lista_materias = $materiasModelo->obtener_listaMaterias_array($id_materias);

                    if (is_array($lista_materias) && !empty($lista_materias)) {
                        $materias_datos = $lista_materias;
                    }else{
                        $materias_datos[] = "Las materias seleccionadas no fueron encontradas";
                    }

                $datosForm_id = [
                            'id_profesor'=>$id_profesor,
                            'id_materias'=>$id_materias
                            ];
                $valorCarga['valorCarga'] = 'no_user';
                $setion = "materias_profesor";
                $_SESSION['confirm_data'] = [
                                            'headTitle'=>'Datos de asignación materia-profesor',
                                            'form_data'=> [],
                                            'form_data_materiasArray'=>$materias_datos,
                                            'form_data_profesor'=>$profesor,
                                            'id_form'=>$datosForm_id,
                                            'title'=>'Confirmar datos asignación materias-profesor',
                                            'valorCarga'=>$valorCarga,
                                            'message'=>'Por favor, revise los datos antes de guardarlos',
                                            'ocupacion'=>$setion,
                                            'next_action'=>'guardarAllocationProfesorMaterias'
                                            ];
                $this->redirect('admin/confirmar');
            }
        }else{
            $this->redirect('admin/allocation_profesorMateria');
        }
    }

    public function insertarPeriodoEscolar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errores = [];

            $periodo1 = trim($_POST['anoPeriodo1'] ?? '');
            $periodo2 = trim($_POST['anoPeriodo2'] ?? '');

            if (empty($periodo1)) {
                $errores[] = "El perido uno no puede estar vacío";
            }

            if (empty($periodo2)) {
                $errores[] = "El perido dos no puede estar vacío";
            }else{
                $periodo_escolar_post = $periodo1.' - '.$periodo2;
                
                $periodoEscolarModelo = new PeriodoEscolarModelo($this->db);
                $check_periodo = $periodoEscolarModelo->obtener_periodoEscolar();
                    foreach($check_periodo as $periodo_escolar_db){
                        if ($periodo_escolar_post === $periodo_escolar_db->periodo_escolar) {
                            $errores[] = "El periodo ".$periodo_escolar_post." ya se encuentra en el sistema";
                        }
                    }
            }

            if ($periodo1 === $periodo2) {
                $errores[] = "Los periodos academicos no pueden ser iguales";
            }

            if($periodo1 > $periodo2){
                $errores[] = "El primer periodo debe ser menor que el segundo periodo seleccionado";
            }
            if($periodo2 < $periodo1){
                $errores[] = "El segundo periodo debe ser mayor que el primero periodo seleccionado";
            }

            if (!empty($errores)) {
                $data = $_POST;
                $_SESSION['error'] = $errores;
                $this->renderView('periodo_escolar/add_periodoEscolar',$data);
            }

            if (empty($errores)) {
                $periodo_escolar = [
                                'inicia' =>$periodo1,
                                'culmina' =>$periodo2
                                    ];
                $valorCarga['valorCarga'] = 'no_user';
                $ocupacion = 'periodo_escolar';
                $_SESSION['confirm_data'] = [
                                            'headTitle'=>'Datos de periodo escolar',
                                            'form_data' =>$periodo_escolar,
                                            'title' =>'Confirmar datos del periodo escolar',
                                            'valorCarga' =>$valorCarga,
                                            'message' =>'Por favor, revise los datos antes de guardarlos.',
                                            'ocupacion'=>$ocupacion,
                                            'next_action' =>'guardarPeriodoEscolar'
                                            ];
                $this->redirect('admin/confirmar');
            }
            
        }else{
            $this->redirect('admin/add_periodoEscolar');
        }
    }

    public function insertarMaterias(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [];

            $nombre_materia = trim($_POST['nombreMateria'] ?? '');
            $description_materia = trim($_POST['descripcion'] ?? '');
            $ano_grado = trim($_POST['anoGrado'] ?? '');

            if (empty($nombre_materia)) {
                $errores[] = "El nombre de la materia es obligatoria";
            }

            if (empty($description_materia)) {
                $errores[] = "La descripción de la meteria es obligatorio";
            }

            if (empty($ano_grado)) {
                $errores[] = "El año/grado es obligatorio";
            }

            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $data = $_POST;
                $this->renderView('admin/add_materias',$data);
            }

            if (empty($errores)) {
                $materias = [
                            'nombre materia'=>$nombre_materia,
                            'descripción'=>$description_materia,
                            'año/grado'=>$ano_grado
                            ];
                $valorCarga['valorCarga'] = 'materia';
                $ocupacion = 'materia';
                $_SESSION['confirm_data'] = [
                                            'headTitle'=>'Datos de materia',
                                            'form_data'=>$materias,
                                            'title'=>'Confirmar datos de materia',
                                            'message'=>'Por favor, revise los datos antes de guardarlos.',
                                            'valorCarga'=>$valorCarga,
                                            'ocupacion'=>$ocupacion,
                                            'next_action'=>'guardarMateria',
                                            ];
                $this->redirect('admin/confirmar');
            }
        }else{
            $this->redirect('admin/add_materias');
        }
    }

    public function insertarComunidad(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errores = [];

            if (empty($nombre_comunidad = trim($_POST['nombre_comunidad'] ?? ''))) {
                $errores[] = "El nombre de la comunidad es obligatorio";
            }

            if (!empty($errores)) {
                $data = $_POST;
                $_SESSION['error'] = $errores;
                $this->renderView('admin/add_comunidad',$data);
            }

            if (empty($errores)) {
                $comunidad = ['nombre comunidad'=>$nombre_comunidad];
                $valorCarga['valorCarga'] = "no_user";
                $ocupacion = "comunidad";
                $id_municipioFreites['id_municipio'] = 1; 
                $_SESSION['confirm_data'] = [
                                            'headTitle'=>'Datos comunidad',
                                            'form_data'=>$comunidad,
                                            'title'=>'Confirmar datos de comunidad',
                                            'message'=>'Por favor, revise los datos antes de guardarlos.',
                                            'valorCarga'=>$valorCarga,
                                            'ocupacion'=>$ocupacion,
                                            'id_municipio'=>$id_municipioFreites,
                                            'next_action'=>'guardarComunidad'
                                            ];
                $this->redirect('admin/confirmar');
            }
        }else{
            $this->redirect('admin/add_comunidad');
        }
    }

    public function update_rule(){
        $this->disableBrowserCache();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [];
            $entity_type = filter_input(INPUT_POST, 'entity_type',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id_persona_post = filter_input(INPUT_POST, 'id_persona',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $update_rule_post = filter_input(INPUT_POST, 'update_rule',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $id_entity_type = trim($id_persona_post) ?? '';
            $update_rule = trim($update_rule_post)?? '';

            $seccion_destino = '';
            if (empty($entity_type)) {
                $errores[] = "No se han cargado los datos relacionados a la entidad";
            }

            if (empty($id_entity_type)) {
                $errores[] = "No se han cargado los datos relacionados";
            }

            if (empty($update_rule)) {
                $errores[] = "Debe seleccionar una opción para actualizar";
            }

            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $this->redirect('admin/obtener_'.$entity_type.'/'.$id_entity_type);
            }
            if (empty($errores)) {
                switch($entity_type){
                    case 'estudiante':
                        if ($update_rule === 'persona') {
                            $seccion_destino = 'persona';
                        }elseif ($update_rule === 'academico') {
                            $seccion_destino = 'academico';
                        }elseif ($update_rule === 'ubicacion') {
                            $seccion_destino = 'ubicacion';
                        }elseif ($update_rule === 'fullDatos') {
                            $seccion_destino = 'full';
                        }
                    break;
                    
                    case 'asistent':
                        if ($update_rule === 'persona') {
                            $seccion_destino = 'persona';
                        }elseif ($update_rule === 'academico') {
                            $seccion_destino = 'academico';
                        }elseif ($update_rule === 'ubicacion') {
                            $seccion_destino = 'ubicacion';
                        }elseif ($update_rule === 'fullDatos') {
                            $seccion_destino = 'full';
                        }                        
                    break;

                    case 'profesor':
                        if ($update_rule === 'persona') {
                            $seccion_destino = 'persona';
                        }elseif ($update_rule === 'academico') {
                            $seccion_destino = 'academico';
                        }elseif ($update_rule === 'ubicacion') {
                            $seccion_destino = 'ubicacion';
                        }elseif ($update_rule === 'fullDatos') {
                            $seccion_destino = 'full';
                        }                        
                    break;
                    case 'materias':
                        $seccion_destino = 'materias';                    
                    break;
                    case 'comunidades':
                        $seccion_destino = 'comunidades';
                    break;
                }
                $this->redirect('admin/update/'.$entity_type.'/'.$seccion_destino.'/'.$id_entity_type);
            }
        }else{
            $this->redirect('admin/list_estudiante/error_not_found');
        }
    }

    public function update_simpleRule($entity_type, $id_entity_type){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($id_entity_type)) {
                $id_entity_decrypt = decrypt_id($id_entity_type);
                $_SESSION['time'] = 'activo';
            }else{
                $this->redirect('admin/list_'.$entity_type.'?message=no_data');
            }
            if($entity_type === 'materia'){
                $materiasModelo = new MateriasModelo($this->db);
                $info_entity = $materiasModelo->obtener_materia($id_entity_decrypt);
            }elseif($entity_type === 'comunidad'){
                $comunidadModelo = new ComunidadModelo($this->db);
                $info_entity = $comunidadModelo->obtener_comunidad($id_entity_decrypt);
            }

            if (!is_array($info_entity)) {
                $this->redirect('admin/list_'.$entity_type.'?message=empty');
            }
            $data_entity = $info_entity;
            unset($info_entity);
            $data = [
                    'id_entity_type'=>$id_entity_type,
                    'title'=>'Datos '.$entity_type,
                    'headTitle'=>'Datos '.$entity_type,
                    'form_data'=>$data_entity,
                    'message'=>'Por favor revise los datos antes de guardarlos',
                    'ocupacion'=>$entity_type,
                    'next_action'=>'update_simple'
                    ];
            $this->renderView('confirmar_update_simple',$data);
        }else{
            $this->redirect('admin/list_'.$entity_type);
        }
    }

    public function update($entity_type, $seccion, $id_entity_type){

            if(!empty($id_entity_type)){
                $id_entity_decrypt = decrypt_id($id_entity_type);
            }
             if ($entity_type === 'estudiante') {
                $estudianteModelo = new EstudianteModelo($this->db);
                switch($seccion){
                    case 'persona':
                        $datos = $estudianteModelo->obtener_personaEstudiante($id_entity_decrypt);
                    break;
                    case 'academico':
                        $datos = $estudianteModelo->obtener_academicoEstudiante($id_entity_decrypt);
                    break;
                    case 'ubicacion':
                        $datos = $estudianteModelo->obtener_ubicacionEstudiante($id_entity_decrypt);
                    break;
                    case 'full':
                        $datos = $estudianteModelo->obtener_estudianteDB($id_entity_decrypt);
                    break;

                    default:
                        $this->redirect('admin/dashboard');
                    return;
                }
               
            }elseif ($entity_type === 'asistent') {
                $asistenteModelo = new AsistenteModelo($this->db);
                switch($seccion){
                    case 'persona':
                        $datos = $asistenteModelo->obtener_personaAsistente($id_entity_decrypt);
                    break;
                    case 'ubicacion':
                        $datos = $asistenteModelo->obtener_ubicacionAsistente($id_entity_decrypt);
                    break;
                    case 'full':
                    $datos = $asistenteModelo->obtener_asistente($id_entity_decrypt);
                    break;

                    default:
                        $this->redirect('admin/dashboard');
                    return;
                }
            }elseif ($entity_type === 'profesor') {
                $profesorModelo = new ProfesorModelo($this->db);
                switch($seccion){
                    case 'persona':
                        $datos = $profesorModelo->obtener_personaProfesor($id_entity_decrypt);
                    break;
                    case 'academico':
                        $datos = $profesorModelo->obtener_academicoProfesor($id_entity_decrypt);
                    break;
                    case 'ubicacion':
                        $datos = $profesorModelo->obtener_ubicacionProfesor($id_entity_decrypt);
                    break;
                    case 'full':
                        $datos = $profesorModelo->obtener_profesorDB($id_entity_decrypt);
                    break;

                    default:
                        $this->redirect('admin/dashboard');
                    return;
                }
            }elseif ($entity_type === 'materias') {
                $materiasModelo = new MateriasModelo($this->db);
                $datos = $materiasModelo->obtener_materias($id_entity_type);
            }elseif ($entity_type === 'comunidades') {
                $comunidadModelo = new ComunidadModelo($this->db);
                $datos = $comunidadModelo->obtener_comunidad($id_entity_type);
            }

            if (!is_array($datos)) {
                $this->redirect('admin/list_'.$entity_type.'?error=no_update');
            }

            $valorCarga = "no_user";
            $method_fullUpdate = 'update_fullExecute';
            $method_seccionUpdate = 'update_seccionExecute';

            $fullDatos = [
                    'id_persona'=>$id_entity_type,
                    'headTitle'=>'Datos personales',
                    'form_data'=>$datos,
                    'valorCarga'=>$valorCarga,
                    'ocupacion'=> $entity_type,
                    'seccion'=>$seccion,
                    'title'=> 'Revisar datos para actualizar',
                    'message'=> 'Por favor, revise los datos antes de guardarlos'
                    ];
            if ($seccion === 'full') {

                $fullDatos['next_action'] = $method_fullUpdate;
                
                if ($entity_type == 'estudiante') {                   
                $selectModelo = new SelectModelo($this->db);
                    $data = array_merge($fullDatos,
                                        ['select_estadoCivil'=> $selectModelo->obtener_estados_civil()],
                                        ['select_generos'=> $selectModelo->obtener_generos()],
                                        ['select_estatus_estudiante'=> $estudianteModelo->obtener_estatus_estudinate()]);
                }else{
                    $data = array_merge($fullDatos,
                                        ['select_estadoCivil'=> $selectModelo->obtener_estados_civil()],
                                        ['select_generos'=> $selectModelo->obtener_generos()]);
                }
                if ($entity_type === 'profesor') {
                    $data['select_estatus_profesor'] = $selectModelo->obtener_estatus_profesor();
                }
                
                $this->renderView('confirmar_update',$data);
            }elseif(($seccion === 'persona') or ($seccion === 'ubicacion') or ($seccion === 'academico')){
                $selectModelo = new SelectModelo($this->db);
                if ($seccion === 'persona') {
                    $fullDatos['select_generos'] = $selectModelo->obtener_generos();
                    $fullDatos['select_estadoCivil'] = $selectModelo->obtener_estados_civil();
                }
                if ($entity_type === 'profesor') {
                    $fullDatos['select_estatus_profesor'] = $selectModelo->obtener_estatus_profesor();
                }
                $fullDatos['next_action'] = $method_seccionUpdate;

                $data = $fullDatos;
                $this->renderView('confirmar_update_parts',$data);
            }                
    }

    public function update_fullExecute($entity_type, $id){

        $this->disableBrowserCache();                  
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errores = [];
            $municipio_texto = null;
            $comunidad_texto = null;
            $municipioId_decrypt = null;
            $comunidadId_decrypt = null;
            $edad_min = '';
            $edad_max = '';
            $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
            $estadoId_encrypt = trim($_POST['estado_id'] ?? '');
            $municipioId_encrypt_post = trim($_POST['municipio_id'] ?? '');
            $municipio_texto_post = trim($_POST['municipio_texto'] ?? '');
            $comunidadId_encrypt_post = trim($_POST['comunidad_id'] ?? '');
            $comunidad_texto_post = trim($_POST['comunidad_texto'] ?? '');

            if ($entity_type === 'estudiante') {
                $edad_min = 10;
                $edad_max = 20;
                if (empty($periodo_escolar = trim($_POST['periodo_escolar'] ?? ''))) {
                    $errores[] = "El periodo escolar es obligatorio";
                }elseif(!empty($periodo_escolar)){
                    $id_periodo_escolar = decrypt_id($periodo_escolar);
                }

                if (empty($ano_grado = trim($_POST['ano_grado'] ?? ''))) {
                    $errores[] = "El ano/grado es obligatorio";
                }
                if(empty($estatus = trim($_POST['estatus'] ?? ''))){
                    $errores[] = "El estatus es obligatorio";
                }
            }elseif ($entity_type === 'profesor') {
                $edad_min = 20;
                $edad_max = 70;
                if(empty($estatus = trim($_POST['estatus'] ?? ''))){
                    $errores[] = "El estatus es obligatorio";
                }
            }elseif($entity_type === 'asistent'){
                $edad_min = 20;
                $edad_max = 70;
            }

            if (empty($nombre1 = trim($_POST['nombre1'] ?? ''))) {
                $errores[] = "El nombre es obligatorio";
            }
            if (empty($nombre2= trim($_POST['nombre2'] ?? ''))) {
                $nombre2 = null;
            }
            if (empty($apellido1 = trim($_POST['apellido1'] ?? ''))) {
                $errores[] = "El apellido es obligatorio";
            }
            if (empty($apellido2 = trim($_POST['apellido2'] ?? ''))) {
                $apellido2 = null;
            }
            if (empty($sexo = trim($_POST['sexo'] ?? ''))) {
                $errores[] = "Es genero es obligatorio";
            }
            if (empty($fecha_nacimiento)) {
                $errores[] = "La fecha de nacimiento es obligatorio";
            }else{
                $fecha_nacimiento_mysql = null;
                $fecha_nacimiento_objeto = DateTime::createFromFormat('Y-m-d',$fecha_nacimiento);
                if($fecha_nacimiento_objeto && $fecha_nacimiento_objeto->format('Y-m-d') === $fecha_nacimiento){
                    $fecha_nacimiento_mysql = $fecha_nacimiento;
                }
    
                if(VALIDATE_EDAD) {
                    $fecha_nacimiento = new DateTime($fecha_nacimiento_mysql);
                    $hoy = new DateTime();
                    $intervalo = $hoy->diff($fecha_nacimiento);
                    $edad = $intervalo->y;

                    if($edad < $edad_min) {
                        $errores[] = "El rango mínimo de edad comprendida para un ".$entity_type." es de ".$edad_min." años";
                    }

                    if($edad > $edad_max) {
                        $errores[] = "El rango máximo de edad comprendido para ".$entity_type." es de ".$edad_max." años";
                    }
                }
            }

            if (empty($estado_civil = trim($_POST['estado_civil'] ?? ''))) {
                $errores[] = "El estado civil es obligatorio";
            }

            if (empty($estadoId_encrypt)) {
                $errores[] = "El estado es obligatorio";
            }elseif(!empty($estadoId_encrypt)){
                $estadoId_decrypt = decrypt_id($estadoId_encrypt); 
            }

            if (!empty($municipioId_encrypt_post)) {                
                $municipioId_decrypt = decrypt_id($municipioId_encrypt_post);
            }elseif(!empty($municipio_texto_post)){
                $municipio_texto = $municipio_texto_post;
            }else{
                $errores[] = "El municipio es obligatorio";
            }

            if (!empty($comunidadId_encrypt_post)) {                
                $comunidadId_decrypt = decrypt_id($comunidadId_encrypt_post);
            }elseif(!empty($comunidad_texto_post)){
                $comunidad_texto = $comunidad_texto_post;
            }else{
                $errores[] = "La comunidad es obligatoria";
            }

            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $this->redirect('admin/obtener_'.$entity_type.'/'.$id);
            }
            if (empty($errores)) {
                if (!empty($id)) {
                    $id_decrypt = decrypt_id($id);
                }
                $datosCompletos = [
                                'nombre1'=>$nombre1,
                                'nombre2'=>$nombre2,
                                'apellido1'=>$apellido1,
                                'apellido2'=>$apellido2,
                                'sexo'=>$sexo,
                                'fecha_nacimiento'=>$fecha_nacimiento_mysql,
                                'estado_civil'=>$estado_civil,
                                'estado_id'=>$estadoId_decrypt,
                                'municipio_id'=>$municipioId_decrypt,
                                'municipio_texto'=>$municipio_texto,
                                'comunidad_id'=>$comunidadId_decrypt,
                                'comunidad_texto'=>$comunidad_texto];

                if ($entity_type === 'estudiante') {
                    $datosCompletos = [
                                'periodo_escolar'=>$id_periodo_escolar,
                                'ano_grado'=>$ano_grado,
                                'estatus'=>$estatus];
                    $estudianteModelo = new EstudianteModelo($this->db);
                    $resultado = $estudianteModelo->update_estudiante_todo($datosCompletos, $id_decrypt);
                }elseif ($entity_type === 'profesor') {
                    $datosCompletos['estatus'] = $estatus;
                    $profesorModelo = new ProfesorModelo($this->db);
                    $resultado = $profesorModelo->update_profesor_todo($datosCompletos, $id_decrypt);
                }elseif($entity_type === 'asistent'){
                    $asistenteModelo = new AsistenteModelo($this->db);
                    $resultado = $asistenteModelo->update_asistente_todo($datosCompletos, $id_decrypt);
                }

                if($resultado){
                    $success[] = "Actualizacion completada";
                    $_SESSION['success'] = $sucess;
                    $this->redirect('admin/obtener_'.$entity_type.'/'.$id);
                }else{
                    $errores[] = "Ocurrió un error inesperado";
                    $_SESSION['error'] = $errores;
                    $this->redirect('admin/obtener_'.$entity_type.'/' . $id . '/error');
                }
            }
        }
    }

    public function update_seccionExecute($entity_type, $seccion, $id){
        
        $this->disableBrowserCache();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = [];
            if($seccion === 'persona'){           
                $nombre2 = null;
                $apellido2 = null;
                $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');

                if(empty($nombre1 = trim($_POST['nombre1'] ?? ''))){
                    $errores[] = "El nombre es obligatorio";
                }
                if (empty(trim($_POST['nombre2'] ?? ''))) {
                    $nombre2 = null;
                }else{
                    $nombre2 = $_POST['nombre2'];
                }
                if (empty($apellido1 = trim($_POST['apellido1'] ?? ''))) {
                    $errores[] = "El apellido es obligatorio"; 
                }
                if (empty(trim($_POST['apellido2'] ?? ''))) {
                    $apellido2 = null;
                }else{
                    $apellido2 = $_POST['apellido2'];
                }
                if (empty($sexo = trim($_POST['sexo'] ?? ''))) {
                    $errores[] = "El sexo es obligatorio";
                }

                $fecha_nacimiento_mysql = null;
                $fecha_nacimiento_objeto = DateTime::createFromFormat('Y-m-d',$fecha_nacimiento);
                if($fecha_nacimiento_objeto && $fecha_nacimiento_objeto->format('Y-m-d') === $fecha_nacimiento){
                    $fecha_nacimiento_mysql = $fecha_nacimiento;
                }

                if (empty($fecha_nacimiento_mysql)) {
                    $errores[] = "La fecha es obligatoria";
                }

                
                if (VALIDATE_EDAD) {
                    $fecha_nacimiento = new DateTime($fecha_nacimiento_mysql);
                    $hoy = new DateTime();
                    $intervalo = $hoy->diff($fecha_nacimiento);
                    $edad = $intervalo->y;
                    if ($entity_type === 'estudiante') {
                        $edad_min = 10;
                        $edad_max = 22;
                    }elseif($entity_type === 'profesor'){
                        $edad_min = 20;
                        $edad_max = 70;
                    }elseif($entity_type === 'asistent'){
                        $edad_min = 18;
                        $edad_max = 70;
                    }
                    if ($edad < $edad_min) {
                        $errores[] = "El rango mínimo de edad comprendida para un ".$entity_type." es de ".$edad_min." años";
                    }

                    if ($edad > $edad_max) {
                        $errores[] = "El rango máximo de edad comprendido para un ".$entity_type." es de ".$edad_max." años";
                    }
                }

                if (empty($estado_civil = trim($_POST['estado_civil'] ?? ''))) {
                    $errores[] = "El estado civil es obligatorio";
                    }

            }elseif($seccion === 'academico'){
                if ($entity_type === 'estudiante') {
                    $ano_grado = trim($_POST['ano_grado'] ?? '');
                    $periodo_escolar = trim($_POST['periodo_escolar'] ?? '');
                    
                    if (empty($ano_grado)) {
                        $errores[] = "El año grado es obligatorio";
                    }
                    if (empty($periodo_escolar)) {
                        $errores[] = "El periodo escolar es obligatorio";
                    }
                    if(empty($estatus = trim($_POST['estatus'] ?? ''))){
                        $errores[] = "El estatus es obligatorio";
                    }
                }elseif($entity_type === 'profesor') {
                    if(empty($estatus = trim($_POST['estatus'] ?? ''))){
                        $errores[] = "El estatus es obligatorio";
                    }
                }
                
            }elseif($seccion === 'ubicacion') {
                $estado_id_post = trim($_POST['estado_id'] ?? '');
                $municipio_texto_post = trim($_POST['municipio_texto'] ?? '');
                $municipio_id_post = trim($_POST['municipio_id'] ?? '');
                $comunidad_texto_post = trim($_POST['comunidad_texto'] ?? '');
                $comunidad_id_post = trim($_POST['comunidad_id'] ?? '');
                $municipio_texto = null;
                $municipio_id = null;
                $comunidad_texto = null;
                $comunidad_id = null;

                if (empty($estado_id_post)) {
                    $errores[] = "El estado es obligatorio";
                }else{
                    $estado_id = decrypt_id($estado_id_post);
                }

                if (!empty($municipio_id_post)) {
                    $municipio_id = decrypt_id($municipio_id_post);
                }elseif(!empty($municipio_texto_post)){
                    $municipio_texto = $municipio_texto_post;
                }else{
                    $errores[] = "El municipio es obligatorio";
                }

                if (!empty($comunidad_id_post)) {
                    $comunidad_id = decrypt_id($comunidad_id_post);
                }elseif(!empty($comunidad_texto_post)){
                    $comunidad_texto = $comunidad_texto_post;
                }else{
                    $errores[] = "La comunidad es obligatoria";
                }
            }
                if (!empty($errores)) {
                    $_SESSION['error'] = $errores;
                    $this->redirect('admin/obtener_'.$entity_type.'/'.$id);
                }
                if(empty($errores)){
                    
                    if (!empty($id)) {
                        $id_decrypt = decrypt_id($id);
                    }
                    $resultado = false;
                    if($seccion === 'persona'){
                        $datosCompletos = [
                                            'nombre1'=>$nombre1,
                                            'nombre2'=>$nombre2,
                                            'apellido1'=>$apellido1,
                                            'apellido2'=>$apellido2,
                                            'sexo'=>$sexo,
                                            'fecha_nacimiento'=>$fecha_nacimiento_mysql,
                                            'estado_civil'=>$estado_civil];

                        if ($entity_type === 'estudiante') {
                            $estudianteModelo = new EstudianteModelo($this->db);
                            $resultado = $estudianteModelo->update_personaEstudiante($datosCompletos, $id_decrypt);
                        }elseif($entity_type === 'profesor'){
                            $profesorModelo = new ProfesorModelo($this->db);
                            $resultado = $profesorModelo->update_profesorPersona($datosCompletos, $id_decrypt);
                        }elseif($entity_type === 'asistent'){
                            $asistenteModelo = new AsistenteModelo($this->db);
                            $resultado = $asistenteModelo->update_personaAsistente($datosCompletos, $id_decrypt);
                        }
                    }elseif ($seccion === 'academico') {
                        
                        if ($entity_type === 'estudiante') {
                            $estudianteModelo = new EstudianteModelo($this->db);
                            $datosCompletos = [
                                        'ano_grado'=>$ano_grado,
                                        'periodo_escolar' =>decrypt_id($periodo_escolar),
                                        'estatus'=>$estatus];
                            $resultado = $estudianteModelo->update_academicoEstudiante($datosCompletos, $id_decrypt);
                        }elseif($entity_type === 'profesor'){
                            $profesorModelo = new ProfesorModelo($this->db);
                            $datosCompletos = ['estatus'=>$estatus];
                            $resultado = $profesorModelo->update_profesorAcademico($datosCompletos, $id_decrypt);
                        }
                        
                    }elseif ($seccion === 'ubicacion') {
                        $datosCompletos = [
                                    'estado_id' => $estado_id,
                                    'municipio_id' => $municipio_id,
                                    'comunidad_id' => $comunidad_id,
                                    'municipio_texto' => $municipio_texto,
                                    'comunidad_texto' => $comunidad_texto];
                        
                        if ($entity_type === 'estudiante') {
                            $estudianteModelo = new EstudianteModelo($this->db);
                            $resultado = $estudianteModelo->update_estudianteUbicacion($datosCompletos, $id_decrypt);
                        }elseif ($entity_type === 'profesor') {
                            $profesorModelo = new ProfesorModelo($this->db);
                            $resultado = $profesorModelo->update_profesorUbicacion($datosCompletos, $id_decrypt);
                        }elseif($entity_type === 'asistent'){
                            $asistenteModelo = new AsistenteModelo($this->db);
                            $resultado = $asistenteModelo->update_ubicacionAsistente($datosCompletos, $id_decrypt);
                        }
                    }
                    if ($resultado) {
                        $success[] = "Actualizacion completada";
                        $_SESSION['seccess'] = $success;
                        $this->redirect('admin/obtener_'.$entity_type.'/'.$id);
                    }else{
                        $errores[] = "Ocurrió un error inesperado";
                        $_SESSION['error'] = $errores;
                        $this->redirect('admin/list_'.$entity_type.'/error');
                    } 
                }
        }else{
            $this->redirect('admin/obtener_'.$entity_type.'/'.$id.'/error');
        }
    }

    public function update_simple($entity_type, $id_entity_type){

        $this->disableBrowserCache();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_simple'])) {
                $resultado = '';
                if(!empty($id_entity_type)){
                        $id_entity_decrypt = decrypt_id($id_entity_type);
                }else{
                    $this->redirect('admin/list_'.$entity_type.'?message=no_data');
                }
                switch ($entity_type) {
                    case 'materia':
                    if(empty($nombre_materia = trim($_POST['nombre_materia'] ?? ''))){
                        $errores[] = "El nombre de la materia es obligatorio";
                    }
                    if(empty($description_materia = trim($_POST['descripcion_materias'] ?? ''))){
                    $errores[] = "La descripción es obligatorio";
                    }
                    break;
                    case 'comunidad':
                    if (empty($nombre_comunidad = trim($_POST['nombre_comunidad']))) {
                        $errores[] = "El nombre de la comunidad es obligatorio";
                    }
                    break;
                    default:
                    $this->redirect('admin/list_'.$entity_type.'?message=undefined');
                    break;
                }

                if (!empty($errores)) {
                    $_SESSION['error'] = $errores;
                    $this->redirect('admin/list_'.$entity_type);
                }else{

                    $_SESSION['time_tmp'] = 'desactivo';
                    echo $_SESSION['time_tmp'];
                    if ($entity_type === 'materia') {
                        $datosCompletos = [
                                'nombre_materia'=>$nombre_materia,
                                'descripcion_materia'=>$description_materia
                                ];
                        $materiasModelo = new MateriasModelo($this->db);
                        $resultado = $materiasModelo->update_materia($datosCompletos, $id_entity_decrypt);                                
                    }elseif($entity_type === 'comunidad'){
                        $datosCompletos = ['nombre_comunidad'=>$nombre_comunidad];
                        $comunidadModelo = new ComunidadModelo($this->db);
                        $resultado = $comunidadModelo->update_comunidad($datosCompletos, $id_entity_decrypt);
                    }
                    if ($resultado) {
                        $success[] = "Actualización completada";
                        $_SESSION['success'] = $success;                   
                        $this->redirect('admin/list_'.$entity_type.'?message=seccess');
                    }else{
                        $errores[] = "Ocurrió un error inesperado";
                        $_SESSION['error'] = $errores;
                        $this->redirect('admin/list_'.$entity_type.'?message=error');
                    }
                }            
            }else{
                $this->redirect('admin/list_'.$entity_type.'?message=no_data');
            }           
        }
    }

    public function confirmar(){
        $this->disableBrowserCache();
        $data = $_SESSION['confirm_data'] ?? [];

        if (empty($data)) {
            $this->redirect('admin/dashboard');
        }

        $this->renderView('confirmar', $data);
    }

    public function guardarAsistente(){
        $this->disableBrowserCache();                  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nuevosDatos = [];
            $errores = [];
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');

            if (empty($nombre_usuario)) {
                $errores[] = "El nombre de usuario es obligatorio";
            }
            if (empty($contrasena)) {
                $errores[] = "La contraseña es obligatoria";
            }else{
                $contrasena =  password_hash($contrasena, PASSWORD_BCRYPT);
            }

            if(!empty($errores)){
                $data = $_POST;
                $_SESSION['error'] = $errores;
                $this->renderView('confirmar',$data);
            }

            if(!isset($_SESSION['confirm_data'])) {
                $this->redirect('admin/confirmar');
            }

            if (empty($errores)) {
                $datos_persona = $_SESSION['confirm_data']['form_data'];
                $ubicacion_data = $_SESSION['confirm_data']['form_ubicacion'];
                $ubicacion_id_data = $_SESSION['confirm_data']['form_id_ubicacion'];
                $datos_ocupacion = $_SESSION['confirm_data']['ocupacionDB'];

                $datosCompletos = array_merge(
                                            $datos_persona,
                                            $ubicacion_data,
                                            $ubicacion_id_data,
                                            $datos_ocupacion,
                                            ['nombre_usuario'=>$nombre_usuario,
                                            'contrasena'=>$contrasena]);
                if($datosCompletos['ocupacion'] === 'asistent'){
                    $datosCompletos['ocupacion'] = 'asistente';
                }
                if ($datosCompletos['sexo'] == 'Masculino') {
                    $datosCompletos['sexo'] = 'M';
                }elseif($datosCompletos['sexo'] == 'Femenino'){
                    $datosCompletos['sexo'] = 'F';
                }
                if (!empty($datosCompletos['id_estado'])) {
                    $datosCompletos['id_estado'] = decrypt_id($datosCompletos['id_estado']);
                }if (!empty($datosCompletos['id_municipio'])) {
                    $datosCompletos['id_municipio'] = decrypt_id($datosCompletos['id_municipio']);
                }if (!empty($datosCompletos['id_comunidad'])) {
                    $datosCompletos['id_comunidad'] = decrypt_id($datosCompletos['id_comunidad']);
                }

                $datosCompletosDB = [
                                    'nombre1' =>$datosCompletos['Primer nombre'],
                                    'nombre2' =>$datosCompletos['Segundo nombre'],
                                    'apellido1' =>$datosCompletos['Primer apellido'],
                                    'apellido2' =>$datosCompletos['Segundo apellido'],
                                    'sexo' =>$datosCompletos['sexo'],
                                    'cedula' =>$datosCompletos['cedula'],
                                    'fecha_na' =>$datosCompletos['fecha de nacimiento'],
                                    'estadCivil' =>$datosCompletos['estado civil'],
                                    'estado' =>$datosCompletos['id_estado'],
                                    'id_municipio' =>$datosCompletos['id_municipio'],
                                    'id_comunidad' =>$datosCompletos['id_comunidad'],
                                    'municipio_texto' =>$datosCompletos['municipio'],
                                    'comunidad_texto' =>$datosCompletos['Ciudad'],
                                    'nombre_usuario' =>$datosCompletos['nombre_usuario'],
                                    'contrasena' =>$datosCompletos['contrasena'],
                                    'ocupacion' =>$datosCompletos['ocupacion']
                                    ];

                $asistenteModelo = new AsistenteModelo($this->db);

                if ($asistenteModelo->insertarAsistenteDB($datosCompletosDB)) {
                    unset($_SESSION['confirm_data']);
                    $success[] = "Actualización completada";
                    $_SESSION['success'] = $success;
                    $this->redirect('admin/add_asistent?success=true');
                }else{
                    $errores[] = "Ocurrió un error inesperado al guardar asistente";
                    $_SESSION['error'] = $errores;
                    $this->redirect('admin/add_asistent');
                }
            }
        }else{
            $this->redirect('admin/confirmar');
        }
    }

    public function guardarEstudiante(){

        $this->disableBrowserCache();                  
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nuevosDatos = [];
            $errores = [];

            $periodo_escolar = trim($_POST['periodo_escolar'] ?? '');
            $ano_grado = trim($_POST['ano_grado'] ?? '');
            $fecha_inscripcion = trim($_POST['fecha_inscripcion'] ?? '');
            $estatus = trim($_POST['estatus'] ?? '');

            if (empty($periodo_escolar)) {
                $errores[] = "El periodo escolar es obligatorio";
            }

            $fecha_inscripcion_mysql = null;
            $fecha_objeto = DateTime::createFromFormat('Y-m-d',$fecha_inscripcion);
            if ($fecha_objeto && $fecha_objeto->format('Y-m-d') === $fecha_inscripcion) {
                $fecha_inscripcion_mysql = $fecha_inscripcion;
            }

            if(empty($fecha_inscripcion_mysql)){
                $errores[] = "La fecha de inscripción no es válido";
            }

            if (empty($ano_grado)) {
                $errores[] = "El año/grado es obligatorio";
            }

            if(empty($estatus)) {
                $errores[] = "El estatus no es válido";
            }

            if(!empty($errores)) {
                $_SESSION['error'] = $errores;
                $this->redirect('admin/add_estudiante');
            }

            if(!isset($_SESSION['confirm_data'])) {
                $this->redirect('admin/add_estudiante?error=no_session_data');
            }

            $datos_persona = $_SESSION['confirm_data']['form_data'];
            $datos_ubicacion = $_SESSION['confirm_data']['form_ubicacion'];
            $datos_id_ubicacion = $_SESSION['confirm_data']['form_id_ubicacion'];
            $datos_ocupacion = $_SESSION['confirm_data']['ocupacionDB'];

            $datosCompletos = array_merge(
                $datos_persona,
                $datos_ubicacion,
                $datos_id_ubicacion,
                $datos_ocupacion,
                ['periodo escolar'=>$periodo_escolar,
                'año/grado'=>$ano_grado,
                'fecha inscripcion'=>$fecha_inscripcion_mysql,
                'estatus'=>$estatus]);
            if($datosCompletos['sexo'] == 'Masculino'){
                $datosCompletos['sexo'] = 'M';
            }elseif($datosCompletos['sexo'] == 'Femenino'){
                $datosCompletos['sexo'] = 'F';
            }

            if (!empty($datosCompletos['id_estado'])) {
                $datosCompletos['id_estado'] = decrypt_id($datosCompletos['id_estado']);
            }
            if (!empty($datosCompletos['id_municipio'])) {
                $datosCompletos['id_municipio'] = decrypt_id($datosCompletos['id_municipio']);
            }
            if (!empty($datosCompletos['id_comunidad'])) {
                $datosCompletos['id_comunidad'] = decrypt_id($datosCompletos['id_comunidad']);
            }

            $datosCompletosDB = [
                                'nombre1'=>$datosCompletos['Primer nombre'],
                                'nombre2'=>$datosCompletos['Segundo nombre'],
                                'apellido1'=>$datosCompletos['Primer apellido'],
                                'apellido2'=>$datosCompletos['Segundo apellido'],
                                'sexo'=>$datosCompletos['sexo'],
                                'cedula'=>$datosCompletos['cedula'],
                                'fecha_na'=>$datosCompletos['fecha de nacimiento'],
                                'estadCivil'=>$datosCompletos['estado civil'],
                                'id_estado'=>$datosCompletos['id_estado'],
                                'id_municipio'=>$datosCompletos['id_municipio'],
                                'id_comunidad'=>$datosCompletos['id_comunidad'],
                                'municipio_texto'=>$datosCompletos['municipio'],
                                'comunidad_texto'=>$datosCompletos['Ciudad'],
                                'periodo_escolar'=>$datosCompletos['periodo escolar'],
                                'ano_grado'=>$datosCompletos['año/grado'],
                                'fecha_inscripcion'=>$datosCompletos['fecha inscripcion'],
                                'estatus'=>$datosCompletos['estatus']
                            ];

            $estudianteModelo = new EstudianteModelo($this->db);

            if ($estudianteModelo->insertarEstudianteDB($datosCompletosDB)) {
                unset($_SESSION['confirm_data']);
                $success[] = "Los datos han sido guardados";
                $_SESSION['success'] = $success;
                $this->redirect('admin/add_estudiante?success=true');
            }else{
                $errores[] = "Ocurrió un error inesperado al guardar estudiante";
                $_SESSION['error'] = $errores;
                $this->redirect('admin/add_estudiante');
            }

        }else{
            $this->redirect('admin/confirmar');
        }
    }

    public function guardarProfesor(){ 

        $this->disableBrowserCache();                    
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nuevosDatos = [];
            $errores = [];

            $fecha_mysql = null;
            $fecha_objeto = DateTime::createFromFormat('Y-m-d',$_POST['fecha_contratacion']);
            if ($fecha_objeto && $fecha_objeto->format('Y-m-d') === $_POST['fecha_contratacion']) {
                $fecha_mysql = $_POST['fecha_contratacion'];
            }
            if(empty($fecha_mysql)){
                $errores[] = "La fecha de contratación es obligatorio";
            }

            if(empty($_POST['selectEstatusProfesor'])){
                $errores[] = "El estatus es obligatorio";
            }

            if (empty($_POST['nombre_usuario'])) {
                $errores[] = "El nombre de usuario es obligatorio";
            }

            if (empty($_POST['contrasena']) || strlen($_POST['contrasena']) < 8) {
                $errores[] = "La contraseña debe tener al menos 8 caracteres";
            }

            if (!empty($errores)) {
                $_SESSION['error'] = $errores;
                $this->redirect('admin/confirmar');
            }

            if (!isset($_SESSION['confirm_data'])) {
                $this->redirect('admin/add_profesor?error=no_session_data');
            }

            $datos_persona = $_SESSION['confirm_data']['form_data'];
            $datos_ubicacion = $_SESSION['confirm_data']['form_ubicacion'];
            $datos_id_ubicacion = $_SESSION['confirm_data']['form_id_ubicacion'];
            $datos_ocupacion = $_SESSION['confirm_data']['ocupacionDB'];

            $datosCompletos = array_merge($datos_persona, $datos_ubicacion, $datos_id_ubicacion, $datos_ocupacion, ['fecha_contratacion'=> $fecha_mysql,
                    'estatus'=> $_POST['selectEstatusProfesor'],
                    'nombre_usuario'=> $_POST['nombre_usuario'],
                    'contrasena'=> password_hash($_POST['contrasena'], PASSWORD_DEFAULT)]);

            if($datosCompletos['sexo'] == 'Masculino'){
                $datosCompletos['sexo'] = 'M';
            }elseif($datosCompletos['sexo'] == 'Femenino'){
                $datosCompletos['sexo'] = 'F';
            }
            if(!empty($datosCompletos['id_estado'])){
                $datosCompletos['id_estado'] = decrypt_id($datosCompletos['id_estado']);
            }
            if(!empty($datosCompletos['id_municipio'])){
                $datosCompletos['id_municipio'] = decrypt_id($datosCompletos['id_municipio']);
            }
            if(!empty($datosCompletos['id_comunidad'])){
                $datosCompletos['id_comunidad'] = decrypt_id($datosCompletos['id_comunidad']);
            }
            $datosCompletosDB = [
                            'nombre1' => $datosCompletos['Primer nombre'],
                            'nombre2' => $datosCompletos['Segundo nombre'],
                            'apellido1' => $datosCompletos['Primer apellido'],
                            'apellido2' => $datosCompletos['Segundo apellido'],
                            'sexo' => $datosCompletos['sexo'],
                            'fecha_na' => $datosCompletos['fecha de nacimiento'],
                            'cedula' => $datosCompletos['cedula'],
                            'estadCivil' => $datosCompletos['estado civil'],
                            'id_estado' => $datosCompletos['id_estado'],
                            'id_municipio' => $datosCompletos['id_municipio'],
                            'id_comunidad' => $datosCompletos['id_comunidad'],
                            'municipio_texto' => $datosCompletos['municipio'],
                            'comunidad_texto' => $datosCompletos['Ciudad'],
                            'fecha_contratacion' => $datosCompletos['fecha_contratacion'],
                            'estatus' => $datosCompletos['estatus'],
                            'nombre_usuario' => $datosCompletos['nombre_usuario'],
                            'contrasena' => $datosCompletos['contrasena'],
                            'ocupacion' => $datosCompletos['ocupacion']
                            ];
            $profesorModelo = new ProfesorModelo($this->db);

            if ($profesorModelo->insertarProfesorDB($datosCompletosDB)) {
                unset($_SESSION['confirm_data']);
                $success[] = "Los datos han sido guardados";
                $_SESSION['success'] = $success;
                $this->redirect('admin/add_profesor?success=true');
            }else{
                $errores[] = "Ocurrió un error inesperado al guardar al profesor";
                $_SESSION['error'] = $errores;
                $this->redirect('admin/add_profesor?error=db_error');
            }


        }else{
            $this->redirect('admin/confirmar');        
        }
    }

    public function guardarAllocationProfesorMaterias(){

        $this->disableBrowserCache();                  
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if(!isset($_SESSION['confirm_data'])){
                $this->redirect('admin/allocation_profesorMateria?error=no_session_data');
            }

            $data_id = $_SESSION['confirm_data']['id_form'];

            $id_profesor = (int)$data_id['id_profesor'];
            $materias_id = (array)$data_id['id_materias'];

            $profesorMateriasModelo = new ProfesorModelo($this->db);
            
            if ($profesorMateriasModelo->insertar_allocationProfesorMaterias($id_profesor, $materias_id)) {
                unset($_SESSION['confirm_data']);
                $success[] = "Los datos han sido guardados";
                $_SESSION['success'] = $success;    
                $this->redirect('admin/allocation_profesorMateria?success=true');
            }else{
                $errores[] = "Ocurrió un error inesperado al guardar la asignación profesor-materias";
                $_SESSION['error'] = $errores;
                $this->redirect('admin/allocation_profesorMateria?error=db_error');
            }
        }
    }

    public function guardarPeriodoEscolar(){

        $this->disableBrowserCache();  

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['confirm_data'])) {
                $this->redirect('admin/add_periodoEscolar?error=no_session_data');
            }

            $datosCompletos = $_SESSION['confirm_data']['form_data'];

            $datosCompletosDB = [
                            'periodo1'=>$datosCompletos['inicia'],
                            'periodo2'=>$datosCompletos['culmina']
                                ];

            $periodo_escolarModelo = new PeriodoEscolarModelo($this->db);

            if ($periodo_escolarModelo->insertarPeriodoEscolarDB($datosCompletosDB)) {
                unset($_SESSION['confirm_data']);
                $success[] = "Los datos han sido guardados";
                $_SESSION['success'] = $success;
                $this->redirect('admin/add_periodoEscolar?success=true');
            }else{
                $errores[] = "Ocurrió un error inesperado al insertar el periodo escolar";
                $_SESSION['error'] = $errores;
                $this->redirect('admin/add_periodoEscolar?error=db_error');
            }

        }
    }

    public function guardarMateria(){

        $this->disableBrowserCache();                  
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            if (!isset($_SESSION['confirm_data'])) {
                $this->redirect('admin/add_materias?error=no_session_data');
            }

            $datosCompletos = $_SESSION['confirm_data']['form_data'];

            $datosCompletosDB = [
                                'nombre_materia'=>$datosCompletos['nombre materia'],
                                'description_materia'=>$datosCompletos['descripción'],
                                'ano_grado'=>$datosCompletos['año/grado']
                                ];
            $materiaModelo = new MateriasModelo($this->db);

            if ($materiaModelo->insertarMateriaDB($datosCompletosDB)) {
                unset($_SESSION['confirm_data']);
                $success[] = "Los datos han sido guardados";
                $$_SESSION['success'] = $success;
                $this->redirect('admin/add_materias?success=true');
            }else{
                $errores[] = "Ocurrió un error inesperado al guardar materias";
                $_SESSION['error'] = $errores;
                $this->redirect('admin/add_materias?error=db_error');
            }
        }else{
            $this->redirect('admin/confirmar');
        }
    }

    public function guardarComunidad(){

        $this->disableBrowserCache();                  
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['confirm_data'])) {
                $this->redirect('admin/confirmar');
            }

            $datosCompletos = $_SESSION['confirm_data']['form_data'];
            $datos_id_municipio = $_SESSION['confirm_data']['id_municipio'];

            $datosCompletosDB = [
                                'nombre_comunidad'=> $datosCompletos['nombre comunidad'],
                                'id_municipio'=>$datos_id_municipio['id_municipio']
                                ];

            $comunidadModelo = new ComunidadModelo($this->db);

            if ($comunidadModelo->insertarComunidadDB($datosCompletosDB)) {
                unset($_SESSION['confirm_data']);
                $success = "Los datos han sido guardados";
                $_SESSION['success'] = $success;
                $this->redirect('admin/add_comunidad?success=true');
            }else{
                $errores[] = "Ocurrió un error inesperado al guardar la comunidad";
                $_SESSION['error'] = $errores;
                $this->redirect('admin/add_comunidad?error=db_error');
            }

        }else{
            $this->redirect('admin/confirmar');
        }
    }

    public function delete($entity_type, $id_entity){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($id_entity)) {
                $id_entity_decrypt = decrypt_id($id_entity);
            }else{
                $this->redirect('admin/list_'.$entity_type.'?message=empty');
            }
            if ($entity_type === 'estudiante') {
                $estudianteModelo = new EstudianteModelo($this->db);
                $info_entity = $estudianteModelo->obtener_personaEstudiante($id_entity_decrypt);
            }elseif($entity_type === 'profesor'){
                $profesorModelo = new ProfesorModelo($this->db);
                $info_entity = $profesorModelo->obtener_personaProfesor($id_entity_decrypt);
            }elseif($entity_type === 'periodo_escolar'){
                $periodoEscolarModelo = new PeriodoEscolarModelo($this->db);
                $info_entity = $periodoEscolarModelo->obtener_periodoEscolar_uno($id_entity_decrypt);
            }elseif($entity_type === 'asistent'){
                $asistenteModelo = new AsistenteModelo($this->db);
                $info_entity = $asistenteModelo->obtener_personaAsistente($id_entity_decrypt);
            }elseif($entity_type === 'materia'){
                $materiasModelo = new MateriasModelo($this->db);
                $info_entity = $materiasModelo->obtener_materia($id_entity_decrypt);
            }elseif($entity_type === 'comunidad'){
                $comunidadModelo = new ComunidadModelo($this->db);
                $info_entity = $comunidadModelo->obtener_comunidad($id_entity_decrypt);
            }

            if (!empty($info_entity)){
                $datos = [
                        'id_entity'=>$id_entity,
                        'form_data'=>$info_entity,
                        'headTitle'=>'Datos '.$entity_type,
                        'title'=> 'Revisar datos para eliminar',
                        'message'=> 'Por favor, revise los datos antes de eliminarlos',
                        'ocupacion'=>$entity_type,
                        'next_action'=>'deleteExecute'
                        ];
            }else{
                $this->redirect('admin/list_'.$entity_type.'?message=no_data');
            }

            $this->renderView('confirmar_delete',$datos);
        }
    }

    public function deleteExecute($entity_type, $id_entity){

        $this->disableBrowserCache();                  
        
        if (!empty($id_entity)) {
            $id_entity_decrypt = decrypt_id($id_entity);
        }else{
            $this->redirect('admin/list_'.$entity_type.'?message=error');
        }
        if($entity_type === 'estudiante') {
            $estudianteModelo = new EstudianteModelo($this->db);
            $resultado = $estudianteModelo->delete_estudiante($id_entity_decrypt);
        }elseif($entity_type === 'profesor'){
            $profesorModelo = new ProfesorModelo($this->db);
            $resultado = $profesorModelo->delete_profesor($id_entity_decrypt);
        }elseif($entity_type === 'periodo_escolar'){
            $periodoEscolarModelo = new PeriodoEscolarModelo($this->db);
            $resultado = $periodoEscolarModelo->delete_periodoEscolar($id_entity_decrypt);
        }elseif($entity_type === 'asistent'){
            $asistenteModelo = new AsistenteModelo($this->db);
            $resultado = $asistenteModelo->delete_asistente($id_entity_decrypt);
        }elseif($entity_type === 'materia'){
            $materiasModelo = new MateriasModelo($this->db);
            $resultado = $materiasModelo->delete_materia($id_entity_decrypt);
        }elseif($entity_type === 'comunidad'){
            $comunidadModelo = new ComunidadModelo($this->db);
            $resultado = $comunidadModelo->delete_comunidad($id_entity_decrypt);
        }
        if ($resultado) {
            $success[] = "Eliminación completada";
            $_SESSION['success'] = $success;
            $this->redirect('admin/list_'.$entity_type.'?message=seccess');
        }else{
            $errores[] = "Ocurrió un error inesperado";
            $_SESSION['error'] = $errores;
            $this->redirect('admin/list_'.$entity_type.'?message=error_execute');
        }
    }

    public function cancelar(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('admin/dashboard');
        }
    }

    public function cancelar_update(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('admin/dashboard');
        }
    }
  
}