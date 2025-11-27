<?php
class SelectModelo{
	public function obtener_estados_civil(): array{
		return [['valor'=>'casado', 'texto'=>'Casado'],
				['valor'=>'soltero', 'texto'=>'Soltero'],
				['valor'=>'divorciado', 'texto'=>'Divorciado'],
				['valor'=>'viudo', 'texto'=>'Viudo']
				];
	}

	public function obtener_generos(): array{
		return [['valor'=>'M', 'texto'=>'Masculino'],
				['valor'=>'F', 'texto'=>'Femenino']];
	}

	public function obtener_estatus_profesor(): array{
		return [['valor'=>'activo', 'texto'=>'Activo'],
				['valor'=>'de licencia', 'texto'=>'De licencia'],
				['valor'=>'jubilado', 'texto'=>'Jubilado'],
				['valor'=>'contrato temporal', 'texto'=>'Contrato temporal'],
				['valor'=>'inactivo', 'texto'=>'Inactivo'],
				['valor'=>'despedido', 'texto'=>'Despedido']
				];
	}
}