@extends('layouts.ventas')

@section('content')
    
    <style>
        .loading, .no-result, .btn-referencia, .referencias, .campo-ref{
            display: none;
        }
        .loading-cliente, .loading-producto{
            display: inline-block;
            visibility: hidden;
        }
        .loading-cliente > img, .loading > img, .loading-producto > img{
            width: 2em;
        }

        .ui-autocomplete{
            max-height:100px;
            overflow-y:auto;
            overflow-x:hidden;
            padding-right:20px
            z-index: 2147483647 !important;
        }
        
        * html .ui-autocomplete{
            height:300px
        }

        .ui-font{
            z-index: 9999;
        }

        .popover-cliente{
            display: none;
        }
        .cliente_over{
            cursor: pointer;
        }

        .popover-direccion{
            display: none;
        }
        .direccion_over{
            cursor: pointer;
        }

        .tbl_num_series > thead > tr > th{
            border-bottom: 0;
        }

        .tbl_num_series > tbody > tr > th{
            text-align: center;
        }

    </style>
    
    {!! Form::token() !!}
        {!!Form::hidden('producto_series', $series, ['class'=>'producto-series'])!!}
        {!!Form::hidden('requerir_pagos', $pagos, ['class'=>'requerir-pagos'])!!}
        {!!Form::hidden('accion_inventario', $inventario, ['class'=>'accion-inventario'])!!}
        {!!Form::hidden('cargo', $cargo, ['class'=>'cargo'])!!}

        <div  class="modal fade references-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm ui-front" role="document">
                <div class="modal-content" >
                    <div class="modal-body">
                        <div class="mensaje"></div>

                        <div class="list-group frmReferencia" style="display: none">

                            @foreach($referencias as $ref)
                                @if($ref == 'OC')
                                    <button type="button" class="list-group-item" data-dismiss="modal">Orden Compra</button>
                                @endif
                                @if($ref == 'CFDI')
                                    <button type="button" class="list-group-item" data-dismiss="modal">Factura</button>
                                @endif
                                @if($ref == 'C')
                                    <button type="button" class="list-group-item btnCotizacion" data-dismiss="modal" >Cotización</button>
                                @endif
                                @if($ref == 'OF')
                                    <button type="button" class="list-group-item" data-dismiss="modal">Orden Facturación</button>
                                @endif
                                @if($ref == 'P')
                                    <button type="button" class="list-group-item" data-dismiss="modal">Pedido</button>
                                @endif
                            @endforeach
                        </div>

                        <div class="frmComentarios" style="display: none">
                            <div class="container-fluid">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <input type="hidden" id="fila">
                                        <textarea class="form-control comentario" autofocus></textarea>    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <span class="btn btn-primary btnAddComment">Agregar</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="btn btn-default btnCancelar">Cancelar</span>
                                    </div>
                                </div>    
                                    
                            </div>
                        </div>

                        <div class="frmSeries" style="display: none">
                            <div class="container-fluid">
                                <table class="tbl_num_series table table-condensed">
                                    <thead>
                                        <tr>
                                            <th colspan="3">Numero de Series</th>
                                            <td colspan="2"><div class="num-series"></div></td>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Articulo</th>
                                            <td colspan="2">
                                                <div class="articulo"></div>
                                                <input type="hidden" id="codigo_series" />
                                                <input type="hidden" id="fila_serie" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2" colspan="3">Series</td>
                                            <td colspan="2" style="text-align: center;">Garantias</td>
                                        </tr>
                                        <tr>
                                            <td>Dias</td>
                                            <td>Num Copias</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <div class="row form-group">
                                    <div class="col-md-2">
                                        <button class="btn btn-primary btnAddSerie">Agregar</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-default btnCancelar">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="frmAutoriza" style="display: none">
                            <div class="row">
                                {!!Form::hidden('fila-producto', null, ['class'=>'fila-producto'])!!}
                                {!!Form::label('clave_autoriza', 'Clave', ['class'=>'col-md-4'])!!}
                                <div class="col-md-8">
                                    {!!Form::password('clave_autoriza', ['class'=>'form-control input-sm clave_autoriza', 'autofocus'])!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4  form-group">

                {!!Form::label('folio', 'Folio', ['class'=>'col-md-4'])!!}
                <div class="col-md-8">
                    @if($consecutivo == '')
                        {!!Form::text('folio', null, ['class'=>'form-control input-sm', 'required', 'autofocus'])!!}    
                    @else
                        {!!Form::text('folio', $consecutivo + 1, ['class'=>'form-control input-sm', 'required'])!!}
                    @endif
                </div>
                
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('fecha', 'Fecha', ['class'=>'col-md-4'])!!}
                <div class="col-md-8">
                    {!!Form::text('fecha', $fecha, ['class'=>'form-control input-sm', 'required'])!!}
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                {!!Form::label('dias_credito', 'Dias de credito', ['class'=>'col-md-4'])!!}
                <div class="col-md-8">
                    {!!Form::select('dias_credito', [], null, ['class'=>'form-control dias_credito'])!!}    
                </div>
            </div>

            <dov class="col-md-4 form-group">
                {!!Form::label('usoCFDI', 'Uso CFDI', ['class'=>'col-md-4'])!!}
                <div class="col-md-8">
                    {!!Form::text('usoCFDI', null, ['class'=>'form-control input-sm', 'disabled'])!!}
                </div>
            </dov>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                @if($persona == 'C')
                    {!!Form::label('cliente', 'Cliente')!!}
                    <div class="loading-cliente"></div>
                    {!!Form::text('cliente', null, ['class'=>'form-control input-sm cliente', 'required', 'autofocus'])!!}

                @elseif($persona == 'P')
                    {!!Form::label('proveedor', 'Proveedor')!!}
                    <div class="loading-cliente"></div>
                    {!!Form::text('Proveedor', null, ['class'=>'form-control input-sm proveedor', 'required'])!!}
                @endif
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('direccion_envio', 'Direccion de envio')!!}
                {!!Form::select('direccion_envio', [''=>'- Seleccione -'], null, ['class'=>'form-control input-sm', 'required', 'disabled'])!!}
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('vendedor', 'Vendedor')!!}
                {!!Form::select('vendedor', [''=>'- Seleccione -'], null, ['class'=>'form-control input-sm vendedor', 'required', 'disabled'])!!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group" >
                <div id="datos_cliente"></div>                
            </div>

            <div class="col-md-4 form-group">
                <div id="direccion_cliente"></div>
            </div>

            <div class="col-md-4 form-group">
                <div class="loading"></div>
                <div class="no-result">No se encontraron resultados</div>
                <button class="btn-referencia">Referencia</button>
                {!!Form::hidden('ref', 0, ['class'=>'ref-hide'])!!}
                {!!Form::text('campo-ref', null, ['class'=>'form-control input-sm campo-ref'])!!}
                {!!Form::select('referencias', [''=>'- Seleccione -'], null, ['class'=>'form-control input-sm referencias'])!!}
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12  form-group">
                <fieldset>
                    <legend>Productos</legend>
                    <div class="mensajeProductos"></div>
                    <table class="table table-condensed frmFactura">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Codigo</th>
                                <th>Cantidad</th>
                                <th>Promocion</th>
                                <th>Precio</th>
                                <th>MV/TP</th>
                                <th>Dto (%)</th>
                                <th>Precio Venta</th>
                                <th>Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table> 

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group col-md-12 btns">
                                    <button class="btn btn-primary addProduct" disabled>Agregar Producto</button> 
                                    <button class="btn btn-info notProduct" disabled>Agregar Comentario</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row frmFormaPago" style="display: none">
                                    <label class="col-md-12" style="text-align: center;">Forma de pago</label>
                                    <div class="col-md-3 form-group">
                                        <a class="btn btn-info formaPago">Agregar formaPago</a>
                                    </div>
                                    <div class="inputFormaPago col-md-9">
                                    </div>
                                </div>
                                    
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <div class="operaciones">
                                    <div class="row form-group enganche" style="display: none;">
                                        {!!Form::label('enganche', 'Enganche', ['class'=>'col-md-4'])!!}
                                        <div class="col-md-8">
                                            {!!Form::text('enganche', null, ['class'=>'form-control input-sm currency', 'data-number-to-fixed'=>'2'])!!}
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        {!!Form::label('subtotal', 'Subtotal', ['class'=>'col-md-4'])!!}
                                        <div class="col-md-8">
                                            <div class="input-group"> 
                                                <span class="input-group-addon">$</span>
                                                {!!Form::text('subtotal', '0.00', ['class'=>'form-control input-sm currency', 'data-number-to-fixed'=>'2', 'disabled'])!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        {!!Form::label('iva', 'IVA', ['class'=>'col-md-4'])!!}
                                        <div class="col-md-8">
                                            <div class="input-group"> 
                                                <span class="input-group-addon">$</span>
                                                {!!Form::text('iva', '0.00', ['class'=>'form-control input-sm currency', 'data-number-to-fixed'=>'2', 'disabled'])!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        {!!Form::label('total', 'Total', ['class'=>'col-md-4'])!!}
                                        <div class="col-md-8">
                                            <div class="input-group"> 
                                                <span class="input-group-addon">$</span>
                                                {!!Form::text('total', '0.00', ['class'=>'form-control input-sm currency', 'data-number-to-fixed'=>'2', 'disabled'])!!}
                                            </div>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>   
            
        </div>
@endsection

@section('scripts')    
    <script src="{{ asset('plugins/jquery-3.2.1/jquery-3.2.1.min.js') }}"></script>
    
    <script src="{{ asset('plugins/chosen-1.8.6/chosen.jquery.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.numeric.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('ventas/core/bootstrap.min.js') }}"></script>
    
    <script>
        $(document).ready(function(){
        //DECLARACION DE VARIABLES
            //tabla de productos
                num_fila = 1;
                var promocion = '';
                var producto;
                comentarios = []
                comment_showed = []
            //cliente
                var cliente = '';
                var ubicacion = ''; //direccion de envio en cotizacion

            //productos
                get_series = new Array()

        //FUNCIONES GENERALES
            //funciones cargadas al iniciar la pagina
            $('#cliente').focus()
            //llena el campo dias de credito con numeros del 1 al 45
            for (i = 0; i <= 45; i++) { 
                $('#dias_credito').append(
                    '<option value="'+i+'">'+
                        i+
                    '</option>'
                )
            }
            //llena el campo dias de credito con numeros del 60 al 360 sumando 30 al 60 y sucesivamente
            for(i=60; i<=360; i+=30){
                $('#dias_credito').append(
                    '<option value="'+i+'">'+
                        i+
                    '</option>'
                )
            }

            //formatea un numero a moneda
            //uso: formatNumber()
            formatNumber = {
                        separador: ",", // separador para los miles
                        sepDecimal: '.', // separador para los decimales
                        formatear:function (num){
                            num +='';
                            var splitStr = num.split('.');
                            var splitLeft = splitStr[0];
                            var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
                            var regx = /(\d+)(\d{3})/;
                            while (regx.test(splitLeft)) {
                                splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
                            }
                            return this.simbol + splitLeft  +splitRight;
                        },
                            new:function(num, simbol){
                            this.simbol = simbol ||'';
                            return this.formatear(num);
                        }
                    }
            
            //evento tecla f6
            $(document).keydown(function(e){
                //e.preventDefault();
                var keyCode = e.keyCode || e.which;
                var ref     = $('.ref-hide').val();
                vendedor    = $('#vendedor').val()

                 //your keyCode contains the key code, F1 to F12 
                 //is among 112 and 123. Just it.
                 //F6
                if(keyCode == 117){

                    if($('.campo-ref').is(':focus')){
                        
                        //buscar referencia
                        if(vendedor != '' &&  ref != 2){

                            mostrar_modal('frmReferencia', 'frmSeries', 'frmComentarios', 'frmAutoriza')
                        }

                    }else{
                        ocultar_modal()
                    }
                }




                if($('#cliente').is(':focus')){
                    $('#cliente').focus()    
                }
            });

            function mostrar_modal(mostrar, ocultar1, ocultar2, ocultar3){
                if(mostrar == 'frmSeries' || mostrar == 'frmComentarios' ){
                    $('.modal-sm').addClass('modal-lg');
                    $('.modal-lg').removeClass('modal-sm');
                    $('.modal-lg').css("width","600px")
                    
                    //evita que el modal se cierre con esc o al dar clic fuera
                    $('.references-modal').attr('data-backdrop', 'static')
                    $('.references-modal').attr('data-keyboard', 'false')

                }

                if(mostrar == 'frmAutoriza'){
                    //evita que el modal se cierre con esc o al dar clic fuera
                    $('.references-modal').attr('data-backdrop', 'static')
                    $('.references-modal').attr('data-keyboard', 'false')
                }

                if(mostrar == 'frmReferencia'){
                    //evita que el modal se cierre con esc o al dar clic fuera
                    $('.references-modal').attr('data-keyboard', 'true')
                }

                if(mostrar == 'frmReferencia' || mostrar == 'frmAutoriza'){
                    $('.modal-lg').addClass('modal-sm');
                    $('.modal-sm').removeClass('modal-lg');
                    $('.modal-sm').removeAttr('style')
                }
                
                if($('.references-modal').hasClass('in') ){
                    $('.'+mostrar).css('display', 'none')
                    $('.'+ocultar1).css('display', 'none')
                    $('.'+ocultar2).css('display', 'none')
                    $('.'+ocultar3).css('display', 'none')

                    $('.references-modal').modal('hide');

                }else if(!$('.references-modal').hasClass('in') ){
                    $('.references-modal').modal('show');

                    $('.'+mostrar).css('display', 'block')
                    $('.'+ocultar1).css('display', 'none')
                    $('.'+ocultar2).css('display', 'none')
                    $('.'+ocultar3).css('display', 'none')

                }
            }

            function ocultar_modal(){
                if($('.references-modal').hasClass('in') ){
                    $('.references-modal').modal('hide');
                    $('.frmComentarios').css('display', 'none')
                    $('.frmReferencia').css('display', 'none')
                    $('.frmSeries').css('display', 'none')
                    $('.frmAutoriza').css('display', 'none')

                    $('.references-modal').removeAttr('data-backdrop')
                    $('.references-modal').removeAttr('data-keyboard')
                }
            }

            $(document).on('click', '.btnCancelar', function(){
                $('.comentario').val('')
                $('.tbl_num_series tbody').html('')
                ocultar_modal()

            })

            $('.dias_credito').chosen()
            $('#dias_credito_chosen').css('width', '100%')
            
            $(document).on('change', '.dias_credito', function(){
                dias = $(this).val()

                if(dias >= 60){
                    $('.enganche').css('display', 'block')
                }else{
                    $('.enganche').css('display', 'none')
                }
                
            })

        //FUNCIONES CLIENTE
            //loading cliente
            verifica = false;
            $(document).on('focus', '#cliente', function(){
                cliente = $(this).val()
                verifica = verificar_tabla_producto()
                no_autocomplete = false;
                
                $(this).keyup(function(e){
                    if(verifica){
                        mensaje = confirm('¿Realmente desea cambiar de usuario?')
                        
                        if(mensaje){
                            verifica = false
                            
                            setTimeout(function(){
                                $('.loading-cliente').html('')
                                $('.loading-cliente').css('visibility', 'hidden')    
                            }, 100)

                            $('.frmFactura tbody').html('')
                            $('#subtotal').val('0.00')
                            $('#iva').val('0.00')
                            $('#total').val('0.00')
                            
                            $(this).val('')

                            $('#direccion_envio').html(
                                '<option value="">- Seleccione -</option>'
                            )

                            $('#vendedor').html(
                                '<option value="">- Seleccione -</option>'
                            )

                            $('#datos_cliente').html('')
                            $('#direccion_cliente').html('')
                            $('.campo-ref').css('display', 'none')
                            $('.referencias').css('display', 'none')
                            
                            no_autocomplete = false

                        }else{
                            no_autocomplete = true
                            verifica = false
                            $('.cliente').val(cliente)
                            $('.campo-ref').focus()
                        }

                    }else{
                        
                        if(cliente.length >= 2){
                            if(!no_autocomplete){
                                autocomplete_cliente(this)
                                $('.loading-cliente').html(
                                    '<img src="{{asset('assets/img/loading.gif')}}" >'
                                )
                                $('.loading-cliente').css('visibility', 'visible')
                            }
                            
                        }else{
                            setTimeout(function(){
                                $('.loading-cliente').html('')
                                $('.loading-cliente').css('visibility', 'hidden')    
                            }, 2000)
                            
                        }
                    }       
                })                
            })

            function autocomplete_cliente(input){
                $(input).autocomplete({
                    source: function(request, response){
                        
                        $.ajax({
                            url: "{!! route('ventas-clientes') !!}",
                            method: 'GET',
                            datatype: 'json',
                            data: {
                                cliente: request.term
                            },
                            success: function(c, textStatus, xhr){
                                if(xhr.status == 200){
                                    if(c.length == 0){
                                        $('.loading-cliente').html('No se encontraron resultados')
                                        setTimeout(function(){
                                            $('.loading-cliente').html('')        
                                        }, 3000)
                                        
                                    }else{
                                        $('.loading-cliente').css('visibility', 'hidden')
                                        
                                        response(c);
                                    }
                                        
                                }
                                
                            }
                        })
                    },
                    select: function(event, ui){
                        setTimeout(function(){
                            $('.loading-cliente').html('')
                            $('.loading-cliente').css('visibility', 'hidden')    
                        }, 1000)

                        $('#cliente').val(ui.item.id_cliente)

                        llenar_popover('datos_cliente', 'cliente_over', 'popover-cliente', 'Facturado a', ui.item);

                        cliente = ui.item.id_cliente

                        $('.dias_credito').val(ui.item.dias_credito).trigger('chosen:updated')

                        $('#usoCFDI').removeAttr('disabled')
                        $('#usoCFDI').val(ui.item.usoCFDI)
                        
                        //$('.dias_credito').chosen()

                        //busca direcciones
                            $.ajax({
                                url:'{!! route('ventas-direccion') !!}',
                                method: 'GET',
                                datatype: 'json',
                                data: {
                                    cliente: cliente
                                },
                                success: function(d){
                                    fiscal = {
                                        index:0, 
                                        nombre:'DIRECCIÓN FISCAL',
                                        razon_social: ui.item.razon_social,
                                        direccion: ui.item.direccion,
                                        no_exterior: ui.item.no_exterior,
                                        no_interior: ui.item.no_interior,
                                        colonia: ui.item.colonia,
                                        ciudad: ui.item.ciudad,
                                        estado: ui.item.estado,
                                        municipio: ui.item.municipio,
                                        pais: ui.item.pais,
                                        cp: ui.item.cp
                                    }

                                    ubicacion = fiscal.index;
                                    d.push(fiscal);

                                    $('#direccion_envio').html('');
                                    
                                    $.each(d, function(i,v){
                                        $('#direccion_envio').append(
                                            '<option value="'+v.index+'">'+
                                                v.nombre+
                                            '</option>'
                                        )
                                    })

                                    //direccion fiscal por defecto
                                    $('#direccion_envio').val(0);
                                    $('#direccion_envio').removeAttr('disabled')

                                    //popover direccion fiscal
                                    llenar_popover('direccion_cliente', 'direccion_over', 'popover-direccion', 'Dirección de envio', fiscal)

                                    //seleccion de direccion
                                    $('#direccion_envio').on('change', function(){
                                        
                                        valSelected = $(this).val()
                                        $.each(d, function(i, v){
                                            
                                            if(valSelected == v.index){
                                                llenar_popover('direccion_cliente', 'direccion_over', 'popover-direccion', 'Dirección de envio', v)
                                            }
                                        })   

                                        ubicacion = valSelected;
                                        buscar_cotizacion(cliente, ubicacion);
                                    })
                                }
                            })

                        agente = ui.item.agente
                        //busca Vendedor
                            $.ajax({
                                url: '{!! route('ventas.agente') !!}',
                                method: 'GET',
                                datatype: 'json',
                                success: function(v){
                                    $('#vendedor').html('');

                                    $.each(v, function(i, e){

                                        $('#vendedor').append(
                                            '<option value="'+e.id_empleado+'">'+
                                                e.departamento+' - '+
                                                e.nombre+' '+e.apellido_p+' '+e.apellido_m+
                                            '</option>'
                                        )   
                                    })

                                    $('#vendedor').val(agente);
                                    $('#vendedor').removeAttr('disabled')
                                    
                                    $('.campo-ref').css('display', 'block')
                                    $('.referencias').css('display', 'none')
                                    $('.campo-ref').focus()
                                }
                            })

                        //agregar fila de producto
                        //eliminar_fila(input)
                        $('.addProduct').removeAttr('disabled')
                        $('.notProduct').removeAttr('disabled')
                        $('.frmFactura tbody').html('')
                        agregar_fila(num_fila)     
                    }
                })
            }

            /*
            llena los campo del pop over del cliente y de la dierccion de envio
                llenar_popover(
                    id de elemento div,
                    clase del elemento a,
                    clase del elemento popover,
                    titulo del popover,
                    datos del pop over
                )    
            */
            function llenar_popover(div, link, pop, title, datos){
                //clientes
                if(title == 'Facturado a'){
                    if(datos.nombres == ''){
                        nombre = datos.razon_social;    
                    }else{
                        nombre = nombre = datos.nombres+' '+datos.apellido_p+' '+datos.apellido_m;
                    }
                }else{
                    if(datos.nombre == ''){
                        nombre = datos.razon_social;
                    }else{
                        nombre = datos.nombre;
                    }
                }

                //direccion
                datos.razon_social ? datos.razon_social : datos.razon_social = ''
                datos.direccion    ? datos.direccion    : datos.direccion = ''
                datos.no_exterior  ? datos.no_exterior  : datos.no_exterior = ''
                datos.no_interior  ? datos.no_interior  : datos.no_interior = ''
                datos.colonia      ? datos.colonia      : datos.colonia = ''
                datos.ciudad       ? datos.ciudad       : datos.ciudad = ''
                datos.municipio    ? datos.municipio    : datos.municipio = ''
                datos.estado       ? datos.estado       : datos.estado = ''
                datos.pais         ? datos.pais         : datos.pais = ''
                datos.cp           ? datos.cp           : datos.cp = ''

                div = '#'+div;
                $(div).html(
                    '<a class=".'+link+'">'+
                        nombre+
                    '</a>'+
                    '<div class="'+pop+' popover" style="width: 30em;">'+
                        '<span>'+title+'</span>'+
                        datos.razon_social+'<br>'+
                        datos.direccion+' '+datos.no_exterior+' '+datos.no_interior+'<br>'+
                        datos.colonia+'<br>'+
                        datos.ciudad+', '+datos.municipio+'<br>'+
                        datos.estado+' '+datos.pais+', '+datos.cp+
                    '</div>'       
                )
            }

            //funciones over -cliente y direccion
                $("#datos_cliente").click(function(){
                    $('.popover-cliente').css('display', 'block');
                });
                $("#datos_cliente").mouseout(function(){
                    $('.popover-cliente').css('display', 'none');
                });

                $("#direccion_cliente").click(function(){
                    $('.popover-direccion').css('display', 'block');
                });
                $("#direccion_cliente").mouseout(function(){
                    $('.popover-direccion').css('display', 'none');
                });

            //busca referencias
                //busca cotiazciones
                $(document).on('click', '.btnCotizacion', function(){
                    buscar_cotizacion(cliente, ubicacion);
                })
                
                //busca pedidos
                //busca ordenes de facturacion
            
            //llena el select con las cotizaciones del cliente seleccionado y de la direccion de envio
            function buscar_cotizacion(cliente, ubicacion){
                $('.campo-ref').css('display', 'none');
                $('.loading').html(
                    '<img src="{{asset('assets/img/loading.gif')}}" >'
                )
                $('.loading').css('display', 'block');

                $.ajax({
                    url: '{!! route('ventas.referencia.cotizacion') !!}',
                    method: 'GET',
                    datatype: 'json',
                    data:{
                        cliente_id : cliente,
                        ubicacion  : ubicacion
                    },
                    success: function(c, textStatus, xhr){
                        if(xhr.status == 200){
                            $('.loading').css('display', 'none')

                            if(c.length == 0){
                                $('.no-result').css('display', 'block')

                                setTimeout(function(){
                                    $('.no-result').css('display', 'none')
                                }, 3000)
                                
                                $('.campo-ref').css('display', 'block')
                                $('.campo-ref').removeAttr('disabled')
                                $('.campo-ref').focus()
                                $('.referencias').css('display', 'none');

                            }else{
                                $('.referencias').html(
                                    '<option value="">- Seleccione -</option>'
                                );

                                $.each(c, function(i, v){
                                    $('.referencias').append(
                                        '<option value="'+v.id_cotizacion+'">'+
                                            v.id_cotizacion+
                                        '</option>'
                                    )
                                })

                                $('.referencias').css('display', 'block');
                                $('.referencias').focus()


                                //al seleccionar una referencia se van a obtener los datos
                                $(document).on('change', '.referencias', function(){
                                    refSelected = $(this).val()

                                    $.each(c, function(i, v){
                                        if(refSelected == v.id_cotizacion){

                                        }
                                    })
                            
                                    //buscar producto
                                })
                            }
                                
                        }
                    }
                })
            }
  
        //FUNCIONES TABLA PRODUCTOS
            //Funcion que permite ver si existe algun producto en la tabla antes de cambiar de cliente
            function verificar_tabla_producto(){
                var existe = false;

                subtotal = $('#subtotal').val()
                if(subtotal != '0.00'){

                    existe = true
                    return existe
                }

                return existe
            }

            function obtener_clase(div){
                // obtener la clase 
                c = $(div).attr('class').split(' ');
                $.each(c, function(i,v){
                    if(v.length == 2){
                        clase = c[i]
                    }
                })
                return clase
            }

            function agregar_fila(num){
                bloquear_campos(num_fila)
                //agregar una nueva fila
                load         = '<div class="loading-producto f'+num+'"></div>'+
                                '<div class="loading-row f'+num+'"></div>'
                codigo       =  '<input type="hidden" class="pedir-series f'+num+'" />'+
                                '<input type="text" class="form-control input-sm f'+num+' codigo" style="width: 100%" />'
                cantidad     =  '<div class="input-group" >'+
                                    '<input type="text" class="form-control input-sm f'+num+' cantidad" disabled />'+
                                    '<span class="input-group-addon unidad f'+num+'"></span>'+
                                '</div>';
                promocion    =   '<select class="form-control input-sm f'+num+' promocion " style="width: 100%" disabled>'+
                                    '<option value="" selected>- Seleccionar -</option>'+
                                '</select>';
                precio       =  '<input type="hidden" name="autoriza" class="autoriza f'+num+'" />'+
                                '<input type="text" class="form-control input-sm f'+num+' precio" style="width:100%" disabled />'
                moneda       =  '<div class="f'+num+' moneda" style="width:100%"></div>'
                descuento    =  '<input type="text" class="form-control input-sm f'+num+' descuento" style="width:100%" disabled />'
                precio_venta =  '<div class="f'+num+' precio-venta" style="width:100%"></div>'
                monto        =  '<div class="f'+num+' monto" style="width:100%"></div>'
                
                accion       =  '<span class="close btnElimina f'+num+'" style="padding: 1px 2px">'+
                                    '<span aria-hidden="true">&times;</span>'+
                                '</span> '+
                                '<span class="close addComment f'+num+'" style="padding: 1px 2px">'+
                                    '<i class="glyphicon glyphicon-comment"></i>'+
                                '</span>'

                $('.frmFactura').append(
                    '<tr>'+
                        '<td style="width:3%">'+load+'</td>'+
                        '<td style="width:20%">'+codigo+'</td>'+
                        '<td style="width:13%">'+cantidad+'</td>'+
                        '<td style="width:21%">'+promocion+'</td>'+
                        '<td style="width:9%">'+precio+'</td>'+
                        '<td style="width:7%">'+moneda+'</td>'+
                        '<td style="width:7%">'+descuento+'</td>'+
                        '<td style="width:7%">'+precio_venta+'</td>'+
                        '<td style="width:10%">'+monto+'</td>'+
                        '<td style="width:3%">'+accion+'</td>'+
                    '</tr>'
                )

                $('.f'+num+'.codigo').focus()

                num_fila++;
            }

            function elimina_fila(tr){
                $(tr).parents('tr').remove()
                var nFilas = $(".frmFactura tbody tr").length;

                if(nFilas > 1){
                    $('.btns').html(
                        '<a class="btn btn-primary addProduct"s>Agregar Producto</a> '
                    )
                }else if(nFilas == 1 && $('.f'+nFilas+'.codigo').val() != ''){
                    $('.btns').html(
                        '<a class="btn btn-primary addProduct"s>Agregar Producto</a> '
                    )
                }else if(nFilas == 0){
                    num_fila = 1
                    agregar_fila(num_fila)
                    $('.btns').html(
                        '<a class="btn btn-primary addProduct"s>Agregar Producto</a> '+
                        '<a class="btn btn-info notProduct">Agregar Comentario</a>'
                    )
                }

                comentarios = []
                get_series = []

                $('.frmFormaPago').css('display', 'none')
            }

            function elimina_comentario(producto, fila, accion){
                //elimina comentario del arreglo de comentarios 
                for(var i = comentarios.length; i--;) {
                    if(accion == 'quitar'){
                        if(comentarios[i].producto === producto && comentarios[i].fila == fila) {
                            eliminado = comentarios.splice(i, 1);
                        }
                    }

                    if(accion == 'cambio_primera'){
                        if(comentarios[i].fila == fila) {
                            eliminado = comentarios.splice(i, 1);
                        }
                    } 
                }
            }
            
            //eliminar fila de producto
            $(document).on('click', '.btnElimina', function(){
                elimina_fila(this)    

                producto = $(this).parents('tr').attr('class')
                f = obtener_clase(this)

                //elimina fila de comentarios
                $('.comment'+producto).remove()

                elimina_comentario(producto, f, 'quitar')

                calcular_totales()
            })

            //agregar comentario sin producto
            $(document).on('click', '.notProduct', function(){

                $('.addProduct').css('display', 'none')

                $('.frmFactura tbody').html(
                    '<tr>'+
                        '<td colspan="10">'+
                            '<textarea class="form-control col-md-11" style="width:95%", placeholder="Comentario"></textarea>'+
                            '<span class="close btnEliminaNotProducto col-md-1" style="padding: 1px 2px; width:5%">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</span> '+
                        '</td>'+
                    '</tr>'
                )
            })

            $(document).on('click', '.btnEliminaNotProducto', function(){
                elimina_fila(this)
            })

            //bloquea la fila al agregar un nuevo campo
            function bloquear_campos(num){
                
                num_blocked = num - 1
                if(num_blocked > 0){
                    
                    $('.f'+num_blocked+'.codigo').attr('disabled', 'disabled')    
                }
            }

            //agregar fila de producto
            $(document).on('click', '.addProduct', function(){
                $('.notProduct').remove()

                    //verificaciones
                        //valor de precio que no sea menor al valor de la base de datos si es menor solicitar autorizacion de la persona que cuente con los permisos necesarios para realizar la accion

                        //si el valor de promocion es diferente de '' y se desea agregar un descuento mayor solicitar autilizacion de igual manera que en la verificacion anterior

                        //si el campo cantidad es == 0 mostrar un mensaje ya debe de haber por lo menos un producto

                        //si el campo de codigo == '' pedir codigo

                    //si las verificaciones son correctas
                        //agregar el id_producto como clase de todos los elementos de la fila

                        agregar_fila(num_fila)    
            })

            //acciones del campo codigo
            $(document).on('focus', '.codigo', function(){
                f = obtener_clase(this)

                $(this).keyup(function(){
                    fila_loading = '.'+f;

                    if($(this).val().length > 2){
                        autocomplete_productos(this, f) 

                        $('.loading-producto'+fila_loading).html(
                            '<img src="{{asset('assets/img/loading.gif')}}" >'
                        )

                        $('.loading-producto'+fila_loading).css('visibility', 'visible')
                            
                    }else{
                        $('.loading-producto'+fila_loading).html('')
                        $('.loading-producto'+fila_loading).css('visibility', 'hidden')
                    }
                })
            })

            var producto_series = $('.producto-series').val()
            //autocomplete catalogo de productos
            function autocomplete_productos(input, f){
                $(input).autocomplete({
                    source: function(request, response){
                        $.ajax({
                            url: '{!! route('ventas-productos') !!}',
                            method: 'GET',
                            datatype: 'json',
                            data: {
                                codigo: request.term
                            },
                            success: function(p, textStatus, xhr){
                                if(xhr.status == 200){
                                    if(p.length == 0){

                                        $('.loading-producto.'+f).html('No se encontraron resultados')
                                        setTimeout(function(){
                                            $('.loading-producto.'+f).html('')        
                                        }, 3000)
                                        
                                    }else{
                                        
                                        $('.loading-producto.'+f).css('visibility', 'hidden')
                                        
                                        response(p);
                                    }
                                        
                                }
                               
                            }

                        })
                    },
                    select: function(event, ui){
                        codigo = ui.item.codigo
                        duplicado = buscar_duplicado(codigo)
                        requerir_serie = ui.item.serie;

                        if(duplicado != '' && duplicado != f){
                            aux_cantidad = $('.cantidad.'+duplicado).val()
                            cantidad = parseInt(aux_cantidad) + 1
                            $('.cantidad.'+duplicado).val(cantidad)

                            descuento = $('.'+duplicado+'.descuento').val()
                            monto = obtener_monto(descuento, duplicado)
                            $('.'+duplicado+'.monto').html(
                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                            )
                            
                            calcular_totales()
                        
                            if(requerir_serie == 'V' && producto_series == 'S')
                                formulario_series_venta(duplicado, codigo, cantidad)

                            else if(requerir_serie == 'V' && producto_series == 'I')
                                formulario_series_compra(duplicado, codigo, cantidad)

                            elimina_fila(input)
                            
                            agregar_fila(num_fila)

                            $('.'+f+'.codigo').html('')

                        }else{
                            elimina_comentario(codigo, f, 'cambio_primera')

                            $(input).keydown(function(){
                                $('.loading-row.'+f).html('')
                                $('.loading-producto.'+f).css('display', 'inline-block')
                            })
                            
                            $('.loading-producto.'+f).css('visibility', 'visible')
                            
                            busca_producto('{!! route('ventas-productos') !!}', codigo, f)

                            $('.notProduct').css('display','none')    
                        }

                    }
                })
            }

            //busca en la tabla si ya existe un valor 
            function buscar_duplicado(codigo){
                aux_codigo = ''
                row_duplicado = '';
                $('.frmFactura tbody tr').each(function(index){
                    
                    $(this).children('td').each(function(index2){
                        if(index2 == 1){
                            aux_codigo = $(this).children('input[type="text"]').val();

                            if(aux_codigo != undefined){
                                if(aux_codigo.indexOf(codigo) != -1){
                                    var nFilas = $(".frmFactura tbody tr").length;
                                    
                                    if(nFilas != 1){
                                        row_duplicado = obtener_clase($(this).children('input'))
                                    }
                                }
                            }
                                
                        }
                        
                    })
                    if(row_duplicado != ''){
                        return false
                    }
                })
                return row_duplicado
            }

            //busca productos seleccionado
            function busca_producto(url, codigo, row){

                $.ajax({
                    url: url,
                    method: 'GET',
                    datatype: 'json',
                    data: {
                        obtener: codigo
                    },
                    success: function(p, textStatus, xhr){
                            
                        if(xhr.status == 200){
                            
                            if(p.length == 0){
                                $('.loading-producto.'+row).html('No se encontraron resultados')
                                setTimeout(function(){
                                    $('.loading-producto.'+row).html('')        
                                }, 3000)

                            }else{

                                //desbloquear campos
                                $('.'+row+'.cantidad').removeAttr('disabled')
                                $('.'+row+'.promocion').removeAttr('disabled')
                                $('.'+row+'.precio').removeAttr('disabled')
                                $('.'+row+'.descuento').removeAttr('disabled')


                                $('.loading-producto.'+row).css('visibility', 'hidden')

                                $('.'+row+'.cantidad').val(p.producto.cantidad)
                                $('.unidad.'+row).html(p.producto.unidad_venta)

                                $('.pedir-series.'+row).val(p.producto.requerir_serie)
                                
                                $('.'+row+'.codigo').parents("tr").removeAttr('class')
                                $('.'+row+'.codigo').parents("tr").addClass(codigo) 
                                
                                $('.'+row+'.promocion').html(
                                    '<option value="">'+
                                        '- Seleccione -'+
                                    '</option>'
                                )

                                $.each(p.promociones, function(i,v){
                                    $('.'+row+'.promocion').append(
                                        '<option value="'+v.id_promocion+'">'+
                                            v.descripcion+
                                        '</option>'
                                    )
                                })
                                
                                $('.'+row+'.precio').val(p.producto.precio)
                                //pueba agregar precio minimo
                                $('.'+row+'.precio').attr('min', p.producto.precio)
                                
                                $('.'+row+'.moneda').html(p.producto.moneda_venta+'/'+p.producto.tipo_cambio)
                                
                                $('.'+row+'.descuento').val(0)

                                precio_venta = parseFloat(p.producto.precio) * parseFloat(p.producto.tipo_cambio)
                                $('.'+row+'.precio-venta').html(
                                    '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                                )
                                monto = parseFloat(p.producto.cantidad) * parseFloat(precio_venta)
                                $('.'+row+'.monto').html(
                                    '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                                )

                                //al seleccionar una promocion
                                $(document).on('change', '.'+row+'.promocion', function(e){
                                    promSelected = $(this).val()

                                    $.each(p.promociones, function(i, e){

                                        if(promSelected == e.id_promocion){
                                            $('.'+row+'.descuento').val(e.descuento)
                                            $('.'+row+'.descuento').attr('min', 0)
                                            $('.'+row+'.descuento').attr('max', e.descuento)

                                            monto = obtener_monto(e.descuento, row)
                                            
                                            $('.'+row+'.monto').html(
                                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                                            )
                                        
                                            calcular_totales()
                                        }else{
                                            $('.'+row+'.descuento').val(0)
                                            $('.'+row+'.descuento').removeAttr('max')

                                            monto = obtener_monto(0, row)

                                            $('.'+row+'.monto').html(
                                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                                            )

                                            calcular_totales()
                                        }
                                    })
                                })
                            
                                calcular_totales()

                                $('.frmFormaPago').css('display', 'inline-block')
                            }
                        }

                    }

                })
            }

            //cambiar cantidad
            
            $(document).on('blur', '.cantidad', function(){
                f = obtener_clase(this)
                series = $('.pedir-series.'+f).val()
                cantidad = $(this).val()
                codigo = $('.cantidad').parents('tr').attr('class')

                if(series == 'V' && producto_series == 'S'){
                    if(cantidad != 0){

                        disp_series = series_disponibles(codigo)

                        if(parseInt(disp_series) < parseInt(cantidad)){
                            alert('El número de series disponibles es menor al el de productos que está ingresando. Se mostraran únicamente las series disponibles.')
                        
                            $(this).val(disp_series)

                            formulario_series_venta(f, codigo, disp_series)
                        }else{


                            formulario_series_venta(f, codigo, cantidad)
                        }

                        return false
                    }
                
                }else if(series == 'V' && producto_series == 'I'){
                    formulario_series_compra(f, codigo, cantidad)
                }

            })
            
            $(document).on('focus', '.cantidad', function(e){
                e.preventDefault();
                f = obtener_clase(this)
                codigo = $(this).parents('tr').attr('class')

                $(this).numeric(
                    {
                        decimal: false, 
                        negative: false 
                    }, 
                    function() { 
                        alert("Positive integers only"); 
                        this.value = ""; 
                        this.focus(); 
                    }
                );

                $(this).keyup(function(e){

                    cantidad = $(this).val()
    
                    if(cantidad != ''){
                        if($('.'+f+'.descuento').val() != '' || $('.'+f+'.descuento').val() != 0){    
                        
                            descuento = $('.'+f+'.descuento').val()
                            monto = obtener_monto(descuento, f)
                        
                            $('.'+f+'.monto').html(
                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                            )

                        }else{
                            monto = obtener_monto(0, f)

                            $('.'+f+'.monto').html(
                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                            )
                        }

                        calcular_totales()                            
                    }else{
                        
                        $('.'+f+'.monto').html('$ 0.00')
                    }
                })
            })

            //funciones de series para formulario de factura de compra
                function buscar_series(codigo){
                    serie_encontrada = []
                    $.each(get_series, function(i, s){
                        if(codigo == s.codigo){
                            serie_encontrada.push(s)
                        }
                    })

                    return serie_encontrada;
                }
                
                function existe_serie(codigo, serie){
                    existe = false;
                    $.each(get_series, function(i, s){
                        if(codigo == s.codigo && serie == s.serie){
                            existe = true
                        }
                    })

                    return existe
                }
                          
                function agregar_fila_serie_compra(inicio, fin, series, valor){
                    for(i=inicio; i<fin; i++){
                        if(valor == 'series'){
                            $('.tbl_num_series tbody').append(
                                '<tr>'+
                                    '<td colspan="3">'+
                                        '<input type="text" class="form-control input-sm series s'+i+' " value="'+series[i].serie+'" />'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" />'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" />'+
                                    '</td>'+
                                '</tr>'
                            )
                        }else{
                            $('.tbl_num_series tbody').append(
                                '<tr>'+
                                    '<td colspan="3">'+
                                        '<input type="text" class="form-control input-sm series s'+i+' " />'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" />'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" />'+
                                    '</td>'+
                                '</tr>'
                            )
                        }
                    }
                }

                function formulario_series_compra(f, codigo, cantidad){
                    $('.loading-producto.'+f).css('display', 'none')
                    oculta_comentario(codigo, f)

                    serie_shown = []
                    serie_shown = buscar_series(codigo)
                    num_series =  serie_shown.length
                    articulo = $('.'+f+'.codigo').val()

                    $('.num-series').html(cantidad)
                    $('.articulo').html(articulo)
                    $('#codigo_series').val(codigo)
                    $('.tbl_num_series tbody').html('')

                    if(num_series == 0){
                        //agregar numero de filas == cantidad
                        agregar_fila_serie_compra(0, cantidad, serie_shown, 'vacio', codigo)

                    }else if(num_series == cantidad){
                        //agregar numero de filas = cantidad == num_series
                        agregar_fila_serie_compra(0, cantidad, serie_shown, 'series', codigo)

                    }else if(num_series < cantidad){
                        agregar_fila_serie_compra(0, num_series, serie_shown, 'series', codigo)

                        agregar_fila_serie_compra(num_series, cantidad, serie_shown, 'vacio', codigo)
                        
                    }else if(num_series > cantidad){
                        //eliminar ultima serie
                        
                        eliminado = serie_shown.splice(num_series-1, 1)
                        
                        serie_delete = eliminado[0].serie
                        
                        $.each(get_series, function(i, s){
                            
                            if(serie_delete == s.serie){
                                get_series.splice(i, 1)
                            }
                        }) 

                    }

                    setTimeout(function(){
                        $('.series.s0').focus()
                    }, 500)

                    mostrar_modal('frmSeries', 'frmComentarios', 'frmReferencia', 'frmAutoriza')
                }
                
                add = new Array()
                
                $(document).on('click', '.btnAddSerie', function(){
                    producto = $('#codigo_series').val()
                    add = {
                        codigo: '',
                        serie:'',
                        garantia_dias: '',
                        garantia_copias: ''
                    }        

                    $('.tbl_num_series tbody tr').each(function(index){
                        cont = 0;
                        $(this).children('td').each(function(index2){
                            switch(index2){
                                case 0:
                                    //aux_codigo.push($(this).children('input').val());
                                    codigo = $('#codigo_series').val()
                                    if($(this).children('select')){
                                        serie = $(this).children('select').val()
                                    
                                        add.codigo = codigo
                                        add.serie = serie
                                    }else{
                                        return false   
                                    }    
                                    break
                                    

                                case 1:
                                    garantia_dias = $(this).children('input').val()
                                    add.garantia_dias = garantia_dias
                                    break

                                case 2:
                                    garantia_copias = $(this).children('input').val()
                                    add.garantia_copias = garantia_copias
                                    break

                            }
                            
                        })

                        existe = existe_serie(codigo, serie)
                        if(existe != true){
                            if(add.serie != undefined){
                                get_series.push(add)
                                add = []
                            }
                                
                        }else{
                            cont++
                            get_series = []
                        }
                    })

                    if(cont != 0){
                        
                        $('.mensaje').html(
                            '<div class="alert alert-danger alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                'Los campos no pueden contener el mismo numero de serie, intente de nuevo.'+
                            '</div>'
                        )
                        setTimeout(function(){
                            $('.mensaje').html('')
                        }, 2000)
                            
                    }else{
                        $('.mensaje').html('')
                        //ocultar modal y limpriar campos
                        ocultar_modal()

                        $('#codigo_series').val('')
                        $('.tbl_num_series tbody').html('')
                        serie_shown = []

                        div = $('.'+producto).children('td').children('.codigo')
                        f = obtener_clase(div)
                        
                        aux_serie = []
                        aux_serie = buscar_series(producto)

                        $('.loading-producto.'+f).css('display', 'none')
                        $('.loading-row.'+f).html(
                            '<span class="close showComment showSeries '+f+'" style="padding: 1px 2px">'+
                                '<i class="glyphicon glyphicon-plus"></i>'+
                            '</span>'
                        )

                        $('.'+f+'.promocion').focus()
                    }    
                })
            //fin de funciones de series de formulario de factura de compra

            function series_disponibles(codigo){
                var disponibles;

                $.ajax({
                    url: '{{ route('ventas.series_disponibles')}}',
                    method: 'GET',
                    data:{
                        producto : codigo
                    },
                    async: false,
                    success: function(s){
                        disponibles = s
                        
                    }
                })

                return disponibles
            }


            //funciones para formulario de ventas
                function formulario_series_venta(f, codigo, cantidad){

                    $('.loading-producto').css('display', 'none')
                    oculta_comentario(codigo, f)

                    serie_shown = []
                    serie_shown = buscar_series(codigo)
                    num_series =  serie_shown.length
                    articulo = $('.'+f+'.codigo').val()

                    $('.num-series').html(cantidad)
                    $('.articulo').html(articulo)
                    $('#codigo_series').val(codigo)
                    $('.tbl_num_series tbody').html('')

                    if(num_series == 0){
                        //agregar numero de filas == cantidad
                        agregar_fila_series_venta(0, cantidad, serie_shown, 'vacio', codigo)

                    }else if(num_series == cantidad){
                        //agregar numero de filas = cantidad == num_series
                        agregar_fila_series_venta(0, cantidad, serie_shown, 'series', codigo)

                    }else if(num_series < cantidad){
                        agregar_fila_series_venta(0, num_series, serie_shown, 'series', codigo)

                        agregar_fila_series_venta(num_series, cantidad, serie_shown, 'vacio', codigo)
                        
                    }else if(num_series > cantidad){
                        //eliminar ultima serie
                        
                        eliminado = serie_shown.splice(num_series-1, 1)
                        
                        serie_delete = eliminado[0].serie
                        
                        $.each(get_series, function(i, s){
                            
                            if(serie_delete == s.serie){
                                get_series.splice(i, 1)
                            }
                        }) 

                    }

                    setTimeout(function(){
                        $('.series.s0').focus()
                    }, 500)

                    mostrar_modal('frmSeries', 'frmComentarios', 'frmReferencia', 'frmAutoriza')
                }

                function agregar_fila_series_venta(inicio, fin, series, valor, codigo){
                    garantias = obtener_garantias(codigo);

                    for(i=inicio; i<fin; i++){
                        if(valor == 'series'){
                            $('.tbl_num_series tbody').append(
                                '<tr>'+
                                    '<td colspan="3">'+
                                        '<input type="text" class="form-control input-sm series s'+i+' " value="'+series[i].serie+'" disabled/>'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" value="'+series[i].garantia_dias+'" />'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" value="'+series[i].garantia_copias+'" />'+
                                    '</td>'+
                                '</tr>'
                            )
                        }else{
                            $('.tbl_num_series tbody').append(
                                '<tr>'+
                                    '<td colspan="3">'+
                                        '<select class="form-control input-sm series s'+i+'" >'+
                                        '</select>'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" value="'+garantias.dias+'" />'+
                                    '</td>'+
                                    '<td style="width: 25%">'+
                                        '<input type="text" style="width: 100%" class="form-control input-sm" value="'+garantias.copiado+'" />'+
                                    '</td>'+
                                '</tr>'
                            )

                            
                            //$('.series.s'+i).chosen()
                        }
                    }

                    obtener_series(codigo)
                }

                function obtener_series(codigo){
                    $.ajax({
                        url: '{!! route('ventas.obtener_series') !!}',
                        method: 'GET',
                        datatype: 'json',
                        data: {
                            producto : codigo
                        },
                        success: function(s, textStatus, xhr){
                            if(xhr.status == 200){
                                $('.series').html(
                                    '<option>- Seleccione -</option>'
                                )

                                $.each(s, function(i, v){
                                    $('.series').append(
                                        '<option>'+v.serie+'</option>'
                                    )    
                                })
                            }else{
                                obtener_series(codigo)
                                
                            }
                        }

                    })
                }

            //fin de formulario de ventas

            //cambiar precio
            $(document).on('focus', '.precio', function(){
                $(this).numeric(
                    {
                        negative: false 
                    }, 
                    function() { 
                        alert("Positive integers only"); 
                        this.value = ""; 
                        this.focus(); 
                    }
                );

                f = obtener_clase(this)

                $(this).keyup(function(){
                    precio = $(this).val()

                    tipo_cambio = $('.'+f+'.moneda').text()
                    t = tipo_cambio.split('/')
                    tipo_cambio = t[1]

                    precio_venta = parseFloat(precio * tipo_cambio);

                    $('.'+f+'.precio-venta').html(
                        '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                    )

                    descuento = $('.'+f+'.descuento').val()
                    monto = obtener_monto(descuento, f)

                    $('.'+f+'.monto').html(
                        '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                    )

                    calcular_totales()
                })

                $(this).blur(function(){

                    precio_minimo = $(this).attr('min')

                    if(precio < precio_minimo && precio.length <= precio_minimo.length){
                        $('.fila-producto').val(f)
                        $('.mensaje').html('')
                        
                        mostrar_modal('frmAutoriza', 'frmSeries', 'frmComentarios', 'frmReferencia')
                        
                        setTimeout(function(){
                            $('.clave_autoriza').val('')
                            $('.clave_autoriza').focus()    
                        }, 500)
                        
                    }

                    if(precio == precio_minimo){
                        $('.fila-producto').val('')
                        $('.autoriza.'+f).val('')
                    }
                })
            })

            autoriza = 0;
            
            $(document).on('keydown', '.clave_autoriza', function(e){
                enter = e.keyCode
                
                if(enter == 13){
                    clave = $(this).val()
                    autoriza = verifica_autorizacion(clave)
                    producto_row = $('.fila-producto').val()

                    if(autoriza != undefined){
                        $('.mensaje').html(
                            '<div class="alert alert-success alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                'La clave de autorización correcta.'+
                            '</div>'
                        );
                        $('.autoriza.'+producto_row).val(autoriza)

                        nvo_min = $('.'+producto_row+'.precio').val()
                        $('.'+producto_row+'.precio').attr('min', nvo_min)

                        setTimeout(function(){
                            ocultar_modal()
                            $('.'+producto_row+'.descuento').focus()
                        },1000)
                                    
                    }else{
                        
                        descuento = $('.'+producto_row+'.descuento').val()
                        
                        $('.mensaje').html(
                            '<div class="alert alert-danger alert-dismissible" role="alert">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                'La clave de autorización es incorrecta.'+
                            '</div>'
                        );

                        setTimeout(function(){
                            min = $('.'+producto_row+'.precio').attr('min')
                            $('.'+producto_row+'.precio').val(min)
                            $('.mensaje').html('')
                            $('.fila-producto').val('')
                            $('.clave_autoriza').val('')

                            
                            precio_venta = parseFloat(min * tipo_cambio);

                            $('.'+producto_row+'.precio-venta').html(
                                '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                            )

                            monto = obtener_monto(descuento, producto_row)

                            $('.'+f+'.monto').html(
                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                            )

                            calcular_totales()

                            ocultar_modal()
                        }, 1000)


                    }
                }    
            })
            
            function verifica_autorizacion(clave){
                token = $('input[name="_token"]').val()
                
                var output;

                $.ajax({
                    url: '{{ route('ventas.autorizacion') }}',
                    method: 'POST',
                    datatype: 'json',
                    data: {
                        _token: token,
                        clave: clave
                    },
                    async: false,
                    success: function(cve, textStatus, xhr){
                        
                        if(xhr.status == 200){
                            
                            output = cve.id_empleado;
                            return output
                        }
                    }

                })

                return output;
            }

            //cambiar descuento
            $(document).on('focus', '.descuento', function(){
                $(this).numeric(
                    {
                        decimal: false, 
                        negative: false 
                    }, 
                    function() { 
                        alert("Positive integers only"); 
                        this.value = ""; 
                        this.focus(); 
                    }
                );

                f = obtener_clase(this)
                max = $(this).attr('max');
                min = $(this).attr('min');

                $(this).keyup(function(){
                    descuento = $(this).val()

                    monto = obtener_monto(descuento, f)
                    
                    if(parseInt(descuento) > parseInt(max) && descuento != '' && $('.'+f+'.promocion').val() != ''){
                        $(this).val(max)

                        monto = obtener_monto(max, f)
                    }

                    if(descuento.length > 4 && descuento.indexOf('000') != -1){
                        $(this).val(min)

                        monto = obtener_monto(min, f)
                    }

                    $('.'+f+'.monto').html(
                        '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                    )                          

                    calcular_totales()
                })
            })

            //obtiene el monto total del producto 
            function obtener_monto(descuento, fila){

                cantidad = $('.'+fila+'.cantidad').val()
                precio_venta = $('.'+fila+'.precio-venta').text()

                
                precio_venta = precio_venta.replace(' ', '');
                precio_venta = precio_venta.replace('$', '');
                precio_venta = precio_venta.replace(/,/g, '');

                monto = parseFloat(cantidad * precio_venta)
                precio_aux = parseFloat(monto * descuento) / 100;
                
                nmonto = monto - precio_aux
                
                return nmonto
            }

            function calcular_totales(){
                var sub_monto = 0;
                var iva       = 0;
                var total     = 0;

                $('.frmFactura tbody, tr').each(function(index){
                    
                    $(this).children('td').each(function(index2){
                        switch(index2){
                            case 8:
                                if($(this).text().length == 0){
                                    sub_monto += parseFloat(0)
                                }else{
                                    aux_monto = $(this).text().replace(' ', '');
                                    aux_monto = aux_monto.replace('$', '');
                                    aux_monto = aux_monto.replace(/,/g, '');
                                    sub_monto += parseFloat(aux_monto)
                                }
                                break;
                        }
                    })
                })

                iva = sub_monto * .16;
                total = sub_monto + iva
                $('#subtotal').val(formatNumber.new(parseFloat(sub_monto).toFixed(2)))
                $('#iva').val(formatNumber.new(parseFloat(iva).toFixed(2)))
                $('#total').val(formatNumber.new(parseFloat(total).toFixed(2)))
            }

            //muestra el modal con el formulario para agregar comentarios

            $(document).on('click', '.addComment', function(){
                f = obtener_clase(this)
                producto = $(this).parents('tr').attr('class')

                $('#fila').val(f);
                //$('.comentario').focus()

                $('.loading-producto.'+f).css('display', 'none')
                oculta_comentario(producto, f)

                setTimeout(function(){
                    $('.comentario').focus()
                }, 500)

                mostrar_modal('frmComentarios', 'frmReferencia', 'frmSeries', 'frmAutoriza')
            })    

            $(document).on('click', '.btnAddComment', function(){
                fila = $('#fila').val()
                comentario = $('.comentario').val()

                //ocultar modal
                ocultar_modal()

                $('#fila').val('')
                $('.comentario').val('')

                producto = $('.'+fila).parents('tr').attr('class')

                add = {
                    fila: fila,
                    producto: producto,
                    comentario: comentario
                }

                comentarios.push(add)          

                $('.loading-producto.'+fila).css('display', 'none')
                $('.loading-row.'+fila).html(
                    '<span class="close showComment '+fila+'" style="padding: 1px 2px">'+
                        '<i class="glyphicon glyphicon-plus"></i>'+
                    '</span>'
                )
            })
            
            function buscar_en_comentarios(producto, fila){
                
                $.each(comentarios, function(i, v){
                    if(producto = v.producto && fila == v.fila){
                        comment_showed.push(v.comentario)
                    }
                })
            }

            $(document).on('click', '.showComment', function(){
                f = obtener_clase(this);

                producto = $(this).parents('tr').attr('class')

                //inserta nueva fila a la fabla
                var newRow = $(
                        '<tr class="comment'+producto+'">'+
                           '<td colspan="10">'+
                                '<div class="comments col-md-6"></div>'+
                                '<div class="show-series '+f+' col-md-6"></div>'+
                           '</td>'+
                        '</tr>'
                );
                newRow.insertAfter($('.'+producto));

                //busca los comentarios por producto
                comment_showed = []
                buscar_en_comentarios(producto, f)

                //agrega los comentarios a la fila que se agrego
                $('.comment'+producto+' td .comments').html(
                    '<label>Comentarios: </label><br>'
                )
                $.each(comment_showed, function(i, c){
                    $('.comment'+producto+' td .comments').append(
                        '- '+c+'<br>'
                    )
                })

                //agregar series
                serie_row = []
                serie_row = buscar_series(producto)
                $('.show-series.'+f).html(
                    '<table class="tblSeries '+f+' table">'+
                        '<tr>'+
                            '<th>Series</th>'+
                            '<th>Garantia Dias</th>'+
                            '<th>GarantiaCopias</th>'+
                            '<td>'+
                                '<a data-row="'+f+'" data-producto="'+producto+'" class="btnEditarSeries">Editar</a>'+
                            '</td>'+
                        '</tr>'+
                    '</table>'
                )
                num_series = serie_row.length
                $.each(serie_row, function(i, s){
                    //if(num_series-1 == i){
                        $('.tblSeries.'+f).append(
                            '<tr>'+
                                '<td>'+s.serie+'</td>'+
                                '<td>'+s.garantia_dias+'</td>'+
                                '<td>'+s.garantia_copias+'</td>'+
                            '</tr>'
                        )
                     /*
                        $('.comment'+producto+' td .series').append(
                            s.serie+' <b>Garantias en Dias</b>'+s.garantia_dias+' <b>Garantia en Copias</b>'+s.garantia_copias
                        )    
                    }else{
                        $('.comment'+producto+' td .series').append(
                            s.serie+' <b>Garantias en Dias</b>'+s.garantia_dias+' <b>Garantia en Copias</b>'+s.garantia_copias+' || '
                        )
                    
                    }*/
                       
                })

                //cambiar icono de + a -
                $('.loading-row.'+f).html(
                    '<span class="close hideComment '+f+'" style="padding: 1px 2px">'+
                        '<i class="glyphicon glyphicon-minus"></i>'+
                    '</span>'
                )
            })

            function oculta_comentario(producto, f){
                $('.comment'+producto).remove()

                //cambiar icono de + a -
                $('.loading-row.'+f).html(
                    '<span class="close showComment '+f+'" style="padding: 1px 2px">'+
                        '<i class="glyphicon glyphicon-plus"></i>'+
                    '</span>'
                )
            }

            $(document).on('click', '.hideComment', function(){
                f = obtener_clase(this)
                producto = $(this).parents('tr').attr('class')
                
                oculta_comentario(producto, f)
            })
            
        //PRUEBAS
            garantias = []
            function obtener_garantias(codigo){
                var garantia = {
                    dias: 0,
                    copiado: 0
                }

                $.ajax({
                    url: '{{ route('ventas.obtener-garantias') }}',
                    datatype: 'json',
                    method: 'GET',
                    data:{
                        codigo: codigo
                    },
                    async: false,
                    success: function(g){
                        if(g.garantia_tiempo.toLowerCase().indexOf('meses') != -1 || g.garantia_tiempo.toLowerCase().indexOf('mes')){
                            meses = g.garantia_tiempo.split(' ')
                            copiado = g.garantia_copiado.split(' ')

                            garantia = {
                                dias: meses[0] * 30,
                                copiado: copiado[0]
                            }

                        }

                        if(g.garantia_tiempo.toLowerCase().indexOf('año') != -1 || g.garantia_tiempo.toLowerCase().indexOf('años') != -1){
                            anio = g.garantia_tiempo.split(' ')
                            copiado = g.garantia_copiado.split(' ')

                            mes = anio[0] * 12

                            garantia = {
                                dias: mes * 30,
                                copiado: copiado[0]
                            }
                        }

                        if(g.garantia_tiempo.toLowerCase().indexOf('dia') != -1 || g.garantia_tiempo.toLowerCase().indexOf('dias') != -1 ){
                            dias = g.garantia_tiempo.split(' ')
                            copiado = g.garantia_copiado.split(' ')

                            garantia = {
                                dias: dias[0],
                                copiado: copiado[0]
                            }
                        }
                        
                    }
                })

                return garantia
            }
                       
        //PRUEBAS SIN TERMINAR
            num_forma_pago = 0
            $(document).on('click', '.formaPago', function(){
                if(num_forma_pago <3){
                    $('.inputFormaPago').append(
                        '<div class="row">'+
                            '<div class="col-md-6 form-group">'+
                                '<select name="forma_pago" class="form-control input-sm p'+num_forma_pago+'">'+
                                    '<option value="">- Seleccione -</option>'+
                                    '<option value="Efectivo">Efectivo</option>'+
                                    '<option value="Tarjeta">Tarjeta de Credito/Debito</option>'+
                                    '<option value="Cheque">Cheque</option>'+
                                    '<option value="Transferencia">Transferencia</option>'+
                                    '<option value="Credito">Credito</option>'+
                                '</select>'+
                            '</div>'+

                            '<div class="col-md-6 form-group">'+
                                '{!!Form::text('cantidad_pago', null, ['class'=>'form-control input-sm cantidad-pago'])!!}'+
                            '</div>'+
                        '</div>'
                    )

                
                    num_forma_pago++;
                }
            })

            
            $(document).on('click', '.btnEditarSeries', function(){
                f = $(this).attr('data-row')
                producto = $(this).attr('data-producto')
                //producto = $('.'+f+'.cantidad').parents('tr').attr('class')
                cantidad = $('.'+f+'.cantidad').val()

                formulario_series_venta(f, producto, cantidad)
            })
            
        })
    </script>
@endsection