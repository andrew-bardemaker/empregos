import axios from 'axios';
var Services = {};
var base_url = 'https://geladaemcasaapp.com.br/ws/';

//user.cpf = '03112757025'
//user.senha = '240292'
Services.mailing = (App, formData) => {
    
}

Services.login = (App, values) => {
    axios.post(base_url + 'login', { user: values.auten, senha: values.senha }).then(res => {
        console.log(res.data)
        App.responseLogin(res.data)
    })
}

Services.usuarios = (App, id, token) => {
    axios.post(base_url + 'usuarios', { id_usuario: id, token: token }).then(res => {
        App.responseUsuarios(res.data);
    })
}

Services.notificacaoNaoLida = (App, user, token) => {
    axios.post(base_url + 'notificacoes/naolidas', { id_usuario: user, token: token }).then(res => {
        App.responseNotificacaoNaoLida(res.data);
    })
}

Services.notificacoes = (App, user, token) => {
    axios.post(base_url + '/notificacoes', { id_usuario: user, token: token }).then(res => {
        App.responseNotificacoes(res.data);
    })
}

Services.notificacaoInterna = (App, user, token, id) => {
    axios.post(base_url + '/notificacao', { id_usuario: user, token: token, id_notificacao: id }).then(res => {
        App.responseNotificacaoInterna(res.data);
    })
}

Services.cadastro = (App, values) => {
    axios.post(base_url + 'usuarios/cadastro', {
        nome: values.nome, cpf: values.cpf, email: values.email,
        telefone_celular: values.telefone, data_nascimento: values.nascimento, senha: values.senha,
        senha_confirma: values.confirmasenha, regulamento: values.regulamento
    }).then(res => {
        if (res.data) {
            App.responseCadastro(res.data);
        }
    })
}

Services.regulamento = (App) => {
    axios.get(base_url + 'regulamento').then(res => {
        // console.log(res.data)
        App.responseRegulamento(res.data.regulamento);
    })
}

Services.validaCpf = (App, cpf) => {
    axios.get(base_url + 'validacpf?cpf=' + cpf).then(res => {
        if (res.data) {
            App.responseValidaCpf(res.data);
        }
    })
}

Services.produtos = (App, values) => {
    axios.get(base_url + 'produtos?id_marca=' + values.id_marca + '&id_categoria=' + values.id_categoria +
        '&busca=' + values.busca + '&pagina=' + values.pagina + '&filtro=' + values.filtro + '&valor_ini=' + values.valor_ini +
        '&valor_fim=' + values.valor_fim).then(res => {
            if (res.data) {
                App.responseProdutos(res.data);
            }
        })
}

Services.produtosCategorias = (App) => {
    axios.get(base_url + 'produtos/categorias').then(res => {
        if (res.data) {
            App.responseProdutosCategorias(res.data);
        }
    })
}

Services.produtosMarcas = (App) => {
    axios.get(base_url + 'produtos/marcas').then(res => {
        if (res.data) {
            App.responseProdutosMarcas(res.data);
        }
    })
}

Services.alteraAvatar = (App, id, token, base64) => {
    // console.log(App)
    axios.post(base_url + 'avatar/cadastro', { id_usuario: id, token: token, avatar: base64 }).then(res => {
        if (res.data) {
            App.responseAvatar(res.data);
        }
    })
}

Services.deleteAvatar = (App, id, token) => {
    axios.post(base_url + 'avatar/delete', { id_usuario: id, token: token }).then(res => {
        if (res.data) {
            App.responseAvatar(res.data);
        }
    })
}

Services.usuariosAtualiza = (App, usuario, values) => {
    axios.post(base_url + 'usuarios/atualiza', {
        id_usuario: usuario,
        nome: values.nome, telefone_celular: values.telefone, email: values.email,
        senha: values.senha, senha_confirma: values.confirma_senha, token: values.token
    }).then(res => {
        if (res.data) {
            App.responseUsuariosAtualiza(res.data);
        }
    })
}

Services.faq = (App, busca) => {
    axios.post(base_url + 'faq', { pesquisa: busca }).then(res => {
        if (res.data) {
            App.responseFaq(res.data);
        }
    })
}

Services.ajuda = (App, values) => {
    axios.post(base_url + 'faleconosco', { nome: values.nome, email: values.email, telefone: values.telefone, mensagem: values.msg }).then(res => {
        console.log(res.data)
        App.responseAjuda(res.data)
    })
}

Services.recuperaSenha = (App, email) => {
    axios.post(base_url + 'recuperasenha', { email: email }).then(res => {
        App.responseRecuperaSenha(res.data)
    })
}

Services.usuariosEndereco = (App, id, token) => {
    axios.post(base_url + 'usuarios/enderecos', { id_usuario: id, token: token }).then(res => {
        if (res.data) {
            App.responseUsuariosEndereco(res.data);
        }
    })
}

Services.webCep = (App, cep) => {
    console.log(cep)
    axios.get("https://webservice.kinghost.net/web_cep.php" + '?auth=87d09142dc3136f05dcb02dd81633cb5&formato=json&cep=' + cep).then(res => {
        if (res.data) {
            App.responseWebCep(res.data);
        }
    })
}

Services.bairros = (App) => {
    axios.get(base_url + 'bairros?status=1').then(res => {
        App.responseBairros(res.data)
    })
}

Services.cadastroEndereco = (App, id, values) => {
    axios.post(base_url + 'enderecos/cadastro', {
        id_usuario: id,
        token: values.token,
        cep: values.cep,
        endereco: values.endereco,
        numero: values.numero,
        complemento: values.complemento,
        id_bairro: values.bairro,
        cidade: values.cidade,
        uf: values.uf,
        latitude: values.lat,
        longitude: values.long,
        identificador: values.identificador,
    }).then(res => {
        if (res.data) {
            App.responseCadastroEndereco(res.data);
        }
    })
}

Services.deleteEndereco = (App, id, token, endereco) => {
    axios.post(base_url + 'enderecos/delete', {
        id_usuario: id,
        token: token,
        id_endereco: endereco,
    }).then(res => {
        if (res.data) {
            App.responseDeleteEndereco(res.data);
        }
    })
}

Services.produto = (App, id) => {
    axios.get(base_url + 'produto?id_produto=' + id).then(res => {
        App.responseProduto(res.data)
    })
}

Services.carrinho = (App, id, token, values) => {
    axios.post(base_url + 'carrinho', {
        id_usuario: id,
        token: token,
        action: values.action,
        id_carrinho: values.id_carrinho,
        id_produto: values.id_produto,
        qntd: values.qntd
    }).then(res => {
        if (res.data) {
            App.responseCarrinho(res.data);
        }
    })
}

Services.checkout = (App, id, token, carrinho, endereco, values) => {
    axios.post(base_url + 'checkout', {
        id_usuario: id,
        token: token,
        id_carrinho: carrinho,
        id_endereco: endereco,
        action: values.action,
        cartao_numero: values.cartao_numero,
        cartao_nome: values.cartao_nome,
        cartao_cvv: values.cartao_cvv,
        cartao_vencimento: values.cartao_vencimento,
        observacoes: values.observacoes
    }).then(res => {
        if (res.data) {
            App.responseCheckout(res.data);
        }
    })
}

Services.pedidos = (App, id, token) => {
    axios.post(base_url + 'pedidos', { id_usuario: id, token: token }).then(res => {
        if (res.data) {
            App.responsePedidos(res.data);
        }
    })
}

Services.pedidoInterna = (App, user, token, id) => {
    axios.post(base_url + '/pedido', { id_usuario: user, token: token, id_pedido: id }).then(res => {
        App.responsePedidoInterna(res.data);
    })
}

Services.chamados = (App, user, token) =>{
    axios.post(base_url + '/chamados', { id_usuario: user, token: token }).then(res => {
        App.responseChamados(res.data);
    })
}



Services.chamadoInterna = (App, user, token, id) => {
    axios.post(base_url + '/chamado', { id_usuario: user, token: token, id_chamado: id }).then(res => {
        App.responseChamadoInterna(res.data);
    })
}

Services.chamadosCategoria = (App) => {
    axios.get(base_url + 'chamados/categorias').then(res => {
        
        App.responseChamadosCategoria(res.data);
    })
}

Services.cadastroChamados = (App, formData) => {
    axios({
        method: 'post',
        url: base_url+"chamados/cadastro",
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
    })
        .then(res => {
            App.responseCadastroChamados(res.data)
        })
        .catch(error => {
            App.responseCadastroChamados(error.data)
        });
}

Services.chamadosInteracao = (App, formData) => {
    axios({
        method: 'post',
        url: base_url+"chamados/interacao",
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
    })
        .then(res => {
            App.responseChamadosInteracao(res.data)
        })
        .catch(error => {
            App.responseChamadosInteracao(error.data)
        });
}


Services.chamadosAvaliacao = (App, user, token, chamado, nota) => {
    axios.post(base_url + 'chamados/avaliacao', { id_usuario: user, token: token,
         id_chamado: chamado, nota: nota }).then(res => {
        if (res.data) {
            App.responseChamadosAvaliacao(res.data);
        }
    })
}

Services.promocoes = (App) => {
    axios.get(base_url + 'promocoes').then(res => {
        App.responsePromocoes(res.data)
    })
}

// Services.promocaoInterna = (App, id) => {
//     axios.post(base_url + '/promocao', { id_promo: id }).then(res => {
//         App.responsePromocaoInterna(res.data);
//     })
// }

Services.promocaoInterna = (App, id) => {
    axios.get(base_url + 'promocao?id_promo=' + id).then(res => {
        App.responsePromocaoInterna(res.data);
    })
}

Services.pedidoMinimo = (App, id) => {
    axios.get(base_url + 'valor-minimo-pedido').then(res => {
        App.responsePedidoMinimo(res.data);
    })
}

Services.banners = (App) => {
    axios.get(base_url + 'banners').then(res => {
        App.responseBanners(res.data);
    })
}

Services.geolocalizacao = (App, id, token, lat, long) => {
    axios.post(base_url + 'usuarios/geolocalizacao', { id_usuario: id,
        token: token, latitude: lat.toString(), longitude:long.toString() }).then(res => {
    })
}

Services.pedidoCancelar = (App, user, token, id, obs) => {
    axios.post(base_url + 'pedidocancelar', { id_usuario: user, token: token, id_pedido: id, observacoes: obs}).then(res => {
        App.responsePedidoCancelar(res.data);
    })
}

// ENTREGADORES 

Services.loginEntregadores = (App, values) => {
    axios.post(base_url + 'entregadores/login', { user: values.auten, senha: values.senha }).then(res => {
        console.log(res.data)
        App.responseLogin(res.data)
    })
}

Services.entregadoresGeolocalizacao = (App, id, token, lat, long) => {
    axios.post(base_url + 'entregadores/geolocalizacao', { id_entregador: id,
        token: token, latitude: lat.toString(), longitude: long.toString() }).then(res => {
        App.responseEntregadoresGeolocalizacao(res.data)
    })
}

Services.pedidosEntregador = (App, id, token, status) => {
    axios.post(base_url + 'entregadores/pedidos', { id_entregador: id, token: token, status:status }).then(res => {
        if (res.data) {
            App.responsePedidos(res.data);
        }
    })
}

Services.pedidoInternaEntregador = (App, user, token, id) => {
    axios.post(base_url + 'entregadores/pedido', { id_entregador: user, token: token, id_pedido: id }).then(res => {
        App.responsePedidoInterna(res.data);
    })
}

Services.cancelarPedidoEntregador = (App, user, token, id, obs) => {
    axios.post(base_url + 'entregadores/pedidocancelar', { id_entregador: user, token: token, id_pedido: id, observacoes: obs}).then(res => {
        App.responsePedidoCancelar(res.data);
    })
}

Services.aceitarPedidoEntregador = (App, user, token, id) => {
    axios.post(base_url + 'entregadores/pedidoaceitar', { id_entregador: user, token: token, id_pedido: id}).then(res => {
        App.responsePedidoAceitar(res.data);
    })
}

Services.entregarPedidoEntregador = (App, user, token, id) => {
    axios.post(base_url + 'entregadores/pedidoconfirmarentrega', { id_entregador: user, token: token, id_pedido: id}).then(res => {
        App.responsePedidoEntregar(res.data);
    })
}

Services.dadosEntregador = (App, id, token) => {
    axios.post(base_url + 'entregador', { id_entregador: id, token: token }).then(res => {
        App.responseDadosEntregador(res.data);
    })
}

Services.estoque = (App, id, token) => {
    axios.post(base_url + 'entregadores/estoque', { id_entregador: id, token: token }).then(res => {
        App.responseEstoque(res.data);
    })
}

Services.statusEntregador = (App, id, token, status) => {
    axios.post(base_url + 'entregadores/status', { id_entregador: id, token: token, status_disponibilidade: status }).then(res => {
        App.responseStatusEntregador(res.data);
    })
}

Services.transferenciaEntregadores = (App, id, token) => {
    axios.post(base_url + 'transferencia/entregadores', { id_entregador: id, token: token }).then(res => {
        App.responseTransferenciaEntregadores(res.data);
    })
}

Services.transferenciaPedido = (App, id, token, pedido, msg, entregador) => {
    axios.post(base_url + 'transferencia/pedido', { id_entregador: id, token: token, id_pedido : pedido, 
        id_entregador_destinatario : entregador, observacoes : msg }).then(res => {
        App.responseTransferenciaPedido(res.data);
    })
}


Services.horarioFuncionamento = (App) => {
    axios.get(base_url + 'horariofuncionamento').then(res => {
        // console.log(res.data)
        App.responseHorarioFuncionamento(res.data);
    })
}

Services.horarioFuncionamento2 = (App) => {
    axios.get(base_url + 'horariofuncionamento').then(res => {
        // console.log(res.data)
        App.responseHorarioFuncionamento2(res.data);
    })
}

Services.oneSignal = (App, id, token, onesignal) => {
    axios.post(base_url + 'usuarios/onesignal', { id_usuario: id, token: token, id_onesignal: onesignal}).then(res => {
        // App.responseOneSignal()
    })
}


export default Services;