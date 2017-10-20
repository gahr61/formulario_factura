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

    </style>

    <form>

        <div  class="modal fade references-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm ui-front" role="document">
                <div class="modal-content" >
                    <div class="modal-body">
                        <div class="list-group frmReferencia" style="display: none">
                            <button type="button" class="list-group-item btnCotizacion" data-dismiss="modal" >Cotización</button>
                            <button type="button" class="list-group-item" data-dismiss="modal">Pedido</button>
                            <button type="button" class="list-group-item" data-dismiss="modal">Orden Facturación</button>
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
                                        <span class="btn btn-default">Cancelar</span>
                                    </div>
                                </div>    
                                    
                            </div>
                        </div>

                        <div class="frmSeries" style="display: none"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4  form-group">
                {!!Form::label('folio', 'Folio', ['class'=>'col-md-4'])!!}
                <div class="col-md-8">
                    {!!Form::text('folio', $consecutivo + 1, ['class'=>'form-control input-sm', 'required'])!!}    
                </div>
                
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('fecha', 'Fecha', ['class'=>'col-md-4'])!!}
                <div class="col-md-8">
                    {!!Form::text('fecha', $fecha, ['class'=>'form-control input-sm', 'required'])!!}
                    </div>
            </div>
            <div class="col-md-4 form-group">
                {!!Form::label('dias_credito', 'Dias de credito', ['class'=>'col-md-6'])!!}
                <div class="col-md-6">
                    {!!Form::select('dias_credito', [], null, ['class'=>'form-control'])!!}    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                {!!Form::label('cliente', 'Cliente')!!}
                <div class="loading-cliente"></div>
                {!!Form::text('cliente', null, ['class'=>'form-control input-sm cliente', 'required', 'autofocus'])!!}
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('direccion_envio', 'Direccion de envio')!!}
                {!!Form::select('direccion_envio', [''=>'- Seleccione -'], null, ['class'=>'form-control input-sm', 'required'])!!}
            </div>

            <div class="col-md-4 form-group">
                {!!Form::label('vendedor', 'Vendedor')!!}
                {!!Form::select('vendedor', [''=>'- Seleccione -'], null, ['class'=>'form-control input-sm vendedor', 'required'])!!}
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
                {!!Form::select('referencias', [''=>'- Seleccione -'], null, ['class'=>'form-control input-sm referencias', 'required'])!!}
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12  form-group">
                <fieldset>
                    <legend>Productos</legend>
                    
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

                    <div class="row form-group">
                        <div class="col-md-8">
                            <div class="form-group btns">
                                <a class="btn btn-primary addProduct"s>Agregar Producto</a> 
                                <a class="btn btn-info notProduct">Agregar Comentario</a> 
                            </div>
                            <div class="col-md-6">
                                {!!Form::label('forma_pago', 'Forma de pago')!!}
                                {!!Form::select('forma_pago', ['Efectivo'=>'Efectivo', 'Tarjeta de Credito/Debito', 'Cheque'=>'Cheque', 'Transferencia'=>'Transferencia', 'Credito'=>'Credito'], null, ['class'=>'form-control'])!!}
                            </div>    
                        </div>
                        
                        <div class="col-md-4">
                            <hr>
                            <div class="operaciones">
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
                </fieldset>
            </div>   
        </div>

    </form>

@endsection

@section('scripts')
    
    <script src="{{ asset('plugins/jquery-3.2.1/jquery-3.2.1.min.js') }}"></script>
    
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

                            mostrar_modal('frmReferencia', 'frmSeries', 'frmComentarios')

                            
                        }

                    }
                }  

                if($('#cliente').is(':focus')){
                    $('#cliente').focus()    
                }
            });

            function mostrar_modal(mostrar, ocultar1, ocultar2){
                if(mostrar == 'frmSeries' || mostrar == 'frmComentarios'){
                    $('.modal-sm').addClass('modal-lg');
                    $('.modal-lg').removeClass('modal-sm');
                    $('.modal-lg').css("width","600px")    
                }

                if(mostrar == 'frmReferencia'){
                    $('.modal-lg').addClass('modal-sm');
                    $('.modal-sm').removeClass('modal-lg');
                    $('.modal-sm').removeAttr('style')    
                }
                
                if($('.references-modal').hasClass('in') ){
                    $('.'+mostrar).css('display', 'none')
                    $('.'+ocultar1).css('display', 'none')
                    $('.'+ocultar2).css('display', 'none')

                    $('.references-modal').modal('hide');

                }else if(!$('.references-modal').hasClass('in') ){
                    $('.references-modal').modal('show');

                    $('.'+mostrar).css('display', 'block')
                    $('.'+ocultar1).css('display', 'none')
                    $('.'+ocultar2).css('display', 'none')

                }
            }

        //FUNCIONES CLIENTE
            //loading cliente
            verifica = false;
            $(document).on('focus', '#cliente', function(){
                cliente = $(this).val()
                verifica = verificar_tabla_producto()
                no_autocomplete = false;
                
                $(this).keydown(function(e){
                    if(verifica){
                        mensaje = confirm('¿Realmente desea cambiar de usuario?')
                        
                        if(mensaje){
                            verifica = false

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
                        if(cliente.length > 2){
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
                        $('#cliente').val(ui.item.id_cliente)

                        llenar_popover('datos_cliente', 'cliente_over', 'popover-cliente', 'Facturado a', ui.item);

                        cliente = ui.item.id_cliente

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
                                    
                                    $('.campo-ref').css('display', 'block')
                                    $('.referencias').css('display', 'none')
                                    $('.campo-ref').focus()
                                }
                            })

                        //agregar fila de producto
                        //eliminar_fila(input)
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
                //agregar una nueva fila
                load         = '<div class="loading-producto f'+num+'"></div>'+
                                '<div class="loading-comment f'+num+'"></div>'
                codigo       =  '<input type="hidden" class="pedir-series f'+num+'" />'+
                                '<input type="text" class="form-control input-sm f'+num+' codigo" style="width: 100%" />'
                cantidad     =  '<div class="input-group" >'+
                                    '<input type="text" class="form-control input-sm f'+num+' cantidad" />'+
                                    '<span class="input-group-addon">PZA</span>'+
                                '</div>';
                promocion    =   '<select class="form-control f'+num+' promocion " style="width: 100%">'+
                                    '<option value="" selected>- Seleccionar -</option>'+
                                '</select>';
                precio       =  '<input type="text" class="form-control input-sm f'+num+' precio" style="width:100%" />'
                moneda       =  '<div class="f'+num+' moneda" style="width:100%"></div>'
                descuento    =  '<input type="text" class="form-control input-sm f'+num+' descuento" style="width:100%" />'
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
                        '<td>'+load+'</td>'+
                        '<td>'+codigo+'</td>'+
                        '<td>'+cantidad+'</td>'+
                        '<td>'+promocion+'</td>'+
                        '<td>'+precio+'</td>'+
                        '<td>'+moneda+'</td>'+
                        '<td>'+descuento+'</td>'+
                        '<td>'+precio_venta+'</td>'+
                        '<td>'+monto+'</td>'+
                        '<td>'+accion+'</td>'+
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
                }else if(nFilas == 1 && $('.f'+nFilas+'.codigo').val().length != 0 && $('.f'+nFilas+'.codigo').val().length != 'undefined'){
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

                //agregar_fila(num_fila)
            })

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

                    if($(this).val().length > 1){
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
                        
                        if(duplicado){
                            aux_cantidad = $('.cantidad.'+duplicado).val()
                            cantidad = parseInt(aux_cantidad) + 1
                            $('.cantidad.'+duplicado).val(cantidad)

                            descuento = $('.'+duplicado+'.descuento').val()
                            monto = obtener_monto(descuento, duplicado)
                            $('.'+duplicado+'.monto').html(
                                '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                            )
                            
                            calcular_totales()

                            elimina_fila(input)
                            agregar_fila(num_fila)
            
                            $('.'+f+'.codigo').html('')

                        }else{
                            elimina_comentario(codigo, f, 'cambio_primera')

                            $(input).keydown(function(){
                                $('.loading-comment.'+f).html('')
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
                aux_codigo = []
                row_duplicado = '';
                $('.frmFactura tbody, tr').each(function(index){
                    
                    $(this).children('td').each(function(index2){
                        switch(index2){
                            case 1:
                                //aux_codigo.push($(this).children('input').val());
                                aux_codigo = $(this).children('input').val();
                                row = $(this).parents()

                                if(aux_codigo.indexOf(codigo) != -1){
                                
                                    row_duplicado = obtener_clase($(this).children('input'))
                                    
                                    return false;
                                }
                                break;
                        }
                    })
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
                                $('.loading-producto.'+row).css('visibility', 'hidden')

                                $('.'+row+'.cantidad').val(p.producto.cantidad)

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
                            }
                        }

                        
                    }

                })
            }

            //cambiar cantidad
            $(document).on('focus', '.cantidad', function(){
                f = obtener_clase(this)

                $(this).keyup(function(){
                    cantidad = $(this).val()
                    series = $('.pedir-series.'+f).val()

                    if(cantidad != ''){
                        if(series == 'V'){
                            articulo = $('.'+f+'.codigo').val()
                            cantidad = $('.'+f+'.cantidad').val()
                            $('.frmSeries').html(
                                '<div class="container-fluid">'+
                                    '<div class="form-group">'+
                                        '{!!Form::label('cantidad-series', 'Numero de Series')!!}'+
                                        ' [ '+cantidad+' ] '+
                                    '</div>'+
                                    '<div class="form-group">'+
                                        '<label>Articulo</label> '+articulo+
                                    '</div>'+
                                    '<label>Series</label> '+
                                    '<div class="row form-group">'+
                                        '<div class="num_series col-md-8 col-md-offset-2"></div>'+
                                    '</div>'+

                                    '<div class="row form-group">'+
                                        '<div class="col-md-2">'+
                                            '<span class="btn btn-primary btnAddComment">Agregar</span>'+
                                        '</div>'+
                                        '<div class="col-md-2">'+
                                            '<span class="btn btn-default">Cancelar</span>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'
                            )

                            $('.num_series').html('')
                            for(i=1; i<=cantidad; i++){
                                $('.num_series').append(
                                    '<input type="text" id="serie'+i+'" class="form-control input-sm" /><br>'
                                )
                            }

                            mostrar_modal('frmSeries', 'frmComentarios', 'frmReferencia')
                        }
                                                
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
            })

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
                $('.comentario').focus()

                $('.loading-producto.'+f).css('display', 'none')
                oculta_comentario(producto, f)

                mostrar_modal('frmComentarios', 'frmReferencia', 'frmSeries')
                
            })    

            $(document).on('click', '.btnAddComment', function(){
                fila = $('#fila').val()
                comentario = $('.comentario').val()

                //mostrar modal
                if($('.references-modal').hasClass('in') ){
                    $('.references-modal').modal('hide');
                    $('.frmComentarios').css('display', 'none')
                    
                }

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
                $('.loading-comment.'+fila).html(
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
                

                fila_comment = '<tr class="comment'+producto+'">'
                                    '<td colspan="10">1</td>'+
                                '</tr>'

                //inserta nueva fila a la fabla
                var newRow = $(
                        '<tr class="comment'+producto+'">'+
                           '<td colspan="10"></td>'+
                        '</tr>'
                );
                newRow.insertAfter($('.'+producto));

                //busca los comentarios por producto
                comment_showed = []
                buscar_en_comentarios(producto, f)

                //agrega los comentarios a la fila que se agrego
                $('.comment'+producto+' td').html('')
                $.each(comment_showed, function(i, c){
                    $('.comment'+producto+' td').append(
                        '- '+c+'<br>'
                    )
                })

                //cambiar icono de + a -
                $('.loading-comment.'+f).html(
                    '<span class="close hideComment '+f+'" style="padding: 1px 2px">'+
                        '<i class="glyphicon glyphicon-minus"></i>'+
                    '</span>'
                )
            })

            function oculta_comentario(producto, f){
                $('.comment'+producto).remove()

                //cambiar icono de + a -
                $('.loading-comment.'+f).html(
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
            
        })
    </script>
@endsection