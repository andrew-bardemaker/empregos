import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom';
import { withStyles } from '@material-ui/core/styles';
import { Button, Grid} from '@material-ui/core';
import Services from "../Services";
import PropTypes from 'prop-types';
import { createTheme, ThemeProvider, StylesProvider } from '@material-ui/core/styles';
import { useStyles } from '../assets/estilos/pages/home';

class Filters extends Component {

    state = {

        categorias: [],
    }

    componentDidMount() {
        Services.produtosCategorias(this);
    }

    responseProdutosCategorias(response) {
        if (response.success === 'true') {
            this.setState({ categorias: response.produtos_categorias });
            localStorage.removeItem('id_categoriaProduto');
        }
        else {
            this.setState({ categorias: [] });
        }
    }
    render() {
        const { classes } = this.props;
        return <Grid container spacing={1}>
            {this.state.categorias.map((categorias) => {
                return <Grid item xs={6} lg={4} key={categorias.id}>
                    <Link to="./produtos" onClick={() => localStorage.setItem('id_categoriaProduto', categorias.id)}><Button
                        variant="outlined"
                        size="large"
                        color="secondary"
                        className={classes.button}
                    >
                        {categorias.titulo}
                    </Button></Link>
                </Grid>
            })}
        </Grid>
    }
}


Filters.propTypes = {
    classes: PropTypes.object.isRequired,
};

export default withStyles(useStyles)(Filters);