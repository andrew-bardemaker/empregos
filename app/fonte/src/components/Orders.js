import { Component } from 'react';
import Services from "../Services";

class OpenOrders extends Component {

    state = {
        pedidos:0,
    }

    componentDidMount() {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';
        let filtro = '1';

        Services.pedidosEntregador(this, usuario.id, token, filtro)
    }

    responsePedidos(response) {
          if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
          }
      
          if (response.success === 'true'){
            let tamanho = response.pedidos.length;
          this.setState({pedidos: tamanho})
          }
    }

    render() {
        return this.state.pedidos;
    }
}

export default OpenOrders;