import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
import Alert from '@material-ui/lab/Alert';
import { Component } from 'react';
import Services from "../Services";

class AlertOpen extends Component {

    state = {
        funcionamento: [],
    }

    componentDidMount() {
        Services.horarioFuncionamento(this);
    }

    responseHorarioFuncionamento = (response) => {
        if (response.success === 'true') {
            this.setState({ funcionamento: response })
        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    render() {
        if (this.state.funcionamento.status_aberto === '1' || this.state.funcionamento.status_aberto === 1) {
            return <Alert severity="success" className="w-100">
                {this.state.funcionamento.msg}
    </Alert>
        }
        else {
            return <Alert severity="error" className="w-100">{this.state.funcionamento.msg}</Alert>
        }
    }
}

export default AlertOpen;