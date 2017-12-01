//DECLARACION DE VARIABLES
    //tabla de productos
        num_fila = 1;
        var promocion = '';
        var producto;
        comentarios = []
        comment_showed = []
    //cliente
        var cliente = '';
        var tipo_cliente;
        var ubicacion = ''; //direccion de envio en cotizacion
        verifica = false;

    //productos
        get_series = new Array()
        var producto_series = $('.producto-series').val() 
        garantias = []       
        add = new Array()
        autoriza = 0;
        num_forma_pago = 1

    //referencias
        existencia_producto = []

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
        
        f_comment = $('#fila').val()
        comment_found = false
        $.each(comentarios, function(i, c){
            if(f_comment == c.fila){
                comment_found = true
                //return false
            }
        })
        
        cod_series = $('#codigo_series').val()
        f_serie = $('#fila_series').val()
        if(cod_series != ''){
            aux_serie = buscar_series(cod_series)
        }else{
            producto = $('.'+f_comment).parents('tr').attr('class')
            aux_serie = buscar_series(producto)
        }
        
        if(comment_found == true  && aux_serie.length != 0){ //si encuentra serie y comentario
            ocultar_modal()
        }else  if(comment_found == true  && aux_serie.length == 0){ //si encuentra comentario 
            ocultar_modal()
        }else  if(comment_found != true  && aux_serie.length != 0){ //si encuentra serie
            ocultar_modal()
        }else if(comment_found == false  && aux_serie.length == 0){ //si no encuentra serie y comentario
            $('.tbl_num_series tbody').html('')

            if(f_comment != '')
                $('.loading-row.'+f_comment).html('')

            if(f_serie != '')
                $('.loading-row.'+f_serie).html('')

            ocultar_modal()
        }

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

    //GENERALES
    function mostrar_modal(mostrar, ocultar1, ocultar2, ocultar3){
        if(mostrar == 'frmSeries' || mostrar == 'frmComentarios' || mostrar == 'frmAutoriza'){
            $('.modal-sm').addClass('modal-lg');
            $('.modal-lg').removeClass('modal-sm');
            $('.modal-lg').css("width","600px")
            
            //evita que el modal se cierre con esc o al dar clic fuera
            $('.references-modal').modal({
                backdrop: 'static',
                keyboard: false
            })
        }

        if(mostrar == 'frmReferencia'){
            $('.modal-lg').addClass('modal-sm');
            $('.modal-sm').removeClass('modal-lg');
            $('.modal-sm').removeAttr('style')

            $('.references-modal').modal({
                backdrop: 'static',
                keyboard: false
            })
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
        $('.mensaje').html('')
        if($('.references-modal').hasClass('in') ){
            $('.references-modal').modal('hide');
            $('.frmComentarios').css('display', 'none')
            $('.frmReferencia').css('display', 'none')
            $('.frmSeries').css('display', 'none')
            $('.frmAutoriza').css('display', 'none')
        }
    }

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

//FUNCIONES REFERENCIAS
        function elimina_de_referencias(codigo, row){

            if(existencia_producto.length != 0){
                $.each(existencia_producto, function(i, p){
                    if(p != undefined){
                        if(p.codigo == codigo){
                            
                            num_f = p.fila.length
                            
                            if(num_f > 1){
                                $.each(p.fila, function(j, f){
                                    if(f.f == row){
                                        p.fila.splice(j, 1)
                                        return false
                                    }
                                })
                            }else{  
                                
                                existencia_producto.splice(i, 1)
                            }
                        }
                    }
                })
            }
            
        }
      
        function eliminar_fila_referencia(ref){
            //recorrer la tabla y buscar la clase que sea igual a la referencia
            $('.frmFactura tbody tr').each(function(index){
                
                if($(this).attr('class') != undefined){

                    clase = $(this).attr('class').split(' ')
                   
                    codigo = clase[0]
                    if((clase[1] == ref && clase[1] != undefined) || clase[0] == ref || clase[2] != undefined){
                        input = $(this).children('td').children('input[type=text]')
                        f = obtener_clase(input)
                        elimina_de_referencias(codigo, f)
                        
                        elimina_fila(input)
                        
                    }
                }
            })

            calcular_totales()
        }
        
        function obtener_ultima_fila(){

            num_filas = $('.frmFactura tbody tr').length;
            
            if(num_filas != 0){
                $('.frmFactura tbody tr:last').children('td').each(function(index){
                    switch(index){
                        case 1:
                            codigo = $(this).children('input[type=text]').val()
                            f = obtener_clase($(this).children('input[type=text]'))
                            break
                    }
                })

                contenido_fila = {
                    codigo : codigo,
                    f : f
                }

                return contenido_fila;
            }else{
                return 0
            }
                
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
            buscar_cotizacion(cliente, tipo_cliente, ubicacion);
        })

        //busca pedidos
        $(document).on('click', '.btnPedido', function(){
            busca_pedido(cliente, ubicacion)
        })

        //busca ordenes de facturacion
        $(document).on('click', '.btnOF', function(){
            busca_ordenes_facturacion(cliente, ubicacion)
        })

        function agrega_fila_orden_factura(datos, ref){ //app
            last_row = obtener_ultima_fila()
            if(last_row.codigo == ''){
                $.each(datos, function(i, d){
                    if(i == 1){
                        if(d.indexOf('SERVICIO') != -1 || d.indexOf('INSTALACION') != -1){ //si contiene la palabra servicio
                            //agrega datos a la fila sin hacer la busqueda de producto
                            f = last_row.f
                            nva_con_codigo(f, datos, d, i, ref)
                            

                        }else{ //si no se encuentra la palabra servicio
                            //hacer un trim a la variable y la separa con split
                            //busqueda de producto con el codigo obtenido
                            f = last_row.f

                            nva_con_codigo(f, datos, d, i, ref)
                            
                        }
                    }

                    if(i>1 && i%3 == 0){ //se buscan series si el valor de i es 1 y i es multiplo de 3
                        //hacer trim y split
                        //busqueda de producto
                        num = nvo_num_fila()
                        nva_fila = 'f'+num;
                        
                        agregar_fila(num)

                        nva_con_codigo(nva_fila, datos, d, i, ref)
                    }
                })

                calcular_totales()
            }else{
                $.each(datos, function(i, d){    

                    if(i == 1){
                        num = nvo_num_fila()
                        nva_fila = 'f'+num;

                        agregar_fila(num)

                        if(d.indexOf('SERVICIO') != -1 || d.indexOf('INSTALACION') != -1){ //si contiene la palabra servicio
                            //agrega datos a la fila sin hacer la busqueda de producto
                            
                            nva_con_codigo(nva_fila, datos, d, i, ref)
                            
                        }else{ //si no se encuentra la palabra servicio
                            //hacer un trim a la variable y la separa con split
                            //busqueda de producto con el codigo obtenido

                            nva_con_codigo(nva_fila, datos, d, i, ref)
                            
                        }
                    }

                    if(i>1 && i%3 == 0){ //se buscan series si el valor de i es 1 y i es multiplo de 3
                        //hacer trim y split
                        //busqueda de producto
                        num = nvo_num_fila()
                        nva_fila = 'f'+num;

                        agregar_fila(num)

                        nva_con_codigo(nva_fila, datos, d, i, ref)
                    }
                                               
                })

                calcular_totales()
            }
        }

        function nvo_num_fila(){ //app
            last_row = obtener_ultima_fila();
            quita_f = last_row.f.replace('f', '')
            num = parseInt(quita_f) + 1;
            nva_fila = 'f'+num;

            return num;
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
            if(v.length >= 2 && v.length <= 3){
                clase = c[i]
            }
        })
        return clase
    }

    $(document).on('click', '.addCodSAT', function(){
        $(this).popover({
            html: true
        })
    })

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
        
        accion1       = '<span class="close addCodSAT f'+num+'" style="padding: 1px 2px; display:none"'+
                            ' data-container="body" data-toggle="popover" data-placement="left">'+
                            '<i class="glyphicon glyphicon-info-sign"></i>'+
                        '</span>'

        accion2       =  '<span class="close addComment f'+num+'" style="padding: 1px 2px; display: none">'+
                            '<i class="glyphicon glyphicon-comment"></i>'+
                        '</span>'

        accion3       =  '<span class="close btnElimina f'+num+'" style="padding: 1px 2px">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</span> '

        $('.frmFactura').append(
            '<tr>'+
                '<td style="width:3%">'+load+'</td>'+
                '<td style="width:20%">'+codigo+'</td>'+
                '<td style="width:12%">'+cantidad+'</td>'+
                '<td style="width:21%">'+promocion+'</td>'+
                '<td style="width:9%">'+precio+'</td>'+
                '<td style="width:7%">'+moneda+'</td>'+
                '<td style="width:6%">'+descuento+'</td>'+
                '<td style="width:10%">'+precio_venta+'</td>'+
                '<td style="width:10%">'+monto+'</td>'+
                '<td style="width:1%">'+accion1+'</td>'+
                '<td style="width:1%">'+accion2+'</td>'+
                '<td style="width:1%">'+accion3+'</td>'+

            '</tr>'
        )

        $('.f'+num+'.codigo').focus()

        num_fila++;
    }

    function elimina_fila(tr, codigo){
        if(codigo == '' || codigo == undefined){
            $(tr).parents('tr').remove()
        }else{

            f = obtener_clase(tr)
            clase = 'PROM'+f+codigo
            $('.frmFactura tbody tr').each(function(i){
                aux = $(this).attr('class')
                if(aux.indexOf(clase) != -1){
                    $('.'+clase).remove()
                }
            })
        }

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
            num_fila = 0
            comentarios = []
            get_series = []
            
            agregar_fila(num_fila)
            
            $('.btns').html(
                '<a class="btn btn-primary addProduct"s>Agregar Producto</a> '+
                '<a class="btn btn-info notProduct">Agregar Comentario</a>'
            )
            
            $('.frmFormaPago').css('display', 'none')
        }       

        $('.inputFormaPago').html('')
        num_forma_pago = 0
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
        producto = $(this).parents('tr').attr('class')
        f = obtener_clase(this)
        codigo = producto.split(' ')

        elimina_fila(this)
        elimina_fila(this, codigo[0])    
        

        if(producto != undefined){
            

            elimina_de_referencias(codigo[0], f)

            //elimina fila de comentarios
            $('.comment'+producto).remove()

            elimina_comentario(producto, f, 'quitar')

            calcular_totales()
        }
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
        if(num >= 0 ){
            last = obtener_ultima_fila()

            if(last != 0){
                num = last.f.replace('f', '')

                $('.f'+num+'.codigo').attr('disabled', 'disabled')
            }
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
        codigo = $(this).parents('tr').attr('class')

        nvo_actualiza_existencia(codigo, cantidad, f)       

        if(series == 'V' && producto_series == 'S'){
            if(cantidad != 0){

                disp_series = series_disponibles(codigo)

                if(parseInt(disp_series) < parseInt(cantidad)){
                    alert('El número de series disponibles es menor al el de productos que está ingresando. Se mostraran únicamente las series disponibles.')
                
                    $(this).val(disp_series)
                    nvo_actualiza_existencia(codigo, disp_series, f)  

                    if(disp_series != 0)
                        formulario_series_venta(f, codigo, disp_series)
                    else
                        descuento = $('.'+f+'.descuento').val()
                        monto = obtener_monto(descuento, f)
                        $('.'+f+'.monto').html(
                            '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                        )

                        calcular_totales()

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
                //alert("Positive integers only"); 
                //this.value = ""; 
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

    function agrega_producto_existencia(articulo, f){ //app
        producto = {
            codigo: articulo.codigo,
            cantidad_original: articulo.existencia,
            fila : [
                {
                    cantidad: parseInt(articulo.cantidad),
                    f: f
                }
            ]
        }

        no_agrega = false

        if(producto.cantidad_original < 1 || producto.cantidad_original == null){
            //buscar en otras sucursales

            alert('El producto '+articulo.descripcion+' tiene una existencia de 0 en la sucursal '+articulo.sucursal)
            no_agrega = true 
            elimina_fila($('.'+f))
        }
        existe = en_existencia(articulo, f)

        if(existe == false && producto.cantidad_original != 0 && producto.cantidad_original != null){
            existencia_producto.push(producto)
        }

        return no_agrega
    }


    function en_existencia(articulo, row){
        exist = false
        $.each(existencia_producto, function(i, p){
            if(p.codigo == articulo.codigo){
                exist=true

                cont = 0
                aux_exist = 0
                $.each(p.fila, function(i, f){
                    if(f.f != row){
                        cont++;
                        aux_exist += f.cantidad  
                    }else{
                        cont--
                    }
                })

                if(cont != 0){
                    if(p.cantidad_original == aux_exist){
                        alert('La cantidad de producto '+p.codigo+' es igual a la existencia, no se puede agregar una cantidad mayor.')
                        
                        elimina_fila($('.'+row))
                    }else{
                        fila = {
                            cantidad: parseInt(articulo.cantidad),
                            f: row
                        }

                        p.fila.push(fila)

                    }
                        
                }
                
                return false
            }                   
        })

        return exist
    }

    function nvo_actualiza_existencia(codigo, cantidad, row){

        $.each(existencia_producto, function(i, p){
            if(p.codigo == codigo || codigo.indexOf(p.codigo) != -1){
                sum = 0
                sub_sum = 0
                $.each(p.fila, function(i, f){
                    if(f.f == row){
                        f.cantidad = parseInt(cantidad)
                        sum += f.cantidad
                    }else{
                        sub_sum += f.cantidad
                    }
                })

                total = sum + sub_sum
                if(total > p.cantidad_original){
                    nva_cant = p.cantidad_original - sub_sum
                    nvo_actualiza_existencia(codigo, nva_cant, row)

                    alert('La cantidad de producto '+codigo+' es mayor a la existencia ('+p.cantidad_original+'), no es posible agregar una cantidad mayor.')
                
                    $('.'+row+'.cantidad').val(nva_cant)
                }
                
            }
        })
    }
    
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
                cont = 0
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

                    //get_series = []
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
                }, 4000)
                    
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
            $('#fila_series').val(f)
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

            if(autoriza != undefined){// && clave != '' || clave.length != 0){
                $('.mensaje').html(
                    '<div class="alert alert-success alert-dismissible" role="alert">'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                        'La clave de autorización correcta.'+
                    '</div>'
                );
                $('.autoriza.'+producto_row).val(autoriza)

                nvo_min = parseFloat($('.'+producto_row+'.precio').val())
                $('.'+producto_row+'.precio').attr('min', nvo_min)
                descuento = $('.'+producto_row+'.descuento').val()

                setTimeout(function(){
                    $('.mensaje').html('')
                    $('.fila-producto').val('')
                    $('.clave_autoriza').val('')
                    $('.'+producto_row+'.promocion').val('')

                    tipo_cambio = $('.'+producto_row+'.moneda').text()
                    t = tipo_cambio.split('/')
                    tipo_cambio = t[1]
                    
                    precio_venta = parseFloat(nvo_min * tipo_cambio);

                    $('.'+producto_row+'.precio-venta').html(
                        '$ '+formatNumber.new(parseFloat(precio_venta).toFixed(2))
                    )

                    monto = obtener_monto(descuento, producto_row)

                    $('.'+f+'.monto').html(
                        '$ '+formatNumber.new(parseFloat(monto).toFixed(2))
                    )

                    calcular_totales()

                    ocultar_modal()
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
                    $('.'+producto_row+'.promocion').val('')

                    tipo_cambio = $('.'+producto_row+'.moneda').text()
                    t = tipo_cambio.split('/')
                    tipo_cambio = t[1]
                    
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
                //alert("Positive integers only"); 
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
            precio = $(this).val()
            
            if(parseFloat(precio) < parseFloat(precio_minimo)){
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

        if(comment_showed.length != 0){
            //agrega los comentarios a la fila que se agrego
            $('.comment'+producto+' td .comments').html(
                '<label>Comentarios: </label><br>'
            )
            $.each(comment_showed, function(i, c){
                $('.comment'+producto+' td .comments').append(
                    '- '+c+'<br>'
                )
            })
        }

        //agregar series
        serie_row = []
        serie_row = buscar_series(producto)
    
        if(serie_row.length != 0){
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
        }
            

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

    $(document).on('click', '.formaPago', function(){
        total = $('#total').val()
        total = total.replace(/,/g, '');
        total = parseFloat(total)

        subtotal = 0;

        $('.total-formas').children('input').each(function(index){                    
            if($(this).val() != '')
                subtotal = parseFloat(subtotal) + parseFloat($(this).val())          
        })

        nvo_total = total - subtotal

        if(nvo_total > 0){
            if(num_forma_pago < 10){
                $('.inputFormaPago').append(
                    '<div class="row">'+
                        '<div class="col-md-6 form-group formas">'+
                            '<select name="forma_pago" class="form-control input-sm forma-pago fp'+num_forma_pago+'">'+
                                '<option value="">- Seleccione -</option>'+
                                '<option value="30">Aplicación de anticipos</option>'+
                                '<option value="01">Efectivo</option>'+
                                '<option value="02">Cheque</option>'+
                                '<option value="03">Transferencia Electrónico de fondos</option>'+
                                '<option value="04">Tarjeta de Credito</option>'+
                                '<option value="28">Tarjeta de Debito</option>'+
                                '<option value="99">Por definir</option>'+
                            '</select>'+
                        '</div>'+

                        '<div class="col-md-5 form-group total-formas">'+
                            '<input type="text" name="cantidad_pago" class="form-control input-sm cantidad-pago fp'+num_forma_pago+'" />'+
                        '</div>'+

                        '<div class="col-md-1">'+
                            '<span class="close btnEliminaFormaPago fp'+num_forma_pago+'" style="padding: 1px 2px;display:none">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</span> '+
                        '</div>'+
                    '</div>'
                )

            
                num_forma_pago++;
            }
        }
    })

    $(document).on('change', '.forma-pago', function(){
        tot_forma = $('.total-formas').children('input').length

        total = $('#total').val()
        total = total.replace(/,/g, '');
        total = parseFloat(total)

        subtotal = 0;

        $('.total-formas').children('input').each(function(index){
            //if($(this).val() != '' && (tot_forma-1) != index){
            if($(this).val() != ''){
                subtotal = parseFloat(subtotal) + parseFloat($(this).val())
                
            }
        })

        tot_forma = $('.total-formas').children('input').length

        $('.total-formas').children('input').each(function(index){
        
                f = obtener_clase($(this))
                nvo_total = total - subtotal

                if(index+1 == tot_forma){
                    $('.cantidad-pago.'+f).val(nvo_total.toFixed(2))
                    $('.cantidad-pago.'+f).attr('max', nvo_total.toFixed(2))
                }else{
                    $('.forma-pago.'+f).attr('disabled', 'disabled')
                    $('.cantidad-pago.'+f).removeAttr('max')
                    $('.cantidad-pago.'+f).attr('disabled', 'disabled')
                    $('.btnEliminaFormaPago.'+f).css('display', 'block')
                }
            
        })
        
    })

    $(document).on('focus', '.cantidad-pago', function(){
        max = parseFloat($(this).attr('max'))
        $(this).keyup(function(){
            cantidad = parseFloat($(this).val())

            if(cantidad > max){
                $(this).val(max)
            }
        })
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

    $(document).on('click', '.btnEliminaFormaPago', function(){
        f = obtener_clase($(this))
        $('.'+f).remove()
    })
    

