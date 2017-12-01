@extends('layouts.ventas')

@section('content')
	<?php
		session_start();

		$_SESSION['e_sucursal'] 	= 2;
		$_SESSION['e_nombre'] 		= 'Rogelio';
		$_SESSION['e_apellido_p'] 	= 'Gámez';
		$_SESSION['e_apellido_m'] 	= 'Hernández';
		$_SESSION['e_permisos'] 	= 'P000';
		
	?>
	
	{!!Form::open(['route'=>'factura.prueba', 'method'=>'POST', 'id'=>'facturaVenta'])!!}
		<input type="hidden" name="movimiento" value="FV">
		<input type="hidden" name="series" value="S">
		<input type="hidden" name="pagos" value="V">
		<input type="hidden" name="folio" value="A">
		<input type="hidden" name="inventario" value="D">
		<input type="hidden" name="referencias" value="C,OF,P">
		<input type="hidden" name="persona" value="C">
		<input type="hidden" name="cargo" value="V">

		<a href="javascript:{}" onclick="document.getElementById('facturaVenta').submit(); return false;">Factura de venta</a>
	
	{!!Form::close()!!}

	{!!Form::open(['route'=>'factura.prueba', 'method'=>'POST', 'id'=>'facturaCompra'])!!}
		<input type="hidden" name="movimiento" value="FC">
		<input type="hidden" name="series" value="I">
		<input type="hidden" name="pagos" value="F">
		<input type="hidden" name="folio" value="I">
		<input type="hidden" name="inventario" value="A">
		<input type="hidden" name="referencias" value="OC,CFDI">
		<input type="hidden" name="persona" value="P">
		<input type="hidden" name="cargo" value="V">

		<a href="javascript:{}" onclick="document.getElementById('facturaCompra').submit(); return false;">Factura de compra</a>
	{!!Form::close()!!}

@endsection