import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Alert from '@material-ui/lab/Alert';
import { Component } from 'react';
import Services from "../Services";


class AlertOrders extends Component {

    state = {
        pedidos: 0,
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

        if (response.success === 'true') {
            let tamanho = response.pedidos.length;
            this.setState({ pedidos: tamanho })

            if (this.state.pedidos > 0) {
                const EfectSound = new Audio('audio');
                this.playSound(EfectSound);
            }
        }
    }

    playSound = (audioFile) => {
        audioFile.play();
    };

    render() {
        if (this.state.pedidos > 0) {
            return <Alert severity="error" className="w-100">
                Há {this.state.pedidos} pedidos aguardando aprovação!
    </Alert>
        }
        else {
            return <Alert severity="success" className="w-100">Não há pedidos para aceitar!</Alert>
        }
    }
}

export default AlertOrders;