import { Component } from 'react';
import Services from "../Services";

class Notification extends Component {

    state = {
        notificacoes:0}

    componentDidMount() {
        let usuario = JSON.parse(localStorage.getItem('user')) || '';
        let token = JSON.parse(localStorage.getItem('token')) || '';

        Services.notificacaoNaoLida(this, usuario.id, token)
    }

    responseNotificacaoNaoLida(response) {
        if (response.error === 'true' && response.type === 'token_invalido') {
            localStorage.setItem('token_invalido', 'ok')
            this.setState({ redirect: '#' });
            return
          }
      
          if (response.success === 'true'){
          this.setState({notificacoes: response.total})
          }
    }

    render() {
        return this.state.notificacoes;
    }
}

export default Notification;