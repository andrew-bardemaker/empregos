function deleteUsuario(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o usuário administrador: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteNews(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja excluir a novidade: "+nome+"?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function ativarNews(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar a novidade: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarNews(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar a novidade: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function ativarBanner(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o banner: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarBanner(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o banner: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteUsuarios(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o usuário: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function ativarUsuario(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o usuário: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarUsuario(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o usuário: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteEntregadores(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o entregador: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function ativarEntregadores(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o entregador: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarEntregadores(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o entregador: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}



function ativarCuppom(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o cuppom: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarCuppom(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o cuppom: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}
function deleteCuppom(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o cuppom: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}


function deleteImg(cod,act){
  bootbox.confirm("Deseja realmente excluir esta imagem?", function(result){ 
	  	if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	  	}  
	});  
}

function deletePromocoes(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir a promoção: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function ativarPromocao(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar a promoção: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarPromocao(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar a promoção: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteAditivosRegulamento(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o registro de aditivos regulamento: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function deleteChamados(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o chamado: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function deleteChamadosCategorias(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir a categoria de chamado: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function deleteGrupos(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o grupo de usuários: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}
function deleteGruposTaxas(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir o grupo de taxas: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}
function deleteUsuariosGrupo(){
    bootbox.confirm("Você tem certeza que deseja excluir os usuários selecionados do grupo?", function(result) {
	    if (result == true) {
	        document.getElementById('form-usuarios-grupos-delete').submit();
	    }   
	});   
}

function marcarNotificacaoLida(cod,act,nome){
	bootbox.confirm("Deseja realmente marcar a notificação como lida?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function marcarMensagemLida(cod,act,nome){
	bootbox.confirm("Deseja realmente marcar a mensagem como lida?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function ativarAditivosRegulamento(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o aditivo: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarAditivosRegulamento(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o aditivo: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteFAQ(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja deletar a pergunta: "+nome+"?", function(result) {
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}
	});	
}

function deleteProdutosCategorias(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir a categoria de produtos: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function deleteProdutosMarcas(cod,act,nome){
	bootbox.confirm("Deseja realmente excluir a marca de produtos: "+nome+"?", function(result){ 
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}  
	});  
}

function ativarProdutosMarcas(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar a marca: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarProdutosMarcas(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar a marca: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteProdutos(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja excluir o produto: "+nome+"?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function ativarProdutos(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja ativar o produto: "+nome+"?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function desativarProdutos(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja desativar o produto: "+nome+"?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function destaqueProdutosSim(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja colocar o produto "+nome+" em destaque na página inicial?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function destaqueProdutosNao(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja tirar o produto "+nome+" em destaque na página inicial?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function deleteBairros(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja excluir o bairro: "+nome+"?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function ativarBairros(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o bairro: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarBairros(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o bairro: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteHorarioFuncionamento(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja excluir o horário de funcionamento: "+nome+"?", function(result){
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}	
	});  
}

function ativarHorarioFuncionamento(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar o horário de funcionamento: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function desativarHorarioFuncionamento(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar o horário de funcionamento: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteCompras(cod,act,nome){
    bootbox.confirm("Você tem certeza que deseja excluir os registro de compra: #"+nome+"?", function(result) {
	    if (result == true) {
	        document.location.href = "_process_request.php?act="+act+"&id="+cod;
	    }   
	});   
}

function deleteEntregadoresEstoque(cod,act,nome){
    bootbox.confirm("Você tem certeza que deseja excluir os registro de compra: #"+nome+"?", function(result) {
	    if (result == true) {
	        document.location.href = "_process_request.php?act="+act+"&id="+cod;
	    }   
	});   
}

function deleteBaixaDeProdutos(cod,act,nome){
    bootbox.confirm("Você tem certeza que deseja excluir os registro de baixa de produto: #"+nome+"?", function(result) {
	    if (result == true) {
	        document.location.href = "_process_request.php?act="+act+"&id="+cod;
	    }   
	});   
}

function update_value_metrica(cod,act){      
	var val=document.getElementById('valor').value; 
	bootbox.confirm("Você tem certeza que deseja atualizar o valor de "+val+"?",function(result){
		if(result==true){
			document.location.href = "_process_request.php?act="+act+"&id="+cod+"&valor="+val;
		}
	});

}

function ativarAnuncio(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar anúncio: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  

}
function desativarAnuncio(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar anúncio: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteAnuncio(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja deletar o anúncio: "+nome+"?", function(result) {
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}
	});	
}


function ativarVaga(cod,act,nome){
	bootbox.confirm("Deseja realmente ativar vaga: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  

}
function desativarVaga(cod,act,nome){
	bootbox.confirm("Deseja realmente desativar vaga: "+nome+"?", function(result){ 
		if (result == true) {
	    	document.location.href = "_process_request.php?act="+act+"&id="+cod;
	 	}  
	});  
}

function deleteVaga(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja deletar a vaga: "+nome+"?", function(result) {
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}
	});	
}


function deletePrecoRegiao(cod,act,nome){
	bootbox.confirm("Você tem certeza que deseja deletar o preço desta região?: "+nome+"?", function(result) {
		if (result == true) {
			document.location.href = "_process_request.php?act="+act+"&id="+cod;
		}
	});	
}