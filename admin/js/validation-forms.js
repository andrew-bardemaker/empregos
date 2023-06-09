function formUsuariosAdmin() {
	$("#form-usuarios-admin").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-usuarios-admin").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "usuarios-admin?msg=a001";

				} else if (data == "success_edit") {
					window.location.href = "usuarios-admin?msg=a002";

				} else if (data == "nome") {
					status.html("").hide();
					status.html("Preencha o nome corretamente").slideDown();

				} else if (data == "senha") {
					status.html("").hide();
					status.html("Informe a senha").slideDown();

				} else if (data == "rpt_senha") {
					status.html("").hide();
					status.html("Repita a senha novamente").slideDown();

				} else if (data == "senha_equals") {
					status.html("").hide();
					status.html("As senhas que você digitou não são iguais").slideDown();

				} else if (data == "senha_caracteres") {
					status.html("").hide();
					status.html("A senha deve ter no mínimo 4 caracteres").slideDown();

				} else if (data == "user") {
					status.html("").hide();
					status.html("Preencha o campo usuário corretamente").slideDown();

				} else if (data == "user_exists") {
					status.html("").hide();
					status.html("Usuário já existente").slideDown();

				} else if (data == "acess_panel") {
					status.html("").hide();
					status.html("Selecione uma opção para acesso painel").slideDown();

				} else {
					console.log("Data:" + data);
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formUsuariosAdminPermissoes() {
	$("#form-usuarios-admin-permissoes").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-usuarios-admin-permissoes").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success_edit") {
					window.location.href = "usuarios-admin?msg=a008";

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formUsuarios() {
	$("#form-usuarios-editar").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-usuarios-editar").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "usuarios?msg=u001";

				} else if (data == "success_edit") {
					window.location.href = "usuarios?msg=u002";

				} else if (data == "cpf") {
					status.html("").hide();
					status.html("Informe um número de CPF válido").slideDown();

				} else if (data == "cpf_invalido") {
					status.html("").hide();
					status.html("CPF inválido").slideDown();

				} else if (data == "cpf_existente") {
					status.html("").hide();
					status.html("CPF já existe").slideDown();

				} else if (data == "nome") {
					status.html("").hide();
					status.html("Preencha o nome corretamente").slideDown();

				} else if (data == "nascimento") {
					status.html("").hide();
					status.html("Data de nascimento inválida").slideDown();

				} else if (data == "telefone_celular") {
					status.html("").hide();
					status.html("Informe um número de telefone celular válido").slideDown();

				} else if (data == "email") {
					status.html("").hide();
					status.html("Informe um endereço de e-mail válido").slideDown();

				} else if (data == "email_existente") {
					status.html("").hide();
					status.html("E-mail já existe").slideDown();

				} else if (data == "senha_numerica") {
					status.html("").hide();
					status.html("A senha deve ser numérica").slideDown();

				} else if (data == "senha") {
					status.html("").hide();
					status.html("Informe a senha").slideDown();

				} else if (data == "senha_confirma") {
					status.html("").hide();
					status.html("Repita a senha novamente").slideDown();

				} else if (data == "senhas_iguais") {
					status.html("").hide();
					status.html("As senhas que você digitou não são iguais").slideDown();

				} else if (data == "senha_caracteres") {
					status.html("").hide();
					status.html("A senha deve possuir 6 números").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formEntregadores() {
	$("#form-entregadores").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-entregadores").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "entregadores?msg=e001";

				} else if (data == "success_edit") {
					window.location.href = "entregadores?msg=e002";

				} else if (data == "login") {
					status.html("").hide();
					status.html("Preencha o login corretamente").slideDown();

				} else if (data == "login_existente") {
					status.html("").hide();
					status.html("Login já existe").slideDown();

				} else if (data == "nome") {
					status.html("").hide();
					status.html("Preencha o nome corretamente").slideDown();

				} else if (data == "telefone_celular") {
					status.html("").hide();
					status.html("Informe um número de telefone celular válido").slideDown();

				} else if (data == "email") {
					status.html("").hide();
					status.html("Informe um endereço de e-mail válido").slideDown();

				} else if (data == "senha") {
					status.html("").hide();
					status.html("Informe a senha").slideDown();

				} else if (data == "senha_confirma") {
					status.html("").hide();
					status.html("Repita a senha novamente").slideDown();

				} else if (data == "senhas_iguais") {
					status.html("").hide();
					status.html("As senhas que você digitou não são iguais").slideDown();

				} else if (data == "senha_caracteres") {
					status.html("").hide();
					status.html("A senha deve possuir no mínimo 4 caracteres").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formMetricasControle() {
	$("#form-metricas").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-metricas").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "metrica-controles?msg=e001";

				} else if (data == "success_edit") {
					window.location.href = "metrica-controles?msg=e002";

				} else if (data == "login") {
					status.html("").hide();
					status.html("Preencha o login corretamente").slideDown();

				} else if (data == "login_existente") {
					status.html("").hide();
					status.html("Login já existe").slideDown();

				} else if (data == "nome") {
					status.html("").hide();
					status.html("Preencha o nome corretamente").slideDown();

				} else if (data == "telefone_celular") {
					status.html("").hide();
					status.html("Informe um número de telefone celular válido").slideDown();

				} else if (data == "email") {
					status.html("").hide();
					status.html("Informe um endereço de e-mail válido").slideDown();

				} else if (data == "senha") {
					status.html("").hide();
					status.html("Informe a senha").slideDown();

				} else if (data == "senha_confirma") {
					status.html("").hide();
					status.html("Repita a senha novamente").slideDown();

				} else if (data == "senhas_iguais") {
					status.html("").hide();
					status.html("As senhas que você digitou não são iguais").slideDown();

				} else if (data == "senha_caracteres") {
					status.html("").hide();
					status.html("A senha deve possuir no mínimo 4 caracteres").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formPromo() {
	$("#form-promo").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-promo").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "promocoes?msg=p001";

				} else if (data == "success_edit") {
					window.location.href = "promocoes?msg=p002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "descricao") {
					status.html("").hide();
					status.html("Preencha o campo descrição").slideDown();

				} else if (data == "data_inicio") {
					status.html("").hide();
					status.html("Data de início inválida").slideDown();

				} else if (data == "data_fim") {
					status.html("").hide();
					status.html("Data de fim inválida").slideDown();

				} else if (data == "datas") {
					status.html("").hide();
					status.html("A data de fim não pode menor que a data de início").slideDown();

				} else if (data == "hora_inicio") {
					status.html("").hide();
					status.html("Hora de início inválida").slideDown();

				} else if (data == "hora_fim") {
					status.html("").hide();
					status.html("Hora de fim inválida").slideDown();

				} else if (data == "horas_invalidas") {
					status.html("").hide();
					status.html("A hora de fim não pode ser menor que a hora de início").slideDown();

				} else if (data == "produto") {
					status.html("").hide();
					status.html("Selecione um produto").slideDown();

				}

				// else if (data == "pontuacao") {
				// 	status.html("").hide();
				// 	status.html("Preencha o campo porcentagem % pontuação - bonificação ").slideDown();

				// } else if (data == "promo_categorias") {
				// 	status.html("").hide();
				// 	status.html("Selecione uma opção para categoria de produtos").slideDown();

				// } else if (data == "promo_marcas") {
				// 	status.html("").hide();
				// 	status.html("Selecione uma opção para marca de produtos").slideDown();

				// } else if (data == "promo_produtos") {
				// 	status.html("").hide();
				// 	status.html("Selecione uma opção para produtos").slideDown();

				// } else if (data == "participantes") {
				// 	status.html("").hide();
				// 	status.html("Selecione quais serão os participantes").slideDown();

				// } else if (data == "id_grupo_economico") {
				// 	status.html("").hide();
				// 	status.html("Selecione um grupo econômico").slideDown();

				// } else if (data == "id_loja") {
				// 	status.html("").hide();
				// 	status.html("Selecione uma loja").slideDown();

				// } else if (data == "id_grupo_usuarios") {
				// 	status.html("").hide();
				// 	status.html("Selecione um grupo de usuários").slideDown();

				// } else if (data == "visualizacao_web") {
				// 	status.html("").hide();
				// 	status.html("Selecione uma opção para visualização na web").slideDown();

				// } 

				else if (data == "invalidimg") {
					status.html("").hide();
					status.html("A extensão da imagem não é válida").slideDown();

				} else if (data == "invalidimgsize") {
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb").slideDown();

				} else if (data == "img") {
					status.html("").hide();
					status.html("Nenhuma imagem foi selecionada").slideDown();

				} else if (data == "invalidimgdimensoes") {
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

// function formBonificacaoAniversariantes() {
// 	$("#form-bonificacao-aniversariantes").submit(function (event) {
// 		event.preventDefault();
// 		var form = $(this);
// 		var postData = form.serialize();
// 		var status = form.parent().find(".alert-danger");
// 		form.parent().find('.btn').hide();
// 		form.parent().find('.loader').show();

// 		$("#form-bonificacao-aniversariantes").ajaxSubmit({
// 			type: "POST",
// 			url: "_process_request.php",
// 			data: postData,
// 			success: function(data) {
// 				form.parent().find('.loader').hide();
// 				form.parent().find('.btn').show();

// 				if (data == "success") {
// 					window.location.href="bonificacao-aniversariantes?msg=ba001";

// 				} else if (data == "success_edit") {
// 					window.location.href="bonificacao-aniversariantes?msg=ba002";

// 				} else if (data == "porcentagem_bonificacao") {
// 					status.html("").hide();
// 					status.html("Prencha o campo porcentagem bonificação").slideDown();

// 				} else {
// 					console.log(data);
// 					status.html("").hide();
// 					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
// 				}
// 			},
// 			error: function () {
// 				status.html("").hide();
// 				status.html("Algo deu errado, tente novamente").slideDown();
// 			}
// 		});
// 	});	
// }

// function formPontuacaoManual() {
// 	$("#form-pontuacao-manual").submit(function (event) {
// 		event.preventDefault();
// 		var form = $(this);
// 		var postData = form.serialize();
// 		var status = form.parent().find(".alert-danger");
// 		form.parent().find('.btn').hide();
// 		form.parent().find('.loader').show();

// 		$("#form-pontuacao-manual").ajaxSubmit({
// 			type: "POST",
// 			url: "_process_request.php",
// 			data: postData,
// 			success: function(data) {
// 				form.parent().find('.loader').hide();
// 				form.parent().find('.btn').show();

// 				if (data == "success") {
// 					window.location.href="pontuacao-manual?msg=pm01";

// 				} else if (data == "cpfcnpj") {
// 					status.html("").hide();
// 					status.html("Preencha o campo CPF").slideDown();

// 				} else if (data == "cpfcnpj_invalido") {
// 					status.html("").hide();
// 					status.html("CPF inválido").slideDown();

// 				} else if (data == "operacao") {
// 					status.html("").hide();
// 					status.html("Selecione o tipo de operação").slideDown();

// 				} else if (data == "pontos") {
// 					status.html("").hide();
// 					status.html("Preencha o campo pontos").slideDown();

// 				} else if (data == "usuario_invalido") {
// 					status.html("").hide();
// 					status.html("Usuário inexistente").slideDown();

// 				} else {
// 					console.log(data);
// 					status.html("").hide();
// 					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
// 				}
// 			},
// 			error: function () {
// 				status.html("").hide();
// 				status.html("Algo deu errado, tente novamente").slideDown();
// 			}
// 		});
// 	});	
// }

function formRegulamento() {
	$("#form-regulamento").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-regulamento").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success_edit") {
					window.location.href = "regulamento?msg=r001";

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formTermosDePrivacidade() {
	$("#form-termos-de-privacidade").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-termos-de-privacidade").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success_edit") {
					window.location.href = "termos-de-privacidade?msg=tp01";

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formAditivosRegulamento() {
	$("#form-aditivos-regulamento").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-aditivos-regulamento").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "aditivos-regulamento?msg=ar01";

				} else if (data == "success_edit") {
					window.location.href = "aditivos-regulamento?msg=ar02";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "texto") {
					status.html("").hide();
					status.html("Preencha o campo texto").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formChamadosCategorias() {
	$("#form-chamados-categorias").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-chamados-categorias").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "chamados-categorias?msg=cc01";

				} else if (data == "success_edit") {
					window.location.href = "chamados-categorias?msg=cc02";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formChamados() {
	$("#form-chamados").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-chamados").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "chamados?msg=ch001";
					status.html("").hide();

				} else if (data == "tipo_de_usuario") {
					status.html("").hide();
					status.html("Selecione uma opção para tipo de usuário").slideDown();

				} else if (data == "cpfcnpj") {
					status.html("").hide();
					status.html("Preencha o campo CPF/CNPJ").slideDown();

				} else if (data == "assunto") {
					status.html("").hide();
					status.html("Preencha o campo assunto").slideDown();

				} else if (data == "categoria") {
					status.html("").hide();
					status.html("Selecione uma opção para categoria").slideDown();

				} else if (data == "mensagem") {
					status.html("").hide();
					status.html("Preencha o campo mensagem").slideDown();

				} else if (data == "arquivo_extensao") {
					status.html("").hide();
					status.html("Extensão de arquivo inválida").slideDown();

				} else if (data == "usuario_invalido") {
					status.html("").hide();
					status.html("Usuário não encontrado").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formChamadosInteracao() {
	$("#form-chamados-interacao").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		var id_chamado = form.parent().find("#id_chamado").val();
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-chamados-interacao").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "chamados-visualizar?id=" + id_chamado + "&msg=chi001";
					status.html("").hide();

				} else if (data == "status_chamado") {
					status.html("").hide();
					status.html("Selecione uma opção para status chamado").slideDown();

				} else if (data == "mensagem") {
					status.html("").hide();
					status.html("Preencha o campo mensagem").slideDown();

				} else if (data == "arquivo_extensao") {
					status.html("").hide();
					status.html("Extensão de arquivo inválida").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formProdutos() {
	$("#form-produtos").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-produtos").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "produtos?msg=pd01";

				} else if (data == "success_edit") {
					window.location.href = "produtos?msg=pd02";

				} else if (data == "codigo") {
					status.html("").hide();
					status.html("Preencha o campo código").slideDown();

				} else if (data == "codigo_exists") {
					status.html("").hide();
					status.html("Já existe um produto cadastrado com este código").slideDown();

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título - Informações Públicas").slideDown();

				} else if (data == "preco") {
					status.html("").hide();
					status.html("Preencha o campo preço").slideDown();

				} else if (data == "marca") {
					status.html("").hide();
					status.html("Selecione uma marca").slideDown();

				} else if (data == "categ") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();

				} else if (data == "file") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();

				} else if (data == "file_upload") {
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();

				} else if (data == "invalidimg") {
					status.html("").hide();
					status.html("A extensão da imagem não é válida.").slideDown();

				} else if (data == "invalidimgsize") {
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb.").slideDown();

				} else if (data == "invalidimgdimensoes") {
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();

				} else if (data == "file_zip") {
					status.html("").hide();
					status.html("O arquivo selecionado não está compactado em .ZIP").slideDown();

				} else if (data == "file_csv") {
					status.html("").hide();
					status.html("O arquivo .CSV não está nomeado corretamente").slideDown();

				} else if (data == "read_csv") {
					status.html("").hide();
					status.html("Não foi possível fazer a leitura do arquivo estoque.csv, tente novamente").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formProdutosCategorias() {
	$("#form-produtos-categorias").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-produtos-categorias").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "produtos-categorias?msg=ec01";

				} else if (data == "success_edit") {
					window.location.href = "produtos-categorias?msg=ec02";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo Título").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}

			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formProdutosMarcas() {
	$("#form-produtos-marcas").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-produtos-marcas").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "produtos-marcas?msg=pm001";

				} else if (data == "success_edit") {
					window.location.href = "produtos-marcas?msg=pm002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "file") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();

				} else if (data == "file_upload") {
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();

				} else if (data == "invalidimg") {
					status.html("").hide();
					status.html("A extensão da imagem não é válida.").slideDown();

				} else if (data == "invalidimgsize") {
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb.").slideDown();

				} else if (data == "invalidimgdimensoes") {
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formGruposUsuarios() {
	$("#form-grupos-usuarios").submit(function (event) {
		event.preventDefault();
 

		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");

		$.ajax({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			// data: postData+'&usuarios='+usuarios+'&usuarios_pf='+usuarios_pf+'&usuarios_colaboradores='+usuarios_colaboradores+'',
			success: function (data) {
				if (data == "success") {
					window.location.href = "grupos?msg=g001";

				} else if (data == "success_edit") {
					window.location.href = "grupos?msg=g002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "usuarios") {
					status.html("").hide();
					status.html("Nenhum usuário foi selecionado").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formGruposUsuariosVincularIndividual() {
	$("#form-grupos-usuarios-vincular-individual").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();
		var id_grupos_usuarios = form.parent().find("#grupo_usuarios").val();

		$.ajax({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "grupos-usuarios?id=" + id_grupos_usuarios + "&msg=g005";

				} else if (data == "cpf_invalido") {
					status.html("").hide();
					status.html("CPF inválido, usuário não encontrado.").slideDown();

				} else if (data == "grupo_usuarios") {
					status.html("").hide();
					status.html("Grupo de usuários inválido.").slideDown();

				} else if (data == "grupo_usuarios_exists") {
					status.html("").hide();
					status.html("Usuário já vinculado ao grupo.").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formGruposUsuariosImport() {
	$("#form-usuarios-import").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-usuarios-import").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "grupos?msg=ui001";

				} else if (data == "grupo_usuarios") {
					status.html("").hide();
					status.html("Selecione uma opção para Grupo de Usuários").slideDown();

				} else if (data == "file") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado para upload").slideDown();

				} else if (data == "file_zip") {
					status.html("").hide();
					status.html("O arquivo selecionado não está compactado em .ZIP").slideDown();

				} else if (data == "file_csv") {
					status.html("").hide();
					status.html("O arquivo .CSV não está nomeado corretamente").slideDown();

				} else if (data == "read_csv") {
					status.html("").hide();
					status.html("Não foi possível fazer a leitura do arquivo CSV, tente novamente").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formMensagensUsuarios() {
	$("#form-mensagens-usuarios").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-mensagens-usuarios").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "mensagens-usuarios?msg=mu01";

				} else if (data == "success_edit") {
					window.location.href = "mensagens-usuarios?msg=mu02";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "texto") {
					status.html("").hide();
					status.html("Preencha o campo texto").slideDown();

				} else if (data == "tipo") {
					status.html("").hide();
					status.html("Selecione uma categoria").slideDown();

				} else if (data == "participantes") {
					status.html("").hide();
					status.html("Selecione quais serão os destinatários").slideDown();

				} else if (data == "grupo_usuarios") {
					status.html("").hide();
					status.html("Selecione ao menos um grupo de usuários").slideDown();

				} else if (data == "grupos_economicos") {
					status.html("").hide();
					status.html("Selecione ao menos um grupo econômico").slideDown();

				} else if (data == "lojas") {
					status.html("").hide();
					status.html("Selecione ao menos uma loja").slideDown();

				} else if (data == "usuario_individual") {
					status.html("").hide();
					status.html("Selecione ao menos um usuário").slideDown();

				} else if (data == "usuarios_reputacao") {
					status.html("").hide();
					status.html("Selecione ao menos uma opção reputação usuários").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formFAQ() {
	$("#form-faq").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-faq").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "faq?msg=f001";
				} else if (data == "success_edit") {
					window.location.href = "faq?msg=f002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "texto") {
					status.html("").hide();
					status.html("Preencha o campo texto").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o suporte").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formNews() {
	$("#form-news").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();
		$("#form-news").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "news?msg=n001";

				} else if (data == "success_edit") {
					window.location.href = "news?msg=n002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "texto") {
					status.html("").hide();
					status.html("Preencha o campo texto").slideDown();

				} else if (data == "data") {
					status.html("").hide();
					status.html("Data inválida").slideDown();

				} else if (data == "file") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();

				} else if (data == "file_upload") {
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload do arquivo, tente novamente").slideDown();

				} else if (data == "invalidimg") {
					status.html("").hide();
					status.html("A extensão da imagem não é válida.").slideDown();

				} else if (data == "invalidimgsize") {
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb.").slideDown();

				} else if (data == "invalidimgdimensoes") {
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas.").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

// function formReputacaoUsuarios() {
// 	$("#form-reputacao-usuarios").submit(function (event) {
// 		event.preventDefault();
// 		var form = $(this);
// 		var postData = form.serialize();
// 		var status = form.parent().find(".alert-danger");
// 		form.parent().find('.btn').hide();
// 		form.parent().find('.loader').show();

// 		$("#form-reputacao-usuarios").ajaxSubmit({
// 			type: "POST",
// 			url: "_process_request.php",
// 			data: postData,
// 			success: function(data) {				
// 				form.parent().find('.loader').hide();
// 				form.parent().find('.btn').show();
// 				if (data == "success") {
// 					window.location.href="reputacao-usuarios?msg=ru001";

// 				} else if (data == "titulo") {
// 					status.html("").hide();
// 					status.html("Preencha o campo título").slideDown();

// 				} else if (data == "pontuacao") {
// 					status.html("").hide();
// 					status.html("Preencha o campo porcentagem % pontuação - bonificação *").slideDown();

// 				} else {
// 					console.log(data);
// 					status.html("").hide();
// 					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
// 				}
// 			},
// 			error: function () {
// 				status.html("").hide();
// 				status.html("Algo deu errado, tente novamente").slideDown();
// 			}
// 		});
// 	});	
// }

// function formSegmentacaoLojista() {
// 	$("#form-segmentacao-lojista").submit(function (event) {
// 		event.preventDefault();
// 		var form = $(this);
// 		var postData = form.serialize();
// 		var status = form.parent().find(".alert-danger");
// 		form.parent().find('.btn').hide();
// 		form.parent().find('.loader').show();

// 		$("#form-segmentacao-lojista").ajaxSubmit({
// 			type: "POST",
// 			url: "_process_request.php",
// 			data: postData,
// 			success: function(data) {				
// 				form.parent().find('.loader').hide();
// 				form.parent().find('.btn').show();
// 				if (data == "success") {
// 					window.location.href="segmentacao-lojista?msg=sl001";

// 				} else if (data == "titulo") {
// 					status.html("").hide();
// 					status.html("Preencha o campo título").slideDown();

// 				} else if (data == "pontuacao") {
// 					status.html("").hide();
// 					status.html("Preencha o campo porcentagem % pontuação - bonificação *").slideDown();

// 				} else {
// 					console.log(data);
// 					status.html("").hide();
// 					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
// 				}
// 			},
// 			error: function () {
// 				status.html("").hide();
// 				status.html("Algo deu errado, tente novamente").slideDown();
// 			}
// 		});
// 	});	
// }

// function formRecargasResgate() {
// 	$("#form-recargas-resgate").submit(function (event) {
// 		event.preventDefault();
// 		var form = $(this);
// 		var postData = form.serialize();
// 		var status = form.parent().find(".alert-danger");
// 		form.parent().find('.btn').hide();
// 		form.parent().find('.loader').show();

// 		$("#form-recargas-resgate").ajaxSubmit({
// 			type: "POST",
// 			url: "_process_request.php",
// 			data: postData,
// 			success: function(data) {
// 				form.parent().find('.loader').hide();
// 				form.parent().find('.btn').show();

// 				if (data == "success") {
// 					window.location.href="usuarios-recargas-resgate?msg=rr001";

// 				} else if (data == "valor") {
// 					status.html("").hide();
// 					status.html("Preencha o campo valor").slideDown();

// 				} else if (data == "pontuacao") {
// 					status.html("").hide();
// 					status.html("Preencha o campo pontuação").slideDown();

// 				} else {
// 					console.log(data);
// 					status.html("").hide();
// 					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();	
// 				}
// 			},
// 			error: function () {
// 				status.html("").hide();
// 				status.html("Algo deu errado, tente novamente").slideDown();
// 			}
// 		});
// 	});	
// }

function formValorMinimoPedido() {
	$("#form-valor-minimo-pedido").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-valor-minimo-pedido").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "valor-minimo-pedido?msg=vmr01";

				} else if (data == "valor") {
					status.html("").hide();
					status.html("Preencha o campo valor").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formSMS() {
	$("#form-sms").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-sms").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "sms?msg=sms01";

				} else if (data == "success_edit") {
					window.location.href = "sms?msg=sms02";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "texto") {
					status.html("").hide();
					status.html("Preencha o campo texto").slideDown();

				} else if (data == "participantes") {
					status.html("").hide();
					status.html("Selecione quais serão os destinatários").slideDown();

				} else if (data == "grupo_usuarios") {
					status.html("").hide();
					status.html("Selecione ao menos um grupo de usuários").slideDown();

				} else if (data == "grupos_economicos") {
					status.html("").hide();
					status.html("Selecione ao menos um grupo econômico").slideDown();

				} else if (data == "lojas") {
					status.html("").hide();
					status.html("Selecione ao menos uma loja").slideDown();

				} else if (data == "usuario_individual") {
					status.html("").hide();
					status.html("Selecione ao menos um usuário").slideDown();

				} else if (data == "usuarios_reputacao") {
					status.html("").hide();
					status.html("Selecione ao menos uma opção reputação usuários").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formBairros() {
	$("#form-bairros").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-bairros").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();

				if (data == "success") {
					window.location.href = "bairros?msg=b001";

				} else if (data == "success_edit") {
					window.location.href = "bairros?msg=b002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo Título").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}

			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formBanners() {
	$("#form-banners").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-banners").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "banners?msg=b01";

				} else if (data == "success_edit") {
					window.location.href = "banners?msg=b02";

				} else if (data == "posicao") {
					status.html("").hide();
					status.html("Preencha o campo posição").slideDown();

				} else if (data == "file") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();

				} else if (data == "file_upload") {
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload da imagem, tente novamente").slideDown();

				} else if (data == "invalidimg") {
					status.html("").hide();
					status.html("A extensão da imagem não é válida").slideDown();

				} else if (data == "invalidimgsize") {
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb").slideDown();

				} else if (data == "invalidimgdimensoes") {
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas").slideDown();

				} else if (data == "file-m") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado - mobile").slideDown();

				} else if (data == "file_upload-m") {
					status.html("").hide();
					status.html("Ocorreu um erro ao fazer o upload da imagem, tente novamente - mobile").slideDown();

				} else if (data == "invalidimg-m") {
					status.html("").hide();
					status.html("A extensão da imagem não é válida - mobile").slideDown();

				} else if (data == "invalidimgsize-m") {
					status.html("").hide();
					status.html("A imagem é muito grande, envie arquivos de até 2Mb - mobile").slideDown();

				} else if (data == "invalidimgdimensoes-m") {
					status.html("").hide();
					status.html("A imagem não possui as dimensões corretas - mobile").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formCompras() {
	$("#form-compras").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-compras").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "compras?msg=c001";

				} else if (data == "success_edit") {
					window.location.href = "compras?msg=c002";

				} else if (data == "entregador") {
					status.html("").hide();
					status.html("Selecione uma opção para Entregador").slideDown();

				} else if (data == "chave_acesso") {
					status.html("").hide();
					status.html("Preencha o campo chave acesso").slideDown();

				} else if (data == "numero") {
					status.html("").hide();
					status.html("Preencha o campo número").slideDown();

				} else if (data == "data_emissao") {
					status.html("").hide();
					status.html("Preencha o campo data emissão").slideDown();

				} else if (data == "hora_emissao") {
					status.html("").hide();
					status.html("Preencha o campo hora emissão").slideDown();

				} else if (data == "xml") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();

				} else if (data == "xml_invalid") {
					status.html("").hide();
					status.html("Arquivo enviado inválido").slideDown();

				} else if (data == "xml_exists") {
					status.html("").hide();
					status.html("Arquivo enviado já registrado").slideDown();

				} else if (data == "registro_compra") {
					status.html("").hide();
					status.html("Compra já registrada").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formCompras2() {
	$("#form-compras2").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-compras2").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "compras?msg=c001";

				} else if (data == "success_edit") {
					window.location.href = "compras?msg=c002";

				} else if (data == "entregador") {
					status.html("").hide();
					status.html("Selecione uma opção para Entregador").slideDown();

				} else if (data == "chave_acesso") {
					status.html("").hide();
					status.html("Preencha o campo chave acesso").slideDown();

				} else if (data == "xml") {
					status.html("").hide();
					status.html("Nenhum arquivo foi selecionado").slideDown();

				} else if (data == "xml_invalid") {
					status.html("").hide();
					status.html("Arquivo enviado inválido").slideDown();

				} else if (data == "xml_exists") {
					status.html("").hide();
					status.html("Arquivo enviado já registrado").slideDown();

				} else if (data == "registro_compra") {
					status.html("").hide();
					status.html("Compra já registrada").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formHorarioFuncionamento() {
	$("#form-horario-funcionamento").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-horario-funcionamento").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "horarios-funcionamento?msg=hf001";

				} else if (data == "success_edit") {
					window.location.href = "horarios-funcionamento?msg=hf002";

				} else if (data == "data_inicio") {
					status.html("").hide();
					status.html("Data de início inválida").slideDown();

				} else if (data == "data_fim") {
					status.html("").hide();
					status.html("Data de fim inválida").slideDown();

				} else if (data == "datas") {
					status.html("").hide();
					status.html("A data de fim não pode menor que a data de início").slideDown();

				} else if (data == "hora_inicio") {
					status.html("").hide();
					status.html("Hora de início inválida").slideDown();

				} else if (data == "hora_fim") {
					status.html("").hide();
					status.html("Hora de fim inválida").slideDown();

				} else if (data == "horas_invalidas") {
					status.html("").hide();
					status.html("A hora de fim não pode ser menor que a hora de início").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formEntregadoresEstoque() {
	$("#form-entregadores-estoque").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-entregadores-estoque").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "entregadores-estoque?msg=ee001";

				} else if (data == "success_edit") {
					window.location.href = "entregadores-estoque?msg=ee002";

				} else if (data == "entregador") {
					status.html("").hide();
					status.html("Selecione uma opção para Entregador").slideDown();

				} else if (data == "data") {
					status.html("").hide();
					status.html("Preencha o campo data").slideDown();

				} else if (data == "produto_qntd") {
					status.html("").hide();
					status.html("Preencha as informações de produto/ quantidade").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formEntregadoresEstoqueTransferencia() {
	$("#form-entregadores-estoque-transferencia").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-entregadores-estoque-transferencia").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "entregadores-estoque?msg=ee001";

				} else if (data == "success_edit") {
					window.location.href = "entregadores-estoque?msg=ee002";

				} else if (data == "entregador_saida") {
					status.html("").hide();
					status.html("Selecione uma opção para Entregador Saída").slideDown();

				} else if (data == "entregador_entrada") {
					status.html("").hide();
					status.html("Selecione uma opção para Entregador Entrada").slideDown();

				} else if (data == "data") {
					status.html("").hide();
					status.html("Preencha o campo data").slideDown();

				} else if (data == "produto_qntd") {
					status.html("").hide();
					status.html("Preencha as informações de produto/ quantidade").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formBaixaDeProdutos() {
	$("#form-baixa-de-produtos").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		$('.btn').hide();
		$('.loader').show();

		$("#form-baixa-de-produtos").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				$('.loader').hide();
				$('.btn').show();
				console.log(data);
				if (data == "success") {
					window.location.href = "baixa-de-produtos?msg=bp001";

				} else if (data == "success_edit") {
					window.location.href = "baixa-de-produtos?msg=bp002";

				} else if (data == "entregador") {
					status.html("").hide();
					status.html("Selecione uma opção para Entregador").slideDown();

				} else if (data == "data") {
					status.html("").hide();
					status.html("Preencha o campo data").slideDown();

				} else if (data == "produto_qntd") {
					status.html("").hide();
					status.html("Preencha as informações de produto/ quantidade").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

function formPushNotification() {
	$("#form-push-notification").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-push-notification").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "push-notification?msg=pn001";

				} else if (data == "success_edit") {
					window.location.href = "push-notification?msg=pn002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "texto") {
					status.html("").hide();
					status.html("Preencha o campo texto").slideDown();

				} else if (data == "participantes") {
					status.html("").hide();
					status.html("Selecione quais serão os destinatários").slideDown();

				} else if (data == "grupo_usuarios") {
					status.html("").hide();
					status.html("Selecione ao menos um grupo de usuários").slideDown();

				} else if (data == "usuario_individual") {
					status.html("").hide();
					status.html("Selecione ao menos um usuário").slideDown();

				} else if (data == "status_notificacao_interna") {
					status.html("").hide();
					status.html("Selecione uma opção para - Criar notificação interna?").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}
function formRegistroPreco() {
	$("#form-preco-regiao").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");
		form.parent().find('.btn').hide();
		form.parent().find('.loader').show();

		$("#form-preco-regiao").ajaxSubmit({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			success: function (data) {
				form.parent().find('.loader').hide();
				form.parent().find('.btn').show();
				if (data == "success") {
					window.location.href = "registro-preco-regiao?msg=rpr001";

				} else if (data == "success_edit") {
					window.location.href = "registro-preco-regiao?msg=rpr002";

				} else if (data == "estado") {
					status.html("").hide();
					status.html("Escolha um estado*").slideDown();

				} else if (data == "cidade") {
					status.html("").hide();
					status.html("Escolha uma cidade*").slideDown();

				} else if (data == "preco") {
					status.html("").hide();
					status.html("Por favor, digite um preço*").slideDown();
				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}


function formGruposTaxas() {
	$("#form-grupos-taxas").submit(function (event) {
		event.preventDefault();
 

		var form = $(this);
		var postData = form.serialize();
		var status = form.parent().find(".alert-danger");

		$.ajax({
			type: "POST",
			url: "_process_request.php",
			data: postData,
			// data: postData+'&usuarios='+usuarios+'&usuarios_pf='+usuarios_pf+'&usuarios_colaboradores='+usuarios_colaboradores+'',
			success: function (data) {
				if (data == "success") {
					window.location.href = "grupos-taxas?msg=g001";

				} else if (data == "success_edit") {
					window.location.href = "grupos-taxas?msg=g002";

				} else if (data == "titulo") {
					status.html("").hide();
					status.html("Preencha o campo título").slideDown();

				} else if (data == "usuarios") {
					status.html("").hide();
					status.html("Nenhum usuário foi selecionado").slideDown();

				} else {
					console.log(data);
					status.html("").hide();
					status.html("Ocorreu um erro, entre em contato com o desenvolvedor").slideDown();
				}
			},
			error: function () {
				status.html("").hide();
				status.html("Algo deu errado, tente novamente").slideDown();
			}
		});
	});
}

jQuery(function () {
	formUsuariosAdmin();
	formRegistroPreco();
	formUsuariosAdminPermissoes();
	formUsuarios();
	formEntregadores();
	formPromo();
	// formBonificacaoAniversariantes();
	// formPontuacaoManual();
	formRegulamento();
	formTermosDePrivacidade();
	formAditivosRegulamento();
	formChamadosCategorias();
	formChamados();
	formChamadosInteracao();
	formProdutos();
	formProdutosCategorias();
	formProdutosMarcas();
	formGruposUsuarios();
	formGruposUsuariosVincularIndividual();
	formGruposUsuariosImport();
	formMensagensUsuarios();
	formFAQ();
	formNews();
	// formReputacaoUsuarios();
	// formSegmentacaoLojista();
	// formRecargasResgate();
	formValorMinimoPedido();
	formSMS();
	formBairros();
	formBanners();
	formCompras();
	formCompras2();
	formHorarioFuncionamento();
	formEntregadoresEstoque();
	formEntregadoresEstoqueTransferencia();
	formBaixaDeProdutos();
	formPushNotification();
	formGruposTaxas()
	// formMetricasControle();
});
