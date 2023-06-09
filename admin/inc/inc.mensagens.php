<?php
if (isset($_GET['msg']) && !empty($_GET['msg'])) {
	$msg = $_GET['msg'];
	switch ($msg) {
			/*case '000':
			$txt = 'Navegue pelos links no menu ao lado';
			break;*/

		case '123':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Acessos inválidos, tente novamente
					</div>';
			break;

		case '456':
			$txt = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Realize o login primeiro
					</div>';
			break;

			// mnsgs times do coração
		case 'tc001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Time do coração cadastrado com sucesso
					</div>';
			break;

		case 'tc002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Time do coração atualizado com sucesso
					</div>';
			break;

		case 'tc003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Time do coração excluído com sucesso
					</div>';
			break;

			// mnsgs regulamento
		case 'r001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Regulamento atualizado com sucesso.
					</div>';
			break;

			// mnsgs termos de privacidade
		case 'tp01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Termos de Privacidade atualizado com sucesso.
					</div>';
			break;

			// mnsgs administradores
		case 'a001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Administrador cadastrado com sucesso
					</div>';
			break;

		case 'a002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Administrador atualizado com sucesso
					</div>';
			break;

		case 'a003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Administrador excluído com sucesso
					</div>';
			break;

		case 'a006':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Administrador ativado com sucesso
					</div>';
			break;

		case 'a007':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Administrador desativado com sucesso
					</div>';
			break;

		case 'a008':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Permissões Administrador atualizadas com sucesso
					</div>';
			break;

			// mnsgs valor mínimo pedido
		case 'vmr01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Valor mínimo pedido atualizado com sucesso.
					</div>';
			break;

			// mnsgs compras
		case 'c001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de compra cadastrado com sucesso
					</div>';
			break;

		case 'c003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de compra excluído com sucesso
					</div>';
			break;

			// mnsgs entregadores/ estoque
		case 'ee001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de compras/ entregadores cadastrado com sucesso
					</div>';
			break;

		case 'ee003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de compras/ entregadores excluído com sucesso
					</div>';
			break;

			// mnsgs produtos
		case 'pd01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto cadastrado com sucesso
					</div>';
			break;

		case 'pd02':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto atualizado com sucesso
					</div>';
			break;

		case 'pd03':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto excluído com sucesso
					</div>';
			break;

		case 'pd04':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto ativado com sucesso.
					</div>';
			break;

		case 'pd05':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto desativado com sucesso.
					</div>';
			break;

		case 'pd06':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto colocado em destaque
					</div>';
			break;

		case 'pd07':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Produto retirado do destaque
					</div>';
			break;

			// mnsgs promoções
		case 'p001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Promoção cadastrada com sucesso.
					</div>';
			break;

		case 'p002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Promoção atualizada com sucesso.
					</div>';
			break;

		case 'p003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Promoção excluída com sucesso.
					</div>';
			break;

		case 'p004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Promoção ativada com sucesso.
					</div>';
			break;

		case 'p005':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Promoção desativada com sucesso.
					</div>';
			break;

			// mnsgs pontuação manual
		case 'pm01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de Pontuação Manual cadastrado com sucesso.
					</div>';
			break;

			// mnsgs aditivos regulamento
		case 'ar01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de Aditivos Regulamento cadastrado com sucesso.
					</div>';
			break;

		case 'ar02':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de Aditivos Regulamento atualizado com sucesso.
					</div>';
			break;

		case 'ar03':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de Aditivos Regulamento excluído com sucesso.
					</div>';
			break;

		case 'ar04':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de Aditivos Regulamento ativado com sucesso.
					</div>';
			break;

		case 'ar05':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de Aditivos Regulamento desativado com sucesso.
					</div>';
			break;

			// mnsgs categorias chamados
		case 'cc01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Categoria de Chamados cadastrado com sucesso.
					</div>';
			break;

		case 'cc02':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Categoria de Chamados atualizado com sucesso.
					</div>';
			break;

		case 'cc03':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Categoria de Chamados excluído com sucesso.
					</div>';
			break;

			// mnsgs grupos de usuarios
		case 'g001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Grupo de Usuários cadastrado com sucesso.
					</div>';
			break;

		case 'g002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Grupo de Usuários atualizado com sucesso.
					</div>';
			break;

		case 'g003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Grupo de Usuários excluído com sucesso.
					</div>';
			break;

		case 'g004':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário(s) do grupo excluído(s) com sucesso.
					</div>';
			break;

		case 'g005':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário(s) vinculado(s) ao grupo com sucesso.
					</div>';
			break;

			// mnsgs usuários importados
		case 'ui001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuários importados com sucesso.
					</div>';
			break;

			// mnsgs usuarios
		case 'u001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário cadastrado com sucesso.
					</div>';
			break;

		case 'u002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário atualizado com sucesso.
					</div>';
			break;

		case 'u003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário excluído com sucesso.
					</div>';
			break;

		case 'u004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário ativado com sucesso.
					</div>';
			break;

		case 'u005':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Usuário desativado com sucesso.
					</div>';
			break;

			// mnsgs entregadores
		case 'e001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Entregador cadastrado com sucesso.
					</div>';
			break;

		case 'e002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Entregador atualizado com sucesso.
					</div>';
			break;

		case 'e003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Entregador excluído com sucesso.
					</div>';
			break;

		case 'e004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Entregador ativado com sucesso.
					</div>';
			break;

		case 'e005':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Entregador desativado com sucesso.
					</div>';
			break;

			// mnsgs chamados
		case 'ch001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>					
					Chamado cadastrado com sucesso.
					</div>';
			break;

		case 'ch003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>					
					Chamado excluído com sucesso.
					</div>';
			break;

		case 'chi001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>					
					Resposta cadastrada com sucesso.
					</div>';
			break;

			// mnsgs notificações
		case 'ntf001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Notificação marcada como lida com sucesso.
					</div>';
			break;

			// mnsgs mensagens
		case 'msg001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Mensagem marcada como lida com sucesso.
					</div>';
			break;

			// mnsgs relatorios
		case 'rlt001':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Os dados informados não retornaram nenhuma informação.
					</div>';
			break;

			// mnsgs mensagem / usuários
		case 'mu01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Mensagem enviada com sucesso.
					</div>';
			break;

			//mnsgs faq
		case 'f001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						FAQ cadastrada com sucesso
					</div>';
			break;
		case 'f002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						FAQ atualizada com sucesso
					</div>';
			break;
		case 'f003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						FAQ exclu&iacute;da com sucesso
					</div>';
			break;

			// mnsgs reputação usuários
		case 'ru001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Reputação Usuário atualizada com sucesso
					</div>';
			break;

			// mnsgs segmentação lojista
		case 'sl001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Segmentação Lojista atualizada com sucesso
					</div>';
			break;

			// mnsgs news
		case 'n001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					News cadastrada com sucesso
					</div>';
			break;

		case 'n002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					News atualizada com sucesso
					</div>';
			break;

		case 'n003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					News excluída com sucesso
					</div>';
			break;

		case 'n004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					News ativada com sucesso
					</div>';
			break;

		case 'n005':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					News desativada com sucesso
					</div>';
			break;

			// mnsgs resgate recargas
		case 'rr001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Resgate Recargas atualizado com sucesso.
					</div>';
			break;

			// mnsgs resgate
		case 'rvv01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Resgate Via Varejo atualizado com sucesso.
					</div>';
			break;

			// mnsgs categoria de produtos
		case 'ec01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Categoria de Produtos cadastrada com sucesso.
					</div>';
			break;

		case 'ec02':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Categoria de Produtos atualizada com sucesso.
					</div>';
			break;

		case 'ec03':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Categoria de Produtos excluída com sucesso.
					</div>';
			break;

			// mnsgs categoria de produtos
		case 'pm001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Marca de Produtos cadastrada com sucesso.
					</div>';
			break;

		case 'pm002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Marca de Produtos atualizada com sucesso.
					</div>';
			break;

		case 'pm003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Marca de Produtos excluída com sucesso.
					</div>';
			break;

		case 'pm004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Marca de Produtos ativado com sucesso
					</div>';
			break;

		case 'pm005':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Marca de Produtos desativado com sucesso
					</div>';
			break;

			// mnsgs bairros
		case 'b001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Bairro cadastrado com sucesso.
					</div>';
			break;

		case 'b002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Bairro atualizado com sucesso.
					</div>';
			break;

		case 'b003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Bairro excluído com sucesso.
					</div>';
			break;

		case 'b004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Bairro ativado com sucesso
					</div>';
			break;

		case 'b005':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Bairro desativado com sucesso
					</div>';
			break;

			// mnsgs horário funcionamento
		case 'hf001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Horário Funcionamento cadastrado com sucesso.
					</div>';
			break;

		case 'hf002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Horário Funcionamento atualizado com sucesso.
					</div>';
			break;

		case 'hf003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Horário Funcionamento excluído com sucesso.
					</div>';
			break;

		case 'hf004':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Horário Funcionamento ativado com sucesso
					</div>';
			break;

		case 'hf005':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Horário Funcionamento desativado com sucesso
					</div>';
			break;

			// mnsgs banners
		case 'b01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Banner cadastrado com sucesso
					</div>';
			break;

		case 'b02':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Banner atualizado com sucesso
					</div>';
			break;

		case 'b03':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Banner excluído com sucesso
					</div>';
			break;

		case 'b04':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Banner ativado com sucesso
					</div>';
			break;

		case 'b05':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Banner desativado com sucesso
					</div>';
			break;

			// mnsgs baixa de produtos
		case 'bp001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de baixa de produtos cadastrado com sucesso
					</div>';
			break;

		case 'bp003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Registro de baixa de produtos excluído com sucesso
					</div>';
			break;

			// mnsgs sms
		case 'sms01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					SMS enviado com sucesso.
					</div>';
			break;

			// mnsgs push notification
		case 'pn001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Push Notification enviada com sucesso.
					</div>';
			break;



			// mnsgs banners
		case 'cup01':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Cupom  cadastrado com sucesso
					</div>';
			break;

		case 'cup02':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Cupom  atualizado com sucesso
					</div>';
			break;

		case 'cup03':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Cupom  excluído com sucesso
					</div>';
			break;

		case 'cup04':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Cupom  ativado com sucesso
					</div>';
			break;

		case 'cup05':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Cupom  desativado com sucesso
					</div>';
			break;

		case 'metr001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Valor de   NEXT DAY   foi alterado com sucesso
					</div>';
			break;

		case 'metr002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Valor de TODAY foi alterado com sucesso
					</div>';
			break;
		case 'anun001':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Anúncio exclu&iacute;do com sucesso
						</div>';
			break;

		case 'v003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Vaga excluída com sucesso
							</div>';
			break;

		case 'rpr001':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Preço incluído com sucesso!
							</div>';
			break;

		case 'rpr002':
			$txt = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								Preço atualizado com sucesso!
								</div>';
			break;

		case 'rpr003':
			$txt = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									Preço excluído com sucesso!
									</div>';
			break;
		default:
			$txt = '';
	}

	$tpl->gotoBlock('_ROOT');
	$tpl->assign('msg', $txt);
}
