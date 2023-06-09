jQuery(document).ready(function() {
   
    "use strict";

    jQuery('.hidden').html('');

    // Tooltip
    jQuery('.tooltips').tooltip({ container: 'body'});

    // Popover
    jQuery('.popovers').popover();

    // Datepicker
    jQuery('.datepicker').datepicker();

    // wysihtml5
    // jQuery('.wysihtml5').wysihtml5({color: false,html:false});

    // CKEditor
    jQuery('.text-area2').ckeditor();

    // Tags Input
    jQuery('#tags').tagsInput({width:'auto'});

   // Máscaras
    jQuery(function(){
        jQuery('.date').mask("00/00/0000", {placeholder: "__/__/____", clearIfNotMatch: true});      
        jQuery('.cpf').mask('000.000.000-00', {placeholder: "___.___.___-__", clearIfNotMatch: true});
        jQuery('.cnpj').mask('00.000.000/0000-00', {placeholder: "__.___.___/____-__", clearIfNotMatch: true});
        jQuery('.cep').mask('00000-000', {placeholder: "_____-__", clearIfNotMatch: true});
        jQuery('.hour').mask('00:00', {placeholder: "__:__", clearIfNotMatch: true});
        jQuery('.hour_full').mask('00:00:00', {placeholder: "__:__:__", clearIfNotMatch: true});
        jQuery('.money').mask('000.000.000.000.000,00', {reverse: true});
        jQuery('.bonificacao').mask('000.000.000.000.000,0000', {reverse: true});
        jQuery('.bonificacao_reputacao').mask('0,00', {reverse: true});
        jQuery('.bonificacao_promo').mask('0,00', {reverse: true});
        jQuery('.km').mask('000.00', {reverse: true});        
        jQuery('.numero').mask('0000000');
        jQuery('.idade').mask('00');

        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.fone').mask(SPMaskBehavior, spOptions);

        var maskCpfCnpj = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '000.000.000-009' : '00.000.000/0000-00';
        },
        options = {onKeyPress: function(val, e, field, options) {
                field.mask(maskCpfCnpj.apply({}, arguments), options);
            }
        };

        $('#cpfcnpj').mask(maskCpfCnpj, options);

    });

    jQuery(".date").attr('autocomplete', 'off');

    // Função para modificar período
    jQuery('#periodo').change(function(event) {
        var periodo = $(this).val();
        var data = $(this).find(':selected').data('period');

        if (periodo === 'yesterday') {
            $('#data_ini').val(data);
            $('#data_fim').val(data);
        } else if (periodo === 'custom') {        
            $('#data_ini').val('');
            $('#data_fim').val('');
        } else {
            $('#data_ini').val(data);
            $('#data_fim').val($('#periodo option[value="today"]').data('period'));
        }

    });

    // // Função mostra esconde inputs cadastro gamification
    // jQuery(function($) {
    //     $('#tipo_gamification').on('change', function() {
    //         var tipo = $(this).val();

    //         if (tipo == 1) { // Consumo Grupos
    //             $('#consumo_grupos').show();
    //             $('#consumo_produto').hide();
    //         } else if (tipo == 2) { // Consumo Produto - Unidade
    //             $('#consumo_produto').show();
    //             $('#consumo_grupos').hide();
    //             $('#consumo_unidade').show();
    //             $('#consumo_valor').hide();
    //         } else if (tipo == 3) { // Consumo Produto - Valor
    //             $('#consumo_produto').show();
    //             $('#consumo_grupos').hide();
    //             $('#consumo_valor').show();
    //             $('#consumo_unidade').hide();
    //         }
    //     }).trigger('change');
    // });

    // // Função mostra esconde inputs cadastro gamification postos
    // jQuery(function($) {
    //     $('#tipo_gamification_postos').on('change', function() {
    //         var tipo = $(this).val();

    //         if (tipo == 1) { // Consumo Grupos
    //             $('#consumo_valor').hide();
    //             $('#consumo_unidade').show();
    //         } else if (tipo == 2) { // Consumo Valor
    //             $('#consumo_unidade').hide();
    //             $('#consumo_valor').show();
    //         }
    //     }).trigger('change');
    // });

    // jQuery(function($) {
    //     $('#tipo_premio').on('change', function() {
    //         var tipo_premio = $(this).val();

    //         if (tipo_premio == 1) { // Bonificação
    //             $('#premio_bonificacao').show();
    //             $('#premio_produtos').hide();
    //         } else if (tipo_premio == 2) { // Produto(s)
    //             $('#premio_produtos').show();
    //             $('#premio_bonificacao').hide();
    //         }
    //     }).trigger('change');
    // });

    // jQuery('#tipo_gamification').change(function(event) {
    //     var tipo = $(this).val();

    //     if (tipo == 1) { // Consumo Grupos
    //         $('#consumo_grupos').show();
    //         $('#consumo_produto').hide();
    //     } else if (tipo == 2) { // Consumo Produto - Unidade
    //         $('#consumo_produto').show();
    //         $('#consumo_grupos').hide();
    //         $('#consumo_unidade').show();
    //         $('#consumo_valor').hide();
    //     } else if (tipo == 3) { // Consumo Produto - Valor
    //         $('#consumo_produto').show();
    //         $('#consumo_grupos').hide();
    //         $('#consumo_valor').show();
    //         $('#consumo_unidade').hide();
    //     }

    // });

    jQuery('#data_ini, #data_fim').datepicker().on('input change', function() { 
        $('#periodo option[value="custom"]').attr('selected', 'selected');
        $("select#periodo").select2(); 
    });

    // Select2
    jQuery(".select-basic, .select-multi").select2({width: '100%'});
    jQuery('.select-search-hide').select2({
        width: '100%',
        minimumResultsForSearch: -1
    });
    
    function format(item) {
        return '<i class="fa ' + ((item.element[0].getAttribute('rel') === undefined)?"":item.element[0].getAttribute('rel') ) + ' mr10"></i>' + item.text;
    }
    
    // This will empty first option in select to enable placeholder
    jQuery('select option:first-child').text('');

    jQuery(".select-templating").select2({
        width: '100%',    
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; }
    });  

    jQuery(".select-disabled").select2({disabled: true, width: '100%'});
     
    // Show panel buttons when hovering panel heading
    jQuery('.panel-heading').hover(function() {
        jQuery(this).find('.panel-btns').fadeIn('fast');
    }, function() {
        jQuery(this).find('.panel-btns').fadeOut('fast');
    });
   
    // Close Panel
    jQuery('.panel .panel-close').click(function() {
        jQuery(this).closest('.panel').fadeOut(200);
        return false;
    });
   
    // Minimize Panel
    jQuery('.panel .panel-minimize').click(function(){
        var t = jQuery(this);
        var p = t.closest('.panel');
        if(!jQuery(this).hasClass('maximize')) {
            p.find('.panel-body, .panel-footer').slideUp(200);
            t.addClass('maximize');
            t.find('i').removeClass('fa-minus').addClass('fa-plus');
            jQuery(this).attr('data-original-title','Maximize Panel').tooltip();
        } else {
            p.find('.panel-body, .panel-footer').slideDown(200);
            t.removeClass('maximize');
            t.find('i').removeClass('fa-plus').addClass('fa-minus');
            jQuery(this).attr('data-original-title','Minimize Panel').tooltip();
        }
        return false;
    });
   
    jQuery('.leftpanel .nav .parent > a').click(function() {

        var coll = jQuery(this).parents('.collapsed').length;

        if (!coll) {
            jQuery('.leftpanel .nav .parent-focus').each(function() {
                jQuery(this).find('.children').slideUp('fast');
                jQuery(this).removeClass('parent-focus');
            });

            var child = jQuery(this).parent().find('.children');
            if(!child.is(':visible')) {
                child.slideDown('fast');
                if(!child.parent().hasClass('active'))
                child.parent().addClass('parent-focus');
            } else {
                child.slideUp('fast');
                child.parent().removeClass('parent-focus');
            }
        }
        return false;
    });
  
    // Menu Toggle
    jQuery('.menu-collapse').click(function() {
        if (!$('body').hasClass('hidden-left')) {
            if ($('.headerwrapper').hasClass('collapsed')) {
                $('.headerwrapper, .mainwrapper').removeClass('collapsed');
                $.cookie('app-menu', true);
                //console.log($.cookie('menu'));
            } else {
                $('.headerwrapper, .mainwrapper').addClass('collapsed');
                $('.children').hide(); // hide sub-menu if leave open
                $.cookie('app-menu', false);
                //console.log($.cookie('menu'));
            }
        } else {
            if (!$('body').hasClass('show-left')) {
                $('body').addClass('show-left');
            } else {
                $('body').removeClass('show-left');
            }
        }
        return false;
    });
   
    // Add class nav-hover to mene. Useful for viewing sub-menu
    jQuery('.leftpanel .nav li').hover(function(){
        $(this).addClass('nav-hover');
    }, function(){
        $(this).removeClass('nav-hover');
    });

    // For Media Queries
    jQuery(window).resize(function() {
        hideMenu();
    });
   
    hideMenu(); // for loading/refreshing the page
    function hideMenu() {

        if($('.header-right').css('position') == 'relative') {
            $('body').addClass('hidden-left');
            $('.headerwrapper, .mainwrapper').removeClass('collapsed');
        } else {
            $('body').removeClass('hidden-left');
        }
    }
   
   // PAGEDITAR
   jQuery('.thmb').hover(function() {
        var t = jQuery(this);
        t.find('.ckbox').show();
        t.find('.fm-group').show();
    }, function() {

        var t = jQuery(this);
        if(!t.closest('.thmb').hasClass('checked')) {
            t.find('.ckbox').hide();
            t.find('.fm-group').hide();
        }
    });

    jQuery('.ckbox').each(function(){
        var t = jQuery(this);
        var parent = t.parent();
        if(t.find('input').is(':checked')) {
            t.show();
            parent.find('.fm-group').show();
            parent.addClass('checked');
        }
    });

    jQuery('.ckbox').click(function(){
        var t = jQuery(this);
        if(!t.find('input').is(':checked')) {
            t.closest('.thmb').removeClass('checked');
            enable_itemopt(false);
        } else {
            t.closest('.thmb').addClass('checked');
            enable_itemopt(true);
        }
    });

    jQuery('#selectAll').click(function() {
        if(jQuery(this).hasClass('btn-default')) {
            jQuery('.thmb').each(function() {
                jQuery(this).find('input').attr('checked',true);
                jQuery(this).addClass('checked');
                jQuery(this).find('.ckbox, .fm-group').show();
            });
            enable_itemopt(true);
            jQuery(this).removeClass('btn-default').addClass('btn-primary');
            jQuery(this).text('Select None');
        } else {
            jQuery('.thmb').each(function(){
                jQuery(this).find('input').attr('checked',false);
                jQuery(this).removeClass('checked');
                jQuery(this).find('.ckbox, .fm-group').hide();
            });
            enable_itemopt(false);
            jQuery(this).removeClass('btn-primary').addClass('btn-default');
            jQuery(this).text('Select All');
        }
    });

    function enable_itemopt(enable) {
        if(enable) {
            jQuery('.media-options .btn.disabled').removeClass('disabled').addClass('enabled');
        } else {

        // check all thumbs if no remaining checks
        // before we can disabled the options
        var ch = false;
        jQuery('.thmb').each(function(){
            if(jQuery(this).hasClass('checked'))
            ch = true;
        });

        if(!ch)
            jQuery('.media-options .btn.enabled').removeClass('enabled').addClass('disabled');
        }
    }

    jQuery("a[data-rel^='prettyPhoto']").prettyPhoto();

    $('#downloadAll').click(function (event) {
        event.preventDefault();
        $('.thmb.checked .link-cupon').multiDownload();
    });

    $('#selecctall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.checkbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox"               
            });
        } else {
            $('.checkbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox"                       
            });         
        }
    });

    $('#selecctall2').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.checkbox2').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox"               
            });
        } else {
            $('.checkbox2').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox"                       
            });         
        }
    });

    $('#selecctall3').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.checkbox3').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox"               
            });
        } else {
            $('.checkbox3').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox"                       
            });         
        }
    });

    // DATATABLES

    // Server-side processing
    // jQuery('#list_usuarios').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     // "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_usuarios_datatable.php",        
    //     "columns": [
    //         { "data": "nome" },
    //         { "data": "cpf" },
    //         { "data": "email" },
    //         // { "data": "cnpj" },
    //         { "data": "cnpj" },
    //         { "data": "celular" },
    //         { "data": "nascimento" },
    //         { "data": "data_cadastro" },   
    //         { "data": "app_pontos" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {

    //                 var extract_act = '<a href="usuarios-extrato?id='+row.id+'" data-toggle="tooltip" title="Extrato" class="tooltips"><i class="fa fa-file-text" aria-hidden="true"></i></a>'
    //                 var edit_act    = '<a href="usuarios-editar?id='+row.id+'" data-toggle="tooltip" title="Editar" class="tooltips"><i class="fa fa-pencil" aria-hidden="true"></i></a>'
    //                 // var extract_act = '<a href="#" data-toggle="tooltip" title="Extrato" class="tooltips"><i class="fa fa-file-text" aria-hidden="true"></i></a>'
    //                 // var edit_act    = '<a href="#" data-toggle="tooltip" title="Editar" class="tooltips"><i class="fa fa-pencil" aria-hidden="true"></i></a>'
                        
    //                 // return extract_act + edit_act

    //                 if (row.status == 1) {
    //                     var status_act = '<a href="javascript:void(0)" onclick="desativarUsuario('+row.id+',\'desativar_usuarios\', \''+row.nome+'\')" data-toggle="tooltip" title="Usuário Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>'
    //                 } else {
    //                     var status_act = '<a href="javascript:void(0)" onclick="ativarUsuario('+row.id+',\'ativar_usuarios\', \''+row.nome+'\')" data-toggle="tooltip" title="Usuário Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>'
    //                 }  

    //                 return extract_act + status_act + edit_act
    //             },
    //             "targets": 8
    //         },
    //         { "className": "table-action text-center", "targets": 8 },
    //         { "className": "word-break", "targets": 2 },
    //         { "type": "date-eu", "targets": 5 },
    //         { "type": "date-eu", "targets": 6 },
    //         { "orderable": false, "targets": 8 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    jQuery('#list_usuarios').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "desc" ]],
      "columnDefs": [
            { "type": "date-eu", targets: 4 },
            { "type": "de_datetime", targets: 0 },
            { "orderable": false, targets: 6 },
            { "width": "5%", "targets": 6 }
        ]        
    })

    $(`#list_usuarios_faq`).DataTable({
        "iDisplayLength": 25,
        responsive: false,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
                { "orderable": false, targets: 5 },
                { "type": "de_datetime", "targets": 0 }
        ]
      })
    // jQuery('#list_lojas').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     // "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_lojas.php",        
    //     "columns": [
    //         { "data": "codigo_cliente" },
    //         { "data": "nome_fantasia" },
    //         { "data": "cnpj" },
    //         { "data": "regional" },
    //         { "data": "grupo" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {

    //                 // var extract_act = '<a href="usuarios-pessoa-juridica-extrato?id='+row.id+'" data-toggle="tooltip" title="Extrato" class="tooltips"><i class="fa fa-file-text" aria-hidden="true"></i></a>'
    //                 // var edit_act    = '<a href="lojas-editar?id='+row.id+'" data-toggle="tooltip" title="Editar" class="tooltips"><i class="fa fa-pencil" aria-hidden="true"></i></a>'
    //                 var extract_act = '';
    //                 var edit_act    = '';
    //                 var view_act    = '<a href="lojas-visualizar?id='+row.id+'" data-toggle="tooltip" title="Visualizar" class="tooltips"><i class="fa fa-search-plus" aria-hidden="true"></i></a>'
                    
    //                 return extract_act + edit_act + view_act
                    
    //                 // if (row.status == 1) {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="desativarUsuario('+row.id+',\'desativar_usuario_pj\', \''+row.nome_fantasia+'\')" data-toggle="tooltip" title="Usuário Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>'
    //                 // } else {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="ativarUsuario('+row.id+',\'ativar_usuario_pj\', \''+row.nome_fantasia+'\')" data-toggle="tooltip" title="Usuário Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>'
    //                 // }

    //                 // return extract_act + status_act + edit_act            
    //             },
    //             "targets": 5
    //         },
    //         { "className": "table-action text-center", "targets": 5 },
    //         // { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 5 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    // jQuery('#list_usuarios_colaboradores').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_usuarios_colaboradores_datatable.php",        
    //     "columns": [
    //         { "data": "data_cadastro" },
    //         { "data": "nome" },
    //         { "data": "cpf" },
    //         { "data": "email" },
    //         { "data": "matricula" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {

    //                 var extract_act = '<a href="usuarios-colaboradores-extrato?id='+row.id+'" data-toggle="tooltip" title="Extrato" class="tooltips"><i class="fa fa-file-text" aria-hidden="true"></i></a>'
    //                 var edit_act    = '<a href="usuarios-colaboradores-editar?id='+row.id+'" data-toggle="tooltip" title="Editar" class="tooltips"><i class="fa fa-pencil" aria-hidden="true"></i></a>'
                    
    //                 return extract_act + edit_act

    //                 // if (row.status == 1) {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="desativarUsuario('+row.id+',\'desativar_aluno\', \''+row.nome+'\')" data-toggle="tooltip" title="Usuário Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>'
    //                 // } else {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="ativarUsuario('+row.id+',\'ativar_aluno\', \''+row.nome+'\')" data-toggle="tooltip" title="Usuário Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>'
    //                 // }  

    //                 // return extract_act + status_act + edit_act
    //             },
    //             "targets": 5
    //         },
    //         { "className": "table-action text-center", "targets": 5 },
    //         { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 5 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    // jQuery('#list_produtos').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "asc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_produtos.php",        
    //     "columns": [
    //         { "data": "id" },
    //         { "data": "titulo" },
    //         { "data": "grupo" },
    //         { "data": "subgrupo" }
    //     ]
    // });

    jQuery('#list_compras').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "type": "de_datetime", targets: 0 },
            { "orderable": false, targets: 3 }
        ]        
    });

    jQuery('#list_entregadores_estoque').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "type": "date-eu", targets: 0 },
            { "orderable": false, targets: 5 }
        ]        
    });

    jQuery('#list_baixa_de_produtos').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "type": "date-eu", targets: 0 },
            { "orderable": false, targets: 4 }
        ]        
    });

    jQuery('#list_produtos').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 6 }
         ]        
    });

    jQuery('#list_horario_funcionamento').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "desc" ]],
      "columnDefs": [
            { "type": "de_datetime", targets: 0 },
            { "type": "de_datetime", targets: 1 },
            { "orderable": false, targets: 2 }
         ]        
    });

    jQuery('#list_produtos_categorias').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 1 }
         ]        
    });

    jQuery('#list_produtos_marcas').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 1 }
         ]        
    });

    jQuery('#list_bairros').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 1 }
         ]        
    });

    // jQuery('#list_produtos').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     // "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_produtos.php",        
    //     "columns": [
    //         { "data": "codigo" },
    //         { "data": "descricao" },
    //         { "data": "marca" },
    //         { "data": "categoria" },
    //         { "data": "app_pontos" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {

    //                 // var extract_act = '<a href="usuarios-pessoa-juridica-extrato?id='+row.id+'" data-toggle="tooltip" title="Extrato" class="tooltips"><i class="fa fa-file-text" aria-hidden="true"></i></a>'
    //                 // var edit_act    = '<a href="lojas-editar?id='+row.id+'" data-toggle="tooltip" title="Editar" class="tooltips"><i class="fa fa-pencil" aria-hidden="true"></i></a>'

    //                 var extract_act = ''; 
    //                 var edit_act    = '';                    
    //                 var view_act    = '<a href="produtos-visualizar?id='+row.id+'" data-toggle="tooltip" title="Visualizar" class="tooltips"><i class="fa fa-search-plus" aria-hidden="true"></i></a>'

    //                 return extract_act + edit_act + view_act
                    
    //                 // if (row.status == 1) {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="desativarUsuario('+row.id+',\'desativar_usuario_pj\', \''+row.nome_fantasia+'\')" data-toggle="tooltip" title="Usuário Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>'
    //                 // } else {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="ativarUsuario('+row.id+',\'ativar_usuario_pj\', \''+row.nome_fantasia+'\')" data-toggle="tooltip" title="Usuário Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>'
    //                 // }

    //                 // return extract_act + status_act + edit_act            
    //             },
    //             "targets": 5
    //         },
    //         { "className": "table-action text-center", "targets": 5 },
    //         // { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 5 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    // jQuery('#list_numeros_serie').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     // "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_trn.php",        
    //     "columns": [
    //         { "data": "numero_serie" },
    //         { "data": "data_emissao" },
    //         { "data": "codigo_cliente" },
    //         { "data": "cnpj" },
    //         { "data": "nota_fiscal" },
    //         { "data": "codigo_produto" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {

    //                 // var extract_act = '<a href="usuarios-pessoa-juridica-extrato?id='+row.id+'" data-toggle="tooltip" title="Extrato" class="tooltips"><i class="fa fa-file-text" aria-hidden="true"></i></a>'
    //                 // var edit_act    = '<a href="lojas-editar?id='+row.id+'" data-toggle="tooltip" title="Editar" class="tooltips"><i class="fa fa-pencil" aria-hidden="true"></i></a>'

    //                 var extract_act = ''; 
    //                 var edit_act    = '';                    
    //                 var view_act    = '<a href="numeros-serie-visualizar?id='+row.id+'" data-toggle="tooltip" title="Visualizar" class="tooltips"><i class="fa fa-search-plus" aria-hidden="true"></i></a>'

    //                 return extract_act + edit_act + view_act
                    
    //                 // if (row.status == 1) {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="desativarUsuario('+row.id+',\'desativar_usuario_pj\', \''+row.nome_fantasia+'\')" data-toggle="tooltip" title="Usuário Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>'
    //                 // } else {
    //                 //     var status_act = '<a href="javascript:void(0)" onclick="ativarUsuario('+row.id+',\'ativar_usuario_pj\', \''+row.nome_fantasia+'\')" data-toggle="tooltip" title="Usuário Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>'
    //                 // }

    //                 // return extract_act + status_act + edit_act            
    //             },
    //             "targets": 6
    //         },
    //         { "className": "table-action text-center", "targets": 6 },
    //         // { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 6 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    // jQuery('#list_nfce_').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "columnDefs": [
    //         { "type": "de_datetime", targets: 0 },
    //         { "orderable": false, targets: 3 }
    //     ]        
    // });

    // jQuery('#list_nfce').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_nfce.php",        
    //     "columns": [
    //         { "data": "data_emissao" },
    //         { "data": "chave_acesso" },
    //         { "data": "status" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {
    //                 if (row.status == 1) {
    //                     var status = 'Autorizada'
    //                 } else if (row.status == 2) {
    //                     var status = 'Cancelada'
    //                 }      
    //                 return status
    //             },
    //             "targets": 2
    //         },
    //         {
    //             "render": function ( data, type, row ) {
    //                 var view_act = '<a href="nfc-e-visualizar?id='+row.id+'" data-toggle="tooltip" title="Visualizar" class="tooltips"><i class="fa fa-search-plus" aria-hidden="true"></i></a>'        
    //                 return view_act
    //             },
    //             "targets": 3
    //         },          
    //         { "className": "table-action text-center", "targets": 3 },
    //         { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 3 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    // jQuery('#list_pontuacao_manual').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_pontuacao_manual.php",        
    //     "columns": [
    //         { "data": "data_registro" },
    //         { "data": "protocolo" },
    //         { "data": "id" }
    //     ],        
    //     "columnDefs": [
    //         {
    //             "render": function ( data, type, row ) {
    //                 var view_act = '<a href="pontuacao-manual-visualizar?id='+row.id+'" data-toggle="tooltip" title="Visualizar" class="tooltips"><i class="fa fa-search-plus" aria-hidden="true"></i></a>'        
    //                 return view_act
    //             },
    //             "targets": 2
    //         },
    //         { "className": "table-action text-center", "targets": 2 },
    //         { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 2 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    // jQuery('#list_comprovantes_de_resgate').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "processing": true,
    //     "serverSide": true,        
    //     "ajax": "php/_get_comprovantes_de_resgate.php", 
    //     "columns": [
    //         { "data": "data_hora_registro" },
    //         { "data": "comprovante_de_resgate" },
    //         { "data": "status" },
    //         { "data": "id" },
    //     ],              
    //     "columnDefs": [
    //         {
    //         "render": function ( data, type, row ) {
    //         var cdr = row.codigo_posto+'.'+row.numero+'.'+row.id        
    //         return cdr
    //         },
    //         //"filter": 'comprovante_de_resgate',
    //         "targets": 1
    //         },
    //         {
    //             "render": function ( data, type, row ) {
    //                 if (row.status == 1) {
    //                     var status = 'Não utilizado'
    //                 } else if (row.status == 2) {
    //                     var status = 'Utilizado'
    //                 } else if (row.status == 3) {
    //                     var status = 'Bloqueado'
    //                 }      
    //                 return status
    //             },
    //             "targets": 2
    //         },
    //         {
    //             "render": function ( data, type, row ) {
    //                 var view_act = '<a href="comprovantes-de-resgate-visualizar?id='+row.id+'" data-toggle="tooltip" title="Visualizar" class="tooltips"><i class="fa fa-search-plus" aria-hidden="true"></i></a>'        
    //                 return view_act
    //             },
    //             "targets": 3
    //         },
    //         { "className": "table-action text-center", "targets": 3 },
    //         { "type": "date-eu", "targets": 0 },
    //         { "orderable": false, "targets": 3 },
    //         //{ "responsivePriority": 1, targets: 4 }
    //     ],
    //     "drawCallback": function(settings) {
    //         //console.log(settings.json);
    //         jQuery('.tooltips').tooltip({ container: 'body'});
    //     }
    // });

    jQuery('#list_faq').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "asc" ]],
      "columnDefs": [
            { "orderable": false, "targets": 1 }
         ]        
    });

    // jQuery('#list_grupos_economicos').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "order": [[ 0, "asc" ]],
    //   "columnDefs": [
    //         { "orderable": false, "targets": 2 }
    //      ]        
    // });

    jQuery('#list_vendas').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "desc" ]],
      "columnDefs": [
            { "type": "de_datetime", targets: 0 },
            { "orderable": false, "targets": 6 }
         ]        
    });   

    jQuery('#list_resgates').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 1, "desc" ]],
      "columnDefs": [
            { "type": "de_datetime", targets: 1 },
            { "orderable": false, "targets": 9 }
         ]        
    });     

    // jQuery('#list_lojas_ge').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": true,
    //     "paging": false,
    //     "info": true,
    //     responsive: true
    // });

    // jQuery('#list_usuarios_lojas').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": true,
    //     "paging": false,
    //     "info": true,
    //     responsive: true    
    // });

    jQuery('#list_grupos_usuarios').DataTable({
        "lengthMenu": [[-1], ["All"]],
        "bLengthChange" : true,
        "searching": true,
        "paging": false,
        "info": true,
        responsive: true,
        "columnDefs": [
          { "orderable": false, targets: 3 }
        ]     
    });
    
    // jQuery('#list_comprovantes_de_resgate2').DataTable({
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "columnDefs": [
    //         { "type": "de_datetime", targets: 0 },
    //         { "orderable": false, targets: 3 }
    //     ]        
    // });

    // jQuery('#list_analytics_fechamentos_postos').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true
    // });

    // jQuery('#list_analytics_areas_de_interesse_pf, #list_analytics_areas_de_interesse_colaboradores').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": true,
    //     "paging": false,
    //     "info": false,
    //     responsive: true,
    //     "columnDefs": [
    //         { "orderable": false, targets: 3 }
    //     ]
    // });

    // jQuery('#list_analytics_usuarios_geolocalizacao').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true,
    //     "order": [[ 4, "desc" ]],
    //     "columnDefs": [
    //         { "type": "de_datetime", targets: 4 },
    //         { "orderable": false, targets: 5 }
    //     ]  
    // });

    // jQuery('#list_terceiros').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "order": [[ 0, "desc" ]],
    //     "columnDefs": [
    //         { "type": "date-eu", targets: 0 },
    //         { "orderable": false, targets: 4 }
    //     ]        
    // });

    jQuery('#list_aditivos_regulamento').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "type": "date-eu", targets: 0 },
            { "orderable": false, targets: 2 }
        ]        
    });

    jQuery('#list_grupos').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "columnDefs": [
            { "orderable": false, targets: 1 }
        ]        
    });

    jQuery('#list_grupos2').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "columnDefs": [
            { "orderable": false, targets: 1 }
        ]        
    });

    jQuery('#list_administradores').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "columnDefs": [
            { "orderable": false, targets: 1 }
        ]        
    });

    jQuery('#list_mensagens_usuarios').DataTable({
        "iDisplayLength": 25,
        responsive: true,
        "columnDefs": [
            { "type": "date-eu", targets: 0 },
            { "orderable": false, targets: 2 }
        ]        
    });

    // jQuery('#list_times_do_coracao').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //     ]        
    // });

    // jQuery('#list_postos').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 2 }
    //      ]        
    // });

    // jQuery('#list_mkt_digital').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });
   
    // jQuery('#list_veiculos').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   /*"columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]*/        
    // });

    // jQuery('#list_distribuidoras').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });

    // jQuery('#list_regioes').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });

    // jQuery('#list_servicos').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });

    // jQuery('#list_conveniencia').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });

    // jQuery('#list_abastecimento').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });

    jQuery('#list_promocoes').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 1 }
         ]        
    });

    // jQuery('#list_gamification').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "type": "de_datetime", targets: 0 },
    //         { "type": "de_datetime", targets: 1 },
    //         { "orderable": false, targets: 2 }
    //      ]        
    // });

    // jQuery('#list_gamification_usuarios_1, #list_gamification_usuarios_2, #list_gamification_usuarios_3').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true    
    // });

    // jQuery('#list_bonificacao').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 2 }
    //      ]        
    // });

    // jQuery('#list_bonificacao_cargos').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //      ]        
    // });

    // jQuery('#list_consumo_diario').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 2 }
    //      ]        
    // });

    // jQuery('#list_produtos_limite_consumo').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "columnDefs": [
    //         { "orderable": false, targets: 5 }
    //      ]        
    // });

    // jQuery('#list_cargos').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   // "columnDefs": [
    //   //       { "orderable": false, targets: 2 }
    //   //    ]        
    // });

    jQuery('#list_entregadores').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 2 }
         ]        
    });

    jQuery('#list_chamados_categorias').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "orderable": false, targets: 1 }
         ]        
    });

    // jQuery('#list_analytics_usuarios_bonificacao').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 2, "desc" ]],   
    //   "columnDefs": [
    //         { "orderable": false, targets: 4 }
    //      ]      
    // });

    // jQuery('#list_analytics_usuarios_resgate').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 2, "desc" ]],            
    // });

    // jQuery('#list_analytics_usuarios_nfce_colaboradores').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 3, "desc" ]],   
    //   "columnDefs": [
    //         { "orderable": false, targets: 6 }
    //      ]      
    // });

    // jQuery('#list_analytics_usuarios_nfce_pf').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 2, "desc" ]],   
    //   "columnDefs": [
    //         { "orderable": false, targets: 5 }
    //      ]      
    // });

    // jQuery('#list_analytics_usuarios_limite_consumo_colaboradores').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 3, "desc" ]],   
    //   "columnDefs": [
    //         { "orderable": false, targets: 4 }
    //      ]      
    // });

    // jQuery('#list_analytics_usuarios_limite_consumo_pf').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 2, "desc" ]],   
    //   "columnDefs": [
    //         { "orderable": false, targets: 3 }
    //      ]      
    // });

    jQuery('#list_chamados').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "desc" ]],
      "columnDefs": [
            { "orderable": false, targets: 5 },
            { "type": "de_datetime", "targets": 0 }
         ]        
    });

    // jQuery('#list_pontuacao_manual').DataTable({
    //   "iDisplayLength": 25,
    //   responsive: true,
    //   "order": [[ 0, "desc" ]],
    //   "columnDefs": [
    //         { "orderable": false, targets: 6 },
    //         { "type": "de_datetime", "targets": 0 }
    //      ]        
    // });

    // jQuery('#list_usuarios_recargas').DataTable({
    //   "lengthMenu": [[-1], ["All"]],
    //   "paging": false,
    //   responsive: true,
    //   "order": [[ 8, "desc" ]],
    //   "columnDefs": [
    //         { "orderable": false, targets: 9 },
    //         { "type": "de_datetime", "targets": 8 }
    //      ]        
    // });

    jQuery('#list_notificacoes').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "desc" ]],
      "columnDefs": [
            { "orderable": false, targets: 3 },
            { "type": "de_datetime", "targets": 0 }
         ]        
    });

    jQuery('#list_mensagens').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "order": [[ 0, "desc" ]],
      "columnDefs": [
            { "orderable": false, targets: 4 },
            { "type": "de_datetime", "targets": 0 }
         ]        
    });

    // jQuery('#list_analytics_usuarios_pf, #list_analytics_usuarios_colaboradores').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true,
    //     "columnDefs": [
    //         { "orderable": false, targets: 7 }
    //     ]        
    // });

    // jQuery('#list_analytics_usuarios_pj').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true,
    //     "columnDefs": [
    //         { "orderable": false, targets: 6 }
    //     ]        
    // });

    // jQuery('#list_analytics_produtos').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true       
    // });

    // jQuery('#list_analytics_produtos_nfce').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true,
    //     "columnDefs": [
    //         { "orderable": false, targets: 3 },
    //         { "type": "date-eu", "targets": 0 },
    //     ]         
    // });

    // jQuery('#list_analytics_abastecimento_pf, #list_analytics_abastecimento_pj, #list_analytics_abastecimento_colaboradores').DataTable({
    //     "lengthMenu": [[-1], ["All"]],
    //     "bLengthChange" : true,
    //     "searching": false,
    //     "paging": false,
    //     "info": false,
    //     responsive: true       
    // });

    jQuery('#list_usuarios_notificacoes').DataTable({
        "lengthMenu": [[-1], ["All"]],
        "bLengthChange" : true,
        "searching": true,
        "paging": false,
        "info": true,
        responsive: true,
        "columnDefs": [
          { "orderable": false, targets: 2 }
        ]     
    });

    // jQuery('#list_produtos_limite_consumo_extrato').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     // "columnDefs": [
    //     //       { "orderable": false, targets: 1 }
    //     //    ]        
    // });  

    // jQuery('#list_usuarios_reputacao').DataTable({
    //     "iDisplayLength": 25,
    //     responsive: true,
    //     "columnDefs": [
    //         { "orderable": false, targets: 1 }
    //     ]        
    // });  

    jQuery('#list_news').DataTable({
      "iDisplayLength": 25,
      responsive: true,
      "columnDefs": [
            { "type": "date-eu", targets: 0 },
            { "orderable": false, targets: 2 }
         ]        
    });

    $(".fancybox").click(function(){
        $.fancybox({
            type: 'html',
            autoSize: false,
            content: '<iframe src="https://docs.google.com/gview?url='+this.href+'&embedded=true" height="99%" width="100%"></iframe>',
            /*
            beforeClose: function() {
                $(".fancybox").unwrap();
            }
            */
        }); // fancybox
        return false;
    }); // click

    // Consulta Pontuação Painel
    // $("#form-consulta-pontuacao").submit(function() {
    //     var str = $(this).serialize();      
    //     var result;
    //     $.ajax({
    //         type: "POST",
    //         url: "_consulta_pontuacao.php",
    //         data: str,
    //         success: function(msg) {                
    //             result = msg;                
    //             $('#formstatus').html(result);
    //         },
    //         error: function() { 
    //             result = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Ocorreu um erro, tente novamente.</div>';   
    //             //result = '<div class="alert alert-danger text-center mt20" role="alert"><i class="fa fa-times-circle"></i> There was an error sending the message!</div>';
    //             $("#formstatus").html(result);
    //         }
    //     });
    //     return false;
    // });

});


