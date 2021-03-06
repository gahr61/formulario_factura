@extends('layouts.ventas')

@section('content')
        {!! Form::token() !!}
        {!!Form::hidden('tipo_movimiento', $movimiento, ['class'=>'tipo-movimiento'])!!}
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
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @foreach($referencias as $ref)
                                @if($ref == 'CFDI')
                                    <button type="button" class="list-group-item btnCFDI" data-dismiss="modal">Factura</button>
                                @endif
                                @if($ref == 'C')
                                    <button type="button" class="list-group-item btnCotizacion" data-dismiss="modal" >Cotización</button>
                                @endif

                                @if($ref == 'OC')
                                    <button type="button" class="list-group-item btnOC" data-dismiss="modal">Orden Compra</button>
                                @endif
                                
                                @if($ref == 'OF')
                                    <button type="button" class="list-group-item btnOF" data-dismiss="modal">Orden Facturación</button>
                                @endif
                                @if($ref == 'P')
                                    <button type="button" class="list-group-item btnPedido" data-dismiss="modal">Pedido</button>
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
                                                <input type="hidden" id="fila_series" />
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
                        {!!Form::text('folio', $consecutivo, ['class'=>'form-control input-sm', 'required'])!!}
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
                {!!Form::hidden('ref', 0, ['class'=>'ref-hide'])!!}
                {!!Form::text('campo-ref', null, ['class'=>'form-control input-sm campo-ref', 'placeholder'=>'Presione F6 para realizar la busqueda de referencia'])!!}
                {!!Form::select('referencias', [], null, ['class'=>'form-control input-sm referencias', 'multiple', 'id'=>'referencias', 'placeholder'=>'seleccione'])!!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                {!!Form::label('dias_credito', 'Dias de credito')!!}
                {!!Form::select('dias_credito', [], null, ['class'=>'form-control dias_credito'])!!}    
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('usoCFDI', 'Uso CFDI')!!}
                {!!Form::select('usoCFDI', $usoCFDI, null, ['class'=>'form-control input-sm uso-cfdi', 'disabled'])!!}
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
                                <th>MV/TC</th>
                                <th>Dto (%)</th>
                                <th>Precio Venta</th>
                                <th>Importe</th>
                                <th></th>
                                <th></th>
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
                            <div class="col-md-9">
                                <div class="row frmFormaPago" style="display: none">
                                    <label class="col-md-12" style="text-align: center;">Forma de pago</label>
                                    <div class="col-md-2 form-group">
                                        <a class="btn btn-info formaPago">Agregar formaPago</a>
                                    </div>
                                    <div class="inputFormaPago col-md-10">
                                    </div>
                                </div>
                                    
                            </div>
                            
                            <div class="col-md-3 form-group">
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

                    <hr>

                    <div class="container-fluid final-form" style="display: none">
                        <label>Forma de pago de factura</label>
                        <div class="forma-pago-final">
                            
                        </div>
                    </div>
                </fieldset>
            </div>   
            
        </div>
@endsection

@section('scripts')    
    <script src="{{ asset('plugins/jquery-3.2.1/jquery-3.2.1.min.js') }}"></script>
    
    <script src="{{ asset('plugins/chosen-1.8.6/chosen.jquery.min.js') }}"></script>

    <script src="{{ asset('venta/js/jquery.numeric.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('venta/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('venta/js/app.js') }}"></script>

    <script>
        //CLIENTE
            $(document).on('focus', '#cliente', function(){
                cliente = $(this).val()
                verifica = verificar_tabla_producto() // verifica  si existe algun producto agregado para facturar
                no_autocomplete = false;
                
                //si se ingreso un cliente y se desea cambiar se verifica si no se habian agregado productos, si se agregaron elimina los productos y se reinicia el proceso de facturacion, si no se desea cambiar el cliente se dejan las cosas 
                $(this).keyup(function(e){
                    if(verifica){
                        mensaje = confirm('¿Realmente desea cambiar de cliente?')
                        
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
                            
                            $(".referencias").chosen("destroy");
                            $('.referencias').css('display', 'none')
                            $('.referencias').html('')
                            referencias = []

                            $('.btns').html(
                                '<a class="btn btn-primary addProduct" disabled>Agregar Producto</a> '+
                                '<a class="btn btn-info notProduct" disabled>Agregar Comentario</a>'
                            )

                            $('.frmFormaPago').css('display', 'none')
                            $('.final-form').css('display', 'none')
                            $('.forma-pago-final').html('')
                            $('.inputFormaPago').html('')
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

            /*
                funcion que permite obtener el cliente escribiendo su nombre o numero de cliente
            */
            function autocomplete_cliente(input){
                $(input).autocomplete({
                    source: function(request, response){
                        
                        $.ajax({
                            url: "{!! route('ventas-clientes') !!}",
                            method: 'GET',
                            datatype: 'xml',
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
                        tipo_cliente = ui.item.tipo

                        $('.dias_credito').val(ui.item.dias_credito).trigger('chosen:updated')

                        $('#usoCFDI').removeAttr('disabled')
                        $('#usoCFDI').val(ui.item.usoCFDI)
                        
                        //busca direcciones
                            busca_direcciones(cliente, ui.item)

                        agente = ui.item.agente
                        //busca Vendedor
                            busca_agente(agente)

                        //agregar fila de producto
                        
                        setTimeout(function(){
                            $('.addProduct').removeAttr('disabled')
                            $('.notProduct').removeAttr('disabled')
                            $('.frmFactura tbody').html('')
                            agregar_fila(num_fila)
                            $('.campo-ref').focus()
                        }, 500)
                            
                    }
                })
            }

            //busca las direcciones de envio que tiene el cliente
            function busca_direcciones(cliente, item){
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
                            razon_social: item.razon_social,
                            direccion: item.direccion,
                            no_exterior: item.no_exterior,
                            no_interior: item.no_interior,
                            colonia: item.colonia,
                            ciudad: item.ciudad,
                            estado: item.estado,
                            municipio: item.municipio,
                            pais: item.pais,
                            cp: item.cp
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

                            /*
                            ssi se cambia de direccion de envio
                            resetear valor de referencias y mostrar modal
                            */
                            $('.frmFactura tbody').html('')
                            agregar_fila(1)
                            $('#subtotal').val('0.00')
                            $('#iva').val('0.00')
                            $('#total').val('0.00')

                            referencia = []
                            $('.campo-ref').css('display', 'block')
                            $('.campo-ref').removeAttr('disabled')
                            $('.referencias').chosen("destroy");
                            $('.referencias').css('display', 'none')
                            
                            mostrar_modal('frmReferencia', 'frmSeries', 'frmComentarios', 'frmAutoriza')
                            
                            
                            //buscar_cotizacion(cliente, ubicacion);
                        })
                    }
                })
            }

            //busca el los vendedores por sucursal, seleccionando por default el agente que atendio al cliente
            function busca_agente(agente){
                $.ajax({
                    url: '{!! route('ventas.agente') !!}',
                    method: 'GET',
                    datatype: 'json',
                    success: function(v, textStatus, xhr){

                        if(xhr.status == 200){
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
                            $(".referencias").chosen("destroy");
                            $('.referencias').css('display', 'none')
                            $('.referencias').html('')
                            $('.campo-ref').focus()    
                        }
                        
                    }
                }).fail(function( jqXHR, textStatus ) { //si la consulta falla se intenta buscar de nuevo el agente
                    busca_agente(agente)
                });
            }
        
        //REFERENCIAS
        //COTIZACIONES
            //llena el select con las cotizaciones del cliente seleccionado y de la direccion de envio
            var referencias = [];
            /*
                busca las cotizaciones de cada cliente, si el cliente es diferente de tipo 7, se obtienen todas las cotizaciones
                independientemente de la ubicacion, si es igual a tipo 7 se busca por ubicacion
            */
            function buscar_cotizacion(cliente, tipo, ubicacion){
                if(tipo != 7){
                    ubicacion = ''
                }
                if(cliente.indexOf('-') != -1){
                    aux_cliente = cliente.split(' - ')
                    nvo_cliente = aux_cliente[0]
                }else{
                    nvo_cliente = cliente
                }

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
                        cliente_id : nvo_cliente,
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

                            }else{
                                $('.referencias').html(
                                    ''
                                );
                                
                                //ref = 0
                                $.each(c, function(i, v){
                                    if(v.vencido != 1){
                                        $('.referencias').append(
                                            '<option value="'+v.id_cotizacion+'">'+
                                                'COT'+v.id_cotizacion+
                                            '</option>'
                                        )
                                        
                                    }
                                                                            
                                })

                                /*if(ref != undefined){
                                    $('.no-result').css('display', 'block')

                                    setTimeout(function(){
                                        $('.no-result').css('display', 'none')
                                    }, 3000)
                                    
                                    $('.campo-ref').css('display', 'block')
                                    $('.campo-ref').removeAttr('disabled')
                                    $('.campo-ref').focus()
                                }else{*/

                                    $('.referencias').css('display', 'block');

                                    $('.referencias').val(referencias).chosen({
                                        placeholder_text_multiple: 'Seleccione'
                                    }).trigger('chosen:updated');

                                    $('.referencias').css('display', 'none');
                                    
                                    $('.chosen-container-multi').css('width', '100%')
                                    

                                    //al seleccionar una referencia se van a obtener los datos                                
                                    $(document).on('change', '.referencias', function(e, params){
                                        if(params.deselected){ 
                                            referencias = $(this).val();
                                            refDelete = params.deselected

                                            eliminar_fila_referencia('COT'+refDelete)

                                            $('.inputFormaPago').html('')
                                            num_forma_pago = 0
                                        }else{
                                            referencias = $(this).val();
                                            refSelected = params.selected

                                            prod_existencia = 0
                                            $.each(c, function(i, v){
                                                if(refSelected == v.id_cotizacion){
                                                    numFila = $(".frmFactura tbody tr").length;

                                                    //nvo_agregar_datos_cotizacion('COT'+v.id_cotizacion, v, numFila)
                                                    agregar_datos_cotizacion('COT'+v.id_cotizacion, v)
                                                }
                                            })
                                        }
                                    })
                                //}
                                    
                            }
                                
                        }
                    }
                })
            }

            //se obtienen los accesorios de cada producto que se encuentra en la cotizacion
            function obtener_accesorios(series){
                token = $('input[name="_token"]').val()

                var rel;
                $.ajax({
                    url: '{{ route("ventas.obtener_accesorios") }}',
                    method: 'POST',
                    datatype: 'json',
                    data:{
                        _token: token,
                        relacionados: series
                    },
                    async: false,
                    success:function(r){

                        rel = r
                    }
                })
                return rel;
            }

            /*
                funcion que obtiene los datos de la cotizacion para agregar las filas a la factura
                recibe como parametris
                tipoRef = numero de cotizacion (ej. COT149)
                datos = datos de la cotizacion 
            */
            function agregar_datos_cotizacion(tipoRef, datos){
                last_row = obtener_ultima_fila();
                
                //verifica que la ultima fila de la tabla se encuentre vacia
                if(last_row.codigo == ''){

                    //si la cotizacion es direfente a multimple y no tiene productos relacionados solo busca un producto
                    if(datos.producto != 'MULTIPLE' && datos.relacionados == ''){
                        busca_producto('{!! route('ventas-productos') !!}', tipoRef, datos.producto, last_row.f, datos.precio)


                    //si la cotizacion es diferente de multiple y si tiene productos relacionados
                    }else if(datos.producto != 'MULTIPLE' && datos.relacionados != ''){
                        
                        //lo agrega el la fila que este vacia
                        aux_existencia = busca_producto('{!! route('ventas-productos') !!}', tipoRef, datos.producto, last_row.f, datos.precio) || 0


                        //verifica que el la existencia del producto sea diferente de 0 o diferente de null
                        if(aux_existencia != 0){
                            //buscar los productos/accesorios/consumibles relacionados 
                            accesorio = buscar_relacionados(datos)
                            //por cada relacionado obtiene el numero de la fila y le agrega uno
                            agregar_fila_referencia(accesorio, tipoRef)

                            $('.notProduct').css('display','none')

                        }else{
                            num_fila = $('.frmFactura tbody tr').length
                           
                            if(num_fila == 1 &&  last_row.codigo == ''){
                                $('.frmFormaPago').css('display', 'none')
                                $('.final-form').css('display', 'none')
                                //$('.notProduct').css('display','inline-block')
                            }
                        }

                    //si la cotizacion es multimple
                    }else if(datos.producto == 'MULTIPLE'){
                        agregar_fila_referencia_multiple(tipoRef, datos, last_row.f)
                    }
                }else{ //si la ultima fila no se encuentra vacia
                    if(datos.producto != 'MULTIPLE' && datos.relacionados == ''){ 
                        nueva_fila_referencia(tipoRef, datos) //agrega una nueva fila

                    }else if(datos.producto != 'MULTIPLE' && datos.relacionados != ''){
                        nueva_fila_referencia(tipoRef, datos)   //agrega una nueva fila

                        if(prod_existencia == 0 || prod_existencia == null){
                        }else{                            
                            //buscar los relacionados
                            accesorio = buscar_relacionados(datos)

                            //por cada relacionado obtiene el numero de la fila y le agrega uno
                            agregar_fila_referencia(accesorio, tipoRef)
                        }

                    }else if(datos.producto == 'MULTIPLE'){
                        //obtiene el numeri de la nueva fila
                        num = nvo_num_fila()
                        nva_fila = 'f'+num;

                        agregar_fila(num) //agrega una nueva fila

                        agregar_fila_referencia_multiple(tipoRef, datos, nva_fila)

                    }
                }

                //$('.notProduct').css('display','none') //oculta el boton de comentario
            }

            //agrega una nueva fila de cualquier referencia
            function nueva_fila_referencia(tipoRef, datos){
                last_row = obtener_ultima_fila();

                if(last_row.codigo == ''){
                    prod = busca_producto('{!! route('ventas-productos') !!}', tipoRef, datos.producto, last_row.f, datos.precio, datos.cantidad) 
                }else{
                    num = nvo_num_fila()
                    nva_fila = 'f'+num;
                                            
                    agregar_fila(num)

                    prod = busca_producto('{!! route('ventas-productos') !!}', tipoRef, datos.producto, nva_fila, datos.precio, datos.cantidad) 
                }
            }

            //agrega los accesorios relacionados con la referencia seleccionada
            function agregar_fila_referencia(accesorio, tipoRef){
                $.each(accesorio, function(i, a){
                    cont = 0
                    $.each(series_relacionados, function(j, sr){
                        precio = sr.split(',')
                        if(sr.indexOf(a.id_producto) != -1 && precio[1] != -1){ //si el producto se encuentra en los relacionados y el precio es diferente de -1
                            //obtener el numero de la nueva fila
                            num = nvo_num_fila()
                            nva_fila = 'f'+num;

                            agregar_fila(num)
                            
                            precio = sr.split(',')
                            precio_relacionado = precio[1]

                            aux_existencia = busca_producto('{!! route('ventas-productos') !!}', tipoRef, a.id_producto, nva_fila, precio_relacionado) 
                        }
                        
                    })
                    cont++ 
                })

                if(accesorio.length == cont && aux_existencia){
                    verifica_precios(tipoRef)

                }
            }

            //agrega nueva fila donde la referencia cuente con valor de  producto igual a multiple
            function agregar_fila_referencia_multiple(tipoRef, datos, f){
                series      = datos.caracteristicas.split(',')
                precios     = datos.relacionados.split(',')
                cantidades  = datos.garantia.split(',')
                descuentos  = datos.descuentos ? datos.descuentos.split(',') : 0
                unidades    = datos.unidades ? datos.unidades.split(',') : ''
                cont        =  0
                
                aux_existencia = busca_producto('{!! route('ventas-productos') !!}', tipoRef, series[0], f, precios[0], cantidades[0], descuentos[0], unidades[0]) || 0
                
                //recorre los codigos de producto de la cotizacion multiple
                $.each(series, function(i, sc){
                    if(sc != '' && i != 0){
                        last_row = obtener_ultima_fila()

                        num = nvo_num_fila()
                        nva_fila = 'f'+num;

                        if(last_row.f != nva_fila){
                            agregar_fila(num)
                        }

                        aux_existencia = busca_producto('{!! route('ventas-productos') !!}', tipoRef, sc, nva_fila, precios[i], cantidades[i], descuentos[i], unidades[i])
                    }
                    cont++;
                })

                if(series.length == cont && aux_existencia != 0){
                    verifica_precios(tipoRef)

                    num_fila = $('.frmFactura tbody tr').length
                
                    $('.frmFormaPago').css('display', 'block')
                    $('.final-form').css('display', 'block')
                }
            }

        //PEDIDOS
            //busca los pedidos de un cliente 
            function busca_pedido(cliente, ubicacion){
                if(cliente.indexOf('-') != -1){
                    aux_cliente = cliente.split(' - ')
                    nvo_cliente = aux_cliente[0]
                }else{
                    nvo_cliente = cliente
                }

                $('.campo-ref').css('display', 'none');
                $('.loading').html(
                    '<img src="{{asset('assets/img/loading.gif')}}" >'
                )
                $('.loading').css('display', 'block');

                $.ajax({
                    url: '{{ route('referencia.pedido') }}',
                    method: 'GET',
                    datatype: 'json',
                    data:{
                        cliente: nvo_cliente,
                        ubicacion: ubicacion
                    },
                    success: function(p, textStatus, xhr){
                        if(xhr.status == 200){
                            $('.loading').css('display', 'none')

                            if(p.length == 0){
                                $('.no-result').css('display', 'block')

                                setTimeout(function(){
                                    $('.no-result').css('display', 'none')
                                }, 3000)
                                
                                $('.campo-ref').css('display', 'block')
                                $('.campo-ref').removeAttr('disabled')
                                $('.campo-ref').focus()

                            }else{
                                $('.referencias').html(
                                    ''
                                );

                                $.each(p, function(i, v){
                                    $('.referencias').append(
                                        '<option value="'+v.pedido_id+'">'+
                                            'P'+v.pedido_id+
                                        '</option>'
                                    )                                    
                                })

                                $('.referencias').css('display', 'block');

                                $('.referencias').val(referencias).chosen({
                                    placeholder_text_multiple: 'Seleccione'
                                }).trigger('chosen:updated');

                                $('.referencias').css('display', 'none');
                                
                                $('.chosen-container-multi').css('width', '100%')
                                
                                $('.referencias').focus()

                                //al seleccionar una referencia se van a obtener los datos                                
                                $(document).on('change', '.referencias', function(e, params){
                                    if(params.deselected){ 
                                        referencias = $(this).val();
                                        refDelete = params.deselected

                                        eliminar_fila_referencia('P'+refDelete)
                                    }else{
                                        referencias = $(this).val();
                                        refSelected = params.selected

                                        $.each(p, function(i, v){
                                            if(refSelected == v.pedido_id){
                                                numFila = $(".frmFactura tbody tr").length;
                                                agregar_datos_pedido('P'+v.pedido_id, v, numFila)
                                            }
                                        })
                                    }
                                })
                            }
                                
                        }
                    }

                })
            }

            //agrega los productos de cada pedido a la tabla de productos
            function agregar_datos_pedido(ref, datos, numFila){
                last_row = obtener_ultima_fila();

                if(last_row.codigo == ''){
                    $.each(datos.detalles_pedido, function(i, d){
                        if(i>0){
                            d.producto = d.id_producto
                            d.precio = d.subtotal
                            d.cantidad = d.cantidad

                            busca_producto('{!! route('ventas-productos') !!}', ref, d.id_producto, last_row.f, d.subtotal)    
                            //nueva_fila_referencia(ref, d)
                        }else{

                            busca_producto('{!! route('ventas-productos') !!}', ref, d.id_producto, last_row.f, d.subtotal)    
                        }
                        
                    })
                }else{
                    $.each(datos.detalles_pedido, function(i, d){
                        num = nvo_num_fila()

                        nva_fila = 'f'+num;
                            
                        agregar_fila(num)
                            
                        busca_producto('{!! route('ventas-productos') !!}', ref, d.id_producto, nva_fila, d.subtotal)                        
                    })
                }
            }

        //ORDENES DE FACTURACION 
            //busca las ordenes de facturacion de un cliente 
            function busca_ordenes_facturacion(cliente, ubicacion){
                if(cliente.indexOf('-') != -1){
                    aux_cliente = cliente.split(' - ')
                    nvo_cliente = aux_cliente[0]
                }else{
                    nvo_cliente = cliente
                }

                $('.campo-ref').css('display', 'none');
                $('.loading').html(
                    '<img src="{{asset('assets/img/loading.gif')}}" >'
                )
                $('.loading').css('display', 'block');

                $.ajax({
                    url: '{{ route('referencia.order_facturacion') }}',
                    method: 'GET',
                    datatype: 'json',
                    data: {
                        cliente: nvo_cliente,
                        ubicacion: ubicacion
                    },
                    success: function(o, textStatus, xhr){
                        if(xhr.status == 200){
                            $('.loading').css('display', 'none')

                            if(o.length == 0){
                                $('.no-result').css('display', 'block')

                                setTimeout(function(){
                                    $('.no-result').css('display', 'none')
                                }, 3000)
                                
                                $('.campo-ref').css('display', 'block')
                                $('.campo-ref').removeAttr('disabled')
                                $('.campo-ref').focus()

                            }else{
                                $('.referencias').html(
                                    ''
                                );

                                $.each(o, function(i, v){
                                    $('.referencias').append(
                                        '<option value="'+v.id_reporte+'">'+
                                            'OF'+v.folio_orden+
                                        '</option>'
                                    )                                    
                                })

                                $('.referencias').css('display', 'block');

                                $('.referencias').val('').chosen({
                                    placeholder_text_multiple: 'Seleccione'
                                }).trigger('chosen:updated');

                                $('.referencias').css('display', 'none');
                                
                                $('.chosen-container-multi').css('width', '100%')
                                
                                $('.referencias').focus()

                                //al seleccionar una referencia se van a obtener los datos
                                $(document).on('change', '.referencias', function(e, params){
                                    if(params.deselected){ 
                                        referencias = $(this).val();
                                        refDelete = params.deselected
                                        $.each(e.target, function(i, t){
                                            if(t.value == refDelete)
                                                ref = t.text
                                        })
                                        eliminar_fila_referencia(ref)
                                    }else{
                                        referencias = $(this).val();
                                        refSelected = params.selected

                                        $.each(o, function(i, v){
                                            if(refSelected == v.id_reporte){

                                                numFila = $(".frmFactura tbody tr").length;
                                                agregar_orden_facturacion('OF'+v.folio_orden, v, numFila)
                                            }
                                        })
                                    }
                                })
                            }
                                
                        }
                    }
                })
            }

            //una vez seleccionada la orden de facturacion se obtienen los datos de un script de intranet
            function agregar_orden_facturacion(ref, datos, row){
                if(datos.estado_reporte.indexOf('F') != -1 || datos.estado_reporte.indexOf('N') != -1 || datos.estado_reporte.indexOf('C') != -1 || datos.estado_reporte.indexOf('O') != -1){
                    previa = false
                }else{
                    previa = true
                }

                $.ajax({
                    url : 'http://www.decop.com/pruebas/interno/AI_Plantilla.php',
                    method : 'GET',
                    dataType: 'html',
                    data: {
                        destino:'AI_Reporte_Orden.php',
                        id_reporte: datos.id_reporte,
                        es_previa: previa,
                        omite_impresion: 'on'
                    },
                    success: function(orden){
                        if(orden.indexOf('ERROR 214') != -1){
                            alert('No se ha terminado el reporte, por lo tanto no se puede imprimir Orden de Facturación')
                        }else{

                            //se obtienen los datos de la orden de facturacion
                            inicio = orden.indexOf("<textarea name='holdtext' COLS=57 ROWS=10 onFocus='this.select()'>")  
                            fin = orden.indexOf('</textarea')
                            nuevo = orden.substring(inicio, fin)
                            nuevo = nuevo.replace("<textarea name='holdtext' COLS=57 ROWS=10 onFocus='this.select()'>", '')
                            nuevo = $.trim(nuevo)
                            nuevo = nuevo.split('\n')

                            $.each(nuevo, function(i,n){
                                if(n != undefined){
                                    if(n.length == 0 || n.length == 1) {
                                        nuevo.splice(i, 1)
                                    }
                                }
                            })

                            nuevo.splice(0, 1)
                            if(nuevo[1].indexOf('RENTA') != -1){
                                if(nuevo[2].indexOf('RENTA DE EQUIPO') != -1){
                                    nuevo.splice(2, 1)
                                }
                                
                            }

                            //se obtienen los comentarios de la orden de facturacion
                            inicio = orden.indexOf('<textarea id="comentarios_orden" COLS=57 ROWS=10>')
                            fin = orden.indexOf('<p class="termina_comentarios"></p>')
                            comments_orden = orden.substring(inicio, fin)
                            comments_orden = comments_orden.replace('<textarea id="comentarios_orden" COLS=57 ROWS=10>', '')
                            comments_orden = comments_orden.replace('</textarea>', '')
                            comments_orden = comments_orden.replace('<br>', '')
                            comments_orden = comments_orden.split('\n')

                            nvo_comment_order = []
                            $.each(comments_orden, function(i, o){
                                if(o != undefined){
                                    if(i == 0){
                                        report = comments_orden[i+1].split(' ')
                                        quitar_espacio(report)

                                        var nvo_report;
                                        if(report.length == 2){
                                            if(report[0].indexOf('O.F') != -1){
                                                nvo_report = report[1]
                                            }else if(report[1].indexOf('O.F') != -1){
                                                nvo_report = report[0]
                                            }

                                        }else if(report.length == 4){
                                            if(report[0].indexOf('O.F') != -1){
                                                nvo_report = report[2]+' '+report[3]
                                            }else if(report[2].indexOf('O.F') != -1){
                                                nvo_report = report[0]+' '+report[1]
                                            }
                                        }
                                        
                                        nvo_comment_order.push(comments_orden[i]+' '+nvo_report+' '+comments_orden[i+2])
                                    }
                                    if(i%3 == 0 && i > 1){
                                        
                                        if(comments_orden[i+1] != undefined){
                                            report = comments_orden[i+1].split(' ')
                                            quitar_espacio(report)
                                            var nvo_report;
                                            if(report.length == 2){
                                                if(report[0].indexOf('O.F') != -1){
                                                    nvo_report = report[1]
                                                }else if(report[1].indexOf('O.F') != -1){
                                                    nvo_report = report[0]
                                                }
                                            }else if(report.length == 4){
                                                if(report[0].indexOf('O.F') != -1){
                                                    nvo_report = report[2]+' '+report[3]
                                                }else if(report[2].indexOf('O.F') != -1){
                                                    nvo_report = report[0]+' '+report[1]
                                                }
                                            }
            
                                            nvo_comment_order.push(comments_orden[i]+' '+nvo_report+' '+comments_orden[i+2])
                                        }
                                            
                                    }
                                }
                                    
                            })
                            
                            //agrega los datos de la orden de facturacion a la tabla de productos
                            agrega_fila_orden_factura(nuevo, ref, nvo_comment_order)

                            $('.notProduct').css('display','none')
                        }
                    }
                })
            }

            //quita los espacions de una arreglo
            function quitar_espacio(cadena){
                $(cadena).each(function(i, c){
                    if(c.length == 0){
                        cadena.splice(i, 1)
                        quitar_espacio(cadena)
                    }

                })
            }

            //agrega una los datos de cada elemento de la orden de facturacion
            function nva_con_codigo(row, datos, d, i, ref){
                solo_val = d.split('    ')
                $.each(solo_val, function(i, v){
                    if(v != undefined){
                        if(v.length == 0)
                            solo_val.splice(i, 1)
                    }
                })

                codigo = solo_val[0]               
                if(codigo == 'RENTA'){
                    precio = solo_val[1].replace(' ', '')
                    nvo_precio = precio.replace(',', '')
                    punto = datos[i-1].indexOf('.')
                    cantidad = datos[i-1].substring(0, punto)
                    descuento = 0

                    busca_producto('{!! route('ventas-productos') !!}', ref, codigo, f, nvo_precio, cantidad, descuento)
                }else{
                    precio = solo_val[1].replace(',', '')    
                    punto = datos[i-1].indexOf('.')
                    cantidad = datos[i-1].substring(0, punto)
                    descuento = datos[i+1]

                    busca_producto('{!! route('ventas-productos') !!}', ref, codigo, f, precio, cantidad, descuento)
                }
            }

        //PRODUCTO
            //acciones del campo codigo
            $(document).on('focus', '.codigo', function(){
                last_row = obtener_ultima_fila()
                $(this).keyup(function(){
                    fila_loading = '.'+f;

                    if($(this).val().length > 2){
                        autocomplete_productos(this, last_row.f) 

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
                        //$('.frmFormaPago').css('display', 'none')
                        $('.inputFormaPago').html('')
                        num_forma_pago = 1

                        codigo = ui.item.codigo
                        duplicado = buscar_duplicado(codigo)
                        requerir_serie = ui.item.serie;

                        //agrega_producto_existencia(ui.item, f)
                        if(duplicado != '' && duplicado != f){
                            aux_cantidad = $('.cantidad.'+duplicado).val()
                            cantidad = parseInt(aux_cantidad) + 1
                            $('.cantidad.'+duplicado).val(cantidad)

                            nvo_actualiza_existencia(codigo, cantidad, duplicado)

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
                            

                            busca_producto('{!! route('ventas-productos') !!}', '', codigo, f)

                            $('.notProduct').css('display','none')
                        }

                    }
                })
            }

            //busca productos seleccionado
            function busca_producto(url, referencia='', codigo, row, precio=0, cantidad=0, descuento=0, unidad='', ref_promocion=''){
                if(codigo != undefined){
                    if(codigo.indexOf('-') != -1){
                        producto = codigo.split(' - ')
                        codigo = producto[0]
                    }
                }
                
                clase_fila = ''
                repetida = false
                //verificar que no se encuentre en la fila anterior
                $('.frmFactura tbody tr').each(function(i, t){
                    if($(this).attr('class') != undefined){
                        clase_fila = $(this).attr('class')

                        buscados = codigo+' '+referencia

                        if(clase_fila.indexOf(buscados) != -1){
                            repetida = true
                            return false
                        }
                    }
                    
                })

                if(repetida == false){
                    $.ajax({
                        url: url,
                        method: 'GET',
                        datatype: 'json',
                        data: {
                            obtener: codigo
                        },
                        async: false,
                        success: function(p, textStatus, xhr){
                                
                            if(xhr.status == 200){
                                
                                if(p.length == 0){
                                    $('.loading-producto.'+row).html('No se encontraron resultados')
                                    setTimeout(function(){
                                        $('.loading-producto.'+row).html('')        
                                    }, 3000)

                                }else{
                                    setTimeout(function(){
                                        $('.loading-producto.'+row).html('')        
                                    }, 500)

                                    prod_existencia = p.producto.existencia

                                    $('.inputFormaPago').html('')
                                    num_forma_pago = 1
                                    
                                    $('.addComment'+'.'+row).css('display', 'block')
                                    $('.addCodSAT'+'.'+row).css('display', 'block')
                                    $('.addCodSAT'+'.'+row).attr('data-content', '<b>Codigo SAT</b> '+p.producto.codigo_sat)

                                    //desbloquear campos
                                    $('.'+row+'.cantidad').removeAttr('disabled')
                                    $('.'+row+'.promocion').removeAttr('disabled')
                                    $('.'+row+'.precio').removeAttr('disabled')
                                    $('.'+row+'.descuento').removeAttr('disabled')

                                    $('.loading-producto.'+row).css('visibility', 'hidden')

                                    //referencia
                                    if(referencia == ''){
                                        $('.'+row+'.codigo').parents("tr").removeAttr('class')
                                        $('.'+row+'.codigo').parents("tr").addClass(codigo)

                                    }else if( referencia.indexOf('PROM') != -1){
                                        $('.'+row+'.codigo').parents("tr").removeAttr('class')
                                        $('.'+row+'.codigo').parents("tr").addClass(codigo+' '+referencia+' '+ref_promocion)
                                        //$('.'+row+'.codigo').parents("tr").addClass(referencia)
                                    }else{
                                        
                                        $('.'+row+'.codigo').parents("tr").removeAttr('class')
                                        $('.'+row+'.codigo').parents("tr").addClass(codigo+' '+referencia)
                                        //$('.'+row+'.codigo').parents("tr").addClass(referencia)
                                    }

                                    //codigo
                                    $('.'+row+'.codigo').val(p.producto.codigo+' - '+p.producto.descripcion)

                                    //cantidad
                                    if(cantidad == 0){
                                        $('.'+row+'.cantidad').val(p.producto.cantidad)
                                        if(p.producto.inventariable == 'V')
                                            add = agrega_producto_existencia(p.producto, row)
                                    }else{
                                        p.producto.cantidad = cantidad
                                        $('.'+row+'.cantidad').val(p.producto.cantidad)
                                        if(p.producto.inventariable == 'V')
                                            add = agrega_producto_existencia(p.producto, row)
                                    }

                                    //unidad
                                    if(unidad == '')
                                        $('.unidad.'+row).html(p.producto.unidad_venta)
                                    else
                                        $('.unidad.'+row).html(unidad)

                                    $('.pedir-series.'+row).val(p.producto.requerir_serie)
                                
                                    //promocion
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
                                    
                                    //precio
                                    if(precio == 0 && referencia.indexOf('PROM') == -1){ //cuando es un campo de promocion pero no tiene precio
                                        $('.'+row+'.precio').val(p.producto.precio)
                                        $('.'+row+'.precio').attr('min', p.producto.precio)
                                        $('.'+row+'.precio').attr('max', p.producto.descuento_maximo)

                                    }else if(precio != -1 || referencia.indexOf('PROM') != -1){ //cuando es un campo de promocion 0 tiene precio
                                        if(p.producto.moneda_venta == 0){
                                            $('.'+row+'.precio').val(precio)
                                            $('.'+row+'.precio').attr('min', p.producto.precio)
                                            $('.'+row+'.precio').attr('max', p.producto.descuento_maximo)

                                        }else{
                                            if(descuento.length == 1)
                                                decto = '.0'+descuento
                                            else
                                                decto = '.'+descuento

                                            if(referencia.indexOf('OF') != -1){
                                                $('.'+row+'.precio').val(precio)
                                                
                                                $('.'+row+'.precio').attr('min', p.producto.precio)
                                                $('.'+row+'.precio').attr('max', p.producto.descuento_maximo)
                                                
                                            }else{
                                                precio1 = parseFloat(precio) / (1 - parseFloat(decto))
                                                precio2 = precio1 / parseFloat(p.producto.tipo_cambio)

                                                $('.'+row+'.precio').val(precio2.toFixed(3))
                                                $('.'+row+'.precio').attr('min', parseFloat(p.producto.precio).toFixed(3))
                                                $('.'+row+'.precio').attr('max', p.producto.descuento_maximo)
                                            }                                            
                                        }
                                        
                                    }

                                    //moneda de venta y tipo de cambio
                                    if(p.producto.moneda_venta == 0)
                                        $('.'+row+'.moneda').html('M.N./1.0000')
                                    else if(p.producto.moneda_venta == 1)
                                        $('.'+row+'.moneda').html('USD/'+p.producto.tipo_cambio)
                                    else if(p.producto.moneda_venta == 2)
                                        $('.'+row+'.moneda').html('E/'+p.producto.tipo_cambio)

                                    //descuento
                                    if(descuento == 0){
                                        $('.'+row+'.descuento').val(0)
                            
                                    }else{
                                        $('.'+row+'.descuento').val(descuento)
                                        $('.'+row+'.descuento').attr('min', 0)
                                        $('.'+row+'.descuento').attr('max', descuento)
                                        $('.'+row+'.descuento').attr('disabled', 'disabled')
                                    }
                                    
                                    //precion de venta
                                    precio_venta = obtener_precio_venta(descuento, row)

                                    $('.'+row+'.precio-venta').html(
                                        '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                                    )

                                    //importe
                                    monto = obtener_monto(descuento, row)
                                    
                                    $('.'+row+'.monto').html(
                                        '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                                    )


                                    //al seleccionar una promocion
                                    seleccion_promocion(row, p, referencia, codigo)
                                
                                    calcular_totales()

                                    $('.frmFormaPago').css('display', 'inline-block')
                                    $('.btns').html(
                                        '<a class="btn btn-primary addProduct">Agregar Producto</a> '
                                        
                                    )
                                    $('.final-form').css('display', 'block')


                                    if(p.producto.inventariable == 'V'){
                                        if(add){
                                            elimina_fila('.'+row+'.cantidad')  
                                        }
                                    }
                                }
                            }

                        }
                    })

                    return prod_existencia
                }else{
                    elimina_fila('.'+row)
                }  
            }
            
            //cuando se selecciona una promocion se agregan o se eliminan la fila dependiendo del tipo de promocion que se seleccione
            function seleccion_promocion(row, p, referencia, codigo){
                //al seleccionar una promocion
                $(document).on('change', '.'+row+'.promocion', function(e){
                    $(this).parents('tr').removeClass('info')
                    $(this).parents('tr').removeClass('warning')
                    $(this).parents('tr').removeClass('danger')

                    promSelected = $(this).val()

                    $.each(p.promociones, function(i, e){

                        if(promSelected == e.id_promocion){
                            if(e.descuento.length == 1)
                                decto = '.0'+e.descuento
                            else
                                decto = '.'+e.descuento

                            precio1 = parseFloat(e.precio_2) / (1 - parseFloat(decto))
                            precio2 = precio1 / parseFloat(p.producto.tipo_cambio)

                            min = $('.'+row+'.precio').attr('min')
                            max = $('.'+row+'.precio').attr('min')

                            if(max > 0){
                                max_desc = (precio * max) / 100;
                                precio3 = parseFloat(precio - max)
                            }else{
                                //provicionalmente
                                precio3 = precio2
                            }
                        
                            if($('.'+row+'.cantidad').val() != ''){
                                $('.'+row+'.precio').val(precio2.toFixed(3))
                                $('.'+row+'.precio').attr('min', precio3)

                                $('.'+row+'.descuento').val(e.descuento)
                                $('.'+row+'.descuento').attr('min', 0)
                                $('.'+row+'.descuento').attr('max', e.descuento)

                                precio_venta = obtener_precio_venta(e.descuento, row)
                                $('.'+row+'.precio-venta').html(
                                    '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                                )

                                monto = obtener_monto(e.descuento, row)
                                
                                $('.'+row+'.monto').html(
                                    '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                                )

                                if(e.incluye != '' && e.incluye != ',' && (referencia.indexOf('PROM') == -1) ){
                                    incluye_producto_promocion(e, row, referencia)
                                }else{
                                    elimina_fila_promocion($('.'+row+'.promocion'), codigo, referencia)
                                }
                            
                                calcular_totales()

                                verifica_precios(referencia, row) 
                            }
                                               
                        }else if(promSelected == ''){
                            elimina_fila_promocion($('.'+row+'.promocion'), codigo)

                            $('.'+row+'.precio').val(p.producto.precio)
                            $('.'+row+'.precio').attr('min', p.producto.precio)

                            descuento = $('.'+row+'.descuento').val(0)
                            $('.'+row+'.descuento').removeAttr('max')
                            $('.'+row+'.descuento').removeAttr('disabled')

                            precio_venta = obtener_precio_venta(descuento.val(), row)
                            $('.'+row+'.precio-venta').html(
                                '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                            )

                            monto = obtener_monto(0, row)

                            $('.'+row+'.monto').html(
                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                            )

                            calcular_totales()
                        }
                    })
                })
            }

            //si la promocion incluye un producto lo busca y agrega una nueva fila a la tabla de productos en la vista
            function incluye_producto_promocion(element, row, ref){
                incluye = element.incluye.split(',')
                $.each(incluye, function(i, v){
                    if(v == ''){
                        incluye.splice(i, 1)
                    }
                })

                $.each(incluye, function(i, v){
                    
                    incluido = v.split('x')

                    cod_include = incluido[1]
                    cant_include = incluido[0]
                    
                    duplicado = promocion_repetida('PROM'+row+element.producto, cod_include)

                    if(!duplicado){
                        num = nvo_num_fila()
                        nva_fila = 'f'+num;

                        agregar_fila(num)

                        busca_producto('{!! route('ventas-productos') !!}', 'PROM'+row+element.producto, cod_include, nva_fila, 0, cant_include, 0,'', ref) 
                    }
                        

                })
            }

            //verifica que el producto cuente con series disponibles
            function series_disponibles(codigo){
                producto = codigo.split(' ')
                var disponibles;
                codigo = producto[0]
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

            //obtiene las garantias(dias, impresiones) de cada producto en caso de que requiera series
            function obtener_garantias(codigo){
                producto = codigo.split(' ')
                codigo = producto[0]
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

            //obtiene las series de un producto
            function obtener_series(codigo){
                producto = codigo.split(' ')
                codigo = producto[0]
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

            //verifica la clave de autorizacion de precios 
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
                            //return output
                        }
                    }

                })

                return output;
            }

        // PRUEBAS
            function verificar_datos_factura(){
                //verificar existan filas en la tabla productos
                last_row = obtener_ultima_fila()

                num_fila = $('.frmFactura tbody tr').length;

                if(last_row.codigo == '' && num_fila == 1){
                    alert('No es posible generar la factura.')
                    // datos de factura
                    
                }else{
                    if(cliente.indexOf('-') != -1){
                        aux_cliente = cliente.split(' - ')
                        nvo_cliente = aux_cliente[0]
                    }else{
                        nvo_cliente = cliente
                    }

                    tipo_mov = $('.tipo-movimiento').val();

                    if(tipo_mov = 'FV'){
                        mov = 1
                    }

                    factura = {
                        id_factura: $('#folio').val(),
                        //sucursal: se obtiene en el controlador $_SESSION['sucursal']
                        cliente: nvo_cliente,
                        usoCFDI : $('#usoCFDI').val(),
                        ubicacion: ubicacion,
                        fecha: $('#fecha').val(),
                        //subtotal: $('#subtotal').val(),
                        //iva: $('#iva').val(),
                        importe: $('#total').val(),
                        //saldo: ??? se obtiene de forma de pago??
                        //fecha_vencimiento:  se calcula en el controlador obteniendo los dias de credito:
                        dias_credito: $('.dias_credito').val(),
                        cobrador: 0,
                        //estado: como saber si ya se pago ?? si el importe es igual a la suma de las formas de pago??
                        //fecha_estado: por default es la fecha de hoy, se actualiza en el controlador,
                        //contacto: 0
                        //fecha_contacto: ''
                        vueltas: 0,
                        documento: mov,// cuando es factura,
                        documento_actual: mov,// ??,
                        agente: $('.vendedor').val(),
                        //agente_o: ??
                        //descuentos: suma de descuentos?? desde aqui no
                        //importes: ??
                        //comisiones: ??
                        //p_comisiones: ??
                        //comision_sf: ??
                        //comision_sc: ?? 
                        //deudor: ??asta aqui no
                        //e_entrega: empleado que realiza la factura??
                        //f_entrega: fecha en que se realiza la factura??
                        avisado: 'F',
                        //uuid: donde se encuentra?? este no
                        //fecha_timbre: yo lo necesito??
                        //factura_enviada: ?? asta aqui no
                        forma_pago: [], // la que tenga el mayor importe
                        //cuenta_pago: ?? no se ocupa
                        //orden_compra: ??
                    }

                    detalle_factura = new Array()

                    $('.frmFactura tbody tr').each(function(index){
                       $(this).removeClass('info')
                       $(this).removeClass('warning')
                       $(this).removeClass('danger')
                        clase = $(this).attr('class')
                        if(clase.indexOf(' ') != -1){
                            clase = clase.split(' ')
                            referencia = clase[1];
                        }else{
                            referencia = ''
                        }

                        $(this).children('td').each(function(index1){
                            switch(index1){
                                case 1:

                                    producto = $(this).children('input[type=text]').val()
                                    f = obtener_clase($(this).children('input[type=text]'))

                                    if(producto.indexOf('-') != -1){
                                        aux_codigo = producto.split(' - ')
                                        producto = aux_codigo[0]
                                    }

                                    con_serie = $(this).children('input[type=hidden]').val()
                                    comment_showed = []
                                    buscar_en_comentarios(producto, f)
                                    break
                                case 2:
                                    cantidad = $(this).children('div').children('input').val()
                                    break
                                case 4: 
                                    autoriza = $(this).children('input[type=hidden]').val()
                                    precio = $(this).children('input[type=text]').val()
                                    min = $(this).children('input[type=text]').attr('min')
                                    max = $(this).children('input[type=text]').attr('max')
                                    break
                                case 5:
                                    tipo= $(this).text().split('/')
                                    tipo_cambio = tipo[1]
                                case 6:
                                    if($(this).children('input').val() != undefined)
                                        descuento =  $(this).children('input[type=text]').val()
                                    break
                            }
                        })

                        errores = 0

                        if(max > 0){
                            max_desc = (precio * max) / 100;
                            min = parseFloat(precio - max)
                        }

                        encontrada = ''
                        if(con_serie == 'V' && toString(precio) == toString(min)){
                            encontrada = buscar_series(producto)
                            if(encontrada.length == 0){
                                error_series(this)
                                errores++
                            }

                        }else if(con_serie == 'F' && parseFloat(precio) < min){
                            encontrada = buscar_series(producto)
                            if(encontrada.length == 0){
                                error_precio(this)
                                errores++
                            }

                        }else if(con_serie == 'V' && parseFloat(precio) < min){
                            encontrada = buscar_series(producto)
                            if(encontrada.length == 0){
                                error_serie_precio(this)
                                errores++
                            }
                        }

                        detalle = {
                            //id_detalle: automatico??
                            factura_venta: factura.id_factura,
                            producto: producto,
                            referencia: referencia,
                            cantidad: cantidad,
                            precio: precio,
                            descuento: descuento,
                            tipo_cambio: tipo_cambio,
                            autoriza: autoriza,
                            series: encontrada,
                            comentarios: comment_showed

                        }
                        detalle_factura.push(detalle)
                    })

                    if(errores != 0){
                        alert('Existen algunos errores, corrija e intente nuevamente.')
                    }else{      

                        $('.inputFormaPago').each(function(i){
                            inputs = $(this).children('div')
                            
                            inputs.children('.formas').each(function(j){

                                add = {
                                    forma : '',
                                    importe : 0,
                                    ref : '',
                                    cuenta : ''

                                }
                                
                                forma   = $(this).children('select').val() || ''
                                importe = inputs.children('.total-formas').eq(j).children('input').val() || ''
                                ref     = inputs.children('.num-referencia').eq(j).children('input').val() || ''
                                cuenta  = inputs.children('.num-cuenta').eq(j).children('input').val() || ''
                                
                                add.forma = forma
                                add.importe = importe
                                add.ref = ref
                                add.cuenta = cuenta
                                
                                factura.forma_pago.push(add)
                                
                            })
                            
                        })

                        
                        token = $('input[name="_token"]').val()
                        $.post(
                            '{{ route('ventas-guardar-factura') }}',
                            {
                                _token: token,
                                factura: factura,
                                detalle_factura: detalle_factura
                            },
                            function(data, textStatus, xhr){
                                /*console.log(data)                               
                                var w = window.open('about:blank');
                                w.document.open()
                                w.document.write(data)
                                w.document.close()
                                */
                            }
                        ).done(function(data){
                            console.log(data)
                            if(data == 'recarga'){
                                /*
                                $.ajax({
                                    url : 'http://www.decop.com/pruebas/interno/AI_Plantilla.php',
                                    method : 'GET',
                                    dataType: 'html',
                                    data: {
                                        destino:'AI_Genera_CFDI.php',
                                        id_factura: $('#folio').val(),
                                        origen: 'intranet'
                                    },
                                    success:function(data){

                                    }
                                }).done(function(){
                                    location.reload(true);
                                })*/

                                location.reload(true);
                            }
                                
                        });
                    }

                }
            }

            $(document).on('focus', '.num_cuenta', function(){
                cliente = cliente.split(' - ')
                cliente = $.trim(cliente[0])

                campo = $(this)

                $(this).autocomplete({
                    source: function(request, response){   
                        $.ajax({
                            url: "{!! route('cuentas-cliente') !!}",
                            method: 'GET',
                            datatype: 'xml',
                            data: {
                                cliente: cliente
                            },
                            success: function(c, textStatus, xhr){
                                response(c);
                                
                            }
                        }).fail(function( jqXHR, textStatus ) { //si la consulta falla se agrega el numero de cuenta del cliente
                            console.log(campo)
                            fp = fila_forma_pago(campo)
                            cuenta = $('.num_cuenta.'+fp).val()
                            if(cuenta != ''){

                                console.log(cliente, cuenta)
                            }
                        })
                    },
                    minLength: 0,
                    select: function(event, ui){
                        valor_mayor()
                    }
                }).focus(function(){
                    $(this).autocomplete("search")

                    valor_mayor()
                })
            })
    </script>
   
@endsection