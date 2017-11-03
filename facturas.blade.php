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
                {!!Form::hidden('ref', 0, ['class'=>'ref-hide'])!!}
                {!!Form::text('campo-ref', null, ['class'=>'form-control input-sm campo-ref'])!!}
                {!!Form::select('referencias', [], null, ['class'=>'form-control input-sm referencias', 'multiple', 'id'=>'referencias'])!!}
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
    <script src="../../resources/views/ventas/app.js"></script>

    <script>
        //CLIENTE
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
        
        //REFERENCIAS
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
                                    ''
                                );

                                $.each(c, function(i, v){
                                    $('.referencias').append(
                                        '<option value="'+v.id_cotizacion+'">'+
                                            v.id_cotizacion+
                                        '</option>'
                                    )
                                })

                                $('.referencias').css('display', 'block');

                                $('.referencias').chosen()
                                $('.chosen-container-multi').css('width', '100%')
                                
                                $('.referencias').focus()

                                //al seleccionar una referencia se van a obtener los datos                                
                                $(document).on('change', '.referencias', function(e, params){
                                    if(params.deselected){ 

                                    }else{ 
                                        

                                        refSelected = params.selected

                                        $.each(c, function(i, v){
                                            if(refSelected == v.id_cotizacion){
                                                numFila = $(".frmFactura tbody tr").length;
                    
                                                agregar_datos_cotizacion(v, f, numFila)
                                            }
                                        })
                                    }
                                })
                            }
                                
                        }
                    }
                })
            }

        //PRODUCTO
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

                        console.log(duplicado, f)
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

            //busca productos seleccionado
            function busca_producto(url, codigo, row, precio=0){
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

                                $('.'+row+'.codigo').val(p.producto.codigo+' - '+p.producto.descripcion)

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
                                
                                if(precio == 0){
                                    $('.'+row+'.precio').val(p.producto.precio)
                                    $('.'+row+'.precio').attr('min', p.producto.precio)
                                    precio_venta = parseFloat(p.producto.precio) * parseFloat(p.producto.tipo_cambio)

                                }else{
                                    $('.'+row+'.precio').val(precio)
                                    $('.'+row+'.precio').attr('min', precio)
                                    precio_venta = parseFloat(precio) * parseFloat(p.producto.tipo_cambio)
                                }

                                $('.'+row+'.moneda').html(p.producto.moneda_venta+'/'+p.producto.tipo_cambio)
                                
                                $('.'+row+'.descuento').val(0)

                                
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
   
        // PRUEBAS
            function agregar_datos_cotizacion(datos, f, numFila){
                
                //recorrer tabla 
                $('.frmFactura tbody tr').each(function(index){
                    

                    if(index+1 == numFila){
                        $(this).children('td').each(function(index2){
                            switch(index2){
                                case 1:
                                    codigo = $(this).children('input[type="text"]').val()
                                    f = obtener_clase($(this).children('input[type="text"]'))

                                    if(codigo == ''){

                                        if(datos.producto != 'MULTIPLE' && datos.relacionados == ''){
                                            //f = obtener_clase($(this).children('input[type="text"]'))
                                            busca_producto('{!! route('ventas-productos') !!}', datos.producto, f, datos.precio) 
                                        }else if(datos.producto != 'MULTIPLE' && datos.relacionados != ''){
                                            num = parseInt(f[1]) + 1
                                            nva_fila = 'f'+num;
                                            //f = obtener_clase($(this).children('input[type="text"]'))
                                            busca_producto('{!! route('ventas-productos') !!}', datos.producto, f, datos.precio)

                                            //buscar accesorios que se encuentren en la cotizacion
                                            series_relacionados = datos.relacionados.split('|')

                                            aux_relacionados = ''
                                            $.each(series_relacionados, function(i, r){
                                                if(r != ''){
                                                    relacionados = r.split(',');
                                                    //aux_relacionados.push(relacionados[0])
                                                    aux_relacionados += '"'+relacionados[0]+'"'+ ','
                                                }
                                                    
                                            })
                                            aux_relacionados = aux_relacionados.slice(0, -1)
                                            

                                            accesorio = obtener_accesorios(aux_relacionados)
                                            
                                            $.each(accesorio, function(i, a){
                                            
                                                $.each(series_relacionados, function(i, sr){
                                            
                                                    if(sr.indexOf(a.id_producto) != -1){
                                                        precio = sr.split(',')
                                                        precio_relacionado = precio[1]
                                                        agregar_fila(num)
                                                        busca_producto('{!! route('ventas-productos') !!}', a.id_producto, nva_fila, precio_relacionado)
                                            
                                                    }
                                                })
                                            })

                                        }

                                        if(datos.producto == 'MULTIPLE'){

                                        }
                                        
                                    }else{
                                        //f = obtener_clase($(this).children('input[type="text"]'))
                                        num = parseInt(f[1]) + 1
                                        nva_fila = 'f'+num;

                                        if(datos.producto != 'MULTIPLE' && datos.relacionados == ''){
                                            //f = obtener_clase($(this).children('input[type="text"]'))
                                            agregar_fila(num)
                                            busca_producto('{!! route('ventas-productos') !!}', datos.producto, nva_fila, datos.precio) 

                                        }else if(datos.producto != 'MULTIPLE' && datos.relacionados != ''){
                                            //f = obtener_clase($(this).children('input[type="text"]'))
                                            agregar_fila(num)
                                            busca_producto('{!! route('ventas-productos') !!}', datos.producto, nva_fila, datos.precio)

                                            
                                        }

                                        if(datos.producto == 'MULTIPLE'){
                                            
                                        }
                                    }
                                    break;
                            }
                            
                        })
                    }
                    
                })    
                
            }

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
    </script>
   
@endsection