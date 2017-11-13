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
        verifica = false;

    //productos
        get_series = new Array()
        var producto_series = $('.producto-series').val() 
        garantias = []       
        add = new Array()
        autoriza = 0;
        num_forma_pago = 0

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

        if(keyCode == 121){
            cliente = $('#cliente').val()
            if(cliente != ''){
                verificar_datos_factura()    
            }else{
                alert('No es posible generar la factura.')
            }
            
        }

        if($('#cliente').is(':focus')){
            $('#cliente').focus()    
        }
    });

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
        function eliminar_fila_referencia(ref){
            //recorrer la tabla y buscar la clase que sea igual a la referencia
            $('.frmFactura tbody tr').each(function(index){
                
                if($(this).attr('class') != undefined){

                    clase = $(this).attr('class').split(' ')
                    if(clase[1] == ref){
                        $(this).remove()
                    }
                }
            })

            calcular_totales()
            //obtener numero de filas
            numFila = $(".frmFactura tbody tr").length
            
            if(numFila == 0){
                agregar_fila(numFila)
                $('.notProduct').css('display','inline-block')

                comentarios = []
                get_series = []

                $('.frmFormaPago').css('display', 'none')
                //resetear valores de subtotal, iva y total
                //agregar boton de comentario
            }
        }
        
        function obtener_ultima_fila(){
           $('.frmFactura tbody tr:last').children('td').each(function(index){
                switch(index){
                    case 1:
                        codigo = $(this).children('input[type="text"]').val()
                        f = obtener_clase($(this).children('input[type="text"]'))
                        break
                }
            })

            contenido_fila = {
                codigo : codigo,
                f : f
            }

            return contenido_fila;
        }

        //busca los accesorios del campo relacionados del producto de la cotizacion
        function buscar_relacionados(datos){
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

            return accesorio
        }

        //busca cotiazciones
        $(document).on('click', '.btnCotizacion', function(){
            buscar_cotizacion(cliente, ubicacion);
        })

        //busca pedidos
        $(document).on('click', '.btnPedido', function(){
            busca_pedido(cliente, ubicacion)
        })

        //busca ordenes de facturacion

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
            if(v.length >= 2 && v.length <= 3){
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
        if(num > 1 ){
            last = obtener_ultima_fila()

            num = last.f.replace('f', '')

            $('.f'+num+'.codigo').attr('disabled', 'disabled')
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

    //busca en la tabla si ya existe un valor 
    function buscar_duplicado(codigo){
        aux_codigo = ''
        row_duplicado = '';
        $('.frmFactura tbody tr').each(function(index){
            
            clase =  $(this).attr('class')

            
            if(clase == codigo){
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
            }  

            
        })
        return row_duplicado
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
    //fin de formulario de ventas
    
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

    //cambiar precio
    $(document).on('focus', '.precio', function(){
        $(this).numeric(
            {
                negative: false 
            }, 
            function() { 
                alert("Positive integers only"); 
                //this.value = ""; 
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
    
    //cambiar descuento
    $(document).on('focus', '.descuento', function(){
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
    
               
//PRUEBAS SIN TERMINAR
    $(document).on('click', '.btnEditarSeries', function(){
        f = $(this).attr('data-row')
        producto = $(this).attr('data-producto')
        //producto = $('.'+f+'.cantidad').parents('tr').attr('class')
        cantidad = $('.'+f+'.cantidad').val()

        formulario_series_venta(f, producto, cantidad)
    })
    

