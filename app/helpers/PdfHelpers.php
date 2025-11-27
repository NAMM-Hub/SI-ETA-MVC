<?php
class BoletaPDF extends FPDF{
	function Rotate($angle, $x=-1, $y=-1) {
        // Debes copiar la implementación completa de Rotate de FPDF, no solo RotatedText
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;

        if ($angle != 0) {
            $angle *= M_PI/180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x*$this->k;
            $cy = ($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function RotatedText($x, $y, $txt, $angle) {
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

    function Header(){
    	$this->SetXY(12, 23);
		$this->SetFont('Arial','',8);
		$headerText = "REPUBLICA BOLIVARIANA DE VENEZUELA\nMINISTERIO DEL PODER POPULAR PARA LA EDUCACION\nESCUELA TECNICA AGROPECUARIA CACHAMA\nCACHAMA ESTADO - ANZOATEGUI";		
		$this->MultiCell(100, 5, utf8_decode($headerText), 0, 'L');
		$ruta_log = ROOT_PATH . 'public/css/image/MPP.jpg';

		$this->Image($ruta_log, 91, 25, 27, 26);

		$this->SetFont('Arial', '',8);
		$this->Ln(10);
		$this->SetX(12);
		$this->Cell(25, 6, utf8_decode("PROFESOR:"), 0, 0, 'L');
		$this->Cell(60, 6, "____________________", 0, 1, 'L');
		$this->SetX(12);
		$this->Cell(25, 6, utf8_decode("MATERIA:"), 0, 0, 'L');
		$this->Cell(60, 6, "____________________", 0, 1, 'L');
		$this->SetX(12);
		$this->Cell(25, 6, utf8_decode("CURSO:"), 0, 0, 'L');
		$this->Cell(60, 6, "__________", 0, 1, 'L');
		$this->SetX(12);
		$this->Cell(25, 6, utf8_decode("AÑO ESCOLAR:"), 0, 0,'L');
		$this->Cell(60, 6, "__________", 0, 1, 'L');

		$this->Ln(7);

		$w_nro = 8;
        $w_ci = 25;
        $w_total_nombres = 90; // Ancho combinado de Nombres/Apellidos
        $w_sexo = 7;

		$x_start = 126;
		$y_start = 30;
		$pdf->SetXY($x_start,$y_start);

		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(65, 4, utf8_decode('ACTUACIÓN DEL ESTUDIANTE'),1,1,'C');

		$pdf->SetX($x_start);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(30, 4, utf8_decode('RENDIMIENTO'),1,0,'C');
		$pdf->Cell(35, 4, utf8_decode('NOTAS'),1,0,'C');

		$pdf->SetXY(131,38);
		$pdf->SetFont('Arial','B',8);
		$verticalTextEmpty ="\n \n \n \n\n\n\n\n\n\n\n\n\n ";
		$verticalTextProm = "\n \n \n \n\n\nP\nr\no\nm\ne\nd\ni\no";
		$verticalTextRasgos = "\n \n \n \n\n\n\n\nR\na\ns\ng\no\ns";
 		$verticalTextPAjustado ="\n \n P\n \n\n\nA\nj\nu\ns\nt\na\nd\no";
 		$verticalText70 ="\n \n \n \n\n\n\n7\n0\n%\n\n\n\n ";
		$verticalTextProFinal = "\nP\nr\nu\ne\nb\na\n \n \nF\ni\nn\na\nl";
 		$verticalText30 ="\n \n \n \n\n\n\n3\n0\n%\n\n\n\n ";
		$verticalTextDefinitiva = "\n \n \n \nD\ne\nf\ni\nn\ni\nt\ni\nv\na";

		$pdf->MultiCell(5, 4, utf8_decode($verticalTextEmpty),1,'C');
		$pdf->SetXY(131,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextEmpty),1,'C');
		$pdf->SetXY(136,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextEmpty),1,'C');
		$pdf->SetXY(141,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextEmpty),1,'C');
		$pdf->SetXY(146,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextEmpty),1,'C');
		$pdf->SetXY(151,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextProm),1,'C');
		$pdf->SetXY(156,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextRasgos),1,'C');
		$pdf->SetXY(161,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextPAjustado),1,'C');
		$pdf->SetXY(166,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalText70),1,'C');
		$pdf->SetXY(171,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextProFinal),1,'C');
		$pdf->SetXY(176,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalText30),1,'C');
		$pdf->SetXY(181,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextDefinitiva),1,'C');
		$pdf->SetXY(186,38);
		$pdf->MultiCell(5, 4, utf8_decode($verticalTextDefinitiva),1,'C');

		$pdf->SetX(131);
		for ($i = 0; $i < 12; $i++) {
		    $pdf->Cell(5, 5, '', 1, 0, 'C'); // Celda 1 (5mm)
		}

		// Determinar encabezados a partir de las claves del primer registro

		$firstRow = reset($data);

		//---- 3. Datos ----
		$x_datos = 13;
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetX($x_datos);
		$cellWidth = [5, 30, 78, 39];
		$nro = 1;
		$pdf->Cell($cellWidth[0], 5, utf8_decode('N°'),1,0,'C',0);
		$pdf->Cell($cellWidth[1], 5, utf8_decode('C.I'),1,0,'C',0);
		$pdf->Cell($cellWidth[2], 5, utf8_decode('Apellidos y Nombres'),1,0,'C',0);
		$pdf->Cell($cellWidth[0], 5, utf8_decode('S'),1,0,'C',0);
		$pdf->Ln();

        
    }
    
    public function generarDescargarLista(array $data, string $title, string $filename = 'reporte.pdf'){
		if (empty($data)) {
			$errores = "Datos vacíos";
			return $errores;
		}
		$pdf = new FPDF();

		$pdf->AddPage();
		foreach($data as $row){
			$rowArray = (array) $row;
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetX($x_datos);
			$pdf->Cell($cellWidth[0], 4, utf8_decode($nro), 1, 0, 'C');
			$pdf->Cell($cellWidth[1], 4, utf8_decode($rowArray['C_I']), 1, 0, 'C');
			$pdf->Cell($cellWidth[3], 4, utf8_decode($rowArray['Nombres']), 1, 0, 'C');
			$pdf->Cell($cellWidth[3], 4, utf8_decode($rowArray['Apellidos']), 1, 0, 'C');
			$pdf->Cell($cellWidth[0], 4, utf8_decode($rowArray['Sexo']), 1, 0, 'C');
			for ($i = 0; $i < 12; $i++) {
		    	$pdf->Cell($cellWidth[0], 4, '', 1, 0, 'C'); // Celda 1 (5mm)
		}

			$pdf->Ln();
			$nro++;		
		}

		$pdf->Output();
	}
}

class PdfHelpers{
		public static function generarDescargarListaPersonal(array $data, string $title, string $filename = 'reporte.pdf'){
		if (empty($data)) {
			$errores = "Datos vacíos";
			return $errores;
		}

		$pdf = new FPDF();
		$pdf->AddPage();

		//---- 1. Titulo y configuracion basica----

		$pdf->SetFont('Arial', 'B', 16);
		$pdf->Cell(0, 10, utf8_decode($title), 0, 1, 'C');
		$pdf->Ln(5);

		// Determinar encabezados a partir de las claves del primer registro

		$firstRow = reset($data);

		$headers = array_keys((array)$firstRow);

		array_unshift($headers, 'Nro');

		//---- 2. encabezados (widths fijos)
		
		$totalWidth = 188;
		$nroWidth = 10;
		$numCols = count($headers) - 1;
		$remainWidth = $totalWidth - $nroWidth;
		$colWidth = floor($remainWidth / $numCols); //Anchos para pagias A4
		$pdf->SetFont('Arial', 'B', 10);

		foreach($headers as $header){

			$currentWidth = ($header === 'Nro') ? $nroWidth : $colWidth; 
			$pdf->Cell($currentWidth, 7, utf8_encode(ucfirst(str_replace('_', ' ',$header))), 1, 0, 'C');
		}
		$pdf->Ln();

		//---- 3. Datos ----
		$pdf->SetFont('Arial', '', 10);
		$nro = 1;
		foreach($data as $row){
			$rowArray = (array) $row;
			$pdf->Cell($nroWidth,7, $nro, 1, 0, 'C');

			foreach($rowArray as $col){
				$pdf->Cell($colWidth, 7, utf8_decode(ucfirst($col)), 1, 0, 'C');
			}	

			$pdf->Ln();
			$nro++;		
		}

		$pdf->Output('D', $filename);
	}
}

?>