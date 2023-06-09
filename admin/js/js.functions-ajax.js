jQuery(document).ready(function() {
   
   "use strict";

    // Pág. Usuários - Edição 
    $("#lojas_cnpj").change(function(){
        var lojas_cnpj = $(this).val();
        // console.log(lojas_cnpj);
        $.getJSON("_lojas_consulta.php?cnpj="+lojas_cnpj, function(data){
        	// console.log(data);
        	if (data["success"] == "true") {
        		$("#lojas_nome_fantasia").val(data["cnpj_nome"]);

        	} else if (data["error"] == "true") {
        		bootbox.alert(data['msg']);
        		$("#lojas_nome_fantasia").val("");
        	}
        });
    });

    // 
    $('select#promo_categorias').change( function() {           
        var categoria = $(this).val();
        console.log(categoria);
        $('select#promo_marcas').load('_promo_marcas.php?categoria='+categoria); // Envia a requisição p URL de retorno dos dados
        $("select#promo_marcas").select2('val', ''); // Carrega conteúdo no select
        $("select#promo_marcas").select2({ // Reinicializa plugin do select
            placeholder: "Marca de Produtos"
        }); 

        $("select#promo_produtos").select2("val", ""); 
        $("select#promo_produtos").select2({ // Reinicializa plugin do select
            placeholder: "Produtos"
        });             
    });

    $('select#promo_marcas').change( function() {            
        var categoria = $('select#promo_categorias').val(); 
        var marca     = $(this).val();
        console.log(categoria);
        console.log(marca);
        $('select#promo_produtos').load('_promo_produtos.php?marca='+marca+'&categoria='+categoria); // Envia a requisição p URL de retorno dos dados
        $("select#promo_produtos").select2('val', ''); // Carrega conteúdo no select
        $("select#promo_produtos").select2({ // Reinicializa plugin do select
            placeholder: "Produtos"
        });                 
    });

    $("input[name='participantes']").change(function() {
        var participantes = $(this).val();
        console.log(participantes);

        if (participantes == 1) { // Todos Usuários ProClube
            $('#grupo_economico').hide();
            $('#loja').hide();
            $('#grupos_usuarios').hide();
            $('#usuario_individual').hide();
            $('#usuarios_reputacao').hide();

        } else if (participantes == 2) { // Grupo Econômico
            $('#grupo_economico').show();
            $('#loja').hide();
            $('#grupos_usuarios').hide();
            $('#usuario_individual').hide();
            $('#usuarios_reputacao').hide();

        } else if (participantes == 3) { // Loja
            $('#grupo_economico').hide();
            $('#loja').show();
            $('#grupos_usuarios').hide();
            $('#usuario_individual').hide();
            $('#usuarios_reputacao').hide();

        } else if (participantes == 4) { // Grupo de Usuários
            $('#grupo_economico').hide();
            $('#loja').hide();
            $('#grupos_usuarios').show();
            $('#usuario_individual').hide();
            $('#usuarios_reputacao').hide();

        } else if (participantes == 5) { // Usuário Individual
            $('#grupo_economico').hide();
            $('#loja').hide();
            $('#grupos_usuarios').hide();
            $('#usuario_individual').show();
            $('#usuarios_reputacao').hide();

        } else if (participantes == 6) { // Reputação Usuários
            $('#grupo_economico').hide();
            $('#loja').hide();
            $('#grupos_usuarios').hide();
            $('#usuario_individual').hide();
            $('#usuarios_reputacao').show();
        }
    });

    $(".participantes_push_notification").change(function() {
        var participantes = $(this).val();
        console.log(participantes);

        if (participantes == 1) { // Todos Usuários ProClube
            $('#grupos_usuarios').hide();
            $('#usuario_individual').hide();

        } else if (participantes == 2) { // Grupo de Usuários
            $('#grupos_usuarios').show();
            $('#usuario_individual').hide();

        } else if (participantes == 3) { // Usuário Individual
            $('#grupos_usuarios').hide();
            $('#usuario_individual').show();

        }
    });

    function select2_lojas() {
        $(".select-lojas").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_lojas_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    }

    function select2_grupos_economicos() {
        $(".select-grupos-economicos").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_grupos_economicos_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    }

    function select2_usuarios() {
        $(".select-usuarios").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_usuarios_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });  
    }

    function select2_produtos() {
        $(".select-produtos").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_produtos_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

     function select2_produtos2() {
        $(".select-produtos2").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_produtos_select2.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    function select2_grupos_usuarios() {
        $(".select-grupos-usuarios").select2({
            width: '100%',
            ajax: { 
                url: "php/_get_grupos_usuarios_select.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }); 
    }

    jQuery(function(){
        select2_lojas();
        select2_grupos_economicos();
        select2_usuarios();
        select2_produtos();
        select2_produtos2();
        select2_grupos_usuarios();
    });

    // Adiciona novos campos Usuários
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".usuarios-inputs"); 
        var add_button1 = $("#add_usuarios");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".usuarios").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.usuarios-inputs').append('<div class="usuarios"> <label>Usuário *</label> <div class="mb15"> <select data-placeholder="Buscar usuário..." name="id_usuario[]" class="width100p select-usuarios"> <option value="">Buscar usuário...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_usuarios();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Grupos Econômicos
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".grupos-economicos-inputs"); 
        var add_button1 = $("#add_grupos_economicos");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".grupos-economicos").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.grupos-economicos-inputs').append('<div class="grupos-economicos"> <label>Grupo Econômico *</label> <div class="mb15"> <select data-placeholder="Buscar grupo econômico..." name="grupos_economicos[]" class="width100p select-grupos-economicos"> <option value="">Buscar grupo econômico...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_grupos_economicos();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Lojas
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".lojas-inputs"); 
        var add_button1 = $("#add_lojas");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".lojas").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.lojas-inputs').append('<div class="lojas"> <label>Loja *</label> <div class="mb15"> <select data-placeholder="Buscar loja..." name="lojas[]" class="width100p select-lojas"> <option value="">Buscar loja...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_lojas();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Grupos Usuários
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".grupos-usuarios-inputs"); 
        var add_button1 = $("#add_grupos_usuarios");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".grupos-usuarios").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.grupos-usuarios-inputs').append('<div class="grupos-usuarios"> <label>Grupo Usuário *</label> <div class="mb15"> <select data-placeholder="Buscar grupo usuários..." name="id_grupos_usuarios[]" class="width100p select-grupos-usuarios"> <option value="">Buscar grupo usuários...</option> </select> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_grupos_usuarios();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Estoque
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".estoque-inputs"); 
        var add_button1 = $("#add_estoque");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".estoque").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.estoque-inputs').append('<div class="estoque"> <label>Produto *</label> <div class="mb15"> <select data-placeholder="Buscar produto..." name="produtos[]" class="width100p select-produtos2" required> <option value="">Buscar produto...</option> </select> </div><label>Qntd *</label> <div class="mb15"> <input type="text" name="qntd[]" class="form-control numero" required/> </div><label>Valor unitário *</label> <div class="mb15"> <input type="text" name="preco[]" class="form-control money" required/> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_produtos2();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

    // Adiciona novos campos Estoque
    $(document).ready(function() {
        var max_fields1 = 1000;
        var wrapper1 = $(".entregadoresestoque-inputs"); 
        var add_button1 = $("#add_entregadoresestoque");
        var count1 = 1; 

        $(add_button1).click(function(e) {
            e.preventDefault();
            var length = wrapper1.find(".entregadoresestoque").length;
            if (count1 < max_fields1) { 
                count1++; 
                $('.entregadoresestoque-inputs').append('<div class="entregadoresestoque"> <label>Produto *</label> <div class="mb15"> <select data-placeholder="Buscar produto..." name="produtos[]" class="width100p select-produtos" required> <option value="">Buscar produto...</option> </select> </div><label>Qntd *</label> <div class="mb15"> <input type="text" name="qntd[]" class="form-control numero" required/> </div><a href="#" class="btn btn-xs btn-danger remove_field"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>');
                select2_produtos();

            } else {
                bootbox.alert("Você atingiu o número máximo de novos campos");
            }
        });

        $(wrapper1).on("click", ".remove_field", function(e) {
            e.preventDefault();
            var b = this;
            bootbox.confirm("Você tem certeza que deseja excluir os campos selecionados?", function(result){ 
                if (result == true) {
                    $(b).parent('div').remove();
                    count1--;
                }
            });     
        })
    });

});