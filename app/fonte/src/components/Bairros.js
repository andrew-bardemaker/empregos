
import React, { Component } from 'react';
import Services from "../Services";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMapPin } from '@fortawesome/free-solid-svg-icons'

class Bairros extends Component {

    state = {
        bairros: [],
    }

    componentDidMount() {
        Services.bairros(this);
    }

    responseBairros(response) {
        if (response.success === 'true') {
            this.setState({ bairros: response.bairros })

        }
        else {
            this.setState({ openAlert: true })
            this.setState({ alertMessage: "Opa!" + response.msg })
            this.setState({ alertStatus: 'error' })
            return
        }
    }

    render() {
        const { classes } = this.props;
        return <div>
            {this.state.bairros.map((bairro) => {
            return <span key={bairro.id}> <FontAwesomeIcon style={{color:'#FF8A00', marginRight: 5}} icon={faMapPin} />  {bairro.titulo} <br/></span>
        })}
        </div>
    }
}


export default (Bairros);