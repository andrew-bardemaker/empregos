import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom';
import Grid from '@material-ui/core/Grid';
import Services from "../Services";

class Marcas extends Component {

    state = {

        marcas: [],
    }

    componentDidMount() {
        Services.produtosMarcas(this);
    }

    responseProdutosMarcas(response) {
        if (response.success === 'true') {
            this.setState({ marcas: response.produtos_marcas });
            localStorage.removeItem('id_marcasProduto');
        }
        else {
            this.setState({ marcas: [] });
        }
    }
    render() {
        return <Grid align="center" container spacing={1} direction="row"
            justifyContent="space-around"
            alignItems="center">
            {this.state.marcas.map((marcas) => {
                return <Grid item xs={4} lg={2} key={marcas.id}>
                    <Link to="/produtos" onClick={() => localStorage.setItem('id_marcasProduto', marcas.id)}>
                        <img className='imgMarcas' src={marcas.imagem_500x500} />
                    </Link>
                </Grid>
            })}
        </Grid>
    }
}

export default (Marcas);