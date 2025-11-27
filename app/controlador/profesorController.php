<?php
session_start();
if(!isset($_SESSION['nombre_usuario']) and !isset($_SESSION['rol_usuario'])){
    $errores[] = "Ocurrió un error inesperado";
    $_SESSION['error'] = $errores;
    header('Location: '.BASE_URL.'login/index');
    exit();
}
if ($_SESSION['rol_usuario'] != 'Profesor') {
    $errores[] = "Ocurrió un error inesperado";
    $_SESSION['error'] = $errores;
    header('Location: '.BASE_URL.'login/index');
    exit();
}
class profesorController {
    protected function redirect(string $path){
        $url = BASE_URL . $path;

        header("Location: {$url}");
        exit();
    }

    protected $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    private function renderView($path, $data = []){
        extract($data);
        $viewPath = __DIR__ . '/../vista/profesor/' . $path . '.php';

        if(file_exists($viewPath)){
            require_once $viewPath;
        }else{
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

    public function info_profesor(){
        if(!empty($_SESSION['id'])){
            $id = $_SESSION['id'];
            $id_decrypt = decrypt_id($id);
            $profesorModelo = new ProfesorModelo($this->db);
            $info_profesor = $profesorModelo->obtener_profesorDB($id_decrypt);
            if (!is_array($info_profesor)) {
                $this->redirect('profesor/dashboard');
            }
        }else{
            $this->redirect('profesor/dashboard');
        }

        $data = ['entity_type'=>'profesor',
                'info_profesor'=>$info_profesor,
                'id_profesor'=>$id];
        $this->renderView('info_profesor/info_profesor',$data);
    }

    public function allocation_materias(){
        if(!empty($_SESSION['id'])){
            $id = $_SESSION['id'];
            $id_decrypt = decrypt_id($id);
            $profesorModelo = new MateriasModelo($this->db);
            $info_allocation = $profesorModelo->obtener_relacionProfesor($id_decrypt);
            if (!is_array($info_allocation)) {
                $errores[] = "No se han encontrado datos relacionados";
                $_SESSION['error'] = $errores;
            }
            $data = ['info_allocations'=>$info_allocation];
            $this->renderView('allocations/allocations_materias',$data);
        }else{
            $this->redirect('profesor/dashboard');
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
                $this->redirect('profesor/info_profesor');
            }
            if (empty($errores)) {
                switch($entity_type){
                    case 'profesor':
                        if ($update_rule === 'persona') {
                            $seccion_destino = 'persona';
                        }elseif ($update_rule === 'ubicacion') {
                            $seccion_destino = 'ubicacion';
                        }elseif ($update_rule === 'fullDatos') {
                            $seccion_destino = 'full';
                        }                        
                    break;
                    default :
                        $this->redirect('profesor/info_profesor');
                    break;
                }
                $this->redirect('profesor/update/'.$entity_type.'/'.$seccion_destino.'/'.$id_entity_type);
            }
        }else{
            $this->redirect('profesor/info_profesor/error_not_found');
        }
    }

    public function update_simpleRule($entity_type, $id_entity_type){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!empty($id_entity_type)) {
                $id_entity_decrypt = decrypt_id($id_entity_type);
                $_SESSION['time'] = 'activo';
            }else{
                $this->redirect('profesor/info_'.$entity_type.'?message=no_data');
            }
            if($entity_type === 'materia'){
                $materiasModelo = new MateriasModelo($this->db);
                $info_entity = $materiasModelo->obtener_materia($id_entity_decrypt);
            }elseif($entity_type === 'comunidad'){
                $comunidadModelo = new ComunidadModelo($this->db);
                $info_entity = $comunidadModelo->obtener_comunidad($id_entity_decrypt);
            }

            if (!is_array($info_entity)) {
                $this->redirect('profesor/info_'.$entity_type.'?message=empty');
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
            $this->redirect('profesor/info_'.$entity_type);
        }
    }

    public function update($entity_type, $seccion, $id_entity_type){

            if(!empty($id_entity_type)){
                $id_entity_decrypt = decrypt_id($id_entity_type);
            }
            if ($entity_type === 'profesor') {
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
                        $this->redirect('profesor/dashboard');
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
                $this->redirect('profesor/info_'.$entity_type.'?error=no_update');
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
                              
                $selectModelo = new SelectModelo($this->db);
                    $data = array_merge($fullDatos,
                                        ['select_estadoCivil'=> $selectModelo->obtener_estados_civil()],
                                        ['select_generos'=> $selectModelo->obtener_generos()]);
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

    public function update_fullExecute($id){

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
                    $edad_min = 20;
                    $edad_max = 70;
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
                $this->redirect('profesor/info_profesor');
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

                if ($entity_type === 'profesor') {
                    $datosCompletos['estatus'] = $estatus;
                    $profesorModelo = new ProfesorModelo($this->db);
                    $resultado = $profesorModelo->update_profesor_todo($datosCompletos, $id_decrypt);
                }

                if($resultado){
                    $success[] = "Actualizacion completada";
                    $_SESSION['success'] = $sucess;
                    $this->redirect('profesor/info_'.$entity_type.'/');
                }else{
                    $this->redirect('profesor/info_'.$entity_type.'/');
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
                    if($entity_type === 'profesor'){
                        $edad_min = 20;
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
                    $this->redirect('profesor/info_'.$entity_type.'/');
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

                        if($entity_type === 'profesor'){
                            $profesorModelo = new ProfesorModelo($this->db);
                            $resultado = $profesorModelo->update_profesorPersona($datosCompletos, $id_decrypt);
                        }
                    }elseif ($seccion === 'academico') {
                        
                        if($entity_type === 'profesor'){
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
                        
                        if ($entity_type === 'profesor') {
                            $profesorModelo = new ProfesorModelo($this->db);
                            $resultado = $profesorModelo->update_profesorUbicacion($datosCompletos, $id_decrypt);
                        }
                    }
                    if ($resultado) {
                        $success[] = "Actualizacion completada";
                        $_SESSION['seccess'] = $success;
                        $this->redirect('profesor/info_'.$entity_type.'/success');
                    }else{
                        $this->redirect('profesor/info_'.$entity_type.'/error');
                    } 
                }
        }else{
            $this->redirect('profesor/info_'.$entity_type.'/'.$id.'/error');
        }
    }
    public function cancelar(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('profesor/dashboard');
        }
    }

    public function cancelar_update(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('profesor/dashboard');
        }
    }
  
}