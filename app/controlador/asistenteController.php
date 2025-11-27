<?php
session_start();
if (!isset($_SESSION['nombre_usuario']) and !isset($_SESSION['rol_usuario'])) {
    $errores[] = "Ocurrió un error inesperado";
    $_SESSION['error'] = $errores;
    header('Location: '.BASE_URL.'login/index');
    exit();
}
if ($_SESSION['rol_usuario'] != 'asistente') {
    header('Location: '.BASE_URL.'login/index');
    exit();
}
class asistenteController {
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
        $viewPath = __DIR__ . '/../vista/asistente/' . $path . '.php';

        if(file_exists($viewPath)){
            require_once $viewPath;
        }else{
            echo $viewPath;
            echo "Error 404: Vista no encontrada.";
        }
    }

    public function dashboard() {
        $this->renderView('dashboard');
    }

    public function list_periodoEscolar(){
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

    public function list_estudiante(){
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

    public function list_profesor(){
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

    public function list_materias(){
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

    public function list_allocationProfesorMaterias($id){
        $id_decrypt = decrypt_id($id);
        $materiasModelo = new MateriasModelo($this->db);

        $list_allocationProfesorMateria = $materiasModelo->obtener_relacionProfesorMateria($id_decrypt);

            if (!is_array($list_allocationProfesorMateria)) {
                $list_allocationProfesorMateria = [];
            }

            $data = ['list_allocationProfesorMaterias'=>$list_allocationProfesorMateria];

        $this->renderView('list_allocationProfesorMaterias',$data);
    }

    public function download_listEstudiante(){
        $periodoEscolarModelo = new PeriodoEscolarModelo($this->db);
        $lista_periodoEscolar = $periodoEscolarModelo->obtener_periodoEscolar();

            if (!is_array($lista_periodoEscolar)) {
                $lista_periodoEscolar = [];
            }

        $data = ['lista_periodoEscolar'=>$lista_periodoEscolar];
        $this->renderView('download_listEstudiante',$data);
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
                    $this->redirect('admin/download_listEstudiante');
                }
                if (empty($errores)) {
                    $estudianteModelo = new EstudianteModelo($this->db);
                    $lista_estudiante = $estudianteModelo->obtener_estudiantes_reporte($id_periodo, $ano_grado);

                    if (!empty($lista_estudiante)) {
                        $titulo_reporte = "Reporte de Estudiantes - Grado ".$ano_grado;
                        $nombre_archivo = 'estudiante_' . date('Ymd').'.pdf';
                        $boletaModelo = new BoletaPDF();
                        $boletaModelo->generarDescargarLista($lista_estudiante, $titulo_reporte, $nombre_archivo);
                    }else {
                        $_SESSION['error'] = ['No se encontraron datos para generar el reporte con los filtros seleccionados.'];
                        $this->redirect('asistente/download_listEstudiante');
                    }
                }
            }
        }else{
            $this->redirect('asistente/download_listEstudiante');
        }
    }
    public function cancelar(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('asistente/dashboard');
        }
    }

    public function cancelar_update(){
        $this->disableBrowserCache();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            unset($_SESSION['confirm_data']);
            $this->redirect('asistente/dashboard');
        }
    }
}